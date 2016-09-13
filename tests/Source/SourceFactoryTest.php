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
        $csvFileAdapter = SourceFactory::create('csvFile');

        $this->assertInstanceOf(CsvFileInput::class, $csvFileAdapter);
    }

    /**
     * @test
     */
    public function it_creates_fixed_width_file_input_adapter()
    {
        $fixedWidthFileAdapter = SourceFactory::create('fixedWidthFile');

        $this->assertInstanceOf(FixedWidthFileInput::class, $fixedWidthFileAdapter);
    }

    /**
     * @test
     */
    public function it_creates_rest_api_input_adapter()
    {
        $restApiAdapter = SourceFactory::create('restApi');

        $this->assertInstanceOf(RestApiInput::class, $restApiAdapter);
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
