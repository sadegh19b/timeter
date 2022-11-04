<?php

namespace Tests\Feature;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\InteractsWithValidation;
use Tests\TestCase;

class ProjectValidationTest extends TestCase
{
    use RefreshDatabase, InteractsWithValidation;

    /*
     * 'name' => ['required', 'string', 'min:2', 'max:120'],
     * 'description' => ['nullable', 'string', 'min:5'],
     * 'pay_per_hour' => ['nullable', 'numeric', 'min:1', 'max:100000000']
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
        $this->requestData['pay_per_hour'] = null;

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
    public function pay_per_hour_must_be_numeric(): void
    {
        $this->requestData['pay_per_hour'] = 'test';

        $this->assertFail($this->requestData, $this->formRequest, 'pay_per_hour', 'numeric');
    }

    /** @test */
    public function pay_per_hour_must_be_at_least_one(): void
    {
        $this->requestData['pay_per_hour'] = '0';

        $this->assertFail($this->requestData, $this->formRequest, 'pay_per_hour', 'min');
    }

    /** @test */
    public function pay_per_hour_must_be_a_maximum_of_one_hundred_million(): void
    {
        $this->requestData['pay_per_hour'] = '100000001';

        $this->assertFail($this->requestData, $this->formRequest, 'pay_per_hour', 'max');
    }
}
