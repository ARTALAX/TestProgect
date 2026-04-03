<?php

namespace Modules\User\Enums;

enum UserRole: string
{
    case GUEST = 'guest';
    case USER = 'user';
    case ADMIN = 'admin';
}
