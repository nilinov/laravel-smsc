<?php

namespace Papalapa\Laravel\Smsc\Services;

use Papalapa\Laravel\Smsc\Exceptions\InvalidSmsCodeException;
use Papalapa\Laravel\Smsc\Models\SmsCode;
use Papalapa\Laravel\Smsc\PhoneNumber;

final class CodeValidator
{
    public function __construct(private int $lifetime)
    {
    }

    public function ensure(PhoneNumber $phoneNumber, string $code): void
    {
        if (false === $this->validate($phoneNumber, $code)) {
            throw new InvalidSmsCodeException(__('smsc.invalid_code'));
        }
    }

    public function validate(PhoneNumber $phoneNumber, string $code): bool
    {
        $smsCode = $this->findLatest($phoneNumber, $code);

        if ($smsCode instanceof SmsCode) {
            $smsCode->delete();

            return $smsCode->isAliveAfter($this->lifetime);
        }

        return false;
    }

    private function findLatest(PhoneNumber $phoneNumber, string $code): ?SmsCode
    {
        $smsCode = SmsCode::query()
            ->where('number', '=', $phoneNumber->numeric())
            ->where('code', '=', $code)
            ->orderByDesc('serial')
            ->first();

        if ($smsCode instanceof SmsCode) {
            return $smsCode;
        }

        return null;
    }
}
