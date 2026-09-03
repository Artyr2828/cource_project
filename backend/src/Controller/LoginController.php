<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\DTO\LoginUserRequest;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\Service\LoginService;
use Symfony\Component\HttpFoundation\Cookie;

class LoginController extends AbstractController
{
    public function __construct(
        private LoginService $loginService,
    ){}
    

    #[Route(path: '/api/login', name: 'app_login')]
    public function login(#[MapRequestPayload] LoginUserRequest $loginUserRequest, Request $request): Response
    {
        $this->loginService->validateLoginAttempts($loginUserRequest->email, $request->getClientIp());
        $jwt = $this->loginService->login($loginUserRequest, $request->getClientIp());
        $response = $this->json(['status' => 'ok'], Response::HTTP_OK);
        $response->headers->setCookie(Cookie::create('BEARER', $jwt, 0, '/', null, true, true, false, 'Strict'));
        return $response;
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
