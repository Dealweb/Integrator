<?php

namespace Dealweb\Integrator\Destination\Adapter;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\ConsoleOutput;

class CsvFileOutputTest extends TestCase
{
    private $fixturesPath;

    public function setUp()
    {
        $this->fixturesPath = __DIR__.'/../../Fixtures';
    }

    /**
     * @test
     */
    public function it_validates_the_path_to_the_destination_file()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File path is not writable');

        $consoleOutput = new ConsoleOutput;

        $csvFileOutput = new CsvFileOutput;
        $csvFileOutput->setConfig([
            'filePath' => '/Users/non-available-path.csv',
        ]);
        $csvFileOutput->start($consoleOutput);
    }

    /**
     * @test
     */
    public function it_checks_if_output_file_path_is_writable()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File path is not writable');

        $consoleOutput = new ConsoleOutput;

        $csvFileOutput = new CsvFileOutput;
        $csvFileOutput->setConfig([
            'filePath' => $this->fixturesPath . '/non-available-path.csv',
        ]);
        $csvFileOutput->start($consoleOutput);
    }
}
