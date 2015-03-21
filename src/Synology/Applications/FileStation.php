<?php

namespace Synology\Applications;

use Synology\Api\Authenticate;
use Synology\Exception;

/**
 * Class FileStation
 *
 * @see     http://ukdl.synology.com/download/Document/DeveloperGuide/Synology_File_Station_API_Guide.pdf
 * @package Synology\Applications
 */
class FileStation extends Authenticate
{
    const API_SERVICE_NAME = 'FileStation';

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
     * Return Information about VideoStation
     * - is_manager
     * - version
     * - version_string
     */
    public function getInfo()
    {
        return $this->_request('Info', 'FileStation/info.cgi', 'getinfo');
    }

    /**
     * Get Available Shares
     *
     * @param bool|string $onlyWritable
     * @param int|number  $limit
     * @param int|number  $offset
     * @param string      $sortBy
     * @param string      $sortDirection
     * @param bool        $additional
     *
     * @return array
     *
     * @throws Exception
     */
    public function getShares($onlyWritable = false, $limit = 25, $offset = 0, $sortBy = 'name', $sortDirection = 'asc', $additional = false)
    {
        return $this->_request('List', 'FileStation/file_share.cgi', 'list_share', [
            'onlywritable'   => $onlyWritable,
            'limit'          => $limit,
            'offset'         => $offset,
            'sort_by'        => $sortBy,
            'sort_direction' => $sortDirection,
            'additional'     => $additional ? 'real_path,owner,time,perm,volume_status' : ''
        ]);
    }

    /**
     * Get info about an object
     *
     * @param string $type (List|Sharing)
     * @param string $id
     *
     * @return array
     *
     * @throws Exception
     */
    public function getObjectInfo($type, $id)
    {
        switch ($type) {
            case 'List':
                $path = 'FileStation/file_share.cgi';
                break;
            case 'Sharing':
                $path = 'FileStation/file_sharing.cgi';
                break;
            default:
                throw new Exception('Unknown "' . $type . '" object');
        }

        return $this->_request($type, $path, 'getinfo', ['id' => $id]);
    }

    /**
     * Get a list of files/directories in a given path
     *
     * @param string     $path     like '/home'
     * @param int|number $limit
     * @param int|number $offset
     * @param string     $sortBy   (name|size|user|group|mtime|atime|ctime|crtime|posix|type)
     * @param string     $sortDirection
     * @param string     $pattern
     * @param string     $fileType (all|file|dir)
     * @param bool       $additional
     *
     * @return array
     * @throws Exception
     */
    public function getList($path = '/home', $limit = 25, $offset = 0, $sortBy = 'name', $sortDirection = 'asc', $pattern = '', $fileType = 'all', $additional = false)
    {
        return $this->_request('List', 'FileStation/file_share.cgi', 'list', [
            'folder_path'    => $path,
            'limit'          => $limit,
            'offset'         => $offset,
            'sort_by'        => $sortBy,
            'sort_direction' => $sortDirection,
            'pattern'        => $pattern,
            'filetype'       => $fileType,
            'additional'     => $additional ? 'real_path,size,owner,time,perm' : ''
        ]);
    }

    /**
     * Search for files/directories in a given path
     *
     * @param string     $pattern
     * @param string     $path          like '/home'
     * @param int|number $limit
     * @param int|number $offset
     * @param string     $sortBy        (name|size|user|group|mtime|atime|ctime|crtime|posix|type)
     * @param string     $sortDirection (asc|desc)
     * @param string     $fileType      (all|file|dir)
     * @param bool       $additional
     *
     * @return array
     * @throws Exception
     */
    public function search($pattern, $path = '/home', $limit = 25, $offset = 0, $sortBy = 'name', $sortDirection = 'asc', $fileType = 'all', $additional = false)
    {
        return $this->_request('List', 'FileStation/file_share.cgi', 'list', [
            'folder_path'    => $path,
            'limit'          => $limit,
            'offset'         => $offset,
            'sort_by'        => $sortBy,
            'sort_direction' => $sortDirection,
            'pattern'        => $pattern,
            'filetype'       => $fileType,
            'additional'     => $additional ? 'real_path,size,owner,time,perm' : ''
        ]);
    }

    /**
     * Download a file
     *
     * @param string $path (comma separated)
     * @param string $mode
     *
     * @return array
     */
    public function download($path, $mode = 'open')
    {
        return $this->_request('Download', 'FileStation/file_download.cgi', 'download', [
            'path' => $path,
            'mode' => $mode
        ]);
    }
}