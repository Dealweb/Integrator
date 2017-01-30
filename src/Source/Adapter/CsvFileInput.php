<?php

namespace Dealweb\Integrator\Source\Adapter;

use Dealweb\Integrator\Source\SourceInterface;
use Dealweb\Integrator\Exceptions\MissingOptionsException;
use Dealweb\Integrator\Exceptions\InvalidFilePathException;

class CsvFileInput implements SourceInterface
{
    public function process($config)
    {
        $filePath = isset($config['filePath']) ? $config['filePath'] : null;
        $ignoreFirstLine = isset($config['ignoreFirstLine']) ? $config['ignoreFirstLine'] : false;
        $returningFields = isset($config['return']) ? $config['return'] : [];
        $delimiter = isset($config['delimiter']) ? $config['delimiter'] : ';';

        if (!($filePath && file_exists($filePath) && is_readable($filePath))) {
            throw new InvalidFilePathException(
                "The filePath is not valid: {$filePath}"
            );
        }

        if (empty($returningFields)) {
            throw MissingOptionsException::forOption('return');
        }

        $fileHandler = fopen($filePath, 'r');

        if ($ignoreFirstLine === true) {
            fgetcsv($fileHandler, null, $delimiter);
        }

        while ($row = fgetcsv($fileHandler, null, $delimiter)) {
            $fields = [];

            foreach ($returningFields as $index => $field) {
                if (!isset($row[$index])) {
                    continue;
                }

                $fields[$field] = $row[$index];
            }

            yield $fields;
        }
    }
}
