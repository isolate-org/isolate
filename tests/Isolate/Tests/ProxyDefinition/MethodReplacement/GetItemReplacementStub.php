<?php

namespace Isolate\Tests\ProxyDefinition\MethodReplacement;

use Isolate\LazyObjects\Proxy\Method\Replacement;

class GetItemReplacementStub implements Replacement
{
    /**
     * @var mixed
     */
    private $getItemResult;

    /**
     * @param $getItemResult
     */
    public function __construct($getItemResult = null)
    {
        $this->getItemResult = $getItemResult;
    }

    /**
     * @param array $parameters
     * @param mixed $object
     * @return mixed
     */
    public function call(array $parameters, $object)
    {
        return $this->getItemResult;
    }
}
