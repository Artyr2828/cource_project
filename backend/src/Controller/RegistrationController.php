<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\DTO\RegisterUserRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\Service\RegistrationService;
use Symfony\Component\HttpFoundation\Cookie;

class RegistrationController extends AbstractController
{
    public function __construct(private RegistrationService $serviceRegistration){}

    #[Route('/api/register', name: 'app_register', methods: ['POST'])]
    public function register(#[MapRequestPayload] RegisterUserRequest $dto): JsonResponse
    {
        $token = $this->serviceRegistration->register($dto);
        $response = $this->json(['status' => 'ok'], 201);
        $response->headers->setCookie(Cookie::create('BEARER', $token, 0, '/', null, true, true, false, 'Strict'));
        return $response;
    }

    #[Route('/preview_error', name: '_preview_error')]
    public function temp(){
      return $this->render('base.html.twig');
    }
}
