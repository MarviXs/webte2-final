<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question_text' => 'required|string',
            'question_type' => 'required|string|in:choice,open',
            'is_active' => 'boolean',
            'subject' => 'required|string',
        ];
    }
}
