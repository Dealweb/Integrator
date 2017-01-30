<?php

namespace Dealweb\Integrator\Validation;

use Dealweb\Integrator\Exceptions\InvalidFileTypeException;
use Dealweb\Integrator\Exceptions\InvalidFileFormatException;

class LayoutFileValidator
{
    protected $content;

    /**
     * LayoutFileValidator constructor.
     *
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
        if (!isset($this->content['source']) || !isset($this->content['destination'])) {
            throw new InvalidFileFormatException();
        }

        $this->validateSource(
            $this->content['source']
        );

        $this->validateDestination(
            $this->content['destination']
        );

        return true;
    }

    /**
     * Validates the source setup.
     *
     * @param $source
     *
     * @throws InvalidFileFormatException
     * @throws InvalidFileTypeException
     *
     * @return bool
     */
    protected function validateSource($source)
    {
        if (!isset($source['type'])) {
            throw new InvalidFileFormatException('A source type is required for the setup.');
        }

        $sourceTypeClass = sprintf(
            '\Dealweb\Integrator\Source\Adapter\%sInput',
            ucfirst($source['type'])
        );

        if (!class_exists($sourceTypeClass)) {
            throw new InvalidFileTypeException(sprintf('The %s class was not found.', $sourceTypeClass));
        }

        return true;
    }

    /**
     * Validates the destination setup.
     *
     * @param $destination
     *
     * @throws InvalidFileFormatException
     * @throws InvalidFileTypeException
     *
     * @return bool
     */
    protected function validateDestination($destination)
    {
        if (!isset($destination['type'])) {
            throw new InvalidFileFormatException('A destination type is required for the setup.');
        }

        $destinationTypeClass = sprintf(
            '\Dealweb\Integrator\Destination\Adapter\%sOutput',
            ucfirst($destination['type'])
        );

        if (!class_exists($destinationTypeClass)) {
            throw new InvalidFileTypeException(sprintf('The %s class was not found.', $destinationTypeClass));
        }

        return true;
    }
}
