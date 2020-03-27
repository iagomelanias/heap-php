<?php

namespace Heap;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use Heap\Exception\HeapException;
use Psr\Http\Message\ResponseInterface;

class Client
{
    /**
     * @var string
     */
    private $apiUri = 'https://heapanalytics.com/api';

    /**
     * @var string
     */
    private $appId;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * Client constructor.
     *
     * @param null                 $appId
     * @param ClientInterface|null $httpClient
     */
    public function __construct($appId, ClientInterface $httpClient = null)
    {
        if (! $httpClient) {
            $httpClient = new \GuzzleHttp\Client();
        }

        $this->appId = $appId;
        $this->httpClient = $httpClient;
    }

    /**
     * @return null
     */
    public function getAppId()
    {
        return $this->appId;
    }


    public function setAppId($appId)
    {
        $this->appId = $appId;
    }

    /**
     * @param       $event
     * @param       $identity
     * @param array $properties
     *
     * @return ResponseInterface
     * @throws HeapException
     * @throws GuzzleException
     */
    public function track($event, $identity, $properties = array())
    {
        if (!$event || empty($event)) {
            throw new HeapException('You need to set the event name.');
        }

        if (!$identity || empty($identity)) {
            throw new HeapException('You need to set the identity. More info: https://heapanalytics.com/docs/server-side');
        }

        $data = array(
            'app_id' => $this->appId,
            'event' => $event,
            'identity' => $identity,
        );
        
        if(!empty($properties)){
            $data['properties'] = $properties;
        }

        return $this->call('POST', '/track', $data);
    }

    /**
     * @param       $identity
     * @param array $properties
     *
     * @return ResponseInterface
     * @throws HeapException
     * @throws GuzzleException
     */
    public function addUserProperties($identity, $properties = array())
    {
        if (!$identity || empty($identity)) {
            throw new HeapException('You need to set the identity. More info: https://heapanalytics.com/docs/server-side');
        }

        $data = array(
            'app_id' => $this->appId,
            'properties' => $properties,
            'identity' => $identity,
        );

        return $this->call('POST', '/add_user_properties', $data);
    }

    /**
     * @param $endpoint
     *
     * @return string
     */
    public function generateUri($endpoint)
    {
        return $this->apiUri.$endpoint;
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array  $data
     *
     * @return ResponseInterface
     * @throws HeapException
     * @throws GuzzleException
     */
    public function call($method, $endpoint, $data)
    {
        $fullUri = $this->generateUri($endpoint);

        try {
            $response = $this->httpClient->request($method, $fullUri, array(
                'headers' => array(
                    'Content-Type' => 'application/json',
                ),
                'body' => \GuzzleHttp\json_encode($data),
            ));
        } catch (ServerException $e) {
            throw new HeapException($e->getMessage());
        }

        return $response;
    }
}
