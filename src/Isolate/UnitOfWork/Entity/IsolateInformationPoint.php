<?php

namespace Isolate\UnitOfWork\Entity;

use Isolate\LazyObjects\WrappedObject;

class IsolateInformationPoint extends InformationPoint
{
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

    public function getDefinition($entity)
    {
        $targetEntity = ($entity instanceof WrappedObject) ? $entity->getWrappedObject() : $entity;

        return parent::getDefinition($targetEntity);
    }

    public function hasDefinition($entity)
    {
        $targetEntity = ($entity instanceof WrappedObject) ? $entity->getWrappedObject() : $entity;

        return parent::hasDefinition($targetEntity);
    }
}
