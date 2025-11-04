<?php

declare(strict_types=1);

namespace App\ObjectMapper;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\ObjectMapper\TransformCallableInterface;

/**
 * @implements TransformCallableInterface<object, object>
 */
final readonly class UserTransformer implements TransformCallableInterface
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(mixed $value, object $source, ?object $target): mixed
    {
        if ($value instanceof User) {
            return $this->toDto($value);
        }

        if (is_string($value)) {
            return $this->toEntity($value);
        }

        if (null === $value) {
            return null;
        }

        throw new \InvalidArgumentException(sprintf('Expected User or string, got %s', get_debug_type($value)));
    }

    private function toDto(mixed $value): string
    {
        if (!$value instanceof User) {
            throw new \InvalidArgumentException('Expected User instance');
        }

        return $value->getEmail();
    }

    private function toEntity(mixed $value): User
    {
        if (!is_string($value)) {
            throw new \InvalidArgumentException('Expected string (email)');
        }

        $user = $this->userRepository->findOneBy(['email' => $value]);

        if (!$user) {
            throw new \RuntimeException(sprintf('User with email "%s" not found', $value));
        }

        return $user;
    }
}
