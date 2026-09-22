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
use App\Service\RateLimitService;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PositionEntityRepository;

final class PositionController extends AbstractController
{
    private const LIMIT = 10;
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AttributesRepository $attributeRepository,
        private ValidatePositionData $validatePositionData,
        private RateLimitService $rateLimitService,
        private PositionEntityRepository $positionRepository
    ){}
    #[Route('/api/position', name: 'app_position', methods: ['POST'])]
    public function post(#[MapRequestPayload] PositionDto $dto, Request $request): JsonResponse
    {
       $user = $this->getUser();
       $this->rateLimitService->enforce($user->getEmail(), $request->getClientIp(), 'api');
       $this->rateLimitService->enforce($user->getEmail(), $request->getClientIp(), 'positionCreate');

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
       
       return $this->json(["status"=>"ok", 'position' => $position]);
    }
    #[Route('/api/position', name: 'app_position_get', methods: ['GET'])]
    public function get(Request $request){
        $after = $request->query->getInt('after', 0);
        $before = $request->query->getInt('before', 0);
        $hasNext = false;
        $hasPrevios = false;
        
        if ($after && $before){
            return $this->json(["status" => "передали и то ито"]);
        }
        $positions = $this->positionRepository->findPaginated($before, $after, self::LIMIT);

        if (count($positions) > self::LIMIT){

           array_pop($positions);
           if ($after === 0 && $before === 0){
               $hasNext = true;
           }
           if ($before !== 0){
              $hasPrevios = true;
           } else if ($after !== 0){
              $hasNext = true;
           } 

        } else if (count($positions) <= self::LIMIT){

            if ($after === 0 && $before === 0){
               $hasNext = false;
               $hasPrevios = false;
           } 

           if ($before !== 0){
               $hasPrevios = false;
               $hasNext = true;
           } else if ($after !== 0){
               $hasPrevios = true;
               $hasNext = false;
           }

        }
        return $this->json([
            'positions' => $positions,
            'hasPrevios' => $hasPrevios,
            'hasNext' => $hasNext
        ], 200);
    }
}
