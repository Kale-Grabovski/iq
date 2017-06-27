<?php

namespace AppBundle\Service\Parser\Xml;

use Generator;

/**
 * Interface XmlInterface
 *
 * @package AppBundle\Service\Parser\Xml
 */
interface XmlInterface
{
    /**
     * Parses the XML and yields an array with required data
     *
     * @param  string $body XML body
     * @return Generator
     */
    public function parse(string $body) : Generator;
}
