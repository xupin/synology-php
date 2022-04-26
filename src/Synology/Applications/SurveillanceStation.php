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
     * @return array|bool|\stdClass
     * @throws Exception
     */
    public function getInfo()
    {
        return $this->_request('Info', static::API_PATH, 'GetInfo');
    }

    /**
     * @return array|bool|\stdClass
     * @throws Exception
     */
    public function getCameraList()
    {
        return $this->_request('Camera', static::API_PATH, 'List');
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
        return $this->_request('Camera', static::API_PATH, 'GetSnapshot', $parameters);
    }
}
