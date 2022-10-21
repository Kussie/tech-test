<?php

use App\Jobs\ProcessApplication;
use App\Models\Application;
use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Tests\InteractsWithStubs;
use Tests\TestCase;

class DiscoverProcessableApplicationsCommandTest extends TestCase
{
    use RefreshDatabase;
    use InteractsWithStubs;

    public $applicationOrder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->applicationOrder = Application::factory()
            ->for(Plan::factory()->state([
                'type' => 'nbn',
            ]))
            ->create(
                [
                    'status' => 'order'
                ]
            );

    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_it_finds_a_processable_order_and_dispatches_a_job(): void
    {
        Bus::fake();
        $this->artisan('applications:discover')->assertSuccessful();
        Bus::assertDispatched(ProcessApplication::class);
    }

    public function test_it_finds_a_processable_order_and_changes_the_status(): void
    {
        Bus::fake();
        $this->artisan('applications:discover')->assertSuccessful();
        $this->assertDatabaseHas('applications', [
            'id' => $this->applicationOrder->id,
            'status' => 'processing'
        ]);
    }

    public function test_it_finds_a_processable_order_and_handles_a_successful_response(): void
    {
        Bus::fake();
        $this->artisan('applications:discover')->assertSuccessful();
        $this->assertDatabaseHas('applications', [
            'id' => $this->applicationOrder->id,
            'status' => 'processing'
        ]);

        Http::fake([
            '*' => Http::response($this->getStub('nbn-successful-response'), 200, ['Headers']),
        ]);

        $job = new ProcessApplication($this->applicationOrder);
        $job->handle();

        $this->assertDatabaseHas('applications', [
            'id' => $this->applicationOrder->id,
            'status' => 'complete',
            'order_id' => 'ORD000000000000'
        ]);
    }

    public function test_it_finds_a_processable_order_and_handles_a_failed_response(): void
    {
        Bus::fake();
        $this->artisan('applications:discover')->assertSuccessful();
        $this->assertDatabaseHas('applications', [
            'id' => $this->applicationOrder->id,
            'status' => 'processing'
        ]);

        Http::fake([
            '*' => Http::response($this->getStub('nbn-fail-response'), 200, ['Headers']),
        ]);

        $job = new ProcessApplication($this->applicationOrder);
        $job->handle();

        $this->assertDatabaseHas('applications', [
            'id' => $this->applicationOrder->id,
            'status' => 'order failed'

        ]);
    }
}
