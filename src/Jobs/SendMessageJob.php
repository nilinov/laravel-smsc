<?php

namespace Papalapa\Laravel\Smsc\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Papalapa\Laravel\Smsc\PhoneNumber;

final class SendMessageJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 5;

    public function __construct(
        private PhoneNumber $phoneNumber,
        private string $text
    ) {
    }

    public function handle(Repository $config): void
    {
        Http::get($config->get('smsc.api_url'), [
            'login' => $config->get('smsc.api_login'),
            'psw' => $config->get('smsc.api_password'),
            'sender' => $config->get('smsc.sender_name'),
            'phones' => $this->phoneNumber->numeric(),
            'mes' => $this->text,
        ]);
    }
}
