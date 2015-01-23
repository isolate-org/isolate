<?php

namespace Isolate\Tests\ProxyDefinition;

use Isolate\LazyObjects\Proxy\ClassName;
use Isolate\LazyObjects\Proxy\Definition;
use Isolate\LazyObjects\Proxy\Method;
use Isolate\LazyObjects\Proxy\Methods;
use Isolate\Tests\Double\EntityFake;
use Isolate\Tests\ProxyDefinition\MethodReplacement\GetItemReplacementStub;

class EntityFakeBuilder
{
    public static function buildDefinition()
    {
        return new Definition(
            new ClassName(EntityFake::getClassName()),
            new Methods([
                new Method("getItems", new GetItemReplacementStub())
            ])
        );
    }
}
