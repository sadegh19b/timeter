<?php

namespace Tests\Unit;

use App\Http\Requests\TimeRequest;
use Illuminate\Foundation\Http\FormRequest;
use Tests\InteractsWithValidation;
use Tests\TestCase;

class TimeRequestTest extends TestCase
{
    use InteractsWithValidation;

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

        $this->requestData['start_at'] = '2022-10-10 10:10';
        $this->requestData['end_at'] = '2022-10-10 10:10';
        $this->assertFail($this->requestData, $this->formRequest, 'end_at', 'after');

        $this->requestData['start_at'] = '2022-10-10 10:11';
        $this->requestData['end_at'] = '2022-10-10 10:10';
        $this->assertFail($this->requestData, $this->formRequest, 'end_at', 'after');
    }

    /** @test */
    public function if_the_start_at_or_the__end_at_value_is_in_persian_date_it_should_be_accepted(): void
    {
        $this->requestData['start_at'] = '1401-01-01 10:10';
        $this->assertPass($this->requestData, $this->formRequest, 'start_at', 'date_format');

        $this->requestData['end_at'] = '1401-01-01 10:11';
        $this->assertPass($this->requestData, $this->formRequest, 'end_at', 'date_format');
    }

    /** @test */
    public function if_the_start_at_or_the__end_at_value_is_in_persian_date_should_be_modified_to_carbon_datetime(): void
    {
        $this->requestData['start_at'] = '1301-01-01 10:10';
        $this->requestData['end_at'] = '1301-01-01 10:11';
        $this->formRequest->merge($this->requestData);
        $this->invokeMethod($this->formRequest, 'prepareForValidation');

        $this->assertEquals(persian_to_georgian_datetime($this->requestData['start_at'], 'Y-m-d H:i'), $this->formRequest->get('start_at'));
        $this->assertEquals(persian_to_georgian_datetime($this->requestData['end_at'], 'Y-m-d H:i'), $this->formRequest->get('end_at'));

        $this->requestData['start_at'] = '1401-01-01 10:10';
        $this->requestData['end_at'] = '1401-01-01 10:11';
        $this->formRequest->merge($this->requestData);
        $this->invokeMethod($this->formRequest, 'prepareForValidation');

        $this->assertEquals(persian_to_georgian_datetime($this->requestData['start_at'], 'Y-m-d H:i'), $this->formRequest->get('start_at'));
        $this->assertEquals(persian_to_georgian_datetime($this->requestData['end_at'], 'Y-m-d H:i'), $this->formRequest->get('end_at'));
    }

    /** @test */
    public function if_the_start_at_value_is_now_it_should_be_accepted(): void
    {
        $this->requestData['start_at'] = 'now';
        $this->formRequest->merge($this->requestData);
        $this->invokeMethod($this->formRequest, 'prepareForValidation');

        $this->assertEquals(now()->format('Y-m-d H:i'), $this->formRequest->get('start_at'));
    }

    /** @test */
    public function if_the_end_at_value_is_now_it_should_be_accepted(): void
    {
        $this->requestData['start_at'] = now()->subHour()->format('Y-m-d H:i');
        $this->requestData['end_at'] = 'now';
        $this->formRequest->merge($this->requestData);
        $this->invokeMethod($this->formRequest, 'prepareForValidation');

        $this->assertEquals(now()->format('Y-m-d H:i'), $this->formRequest->get('end_at'));
    }

    /** @test */
    public function if_the_start_at_and_the_end_at_is_equals_date_time_and_the_end_at_value_is_now_it_should_be_accepted_and_the_end_at_should_be_added_a_minute(): void
    {
        $this->requestData['end_at'] = 'now';
        $this->formRequest->merge($this->requestData);
        $this->invokeMethod($this->formRequest, 'prepareForValidation');

        $this->assertEquals(now()->format('Y-m-d H:i'), $this->formRequest->get('start_at'));
        $this->assertEquals(now()->addMinute()->format('Y-m-d H:i'), $this->formRequest->get('end_at'));
    }

    /** @test */
    public function if_the_start_at_and_the_end_at_values_is_now_it_should_be_accepted_and_the_end_at_should_be_added_a_minute(): void
    {
        $this->requestData['start_at'] = 'now';
        $this->requestData['end_at'] = 'now';
        $this->formRequest->merge($this->requestData);
        $this->invokeMethod($this->formRequest, 'prepareForValidation');

        $this->assertEquals(now()->format('Y-m-d H:i'), $this->formRequest->get('start_at'));
        $this->assertEquals(now()->addMinute()->format('Y-m-d H:i'), $this->formRequest->get('end_at'));
    }
}
