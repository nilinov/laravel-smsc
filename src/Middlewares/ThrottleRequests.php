<?php

namespace Papalapa\Laravel\Smsc\Middlewares;

use Illuminate\Cache\RateLimiter;
use Illuminate\Routing\Middleware\ThrottleRequests as BaseThrottleRequests;
use Symfony\Component\HttpFoundation\Response;

final class ThrottleRequests
{
    public function __construct(
        private RateLimiter $limiter,
        private int $limit,
        private int $decayMinutes = 1,
        private string $prefix = 'smsc_',
    ) {
    }

    public function handle($request, \Closure $next): Response
    {
        return (new BaseThrottleRequests($this->limiter))
            ->handle(
                $request,
                $next,
                $this->limit,
                $this->decayMinutes,
                $this->prefix
            );
    }
}
