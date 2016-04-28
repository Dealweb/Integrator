<?php
namespace Dealweb\Integrator\Source\Adapter;

use Dealweb\Integrator\Source\SourceInterface;

class CsvFileAdapter implements SourceInterface
{
    public function process($config)
    {
        if (! file_exists($config['filePath'])) {
            yield false;
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