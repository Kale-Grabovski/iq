<?php

namespace AppBundle\Command;

use AppBundle\Entity\Currency;
use AppBundle\Entity\Rate;
use AppBundle\Service\Parser\ParserInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ParserCommand
 *
 * Performs currencies parsing from Central Bank of Russia.
 *
 * @package AppBundle\Command
 */
class ParserCommand extends ContainerAwareCommand
{
    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param ParserInterface $parser
     */
    public function setParser(ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function setEntity(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Configures the parser command
     */
    protected function configure()
    {
        $this
            ->setName('rate:parser')
            ->setDescription('Parse the currency rates')
            ->addOption('date', null, InputOption::VALUE_REQUIRED, 'Date for which we want to know the rates');
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $date = new DateTime($input->getOption('date'));

        foreach ($this->parser->getRates($date) as $rate) {
            $this->entityManager->beginTransaction();

            $currency = $this->entityManager->getRepository(Currency::class)->getCreateCurrency($rate);
            $this->entityManager->getRepository(Rate::class)->getCreateRate($currency, $rate['value'], $date);

            $this->entityManager->commit();
        }
    }
}
