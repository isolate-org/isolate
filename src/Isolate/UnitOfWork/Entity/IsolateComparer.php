<?php

namespace Isolate\UnitOfWork\Entity;

use Isolate\LazyObjects\WrappedObject;

class IsolateComparer extends Comparer
{
    public function areEqual(Definition $entityDefinition, $firstEntity, $secondEntity)
    {
        $firstTarget = ($firstEntity instanceof WrappedObject) ? $firstEntity->getWrappedObject() : $firstEntity;
        $secondTarget = ($secondEntity instanceof WrappedObject) ? $secondEntity->getWrappedObject() : $secondEntity;

        return parent::areEqual($entityDefinition, $firstTarget, $secondTarget);
    }
}
