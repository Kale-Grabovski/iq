<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Rate;
use AppBundle\Exceptions\RateNotFoundException;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiController
 *
 * @package AppBundle\Controller
 * @Route("/api")
 */
class ApiController extends Controller
{
    /**
     * @var int
     */
    const DIGITS = 4;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Returns currency rate for specified date
     *
     * @Route("/rates/{code}/{date}", name="rates", requirements={"code": "\w{3}", "date": "\d{8}"})
     * @Method({"GET"})
     *
     * @param  string   $code Currency code
     * @param  int      $date Date in YYYYmmdd format
     * @return Response
     */
    public function ratesAction(string $code, int $date) : Response
    {
        $date = new DateTime($date);

        try {
            $rate = $this->getRateByCodeAndDate($code, $date);
        } catch (RateNotFoundException $e) {
            $this->container->get('rate_processor')->processRates($date);

            // Try again and throw exception if we still have no rates
            $rate = $this->getRateByCodeAndDate($code, $date);
        }

        return $this->container->get('api_response')->output([
            'rate' => $this->format($rate->getRate()),
        ]);
    }

    /**
     * Converts amount from one currency to another for specified date
     *
     * @Route("/convert/{fromCode}/{toCode}/{amount}/{date}", name="convert",
     *     requirements={"fromCode": "\w{3}", "toCode": "\w{3}", "amount": "\d+(\.\d+)?", "date": "\d{8}"})
     * @Method({"GET"})
     *
     * @param  string   $fromCode Source currency code
     * @param  string   $toCode   Target currency code
     * @param  float    $amount   Amount to convert
     * @param  int      $date     Date in YYYYmmdd format
     * @return Response
     */
    public function convertAction(string $fromCode, string $toCode, float $amount, int $date) : Response
    {
        $date = new DateTime($date);

        try {
            list($fromRate, $toRate) = $this->getRatesByCodesAndDate($fromCode, $toCode, $date);
        } catch (RateNotFoundException $e) {
            $this->container->get('rate_processor')->processRates($date);

            // Try again and throw exception if we still have no rates
            list($fromRate, $toRate) = $this->getRatesByCodesAndDate($fromCode, $toCode, $date);
        }

        return $this->container->get('api_response')->output([
            'amount' => $this->format($amount * $fromRate->getValue() / $toRate->getValue()),
        ]);
    }

    /**
     * Helper method to return rates in two currencies
     *
     * @param  string   $fromCode Source currency code
     * @param  string   $toCode   Target currency code
     * @param  DateTime $date     Date
     * @return array
     */
    private function getRatesByCodesAndDate(string $fromCode, string $toCode, DateTime $date) : array
    {
        return [
            $this->getRateByCodeAndDate($fromCode, $date),
            $this->getRateByCodeAndDate($toCode, $date),
        ];
    }

    /**
     * Helper method to get rate by currency code and date. Forwards to RateRepository
     *
     * @param  string   $code
     * @param  DateTime $date
     * @return Rate
     */
    private function getRateByCodeAndDate(string $code, DateTime $date) : Rate
    {
        return $this->entityManager->getRepository(Rate::class)->getByCodeAndDate($code, $date);
    }

    /**
     * Formats the output for API float number
     *
     * @param  float $number
     * @return float
     */
    private function format(float $number) : float
    {
        return number_format($number, self::DIGITS);
    }
}
