<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints;

class SalesforceDto
{
    public function __construct(
        #[Constraints\NotBlank(message: 'Company name is required.')]
        #[Constraints\Length(
            min: 2, 
            max: 255, 
            minMessage: 'Company name must be at least {{ limit }} characters long.',
            maxMessage: 'Company name cannot be longer than {{ limit }} characters.'
        )]
        public string $companyName,

        #[Constraints\NotBlank(message: 'Job position is required.')]
        #[Constraints\Length(max: 128, maxMessage: 'Job position cannot be longer than {{ limit }} characters.')]
        public string $position,

        #[Constraints\NotBlank(message: 'Phone number is required.')]
        #[Constraints\Regex(
            pattern: '/^\+?[0-9\s\-\(\)]{7,20}$/',
            message: 'Invalid phone number format.'
        )]
        public string $phone,


        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $email = null 
    ) {}

    public function withBasicData(string $firstName, string $lastName, string $email): self{
        return new self(
            companyName: $this->companyName,
            position: $this->position,
            phone: $this->phone,
            firstName: $firstName,
            lastName: $lastName,
            email: $email
        );
    }
}