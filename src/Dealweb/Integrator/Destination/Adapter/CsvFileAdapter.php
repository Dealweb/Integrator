<?php
namespace Dealweb\Integrator\Destination\Adapter;

use Dealweb\Integrator\Destination\DestinationInterface;

class CsvFileAdapter implements DestinationInterface
{
    /** @var bool */
    protected $headerWrited = false;

    public function batchWrite($configArray, $values)
    {
        $filePath = $configArray['filePath'];
        $fileHandler = fopen($filePath, 'a+');

        if ($configArray['withHeader'] === true && ! $this->headerWrited) {
            fputcsv($fileHandler, $configArray['header'], $configArray['delimiter']);
            $this->headerWrited = true;
        }

        return $this->write($configArray, $values);
    }

    public function write($config, $values = [])
    {
        $filePath = $config['filePath'];
        $fileHandler = fopen($filePath, 'a+');

        try {
            fputcsv($fileHandler, $values, $config['delimiter']);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}