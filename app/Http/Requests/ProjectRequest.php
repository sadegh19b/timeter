<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'description' => ['nullable', 'string', 'min:5'],
            'pay_per_hour' => ['nullable', 'numeric', 'min:1', 'max:100000000']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function attributes(): array
    {
        return [
            'name' => __('Project Name'),
            'description' => __('Project Description'),
            'pay_per_hour' => __('Pay Per Hour')
        ];
    }

    protected function getValidatorInstance(): \Illuminate\Contracts\Validation\Validator
    {
        $this->cleanPayPerHour();
        return parent::getValidatorInstance();
    }

    private function cleanPayPerHour(): void
    {
        if ($this->request->has('pay_per_hour') && !empty($this->request->get('pay_per_hour'))) {
            $this->merge([
                'pay_per_hour' => str_replace(',', '', $this->request->get('pay_per_hour'))
            ]);
        }
    }
}
