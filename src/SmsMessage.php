<?php

namespace Papalapa\Laravel\Smsc;

final class SmsMessage
{
    public function __construct(private PhoneNumber $tel, private string $text)
    {
    }

    public function tel(): PhoneNumber
    {
        return $this->tel;
    }

    public function text(): string
    {
        return $this->text;
    }
}
