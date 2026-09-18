<?php 
namespace App\DTO;
use Symfony\Component\Validator\Constraints;


class MeDto {
    #[Constraints\Length(min:1, max:15, minMessage: "The name must be at least 1 character long", maxMessage: "The name must not exceed 15 characters")]
    public ?string $firstName = null;
    public ?string $lastName = null;
    public ?string $location = null;
    #[Constraints\Url(message: "The URL must be valid")]
    public ?string $avatarUrl = null;
}