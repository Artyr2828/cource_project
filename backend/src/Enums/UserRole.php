<?php
namespace App\Enums;

enum UserRole: string{
    case CANDIDATE = 'candidate';
    case RECRUITER = 'recruiter';
    case ADMINISTRATOR = 'administrator';
}
