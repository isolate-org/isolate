<?php

namespace Isolate\Tests;

use Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\Factory;
use Isolate\LazyObjects\Wrapper;
use Isolate\UnitOfWork\CommandBus\SilentBus;
use Isolate\UnitOfWork\Entity\ChangeBuilder;
use Isolate\UnitOfWork\Entity\Definition;
use Isolate\UnitOfWork\Entity\InformationPoint;
use Isolate\UnitOfWork\Entity\IsolateComparer;
use Isolate\UnitOfWork\Entity\IsolateIdentifier;
use Isolate\UnitOfWork\Entity\IsolateInformationPoint;
use Isolate\UnitOfWork\Entity\Value\Change\ScalarChange;
use Isolate\UnitOfWork\Entity\Value\ChangeSet;
use Isolate\UnitOfWork\EntityStates;
use Isolate\UnitOfWork\Object\IsolateRegistry;
use Isolate\UnitOfWork\Object\PropertyCloner;
use Isolate\UnitOfWork\Object\RecoveryPoint;
use Isolate\UnitOfWork\Object\SnapshotMaker\Adapter\DeepCopy\SnapshotMaker;
use Isolate\UnitOfWork\UnitOfWork;
use Isolate\EventListener\UnitOfWorkSubscriber;
use Isolate\Tests\ClassDefinition\EntityFakeBuilder;
use Isolate\Tests\Double\EditCommandHandlerMock;
use Isolate\Tests\Double\EntityFake;
use Isolate\Tests\Double\NewCommandHandlerMock;
use Isolate\Tests\Double\RemoveCommandHandlerMock;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Isolate\Tests\LazyObjects\EntityFakeBuilder as EntityFakeLazyObjectBuilder;

class UnitOfWorWIthLazyObjectsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * @var Wrapper
     */
    private $wrapper;

    /**
     * @var UnitOfWork
     */
    private $uow;

    /**
     * @var Definition
     */
    private $uowEntityFakeDefinition;

    public function setUp()
    {
        $this->eventDispatcher = new EventDispatcher();
        $this->wrapper = $this->createLazyObjectsWrapper();
        $this->uow = $this->createUnitOfWork();
    }

    public function test_persisting_lazy_objects()
    {
        $entity = new EntityFake(null, "Norbert", "Orzechowicz");
        $entityProxy = $this->wrapper->wrap($entity);
        $this->uow->register($entityProxy);
        $this->uow->commit();

        $this->assertTrue($this->getEntityFakeNewCommandHandler()->objectWasPersisted($entityProxy->getWrappedObject()));
    }

    public function test_persisting_edited_lazy_objects()
    {
        $entity = new EntityFake(1, "Norbert", "Orzechowicz");
        $entityProxy = $this->wrapper->wrap($entity);
        $this->uow->register($entityProxy);

        $entityProxy->changeFirstName('Dawid');
        $entityProxy->changeLastName('Sajdak');

        $this->uow->commit();

        $this->assertTrue($this->getEntityFakeEditCommandHandler()->objectWasEdited($entityProxy->getWrappedObject()));
        $this->assertEquals(
            new ChangeSet([
                new ScalarChange(new Definition\Property("firstName"), "Norbert", "Dawid"),
                new ScalarChange(new Definition\Property("lastName"), "Orzechowicz", "Sajdak")
            ]),
            $this->getEntityFakeEditCommandHandler()->getEditedObjectChanges($entityProxy->getWrappedObject())
        );
    }

    public function test_removing_lazy_objects()
    {
        $entity = new EntityFake(1, "Norbert", "Orzechowicz");
        $entityProxy = $this->wrapper->wrap($entity);
        $this->uow->register($entityProxy);
        $this->uow->remove($entityProxy);
        $this->uow->commit();

        $this->assertTrue($this->getEntityFakeRemoveCommandHandler()->objectWasRemoved($entityProxy->getWrappedObject()));
    }

    function test_rollback_entity_before_commit()
    {
        $lazyItems = ["foo", "bar", "baz"];
        $this->wrapper = $this->createLazyObjectsWrapper($lazyItems);
        $entity = new EntityFake(1, "Dawid", "Sajdak");
        $entityProxy = $this->wrapper->wrap($entity);
        $this->uow->register($entityProxy);

        $entityProxy->changeFirstName("Norbert");
        $entityProxy->changeLastName("Orzechowicz");

        $this->uow->rollback();


        $this->assertSame("Dawid", $entityProxy->getFirstName());
        $this->assertSame("Sajdak", $entityProxy->getLastName());
        $this->assertSame($lazyItems, $entityProxy->getItems());
    }

    public function test_updating_snapshot_when_property_initialized_to_not_generate_changes_for_lazy_loaded_properties()
    {
        $lazyItems = ["foo", "bar", "baz"];
        $this->wrapper = $this->createLazyObjectsWrapper($lazyItems);
        $entity = new EntityFake(1, "Norbert", "Orzechowicz");
        $entityProxy = $this->wrapper->wrap($entity);

        $this->uow->register($entityProxy);

        $this->assertSame($lazyItems, $entityProxy->getItems());
    }

    /**
     * @return UnitOfWork
     */
    private function createUnitOfWork()
    {
        $this->uowEntityFakeDefinition = EntityFakeBuilder::buildDefinition();
        $this->uowEntityFakeDefinition->setNewCommandHandler(new NewCommandHandlerMock());
        $this->uowEntityFakeDefinition->setEditCommandHandler(new EditCommandHandlerMock());
        $this->uowEntityFakeDefinition->setRemoveCommandHandler(new RemoveCommandHandlerMock());

        $definitions = new Definition\Repository\InMemory([$this->uowEntityFakeDefinition]);
        $identifier = new IsolateIdentifier($definitions);

        return new UnitOfWork(
            new IsolateRegistry(new SnapshotMaker(), new PropertyCloner()),
            $identifier,
            new ChangeBuilder($definitions, $identifier),
            new IsolateComparer($definitions),
            new SilentBus($definitions)
        );
    }

    private function createLazyObjectsWrapper($itemsInitializerValue = null)
    {
        $entityFakeDefinition = EntityFakeLazyObjectBuilder::buildDefinition($itemsInitializerValue);

        return new Wrapper(new Factory(new Factory\LazyObjectsFactory()), [$entityFakeDefinition]);
    }

    /**
     * @return NewCommandHandlerMock
     */
    private function getEntityFakeNewCommandHandler()
    {
        return $this->uowEntityFakeDefinition->getNewCommandHandler();
    }

    /**
     * @return EditCommandHandlerMock
     */
    private function getEntityFakeEditCommandHandler()
    {
        return $this->uowEntityFakeDefinition->getEditCommandHandler();
    }

    /**
     * @return RemoveCommandHandlerMock
     */
    private function getEntityFakeRemoveCommandHandler()
    {
        return $this->uowEntityFakeDefinition->getRemoveCommandHandler();
    }
}
