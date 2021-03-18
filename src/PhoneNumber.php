<?php

namespace Papalapa\Laravel\Smsc;

use Papalapa\Laravel\Smsc\Exceptions\IncorrectPhoneNumberException;

final class PhoneNumber
{
    public const MASK = '/^\+7\(\d{3}\)\d{3}\-\d{2}\-\d{2}$/';

    private string $number;

    public function __construct(string $number)
    {
        $this->number = $this->convertToNumeric($number);
    }

    private function convertToNumeric(string $number): string
    {
        // Removes non-numeric symbols from phone number: +7 (100) 200-30-40 => 71002003040
        $number = preg_replace('/[\+\(\)\s-]/', '', $number);

        if (false === $this->isNumeric($number)) {
            throw new IncorrectPhoneNumberException('Некорректный номер телефона');
        }

        return $number;
    }

    private function isNumeric(string $number): bool
    {
        return preg_match('/^7\d{10}$/', $number) === 1;
    }

    public static function fromString(string $tel): self
    {
        return new self($tel);
    }

    public static function asNumeric(string $tel): string
    {
        return (new self($tel))->number;
    }

    public function numeric(): string
    {
        return $this->number;
    }

    public function prefixed(): string
    {
        return sprintf('+%d', $this->number);
    }

    public function masked(): string
    {
        return preg_replace('/^7(\d{3})(\d{3})(\d{2})(\d{2})$/', '+7($1)$2-$3-$4', $this->number);
    }
}
