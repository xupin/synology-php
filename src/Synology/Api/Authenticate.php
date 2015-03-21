<?php

namespace Synology\Api;

use Synology\AbstractApi;
use Synology\Api;
use Synology\Exception;

/**
 * Class Authenticate
 *
 * @package Synology\Api
 */
class Authenticate extends AbstractApi
{
    private $_authApi = null;
    private $_sessionName = null;

    /**
     * Constructor
     *
     * @param string $serviceName
     * @param string $namespace
     * @param string $address
     * @param int    $port
     * @param string $protocol
     * @param int    $version
     */
    public function __construct($serviceName, $namespace, $address, $port = null, $protocol = null, $version = 1)
    {
        parent::__construct($serviceName, $namespace, $address, $port, $protocol, $version);
        $this->_sessionName = $serviceName;
        $this->_authApi     = new Api($address, $port, $protocol, $version);
    }


    /**
     * Connect to Synology
     *
     * @param string $login
     * @param string $password
     *
     * @return Api
     */
    public function connect($login, $password)
    {
        return $this->_authApi->connect($login, $password, $this->_sessionName);
    }

    /**
     * Disconnect to Synology
     */
    public function disconnect()
    {
        return $this->_authApi->disconnect();
    }

    /**
     * {@inheritDoc}
     */
    protected function _request($api, $path, $method, $params = [], $version = null, $httpMethod = 'get')
    {
        if ($this->_authApi->isConnected()) {
            if (!is_array($params)) {
                if (!empty($params)) {
                    $params = [$params];
                } else {
                    $params = [];
                }
            }

            $params['_sid'] = $this->_authApi->getSessionId();

            return parent::_request($api, $path, $method, $params, $version, $httpMethod);
        }
        throw new Exception('Not Connected');
    }

    /**
     * {@inheritDoc}
     */
    public function activateDebug()
    {
        parent::activateDebug();
        $this->_authApi->activateDebug();
    }
}