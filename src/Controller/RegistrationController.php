<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\DTO\RegisterUserRequest;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private JWTTokenManagerInterface $jwt, private ValidatorInterface $validator){}

    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(#[MapRequestPayload] RegisterUserRequest $dto, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): JsonResponse
    {
        $user = new User();
        $user->setEmail($dto->email);
        $plainPassword = $dto->password;
        $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
        $entityManager->persist($user);
        $errors = $this->validator->validate($user);
        if (count($errors) !== 0){
          return $this->json($errors, 422);
        }
        $entityManager->flush();
        $token = $this->jwt->create($user);
        return $this->json(['token' => $token], 201);
    }

    #[Route('/preview_error', name: '_preview_error')]
    public function temp(){
      return $this->render('base.html.twig');
    }
}
