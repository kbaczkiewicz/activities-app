<?php

namespace App\Request\User;

use App\Validation\Constraints\PasswordsAreSame;
use App\Validation\Constraints\UserPasswordMatches;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @PasswordsAreSame()
 */
class ChangePassword
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min = 8)
     * @UserPasswordMatches()
     *
     */
    private $oldPassword;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min = 8)
     */
    private $password;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min = 8)
     */
    private $passwordRepeat;

    public function __construct(?string $oldPassword, ?string $password, ?string $passwordRepeat)
    {
        $this->oldPassword = $oldPassword;
        $this->password = $password;
        $this->passwordRepeat = $passwordRepeat;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['oldPassword']) ? $data['oldPassword'] : null,
            isset($data['password']) ? $data['password'] : null,
            isset($data['passwordRepeat']) ? $data['passwordRepeat'] : null,
        );
    }

    public function getOldPassword(): string
    {
        return $this->oldPassword;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPasswordRepeat(): string
    {
        return $this->passwordRepeat;
    }
}
