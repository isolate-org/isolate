<?php

namespace spec\Isolate\UnitOfWork\Object;

use Isolate\LazyObjects\Proxy\LazyProperty;
use Isolate\Tests\Double\EntityFake;
use Isolate\Tests\Double\ProxyFake;
use Isolate\UnitOfWork\LazyObjects\InitializationCallback;
use Isolate\UnitOfWork\Object\PropertyCloner;
use Isolate\UnitOfWork\Object\SnapshotMaker;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class IsolateRegistrySpec extends ObjectBehavior
{
    function let(SnapshotMaker $cloner, PropertyCloner $propertyCloner)
    {
        $cloner->makeSnapshotOf(Argument::type('object'))->will(function ($args) {
            $object = $args[0];
            return clone $object;
        });

        $this->beConstructedWith($cloner, $propertyCloner);
    }

    function it_knows_when_object_was_not_registered()
    {
        $this->isRegistered(new ProxyFake(new EntityFake()))->shouldReturn(false);
    }

    function it_knows_that_object_was_registered_even_when_proxy_was_registered()
    {
        $entity = new EntityFake();
        $proxy = new ProxyFake($entity);

        $this->register($proxy);

        $this->isRegistered($entity)->shouldReturn(true);
    }

    function it_set_initialization_callback_to_lazy_properties(LazyProperty $lazyProperty)
    {
        $entity = new EntityFake();
        $proxy = new ProxyFake($entity, [$lazyProperty->getWrappedObject()]);

        $this->register($proxy);
        $lazyProperty->setInitializationCallback(Argument::type('Isolate\UnitOfWork\LazyObjects\InitializationCallback'))
            ->shouldHaveBeenCalled();
        
        $this->isRegistered($entity)->shouldReturn(true);
    }

    function it_contains_snapshot_of_wrapped_object_that_was_registered_by_proxy()
    {
        $object = new EntityFake(1, "Norbert", "Orzechowicz");
        $proxy = new ProxyFake($object);

        $this->register($proxy);

        $proxy->changeFirstName("Dawid");
        $proxy->changeLastName("Sajdak");

        $snapshot = $this->getSnapshot($proxy);
        $snapshot->getFirstName()->shouldReturn("Norbert");
        $snapshot->getLastName()->shouldReturn("Orzechowicz");

        $snapshot = $this->getSnapshot($object);
        $snapshot->getFirstName()->shouldReturn("Norbert");
        $snapshot->getLastName()->shouldReturn("Orzechowicz");
    }

    function it_make_new_snapshots_of_registered_objects()
    {
        $object = new EntityFake(1, "Norbert", "Orzechowicz");
        $proxy = new ProxyFake($object);
        $this->register($proxy);
        $object->changeFirstName("Dawid");
        $object->changeLastName("Sajdak");

        $this->makeNewSnapshots();

        $snapshot = $this->getSnapshot($proxy);
        $snapshot->getFirstName()->shouldReturn("Dawid");
        $snapshot->getLastName()->shouldReturn("Sajdak");
    }

    function it_knows_when_object_should_not_be_removed()
    {
        $object = new EntityFake(1, "Norbert", "Orzechowicz");
        $proxy = new ProxyFake($object);

        $this->isRemoved($proxy)->shouldReturn(false);
        $this->isRemoved($object)->shouldReturn(false);
    }

    function it_knows_when_object_should_be_removed()
    {
        $object = new EntityFake(1, "Norbert", "Orzechowicz");
        $proxy = new ProxyFake($object);
        $this->remove($proxy);

        $this->isRemoved($proxy)->shouldReturn(true);
        $this->isRemoved($object)->shouldReturn(true);
    }

    function it_automatically_register_objects_that_should_be_removed()
    {
        $object = new EntityFake(1, "Norbert", "Orzechowicz");
        $proxy = new ProxyFake($object);

        $this->isRegistered($proxy)->shouldReturn(false);
        $this->isRegistered($object)->shouldReturn(false);

        $this->remove($proxy);

        $this->isRegistered($proxy)->shouldReturn(true);
        $this->isRemoved($proxy)->shouldReturn(true);
        $this->isRegistered($object)->shouldReturn(true);
        $this->isRemoved($object)->shouldReturn(true);
    }

    function it_cleans_removed_objects()
    {
        $object = new EntityFake(1, "Norbert", "Orzechowicz");
        $proxy = new ProxyFake($object);
        $this->remove($proxy);

        $this->cleanRemoved();

        $this->isRegistered($proxy)->shouldReturn(false);
        $this->isRemoved($proxy)->shouldReturn(false);
        $this->isRegistered($object)->shouldReturn(false);
        $this->isRemoved($object)->shouldReturn(false);
    }

    function it_returns_all_objects_as_array()
    {
        $object = new EntityFake(1, "Norbert", "Orzechowicz");
        $this->register($object);

        $this->all()->shouldReturn([
            $object
        ]);
    }

    function it_returns_all_objects_as_array_when_registered_as_proxy()
    {
        $object = new EntityFake(1, "Norbert", "Orzechowicz");
        $proxy = new ProxyFake($object);
        $this->register($proxy);

        $this->all()->shouldReturn([
            $object
        ]);
    }

    function it_resets_objects_to_states_from_snapshots(SnapshotMaker $snapshotMaker, PropertyCloner $propertyCloner)
    {
        $object = new EntityFake(1, "Norbert", "Orzechowicz");
        $objectSnapshot = new EntityFake(1, "Norbert", "Orzechowicz");
        $snapshotMaker->makeSnapshotOf($object)->willReturn($objectSnapshot);

        $this->register($object);
        $object->changeFirstName("Dawid");
        $object->changeLastName("Sajdak");
        $objectToRemove = new EntityFake(2);
        $this->remove($objectToRemove);
        $this->reset();

        $propertyCloner->cloneProperties($object, $objectSnapshot)->shouldHaveBeenCalled();
        $this->isRemoved($objectToRemove)->shouldReturn(false);
    }

    function it_resets_objects_to_states_from_snapshots_when_registered_as_proxy(SnapshotMaker $snapshotMaker, PropertyCloner $propertyCloner)
    {
        $object = new EntityFake(1, "Norbert", "Orzechowicz");
        $proxy = new ProxyFake($object);
        $objectSnapshot = new EntityFake(1, "Norbert", "Orzechowicz");
        $snapshotMaker->makeSnapshotOf($object)->willReturn($objectSnapshot);

        $this->register($proxy);

        $object->changeFirstName("Dawid");
        $object->changeLastName("Sajdak");
        $objectToRemove = new EntityFake(2);
        $this->remove($objectToRemove);
        $this->reset();

        $propertyCloner->cloneProperties($object, $objectSnapshot)->shouldHaveBeenCalled();
        $this->isRemoved($objectToRemove)->shouldReturn(false);
    }

}
