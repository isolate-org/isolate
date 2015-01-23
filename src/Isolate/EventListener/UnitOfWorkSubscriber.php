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
            Events::PRE_REGISTER_OBJECT => 'onObjectRegistration',
            Events::PRE_GET_OBJECT_STATE => 'onGetObjectState',
            Events::PRE_REMOVE_OBJECT => 'onObjectRemove',
        ];
    }

    public function onObjectRegistration(PreRegister $event)
    {
        $object = $event->getObject();
        if ($object instanceof WrappedObject) {
            $event->replaceObject($object->getWrappedObject());
        }
    }

    public function onGetObjectState(PreGetState $event)
    {
        $object = $event->getObject();
        if ($object instanceof WrappedObject) {
            $event->replaceObject($object->getWrappedObject());
        }
    }

    public function onObjectRemove(PreRemove $event)
    {
        $object = $event->getObject();
        if ($object instanceof WrappedObject) {
            $event->replaceObject($object->getWrappedObject());
        }
    }
}
