<?php

namespace Heap;

use Heap\Exception\HeapException;
use Heap\Helper\Request;

class Client
{
    private $appId;

    /**
     * Client constructor.
     *
     * @param null $appId
     */
    public function __construct($appId)
    {
        $this->appId = $appId;
        $this->request = new Request();
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
     * @param $event
     * @param $identity
     * @param array $properties
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws HeapException
     */
    public function track($event, $identity, $properties = array())
    {
        if(!$event || empty($event)) {
            throw new HeapException('You need to set the event name.');
        }

        if(!$identity || empty($identity)) {
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

        return $this->request->call('POST', '/track', $data);
    }

    /**
     * @param $identity
     * @param array $properties
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws HeapException
     */
    public function addUserProperties($identity, $properties = array())
    {
        if(!$identity || empty($identity)) {
            throw new HeapException('You need to set the identity. More info: https://heapanalytics.com/docs/server-side');
        }

        $data = array(
            'app_id' => $this->appId,
            'properties' => $properties,
            'identity' => $identity,
        );

        return $this->request->call('POST', '/add_user_properties', $data);
    }
}
