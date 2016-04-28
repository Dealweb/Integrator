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

class SelfUpdateCommand extends AbstractDealwebCommand
{
    protected function configure()
    {
        $this->setName('self-update');
        $this->setDescription('Self update Dealweb Integrator');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pharUrl = 'https://dealweb.github.io/integrator/dealweb-integrator.phar';
        $versionFileUrl = 'https://dealweb.github.io/integrator/dealweb-integrator.phar';

        $updater = new Updater();
        $updater->getStrategy()->setPharUrl($pharUrl);
        $updater->getStrategy()->setVersionUrl($versionFileUrl);

        try {
            $result = $updater->update();
            if (! $result) {
                $output->writeln('You already using the last version!');

                return 0;
            }

            $newVersion = $updater->getNewVersion();
            $oldVersion = $updater->getOldVersion();

            $output->writeln(sprintf('Updated from version %s to %s!', $oldVersion, $newVersion));
            return 0;
        } catch (\Exception $e) {
            $output->writeln('Unknown Error while updating the library!');
            return 1;
        }
    }
}