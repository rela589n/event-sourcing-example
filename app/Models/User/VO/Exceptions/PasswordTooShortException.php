<?php

declare(strict_types=1);

namespace App\Models\User\VO\Exceptions;

final class PasswordTooShortException extends \RuntimeException
{
    private string $sourcePassword;
    private int $minLength;

    public function __construct(string $sourcePassword, int $minLength)
    {
        parent::__construct(
            trans(
                'exceptions/value-objects/password.too-short',
                [
                    'minLength'     => $minLength,
                    'currentLength' => mb_strlen($sourcePassword),
                ]
            )
        );

        $this->sourcePassword = $sourcePassword;
        $this->minLength = $minLength;
    }

    public function getSourcePassword(): string
    {
        return $this->sourcePassword;
    }

    public function getMinLength(): int
    {
        return $this->minLength;
    }
}
