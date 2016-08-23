<?php
namespace Dealweb\Integrator\Source\Adapter;

use Dealweb\Integrator\Source\SourceInterface;
use Dealweb\Integrator\Validation\ConditionValidator;

class FixedWidthFileAdapter implements SourceInterface
{
    public function process($config)
    {
        $filePath = $config['filePath'];
        if (! file_exists($filePath)) {
            yield false;
        }

        $handle = fopen($filePath, 'r');

        $mappings = $config['fieldMapping'];

        if ($config['ignoreFirstLine'] === true) {
            fgets($handle);
        }

        while ($row = fgets($handle)) {
            $fieldsValues = [];
            foreach ($mappings as $fieldName => $mappingData) {
                $fieldPosition = $mappingData['position'];
                $fieldValue = trim(substr($row, $fieldPosition[0] - 1, $fieldPosition[1]));

                if (isset($mappingData['type']) && $mappingData['type'] == 'number') {
                    if (isset ($mappingData['divisionBy'])) {
                        $fieldValue = $fieldValue / (int) $mappingData['divisionBy'];
                    }

                    if (isset($mappingData['decimalPlaces'])) {
                        $fieldValue = round((float) $fieldValue, (int) $mappingData['decimalPlaces']);
                    }
                }

                $fieldsValues[$fieldName] = $fieldValue;
            }

            if (isset($config['condition'])) {
                $condition = $config['condition'];
                if (isset($condition['validIf']) && ! ConditionValidator::validateIsValid($condition['validIf'], $fieldsValues)) {
                    continue;
                }

                if (isset($condition['notValidIf']) && ConditionValidator::validateIsNotValid($condition['notValidIf'], $fieldsValues)) {
                    continue;
                }
            }

            yield $fieldsValues;
        }
    }
}