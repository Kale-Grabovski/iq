<?php

namespace Tests\AppBundle\Util;

use AppBundle\Service\Parser\ParserInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class CbrParserTest
 *
 * @package Tests\AppBundle\Util
 */
class CbrParserTest extends WebTestCase
{
    /**
     * @var ParserInterface
     */
    private $parser;

    public function setUp()
    {
        $this->parser = static::createClient()->getContainer()->get('cbr_parser');
        parent::setUp();
    }

    public function testCbrParserReturnsNotEmptyArrayOfCurrencyRates()
    {
        echo '<pre>'; var_dump($this->parser); exit;

        $this->assertGreaterThan(0, count($result));
    }
}
