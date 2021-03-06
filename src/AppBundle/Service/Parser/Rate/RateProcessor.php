<?php

namespace AppBundle\Service\Parser\Rate;

use AppBundle\Entity\Currency;
use AppBundle\Entity\Rate;
use AppBundle\Exceptions\ParserLockedException;
use AppBundle\Service\Parser\Locker\LockerInterface;
use AppBundle\Service\Parser\ParserInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class RateProcessor
 *
 * @package AppBundle\Service\Parser\Rate
 */
class RateProcessor implements RateInterface
{
    /**
     * Date format using to create lock key
     */
    const DATE_FORMAT = 'Ymd';

    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var LockerInterface
     */
    private $locker;

    /**
     * @param ParserInterface        $parser
     * @param EntityManagerInterface $entityManager
     * @param LockerInterface        $locker
     */
    public function __construct(ParserInterface $parser, EntityManagerInterface $entityManager, LockerInterface $locker)
    {
        $this->parser        = $parser;
        $this->entityManager = $entityManager;
        $this->locker        = $locker;
    }

    /**
     * Run currencies parser and saves the rates
     *
     * @param  DateTime              $date Required currencies date
     * @throws ParserLockedException
     */
    public function processRates(DateTime $date)
    {
        // If we unable to lock means another process already in action
        if (!$this->locker->lock($date->format(self::DATE_FORMAT))) {
            throw new ParserLockedException('Parser locked by key: ' . $date->format(self::DATE_FORMAT));
        }

        foreach ($this->parser->getRates($date) as $rate) {
            $this->entityManager->beginTransaction();

            $currency = $this->entityManager->getRepository(Currency::class)->getCreateCurrency($rate);
            $this->entityManager->getRepository(Rate::class)->getCreateRate($currency, $rate['value'], $date);

            $this->entityManager->commit();
        }

        $this->locker->unlock();
    }
}
