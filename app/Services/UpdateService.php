<?php

namespace App\Services;

use App\Jobs\ProcessXmlFeedJob;
use App\Models\JobStatus;

class UpdateService extends AbstractService
{

    /**
     * Get state
     *
     * @return array
     */
    public function getState(): array
    {
        $job = JobStatus::all()->first();
        $status = $job->status ?? JobStatus::EMPTY;

        return [
            'result' => $status === JobStatus::OK ? true : false,
            'info' => $status,
        ];
    }

    /**
     * Process XML feed job
     *
     * @return void
     */
    public function update(): void
    {
        dispatch(new ProcessXmlFeedJob());
    }

}
