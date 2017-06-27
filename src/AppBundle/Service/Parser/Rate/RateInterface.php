<?php

namespace AppBundle\Service\Parser\Rate;

use DateTime;

/**
 * Interface RateInterface
 *
 * @package AppBundle\Service\Parser\Store
 */
interface RateInterface
{
    /**
     * Runs currencies parser and saves the rates
     *
     * @param DateTime $date
     */
    public function processRates(DateTime $date);
}
