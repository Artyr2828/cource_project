<?php 
namespace App\Exceptions;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MeSectionValidationException extends BadRequestHttpException{
    private array $errors = [];

    public function __construct(array $errors)
    {
        parent::__construct("Error in the Me section");
        $this->errors = $errors;
    }

    public function getErrors(): array{
        return $this->errors;
    }

    
}