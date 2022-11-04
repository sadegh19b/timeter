<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TimeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'start_at' => ['required', 'date_format:Y-m-d H:i'],
            'end_at' => ['nullable', 'date_format:Y-m-d H:i', 'after:start_at']
        ];
    }

    public function attributes(): array
    {
        return [
            'start_at' => __('Start At'),
            'end_at' => __('End At')
        ];
    }

    public function messages(): array
    {
        return [
            'start_at.date_format' => __('The :attribute must be in the correct date format. ex: 2022-01-01 22:00'),
            'end_at.date_format' => __('The :attribute must be in the correct date format. ex: 2022-01-01 22:00'),
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
