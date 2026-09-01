<?php
namespace App\DTO;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraints\GroupSequence;

#[Constraints\GroupSequence(['First', 'Second', 'RegisterUserRequest'])]

class RegisterUserRequest {
   #[Constraints\NotBlank(message: "Email can't be empty", groups: ['First'])]
   #[Constraints\Email(message: "Email is Incorrect", groups: ['Second'])]
   public string $email;

   #[Constraints\NotBlank(message: "Password can't be empty", groups: ['First'])]
   #[Constraints\Length(min:8, minMessage: "Password must be at least 8 characters long", groups: ['Second'])]
   public string $password;
}
