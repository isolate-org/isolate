<?php

namespace Isolate\Tests\LazyObjects;

use Isolate\LazyObjects\Proxy\ClassName;
use Isolate\LazyObjects\Proxy\Definition;
use Isolate\LazyObjects\Proxy\LazyProperty;
use Isolate\LazyObjects\Proxy\Method;
use Isolate\LazyObjects\Proxy\Methods;
use Isolate\LazyObjects\Proxy\Property\Name;
use Isolate\Tests\Double\EntityFake;
use Isolate\Tests\LazyObjects\PropertyInitializers\ItemInitializer;
use Isolate\Tests\ProxyDefinition\MethodReplacement\GetItemReplacementStub;

class EntityFakeBuilder
{
    public static function buildDefinition()
    {
        $definition = new Definition(new ClassName(EntityFake::getClassName()));

        return $definition;
    }
}
