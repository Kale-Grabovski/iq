<?php

namespace AppBundle\Service\Parser;

use AppBundle\Service\Http\HttpClientInterface;
use AppBundle\Service\Parser\Xml\XmlInterface;
use DateTime;
use Generator;

/**
 * Class CbrParser
 *
 * Parses currency rates from Russian Central Bank API.
 *
 * @package AppBundle\Service\Parser
 */
class CbrParser extends AbstractParser
{
    /**
     * Central Bank API url.
     */
    const URL = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=';

    /**
     * @var XmlInterface
     */
    private $xmlParser;

    /**
     * @param HttpClientInterface $httpClient
     * @param XmlInterface        $xmlParser
     */
    public function __construct(HttpClientInterface $httpClient, XmlInterface $xmlParser)
    {
        $this->xmlParser = $xmlParser;
        parent::__construct($httpClient);
    }

    /**
     * @inheritdoc
     */
    public function getRates(DateTime $date) : Generator
    {
        $xmlBody = $this->httpClient->getBody(self::URL . $this->convertDate($date));
        return $this->xmlParser->parse($xmlBody);
    }

    /**
     * @inheritdoc
     */
    protected function convertDate(DateTime $date) : string
    {
        return $date->format('d/m/Y');
    }
}
