<?php

namespace App\Jobs;

use App\Exceptions\ApplicationFailedException;
use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ProcessApplication implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public Application $application)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            $response = Http::post(config('services.b2b_api'), [
                'address_1' => $this->application->address_1,
                'address_2' => $this->application->address_2,
                'city' => $this->application->city,
                'state' => $this->application->state,
                'postcode' => $this->application->postcode,
                'plan_name' => $this->application->plan->name
            ])->throw();

            if ($response['status'] === 'Failed') {
                throw new ApplicationFailedException;
            }

            $this->application->status = 'complete';
            $this->application->order_id = $response['id'];
            $this->application->save();

        } catch (\Exception $e) {
            $this->failed($e);
        }
    }

    public function failed($exception = null): void
    {
        $this->application->status = 'order failed';
        $this->application->save();
    }
}
