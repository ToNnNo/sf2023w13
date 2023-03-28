<?php

namespace App\Classes;

// Value Object
class Person
{
    private string $firstname;
    private string $lastname;

    // PHP 8
    /*public function __construct(
        public readonly string $firstname,
        public readonly string $lastname
    ) {}*/

    public function __construct($firstname, $lastname)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getFullname(): string
    {
        return sprintf('%s %s', $this->firstname, $this->lastname);
    }
}
