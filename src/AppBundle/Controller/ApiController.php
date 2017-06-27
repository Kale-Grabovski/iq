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
            'rate' => $rate->getValue(),
        ]);
    }

    /**
     * Just helper method to get rate by currency code and date. Forwards to RateRepository
     *
     * @param  string   $code
     * @param  DateTime $date
     * @return Rate
     */
    private function getRateByCodeAndDate(string $code, DateTime $date) : Rate
    {
        return $this->entityManager->getRepository(Rate::class)->getByCodeAndDate($code, $date);
    }
}
