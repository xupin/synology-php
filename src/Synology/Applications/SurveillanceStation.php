<?php
/**
 *
 * @author Ondrej Pospisil <https://github.com/pospon>
 * https://global.download.synology.com/download/Document/DeveloperGuide/Surveillance_Station_Web_API_v2.4.pdf
 */

namespace Synology\Applications;

use Synology\Api\Authenticate;
use Synology\Exception;

class SurveillanceStation extends Authenticate
{
    public const API_SERVICE_NAME = 'SurveillanceStation';
    public const API_NAMESPACE = 'SYNO';

    private static $path = 'entry.cgi';

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

    /**
     * @return array|bool|\stdClass
     * @throws Exception
     */
    public function getInfo()
    {
        return $this->_request('Info', static::$path, 'GetInfo');
    }

    /**
     * @return array|bool|\stdClass
     * @throws Exception
     */
    public function getCameraList()
    {
        return $this->_request('Camera', static::$path, 'List');
    }

    /**
     * @param $cameraId
     * @return array|bool|\stdClass
     * @throws Exception
     */
    public function getSnapshot($cameraId)
    {
        $parameters = [
            'cameraId' => $cameraId,
        ];
        return $this->_request('Camera', static::$path, 'GetSnapshot', $parameters);
    }
}
