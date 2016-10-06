<?php

use PHPUnit\Framework\TestCase;
use Dealweb\Integrator\Validation\LayoutFileValidator;

class LayoutFileValidatorTest extends TestCase
{
    /**
     * @test
     * @expectedException Dealweb\Integrator\Exceptions\InvalidFileFormatException
     */
    public function it_requires_to_have_source_setup()
    {
        $layoutFileValidator = new LayoutFileValidator([
            // no source setup
            'destination' => [

            ]
        ]);

        $layoutFileValidator->validate();
    }

    /**
     * @test
     * @expectedException Dealweb\Integrator\Exceptions\InvalidFileFormatException
     */
    public function it_requires_to_have_destination_setup()
    {
        $layoutFileValidator = new LayoutFileValidator([
            'source' => [

            ],
            // no destination setup
        ]);

        $layoutFileValidator->validate();
    }

    /**
     * @test
     * @expectedException Dealweb\Integrator\Exceptions\InvalidFileFormatException
     */
    public function it_requires_to_have_a_valid_source_type()
    {
        $layoutFileValidator = new LayoutFileValidator([
            'source' => [

            ],
            'destination' => [

            ]
        ]);

        $layoutFileValidator->validate();
    }

    /**
     * @test
     * @expectedException Dealweb\Integrator\Exceptions\InvalidFileTypeException
     */
    public function it_does_not_validate_invalid_source_types()
    {
        $layoutFileValidator = new LayoutFileValidator([
            'source' => [
                'type' => 'dummy'
            ],
            'destination' => [

            ]
        ]);

        $layoutFileValidator->validate();
    }

    /**
     * @test
     */
    public function it_validates_csv_file_source_type()
    {
        $layoutFileValidator = new LayoutFileValidator([
            'source' => [
                'type' => 'csvFile'
            ],
            'destination' => [
                'type' => 'csvFile'
            ]
        ]);

        $this->assertTrue($layoutFileValidator->validate());
    }

    /**
     * @test
     */
    public function it_validates_fixed_width_file_source_type()
    {
        $layoutFileValidator = new LayoutFileValidator([
            'source' => [
                'type' => 'fixedWidthFile'
            ],
            'destination' => [
                'type' => 'fixedWidthFile'
            ]
        ]);

        $this->assertTrue($layoutFileValidator->validate());
    }
}
