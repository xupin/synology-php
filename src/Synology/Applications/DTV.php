<?php

namespace Synology\Applications;

use Synology\Api\Authenticate;

/**
 * Class DTV
 *
 * @package Synology\Applications
 */
class DTV extends Authenticate
{
    const API_SERVICE_NAME = 'DTV';

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
}