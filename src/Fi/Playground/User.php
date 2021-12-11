<?php

namespace Fi\Playground;

class User
{
    /**
     * @var RealName
     */
    private $realName;
    /**
     * @var Email
     */
    private $email;
    /**
     * @var Password
     */
    private $password;

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

    public function getEmailDomain()
    {
        return substr($this->getEmail(), strpos($this->getEmail(), '@') + 1);
    }
}
