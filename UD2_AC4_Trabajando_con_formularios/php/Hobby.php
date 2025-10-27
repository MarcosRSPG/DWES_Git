<?php

abstract class Hobby
{
    protected $name;

    protected $fotografia;

    public function __construct($name, $fotografia)
    {
        $this->name = $name;
        $this->fotografia = $fotografia;
    }

    abstract public function getName();

    abstract public function setName($name);

    abstract public function getFotografia();

    abstract public function setFotografia($fotografia);

    abstract public function __toString();

    abstract public function __destruct();
}
