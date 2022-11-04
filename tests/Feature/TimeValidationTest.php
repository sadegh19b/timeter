<?php

namespace Tests\Feature;

use App\Http\Requests\TimeRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\InteractsWithValidation;
use Tests\TestCase;

class TimeValidationTest extends TestCase
{
    use RefreshDatabase, InteractsWithValidation;

    /*
     * 'start_at' => ['required', 'date_format:Y-m-d H:i'],
     * 'end_at' => ['nullable', 'date_format:Y-m-d H:i', 'after:start_at']
     */
    protected FormRequest $formRequest;
    protected array $requestData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->formRequest = new TimeRequest();
        $this->requestData = [
            'start_at' => now()->format('Y-m-d H:i'),
            'end_at' => now()->addHour()->format('Y-m-d H:i')
        ];
    }

    /** @test */
    public function validation_passes_with_correct_data(): void
    {
        $this->assertPasses($this->requestData, $this->formRequest);

        $this->requestData['end_at'] = null;
        $this->assertPasses($this->requestData, $this->formRequest);
    }

    /** @test */
    public function start_at_must_be_required(): void
    {
        unset($this->requestData['start_at']);

        $this->assertFail($this->requestData, $this->formRequest, 'start_at', 'required');
    }

    /** @test */
    public function start_at_must_be_in_the_correct_date_format(): void
    {
        $this->requestData['start_at'] = 'test';
        $this->assertFail($this->requestData, $this->formRequest, 'start_at', 'date_format');

        $this->requestData['start_at'] = '2022/10/10 10:10';
        $this->assertFail($this->requestData, $this->formRequest, 'start_at', 'date_format');

        $this->requestData['start_at'] = '2022-10-10 10:10';
        $this->assertPass($this->requestData, $this->formRequest, 'start_at', 'date_format');
    }

    /** @test */
    public function end_at_must_be_in_the_correct_date_format(): void
    {
        $this->requestData['end_at'] = 'test';
        $this->assertFail($this->requestData, $this->formRequest, 'end_at', 'date_format');

        $this->requestData['end_at'] = '2022/10/10 10:10';
        $this->assertFail($this->requestData, $this->formRequest, 'end_at', 'date_format');

        $this->requestData['end_at'] = '2022-10-10 10:10';
        $this->assertPass($this->requestData, $this->formRequest, 'end_at', 'date_format');
    }

    /** @test */
    public function end_at_must_be_after_start_at(): void
    {
        $this->requestData['start_at'] = '2022-10-10 10:10';
        $this->requestData['end_at'] = '2022-10-10 10:11';
        $this->assertPass($this->requestData, $this->formRequest, 'end_at', 'after');

        $this->requestData['start_at'] = '2022-10-10 10:11';
        $this->requestData['end_at'] = '2022-10-10 10:10';
        $this->assertFail($this->requestData, $this->formRequest, 'end_at', 'after');
    }
}
