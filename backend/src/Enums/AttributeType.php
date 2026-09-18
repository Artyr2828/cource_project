<?php 
namespace App\Enums;

enum AttributeType: string
{
    case STRING = 'string';
    case TEXT = 'text';
    case IMAGE = 'image';
    case NUMERIC = 'numeric';
    case DATE = 'date';
    case PERIOD = 'period';
    case BOOLEAN = 'boolean';
    case ONE_OF_MANY = 'one_of_many';
}