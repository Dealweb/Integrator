<?php
namespace Dealweb\Integrator\Destination\Adapter;

use Dealweb\Integrator\Destination\DestinationInterface;

class CsvFileAdapter implements DestinationInterface
{
    /** @var bool */
    protected $headerWrited = false;

    public function batchWrite($configArray, $values)
    {
        if ($configArray['withHeader'] === true && ! $this->headerWrited) {
            $filePath = $configArray['filePath'];
            $fileHandler = fopen($filePath, 'w+');
            
            fputcsv($fileHandler, $configArray['header'], $configArray['delimiter']);
            $this->headerWrited = true;
        }

        return $this->write($configArray, $values);
    }

    public function write($config, $values = [])
    {
        $filePath = $config['filePath'];
        $fileHandler = fopen($filePath, 'a+');
        $fieldsSequences = $config['content'];

        $csvValues = [];
        foreach ($fieldsSequences as $field) {
            $csvValues[$field] = null;

            if (! isset($values[$field])) {
                continue;
            }

            $csvValues[$field] = $values[$field];
        }

        try {
            fputcsv($fileHandler, $csvValues, $config['delimiter']);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }
}