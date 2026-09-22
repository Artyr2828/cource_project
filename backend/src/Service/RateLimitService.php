<?php 
namespace App\Service;

use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\RateLimiter\RateLimiterFactory;

class RateLimitService{
    public function __construct(
        private RateLimiterFactory $loginLimiter,
        private RateLimiterFactory $apiLimiter,
        private RateLimiterFactory $positionCreateLimiter
    ){}

    public function enforce(string $email, string $clientIp, string $typeOfLimiter): void
    {
        $key = sprintf('%s_%s_%s', $typeOfLimiter, $email, $clientIp);
        if ($typeOfLimiter === 'login'){
            $limiter = $this->loginLimiter->create($key);
        } else if ($typeOfLimiter === 'api'){
            $limiter = $this->apiLimiter->create($key);
        } else if ($typeOfLimiter === 'positionCreate'){
            $limiter = $this->positionCreateLimiter->create($key);
        } 
        else {
             throw new \InvalidArgumentException('Unknown limiter type');
        }
        $limiterResult = $limiter->consume(1);

        if ($limiterResult->isAccepted() === false) {
            $message = sprintf('There are too many requests, please try again in: %d seconds', $limiterResult->getRetryAfter()->getTimestamp() - time());
            throw new TooManyRequestsHttpException($limiterResult->getRetryAfter()->getTimestamp(), $message);
        }

    }
}