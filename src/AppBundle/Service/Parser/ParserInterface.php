<?php

namespace AppBundle\Service\Parser;

use DateTime;
use Generator;

/**
 * Interface ParserInterface
 *
 * @package AppBundle\Service\Parser
 */
interface ParserInterface
{
    /**
     * Returns array of parsed currency rates
     *
     * @param  DateTime $date Date for which we want to know the currency rates
     * @return Generator
     */
    public function getRates(DateTime $date) : Generator;
}
