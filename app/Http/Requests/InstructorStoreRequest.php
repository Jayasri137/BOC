<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class InstructorStoreRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return array_merge(
            // user rules
            [
                'name' => 'required|string|max:50',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|string|max:20',
                // 'password' => 'nullable|confirmed|min:8',
                'profile_picture' => 'nullable|image|max:2048',
                'is_active' => 'sometimes|boolean',
            ],
            // instructor rules
            [
                'title' => 'nullable|string|max:60',
                // 'about' => 'nullable|string',
                // 'is_featured' => 'sometimes|boolean',
            ]
        );
    }
}