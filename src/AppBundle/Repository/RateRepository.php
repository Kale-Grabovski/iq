<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Currency;
use AppBundle\Entity\Rate;
use AppBundle\Exceptions\RateNotFoundException;
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
        $rate = $this->getByDateAndCurrency($currency, $date);
        return $rate ?? $this->createRate($currency, $rateValue, $date);
    }

    /**
     * Trying to find currency rate for passed date
     *
     * @param  string   $code
     * @param  DateTime $date
     * @return Rate
     * @throws RateNotFoundException
     */
    public function getByCodeAndDate(string $code, DateTime $date) : Rate
    {
        $currency = $this->getEntityManager()->getRepository(Currency::class)->getByCode($code);
        $rate     = $this->getByDateAndCurrency($currency, $date);

        if (!$rate) {
            throw new RateNotFoundException('Cant get currency rate');
        }

        return $rate;
    }

    /**
     * Removes rates by passed date
     *
     * @param DateTime $date
     */
    public function deleteRatesByDate(DateTime $date)
    {
        $rates = $this->getEntityManager()->getRepository(Rate::class)->findBy(['createdAt' => $date]);
        if ($rates) {
            foreach ($rates as $rate) {
                $this->getEntityManager()->remove($rate);
            }
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Returns rate by currency and date
     *
     * @param  Currency $currency
     * @param  DateTime $date
     * @return Rate
     */
    private function getByDateAndCurrency(Currency $currency, DateTime $date) : ?Rate
    {
        /** @var Rate $rate */
        $rate = $this->getEntityManager()
            ->getRepository(Rate::class)
            ->findOneBy([
                'createdAt' => $date,
                'currency'  => $currency,
            ]);

        return $rate;
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
