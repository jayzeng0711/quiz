<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StartQuizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quiz_id' => ['required', 'integer', 'exists:quizzes,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'quiz_id.required' => '請選擇測驗。',
            'quiz_id.exists'   => '測驗不存在，請重新整理頁面。',
        ];
    }
}
