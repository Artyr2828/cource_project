<?php 
namespace App\Exceptions;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AttributeValidationException extends BadRequestHttpException {
    private $errors = [];
    public function __construct(array $errors){
        parent::__construct("Attribute Validate Error");
        $this->errors = $errors;
    }

    public function getErrors(){
        return $this->errors;
    }
}