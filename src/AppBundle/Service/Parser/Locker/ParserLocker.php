<?php

namespace AppBundle\Service\Parser\Locker;

/**
 * Class ParserLocker
 *
 * Performs locking logic to prevent parallel access to the parser.
 *
 * @package AppBundle\Service\Parser\Locker
 */
class ParserLocker implements LockerInterface
{
    /**
     * @var resource
     */
    private $semLock;

    /**
     * @inheritdoc
     */
    public function lock(string $lockId) : bool
    {
        $this->semLock = sem_get($lockId);
        return sem_acquire($this->semLock, true);
    }

    /**
     * Release the lock
     */
    public function unlock()
    {
        sem_release($this->semLock);
    }

    /**
     * To prevent eternal lock
     */
    public function __destruct()
    {
        $this->unlock();
    }
}
