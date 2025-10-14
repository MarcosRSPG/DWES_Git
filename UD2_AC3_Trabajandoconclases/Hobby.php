<?php

abstract class Hobby
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    abstract public function getName();

    abstract public function setName($name);

    abstract public function __toString();

    abstract public function __destruct();
}
