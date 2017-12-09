<?php
/**
 * Created by PhpStorm.
 * User: frankie
 * Date: 8/12/17
 * Time: 20:41
 */

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