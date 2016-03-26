<?php

namespace Restfood\Exception;

use RuntimeException;

class ResourceNotFoundException extends RuntimeException
{
    public static function identifierNotFound($resourceName, $identifier)
    {
        $messagePattern = 'Does not exist any %s with the given identifier "%s"';
        return new static(sprintf($messagePattern, $resourceName, $identifier));
    }
}