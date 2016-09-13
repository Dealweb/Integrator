<?php
namespace Dealweb\Integrator\Source\Adapter;

use Dealweb\Integrator\Helper\MappingHelper;
use Dealweb\Integrator\Source\SourceInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

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

        return false;
    }

    public function read($config, $mergeResult = true)
    {
        $values = $this->globalFields->getArrayCopy();

        $body = null;

        if (isset($config['body'])) {
            $body = MappingHelper::parseContent($config['body'], $values);
        }

        if (! isset($config['bodyType']) || $config['bodyType'] == 'json') {
            $body = json_encode($body);
        }

        $client = new Client();
        $headers = MappingHelper::parseContent($config['headers'], $values);
        $request = $client->createRequest(
            $config['httpMethod'],
            MappingHelper::parseContent($config['serviceUrl'], $values),
            [
                'headers' => $headers,
                'body' => $body,
                'verify' => false
            ]
        );

        $returnConfig = (isset($config['return']))? $config['return'] : [];
        $listRootConfig = (isset($config['listRoot']))? $config['listRoot'] : null;

        try {
            $response = $client->send($request);
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
