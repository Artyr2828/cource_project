<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\DTO\UpdateProfileDto;
use App\Entity\Attributes;
use App\Entity\UserAttribute;
use App\Entity\UserProfile;
use App\Repository\AttributesRepository;
use App\Repository\UserAttributeRepository;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Doctrine\ORM\EntityManagerInterface;

final class UserProfileController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private AttributesRepository $attributesRepository, private UserAttributeRepository $userAttributeRepository){}


    #[Route('/api/profile/me', name: 'app_user_profile', methods: ['PATCH'])]
    public function update(#[MapRequestPayload] UpdateProfileDto $dto): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        return $this->json(["status" => "work"]);
        
        if (!empty($dto->attributes)){
            $userAttributes = $this->userAttributeRepository->findBy([
                'user' => $user
            ]);

            $existingByAttributeId = [];

            foreach ($userAttributes as $userAttribute) {
                $existingByAttributeId[$userAttribute->getAttribute()->getId()] = $userAttribute;
            }

            

            foreach ($dto->attributes as $attribute) {
                $attributeId = $attribute['attribute']['id'];
                if (isset($existingByAttributeId[$attributeId])) {
                    $userAttribute = $existingByAttributeId[$attributeId];
                    if (is_array($attribute['value'])){
                        $attributeJson = json_encode($attribute['value'], JSON_THROW_ON_ERROR);
                        $userAttribute->setValue($attributeJson);
                    } else{
                        $userAttribute->setValue($attribute['value']);
                    }
                    unset($existingByAttributeId[$attributeId]);
                    continue;
                }
                $attributeObj = $this->attributesRepository->find($attributeId);
                $userAttribute = new UserAttribute();

                $userAttribute->setUser($user);
                $userAttribute->setAttribute($attributeObj);
                if (is_array($attribute['value'])){
                    $attributeJson = json_encode($attribute['value'], JSON_THROW_ON_ERROR);
                    $userAttribute->setValue($attributeJson);
                } else{
                    $userAttribute->setValue($attribute['value']);
                }
                $user->addToAttributes($userAttribute);
                $this->entityManager->persist($userAttribute);
            }
        foreach ($existingByAttributeId as $userAttribute) {
            $this->entityManager->remove($userAttribute);
        }           
        } 

        $profile = $user->getProfile();

        $profile = $profile['me']->updateFromDto($dto->me);
        $this->entityManager->flush();
        return $this->json([
            'status'=>'ok',
            'dto'=>$dto
        ]);
    }
    #[Route('/api/profile/me', name: 'app_user_profile_get', methods: ['GET'])]
    public function get(): Response{
          /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $profile = $user->getProfile();

        return $this->json($profile);
    }
}
