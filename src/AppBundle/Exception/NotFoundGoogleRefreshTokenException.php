<?php

namespace AppBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class NotFoundGoogleRefreshTokenException extends HttpException
{
    public function __construct()
    {
        parent::__construct(404, 'Not found refresh token.');
    }
}
