<?php

namespace Restfood\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class InvalidDataException extends \RuntimeException
{
    public static function violateConstraints(ConstraintViolationListInterface $violations)
    {
        return new static("Given data is not valid:\n" . static::compose($violations));
    }

    public static function invalidJson()
    {
        return new static("Given data is not a valid json document");
    }

    private static function compose(ConstraintViolationListInterface $constraintViolations)
    {
        $result = '';
        $printPattern = "- %s: %s\n";
        foreach ($constraintViolations as $violation) {
            $result .= sprintf($printPattern, $violation->getPropertyPath(), $violation->getMessage());
        }
        return $result;
    }
}