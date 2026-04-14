<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'selected_options'   => ['required', 'array', 'min:1'],
            'selected_options.*' => ['required', 'string'],
            'direction'          => ['nullable', 'in:prev,next'],
        ];
    }

    public function messages(): array
    {
        return [
            'selected_options.required' => '請選擇一個選項後再繼續。',
            'selected_options.min'      => '請選擇一個選項後再繼續。',
        ];
    }
}
