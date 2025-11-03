<?php

declare(strict_types=1);

namespace App\ObjectMapper;

use App\Entity\ProjectColumn;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\ObjectMapper\Exception\MappingException;
use Symfony\Component\ObjectMapper\TransformCallableInterface;

final readonly class ProjectColumnsCollectionTransformer implements TransformCallableInterface
{
    public function __invoke(mixed $value, object $source, ?object $target): mixed
    {
        if (!$value instanceof Collection) {
            throw new MappingException('Expects a collection');
        }

        return array_map(fn (ProjectColumn $projectColumn) => ['name' => $projectColumn->getName()], $value->toArray());
    }
}
