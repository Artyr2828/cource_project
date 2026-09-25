<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\DTO\UpdateProfileDto;
use App\Entity\UserAttribute;
use App\Repository\AttributesRepository;
use App\Repository\UserAttributeRepository;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ValidateValueUserAttribute;
use App\Service\ValidateUserMeSection;
use Doctrine\ORM\OptimisticLockException;

final class UserProfileController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AttributesRepository $attributesRepository,
        private UserAttributeRepository $userAttributeRepository,
        private ValidateValueUserAttribute $validateValueUserAttribute,
        private ValidateUserMeSection $validateUserMeSection,
    ){}


    #[Route('/api/profile/me', name: 'app_user_profile', methods: ['PATCH'])]
    public function update(#[MapRequestPayload] UpdateProfileDto $dto): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $profile = $user->getProfile();

        if ($dto->version !== $profile->getVersion()){
            throw new OptimisticLockException("Oops, your data is outdated. Refresh the page to get the latest data", null);
        }        

        $this->validateUserMeSection->validate($dto->me);
        $attributes = $this->validateValueUserAttribute->validate($dto->attributes);

        if (!empty($attributes)){
            $userAttributes = $this->userAttributeRepository->findBy([
                'profile' => $profile
            ]);

            $existingByAttributeId = [];

            foreach ($userAttributes as $userAttribute) {
                $existingByAttributeId[$userAttribute->getAttribute()->getId()] = $userAttribute;
            }

            foreach ($attributes as $attribute) {
                
                $attributeId = $attribute->attribute->getId();
                
                if (isset($existingByAttributeId[$attributeId])) {
                    $userAttribute = $existingByAttributeId[$attributeId];
                    if (is_array($attribute->value)){
                        $attributeJson = json_encode($attribute->value, JSON_THROW_ON_ERROR);
                        $userAttribute->setValue($attributeJson);
                    } else{
                        $userAttribute->setValue($attribute->value);
                    }
                    unset($existingByAttributeId[$attributeId]);
                    continue;
                }
                $attributeObj = $this->attributesRepository->find($attributeId);
                $userAttribute = new UserAttribute();
                
                //$userAttribute->setUser($user);

                $userAttribute->setAttribute($attributeObj);
                if (is_array($attribute->value)){
                    $attributeJson = json_encode($attribute->value, JSON_THROW_ON_ERROR);
                    $userAttribute->setValue($attributeJson);
                } else{
                    $userAttribute->setValue($attribute->value);
                }
                $profile->addToAttributes($userAttribute);
                $this->entityManager->persist($userAttribute);
            }
        foreach ($existingByAttributeId as $userAttribute) {
            $this->entityManager->remove($userAttribute);
        }           
        } else {
            $profile->getAttributes()->clear();
        }

        $profile->getMe()->updateFromDto($dto->me);

        $profile->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->flush();
        return $this->json([
            'status'=>'ok',
            'attributes'=>$attributes,
            'me'=>$profile->getMe(),
            'version'=>$profile->getVersion()
        ], 200);
    }



    #[Route('/api/profile/me', name: 'app_user_profile_get', methods: ['GET'])]
    public function get(): Response{
          /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $profile = $user->getProfile();
        $response = [
            'me' => $profile->getMe(),
            'attributes' => $profile->getAttributes(),
            'role' => $user->getRole(),
            'version' => $profile->getVersion()
        ];
        return $this->json($response);
    }
}
