<?php 
namespace App\Service;

use App\DTO\LoginUserRequest;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use App\Entity\User;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class LoginService
{
    public function __construct(
        private JWTTokenManagerInterface $jwt,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private RateLimiterFactory $loginLimiter
    ){}

    public function login(LoginUserRequest $loginUserRequest, string $clientIp): string
    {
        $user = $this->findUserOrThrow($loginUserRequest->email); 
        $this->ensurePasswordValid($user, $loginUserRequest->password);
        $jwt = $this->jwt->create($user);
        $loginLimiter = $this->loginLimiter->create(sprintf('login_%s_%s', $loginUserRequest->email, $clientIp));
        $loginLimiter->reset();
        return $jwt;
    }

    public function validateLoginAttempts(string $email, string $clientIp): void
    {
        $key = sprintf('login_%s_%s', $email, $clientIp);
        $limiter = $this->loginLimiter->create($key);
        $limiterResult = $limiter->consume(1);

        if ($limiterResult->isAccepted() === false) {
            $message = sprintf('There are too many requests, please try again in: %d seconds', $limiterResult->getRetryAfter()->getTimestamp() - time());
            throw new TooManyRequestsHttpException($limiterResult->getRetryAfter()->getTimestamp(), $message);
        }

    }

    private function findUserOrThrow(string $email): User
    {
        $user = $this->userRepository->findOneBy(['email'=>$email]);
        if (!$user) {
            throw new UserNotFoundException('User not found');
        }
        return $user;
    }

    private function ensurePasswordValid(User $user, string $password): void
    {
        if (!$this->passwordHasher->isPasswordValid($user, $password)) {
            throw new BadRequestHttpException('Invalid password');
        }
    }
}