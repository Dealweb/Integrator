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

        $this->addArgument('file', null, InputArgument::REQUIRED, 'Path to Configuration file for integration');
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
        $timeStart = microtime(true);

        $configFilePath = $input->getArgument('file');

        $config = $this->parseConfigFile($configFilePath);

        $this->validateConfig($config);

        $source = SourceFactory::create($config['source']['type']);
        $destination = DestinationFactory::create($config['destination']['type']);
        $destination->setConfig($config['destination']);

        $this->output->writeln(sprintf(
            'Starting integration with source "%s" and destination "%s"',
            $config['source']['type'],
            $config['destination']['type']
        ));

        $count = 0;
        $errorCount = 0;

        $destination->start($output);

        foreach ($source->process($config['source']) as $fieldValues) {
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

        $timeEnd = microtime(true);

        if ($this->output->isVerbose()) {
            $executionTime = ($timeEnd - $timeStart);

            $this->info(sprintf("Integration finished in %d seconds.", $executionTime));
        }

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

    /**
     * Parses the configuration file.
     *
     * @param $layoutFilePath
     * @return array
     */
    private function parseConfigFile($layoutFilePath)
    {
        $this->validateFilePath($layoutFilePath);

        return Yaml::parse(file_get_contents($layoutFilePath));
    }
}
