<?php

namespace Papalapa\Laravel\Smsc;

use Carbon\Carbon;

final class CodeToken
{
    private int $expireAt;

    public function __construct(private PhoneNumber $phoneNumber, int $lifetime)
    {
        $this->expireAt = Carbon::now()->addSeconds($lifetime)->getTimestamp();
    }

    public static function create(PhoneNumber $phoneNumber, int $lifetime): self
    {
        return new self(...func_get_args());
    }

    public function getPhoneNumber(): PhoneNumber
    {
        return $this->phoneNumber;
    }

    public function isStillValid(): bool
    {
        return Carbon::now()->timestamp < $this->expireAt;
    }
}
