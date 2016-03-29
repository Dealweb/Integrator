<?php
namespace Dealweb\Integrator\Destination\Adapter;

use Dealweb\Integrator\Destination\DestinationInterface;
use Dealweb\Integrator\Helper\MappingHelper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class RestApiAdapter implements DestinationInterface
{
    /** @var \ArrayObject */
    protected $globalFields;

    /** @var \Exception */
    protected $lastError;

    /** @var boolean */
    protected $authorizationExecuted;

    public function __construct()
    {
        $this->globalFields = new \ArrayObject([], \ArrayObject::ARRAY_AS_PROPS);
    }

    public function batchWrite($configArray, $values)
    {
        if (! $this->isAuthorizationExecuted()) {
            $this->authorizationExecuted = true;
            $this->write($configArray['authorization'], $values);
        }

        foreach ($configArray['services'] as $configName => $config) {
            $values = array_merge($this->globalFields->getArrayCopy(), $values);
            $result = $this->write($config, $values);
            if ($result === 404) {
                return false;
            } elseif ($result === true) {
            } else {
                return false;
            }
        }

        return true;
    }

    public function write($config, $values = [])
    {
        $body = null;
        if (isset($config['body'])) {
            $body = MappingHelper::parseContent($config['body'], $values);
        }

        if (! isset($config['bodyType']) || $config['bodyType'] == 'json') {
            $body = json_encode($body);
        }

        $client = new Client();
        $headers = MappingHelper::parseContent($config['headers'], $values);
        $request = $client->createRequest($config['httpMethod'], MappingHelper::parseContent($config['serviceUrl'], $values), [
            'headers' => $headers,
            'body' => $body,
            'verify' => false
        ]);

        $returnConfig = (isset($config['return']))? $config['return'] : [];

        try {
            $response = $client->send($request);
            $resultArray = MappingHelper::parseJsonContent($response->getBody()->getContents(), $returnConfig);
            if (isset($config['expectedStatusCode']) && $response->getStatusCode() !== $config['expectedStatusCode']) {
                throw new \Exception(sprintf(
                    'Unexpected status code %s, expecting status code %s',
                    $response->getStatusCode(),
                    $config['expectedStatusCode']
                ));
            }
            $this->globalFields->exchangeArray(
                array_merge($this->globalFields->getArrayCopy(), $resultArray)
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

    /**
     * @return boolean
     */
    public function isAuthorizationExecuted()
    {
        return $this->authorizationExecuted;
    }

    /**
     * @param boolean $authorizationExecuted
     */
    public function setAuthorizationExecuted($authorizationExecuted)
    {
        $this->authorizationExecuted = $authorizationExecuted;
    }
}