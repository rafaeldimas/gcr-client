<?php

namespace Gcr\Mail;

use Gcr\Document;
use Gcr\Process;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FinishProcess extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var Process
     */
    public $process;

    /**
     * Create a new message instance.
     *
     * @param Process $process
     */
    public function __construct(Process $process)
    {
        $this->process = $process;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->attachDocuments()
            ->view('emails.process.finish');
    }

    /**
     * @return $this
     */
    private function attachDocuments()
    {
        /** @var Document $document */
        foreach ($this->process->documents as $document) {
            $this->attachFromStorage($document->getFullPathFile());
        }
        return $this;
    }
}
