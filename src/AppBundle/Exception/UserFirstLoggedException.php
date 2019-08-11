<?php

namespace AppBundle\Exception;

use Symfony\Component\Security\Core\Exception\AuthenticationException;

class UserFirstLoggedException extends AuthenticationException
{
    public function getMessageKey()
    {
        return 'Another user has logged this session out.';
    }

}