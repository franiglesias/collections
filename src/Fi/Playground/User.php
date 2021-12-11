<?php

namespace Fi\Playground;

class User
{
    private RealName $realName;
    private Email $email;
    private Password $password;

    public function __construct(RealName $realName, Email $email, Password $password)
    {
        $this->realName = $realName;
        $this->email = $email;
        $this->password = $password;
    }

    public function getEmail(): string
    {
        return $this->email->getEmail();
    }

    public function getEmailDomain(): string
    {
        return substr($this->getEmail(), strpos($this->getEmail(), '@') + 1);
    }
}
