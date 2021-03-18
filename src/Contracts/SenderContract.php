<?php

namespace Papalapa\Laravel\Smsc\Contracts;

use Papalapa\Laravel\Smsc\SmsMessage;

interface SenderContract
{
    public function send(SmsMessage $sms): void;
}
