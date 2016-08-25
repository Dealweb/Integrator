<?php
namespace Dealweb\Integrator\Source\Adapter;

use Dealweb\Integrator\Source\SourceInterface;
use Dealweb\Integrator\Exceptions\InvalidFilePathException;

class CsvFileAdapter implements SourceInterface
{
    public function process($config)
    {
        $filePathConfig = isset($config['filePath']) ? $config['filePath'] : null;

        if (! $filePathConfig || ! file_exists($filePathConfig)) {
            throw new InvalidFilePathException;
            //yield false;
        }

        $fileHandler = fopen($config['filePath'], 'r');

        if ($config['ignoreFirstLine'] === true) {
            fgetcsv($fileHandler, null, $config['delimiter']);
        }

        $returningFields = $config['return'];
        while ($row = fgetcsv($fileHandler, null, $config['delimiter'])) {

            $fields = [];
            foreach ($returningFields as $index => $field) {
                if (! isset($row[$index])) {
                    continue;
                }

                $fields[$field] = $row[$index];
            }

            yield $fields;
        }
    }
}