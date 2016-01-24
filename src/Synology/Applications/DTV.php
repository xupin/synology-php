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
    const API_NAMESPACE = 'SYNO';

    /**
     * Info API setup
     *
     * @param string $address
     * @param int    $port
     * @param string $protocol
     * @param int    $version
     * @param bool   $verifySSL
     */
    public function __construct($address, $port = null, $protocol = null, $version = 1, $verifySSL = false)
    {
        parent::__construct(self::API_SERVICE_NAME, self::API_NAMESPACE, $address, $port, $protocol, $version, $verifySSL);
    }
}