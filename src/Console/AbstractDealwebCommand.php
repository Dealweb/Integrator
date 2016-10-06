<?php
namespace Dealweb\Integrator\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractDealwebCommand extends Command
{
    protected $columns;
    protected $rows;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * Initializes the command just after the input has been validated.
     *
     * This is mainly useful when a lot of commands extends one main command
     * where some things need to be initialized based on the input arguments and options.
     *
     * @param InputInterface $input An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->columns = exec('tput cols');
        $this->rows = exec('tput lines');

        $this->output = $output;

        parent::initialize($input, $output);
    }

    public function startProcess($message)
    {
        if (! $this->output->isVerbose()) {
            $this->output->write(str_pad("   - " . $message, $this->columns - 15, ' ', STR_PAD_RIGHT));
        }
    }

    public function endProcess($status = true)
    {
        if ($this->output->isVerbose()) {
            $this->output->write(" <= ");
        }

        if ($status) {
            $this->output->writeln("[   <info>Ok</info>   ]");
        } else {
            $this->output->writeln("[ <error>Failed</error> ]");
        }

        if ($this->output->isVerbose()) {
            $this->output->writeln("");
        }
    }

    public function promptPassword()
    {
        $password = '';

        return $password;
    }
}
