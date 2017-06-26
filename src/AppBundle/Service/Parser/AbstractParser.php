<?php

namespace AppBundle\Service\Parser;

use AppBundle\Service\Http\HttpClientInterface;

/**
 * Class AbstractParser
 *
 * @package AppBundle\Service\Parser
 */
abstract class AbstractParser implements ParserInterface
{
    /**
     * @var HttpClientInterface
     */
    protected $httpClient;

    /**
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Converts the date into appropriate for API format
     *
     * @param string $date
     * @return string
     */
    abstract protected function convertDate(string $date) : string;
}
