<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Str;

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

    protected function prepareForValidation(): void
    {
        $this->convertPersianToGeorgianDatetime();
    }

    private function convertPersianToGeorgianDatetime(): void
    {
        if (config('app.locale') === 'fa') {
            if (Str::startsWith($this->request->get('start_at'), '1')) {
                $this->merge([
                    'start_at' => persian_to_georgian_datetime($this->request->get('start_at'), 'Y-m-d H:i')
                ]);
            }

            if (Str::startsWith($this->request->get('end_at'), '1')) {
                $this->merge([
                    'end_at' => persian_to_georgian_datetime($this->request->get('end_at'), 'Y-m-d H:i')
                ]);
            }
        }
    }

    public function authorize(): bool
    {
        return true;
    }
}
