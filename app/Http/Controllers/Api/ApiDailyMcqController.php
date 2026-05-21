<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DailyMcq;
use App\Models\DailyMcqSubmission;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\CurrentAffair;


class ApiDailyMcqController extends Controller
{
    
    
        public function index($date, Request $request)
        {
                // Normalize input (accept dd/mm/yyyy, dd-mm-yyyy or yyyy-mm-dd)
                $normalized = null;
                
                // Try common formats
                $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'd.m.Y', 'Y/m/d'];
                
                foreach ($formats as $fmt) {
                    try {
                        $c = Carbon::createFromFormat($fmt, $date);
                        if ($c && $c->format($fmt) === $date) {
                            $normalized = $c->format('Y-m-d');
                            break;
                        }
                    } catch (\Throwable $e) {
                        // skip
                    }
                }
                
                // If still null, try loose parse (best-effort)
                if (!$normalized) {
                    try {
                        $c = new Carbon($date);
                        $normalized = $c->format('Y-m-d');
                    } catch (\Throwable $e) {
                        return response()->json([
                            'error' => 'Invalid date format. Provide date as YYYY-MM-DD or DD/MM/YYYY.',
                            'provided' => $date
                        ], 422);
                    }
                }
                
                // Query DB by date column (use whereDate for DATE or datetime columns)
                $mcqs = DailyMcq::with(['questions.options'])
                         ->whereDate('date', $normalized)
                         ->get();
                
