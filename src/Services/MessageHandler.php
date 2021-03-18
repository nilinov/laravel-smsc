<?php

namespace Papalapa\Laravel\Smsc\Services;

use Papalapa\Laravel\Smsc\Contracts\SenderContract;
use Papalapa\Laravel\Smsc\PhoneNumber;
use Papalapa\Laravel\Smsc\SmsMessage;

final class MessageHandler
{
    public function __construct(
        private SenderContract $sender,
        private CodeCreator $codeCreator,
        private CodeValidator $codeChecker,
        private TokenGenerator $tokenGenerator,
    ) {
    }

    public function sendCode(PhoneNumber $phoneNumber) : SmsMessage
    {
        $smsCode = $this->codeCreator->create($phoneNumber);
        $message = sprintf('Код подтверждения: %s', $smsCode->code);

        return $this->send($phoneNumber, $message);
    }

    public function validateCode(PhoneNumber $tel, string $code) : bool
    {
        return $this->codeChecker->check($tel, $code);
    }

    public function generateToken(PhoneNumber $tel) : string
    {
        return $this->tokenGenerator->generate($tel);
    }

    public function validateToken(string $data) : PhoneNumber
    {
        return $this->tokenGenerator->validate($data);
    }

    public function sendMessage(PhoneNumber $tel, string $message) : SmsMessage
    {
        return $this->send($tel, $message);
    }

    private function send(PhoneNumber $tel, string $message) : SmsMessage
    {
        $sms = new SmsMessage($tel, $message);
        $this->sender->send($sms);

        return $sms;
    }
}
