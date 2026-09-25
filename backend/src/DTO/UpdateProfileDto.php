<?php 
namespace App\DTO;
use Symfony\Component\Validator\Constraints;
use App\DTO\UserAttributeDto;

class UpdateProfileDto {
    public function __construct(
       public ?MeDto $me = null,
       
       /** @var UserAttributeDto[] */
        #[Constraints\Valid]
       public array $attributes = [],
       
       #[Constraints\NotNull(message: "Your current version has not been sent")]
       public ?int $version = null
    ){}

}