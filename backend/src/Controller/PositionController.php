<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\DTO\PositionDto;
use App\DTO\PositionUpdatedDto;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\PositionEntity;
use App\Repository\AttributesRepository;
use App\Service\ValidatePositionBasicData;
use App\Service\RateLimitService;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PositionEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Service\PositionUpdaterService;

final class PositionController extends AbstractController
{
    private const LIMIT = 10;
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AttributesRepository $attributeRepository,
        private ValidatePositionBasicData $validatePositionBasicData,
        private RateLimitService $rateLimitService,
        private PositionEntityRepository $positionRepository,
        private PositionUpdaterService $positionUpdaterService
    ){}
    #[Route('/api/position', name: 'app_position', methods: ['POST'])]
    public function create(#[MapRequestPayload] PositionDto $dto, Request $request): JsonResponse
    {
       $user = $this->getUser();
       $this->rateLimitService->enforce($user->getEmail(), $request->getClientIp(), 'api');
       $this->rateLimitService->enforce($user->getEmail(), $request->getClientIp(), 'positionCreate');

       $this->validatePositionBasicData->validate($dto);
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
    #[Route('/api/position', name: 'app_position_patch', methods: ['PATCH'])]
    public function update(#[MapRequestPayload] PositionUpdatedDto $dto, Request $request){
        $user = $this->getUser();
        $position = $this->positionUpdaterService->update($user, $dto, $request->getClientIp());
        return $this->json([
            "status"=>"ok",
            "position"=>$position
        ]);
    }

}
