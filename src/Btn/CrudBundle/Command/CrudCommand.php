<?php
namespace Btn\CrudBundle\Command;

use Sensio\Bundle\GeneratorBundle\Command\GenerateDoctrineCrudCommand;
use Sensio\Bundle\GeneratorBundle\Generator\DoctrineCrudGenerator;

class CrudCommand extends GenerateDoctrineCrudCommand
{
    protected $generator;

    protected function configure()
    {
        parent::configure();

        $this->setName('btn:generate:crud');
        $this->setDescription('Bitnoise version of CRUD generator - twitter bootstrap ready!');
    }

    protected function getGenerator()
    {
        if (null === $this->generator) {
            $this->generator = new DoctrineCrudGenerator($this->getContainer()->get('filesystem'), __DIR__.'/../Resources/skeleton/crud');
        }

        return $this->generator;
    }
}