<?php

namespace Isolate\Tests;

use Isolate\LazyObjects\Proxy\Adapter\OcramiusProxyManager\Factory;
use Isolate\LazyObjects\Wrapper;
use Isolate\UnitOfWork\Entity\Definition;
use Isolate\UnitOfWork\Entity\InformationPoint;
use Isolate\UnitOfWork\Entity\Value\Change\ScalarChange;
use Isolate\UnitOfWork\Entity\Value\ChangeSet;
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

    /**
     * @return UnitOfWork
     */
    private function createUnitOfWork()
    {
        $this->uowEntityFakeDefinition = EntityFakeBuilder::buildDefinition();
        $this->uowEntityFakeDefinition->setNewCommandHandler(new NewCommandHandlerMock());
        $this->uowEntityFakeDefinition->setEditCommandHandler(new EditCommandHandlerMock());
        $this->uowEntityFakeDefinition->setRemoveCommandHandler(new RemoveCommandHandlerMock());

        return new UnitOfWork(new InformationPoint([$this->uowEntityFakeDefinition]), $this->eventDispatcher);
    }

    private function createLazyObjectsWrapper()
    {
        $entityFakeDefinition = EntityFakeLazyObjectBuilder::buildDefinition();

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
