<?php

namespace Isolate\UnitOfWork\Entity;

use Isolate\LazyObjects\WrappedObject;
use Isolate\UnitOfWork\Entity\Identifier\PropertyValueIdentifier;

class IsolateIdentifier extends PropertyValueIdentifier
{
    public function isEntity($object)
    {
        $targetObject = ($object instanceof WrappedObject) ? $object->getWrappedObject() : $object;

        return parent::isEntity($targetObject);
    }

    public function isPersisted($entity)
    {
        $targetEntity = ($entity instanceof WrappedObject) ? $entity->getWrappedObject() : $entity;

        return parent::isPersisted($targetEntity);
    }

    public function getIdentity($entity)
    {
        $targetEntity = ($entity instanceof WrappedObject) ? $entity->getWrappedObject() : $entity;

        return parent::getIdentity($targetEntity);
    }
}
