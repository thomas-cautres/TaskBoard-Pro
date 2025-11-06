<?php

declare(strict_types=1);

namespace App\ObjectMapper;

use Symfony\Component\ObjectMapper\Exception\MappingException;
use Symfony\Component\ObjectMapper\ObjectMapper;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\ObjectMapper\TransformCallableInterface;

/**
 * @implements TransformCallableInterface<object, object>
 */
final class CollectionTransformer implements TransformCallableInterface
{
    public function __construct(
        private ObjectMapperInterface $objectMapper = new ObjectMapper(),
    ) {
    }

    public function __invoke(mixed $value, object $source, ?object $target): mixed
    {
        if (!is_iterable($value)) {
            throw new MappingException(\sprintf('The MapCollection transform expects an iterable, "%s" given.', get_debug_type($value)));
        }

        $values = [];

        foreach ($value as $k => $v) {
            /* @phpstan-ignore-next-line */
            $values[$k] = $this->objectMapper->map($v);
        }

        return $values;
    }
}
