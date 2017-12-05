<?php

namespace Uptodown\Collection\Tests;

use Uptodown\EqualableInterface\Equalable;

class MockObject implements Equalable
{
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function equals($object)
    {
        return $this->value == $object->value;
    }
}
