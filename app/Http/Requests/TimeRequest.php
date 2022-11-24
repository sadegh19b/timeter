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
        $this->convertNowToDatetime();
    }

    private function convertPersianToGeorgianDatetime(): void
    {
        if (config('app.locale') === 'fa') {
            if (Str::startsWith($this->get('start_at'), ['13', '14'])) {
                $this->merge([
                    'start_at' => persian_to_georgian_datetime($this->get('start_at'), 'Y-m-d H:i')
                ]);
            }

            if (Str::startsWith($this->get('end_at'), ['13', '14'])) {
                $this->merge([
                    'end_at' => persian_to_georgian_datetime($this->get('end_at'), 'Y-m-d H:i')
                ]);
            }
        }
    }

    private function convertNowToDatetime(): void
    {
        $nowDateTime = now()->format('Y-m-d H:i');

        if ($this->get('start_at') === 'now' && $this->get('end_at') === 'now') {
            $this->merge([
                'start_at' => $nowDateTime,
                'end_at' => now()->addMinute()->format('Y-m-d H:i')
            ]);

            return;
        }

        if (
            $this->get('end_at') === 'now' &&
            $this->get('start_at') !== 'now' &&
            $this->get('start_at') === $nowDateTime
        ) {
            $this->merge([
                'end_at' => now()->addMinute()->format('Y-m-d H:i')
            ]);

            return;
        }

        if ($this->get('start_at') === 'now') {
            $this->merge([
                'start_at' => $nowDateTime
            ]);
        }

        if ($this->get('end_at') === 'now') {
            $this->merge([
                'end_at' => $nowDateTime
            ]);
        }
    }

    public function authorize(): bool
    {
        return true;
    }
}
