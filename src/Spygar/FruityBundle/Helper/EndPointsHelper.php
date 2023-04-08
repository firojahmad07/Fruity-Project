<?php

namespace Spygar\FruityBundle\Helper;

use Spygar\FruityBundle\Repository\FruitsRepository;
use Spygar\FruityBundle\Repository\FamilyRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Spygar\FruityBundle\Entity\Fruits;
use Spygar\FruityBundle\Entity\Family;

class EndPointsHelper
{
    public const FRUITS_FETCH_ALL = '/api/fruit/all';
}