<?php

namespace Dealweb\Integrator\Helper;

use Peekmo\JsonPath\JsonStore;

class MappingHelper
{
    public static function parseContent($content, $values = [])
    {
        $newValue = $content;

        if (!is_array($values) || empty($values)) {
            return $newValue;
        }

        foreach ($values as $field => $fieldValue) {
            if (is_array($newValue)) {
                foreach ($newValue as $index => $individualValue) {
                    $newValue[$index] = self::parseContent($individualValue, $values);
                }
            } else {
                $newValue = str_replace(sprintf('{%s}', $field), $fieldValue, $newValue);
            }
        }

        return $newValue;
    }

    public static function parseJsonContent($content, $config, $listRootPath = null)
    {
        $contentList = [$content];

        if (null !== $listRootPath) {
            $jsonStoreListRoot = new JsonStore($content);
            $contentList = $jsonStoreListRoot->get($listRootPath);
        }

        $resultArray = [];
        foreach ($contentList as $index => $content) {
            $jsonStore = new JsonStore($content);

            foreach ($config as $field => $jsonPath) {
                $fieldValue = current($jsonStore->get($jsonPath));
                $resultArray[$index][$field] = $fieldValue;
            }
        }

        return (null === $listRootPath)
            ? current($resultArray)
            : $resultArray;
    }
}
