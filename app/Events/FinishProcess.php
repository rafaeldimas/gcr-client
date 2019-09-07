<?php

namespace Gcr\Events;

use Gcr\Process;
use Illuminate\Queue\SerializesModels;

class FinishProcess
{
    use SerializesModels;

    /**
     * @var Process
     */
    public $process;

    /**
     * Create a new event instance.
     *
     * @param Process $process
     */
    public function __construct(Process $process)
    {
        $this->process = $process;
    }
}
