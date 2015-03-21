<?php

namespace Isolate\UnitOfWork\Object;

use Isolate\LazyObjects\Proxy\LazyProperty;
use Isolate\LazyObjects\WrappedObject;
use Isolate\UnitOfWork\LazyObjects\InitializationCallback;

class IsolateRegistry implements Registry
{
    /**
     * @var SnapshotMaker
     */
    private $snapshotMaker;

    /**
     * @var PropertyCloner
     */
    private $propertyCloner;

    /**
     * @var PropertyAccessor
     */
    private $propertyAccessor;

    /**
     * @var array
     */
    private $objects;

    /**
     * @var array
     */
    private $snapshots;

    /**
     * @var array
     */
    private $removed;

    /**
     * @param SnapshotMaker $snapshotMaker
     * @param PropertyCloner $propertyCloner
     */
    public function __construct(SnapshotMaker $snapshotMaker, PropertyCloner $propertyCloner)
    {
        $this->snapshotMaker = $snapshotMaker;
        $this->propertyCloner = $propertyCloner;
        $this->propertyAccessor = new PropertyAccessor();
        $this->objects = [];
        $this->snapshots = [];
        $this->removed = [];
    }

    /**
     * {@inheritdoc}
     */
    public function isRegistered($object)
    {
        $targetObject = ($object instanceof WrappedObject) ? $object->getWrappedObject() : $object;

        return array_key_exists($this->getId($targetObject), $this->objects);
    }

    /**
     * {@inheritdoc}
     */
    public function register($object)
    {
        if ($object instanceof WrappedObject) {
            $targetObject = $object->getWrappedObject();
        } else {
            $targetObject = $object;
        }

        $this->objects[$this->getId($targetObject)] = $object;
        $this->snapshots[$this->getId($targetObject)] = $this->snapshotMaker->makeSnapshotOf($targetObject);

        if ($object instanceof WrappedObject) {
            $this->setLazyPropertiesCallback($object);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSnapshot($object)
    {
        $targetObject = ($object instanceof WrappedObject) ? $object->getWrappedObject() : $object;

        return $this->snapshots[$this->getId($targetObject)];
    }

    /**
     * {@inheritdoc}
     */
    public function makeNewSnapshots()
    {
        foreach ($this->objects as $id => $object) {
            $targetObject = ($object instanceof WrappedObject) ? $object->getWrappedObject() : $object;

            $this->snapshots[$id] = $this->snapshotMaker->makeSnapshotOf($targetObject);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isRemoved($object)
    {
        $targetObject = ($object instanceof WrappedObject) ? $object->getWrappedObject() : $object;

        return array_key_exists($this->getId($targetObject), $this->removed);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($object)
    {
        if (!$this->isRegistered($object)) {
            $this->register($object);
        }

        $targetObject = ($object instanceof WrappedObject) ? $object->getWrappedObject() : $object;

        $this->removed[$this->getId($targetObject)] = true;
    }

    /**
     * {@inheritdoc}
     */
    public function cleanRemoved()
    {
        foreach ($this->removed as $id => $object) {
            unset($this->snapshots[$id]);
            unset($this->objects[$id]);
        }

        $this->removed = [];
    }

    /**
     * {@inheritdoc}
     */
    public function all()
    {
        $objects = [];
        foreach ($this->objects as $object) {
            $objects[] = ($object instanceof WrappedObject) ? $object->getWrappedObject() : $object;
        }

        return $objects;
    }

    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->removed = [];

        foreach ($this->snapshots as $id => $objectSnapshot) {
            if ($this->objects[$id] instanceof WrappedObject) {
                $this->propertyCloner->cloneProperties($this->objects[$id]->getWrappedObject(), $objectSnapshot);
            } else {
                $this->propertyCloner->cloneProperties($this->objects[$id], $objectSnapshot);
            }
        }
    }

    /**
     * @param WrappedObject $lazyObject
     */
    private function setLazyPropertiesCallback(WrappedObject $lazyObject)
    {
        foreach ($lazyObject->getLazyProperties() as $lazyProperty) {
            $lazyProperty->setInitializationCallback(new InitializationCallback(
                $this->snapshotMaker,
                $this->propertyAccessor,
                $lazyProperty,
                $this->snapshots[$this->getId($lazyObject->getWrappedObject())]
            ));
        }
    }

    /**
     * @param $object
     * @return string
     */
    private function getId($object)
    {
        return spl_object_hash($object);
    }
}
