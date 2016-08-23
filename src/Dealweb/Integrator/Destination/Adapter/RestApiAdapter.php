<?php
namespace Dealweb\Integrator\Destination\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Dealweb\Integrator\Helper\MappingHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Dealweb\Integrator\Destination\DestinationInterface;

class RestApiAdapter implements DestinationInterface
{
    /** @var \ArrayObject */
    protected $globalFields;

    /** @var \Exception */
    protected $lastError;

    /** @var array */
    protected $config;

    /** @var OutputInterface */
    protected $output;

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function __construct()
    {
        $this->globalFields = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
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

            if ($result === 404) {
                return false;
            } elseif ($result === true) {
            } else {
                return false;
            }
        }

        return true;
    }

    private function call($config, $values = [])
    {
        $body = null;

        if (isset($config['body'])) {
            $body = MappingHelper::parseContent($config['body'], $values);
        }

        if (! isset($config['bodyType']) || $config['bodyType'] == 'json') {
            $body = json_encode($body);
        }

        $client     = new Client();
        $headers    = MappingHelper::parseContent($config['headers'], $values);
        $serviceUrl = MappingHelper::parseContent($config['serviceUrl'], $values);
        $request    = $client->createRequest($config['httpMethod'], $serviceUrl, [
            'headers' => $headers,
            'body' => $body,
            'verify' => false
        ]);

        $returnConfig = (isset($config['return']))? $config['return'] : [];

        try {
            if ($this->output->isVerbose()) {
                $this->output->writeln(sprintf(
                    ' => Calling: [%s] %s',
                    $config['httpMethod'],
                    $serviceUrl
                ));

                $this->output->writeln(' -- Body: ' . $body);
            }

            $response = $client->send($request);

            $resultArray = [];
            if ($config['httpMethod'] !== 'PATCH') {
                $resultArray = MappingHelper::parseJsonContent($response->getBody()->getContents(), $returnConfig);
            }

            if (isset($config['expectedStatusCode']) && $response->getStatusCode() !== $config['expectedStatusCode']) {
                throw new \Exception(sprintf(
                    'Unexpected status code %s, expecting status code %s',
                    $response->getStatusCode(),
                    $config['expectedStatusCode']
                ));
            }
            $this->globalFields->exchangeArray(
                array_merge((array) $this->globalFields->getArrayCopy(), $resultArray)
            );
        } catch (ClientException $e) {
            $this->lastError = $e;
            if ($e->getCode() === 404) {
                return false;
            }

            return false;
        } catch (\Exception $e) {
            $this->lastError = $e;
            return false;
        }

        return true;
    }

    public function finish()
    {
        return true;
    }
}