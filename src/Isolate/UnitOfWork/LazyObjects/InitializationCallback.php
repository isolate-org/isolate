<?php

namespace Isolate\UnitOfWork\LazyObjects;

use Isolate\LazyObjects\Proxy\LazyProperty;
use Isolate\UnitOfWork\Object\PropertyAccessor;
use Isolate\LazyObjects\Proxy\LazyProperty\InitializationCallback as BaseCallback;
use Isolate\UnitOfWork\Object\SnapshotMaker;

final class InitializationCallback implements BaseCallback
{
    /**
     * @var PropertyAccessor
     */
    private $propertyAccessor;

    /**
     * @var
     */
    private $snapshot;

    /**
     * @var LazyProperty
     */
    private $lazyProperty;

    /**
     * @var SnapshotMaker
     */
    private $snapshotMaker;

    /**
     * @param SnapshotMaker $snapshotMaker
     * @param PropertyAccessor $propertyAccessor
     * @param LazyProperty $lazyProperty
     * @param $snapshot
     */
    public function __construct(SnapshotMaker $snapshotMaker, PropertyAccessor $propertyAccessor, LazyProperty $lazyProperty, $snapshot)
    {
        $this->propertyAccessor = $propertyAccessor;
        $this->lazyProperty = $lazyProperty;
        $this->snapshot = $snapshot;
        $this->snapshotMaker = $snapshotMaker;
    }

    /**
     * @param mixed $defaultValue
     * @param mixed $newValue
     * @param mixed $targetObject
     */
    public function execute($defaultValue, $newValue, $targetObject)
    {
        $newValueSnapshot = $this->snapshotMaker->makeSnapshotOf($newValue);
        $this->propertyAccessor->setValue($this->snapshot, (string) $this->lazyProperty->getName(), $newValueSnapshot);
    }
}
