<?php 
namespace App\Service;
use App\DTO\PositionUpdatedDto;
use App\Service\RateLimitService;
use App\Service\ValidatePositionBasicData;
use App\Repository\PositionEntityRepository;
use App\Entity\PositionEntity;
use App\Entity\User;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repository\AttributesRepository;

final class PositionUpdaterService {
    public function __construct (
        private RateLimitService $rateLimitService,
        private ValidatePositionBasicData $validatePositionBasicData,
        private PositionEntityRepository $positionRepository,
        private EntityManagerInterface $entityManager,
        private AttributesRepository $attributeRepository
    )
    {}

    public function update(User $user, PositionUpdatedDto $dataPosition, string $clientIp): PositionEntity{
        $this->rateLimitService->enforce($user->getEmail(), $clientIp, 'api');
        $this->validatePositionBasicData->validate($dataPosition);
        $position = $this->getPositionEntity($dataPosition->positionId);
        $this->validateVersion($dataPosition->version, $position->getVersion());
        $sentPositionAttributeEntities = $this->getPositionAttributeEntities($dataPosition->attributes);
        $position->updateFromPosition($dataPosition, $sentPositionAttributeEntities);
        $this->entityManager->flush();
        return $position;
    }

    private function getPositionEntity(int $positionId): PositionEntity{
        $position = $this->positionRepository->findOneBy([
            'id' => $positionId
        ]);
        return $position;
    }

    private function validateVersion(int $sentVersion, int $currentVersion): void {
        if ($sentVersion !== $currentVersion){
            throw new OptimisticLockException("Oops, your data is outdated. Refresh the page to get the latest data", null);
        }
    }

    private function getPositionAttributeEntities(array $attributes): array {
         $sentPositionAttributeIds = $this->getSentPositionAttributeIds($attributes);
         $positionAttributeEntities = $this->attributeRepository->findBy([
            'id' => $sentPositionAttributeIds
         ]);
         $this->validatePositionAttributesExist($sentPositionAttributeIds, $positionAttributeEntities);
         return $positionAttributeEntities;
    }

    private function getSentPositionAttributeIds(array $sentPositionAttributes): array{
        $sentPositionAttributeIds = [];
        foreach ($sentPositionAttributes as $attribute){
            if (in_array($attribute->attributeId, $sentPositionAttributeIds, true)){
                throw new BadRequestHttpException("Duplicate Attribute");
            }
            $sentPositionAttributeIds[] = $attribute->attributeId;
        }
        return $sentPositionAttributeIds;
    }

    private function validatePositionAttributesExist(array $sentPositionAttributeIds, array $sentPositionAttributeEntities): void{
        if(count($sentPositionAttributeIds) !== count($sentPositionAttributeEntities)){
            throw new NotFoundHttpException("You have specified a non‑existent attribute");
        }
    }
}