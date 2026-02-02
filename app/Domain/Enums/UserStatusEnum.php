<?php

namespace App\Domain\Enums;

enum UserStatusEnum: string
{
    case ACTIVE = 'ACTIVE';
    case INACTIVE = 'INACTIVE';
    case INVITED = 'INVITED';
}
