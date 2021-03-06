<?php

namespace Dealweb\Integrator\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command as BaseCommand;

abstract class AbstractDealwebCommand extends BaseCommand
{
    protected $columns;
    protected $rows;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * The default verbosity of output commands.
     *
     * @var int
     */
    protected $verbosity = OutputInterface::VERBOSITY_NORMAL;

    /**
     * The mapping between human readable verbosity levels and Symfony's OutputInterface.
     *
     * @var array
     */
    protected $verbosityMap = [
        'v'      => OutputInterface::VERBOSITY_VERBOSE,
        'vv'     => OutputInterface::VERBOSITY_VERY_VERBOSE,
        'vvv'    => OutputInterface::VERBOSITY_DEBUG,
        'quiet'  => OutputInterface::VERBOSITY_QUIET,
        'normal' => OutputInterface::VERBOSITY_NORMAL,
    ];

    /**
     * Initializes the command just after the input has been validated.
     *
     * This is mainly useful when a lot of commands extends one main command
     * where some things need to be initialized based on the input arguments and options.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->columns = exec('tput cols');
        $this->rows = exec('tput lines');

        $this->output = $output;

        parent::initialize($input, $output);
    }

    /**
     * Write a string as information output.
     *
     * @param string          $string
     * @param null|int|string $verbosity
     *
     * @return void
     */
    public function info($string, $verbosity = null)
    {
        $this->line($string, 'info', $verbosity);
    }

    /**
     * Write a string as error output.
     *
     * @param string          $string
     * @param null|int|string $verbosity
     *
     * @return void
     */
    public function error($string, $verbosity = null)
    {
        $this->line($string, 'error', $verbosity);
    }

    /**
     * Write a string as standard output.
     *
     * @param string          $string
     * @param string          $style
     * @param null|int|string $verbosity
     *
     * @return void
     */
    public function line($string, $style = null, $verbosity = null)
    {
        $styled = $style ? "<$style>$string</$style>" : $string;

        $this->output->writeln($styled, $this->parseVerbosity($verbosity));
    }

    /**
     * Get the verbosity level in terms of Symfony's OutputInterface level.
     *
     * @param string|int $level
     *
     * @return int
     */
    protected function parseVerbosity($level = null)
    {
        if (isset($this->verbosityMap[$level])) {
            $level = $this->verbosityMap[$level];
        } elseif (!is_int($level)) {
            $level = $this->verbosity;
        }

        return $level;
    }

    public function startProcess($message)
    {
        if (!$this->output->isVerbose()) {
            $this->output->write(str_pad('   - '.$message, $this->columns - 15, ' ', STR_PAD_RIGHT));
        }
    }

    public function endProcess($status = true)
    {
        if ($this->output->isVerbose()) {
            $this->output->write(' <= ');
        }

        if ($status) {
            $this->output->writeln('[   <info>Ok</info>   ]');
        } else {
            $this->output->writeln('[ <error>Failed</error> ]');
        }

        if ($this->output->isVerbose()) {
            $this->output->writeln('');
        }
    }
}
