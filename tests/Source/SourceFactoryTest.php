<?php

use PHPUnit\Framework\TestCase;
use Dealweb\Integrator\Source\SourceFactory;
use Dealweb\Integrator\Source\Adapter\DummyAdapter;
use Dealweb\Integrator\Source\Adapter\CsvFileAdapter;
use Dealweb\Integrator\Source\Adapter\FixedWidthFileAdapter;
use Dealweb\Integrator\Source\Adapter\RestApiAdapter;

class SourceFactoryTest extends TestCase
{
    /** @test */
    public function it_creates_csv_file_adapter()
    {
        $csvFileAdapter = SourceFactory::create('csvFile');

        $this->assertInstanceOf(CsvFileAdapter::class, $csvFileAdapter);
    }

    /** @test */
    public function it_creates_dummy_adapter_when_no_appropriate_adapter_is_found()
    {
        $unexistentAdapter = SourceFactory::create('bla');

        $this->assertInstanceOf(DummyAdapter::class, $unexistentAdapter);
    }

    /** @test */
    public function it_creates_fixed_width_file_adapter()
    {
        $fixedWidthFileAdapter = SourceFactory::create('fixedWidthFile');

        $this->assertInstanceOf(FixedWidthFileAdapter::class, $fixedWidthFileAdapter);
    }

    /** @test */
    public function it_creates_rest_api_adapter()
    {
        $restApiAdapter = SourceFactory::create('restApi');

        $this->assertInstanceOf(RestApiAdapter::class, $restApiAdapter);
    }
}