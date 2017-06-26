<?php

namespace AppBundle\Service\Parser;

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
     * @param  string $date Date for which we want to know the currency rates
     * @return array
     */
    public function getRates(string $date) : array;
}
