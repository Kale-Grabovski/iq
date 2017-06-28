<?php

namespace Tests\AppBundle\Util;

use AppBundle\Entity\Currency;
use AppBundle\Entity\Rate;
use AppBundle\Exceptions\RateNotFoundException;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class RateRepositoryTest
 *
 * @package Tests\AppBundle\Util
 */
class RateRepositoryTest extends WebTestCase
{
    /**
     * @var DateTime
     */
    private $date;

    /**
     * @var
     */
    private $entityManager;

    /**
     * @var Currency
     */
    private $currency;

    public function setUp()
    {
        $this->date          = new DateTime;
        $this->entityManager = static::createClient()->getContainer()->get('doctrine.orm.entity_manager');
        $this->currency      = $this->entityManager
            ->getRepository(Currency::class)
            ->createCurrency([
                'code'    => 'XXX',
                'name'    => 'XXX',
                'nominal' => 1,
            ]);
        parent::setUp();
    }

    public function testGetCreateRate()
    {
        $rate = $this->entityManager->getRepository(Rate::class)->getCreateRate($this->currency, 66.66, $this->date);
        $this->assertNotNull($rate);
        $this->assertEquals($rate->getValue(), 66.66);
    }

    public function testGetByCodeAndDateThrowsException()
    {
        $this->expectException(RateNotFoundException::class);
        $this->entityManager->getRepository(Rate::class)->getByCodeAndDate($this->currency->getCode(), $this->date);
    }

    public function testGetByCodeAndDateReturnsNotNull()
    {
        $this->entityManager->getRepository(Rate::class)->getCreateRate($this->currency, 6, $this->date);
        $rate = $this->entityManager
            ->getRepository(Rate::class)
            ->getByCodeAndDate($this->currency->getCode(), $this->date);
        $this->assertNotNull($rate);
    }

    public function testDeleteRatesByDate()
    {
        $this->entityManager->getRepository(Rate::class)->getCreateRate($this->currency, 6, $this->date);
        $rate = $this->entityManager->getRepository(Rate::class)->findOneByCreatedAt($this->date);
        $this->assertNotNull($rate);

        $this->entityManager->getRepository(Rate::class)->deleteRatesByDate($this->date);
        $rate = $this->entityManager->getRepository(Rate::class)->findOneByCreatedAt($this->date);
        $this->assertNull($rate);
    }

    /**
     * Sorry, I didn't come up with some good strategy used in Symfony to clean up the DB
     */
    public function tearDown()
    {
        $rates = $this->entityManager->getRepository(Rate::class)->findBy(['currency' => $this->currency]);
        if ($rates) {
            foreach ($rates as $rate) {
                $this->entityManager->remove($rate);
            }
        }

        $this->entityManager->remove($this->currency);
        $this->entityManager->flush();
    }
}
