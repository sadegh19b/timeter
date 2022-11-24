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
            'hourly_wage' => ['nullable', 'numeric', 'min:1', 'max:100000000'],
            'use_persian_datetime_in_statistic' => ['boolean']
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('Project Name'),
            'description' => __('Project Description'),
            'hourly_wage' => __('Hourly Wage')
        ];
    }

    public function messages(): array
    {
        return [
            'hourly_wage.max' => __('Hourly wage can be a maximum of one hundred million.'),
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->cleanHourlyWage();
    }

    private function cleanHourlyWage(): void
    {
        if ($this->has('hourly_wage') && !$this->isEmptyString('hourly_wage')) {
            $this->merge([
                'hourly_wage' => str_replace(',', '', $this->get('hourly_wage'))
            ]);
        }
    }

    public function authorize(): bool
    {
        return true;
    }
}
