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
    public function __construct(private Encrypter $encrypter, private int $lifetime)
    {
    }

    public function generate(PhoneNumber $phoneNumber): string
    {
        $token = CodeToken::create($phoneNumber, $this->lifetime);

        return $this->encrypter->encrypt($token);
    }

    public function validate(string $data): PhoneNumber
    {
        try {
            $token = $this->encrypter->decrypt($data);
            if (!($token instanceof CodeToken)) {
                throw new InvalidTokenException(__('smsc.invalid_token'));
            }
            if (!$token->isStillValid()) {
                throw new ExpiredTokenException(__('smsc.expired_token'));
            }
        } catch (DecryptException) {
            throw new DecryptTokenException(__('smsc.token_decryption_failed'));
        }

        return $token->getPhoneNumber();
    }
}
