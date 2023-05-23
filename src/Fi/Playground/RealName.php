<?php

namespace Fi\Playground;

class RealName
{
    private string $firstName;
    private string $lastname;

    public function __construct(string $firstName, string $lastname)
    {
        $this->firstName = $firstName;
        $this->lastname = $lastname;
    }
}
