<?php

namespace Tests\AppBundle\Util;

use AppBundle\Entity\Rate;
use AppBundle\Exceptions\RateNotFoundException;
use AppBundle\Service\Parser\Rate\RateInterface;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class RateProcessorTest
 *
 * @package Tests\AppBundle\Util
 */
class RateProcessorTest extends WebTestCase
{
    /**
     * @var RateInterface
     */
    private $rateProcessor;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * @var
     */
    private $entityManager;

    public function setUp()
    {
        $this->rateProcessor = static::createClient()->getContainer()->get('rate_processor');
        $this->entityManager = static::createClient()->getContainer()->get('doctrine.orm.entity_manager');
        $this->date          = new DateTime('2005-01-01');

        // Clean rates for current date
        $this->entityManager->getRepository(Rate::class)->deleteRatesByDate($this->date);

        parent::setUp();
    }

    public function testProcessorThrowsRateNotFoundException()
    {
        $this->expectException(RateNotFoundException::class);
        $this->entityManager->getRepository(Rate::class)->getByCodeAndDate('USD', $this->date);
    }

    public function testProcessorSavesRateIntoDatabase()
    {
        $this->rateProcessor->processRates($this->date);
        $rate = $this->entityManager->getRepository(Rate::class)->getByCodeAndDate('USD', $this->date);
        $this->assertNotNull($rate);
    }

    public function tearDown()
    {
        $this->entityManager->getRepository(Rate::class)->deleteRatesByDate($this->date);
    }
}
