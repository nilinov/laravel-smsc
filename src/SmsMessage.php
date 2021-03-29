<?php

namespace Papalapa\Laravel\Smsc;

final class SmsMessage
{
    public function __construct(private PhoneNumber $phoneNumber, private string $text)
    {
    }

    public function phoneNumber(): PhoneNumber
    {
        return $this->phoneNumber;
    }

    public function text(): string
    {
        return $this->text;
    }
}
