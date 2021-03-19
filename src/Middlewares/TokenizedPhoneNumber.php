<?php

namespace Papalapa\Laravel\Smsc\Middlewares;

use Illuminate\Http\Request;
use Papalapa\Laravel\Smsc\PhoneNumber;
use Papalapa\Laravel\Smsc\Services\TokenGenerator;

final class TokenizedPhoneNumber
{
    public const TOKENIZED_PHONE_NUMBER = 'tokenizedPhoneNumber';

    public function __construct(private TokenGenerator $tokenGenerator)
    {
    }

    public static function getPhoneNumber(Request $request): PhoneNumber
    {
        return $request->attributes->get(self::TOKENIZED_PHONE_NUMBER);
    }

    public function handle(Request $request, \Closure $next)
    {
        $token = $request->input('token');
        $phoneNumber = $this->tokenGenerator->validate($token);
        $request->attributes->set(self::TOKENIZED_PHONE_NUMBER, $phoneNumber);

        return $next($request);
    }
}
