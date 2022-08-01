<?php

namespace App\Jobs;

use App\Services\ProcessXmlService;

class ProcessXmlFeedJob extends Job
{
    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 600;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ProcessXmlService $service)
    {
        $service->process(config('handbooks.defaults.urls.treasury'));
    }
}
