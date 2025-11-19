<?php

abstract class Hobby
{
    protected $name;

    public function __construct($name, $fotografia)
    {
        $this->name = $name;
        $this->fotografia = $fotografia;
    }

    abstract public function getName();

    abstract public function setName($name);

    abstract public function __toString();
}
