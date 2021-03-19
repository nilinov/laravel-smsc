<?php

namespace Papalapa\Laravel\Smsc\Services;

use Illuminate\Translation\Translator;
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
        private Translator $translator,
    ) {
    }

    public function sendCode(PhoneNumber $phoneNumber): SmsMessage
    {
        $smsCode = $this->codeCreator->create($phoneNumber);
        $message = sprintf('%s: %s', __('smsc.code_description'), $smsCode->code);

        return $this->send($phoneNumber, $message);
    }

    private function send(PhoneNumber $tel, string $message): SmsMessage
    {
        $sms = new SmsMessage($tel, $message);
        $this->sender->send($sms);

        return $sms;
    }

    public function validateCode(PhoneNumber $tel, string $code): bool
    {
        return $this->codeChecker->validate($tel, $code);
    }

    public function generateToken(PhoneNumber $tel): string
    {
        return $this->tokenGenerator->generate($tel);
    }

    public function validateToken(string $data): PhoneNumber
    {
        return $this->tokenGenerator->validate($data);
    }

    public function sendMessage(PhoneNumber $tel, string $message): SmsMessage
    {
        return $this->send($tel, $message);
    }
}
