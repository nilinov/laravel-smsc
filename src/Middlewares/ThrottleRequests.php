<?php

namespace Papalapa\Laravel\Smsc\Middlewares;

use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

final class ThrottleRequests extends \Illuminate\Routing\Middleware\ThrottleRequests
{
    public function __construct(RateLimiter $limiter, private int $limit)
    {
        parent::__construct($limiter);
    }

    public function handle($request, \Closure $next, $maxAttempts = 60, $decayMinutes = 1, $prefix = ''): Response
    {
        return parent::handle($request, $next, $this->limit);
    }
}
