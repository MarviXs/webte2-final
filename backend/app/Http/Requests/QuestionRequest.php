<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class QuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'question_text' => 'required|string',
            'question_type' => 'required|string|in:single_choice,multiple_choice,open',
            'is_active' => 'boolean',
            'subject' => 'nullable|string',
        ];

        if (Auth::user() && Auth::user()->isAdmin()) {
            $rules['owner_id'] = ['nullable', 'uuid', Rule::exists('users', 'id')];
        }

        return $rules;
    }
}
