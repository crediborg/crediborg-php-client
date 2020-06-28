<?php

namespace CrediBorg\Exceptions;

use Exception;

class ValidationException extends Exception
{
    /**
     * Error Fields
     *
     * @var object
     */
    public $fields;

    /**
     * Class Constructor
     *
     * @param object $fields
     */
    public function __construct(object $fields)
    {
        $this->fields = $fields;
        parent::__construct('Resource Validation Failed at Destination Server');
    }
}