<?php


namespace App\Request\User;

use Symfony\Component\Validator\Constraints as Assert;

class Register
{
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=8)
     */
    private $password;

    public function __construct(?string $email, ?string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['email']) ? $data['email'] : null,
            isset($data['password']) ? $data['password'] : null,
        );
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

}
