<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\User;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(target: User::class, source: User::class)]
class UserDto
{
    private int $id;
    private string $email;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): UserDto
    {
        $this->email = $email;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): UserDto
    {
        $this->id = $id;

        return $this;
    }
}
