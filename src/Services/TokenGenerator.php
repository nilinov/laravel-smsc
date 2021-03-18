<?php

namespace Papalapa\Laravel\Smsc\Services;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Encryption\Encrypter;
use Papalapa\Laravel\Smsc\CodeToken;
use Papalapa\Laravel\Smsc\Exceptions\DecryptTokenException;
use Papalapa\Laravel\Smsc\Exceptions\ExpiredTokenException;
use Papalapa\Laravel\Smsc\Exceptions\InvalidTokenException;
use Papalapa\Laravel\Smsc\PhoneNumber;

final class TokenGenerator
{
    public function __construct(
        private Encrypter $encrypter,
        private int $lifetime = 600
    ) {
    }

    public function generate(PhoneNumber $tel): string
    {
        $token = CodeToken::create($tel, $this->lifetime);

        return $this->encrypter->encrypt($token);
    }

    public function validate(string $data): PhoneNumber
    {
        try {
            $token = $this->encrypter->decrypt($data);
            if (!($token instanceof CodeToken)) {
                throw new InvalidTokenException('Данный запрос требует валидный токен');
            }
            if (!$token->isStillValid()) {
                throw new ExpiredTokenException('Срок действия вашего токена истёк');
            }
        } catch (DecryptException) {
            throw new DecryptTokenException('Токен данного запроса не прошёл проверку');
        }

        return $token->getPhoneNumber();
    }
}
