<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LiveClassStoreRequest extends FormRequest
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
            'date' => 'required|date',
            // 'instructor_id' => 'required|exists:users,id',
        ];
    }
}
