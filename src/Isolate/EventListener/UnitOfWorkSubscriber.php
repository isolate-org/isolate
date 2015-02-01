<?php

namespace Isolate\EventListener;

use Isolate\LazyObjects\WrappedObject;
use Isolate\UnitOfWork\Event\PreGetState;
use Isolate\UnitOfWork\Event\PreRegister;
use Isolate\UnitOfWork\Event\PreRemove;
use Isolate\UnitOfWork\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UnitOfWorkSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            Events::PRE_REGISTER_ENTITY => 'onObjectRegistration',
            Events::PRE_GET_ENTITY_STATE => 'onGetObjectState',
            Events::PRE_REMOVE_ENTITY => 'onObjectRemove',
        ];
    }

    public function onObjectRegistration(PreRegister $event)
    {
        $object = $event->getEntity();
        if ($object instanceof WrappedObject) {
            $event->replaceEntity($object->getWrappedObject());
        }
    }

    public function onGetObjectState(PreGetState $event)
    {
        $object = $event->getEntity();
        if ($object instanceof WrappedObject) {
            $event->replaceEntity($object->getWrappedObject());
        }
    }

    public function onObjectRemove(PreRemove $event)
    {
        $object = $event->getEntity();
        if ($object instanceof WrappedObject) {
            $event->replaceEntity($object->getWrappedObject());
        }
    }
}
