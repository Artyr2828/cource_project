<?php 
namespace App\DTO;
use App\Entity\Attributes;

class UserValidAttributes{
    public function __construct(
        public Attributes $attribute,
        public mixed $value
    ){}
}