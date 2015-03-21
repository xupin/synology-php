<?php

namespace Synology;

/**
 * Class Api
 *
 * @package Synology
 */
class Api extends AbstractApi
{
    const API_SERVICE_NAME = 'API';

    private $_sid = null;
    private $_sessionName = 'default';

    /**
     * Info API setup
     *
     * @param string $address
     * @param int    $port
     * @param string $protocol
     * @param int    $version
     */
    public function __construct($address, $port = null, $protocol = null, $version = 1)
    {
        parent::__construct(self::API_SERVICE_NAME, $this->_apiNamespace, $address, $port, $protocol, $version);
    }

    /**
     * Get a list of Service and Apis
     *
     * @return array
     */
    public function getAvailableApi()
    {
        $services = [];
        foreach ($this->_request('Info', 'query.cgi', 'query', ['query' => 'all']) as $key => $value) {
            $keys = explode('.', $key);
            if (!array_key_exists($keys[0], $services)) {
                $services[$keys[0]] = [];
            }


            if (!array_key_exists($keys[1], $services[$keys[0]])) {
                $services[$keys[0]][$keys[1]] = [];
            }

            $services[$keys[0]][$keys[1]][$keys[2]] = $value;
        }

        return $services;
    }

    /**
     * Connect to Synology
     *
     * @param string $username
     * @param string $password
     * @param string $sessionName
     *
     * @return Api
     */
    public function connect($username, $password, $sessionName = null)
    {
        if (!empty($sessionName)) {
            $this->_sessionName = $sessionName;
        }

        $this->log($this->_sessionName, 'Connect Session');
        $this->log($username, 'User');

        $options = [
            'account' => $username,
            'passwd'  => $password,
            'session' => $this->_sessionName,
            'format'  => 'sid'
        ];
        $data = $this->_request('Auth', 'auth.cgi', 'login', $options, 2);

        // save session name id
        $this->_sid = $data->sid;

        return $this;
    }

    /**
     * Logout from Synology
     *
     * @return Api
     */
    public function disconnect()
    {
        $this->log($this->_sessionName, 'Disconnect Session');
        $this->_request('Auth', 'auth.cgi', 'logout', ['_sid' => $this->_sid, 'session' => $this->_sessionName]);
        $this->_sid = null;

        return $this;
    }

    /**
     * Return Session Id
     *
     * @throws Exception
     *
     * @return string
     */
    public function getSessionId()
    {
        if ($this->_sid) {
            return $this->_sid;
        } else {
            throw new Exception('Missing session');
        }
    }

    /**
     * Return true if connected
     *
     * @return boolean
     */
    public function isConnected()
    {
        if (!empty($this->_sid)) {
            return true;
        }

        return false;
    }

    /**
     * Return Session Name
     *
     * @return string
     */
    public function getSessionName()
    {
        return $this->_sessionName;
    }

    public function __destruct()
    {
        if ($this->_sid !== null) {
            $this->disconnect();
        }
    }
}
