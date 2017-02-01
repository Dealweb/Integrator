<?php

namespace Dealweb\Integrator\Source\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ClientException;
use Dealweb\Integrator\Helper\MappingHelper;
use Dealweb\Integrator\Source\SourceInterface;

class RestApiInput implements SourceInterface
{
    /** @var \ArrayObject */
    protected $globalFields;

    /** @var \Exception */
    protected $lastError;

    public function __construct()
    {
        $this->globalFields = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
    }

    public function process($config)
    {
        if (self::read($config['authorization'])) {
            return self::read($config['service'], false);
        }

        throw new \Exception('Your authentication failed.');
    }

    public function read($config, $mergeResult = true)
    {
        $values = $this->globalFields->getArrayCopy();

        $body = null;

        if (isset($config['body'])) {
            $body = MappingHelper::parseContent($config['body'], $values);
        }

        if (!isset($config['bodyType']) || $config['bodyType'] == 'json') {
            $body = json_encode($body);
        }

        $client = new Client();

        $headers = MappingHelper::parseContent($config['headers'], $values);
        $serviceUrl = MappingHelper::parseContent($config['serviceUrl'], $values);

        $request = new Request($config['httpMethod'], $serviceUrl, $headers, $body);

        $returnConfig = (isset($config['return'])) ? $config['return'] : [];
        $listRootConfig = (isset($config['listRoot'])) ? $config['listRoot'] : null;

        try {
            $response = $client->send($request, [
                'verify' => false,
            ]);
            $resultArray = MappingHelper::parseJsonContent($response->getBody()->getContents(), $returnConfig, $listRootConfig);
            if (isset($config['expectedStatusCode']) && $response->getStatusCode() !== $config['expectedStatusCode']) {
                throw new \Exception(sprintf(
                    'Unexpected status code %s, expecting status code %s',
                    $response->getStatusCode(),
                    $config['expectedStatusCode']
                ));
            }
            if ($mergeResult) {
                $this->globalFields->exchangeArray(
                    array_merge($this->globalFields->getArrayCopy(), $resultArray)
                );
            } else {
                return $resultArray;
            }
        } catch (ClientException $e) {
            $this->lastError = $e;

            return false;
        } catch (\Exception $e) {
            $this->lastError = $e;

            return false;
        }

        return $this->globalFields->getArrayCopy();
    }
}
