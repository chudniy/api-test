<?php

namespace AppBundle\Exception\Api;

use AppBundle\Exception\JsonHttpException;
use Symfony\Component\HttpFoundation\Response;

final class ApiException extends JsonHttpException
{
    const ERROR_MESSAGES = [
        '1' => 'Internal server error',
        '101' => 'Invalid username/password.',
        '102' => 'Invalid query.',
        '125' => 'Email address format is invalid.',
        '200' => 'username is required.',
        '201' => 'password is required.',
        '202' => 'Account already exists for this username',
        '203' => 'Account already exists for this email address',
    ];

    public function __construct($code = Response::HTTP_BAD_REQUEST, $message = null)
    {
        parent::__construct($code, $message ? $message : self::ERROR_MESSAGES[$code], ['code' => $code]);
    }
}
