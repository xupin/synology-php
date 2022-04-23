<?php

namespace Synology\Applications;

use Synology\Api\Authenticate;
use Synology\Exception;

/**
 * Class VideoStation
 *
 * @package Synology\Applications
 */
class VideoStation extends Authenticate
{
    public const API_SERVICE_NAME = 'VideoStation';
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
     * Return Information about VideoStation
     * - is_manager
     * - version
     * - version_string
     */
    public function getInfo()
    {
        return $this->_request('Info', 'VideoStation/info.cgi', 'getinfo');
    }

    /**
     * Get a list of objects
     *
     * @param string $type (Movie|TVShow|TVShowEpisode|HomeVideo|TVRecording|Collection|Library)
     * @param int    $limit
     * @param int    $offset
     *
     * @return array
     *
     * @throws Exception
     */
    public function getObjects($type, $limit = 25, $offset = 0)
    {
        $path = '';
        $type = ucfirst($type);
        switch ($type) {
            case 'Movie':
                $path = 'VideoStation/movie.cgi';
                break;
            case 'TVShow':
                $path = 'VideoStation/tvshow.cgi';
                break;
            case 'TVShowEpisode':
                $path = 'VideoStation/tvshow_episode.cgi';
                break;
            case 'HomeVideo':
                $path = 'VideoStation/homevideo.cgi';
                break;
            case 'TVRecording':
                $path = 'VideoStation/tvrecord.cgi';
                break;
            case 'Collection':
                $path = 'VideoStation/collection.cgi';
                break;
            case 'Library':
                $path = 'VideoStation/library.cgi';
                break;
            default:
                throw new Exception('Unknown "' . $type . '" object');
        }

        return $this->_request($type, $path, 'list', ['limit' => $limit, 'offset' => $offset]);
    }

    /**
     * Search for Movie|TVShow|TVShowEpisode|HomeVideo|TVRecording|Collection
     *
     * @param string     $name
     * @param string     $type          (Movie|TVShow|TVShowEpisode|HomeVideo|TVRecording|Collection)
     * @param int|number $limit
     * @param int|number $offset
     * @param string     $sortBy        (title|original_available)
     * @param string     $sortDirection (asc|desc)
     *
     * @return array
     *
     * @throws Exception
     */
    public function searchObject($name, $type, $limit = 25, $offset = 0, $sortBy = 'title', $sortDirection = 'asc')
    {
        $path = '';
        $type = ucfirst($type);
        switch ($type) {
            case 'Movie':
                $path = 'VideoStation/movie.cgi';
                break;
            case 'TVShow':
                $path = 'VideoStation/tvshow.cgi';
                break;
            case 'TVShowEpisode':
                $path = 'VideoStation/tvshow_episode.cgi';
                break;
            case 'HomeVideo':
                $path = 'VideoStation/homevideo.cgi';
                break;
            case 'TVRecording':
                $path = 'VideoStation/tvrecord.cgi';
                break;
            case 'Collection':
                $path = 'VideoStation/collection.cgi';
                break;
            default:
                throw new Exception('Unknown "' . $type . '" object');
        }

        return $this->_request($type, $path, 'search', [
            'title'          => $name,
            'limit'          => $limit,
            'offset'         => $offset,
            'sort_by'        => $sortBy,
            'sort_direction' => $sortDirection,
        ]);
    }
}
