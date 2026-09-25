<?php 
namespace App\DTO;
use Symfony\Component\Validator\Constraints;
use App\DTO\PositionAttribute;
use App\Interfaces\PositionDtoInterface;

class PositionUpdatedDto implements PositionDtoInterface{
    public function __construct(
        #[Constraints\NotNull(message: "The position cannot be found")]
        public int $positionId,

        #[Constraints\NotNull(message: "The name is required for the position")]
        public string $name,
        
        #[Constraints\NotNull(message: "The description is required for the position")]
        public string $description,

        /** @var PositionAttribute[] */
        #[Constraints\Valid]
        public array $attributes = [],

       #[Constraints\NotNull(message: "Your current version has not been sent")]
       public ?int $version = null
    ){}

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}