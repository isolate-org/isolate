<?php

namespace Isolate\UnitOfWork\Entity;

use Isolate\LazyObjects\WrappedObject;

class IsolateComparer extends Comparer
{
    /**
     * @param $firstEntity
     * @param $secondEntity
     * @return bool
     * @throws \Isolate\UnitOfWork\Exception\InvalidArgumentException
     */
    public function areEqual($firstEntity, $secondEntity)
    {
        $firstTarget = ($firstEntity instanceof WrappedObject) ? $firstEntity->getWrappedObject() : $firstEntity;
        $secondTarget = ($secondEntity instanceof WrappedObject) ? $secondEntity->getWrappedObject() : $secondEntity;

        return parent::areEqual($firstTarget, $secondTarget);
    }
}
