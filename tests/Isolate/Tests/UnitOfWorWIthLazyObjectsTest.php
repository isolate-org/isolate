<?php

namespace Isolate\Tests;

use Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\Factory;
use Isolate\LazyObjects\Wrapper;
use Isolate\UnitOfWork\Change;
use Isolate\UnitOfWork\ChangeSet;
use Isolate\UnitOfWork\ObjectClass\Definition;
use Isolate\UnitOfWork\ObjectInformationPoint;
use Isolate\UnitOfWork\UnitOfWork;
use Isolate\EventListener\UnitOfWorkSubscriber;
use Isolate\Tests\ClassDefinition\EntityFakeBuilder;
use Isolate\Tests\Double\EditCommandHandlerMock;
use Isolate\Tests\Double\EntityFake;
use Isolate\Tests\Double\NewCommandHandlerMock;
use Isolate\Tests\Double\RemoveCommandHandlerMock;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Isolate\Tests\ProxyDefinition\EntityFakeBuilder as EntityFakeLazyObjectBuilder;

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
        $this->eventDispatcher->addSubscriber(new UnitOfWorkSubscriber());
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
                new Change("Norbert", "Dawid", "firstName"),
                new Change("Orzechowicz", "Sajdak", "lastName")
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

    /**
     * @return UnitOfWork
     */
    private function createUnitOfWork()
    {
        $this->uowEntityFakeDefinition = EntityFakeBuilder::buildDefinition();
        $this->uowEntityFakeDefinition->addNewCommandHandler(new NewCommandHandlerMock());
        $this->uowEntityFakeDefinition->addEditCommandHandler(new EditCommandHandlerMock());
        $this->uowEntityFakeDefinition->addRemoveCommandHandler(new RemoveCommandHandlerMock());

        return new UnitOfWork(new ObjectInformationPoint([$this->uowEntityFakeDefinition]), $this->eventDispatcher);
    }

    private function createLazyObjectsWrapper()
    {
        $entityFakeDefinition = EntityFakeLazyObjectBuilder::buildDefinition();

        return new Wrapper(new Factory(), [$entityFakeDefinition]);
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
