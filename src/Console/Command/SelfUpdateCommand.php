<?php

namespace Dealweb\Integrator\Console\Command;

use Humbug\SelfUpdate\Updater;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Dealweb\Integrator\Console\AbstractDealwebCommand;

class SelfUpdateCommand extends AbstractDealwebCommand
{
    protected function configure()
    {
        $this->setName('self-update');
        $this->setDescription('Self update Dealweb Integrator');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pharUrl = 'https://Dealweb.github.io/Integrator/bin/dealweb-integrator';
        $versionFileUrl = 'https://Dealweb.github.io/Integrator/current.version';

        $updater = new Updater(null, false);
        $updater->getStrategy()->setPharUrl($pharUrl);
        $updater->getStrategy()->setVersionUrl($versionFileUrl);

        try {
            $result = $updater->update();
            if (!$result) {
                $output->writeln('You already using the last version!');

                return 0;
            }

            $newVersion = $updater->getNewVersion();
            $oldVersion = $updater->getOldVersion();

            $output->writeln(sprintf('Updated from version %s to %s!', $oldVersion, $newVersion));

            return 0;
        } catch (\Exception $e) {
            $output->writeln('Error: '.$e->getMessage());

            return 1;
        }
    }
}
