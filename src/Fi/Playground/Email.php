<?php

namespace Fi\Playground;

class Email
{
    /**
     * @var string
     */
    private $email;


    /**
     * Email constructor.
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
