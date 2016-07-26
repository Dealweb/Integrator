<?php
namespace Dealweb\Integrator\Validation;

use Dealweb\Integrator\File\InvalidFileFormatException;
use Symfony\Component\Yaml\Yaml;
use Dealweb\Integrator\File\InvalidFileTypeException;

class LayoutFileValidator
{
    protected $content;

    public function __construct($content)
    {
        $this->content = Yaml::parse($content);
    }

    public function validate()
    {
        if (!isset($this->content['source']) || !isset($this->content['destination'])) {
            throw new InvalidFileFormatException;
        }

        $this->validateSource(
            $this->content['source']
        );
        $this->validateDestination(
            $this->content['destination']
        );
    }

    protected function validateSource($source)
    {
        $sourceTypeClass = sprintf('\Dealweb\Integrator\Source\Adapter\%sAdapter', $source['type']);

        if (!class_exists($sourceTypeClass)) {
            throw new InvalidFileTypeException;
        }

        return true;
    }

    protected function validateDestination($destination)
    {
        $sourceTypeClass = sprintf('\Dealweb\Integrator\Destination\Adapter\%sAdapter', $destination['type']);

        if (!class_exists($sourceTypeClass)) {
            throw new InvalidFileTypeException;
        }

        return true;
    }
}