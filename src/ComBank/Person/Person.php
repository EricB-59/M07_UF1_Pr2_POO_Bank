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
        if ($this->validateEmail($email)) {
            $this->email = $email;
            pl('validating email: '.$this->getEmail());
            pl('Email is valid');
        } else {
            pl('validating email: '.$email);
            pl('Error: Invalid email address: '.$email);
        }
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