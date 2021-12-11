<?php

namespace Fi\Playground;

class Password
{
    private string $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }
}
