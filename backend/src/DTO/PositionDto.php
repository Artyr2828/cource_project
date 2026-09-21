<?php 
namespace App\DTO;
use Symfony\Component\Validator\Constraints;
use App\DTO\PositionAttribute;

class PositionDto {
    public function __construct(
        #[Constraints\NotNull(message: "The name is required for the position")]
        public string $name,
        
        #[Constraints\NotNull(message: "The description is required for the position")]
        public string $description,

        /** @var PositionAttribute[] */
        #[Constraints\Valid]
        public array $attributes = []
    ){}
}