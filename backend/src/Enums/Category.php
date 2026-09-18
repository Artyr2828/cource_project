<?php
namespace App\Enums;

enum Category: string{
    case CERTIFICATION = 'certification';
    case DOMAIN_KNOWLEDGE = 'domain_knowledge';
    case PERSONAL_INFORMATION = 'personal_information';
    case SOFT_SKILLS = 'soft_skills';
}