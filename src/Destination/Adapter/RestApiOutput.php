<?php

namespace Dealweb\Integrator\Destination\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Dealweb\Integrator\Helper\MappingHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Dealweb\Integrator\Destination\DestinationInterface;

class RestApiOutput implements DestinationInterface
{
    /** @var \ArrayObject */
    protected $globalFields;

    /** @var \Exception */
    protected $lastError;

    /** @var array */
    protected $config;

    /** @var OutputInterface */
    protected $output;

    /** @var \GuzzleHttp\Client */
    protected $client;

    public function __construct()
    {
        $this->globalFields = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);

        $this->client = new Client([
            'cookies' => true,
            'verify'  => false,
        ]);
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function start(OutputInterface $output)
    {
        $this->output = $output;

        $this->call($this->config['authorization'], []);
    }

    public function write($values)
    {
        $this->globalFields->exchangeArray(array_merge($this->globalFields->getArrayCopy(), $values));

        foreach ($this->config['services'] as $config) {
            $values = $this->globalFields->getArrayCopy();
            $result = $this->call($config, $values);

            if ($result === false) {
                return false;
            }
        }

        return true;
    }

    private function call($config, $values = [])
    {
        $body = null;
        $returnConfig = (isset($config['return'])) ? $config['return'] : [];

        if (isset($config['body'])) {
            $body = MappingHelper::parseContent($config['body'], $values);
        }

        if (!isset($config['bodyType']) || $config['bodyType'] == 'json') {
            $body = json_encode($body);
        }

        $headers = MappingHelper::parseContent($config['headers'], $values);
        $serviceUrl = MappingHelper::parseContent($config['serviceUrl'], $values);

        if ($this->output->isVerbose()) {
            $this->output->writeln(sprintf(
                ' => Calling: [%s] %s',
                $config['httpMethod'],
                $serviceUrl
            ));

            $this->output->writeln(' -- Body: '.$body);
        }

        try {
            $response = $this->client->request($config['httpMethod'], $serviceUrl, [
                'headers' => $headers,
                'body'    => $body,
            ]);

            if ($config['httpMethod'] !== 'PATCH' && !empty($returnConfig)) {
                $resultArray = MappingHelper::parseJsonContent($response->getBody()->getContents(), $returnConfig);
                $this->globalFields->exchangeArray(
                    array_merge((array) $this->globalFields->getArrayCopy(), (array) $resultArray)
                );
            }

            if (isset($config['expectedStatusCode']) && $response->getStatusCode() !== $config['expectedStatusCode']) {
                throw new \Exception(sprintf(
                    'Unexpected status code %s, expecting status code %s',
                    $response->getStatusCode(),
                    $config['expectedStatusCode']
                ));
            }

            unset($response);

            return true;
        } catch (ClientException $e) {
            $this->lastError = $e;

            if ($this->output->isVerbose()) {
                $this->output->writeln("<error>We'd got an error from your request</error>");
                $this->output->writeln(
                    sprintf('<error>%s</error>', $e->getMessage())
                );
            }
        } catch (\Exception $e) {
            $this->lastError = $e;

            if ($this->output->isVerbose()) {
                $this->output->writeln(
                    sprintf('<error>Process finished with error: %s</error>', $e->getMessage())
                );
            }
        }

        return false;
    }

    /**
     * Finishes the destination process.
     *
     * @return bool
     */
    public function finish()
    {
        return true;
    }
}
