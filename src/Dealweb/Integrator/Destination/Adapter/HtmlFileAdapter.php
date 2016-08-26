<?php
namespace Dealweb\Integrator\Destination\Adapter;

use Dealweb\Integrator\Destination\DestinationInterface;
use SimpleExcel\SimpleExcel;
use Symfony\Component\Console\Output\OutputInterface;

class HtmlFileAdapter implements DestinationInterface
{
    /** @var SimpleExcel */
    protected $simpleExcel = null;

    /** @var array */
    protected $config;

    /** @var OutputInterface */
    protected $output;

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function start(OutputInterface $output)
    {
        $this->output = $output;
        $this->simpleExcel = new SimpleExcel('html');

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
