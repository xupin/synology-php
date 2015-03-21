<?php

namespace Synology\Applications;

use Synology\Api\Authenticate;
use Synology\Exception;

/**
 * Class Synology_AudioStation_Api
 *
 * @package Synology\Applications
 */
class AudioStation extends Authenticate
{
    const API_SERVICE_NAME = 'AudioStation';

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
     * Return Information about AudioStation
     * - is_manager
     * - version
     * - version_string
     */
    public function getInfo()
    {
        return $this->_request('Info', 'AudioStation/info.cgi', 'getinfo', [], 2);
    }

    /**
     * Get a list of objects
     *
     * @param string $type (Album|Composer|Genre|Artist|Folder|Song|Radio|Playlist|RemotePlayer|MediaServer)
     * @param int    $limit
     * @param int    $offset
     *
     * @return array
     *
     * @throws Exception
     */
    public function getObjects($type, $limit = 25, $offset = 0)
    {
        switch ($type) {
            case 'Album':
                $path = 'AudioStation/album.cgi';
                break;
            case 'Composer':
                $path = 'AudioStation/composer.cgi';
                break;
            case 'Genre':
                $path = 'AudioStation/genre.cgi';
                break;
            case 'Artist':
                $path = 'AudioStation/artist.cgi';
                break;
            case 'Folder':
                $path = 'AudioStation/folder.cgi';
                break;
            case 'Song':
                $path = 'AudioStation/song.cgi';
                break;
            case 'Radio':
                $path = 'AudioStation/radio.cgi';
                break;
            case 'Playlist':
                $path = 'AudioStation/playlist.cgi';
                break;
            case 'RemotePlayer':
                $path = 'AudioStation/remote_player.cgi';
                break;
            case 'MediaServer':
                $path = 'AudioStation/media_server.cgi';
                break;
            default:
                throw new Exception('Unknown "' . $type . '" object');
        }

        return $this->_request($type, $path, 'list', ['limit' => $limit, 'offset' => $offset]);
    }

    /**
     * Get info about an object
     *
     * @param string $type (Folder|Song|Playlist)
     * @param string $id
     *
     * @return array
     *
     * @throws Exception
     */
    public function getObjectInfo($type, $id)
    {
        switch ($type) {
            case 'Folder':
                $path = 'AudioStation/folder.cgi';
                break;
            case 'Song':
                $path = 'AudioStation/song.cgi';
                break;
            case 'Playlist':
                $path = 'AudioStation/playlist.cgi';
                break;
            default:
                throw new Exception('Unknown "' . $type . '" object');
        }

        return $this->_request($type, $path, 'getinfo', ['id' => $id]);
    }

    /**
     * Get cover of an object
     *
     * @param string $type (Song|Folder)
     * @param string $id
     *
     * @return array
     *
     * @throws Exception
     */
    public function getObjectCover($type, $id)
    {
        switch ($type) {
            case 'Song':
                $method = 'getsongcover';
                break;
            case 'Folder':
                $method = 'getfoldercover';
                break;
            default:
                throw new Exception('Unknown "' . $type . '" object');
        }

        return $this->_request('Cover', 'AudioStation/cover.cgi', $method, ['id' => $id]);
    }

    /**
     * Search for Movie|TVShow|TVShowEpisode|HomeVideo|TVRecording|Collection
     *
     * @param string     $name
     * @param int|number $limit
     * @param int|number $offset
     * @param string     $sortBy        (title|original_available)
     * @param string     $sortDirection (asc|desc)
     *
     * @return array
     */
    public function searchSong($name, $limit = 25, $offset = 0, $sortBy = 'title', $sortDirection = 'asc')
    {
        return $this->_request('Song', 'AudioStation/song.cgi', 'search', [
            'title'          => $name,
            'limit'          => $limit,
            'offset'         => $offset,
            'sort_by'        => $sortBy,
            'sort_direction' => $sortDirection
        ]);
    }
}