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
        $toDeleteFilePath = $this->fixturesPath.'/non-existing-csv.csv';

        if (file_exists($toDeleteFilePath)) {
            unlink($toDeleteFilePath);
        }

        $toEmptyFilePath = $this->fixturesPath.'/empty-csv-file.csv';

        if (file_exists($toEmptyFilePath)) {
            file_put_contents($toEmptyFilePath, '');
        }
    }

    /**
     * @test
     */
    public function it_provides_an_invalid_file_path()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Directory is not writable');

        $consoleOutput = new ConsoleOutput();

        $csvFileOutput = new CsvFileOutput();
        $csvFileOutput->setConfig([
            'filePath' => '/Users/non-available-path.csv',
        ]);
        $csvFileOutput->start($consoleOutput);
    }

    /**
     * @test
     */
    public function it_provides_a_non_writable_file()
    {
        $noWritableFilePath = realpath($this->fixturesPath.'/non-writable-file.csv');

        chmod($noWritableFilePath, 0444);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File is not writable');

        $consoleOutput = new ConsoleOutput();

        $csvFileOutput = new CsvFileOutput();
        $csvFileOutput->setConfig([
            'filePath' => $noWritableFilePath,
        ]);
        $csvFileOutput->start($consoleOutput);
    }

    /**
     * @test
     */
    public function it_outputs_the_csv_header()
    {
        $consoleOutput = new ConsoleOutput();

        $csvFileOutput = new CsvFileOutput();
        $csvFileOutput->setConfig([
            'filePath'   => $this->fixturesPath.'/empty-csv-file.csv',
            'withHeader' => true,
            'header'     => [
                'Name',
                'Age',
                'Country',
            ],
        ]);
        $csvFileOutput->start($consoleOutput);

        $fileContent = file_get_contents($this->fixturesPath.'/empty-csv-file.csv');

        $this->assertEquals("Name,Age,Country\n", $fileContent);
    }
}
