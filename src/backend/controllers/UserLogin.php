<?php
namespace Controllers;
use Utils\Login_interface;

class UserLogin implements Login_interface
{
    public $username;
    public $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }
    public function __destruct(){
        echo $this->username . " is Logged in";
    }
    public function login(): string{
        return json_encode(['username' => $this->username, 'password' => $this->password]);
    }
    public function logout(): string{
        return json_encode(['username' => $this->username, 'password' => $this->password]);
    }
}
