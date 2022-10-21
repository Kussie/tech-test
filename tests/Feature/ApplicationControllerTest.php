<?php

use App\Models\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ApplicationControllerTest extends TestCase
{
    use RefreshDatabase;


    protected function setUp(): void
    {
        parent::setUp();
        Application::factory(50)->create();

    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_it_can_view_applications(): void
    {
        $response = $this->getJson(route('applications.index'));

        $response
            ->assertSuccessful()
            ->assertJsonCount(15, 'data')
            ->assertJson(fn (AssertableJson $json) =>
            $json->has('data.0.plan_name')
                ->has('data.0.plan_type')
                ->has('data.0.customer_name')
                ->has('data.0.application_id')
                ->has('data.0.state')
                ->has('data.0.monthly_cost')
                ->has('data.0.monthly_cost.cents')
                ->has('data.0.monthly_cost.formatted')
                ->etc()
            );
    }

    public function test_it_can_filter_applications(): void
    {
        $response = $this->getJson(route('applications.index', ['plan' => 'nbn']));
        $response->assertSuccessful();

        $items = $response->json()['data'];
        collect($items)->each(fn(array $app) => $this->assertEquals('nbn', $app['plan_type']));

    }

    public function test_it_can_not_use_not_expected_filter(): void
    {
        $response = $this->getJson(route('applications.index', ['plan' => 'foobar']));
        $response->assertInvalid([
            'plan' => 'The selected plan is invalid.',
        ]);
    }

}
