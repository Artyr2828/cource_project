<?php 
namespace App\Service;
use App\Enums\AttributeType;
use App\Entity\UserAttribute;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Repository\AttributesRepository;
use App\Exceptions\AttributeValidationException;
use App\DTO\UserValidAttributes;

class ValidateValueUserAttribute {

    public function __construct(private AttributesRepository $attributeRepository){}

    public function validate(array $attributes){
        $attributeIds = [];
        foreach ($attributes as $attribute){
            $attributeIds[] = $attribute->attributeId;
        }

        $attributeIds = array_unique($attributeIds);

        $attributeEntities = $this->attributeRepository->findBy([
            'id' => $attributeIds
        ]);

        

        if (count($attributeIds) !== count($attributeEntities)){
            throw new BadRequestHttpException('One or more attributes not found');
        }

        $attributeMap = [];
        $errors = [];
        $attributeResponse = [];
        foreach ($attributeEntities as $attributeEntity){
             $attributeMap[$attributeEntity->getId()] = $attributeEntity;
        }

        foreach ($attributes as $attribute){
            $attributeEntity = $attributeMap[$attribute->attributeId];
            $userValidAttribute = new UserValidAttributes($attributeEntity, $attribute->value);
            $attributeResponse[] = $userValidAttribute;

            switch ($attributeEntity->getType()){
                case AttributeType::NUMERIC:
                    $errorMess = $this->validateNumeric($attribute->value);
                    $this->addValidationError($errors, $attribute->attributeId, $errorMess);
                    break;
                case AttributeType::BOOLEAN:
                   $errorMess = $this->validateBoolean($attribute->value);
                   $this->addValidationError($errors, $attribute->attributeId, $errorMess);
                   break;
                case AttributeType::STRING:
                   $errorMess = $this->validateString($attribute->value);
                   $this->addValidationError($errors, $attribute->attributeId, $errorMess);
                   break;
                case AttributeType::TEXT:
                   $errorMess = $this->validateText($attribute->value);
                   $this->addValidationError($errors, $attribute->attributeId, $errorMess);
                   break;
                case AttributeType::DATE:
                   $errorMess = $this->validateDate($attribute->value);
                   $this->addValidationError($errors, $attribute->attributeId, $errorMess);
                   break;
                case AttributeType::IMAGE:
                   $errorMess = $this->validateImage($attribute->value);
                   $this->addValidationError($errors, $attribute->attributeId, $errorMess);
                   break;
                case AttributeType::PERIOD:
                   $errorMess = $this->validatePeriod($attribute->value);
                   $this->addValidationError($errors, $attribute->attributeId, $errorMess);
                   break;
                case AttributeType::ONE_OF_MANY:
                    $errorMess = $this->validateOneOfMany($attribute->value, $attribute->options);
                    $this->addValidationError($errors, $attribute->attributeId, $errorMess);
            }
        }
        if (!empty($errors)){
            throw new AttributeValidationException($errors);
        }
        return $attributeResponse;
        
    }

    private function validateNumeric(mixed $value): string {
        if (!is_numeric($value)) {
            return 'The value must be an number';
        }

        if ($value < 0) {
            return 'Your number should not be negative';
        }

        if ($value > 1_000_000_000) {
            return 'The number is too large';
        }

        return '';
    }

    private function validateBoolean(mixed $value): string{
        if (!is_bool($value)){
            return "The values must be true or false";
        }
        return "";
    }

    private function validateString(mixed $value): string{
        if (!is_string($value)){
            return "The value must be a string";
        }
        if (mb_strlen($value) > 255) {
             return 'The value is too long';
        }
        return "";
    }

    private function validateText(mixed $value): string{
        if (!is_string($value)){
            return "The value must be a string";
        }
        return "";
    }

    private function validateDate(mixed $value): string{
        if (!is_string($value)) {
            return 'The value must be a date';
        }
        if ($value !== ""){
            $date = \DateTimeImmutable::createFromFormat('Y-m-d', $value);
            if ($date === false) {
                return "The date must have format YYYY-MM-DD";
            }
        }



        return "";
    } 
    
    private function validateImage(mixed $value): string{
        if (!is_string($value)) {
            return 'The value must be a image';
        }
        if (filter_var($value, FILTER_VALIDATE_URL) === false && $value !== "") {
            return 'The value must be a valid URL';
        }

        return "";
    }

    private function validatePeriod(mixed $value): string{
        if (!is_array($value) || !array_key_exists('from', $value) || !array_key_exists('to', $value)) {
            return 'An incorrect period has been entered';
        }

        $dateFrom = null;
        $dateTo = null;

        if ($value['from'] !== '') {
            $dateFrom = \DateTimeImmutable::createFromFormat('!Y-m-d', $value['from']);

            $errors = \DateTimeImmutable::getLastErrors();

            if ($dateFrom === false || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))) {
                return 'The incorrect start of the period has been entered';
            }
        }

        if ($value['to'] !== '') {
            $dateTo = \DateTimeImmutable::createFromFormat('!Y-m-d', $value['to']);

            $errors = \DateTimeImmutable::getLastErrors();

            if ($dateTo === false || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))) {
                return 'The incorrect end of the period has been entered';
            }
        }

        if ($dateFrom !== null && $dateTo !== null) {
            if ($dateTo <= $dateFrom) {
                return 'The end date must be later than the start date';
            }
        }

        return '';
      }

     private function validateOneOfMany(mixed $value, array $options): string {
        if ($value !== null && !is_string($value)) {
            return 'The value must be a string or null';
        }
        if ($value !== null && !in_array($value, $options, true)) {
            return 'The selected value is not available in the options';
        }
        return '';
    }

    private function addValidationError(array &$errors, int $attributeId, string $errorMess){
        if ($errorMess !== ""){
            $errors[] = [
                'attributeId' => $attributeId,
                'message' => $errorMess
            ];
        }
    }
}