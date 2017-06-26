<?php

namespace AppBundle\Command;

use AppBundle\Service\Parser\ParserInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ParserCommand extends ContainerAwareCommand
{
    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * @var EntityManager
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
     * @param EntityManager $entityManager
     */
    public function setEntity(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setName('rate:parser')
            ->setDescription('Parse the currency rates')
            ->addOption('date', null, InputOption::VALUE_REQUIRED, 'Date for which we want to know the rates');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rates = $this->parser->getRates($input->getOption('date'));
        $this->saveRates($rates);
    }

    private function saveRates(array $rates)
    {
        $currencies = $this->entityManager->getRepository('AppBundle:Currency')->findAll();
        echo '<pre>'; var_dump($currencies); exit;
    }
}
