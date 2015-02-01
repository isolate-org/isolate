<?php

namespace Isolate\Tests\Double;

use Isolate\UnitOfWork\Command\RemoveCommand;
use Isolate\UnitOfWork\Command\RemoveCommandHandler;

class RemoveCommandHandlerMock implements RemoveCommandHandler
{
    private $removedObjects = [];

    /**
     * @param RemoveCommand $command
     */
    public function handle(RemoveCommand $command)
    {
        $this->removedObjects[] = $command->getEntity();
    }

    public function objectWasRemoved($object)
    {
        foreach ($this->removedObjects as $persistedObject) {
            if ($persistedObject === $object) {
                return true;
            }
        }

        return false;
    }
}
