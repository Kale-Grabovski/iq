<?php

namespace AppBundle\Service\Parser\Xml;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Class AbstractXmlCrawler
 *
 * Parses XML using Symfony Crawler class.
 *
 * @package AppBundle\Service\Parser\Xml
 */
abstract class AbstractXmlCrawler implements XmlInterface
{
    /**
     * Returns an instance of XML Crawler
     *
     * @param  string  $body XML body
     * @return Crawler
     */
    protected function getCrawler(string $body) : Crawler
    {
        return new Crawler($body);
    }
}
