<?php
namespace Dealweb\Integrator\Console\Command;

use Dealweb\Integrator\Destination\DestinationFactory;
use Dealweb\Integrator\Source\SourceFactory;
use Humbug\SelfUpdate\Updater;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Dealweb\Integrator\Console\AbstractDealwebCommand;
use Symfony\Component\Yaml\Yaml;

class RollbackCommand extends AbstractDealwebCommand
{
    protected function configure()
    {
        $this->setName('rollback');
        $this->setDescription('Rollback Dealweb Integrator to previous version');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $updater = new Updater(null, false);

        try {
            $result = $updater->rollback();
            if (! $result) {
                $output->writeln('Could not rollback version!');

                return 1;
            }

            $newVersion = $updater->getNewVersion();
            $oldVersion = $updater->getOldVersion();

            $output->writeln(sprintf('Rollback from version %s to %s!', $oldVersion, $newVersion));
            return 0;
        } catch (\Exception $e) {
            $output->writeln('Error: ' . $e->getMessage());
            return 1;
        }
    }
}
