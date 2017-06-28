<?php

namespace AppBundle\Command;

use AppBundle\Service\Parser\Rate\RateInterface;
use DateTime;
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
     * @var RateInterface
     */
    private $rateProcessor;

    /**
     * @param RateInterface $rateProcessor
     */
    public function setRatesProcessor(RateInterface $rateProcessor)
    {
        $this->rateProcessor = $rateProcessor;
    }

    /**
     * Configures the parser command
     */
    protected function configure()
    {
        $this
            ->setName('rate:parser')
            ->setDescription('Parse the currency rates')
            ->addOption('date', null, InputOption::VALUE_OPTIONAL,
                'Date for which we want to know the rates', (new \DateTime)->format('Y-m-d'));
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $date = new DateTime($input->getOption('date'));
        $this->rateProcessor->processRates($date);
    }
}
