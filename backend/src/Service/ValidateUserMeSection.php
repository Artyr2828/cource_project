<?php 
namespace App\Service;
use App\DTO\MeDto;
use App\Exceptions\MeSectionValidationException;

class ValidateUserMeSection {
    
    public function validate(?MeDto $me){
        $errors = [];
        if ($me === null){
            return;
        }
        $errorMess = $this->validateFirstName($me->firstName);
        $this->addValidationError($errors, 'firstName', $errorMess);

        $errorMess = $this->validateLastName($me->lastName);
        $this->addValidationError($errors, 'lastName', $errorMess);

        $errorMess = $this->validateLocation($me->location);
        $this->addValidationError($errors, 'location', $errorMess);

        if (!empty($errors)){
            throw new MeSectionValidationException($errors);
        }

    }

    private function validateFirstName(string $firstName): string {

        if ($firstName === '') {
            return 'The first name must not be empty';
        }

        if (!preg_match("/^[\p{L}]+(?:[ '-][\p{L}]+)*$/u", $firstName)) {
            return 'The first name may contain only letters, spaces, hyphens and apostrophes';
        }

        return '';
    }

    private function validateLastName(string $lastName): string {
        if ($lastName === '') {
            return 'The last name must not be empty';
        }

        if (!preg_match("/^[\p{L}]+(?:[ '-][\p{L}]+)*$/u", $lastName)) {
            return 'The last name may contain only letters, spaces, hyphens and apostrophes';
        }

        return '';
    }

        
    private function validateLocation(string $location): string {
        if ($location === '') {
            return 'The location must not be empty';
        }

        if (!preg_match("/^[\p{L}\p{N}]+(?:[ .,'\/-][\p{L}\p{N}]+)*$/u", $location)) {
            return 'The location may contain only letters, numbers, spaces, dots, commas, hyphens, apostrophes and slashes';
        }

        return '';
    }



    private function addValidationError(array &$errors, string $field, string $errorMess){
        if ($errorMess !== ""){
            $errors[] = [
                'field' => $field,
                'message' => $errorMess
            ];
        }
    }
}