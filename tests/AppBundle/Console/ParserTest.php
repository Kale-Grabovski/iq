<?php

namespace Tests\AppBundle\Util;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class CbrParserTest
 *
 * @package Tests\AppBundle\Util
 */
class CbrParserTest extends WebTestCase
{
    public function testCbrParserReturnsNotEmptyArrayOfCurrencyRates()
    {
        $container = static::createClient()->getContainer();
        $parser = $container->get('cbr_parser');
        echo '<pre>'; var_dump($parser); exit;

        $this->assertGreaterThan(0, count($result));
    }
}
