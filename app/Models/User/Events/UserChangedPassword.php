<?php

declare(strict_types=1);

namespace App\Models\User\Events;

use App\Models\User\Doctrine\User;
use App\Models\User\VO\Password;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
class UserChangedPassword extends UserEvent
{
    private Password $newPassword;

    public function __construct(User $user, Password $newPassword)
    {
        parent::__construct($user);
        $this->newPassword = $newPassword;
    }

    public static function with(User $user, Password $newPassword): self
    {
        return new self($user, $newPassword);
    }

    public function getNewPassword(): Password
    {
        return $this->newPassword;
    }

    public static function NAME(): string
    {
        return 'user_changed_password';
    }
}
