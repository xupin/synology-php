<?php

namespace Synology\Applications;

use Synology\Api\Authenticate;
use Synology\Exception;

/**
 * Class DSM
 *
 * @package Synology\Applications
 */
class Core extends Authenticate
{
    public const API_SERVICE_NAME = 'Core';
    public const API_VERSION = 1;

    /**
     * Info API setup
     *
     * @param string $address
     * @param int    $port
     * @param string $protocol
     * @param int    $version
     * @param bool   $verifySSL
     */
    public function __construct($address, $port = null, $protocol = null, $version = self::API_VERSION, $verifySSL = false)
    {
        parent::__construct(static::API_SERVICE_NAME, static::API_NAMESPACE, $address, $port, $protocol, $version, $verifySSL);
    }

    /**
     * Get a list of objects
     *
     * @param string $type (User|Share|Group|AppPortal|Service|Package|Network|Security.AutoBlock|CurrentConnection)
     * @param int    $limit
     * @param int    $offset
     *
     * @return array
     */
    public function getObjects($type, $limit = 25, $offset = 0)
    {
        $path = '';
        $method = 'list';
        $version = static::API_VERSION;
        switch ($type) {
            case 'User':
                $path = 'entry.cgi';
                break;
            case 'Share':
                $path = 'entry.cgi';
                break;
            case 'Group':
                $path = 'entry.cgi';
                break;
            case 'AppPortal':
                $path = 'entry.cgi';
                break;
            case 'Service':
                $path = 'entry.cgi';
                $method = 'get';
                $version = 3;
                break;
            case 'Package':
                $path = 'entry.cgi';
                break;
            case 'Network':
                $path = 'entry.cgi';
                $method = 'get';
                break;
            //case 'Volume':
            //    $path = 'dsm/volume.cgi';
            //    break;
            case 'Security.AutoBlock':
                $path = 'entry.cgi';
                $method = 'get';
                break;
            //case 'LogViewer':
            //    $path = 'dsm/logviewer.cgi';
            //    break;
            case 'CurrentConnection':
                $path = 'entry.cgi';
                break;
            //case 'iSCSI':
            //    $path = 'dsm/iscsi.cgi';
            //    break;
            default:
                new Exception('Unknown "' . $type . '" object');
        }

        return $this->_request($type, $path, $method, ['limit' => $limit, 'offset' => $offset], $version);
    }
}
