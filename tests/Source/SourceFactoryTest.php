<?php

use PHPUnit\Framework\TestCase;
use Dealweb\Integrator\Source\SourceFactory;
use Dealweb\Integrator\Source\Adapter\CsvFileInput;
use Dealweb\Integrator\Source\Adapter\RestApiInput;
use Dealweb\Integrator\Source\Adapter\FixedWidthFileInput;

class SourceFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_csv_file_input_adapter()
    {
        $csvFileInput = SourceFactory::create('csvFile');

        $this->assertInstanceOf(CsvFileInput::class, $csvFileInput);
    }

    /**
     * @test
     */
    public function it_creates_fixed_width_file_input_adapter()
    {
        $fixedWidthFileInput = SourceFactory::create('fixedWidthFile');

        $this->assertInstanceOf(FixedWidthFileInput::class, $fixedWidthFileInput);
    }

    /**
     * @test
     */
    public function it_creates_rest_api_input_adapter()
    {
        $restApiInput = SourceFactory::create('restApi');

        $this->assertInstanceOf(RestApiInput::class, $restApiInput);
    }

    /**
     * @test
     * @expectedException \Dealweb\Integrator\Exceptions\InvalidFileFormatException
     * @expectedExceptionMessage No converter found for your Dummy source file
     */
    public function it_throws_exception_when_no_appropriate_adapter_is_provided()
    {
        SourceFactory::create('dummy');
    }
}
