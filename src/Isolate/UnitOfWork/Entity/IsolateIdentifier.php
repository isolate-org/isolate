<?php

namespace Isolate\UnitOfWork\Entity;

use Isolate\LazyObjects\WrappedObject;
use Isolate\UnitOfWork\Entity\Identifier\EntityIdentifier;

class IsolateIdentifier extends EntityIdentifier
{
    /**
     * @param $object
     * @return bool
     */
    public function isEntity($object)
    {
        $targetObject = ($object instanceof WrappedObject) ? $object->getWrappedObject() : $object;

        return parent::isEntity($targetObject);
    }

    /**
     * @param mixed $entity
     * @return bool
     */
    public function isPersisted($entity)
    {
        $targetEntity = ($entity instanceof WrappedObject) ? $entity->getWrappedObject() : $entity;

        return parent::isPersisted($targetEntity);
    }

    /**
     * @param $entity
     * @return mixed
     */
    public function getIdentity($entity)
    {
        $targetEntity = ($entity instanceof WrappedObject) ? $entity->getWrappedObject() : $entity;

        return parent::getIdentity($targetEntity);
    }
}
