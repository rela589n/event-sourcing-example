<?php

declare(strict_types=1);


namespace App\Models\User\Events;

use App\Models\User\Doctrine\User;
use App\Models\User\VO\Login;
use App\Models\User\VO\Password;

class UserRegistered extends UserEvent
{
    private Login $login;

    private Password $password;

    private function __construct(User $user, Login $login, Password $password)
    {
        parent::__construct($user);
        $this->login = $login;
        $this->password = $password;
    }

    public static function withCredentials(User $user, Login $login, Password $password): self
    {
        return new self($user, $login, $password);
    }

    public function getLogin(): Login
    {
        return $this->login;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function NAME(): string
    {
        return 'user_registered';
    }
}
