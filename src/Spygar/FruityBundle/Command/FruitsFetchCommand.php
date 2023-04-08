<?php

namespace Spygar\FruityBundle\Command;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Spygar\FruityBundle\Repository\FruitsRepository;
use Spygar\FruityBundle\Repository\FamilyRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Spygar\FruityBundle\Services\FruitsService;
use Spygar\FruityBundle\Entity\Fruits;
use Spygar\FruityBundle\Entity\Family;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

class FruitsFetchCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'fruits:fetch';

    /** @var FruitsRepository */
    private $fruitsRepository;

    /** @var FamilyRepository */
    private $familyRepository;

    /** @var FruitsService */
    private $fruitsService;

    /** @var Mailer */
    private $mailer;

    /**
     * @param FruitsRepository  $FruitsRepository
     * @param FamilyRepository  $familyRepository
     * @param FruitsService     $fruitsService
     * @param Mailer            $mailer
     */
    public function __construct(
        FruitsRepository $fruitsRepository, 
        FamilyRepository $familyRepository,
        FruitsService $fruitsService,
        Mailer $mailer
        )
    {
        parent::__construct();
        $this->fruitsRepository = $fruitsRepository;
        $this->familyRepository = $familyRepository;
        $this->fruitsService = $fruitsService;
        $this->mailer = $mailer;
    }

    protected function configure(): void
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('fruits fetch command.')
            ->setHelp('fetching data from https://fruityvice.com')
        ;
    }
 

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $outputErrorStyle = new OutputFormatterStyle('red', '#ff0', ['bold']);
        $outputSuccesStyle = new OutputFormatterStyle('green', '#0ff', ['bold']);
        $output->getFormatter()->setStyle('error', $outputErrorStyle);
        $output->getFormatter()->setStyle('success', $outputSuccesStyle);

        $responseData = $this->fruitsService->fruitsFetch();

        if ($responseData['status'] == 200) {
            $itemCounts = 0;
            foreach($responseData['data'] as $itemData) {
                $this->familyRepository->saveFamily($itemData['family']);
                $this->fruitsRepository->saveFruit($itemData);
                $itemCounts++;
            }
            $output->writeln('<success> Tatal item :'.$itemCounts.'</>');
        } else {
            $output->writeln('<error>'.$responseData['message'].'</>');
        }

        return Command::SUCCESS;
    }
}