<?php

namespace Modules\User\Exceptions;

class UserUnauthorizedException extends \Exception
{
    public function __construct()
    {
        parent::__construct(message: 'unauthorized');
    }
}
