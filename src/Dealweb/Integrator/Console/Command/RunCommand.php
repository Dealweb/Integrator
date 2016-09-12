<?php
namespace Dealweb\Integrator\Console\Command;

use Symfony\Component\Yaml\Yaml;
use Dealweb\Integrator\Source\SourceFactory;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Dealweb\Integrator\Validation\LayoutFileValidator;
use Dealweb\Integrator\Console\AbstractDealwebCommand;
use Dealweb\Integrator\Destination\DestinationFactory;

class RunCommand extends AbstractDealwebCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('run');
        $this->setDescription('Dealweb Integrator tool');

        $this->addArgument('layout-file', null, InputArgument::REQUIRED, 'Layout file with integration details');
    }

    /**
     * Execute the console command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $layoutFilePath = $input->getArgument('layout-file');

        $this->validateFilePath($layoutFilePath);

        $layoutConfig = Yaml::parse(file_get_contents($layoutFilePath));

        $this->validateConfig($layoutConfig);

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

        $this->output->writeln(sprintf('Finished! %s record(s) processed, %s process failed', $count, $errorCount));

        return 0;
    }

    /**
     * Validates the input file for the integration.
     *
     * @param $filePath
     * @return bool
     * @throws \Exception
     */
    private function validateFilePath($filePath)
    {
        if (null === $filePath) {
            throw new \Exception('A layout file must be provided.');
        }

        if (! file_exists($filePath)) {
            throw new \Exception('The layout provided was not found.');
        }

        return true;
    }

    /**
     * Validates the configuration file.
     *
     * @param $config
     */
    private function validateConfig($config)
    {
        $layoutFileValidator = new LayoutFileValidator($config);

        $layoutFileValidator->validate();
    }
}
