<?php

namespace Isolate\Tests\ClassDefinition;

use Isolate\Tests\Double\EntityFake;
use Isolate\UnitOfWork\Entity\ClassName;
use Isolate\UnitOfWork\Entity\Definition;

class EntityFakeBuilder
{
    public static function buildDefinition()
    {
        $definition = new Definition(
            new ClassName(EntityFake::getClassName()),
            new Definition\Identity("id")
        );

        $definition->setObserved([
            new Definition\Property("firstName"),
            new Definition\Property("lastName")
        ]);

        return $definition;
    }
}