                // Optional: log for debugging
                Log::debug('API getMsq queried', [
                    'provided' => $date,
                    'normalized' => $normalized,
                    'count' => $mcqs->count()
                ]);
             
         
                return response()->json([
                    'requested_date' => $date,
                    'normalized_date' => $normalized,
                    'data' => $mcqs
                ]);
        }


            public function submit($id, Request $request)
            {
                $data = $request->validate([
                    'student_name'  => 'required|string|max:191',
                    'student_phone' => 'nullable|string|max:50',
                    'student_email' => 'nullable|email|max:191',
                    // 'answers'       => 'nullable|array',
                ]);
                    
                $mcq = DailyMcq::find($id);
                if (!$mcq) {
                    return response()->json(['error' => 'MCQ not found'], 404);
                }
            
                $submission = DailyMcqSubmission::create([
                    'daily_mcq_id' => $mcq->id,
                    'student_name' => $data['student_name'],
                    'student_phone'=> $data['student_phone'] ?? null,
                    'student_email'=> $data['student_email'] ?? null,
                    // 'answers'      => $data['answers'] ?? null,
                    'ip_address'   => $request->ip(),
                    'user_agent'   => substr($request->header('User-Agent') ?? '', 0, 255),
                ]);
            
                return response()->json([
                    'message' => 'Submission saved',
                    'submission_id' => $submission->id,
                    'mcq'=>$mcq,
                ], 201);
            }



          
        public function submitAnswr($id, Request $request)
        {
            
         
            $data = $request->validate([
                'student_name'  => 'required|string|max:191',
                'student_phone' => 'nullable|string|max:50',
                'student_email' => 'nullable|email|max:191',
                'answers'       => 'required|array',
            ]);
        
            $mcq = DailyMcq::with(['questions.options'])->find($id);
            if (! $mcq) {
                return response()->json(['error' => 'MCQ not found'], 404);
            }
        
            // marks per question (fallback to 1)
            $markPerQuestion = (int) ($mcq->mark_per_question ?? 1);
        
            $studentAnswers = $data['answers']; // associative: question_id => {type, selected}
            $totalQuestions = $mcq->questions->count();
            $totalMarksPossible = $totalQuestions * $markPerQuestion;
        
            $score = 0;
            $results = [];
        
            DB::beginTransaction();
            try {
                foreach ($mcq->questions as $question) {
                    $qid = (string) $question->id;
                    $qType = $question->type;
                    $studentEntry = $studentAnswers[$qid] ?? null;
        
                    $studentSelected = $studentEntry['selected'] ?? null;
                    // normalize selected to array for uniform processing (for mcq multi/single)
                    $studentSelectedArr = null;
                    if ($qType === 'mcq') {
                        if (is_null($studentSelected)) {
                            $studentSelectedArr = [];
                        } elseif (is_array($studentSelected)) {
                            $studentSelectedArr = array_map('intval', $studentSelected);
                        } else {
                            // could be option id or 1-based index
                            $studentSelectedArr = [(int)$studentSelected];
                        }
                    }
        
                    // Build correct answers from DB for this question
                    $correctOptionIds = [];
                    if ($qType === 'mcq') {
                        $correctOptionIds = $question->options->where('is_correct', 1)->pluck('id')->map(fn($v)=> (int)$v)->toArray();
                    }
        
                    $isCorrect = false;
                    $marksObtained = 0;
        
                    switch ($qType) {
                        case 'mcq':
                            // Student may have sent option id(s) or index number(s).
                            // Detect whether student's numbers match option IDs; if not, treat as 1-based index into options.
                            $optionsCollection = $question->options->values(); // reindex 0..n-1
        
                            // try to detect whether provided selected values are option IDs
                            $mappedStudentOptionIds = [];
                            foreach ($studentSelectedArr as $val) {
                                if ($optionsCollection->contains('id', $val)) {
                                    // it's an option id
                                    $mappedStudentOptionIds[] = (int)$val;
                                } else {
                                    // maybe it's a 1-based index: 1 -> options[0]
                                    $index = ((int)$val) - 1;
                                    if (isset($optionsCollection[$index])) {
                                        $mappedStudentOptionIds[] = (int)$optionsCollection[$index]->id;
                                    }
                                }
                            }
        
                            // normalize unique ints
                            $mappedStudentOptionIds = array_values(array_unique(array_map('intval', $mappedStudentOptionIds)));
        
                            // grading logic:
                            // - if multiple correct options in DB, require exact set match for full marks
                            // - if single correct option, check membership
                            if (count($correctOptionIds) > 1) {
                                sort($mappedStudentOptionIds); sort($correctOptionIds);
                                $isCorrect = ($mappedStudentOptionIds === $correctOptionIds);
                            } else {
                                $isCorrect = in_array($mappedStudentOptionIds[0] ?? null, $correctOptionIds, true);
                            }
                            break;
        
                        case 'true_false':
                            $stored = is_null($question->answer) ? null : strtolower(trim($question->answer));
                            $given = is_null($studentSelected) ? null : strtolower(trim((string)$studentSelected));
                            $isCorrect = ($stored !== null && $given !== null && $stored === $given);
                            break;
        
                        case 'fill_blank':
                            $stored = is_null($question->answer) ? null : mb_strtolower(trim($question->answer));
                            $given = is_null($studentSelected) ? null : mb_strtolower(trim((string)$studentSelected));
                            $isCorrect = ($stored !== null && $given !== null && $stored === $given);
                            break;
        
                        case 'assertion_reason':
                            $stored = $question->answer;
                            $given = is_null($studentSelected) ? null : (string)$studentSelected;
                            $isCorrect = ($stored !== null && $given !== null && $stored === $given);
                            break;
        
                        case 'statement':
                            $stored = is_null($question->answer) ? null : mb_strtolower(trim($question->answer));
                            $given = is_null($studentSelected) ? null : mb_strtolower(trim((string)$studentSelected));
                            $isCorrect = ($stored !== null && $given !== null && $stored === $given);
                            break;
        
                        default:
                            $isCorrect = false;
                    }
        
                    if ($isCorrect) {
                        $marksObtained = $markPerQuestion;
                        $score += $marksObtained;
                    }
        
                    // Save human-friendly student-selected representation for response/storage
                    $studentReadable = $studentSelected;
                    if ($qType === 'mcq') {
                        // convert studentSelectedArr/mappedStudentOptionIds to option texts for response clarity
                        $selectedOptionTexts = [];
                        foreach (($mappedStudentOptionIds ?? []) as $optId) {
                            $opt = $question->options->firstWhere('id', $optId);
                            if ($opt) $selectedOptionTexts[] = $opt->option_text;
                        }
                        $studentReadable = [
                            'selected_option_ids' => $mappedStudentOptionIds ?? [],
                            'selected_option_texts' => $selectedOptionTexts
                        ];
                    }
        
                    $results[$qid] = [
                        'question_id' => (int)$qid,
                        'type' => $qType,
                        'student_selected_raw' => $studentEntry,   // raw payload per question
                        'student_selected' => $studentReadable,
                        'is_correct' => (bool)$isCorrect,
                        'marks_obtained' => $marksObtained,
                        'marks_total' => $markPerQuestion,
                        'correct_option_ids' => ($qType === 'mcq') ? $correctOptionIds : null, // include only for admin debugging; remove for student
                    ];
                }
        
                // persist submission
                $submission = DailyMcqSubmission::create([
                    'daily_mcq_id' => $mcq->id,
                    'student_name' => $data['student_name'],
                    'student_phone'=> $data['student_phone'] ?? null,
                    'student_email'=> $data['student_email'] ?? null,
                    'answers'      => $studentAnswers,
                    'score'        => $score,
                    'ip_address'   => $request->ip(),
                    'user_agent'   => substr($request->header('User-Agent') ?? '', 0, 255),
                ]);
        
                DB::commit();
        
                return response()->json([
                    'message' => 'Submission saved and graded',
                    'submission_id' => $submission->id,
                    'mcq_id' => $mcq->id,
                    'score' => $score,
                    'total_marks' => $totalMarksPossible,
                    'percentage' => $totalMarksPossible ? round(($score / $totalMarksPossible) * 100, 2) : 0,
                    'per_question' => array_values($results),
                ], 201);
            } catch (\Throwable $e) {
                DB::rollBack();
                Log::error('Grading error: '.$e->getMessage(), ['exception' => $e, 'request' => $request->all()]);
                return response()->json(['error' => 'Failed to grade/save submission'], 500);
            }
        }
                    
            
    public function show($id, Request $request)
    {
        // Find the MCQ with questions and options
        $mcq = DailyMcq::with(['questions.options'])->find($id);

        if (! $mcq) {
            return response()->json([
                'status' => 'error',
                'message' => 'MCQ not found'
            ], 404);
        }

        // Optionally transform/pick only fields you want
        $payload = [
            'id' => $mcq->id,
            'date' => $mcq->date,
            'title' => $mcq->title,
            'duration' => $mcq->duration,
            'instructions' => $mcq->instructions,
            'is_published' => (bool)$mcq->is_published,
            'questions' => $mcq->questions->map(function ($q) {
                return [
                    'id' => $q->id,
                    'type' => $q->type,
                    'negative_score' => $q->negative_score,
                    'question_text' => $q->question_text,
                    'assertion' => $q->assertion,
                    'reason' => $q->reason,
                    'answer' => $q->answer,           // for TF / fill_blank / assertion answers if stored
                    'order_no' => $q->order_no,
                    'options' => $q->options->map(function ($opt) {
                        return [
                            'id' => $opt->id,
                            'option_text' => $opt->option_text,
                            // DO NOT return is_correct to the client if you don't want to reveal answers.
                            // 'is_correct' => (bool) $opt->is_correct,
                        ];
                    })
                ];
            }),
        ];
        
        // echo '<pre>';
        // print_r($payload);
        // echo '</pre>';
        
        return response()->json([
            'status' => 'success',
            'data' => $payload
        ]);
    }


  public function showPdf($id, Request $request)
    {
        // Find the MCQ with questions and options
        $mcq = DailyMcq::with(['questions.options'])->find($id);

        // echo '<pre>';
        // print_r($mcq);
        // echo '</pre>';

        if (! $mcq) {
            return response()->json([
                'status' => 'error',
                'message' => 'MCQ not found'
            ], 404);
        }

        // Optionally transform/pick only fields you want
        $payload = [
            'id' => $mcq->id,
            'date' => $mcq->date,
            'title' => $mcq->title,
            'duration' => $mcq->duration,
            'instructions' => $mcq->instructions,
            'is_published' => (bool)$mcq->is_published,
            'pdf' => $mcq->pdf_file

        ];
        
        // echo '<pre>';
        // print_r($payload);
        // echo '</pre>';
        
        return response()->json([
            'status' => 'success',
            'data' => $payload
        ]);
    }


    
    public function indexOld(Request $request)
    {
        // eager load relations you need (media, user) to avoid N+1
        $blogs = Blog::with(['media', 'user'])->latest()->get();

        $payload = $blogs->map(function ($blog) {
            return [
                'id'          => $blog->id,
                'title'       => $blog->title,
                'slug'        => $blog->slug, // uses Blog::getSlugAttribute()
                'description' => $blog->excerpt, // uses Blog::getExcerptAttribute()
                'content'     => $blog->content, // Blog::getContentAttribute() or column
                'image'       => $blog->media_path, // Blog::getMediaPathAttribute()
                'media_id'    => $blog->media_id ?? null,
                'author_name' => $blog->author_name ?? ($blog->user->name ?? 'Unknown'),
                'status'      => (bool) $blog->status,
                'created_at'  => optional($blog->created_at)->toDateTimeString(),
            ];
        });

        return response()->json([
            'success' => true,
            'data'    => $payload,
        ], 200);
    }
    
    public function getAffairsDate($date, Request $request)
{
    
    try {
        // This automatically accepts:
        // YYYY-MM-DD, DD/MM/YYYY, DD-MM-YYYY, etc.
        $normalized = Carbon::parse($date)->format('Y-m-d');
    } catch (\Throwable $e) {
        return response()->json([
            'error' => 'Invalid date format. Provide YYYY-MM-DD or DD/MM/YYYY.',
            'provided' => $date
        ], 422);
    }

    // Fetch data
    $mcqs = CurrentAffair::whereDate('date', $normalized)->get();

    return response()->json([
        'requested_date' => $date,
        'normalized_date' => $normalized,
        'data' => $mcqs
    ]);
    
  
}


   
}
