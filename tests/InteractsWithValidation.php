<?php

namespace Tests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

trait InteractsWithValidation
{
    protected function assertPass(array $data, FormRequest $formRequest, string $field, string $rule): void
    {
        $this->assertTrue(
            $this->isValid($data, $formRequest, $field, $rule),
            "Assert the field '$field' with '$rule' rule to pass, but it didn't.\r\n"
            . $this->validationInformation($data, $formRequest->rules()[$field], $field, $rule)
        );
    }

    protected function assertFail(array $data, FormRequest $formRequest, string $field, string $rule): void
    {
        $this->assertFalse(
            $this->isValid($data, $formRequest, $field, $rule),
            "Assert the field '$field' with '$rule' rule to fail, but it didn't.\r\n"
            . $this->validationInformation($data, $formRequest->rules()[$field], $field, $rule)
        );
    }

    protected function assertPasses(array $data, FormRequest $formRequest): void
    {
        $this->assertTrue(
            $this->isValid($data, $formRequest),
            "Assert fields to passes, but it didn't.\r\nThe failed fields are:\r\n"
            . $this->validationFailedFields($data, $formRequest->rules())
        );
    }

    protected function assertFails(array $data, FormRequest $formRequest): void
    {
        $this->assertFalse($this->isValid($data, $formRequest), "Assert fields to fails, but it's passed.");
    }

    private function isValid(array $data, FormRequest $formRequest, string $field = null, string $rule = null): bool
    {
        $rules = $formRequest->rules();
        $validator = Validator::make($data, $rules);

        if (!is_null($field) && !array_key_exists($field, $rules)) {
            $this->fail(
                "Assert the field '$field' to fail, but it didn't.\r\nThe failed fields are:\r\n"
                . $this->validationFailedFields($data, $rules)
            );
        }

        if (!is_null($rule) && !in_array($rule, $this->baseNameOfValidationRules($rules[$field]), true)) {
            $this->fail(
                "Assert the rule to be '$rule' for '$field' but it's not in the failed rules.\r\nThe failed rules are:\r\n\t"
                . $this->validationFailedRules($data, $rules, $field)
            );
        }

        if (!is_null($field) && $validator->fails() && !array_key_exists($field, $validator->failed())) {
            return true;
        }

        return !$validator->fails();
    }

    private function normalizeValidationRules(array $rules): array
    {
        return array_map([Str::class, 'snake'], array_keys($rules));
    }

    private function validationInformation(array $data, array $rules, string $field, string $rule): string
    {
        $result = '';

        foreach ($data as $key => $value) {
            if ($field === $key) {
                $result .= "Field: [".$key.' => '.$value.']';
            }
        }

        foreach ($rules as $value) {
            if (Str::contains($value, $rule)) {
                $result .= "\nRule:  [".$value."]\n";
            }
        }

        return $result;
    }

    private function validationFailedFields(array $data, array $rules): string
    {
        $validator = Validator::make($data, $rules);
        $result = '';

        if ($validator->fails()) {
            foreach ($validator->failed() as $key => $_rules) {
                $result .= "\t".$key.' => '.implode(' | ', $this->normalizeValidationRules($_rules))."\r\n";
            }
        }

        return $result;
    }

    private function validationFailedRules(array $data, array $rules, string $field): string
    {
        $validator = Validator::make($data, $rules);
        $result = '';

        if ($validator->fails() && isset($validator->failed()[$field])) {
            $result .= "\t".implode(' | ', $this->normalizeValidationRules($validator->failed()[$field]))."\r\n";
        }

        return $result;
    }

    private function baseNameOfValidationRules(array $rules): array
    {
        $result = [];

        foreach ($rules as $rule) {
            $result[] = explode(':', $rule)[0];
        }

        return $result;
    }
}
