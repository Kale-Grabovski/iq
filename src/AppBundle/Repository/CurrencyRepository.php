<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Currency;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;

/**
 * Class CurrencyRepository
 *
 * @package AppBundle\Repository
 */
class CurrencyRepository extends EntityRepository
{
    /**
     * Trying to find needed currency by code or creates and returns a new one
     *
     * @param  array    $currencyData Currency data included keys: code, name and nominal
     * @return Currency
     */
    public function getCreateCurrency(array $currencyData) : Currency
    {
        try {
            $currency = $this->getByCode($currencyData['code']);
        } catch (EntityNotFoundException $e) {
            $currency = $this->createCurrency($currencyData);
        }

        return $currency;
    }

    /**
     * Creates a new currency
     *
     * @param  array    $currencyData Currency data included keys: code, name and nominal
     * @return Currency
     */
    public function createCurrency(array $currencyData) : Currency
    {
        $currency = new Currency;
        $currency->setCode($currencyData['code']);
        $currency->setName($currencyData['name']);
        $currency->setNominal($currencyData['nominal']);

        $this->getEntityManager()->persist($currency);
        $this->getEntityManager()->flush();

        return $currency;
    }

    /**
     * Returns a currency by its code
     *
     * @param  string   $code
     * @return Currency
     * @throws EntityNotFoundException
     */
    public function getByCode(string $code) : Currency
    {
        $currency = $this->getEntityManager()
            ->getRepository(Currency::class)
            ->findOneByCode(strtoupper($code));

        if (!$currency) {
            throw new EntityNotFoundException("Currency with code $code not found");
        }

        return $currency;
    }
}
