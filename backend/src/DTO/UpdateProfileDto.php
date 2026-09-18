<?php 
namespace App\DTO;

class UpdateProfileDto {
    public function __construct(
       public ?MeDto $me = null,
       public array $attributes = []
    ){}
}