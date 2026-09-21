<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\DTO\PositionDto;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\PositionEntity;
use App\Repository\AttributesRepository;
use App\Service\ValidatePositionData;

final class PositionController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AttributesRepository $attributeRepository,
        private ValidatePositionData $validatePositionData
    ){}
    #[Route('/api/position', name: 'app_position')]
    public function post(#[MapRequestPayload] PositionDto $dto): JsonResponse
    {
       //dd($dto);
       $this->validatePositionData->validate($dto);
       $position = new PositionEntity();
       $position->setName($dto->name);
       $position->setDescription($dto->description);
       $attributeIds = [];
       foreach ($dto->attributes as $attribute){
          $attributeIds[] = $attribute->attributeId;
       }
       $attributesExisting = $this->attributeRepository->findBy([
           'id' => $attributeIds
       ]);
       foreach ($attributesExisting as $attribute){
            $position->addAttribute($attribute);
       }

       $this->entityManager->persist($position);
       $this->entityManager->flush();
       
       return $this->json(["status"=>"ok"]);
    }
}
