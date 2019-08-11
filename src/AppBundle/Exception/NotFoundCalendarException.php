<?php

namespace AppBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class NotFoundCalendarException extends HttpException
{
    public function __construct($message = 'Calendar not found')
    {
        parent::__construct(404, $message);
    }
}
