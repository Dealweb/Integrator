<?php
namespace Dealweb\Integrator\Validation;

class ConditionValidator
{
    public static function validateIsValid($conditionArray, $values, $default = true)
    {
        $result = $default;

        if (! is_array($conditionArray)) {
            return $result;
        }

        foreach ($conditionArray as $conditionField => $conditionValue) {
            if ($values[$conditionField] !== $conditionValue) {
                $result = ! $default;
            }
        }

        return $result;
    }

    public static function validateIsNotValid($conditionArray, $values)
    {
        return ! self::validateIsValid($conditionArray, $values, false);
    }
}