<?php

use PHPUnit\Framework\TestCase;
use Dealweb\Integrator\Source\SourceFactory;
use Dealweb\Integrator\Source\Adapter\DummyAdapter;
use Dealweb\Integrator\Source\Adapter\CsvFileAdapter;
use Dealweb\Integrator\Source\Adapter\RestApiAdapter;
use Dealweb\Integrator\Source\Adapter\FixedWidthFileAdapter;

class SourceFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_csv_file_adapter()
    {
        $csvFileAdapter = SourceFactory::create('csvFile');

        $this->assertInstanceOf(CsvFileAdapter::class, $csvFileAdapter);
    }

    /**
     * @test
     */
    public function it_creates_fixed_width_file_adapter()
    {
        $fixedWidthFileAdapter = SourceFactory::create('fixedWidthFile');

        $this->assertInstanceOf(FixedWidthFileAdapter::class, $fixedWidthFileAdapter);
    }

    /**
     * @test
     */
    public function it_creates_rest_api_adapter()
    {
        $restApiAdapter = SourceFactory::create('restApi');

        $this->assertInstanceOf(RestApiAdapter::class, $restApiAdapter);
    }

    /**
     * @test
     * @expectedException \Dealweb\Integrator\Exceptions\InvalidFileFormatException
     * @expectedExceptionMessage No converter found for your Bla source file
     */
    public function it_throws_exception_when_no_appropriate_adapter_is_provided()
    {
        SourceFactory::create('bla');
    }
}
