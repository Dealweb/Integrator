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

    public function tearDown()
    {
        $toDeleteFilePath = $this->fixturesPath . '/non-existing-csv.csv';

        if (file_exists($toDeleteFilePath)) {
            unlink($toDeleteFilePath);
        }

        $toEmptyFilePath = $this->fixturesPath . '/empty-csv-file.csv';

        if (file_exists($toEmptyFilePath)) {
            file_put_contents($toEmptyFilePath, '');
        }
    }

    /**
     * @test
     */
    public function it_validates_if_output_directory_is_writable()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Directory is not writable');

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
    public function it_checks_if_output_file_is_writable()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File is not writable');

        $consoleOutput = new ConsoleOutput;

        $csvFileOutput = new CsvFileOutput;
        $csvFileOutput->setConfig([
            'filePath' => $this->fixturesPath . '/non-writable-file.csv',
        ]);
        $csvFileOutput->start($consoleOutput);
    }

    /**
     * @test
     */
    public function it_outputs_csv_with_header()
    {
        $consoleOutput = new ConsoleOutput;

        $csvFileOutput = new CsvFileOutput;
        $csvFileOutput->setConfig([
            'filePath' => $this->fixturesPath . '/empty-csv-file.csv',
            'header' => [
                'Name',
                'Age',
                'Country',
            ]
        ]);
        $csvFileOutput->start($consoleOutput);
    }
}
