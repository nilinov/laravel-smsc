<?php

namespace Papalapa\Laravel\Smsc\Services;

use Illuminate\Log\Logger;
use Papalapa\Laravel\Smsc\Contracts\SenderContract;
use Papalapa\Laravel\Smsc\SmsMessage;

final class LogSender implements SenderContract
{
    public function __construct(private Logger $logger)
    {
    }

    public function send(SmsMessage $sms): void
    {
        $message = sprintf('SMS to [%s]: %s', $sms->phoneNumber()->numeric(), $sms->text());

        $this->logger->info($message);
    }
}
