<?php

namespace AppBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class BusyCalendarException extends HttpException
{
    public function __construct($message = 'This time period is already booked for another appointment. Please select another one')
    {
        parent::__construct(400, $message);
    }
}
