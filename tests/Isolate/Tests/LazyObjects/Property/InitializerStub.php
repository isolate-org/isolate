<?php

namespace Isolate\Tests\LazyObjects\Property;

use Isolate\LazyObjects\Proxy\Property\ValueInitializer;

class InitializerStub implements ValueInitializer
{
    /**
     * @var
     */
    private $initializationResult;

    /**
     * @param $initializationResult
     */
    public function __construct($initializationResult)
    {
        $this->initializationResult = $initializationResult;
    }

    public function initialize($object, $defaultPropertyValue)
    {
        return $this->initializationResult;
    }
}
