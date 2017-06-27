<?php

namespace AppBundle\Service\Parser\Xml;

use Generator;

/**
 * Class XmlCbrCrawler
 *
 * Parses XML with currency rates
 *
 * @package AppBundle\Service\Parser\Xml
 */
class XmlCbrCrawler extends AbstractXmlCrawler
{
    /**
     * Parses the XML and yields currency rate
     *
     * @param  string    $body XML body
     * @return Generator       Parsed currency rates
     */
    public function parse(string $body) : Generator
    {
        foreach ($this->getCrawler($body)->filter('Valute') as $rate) {
            yield [
                'code'    => $rate->childNodes[3]->nodeValue,
                'nominal' => $rate->childNodes[5]->nodeValue,
                'name'    => $rate->childNodes[7]->nodeValue,
                'value'   => (float)str_replace(',', '.', $rate->childNodes[9]->nodeValue),
            ];
        }
    }
}
