<?php

namespace Isolate\Tests\Double;

use Isolate\LazyObjects\Proxy\LazyProperty;
use Isolate\LazyObjects\Proxy\MethodReplacement;
use Isolate\LazyObjects\WrappedObject;
use Isolate\UnitOfWork\Tests\Double\EntityFake;

class ProxyFake extends \Isolate\Tests\Double\EntityFake implements WrappedObject
{
    private $wrappedObject;
    /**
     * @var array
     */
    private $properties;
    /**
     * @var array|\Isolate\LazyObjects\Proxy\MethodReplacement[]
     */
    private $methods;

    /**
     * @param EntityFake|EntityFake $wrappedObject
     * @param array|LazyProperty[] $properties
     * @param array|MethodReplacement[] $methods
     */
    public function __construct(\Isolate\Tests\Double\EntityFake $wrappedObject, $properties = [], $methods = [])
    {
        $this->wrappedObject = $wrappedObject;
        $this->properties = $properties;
        $this->methods = $methods;
    }

    public function changeLastName($newLastName)
    {
        $this->wrappedObject->changeLastName($newLastName);
    }

    public function changeFirstName($newName)
    {
        $this->wrappedObject->changeFirstName($newName);
    }

    /**
     * @return EntityFake
     */
    public function getWrappedObject()
    {
        return $this->wrappedObject;
    }

    /**
     * @return array|LazyProperty[]
     */
    public function getLazyProperties()
    {
        return $this->properties;
    }

    /**
     * @return array|MethodReplacement[]
     */
    public function getMethodReplacements()
    {
        return $this->methods;
    }
}
