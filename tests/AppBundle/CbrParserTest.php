<?php

namespace Tests\AppBundle\Util;

use DateTime;
use Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class CbrParserTest
 *
 * @package Tests\AppBundle\Util
 */
class CbrParserTest extends WebTestCase
{
    /**
     * @var Generator
     */
    private $rates;

    public function setUp()
    {
        $this->rates = static::createClient()->getContainer()->get('cbr_parser')->getRates(new DateTime);
        parent::setUp();
    }

    public function testParserReturnsGenerator()
    {
        $this->assertInstanceOf(Generator::class, $this->rates);
    }

    public function testParserReturnsCurrencyRatesWithSpecifiedKeys()
    {
        foreach ($this->rates as $rate) {
            $this->assertArrayHasKey('code', $rate);
            $this->assertArrayHasKey('nominal', $rate);
            $this->assertArrayHasKey('name', $rate);
            $this->assertArrayHasKey('value', $rate);
        }
    }

    public function testParserReturnsValueOfFloatType()
    {
        foreach ($this->rates as $rate) {
            $this->assertTrue(is_float($rate['value']));
        }
    }

    public function testParserReturnsNominalOfIntType()
    {
        foreach ($this->rates as $rate) {
            $this->assertTrue(is_int($rate['nominal']));
        }
    }

    public function testParserReturnsCurrencyCodeWithThreeLetters()
    {
        foreach ($this->rates as $rate) {
            $this->assertEquals(3, strlen($rate['code']));
        }
    }
}
