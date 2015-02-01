<?php

namespace Isolate\Tests\Double;

use Isolate\UnitOfWork\Command\EditCommand;
use Isolate\UnitOfWork\Command\EditCommandHandler;

class EditCommandHandlerMock implements EditCommandHandler
{
    private $persistedObjects = [];

    private $persistedObjectsChanges = [];

    /**
     * @param EditCommand $command
     */
    public function handle(EditCommand $command)
    {
        $this->persistedObjects[] = $command->getEntity();
        $this->persistedObjectsChanges[] = $command->getChanges();
    }

    public function objectWasEdited($object)
    {
        foreach ($this->persistedObjects as $persistedObject) {
            if ($persistedObject === $object) {
                return true;
            }
        }

        return false;
    }

    public function getEditedObjectChanges($object)
    {
        foreach ($this->persistedObjects as $index => $persistedObject) {
            if ($persistedObject === $object) {
                return $this->persistedObjectsChanges[$index];
            }
        }

        throw new \RuntimeException("Object was not handled");
    }
}
