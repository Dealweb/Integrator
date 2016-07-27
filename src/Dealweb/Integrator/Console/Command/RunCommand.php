<?php
namespace Dealweb\Integrator\Console\Command;

use Symfony\Component\Yaml\Yaml;
use Dealweb\Integrator\Source\SourceFactory;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Dealweb\Integrator\Console\AbstractDealwebCommand;
use Dealweb\Integrator\Destination\DestinationFactory;

class RunCommand extends AbstractDealwebCommand
{
    protected function configure()
    {
        $this->setName('run');
        $this->setDescription('Dealweb integrator tool');

        $this->addOption('layout-file', null, InputOption::VALUE_REQUIRED, 'Layout file with integration details');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $layoutFilePath = $input->getOption('layout-file');

        if (null === $layoutFilePath) {
            $output->writeln("<error>A layout file must be provided.</error>");
            return 1;
        }

        if (! file_exists($layoutFilePath)) {
            $output->writeln("<error>The layout provided was not found.</error>");
            return 1;
        }

        $layoutConfig = Yaml::parse(file_get_contents($layoutFilePath));

        $source = SourceFactory::create($layoutConfig['source']['type']);
        $destination = DestinationFactory::create($layoutConfig['destination']['type']);
        $destination->setConfig($layoutConfig['destination']);

        $this->output->writeln(sprintf(
            'Starting integration with source "%s" and destination "%s"',
            $layoutConfig['source']['type'],
            $layoutConfig['destination']['type']
        ));

        $count = 0;
        $errorCount = 0;

        $destination->start($output);

        foreach ($source->process($layoutConfig['source']) as $fieldValues) {
            $count++;
            $this->startProcess(sprintf('Processing record number: %s', $count));
            if ($fieldValues === false) {
                continue;
            }

            $success = $destination->write($fieldValues);

            $this->endProcess($success);
            if (! $success) {
                $errorCount++;
            }
        }
        $destination->finish();

        $this->output->writeln('');
        $this->output->writeln(sprintf('Finished! %s record(s) processed, %s process failed', $count, $errorCount));

        return 0;
    }
}