<?php

namespace Papalapa\Laravel\Smsc;

use Papalapa\Laravel\Smsc\Exceptions\IncorrectPhoneNumberException;

final class PhoneNumber
{
    private string $number;

    public function __construct(string $number)
    {
        $this->number = $this->convertToNumeric($number);
    }

    private function convertToNumeric(string $number): string
    {
        $number = preg_replace('/[\+\(\)\.\s-]/', '', $number);

        if (false === $this->isNumeric($number)) {
            throw new IncorrectPhoneNumberException(__('smsc.invalid_phone_number'));
        }

        return $number;
    }

    private function isNumeric(string $number): bool
    {
        return preg_match('/^\d{10,15}$/', $number) === 1;
    }

    public static function fromString(string $number): self
    {
        return new self($number);
    }

    public static function asNumeric(string $number): string
    {
        return (new self($number))->number;
    }

    public function numeric(): string
    {
        return $this->number;
    }

    public function prefixed(): string
    {
        return sprintf('+%d', $this->number);
    }
}
