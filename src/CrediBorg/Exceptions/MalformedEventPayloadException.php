<?php

namespace CrediBorg\Exceptions;

use Exception;

class MalformedEventPayloadException extends Exception
{
    /**
     * Class Constructor
     */
    public function __construct()
    {
        parent::__construct('Event Payload is Malformed');
    }
}
