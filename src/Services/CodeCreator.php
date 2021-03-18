<?php

namespace Papalapa\Laravel\Smsc\Services;

use Papalapa\Laravel\Smsc\Models\SmsCode;
use Papalapa\Laravel\Smsc\PhoneNumber;

final class CodeCreator
{
    public function __construct(private int $size = 6)
    {
    }

    public function create(PhoneNumber $phoneNumber): SmsCode
    {
        $code = new SmsCode([
            'number' => $phoneNumber->numeric(),
            'code' => $this->generateCode($this->size),
        ]);

        if (!$code->save()) {
            throw new \PDOException('Не удалось сохранить смс-код в БД');
        }

        return $code;
    }

    private function generateCode(int $size): string
    {
        return sprintf("%0{$size}d", random_int(0, 10 ** $size - 1));
    }
}
