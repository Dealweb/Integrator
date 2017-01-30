<?php

use PHPUnit\Framework\TestCase;
use Dealweb\Integrator\Destination\DestinationFactory;
use Dealweb\Integrator\Destination\Adapter\CsvFileOutput;
use Dealweb\Integrator\Destination\Adapter\RestApiOutput;
use Dealweb\Integrator\Destination\Adapter\FixedWidthFileOutput;

class DestinationFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_csv_file_output_adapter()
    {
        $csvFileAdapter = DestinationFactory::create('csvFile');

        $this->assertInstanceOf(CsvFileOutput::class, $csvFileAdapter);
    }

    /**
     * @test
     */
    public function it_creates_fixed_width_file_output_adapter()
    {
        $fixedWidthFileAdapter = DestinationFactory::create('fixedWidthFile');

        $this->assertInstanceOf(FixedWidthFileOutput::class, $fixedWidthFileAdapter);
    }

    /**
     * @test
     */
    public function it_creates_rest_api_output_adapter()
    {
        $restApiAdapter = DestinationFactory::create('restApi');

        $this->assertInstanceOf(RestApiOutput::class, $restApiAdapter);
    }

    /**
     * @test
     * @expectedException \Dealweb\Integrator\Exceptions\InvalidFileFormatException
     * @expectedExceptionMessage No converter found for your Dummy destination file
     */
    public function it_throws_exception_when_no_appropriate_adapter_is_provided()
    {
        DestinationFactory::create('dummy');
    }
}
