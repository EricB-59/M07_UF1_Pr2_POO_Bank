<?php namespace ComBank\Person;

use ComBank\Support\Traits\ApiTraits;

class Person 
{
    use ApiTraits;
    private $name;
    private $idCard;
    private $email;

    function __construct($name, $idCard, $email){
        $this->name = $name;
        $this->idCard = $idCard;
        $this->email = $email;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getIdCard()
    {
        return $this->idCard;
    }

    public function getEmail()
    {
        return $this->email;
    }
}