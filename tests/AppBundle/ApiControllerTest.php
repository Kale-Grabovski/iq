<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Service\Parser\Rate\RateInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ApiControllerTest
 *
 * @package Tests\AppBundle\Controller
 */
class ApiControllerTest extends WebTestCase
{
    /**
     * @var RateInterface
     */
    private $rateProcessor;

    public function setUp()
    {
        $this->rateProcessor = static::createClient()->getContainer()->get('rate_processor');
        $this->rateProcessor->processRates(new \DateTime);
        parent::setUp();
    }

    public function testRatesAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/rates/eur/20170424');
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(60.3187, $responseData['rate']);
    }

    public function testConvertAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/convert/usd/eur/2/20160420');
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(1.7654, $responseData['amount']);
    }
}