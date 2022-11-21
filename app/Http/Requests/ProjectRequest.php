<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:2', 'max:120'],
            'description' => ['nullable', 'min:5'],
            'pay_per_hour' => ['nullable', 'numeric', 'min:1', 'max:100000000'],
            'use_persian_datetime_in_statistic' => ['boolean']
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('Project Name'),
            'description' => __('Project Description'),
            'pay_per_hour' => __('Pay Per Hour')
        ];
    }

    public function messages(): array
    {
        return [
            'pay_per_hour.max' => __('Pay Per Hour can be a maximum of one hundred million.'),
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->cleanPayPerHour();
    }

    private function cleanPayPerHour(): void
    {
        if ($this->request->has('pay_per_hour') && !empty($this->request->get('pay_per_hour'))) {
            $this->merge([
                'pay_per_hour' => str_replace(',', '', $this->request->get('pay_per_hour'))
            ]);
        }
    }

    public function authorize(): bool
    {
        return true;
    }
}
