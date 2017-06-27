<?php

namespace AppBundle\Service\Parser\Locker;

/**
 * Interface LockerInterface
 *
 * @package AppBundle\Service\Parser\Locker
 */
interface LockerInterface
{
    /**
     * Locks some entity, file for example
     *
     * @param  string $lockId Lock identifier
     * @return bool           Returns true if the lock has been set successful
     */
    public function lock(string $lockId) : bool;

    /**
     * Unlocks entity
     */
    public function unlock();
}
