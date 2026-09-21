<?php 
namespace App\Service;
use App\DTO\PositionDto; 
use App\Exceptions\PosititonDataValidationException;

class ValidatePositionData {

    public function validate(PositionDto $dto){
        $errors = [];

        $errorMess = $this->validateName($dto->name);
        $this->addValidationError($errors, 'name', $errorMess);

        $errorMess = $this->validateDescription($dto->description);
        $this->addValidationError($errors, 'description', $errorMess);

        if (!empty($errors)){
            throw new PosititonDataValidationException($errors);
        }
    
    }

    private function validateName(string $name): string{
        if ($name === ''){
            return "The item name must not be empty";
        }
        if (mb_strlen($name) >= 150){
            return "The item name should not be longer than 150 characters";
        }
        return '';
    }

    private function validateDescription(string $description){
        if ($description === ''){
            return "The position description cannot be empty";
        }
        if (mb_strlen($description) >= 1000){
            return "The description cannot be longer than 1000 characters";
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