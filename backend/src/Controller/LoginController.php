<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\DTO\LoginUserRequest;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class LoginController extends AbstractController
{
    public function __construct(
        private JWTTokenManagerInterface $jwt,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ){}
    

    #[Route(path: '/api/login', name: 'app_login')]
    public function login(#[MapRequestPayload] LoginUserRequest $loginUserRequest): Response
    {
        $user = $this->userRepository->findOneBy(['email'=>$loginUserRequest->email]);
        if (!$user) {
            return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        if (!$this->passwordHasher->isPasswordValid($user, $loginUserRequest->password)) {
            return $this->json(['message' => 'Invalid password'], Response::HTTP_UNAUTHORIZED);
        }
        $jwt = $this->jwt->create($user);
        return $this->json(['token'=>$jwt], 200);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
