<?php 
namespace App\DTO;
use Symfony\Component\Validator\Constraints;

class UserAttributeDto {
    public function __construct(
        #[Constraints\NotNull(message: "Attribute must not be null")]
        #[Constraints\Positive(message: "Attribute must be positive value")]
        public ?int $attributeId = null,
        
        public mixed $value = null,
        public ?array $options = null
    ){}

}