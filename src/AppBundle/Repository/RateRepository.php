<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Currency;
use AppBundle\Entity\Rate;
use DateTime;

/**
 * Class RateRepository
 *
 * @package AppBundle\Repository
 */
class RateRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Fetches rate by currency and date or creates a new one before
     *
     * @param  Currency $currency
     * @param  float    $rateValue
     * @param  DateTime $date
     * @return Rate
     */
    public function getCreateRate(Currency $currency, float $rateValue, DateTime $date) : Rate
    {
        /** @var Rate $rate */
        $rate = $this->getEntityManager()
            ->getRepository(Rate::class)
            ->findOneBy([
                'createdAt'  => $date,
                'currencyId' => $currency,
            ]);

        return $rate ?? $this->createRate($currency, $rateValue, $date);
    }

    /**
     * Creates a new currency rate
     *
     * @param  Currency $currency
     * @param  float    $rateValue
     * @param  DateTime $date
     * @return Rate
     */
    private function createRate(Currency $currency, float $rateValue, DateTime $date) : Rate
    {
        $rate = new Rate;
        $rate->setCurrency($currency);
        $rate->setCreatedAt($date);
        $rate->setValue($rateValue);

        $this->getEntityManager()->persist($rate);
        $this->getEntityManager()->flush();

        return $rate;
    }
}
