<?php

namespace Isolate\Tests\Double;

use Isolate\UnitOfWork\Command\NewCommand;
use Isolate\UnitOfWork\Command\NewCommandHandler;

class NewCommandHandlerMock implements  NewCommandHandler
{
    private $persistedObjects = [];

    /**
     * @param NewCommand $command
     */
    public function handle(NewCommand $command)
    {
        $this->persistedObjects[] = $command->getEntity();
    }

    public function objectWasPersisted($object)
    {
        foreach ($this->persistedObjects as $persistedObject) {
            if ($persistedObject === $object) {
                return true;
            }
        }

        return false;
    }
}
