<?php

namespace Tests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // Validation methods
    protected function assertFail(array $data, FormRequest $formRequest, string $field, string $rule): void
    {
        $this->assertFalse($this->isvalid($data, $formRequest, $field, $rule));
    }

    protected function assertPass(array $data, FormRequest $formRequest): void
    {
        $this->assertTrue($this->isValid($data, $formRequest));
    }

    private function isValid(array $data, FormRequest $formRequest, string $field = null, string $rule = null): bool
    {
        $validator = Validator::make($data, $formRequest->rules());

        if ($validator->fails()) {
            if (isset($validator->failed()[$field])) {
                $this->assertContains(
                    $rule,
                    $this->normalize_validation_rules($validator->failed()[$field]),
                    "Assert the rule to be $rule but it is not in the failed rules.\r\nthe failed rules are:\r\n"
                    .implode(' | ', $this->normalize_validation_rules($validator->failed()[$field]))
                );

                return false;
            }

            $message = '';
            foreach ($validator->failed() as $key => $rules) {
                $rules = $this->normalize_validation_rules($rules);
                $message .= $key . ' => ' . implode(' | ', $rules) . "\r\n";
            }

            $this->fail("Assert the field '$field' to fail but it didn't\r\nThe failed fields are :\r\n\t$message");
        }

        return true;
    }

    private function normalize_validation_rules(array $rules): array
    {
        return array_map([Str::class, 'snake'], array_keys($rules));
    }
}
