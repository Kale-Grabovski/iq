<?php

namespace AppBundle\Service\Http;

/**
 * Interface HttpClientInterface
 *
 * @package AppBundle\Service
 */
interface HttpClientInterface
{
    /**
     * HTTP OK status
     */
    const STATUS_OK = 200;

    /**
     * Returns body of the page passed by url with GET method
     *
     * @param  string $url URL to be fetched
     * @return string
     */
    public function getBody(string $url) : string;
}
