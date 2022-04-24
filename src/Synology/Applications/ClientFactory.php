<?php

namespace Synology\Applications;

use Synology\Api\Authenticate;
use Synology\Exception;

/**
 * Class ClientFactory
 *
 * @package Synology\Applications
 */
class ClientFactory
{
    public const API_SERVICE_CLIENTS = [
        'AudioStation',
        'Core',
        'DownloadStation',
        'DSM',
        'DTV',
        'FileStation',
        'SurveillanceStation',
        'VideoStation',
    ];

    /**
     * Get Synology API Client for serviceName
     *
     * @param string $serviceName
     * @param string $address
     * @param int    $port
     * @param string $protocol
     */
    public static function getClient($serviceName, $address, $port = null, $protocol = null)
    {
        if (!empty($serviceName) && in_array($serviceName, self::API_SERVICE_CLIENTS)) {
            $className = "\\Synology\\Applications\\" . $serviceName;
            return new $className($address, $port, $protocol);
        }
        //throw new Exception('Unknown "' . $serviceName . '" serviceName');
        return static::getGeneric($serviceName, $address, $port, $protocol);
    }

    /**
     * Get Generic API Client for serviceName
     *
     * @param string $serviceName
     * @param string $address
     * @param int    $port
     * @param string $protocol
     */
    public static function getGeneric($serviceName, $address, $port = null, $protocol = null)
    {
        $className = "\\Synology\\Applications\\GenericClient";
        return new $className($serviceName, $address, $port, $protocol);
    }
}
