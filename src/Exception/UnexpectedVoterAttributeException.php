<?php

namespace App\Exception;

use Exception;

class UnexpectedVoterAttributeException extends Exception
{
    public function __construct(string $attribute)
    {
        parent::__construct($attribute);
    }
}