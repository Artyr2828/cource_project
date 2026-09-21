<?php 
namespace App\Exceptions;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PosititonDataValidationException extends BadRequestHttpException {
    private $errors = [];
    public function __construct(array $errors){
        parent::__construct("Position Validate Error");
        $this->errors = $errors;
    }

    public function getErrors(){
        return $this->errors;
    }
}