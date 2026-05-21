<?php

namespace App\Http\Controllers\WebAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DailyMcq;
use App\Models\DailyMcqSubmission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class DailyMcqController extends Controller
{
    // Daily MCQ List page
   // Controller example
public function index(Request $request)
{
    $query = DailyMcq::with('questions');

    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }
    if ($request->filled('date')) {
        $query->whereDate('created_at', $request->date);
    }

    // use paginate instead of get()
    $mcqs = $query->paginate(10)->withQueryString();

    return view('dailyMcq.index', compact('mcqs'));
}


    // Create MCQ
    public function create()
    {
        return view('dailyMcq.create');
    }

    // Edit MCQ
  public function edit($id)
{
   $mcq = DailyMcq::with(['questions.options'])->findOrFail($id);

    return view('dailyMcq.edit', compact('mcq'));
}

    // Select Course
    public function selectCourse()
    {
        return view('dailyMcq.select_course');
    }
    public function list()
    {
        // Example query – update according to your database tables
      // Eager-load related MCQ (if you have a DailyMcq model and relation)
  $submissions = \DB::table('daily_mcq_submissions as s')
    ->leftJoin('daily_mcqs as d', 's.daily_mcq_id', '=', 'd.id')
    ->select('s.*', 'd.title as exam_title')
    ->orderBy('s.id','desc')
    ->paginate(25);
    
        return view('dailyMcq.student-list', compact('submissions'));
    }
    
    public function store(Request $request)
    {
        // validate top-level fields
        $validated = $request->validate([
            'date' => 'required|date',
            'title' => 'required|string|max:255',
            'duration' => 'required|integer|min:1',
            'mark_per_question' => 'nullable|integer|min:0',
            'pass_marks' => 'nullable|integer|min:0',
            'instructions' => 'nullable|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:5120',
            'is_published' => 'nullable',
            'questions' => 'nullable|array',
            'questions.*.type' => 'required_with:questions|string',
            // you can add more fine-grained rules if you want
        ]);
    
        DB::beginTransaction();
    
        try {
            // create MCQ header
            $mcq = \App\Models\DailyMcq::create([
                'date' => $validated['date'],
                'title' => $validated['title'],
                'duration' => $validated['duration'],
                'mark_per_question' => $validated['mark_per_question'] ?? null,
                'pass_marks' => $validated['pass_marks'] ?? null,
                'instructions' => $validated['instructions'] ?? null,
                'pdf_file' => $request->hasFile('pdf_file') ? $request->file('pdf_file')->store('daily-mcq-pdf','public') : null,
                'is_published' => $request->has('is_published') && $request->is_published ? 1 : 0,
            ]);
    
            // Safely iterate questions (reindex to numeric array)
            $questions = $request->input('questions', []);
            foreach (array_values($questions) as $idx => $q) {
    
                // defensive: skip if type missing
                if (empty($q['type'])) {
                    Log::warning("Skipping question #{$idx} due to missing type", ['question' => $q]);
                    continue;
                }
    
                // prepare question fields (handle different question types)
                $questionData = [
                    'type' => $q['type'],
                    'question_text' => $q['text'] ?? $q['sentence'] ?? $q['question'] ?? null,
                    'assertion' => $q['assertion'] ?? null,
                    'reason' => $q['reason'] ?? null,
                    'answer' => null,
                    'order_no' => $q['order_no'] ?? ($idx + 1),
                ];
    
                // If true_false, answer may be in $q['answer'] already (true/false)
                if (isset($q['answer'])) {
                    // normalize boolean-ish answers to string or 1/0 as you prefer
                    $questionData['answer'] = is_bool($q['answer']) ? ($q['answer'] ? 'true' : 'false') : (string)$q['answer'];
                } else if (isset($q['type']) && $q['type'] === 'fill_blank' && isset($q['answer'])) {
                    $questionData['answer'] = (string)$q['answer'];
                } else if (isset($q['type']) && $q['type'] === 'assertion_reason' && isset($q['answer'])) {
                    $questionData['answer'] = (string)$q['answer'];
                }
    
                // create question (ensure DailyMcqQuestion model has fillable)
                $question = $mcq->questions()->create($questionData);
    
                // If MCQ type, save options. handle variety of option shapes.
                if ($q['type'] === 'mcq' && !empty($q['options']) && is_array($q['options'])) {
                    foreach ($q['options'] as $optIndex => $opt) {
                        // opt may be string or array. handle both:
                        if (is_string($opt)) {
                            $optionText = $opt;
                            $isCorrect = false;
                        } elseif (is_array($opt)) {
                            // expected structure: ['text' => '...', 'is_correct' => 'on'|'1'|true]
                            $optionText = $opt['text'] ?? ($opt['option_text'] ?? null);
                            $isCorrect = isset($opt['is_correct']) && ($opt['is_correct'] === 'on' || $opt['is_correct'] === '1' || $opt['is_correct'] === 1 || $opt['is_correct'] === true);
                        } else {
                            continue; // unknown option shape
                        }
    
                        if ($optionText === null) {
                            Log::warning("Skipping empty option for question {$question->id}", ['opt' => $opt]);
                            continue;
                        }
    
                        $question->options()->create([
                            'option_text' => $optionText,
                            'is_correct' => $isCorrect ? 1 : 0,
                        ]);
                    }
                }
            }
    
            DB::commit();
    
            return redirect()->route('dailyMcq.index')->with('success', 'Daily MCQ created successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            // Log the full exception for debugging
            Log::error('Daily MCQ store failed: '.$e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
            ]);
    
            // Optionally, for debug only, you can re-throw or dd($e)
            // For user-friendly message:
            return back()->withInput()->withErrors(['general' => 'Something went wrong while saving the MCQ. Check logs.']);
        }
    }
    
    
    public function update(Request $request, $id)
        {
            // Basic validation — extend as needed
            $request->validate([
                'date' => 'required|date',
                'title' => 'required|string|max:255',
                'duration' => 'required|integer|min:1',
                'mark_per_question' => 'nullable|integer|min:0',
                'pass_marks' => 'nullable|integer|min:0',
                'instructions' => 'nullable|string',
                'pdf_file' => 'nullable|file|mimes:pdf|max:5120',
                'questions' => 'nullable|array',
                'questions.*.type' => 'required_with:questions|string',
            ]);
        
            DB::beginTransaction();
        
            try {
                // Load mcq with existing questions + options
                $mcq = DailyMcq::with(['questions.options'])->findOrFail($id);
        
                // Update header fields
                $mcq->update([
                    'date' => $request->date,
                    'title' => $request->title,
                    'duration' => $request->duration,
                    'mark_per_question' => $request->mark_per_question,
                    'pass_marks' => $request->pass_marks,
                    'instructions' => $request->instructions,
                    'is_published' => $request->has('is_published') && $request->is_published ? 1 : 0,
                ]);
        
                // Handle file upload if any
                if ($request->hasFile('pdf_file')) {
                    $path = $request->file('pdf_file')->store('daily-mcq-pdf', 'public');
                    $mcq->pdf_file = $path;
                    $mcq->save();
                }
        
                $postedQuestions = $request->input('questions', []);
        
                // Track IDs present in form so we can delete removed ones later
                $formQuestionIds = [];
        
                foreach ($postedQuestions as $formIndex => $qData) {
                    // Normalize incoming values
                    $qType = $qData['type'] ?? null;
                    $qId = isset($qData['id']) && $qData['id'] ? (int)$qData['id'] : null;
        
                    $questionAttrs = [
                        'type' => $qType,
                        'question_text' => $qData['text'] ?? $qData['sentence'] ?? $qData['question'] ?? null,
                        'assertion' => $qData['assertion'] ?? null,
                        'reason' => $qData['reason'] ?? null,
                        'answer' => isset($qData['answer']) ? (string)$qData['answer'] : null,
                        'order_no' => $qData['order_no'] ?? null,
                    ];
        
                    if ($qId) {
                        // Update existing question if found, otherwise create new
                        $question = $mcq->questions()->where('id', $qId)->first();
                        if ($question) {
                            $question->update($questionAttrs);
                        } else {
                            $question = $mcq->questions()->create($questionAttrs);
                        }
                    } else {
                        // New question
                        $question = $mcq->questions()->create($questionAttrs);
                    }
        
                    $formQuestionIds[] = $question->id;
        
                    // Handle options for MCQ type
                    if ($qType === 'mcq') {
                        $formOptionIds = [];
                        $options = $qData['options'] ?? [];
        
                        // Ensure $options is an array and iterate
                        if (is_array($options)) {
                            foreach ($options as $optIndex => $optData) {
                                $optId = isset($optData['id']) && $optData['id'] ? (int)$optData['id'] : null;
                                $optText = $optData['text'] ?? $optData['option_text'] ?? null;
                                $isCorrect = isset($optData['is_correct']) && ($optData['is_correct'] === '1' || $optData['is_correct'] === 1 || $optData['is_correct'] === 'on' || $optData['is_correct'] === true) ? 1 : 0;
        
                                if ($optId) {
                                    $option = $question->options()->where('id', $optId)->first();
                                    if ($option) {
                                        $option->update([
                                            'option_text' => $optText,
                                            'is_correct' => $isCorrect,
                                        ]);
                                    } else {
                                        $option = $question->options()->create([
                                            'option_text' => $optText,
                                            'is_correct' => $isCorrect,
                                        ]);
                                    }
                                } else {
                                    $option = $question->options()->create([
                                        'option_text' => $optText,
                                        'is_correct' => $isCorrect,
                                    ]);
                                }
        
                                $formOptionIds[] = $option->id;
                            }
        
                            // Delete any options removed in the form
                            $existingOptionIds = $question->options()->pluck('id')->toArray();
                            $toDeleteOptionIds = array_diff($existingOptionIds, $formOptionIds);
                            if (!empty($toDeleteOptionIds)) {
                                $question->options()->whereIn('id', $toDeleteOptionIds)->delete();
                            }
                        }
                    } else {
                        // Not MCQ type: remove any existing options (optional)
                        if ($question->options()->count()) {
                            $question->options()->delete();
                        }
                    }
                }
        
                // Delete any questions removed in the form
                $existingQuestionIds = $mcq->questions()->pluck('id')->toArray();
                $toDeleteQuestionIds = array_diff($existingQuestionIds, $formQuestionIds);
                if (!empty($toDeleteQuestionIds)) {
                    // delete options for those questions first (FK constraints)
                    DailyMcqQuestionOption::whereIn('daily_mcq_question_id', $toDeleteQuestionIds)->delete();
                    DailyMcqQuestion::whereIn('id', $toDeleteQuestionIds)->delete();
                }
        
                DB::commit();
        
                return redirect()->route('dailyMcq.index')->with('success', 'Daily MCQ updated successfully.');
            } catch (\Throwable $e) {
                DB::rollBack();
                Log::error('DailyMcq update failed: '.$e->getMessage(), [
                    'exception' => $e,
                    'request' => $request->all(),
                ]);
        
                return back()->withInput()->withErrors(['general' => 'Failed to update MCQ. Check logs.']);
            }
        }
        
        
        
        public function destroy($id)
        {
           DB::beginTransaction();
            try {
                $mcq = \App\Models\DailyMcq::with('questions.options')->findOrFail($id);
        
                $questionIds = $mcq->questions()->pluck('id')->toArray();
                if (!empty($questionIds)) {
                    \App\Models\DailyMcqQuestionOption::whereIn('daily_mcq_question_id', $questionIds)->delete();
                    \App\Models\DailyMcqQuestion::whereIn('id', $questionIds)->delete();
                }
        
                $mcq->delete();
        
                DB::commit();
                return redirect()->route('dailyMcq.index')->with('success', 'Daily MCQ deleted successfully.');
            } catch (\Throwable $e) {
                DB::rollBack();
                \Illuminate\Support\Facades\Log::error("Failed to delete DailyMcq id={$id}: ".$e->getMessage(), ['exception' => $e]);
                return back()->withErrors(['general' => 'Failed to delete MCQ. Check logs.']);
            }
        }



}
