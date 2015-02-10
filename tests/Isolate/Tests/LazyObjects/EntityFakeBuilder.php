<?php

namespace Isolate\Tests\LazyObjects;

use Isolate\LazyObjects\Proxy\ClassName;
use Isolate\LazyObjects\Proxy\Definition;
use Isolate\LazyObjects\Proxy\LazyProperty;
use Isolate\LazyObjects\Proxy\Method;
use Isolate\LazyObjects\Proxy\Property\Name;
use Isolate\Tests\Double\EntityFake;
use Isolate\Tests\LazyObjects\Property\InitializerStub;

class EntityFakeBuilder
{
    public static function buildDefinition($itemsInitializationResult = null)
    {
        $definition = new Definition(
            new ClassName(EntityFake::getClassName()),
            [
                new LazyProperty(
                    new Name("items"),
                    new InitializerStub($itemsInitializationResult),
                    [new Method('getItems')]
                )
            ]
        );

        return $definition;
    }
}
