<?php
namespace App\Enums;

enum UserStatusEnums: string
{
    case ACTIVE  = 'active';
    case BLOCKED = 'blocked';
}
