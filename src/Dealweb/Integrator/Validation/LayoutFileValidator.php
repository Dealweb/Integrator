<?php
namespace Dealweb\Integrator\Validation;

use Dealweb\Integrator\Exceptions\InvalidFileTypeException;
use Dealweb\Integrator\Exceptions\InvalidFileFormatException;

class LayoutFileValidator
{
    protected $content;

    /**
     * LayoutFileValidator constructor.
     * @param string $content The setup content
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Validates if the content contains source and destination setup.
     *
     * @throws InvalidFileFormatException
     */
    public function validate()
    {
        if (! isset($this->content['source']) || ! isset($this->content['destination'])) {
            throw new InvalidFileFormatException;
        }

        $this->validateSource(
            $this->content['source']
        );

        $this->validateDestination(
            $this->content['destination']
        );
    }

    /**
     * Validates the source setup.
     *
     * @param $source
     * @return bool
     * @throws InvalidFileFormatException
     * @throws InvalidFileTypeException
     */
    protected function validateSource($source)
    {
        if (! isset($source['type'])) {
            throw new InvalidFileFormatException('A source type is required for the setup.');
        }

        $sourceTypeClass = sprintf('\Dealweb\Integrator\Source\Adapter\%sAdapter', $source['type']);

        if (! class_exists($sourceTypeClass)) {
            throw new InvalidFileTypeException;
        }

        return true;
    }

    /**
     * Validates the destination setup.
     *
     * @param $destination
     * @return bool
     * @throws InvalidFileTypeException
     */
    protected function validateDestination($destination)
    {
        $sourceTypeClass = sprintf('\Dealweb\Integrator\Destination\Adapter\%sAdapter', $destination['type']);

        if (! class_exists($sourceTypeClass)) {
            throw new InvalidFileTypeException;
        }

        return true;
    }
}
