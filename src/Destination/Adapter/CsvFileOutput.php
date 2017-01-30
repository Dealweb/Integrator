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

    /**
     * @param OutputInterface $output
     */
    public function start(OutputInterface $output)
    {
        $this->output = $output;
        $filePath = $this->config['filePath'] ?? null;
        $withHeader = $this->config['withHeader'] ?? false;
        $csvHeader = $this->config['header'] ?? null;
        $delimiter = $this->config['delimiter'] ?? ',';

        $this->validateFilePath($filePath);

        $this->fileHandler = fopen($filePath, 'w+');

        if ($withHeader === true) {
            $this->addCsvHeader($csvHeader, $delimiter);
        }
    }

    /**
     * @param array $values
     * @return bool
     */
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

    /**
     * @return bool
     */
    public function finish()
    {
        return true;
    }

    /**
     * Validates if the directory path are valid and writable.
     *
     * @param $filePath
     * @throws \Exception
     */
    private function validateFilePath($filePath)
    {
        $directoryPath = dirname($filePath);

        if (! is_writable($directoryPath)) {
            throw new \Exception("Directory is not writable");
        }

        if (file_exists($filePath) && ! is_writable($filePath)) {
            throw new \Exception("File is not writable");
        }
    }

    /**
     * Adds the header for the csv file.
     *
     * @param $header
     * @param $delimiter
     * @throws \Exception
     */
    private function addCsvHeader($header, $delimiter)
    {
        if (! is_array($header)) {
            throw new \Exception('CSV file is not valid');
        }

        fputcsv($this->fileHandler, $header, $delimiter);
    }
}
