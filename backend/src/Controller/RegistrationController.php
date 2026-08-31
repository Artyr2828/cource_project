<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use App\DTO\RegisterUserRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\Service\RegistrationService;

class RegistrationController extends AbstractController
{
    public function __construct(private RegistrationService $serviceRegistration){}

    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(#[MapRequestPayload] RegisterUserRequest $dto): JsonResponse
    {
        $token = $this->serviceRegistration->register($dto);
        return $this->json(['token' => $token], 201);
    }

    #[Route('/preview_error', name: '_preview_error')]
    public function temp(){
      return $this->render('base.html.twig');
    }
}
