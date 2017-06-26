<?php

namespace AppBundle\Service\Parser;

use Symfony\Component\DomCrawler\Crawler;

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
     * @inheritdoc
     * @return array
     */
    public function getRates(string $date) : array
    {
        $xmlBody = $this->httpClient->getBody(self::URL . $this->convertDate($date));
        return $this->convertRates($xmlBody);
    }

    /**
     * @inheritdoc
     * @param string $date
     * @return string
     */
    protected function convertDate(string $date) : string
    {
        return date('d/m/Y', strtotime($date));
    }

    /**
     * Converts XML into an array with currencies info
     *
     * @param string $body XML response with currency rates
     * @return array
     */
    private function convertRates(string $body) : array
    {
        $crawler = new Crawler($body);

        $rates = [];
        foreach ($crawler->filter('Valute') as $rate) {
            $rates[] = [
                'code'    => $rate->childNodes[3]->nodeValue,
                'nominal' => $rate->childNodes[5]->nodeValue,
                'name'    => $rate->childNodes[7]->nodeValue,
                'value'   => str_replace(',', '.', $rate->childNodes[9]->nodeValue),
            ];
        }

        return $rates;
    }
}
