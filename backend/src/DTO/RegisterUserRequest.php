<?php
namespace App\DTO;
use Symfony\Component\Validator\Constraints;
#[Constraints\GroupSequence(['First', 'Second', 'RegisterUserRequest'])]

class RegisterUserRequest {
   #[Constraints\NotBlank(message: "Email can't be empty")]
   #[Constraints\Email(message: "Email is Incorrect")]
   public string $email;

   #[Constraints\NotBlank(message: "Password can't be empty", groups: ['First'])]
   #[Constraints\Length(min:8, minMessage: "Password is very tiny", groups: ['Second'])]
   public string $password;
}
