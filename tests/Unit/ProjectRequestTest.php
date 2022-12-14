<?php

namespace Tests\Unit;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Tests\InteractsWithValidation;
use Tests\TestCase;

class ProjectRequestTest extends TestCase
{
    use InteractsWithValidation;

    /*
     * 'name' => ['required', 'string', 'min:2', 'max:120'],
     * 'description' => ['nullable', 'string', 'min:5'],
     * 'hourly_wage' => ['nullable', 'numeric', 'min:1', 'max:100000000']
     * 'use_persian_datetime_in_statistic' => ['boolean']
     */
    protected FormRequest $formRequest;
    protected array $requestData;

    protected function setUp(): void
    {
        parent::setUp();

        $this->formRequest = new ProjectRequest();
        $this->requestData = Project::factory()->fulfil()->make()->toArray();
    }

    /** @test */
    public function validation_passes_with_correct_data(): void
    {
        $this->assertPasses($this->requestData, $this->formRequest);

        $this->requestData['description'] = null;
        $this->requestData['hourly_wage'] = null;

        $this->assertPasses($this->requestData, $this->formRequest);
    }

    /** @test */
    public function name_must_be_required(): void
    {
        unset($this->requestData['name']);

        $this->assertFail($this->requestData, $this->formRequest, 'name', 'required');
    }

    /** @test */
    public function name_must_have_at_least_2_characters(): void
    {
        $this->requestData['name'] = 't';

        $this->assertFail($this->requestData, $this->formRequest, 'name', 'min');
    }

    /** @test */
    public function name_must_have_a_maximum_of_120_characters(): void
    {
        $this->requestData['name'] = \Str::random(121);

        $this->assertFail($this->requestData, $this->formRequest, 'name', 'max');
    }

    /** @test */
    public function description_must_have_at_least_5_characters(): void
    {
        $this->requestData['description'] = 'test';

        $this->assertFail($this->requestData, $this->formRequest, 'description', 'min');
    }

    /** @test */
    public function hourly_wage_must_be_numeric(): void
    {
        $this->requestData['hourly_wage'] = 'test';
        $this->assertFail($this->requestData, $this->formRequest, 'hourly_wage', 'numeric');

        $this->requestData['hourly_wage'] = '1.5';
        $this->assertPass($this->requestData, $this->formRequest, 'hourly_wage', 'numeric');
    }

    /** @test */
    public function hourly_wage_must_be_at_least_one(): void
    {
        $this->requestData['hourly_wage'] = '0';

        $this->assertFail($this->requestData, $this->formRequest, 'hourly_wage', 'min');
    }

    /** @test */
    public function hourly_wage_must_be_a_maximum_of_one_hundred_million(): void
    {
        $this->requestData['hourly_wage'] = '100000001';

        $this->assertFail($this->requestData, $this->formRequest, 'hourly_wage', 'max');
    }

    /** @test */
    public function use_persian_datetime_in_statistic_must_be_boolean(): void
    {
        $this->requestData['use_persian_datetime_in_statistic'] = 'test';

        $this->assertFail($this->requestData, $this->formRequest, 'use_persian_datetime_in_statistic', 'boolean');
    }

    /** @test */
    public function the_hourly_wage_separated_by_comma_should_be_modified_without_comma(): void
    {
        $this->requestData['hourly_wage'] = '123,456';
        $this->formRequest->merge($this->requestData);
        $this->invokeMethod($this->formRequest, 'prepareForValidation');

        $this->assertEquals('123456', $this->formRequest->get('hourly_wage'));
    }
}
