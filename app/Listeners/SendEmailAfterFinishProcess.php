<?php

namespace Gcr\Listeners;

use Gcr\Events\FinishProcess;
use Gcr\Mail\FinishProcess as FinishProcessMailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailAfterFinishProcess implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  FinishProcess  $event
     * @return void
     */
    public function handle(FinishProcess $event)
    {
        Mail::to('contato@gcrlegalizacao.com.br')
            ->cc([
                'gustavocamargo@gcrlegalizacao.com.br',
                'rafael@gcrlegalizacao.com.br',
            ])
            ->send(new FinishProcessMailable($event->process));
    }
}
