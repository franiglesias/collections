<?php

namespace Fi\Playground;

class RealName
{
    /**
     * @var string
     */
    private $firstName;
    /**
     * @var string
     */
    private $lastname;

    public function __construct(string $firstName, string $lastname)
    {
        $this->firstName = $firstName;
        $this->lastname = $lastname;
    }
}
