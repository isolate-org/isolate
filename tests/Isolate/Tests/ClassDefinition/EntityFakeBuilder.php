<?php

namespace Isolate\Tests\ClassDefinition;

use Isolate\UnitOfWork\ObjectClass\Definition;
use Isolate\UnitOfWork\ObjectClass\IdDefinition;
use Isolate\Tests\Double\EntityFake;

class EntityFakeBuilder
{
    public static function buildDefinition()
    {
        return new Definition(
            EntityFake::getClassName(),
            new IdDefinition("id"),
            ["firstName", "lastName"]
        );
    }
}
