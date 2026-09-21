<?php 
namespace App\DTO;
use Symfony\Component\Validator\Constraints;

class PositionAttribute {
    public function __construct(
        #[Constraints\NotNull(message: "The attribute is an essential part")]
        public int $attributeId
    ){}
}