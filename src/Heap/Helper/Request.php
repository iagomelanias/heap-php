<?php

namespace Heap\Helper;

use GuzzleHttp\Exception\ServerException;
use Heap\Exception\HeapException;
use GuzzleHttp\Client;

class Request
{
    private $apiUri = 'https://heapanalytics.com/api';

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->http = new Client();
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
     * @param array $data
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws HeapException
     */
    public function call($method, $endpoint, $data)
    {
        $fullUri = $this->generateUri($endpoint);

        try {
            $response = $this->http->request($method, $fullUri, array(
                'headers' => array(
                    'Content-Type' => 'application/json',
                ),
                'body' => \GuzzleHttp\json_encode($data),
            ));
        } catch(ServerException $e) {
            throw new HeapException($e->getMessage());
        }

        return $response;
    }
}