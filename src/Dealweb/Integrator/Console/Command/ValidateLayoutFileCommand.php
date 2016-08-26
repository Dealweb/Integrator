<?php
namespace Dealweb\Integrator\Console\Command;

use Dealweb\Integrator\Validation\LayoutFileValidator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Dealweb\Integrator\Console\AbstractDealwebCommand;

class ValidateLayoutFileCommand extends AbstractDealwebCommand
{
    protected function configure()
    {
        $this->setName('validate')
             ->setDescription('Validate a layout file syntax')
             ->addArgument('file', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');

        if (!file_exists($file)) {
            $output->writeln("<error>The requested file was not found or is not accessible.</error>");
        }

        $validator = new LayoutFileValidator(file_get_contents($file));
    }
}
