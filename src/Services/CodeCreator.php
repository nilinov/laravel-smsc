<?php

namespace Papalapa\Laravel\Smsc\Services;

use Papalapa\Laravel\Smsc\Models\SmsCode;
use Papalapa\Laravel\Smsc\PhoneNumber;

final class CodeCreator
{
    public function __construct(private CodeGenerator $codeGenerator)
    {
    }

    public function create(PhoneNumber $phoneNumber): SmsCode
    {
        $code = new SmsCode([
            'number' => $phoneNumber->numeric(),
            'code' => $this->codeGenerator->generate(),
        ]);

        if (!$code->save()) {
            throw new \PDOException('Cannot save SMS-code into DB');
        }

        return $code;
    }
}
