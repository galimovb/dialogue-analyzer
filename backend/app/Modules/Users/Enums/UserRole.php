<?php

namespace App\Modules\Users\Enums;

enum UserRole: string
{
    case Client = 'client';
    case Manager = 'manager';
    case Admin = 'admin';
}
