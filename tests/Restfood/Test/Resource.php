<?php

namespace Restfood\Test;

use Ramsey\Uuid\Uuid;
use Restfood\Entity\ResourceInterface;


class Resource implements ResourceInterface {

    public $id;
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
        $this->id = Uuid::uuid4()->toString();
    }

    public static function obtainIdentifierFieldName()
    {
        return 'id';
    }

    public function obtainIdentifier()
    {
        return $this->id;
    }

    public function obtainName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}