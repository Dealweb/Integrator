<?php

use PHPUnit\Framework\TestCase;
use Dealweb\Integrator\Source\Adapter\CsvFileInput;

class CsvFileInputTest extends TestCase
{
    private $path;

    public function setUp()
    {
        $this->path = __DIR__.'/../../Fixtures';
    }

    public function tearDown()
    {
        $this->path = null;
    }

    /**
     * @test
     * @expectedException \Dealweb\Integrator\Exceptions\InvalidFilePathException
     * @expectedExceptionMessage The filePath is not valid
     */
    public function it_throws_exception_if_no_file_path_is_defined()
    {
        $csvFileAdapter = new CsvFileInput;

        $result = $csvFileAdapter->process([]);

        // TODO: Figure out if there is a better way to trigger the exception.
        iterator_to_array($result);
    }

    /**
     * @test
     * @expectedException \Dealweb\Integrator\Exceptions\InvalidFilePathException
     * @expectedExceptionMessage The filePath is not valid: non-existing-file.csv
     */
    public function it_throws_exception_if_a_non_existing_file_path_if_provided()
    {
        $csvFileAdapter = new CsvFileInput;

        $result = $csvFileAdapter->process([
            'filePath' => 'non-existing-file.csv'
        ]);

        // TODO: Figure out if there is a better way to trigger the exception.
        iterator_to_array($result);
    }

    /**
     * @test
     * @expectedException \Dealweb\Integrator\Exceptions\MissingOptionsException
     * @expectedExceptionMessage Missing option on configuration: return
     */
    public function it_requires_return_option_on_configuration()
    {
        $csvFileAdapter = new CsvFileInput;

        $result = $csvFileAdapter->process([
            'filePath' => $this->path . '/csv-normal-example.csv',
        ]);

        // TODO: Figure out if there is a better way to trigger the exception.
        iterator_to_array($result);
    }

    /**
     * @test
     */
    public function it_gets_the_generator_from_csv_process()
    {
        $csvFileInput = new CsvFileInput();

        $result = $csvFileInput->process([
            'filePath' => $this->path . '/csv-normal-example.csv',
            'return' => [
                'name', 'age', 'country'
            ]
        ]);

        $this->assertEquals([
            [
                'name' => 'John Doe',
                'age' => '22',
                'country' => 'Brazil'
            ],
            [
                'name' => 'Jane Doe',
                'age' => '33',
                'country' => 'Canada'
            ]
        ], iterator_to_array($result));
    }
}
