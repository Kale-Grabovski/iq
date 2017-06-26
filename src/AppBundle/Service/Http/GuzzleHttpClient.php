<?php

namespace AppBundle\Service\Http;

use GuzzleHttp\Client;

/**
 * Class GuzzleHttpClient
 *
 * Wrapper for the Guzzle http library.
 *
 * @package AppBundle\Service
 */
class GuzzleHttpClient implements HttpClientInterface
{
    /**
     * @var Client Guzzle instance
     */
    private $guzzle;

    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * @inheritdoc
     * @param  string $url URL to be fetched
     * @return string
     */
    public function getBody(string $url) : string
    {
        $res = $this->guzzle->request('GET', $url);

        if ($res->getStatusCode() !== static::STATUS_OK) {
            throw new \HttpException('Could not get info by url: ' . $url);
        }

        return $res->getBody();
    }
}
