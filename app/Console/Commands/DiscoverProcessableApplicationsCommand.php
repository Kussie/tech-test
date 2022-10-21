<?php

namespace App\Console\Commands;

use App\Jobs\ProcessApplication;
use App\Models\Application;
use Illuminate\Console\Command;

class DiscoverProcessableApplicationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'applications:discover';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Discover processable applications';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Application::query()
            ->whereRelation('plan', 'type', 'nbn')
            ->where('status', 'order')
            ->chunk(100, function($applications)
            {
                $applications->each(function($application)
                {
                    try {
                        ProcessApplication::dispatch($application);
                        $application->status = 'processing';
                        $application->save();
                    } catch (\Exception $e) {
                        $this->error($e->getMessage());
                    }
                });
            });

        return Command::SUCCESS;
    }
}
