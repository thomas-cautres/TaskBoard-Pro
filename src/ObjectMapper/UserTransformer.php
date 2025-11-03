<?php

declare(strict_types=1);

namespace App\ObjectMapper;

use App\Entity\User;
use Symfony\Component\ObjectMapper\Exception\MappingException;
use Symfony\Component\ObjectMapper\TransformCallableInterface;

class UserTransformer implements TransformCallableInterface
{
    public function __invoke(mixed $value, object $source, ?object $target): mixed
    {
        if (!$value instanceof User) {
            throw new MappingException('Expects a User object');
        }

        return $value->getEmail();
    }
}
