<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PdfUploadStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Define your validation rules
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'serial_number' => 'required|string|max:255',
        ];
    }
}
