<?php

namespace App\Domain\Enums;

enum UserTypeEnum: string
{
    case ADMIN = 'ADMIN';
    case INTERNAL_USER = 'INTERNAL_USER';
    case EXTERNAL_USER = 'EXTERNAL_USER';
}
