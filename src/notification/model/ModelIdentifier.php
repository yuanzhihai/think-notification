<?php

namespace yzh52521\notification\model;

class ModelIdentifier
{
    public $class;

    public $id;

    public function __construct($class, $id)
    {
        $this->id    = $id;
        $this->class = $class;
    }
}