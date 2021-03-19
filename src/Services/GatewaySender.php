<?php

namespace Papalapa\Laravel\Smsc\Services;

use Illuminate\Bus\Dispatcher;
use Illuminate\Config\Repository;
use Papalapa\Laravel\Smsc\Contracts\SenderContract;
use Papalapa\Laravel\Smsc\Jobs\SendMessageJob;
use Papalapa\Laravel\Smsc\SmsMessage;

final class GatewaySender implements SenderContract
{
    public function __construct(
        private Repository $config,
        private Dispatcher $jobDispatcher,
        private ?string $connection
    ) {
    }

    public function send(SmsMessage $sms): void
    {
        $message = $this->appendMessageSender($sms->text());
        $job = new SendMessageJob($sms->tel(), $message);
        $job->onConnection($this->connection);
        $this->jobDispatcher->dispatch($job);
    }

    private function appendMessageSender(string $message): string
    {
        $sender = $this->config->get('smsc.sender_name');

        return sprintf("%s\n%s", $message, $sender);
    }
}
