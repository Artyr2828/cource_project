<?php 
namespace App\Service;

use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\RateLimiter\RateLimiterFactory;

class RateLimitService{
    public function __construct(
        private RateLimiterFactory $loginLimiter
    ){}

    public function enforce(string $email, string $clientIp): void
    {
        $key = sprintf('login_%s_%s', $email, $clientIp);
        $limiter = $this->loginLimiter->create($key);
        $limiterResult = $limiter->consume(1);

        if ($limiterResult->isAccepted() === false) {
            $message = sprintf('There are too many requests, please try again in: %d seconds', $limiterResult->getRetryAfter()->getTimestamp() - time());
            throw new TooManyRequestsHttpException($limiterResult->getRetryAfter()->getTimestamp(), $message);
        }

    }
}