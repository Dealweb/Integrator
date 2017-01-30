<?php
namespace Dealweb\Integrator\Destination\Adapter;

use Dealweb\Integrator\Destination\DestinationInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CsvFileOutput implements DestinationInterface
{
    /** @var array */
    protected $config;

    protected $fileHandler;

    /** @var OutputInterface */
    protected $output;

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function start(OutputInterface $output)
    {
        $this->output = $output;
        $filePath = $this->config['filePath'];

        if (! is_writable($filePath)) {
            throw new \Exception("File path is not writable");
        }

        $this->fileHandler = fopen($filePath, 'w+');

        if ($this->config['withHeader'] === true) {
            fputcsv($this->fileHandler, $this->config['header'], $this->config['delimiter']);
        }
    }

    public function write($values = [])
    {
        $fieldsSequences = $this->config['content'];

        $csvValues = [];
        foreach ($fieldsSequences as $field) {
            $csvValues[$field] = null;

            if (! isset($values[$field])) {
                continue;
            }

            $csvValues[$field] = $values[$field];
        }

        try {
            fputcsv($this->fileHandler, $csvValues, $this->config['delimiter']);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    public function finish()
    {
        return true;
    }
}
