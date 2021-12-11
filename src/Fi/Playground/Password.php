<?php

namespace Fi\Playground;

class Password
{
    /**
     * @var string
     */
    private $password;

    /**
     * Password constructor.
     */
    public function __construct(string $password)
    {
        $this->password = $password;
    }
}
