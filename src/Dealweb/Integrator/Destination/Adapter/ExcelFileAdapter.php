<?php
namespace Dealweb\Integrator\Destination\Adapter;

use Dealweb\Integrator\Destination\DestinationInterface;
use SimpleExcel\SimpleExcel;

class ExcelFileAdapter implements DestinationInterface
{
    /** @var SimpleExcel */
    protected $simpleExcel = null;

    /** @var array */
    protected $config;

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function start()
    {
        $this->simpleExcel = new SimpleExcel('xml');

        if ($this->config['withHeader'] === true) {
            $this->simpleExcel->writer->addRow($this->config['header']);
        }
    }

    public function write($values = [])
    {
        $fieldsSequences = $this->config['content'];

        $xmlValues = [];
        foreach ($fieldsSequences as $field) {
            $xmlValues[$field] = null;

            if (! isset($values[$field])) {
                continue;
            }

            $xmlValues[$field] = $values[$field];
        }

        $this->simpleExcel->writer->addRow($xmlValues);
        return true;
    }

    public function finish()
    {
        $result = $this->simpleExcel->writer->saveString();

        $fp = fopen($this->config['filePath'], 'w+');
        fwrite($fp, $result);
        fclose($fp);
    }
}