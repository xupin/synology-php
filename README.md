Synology PHP
=================

This is an update of ``https://github.com/zzarbi/synology`` with new code standards and namespaces.

You will find some additional [Tools](./tools/) here as well, including a [Synology Web API Explorer](https://github.mikespub.net/synology/tools/index.html) using a basic [REST API](./tools/rest.php) interface and generated swagger files.

This is a PHP Library that consume Synology APIs

* SYNO.Api :
    * connect
    * disconnect
    * getAvailableApi

* SYNO.SynologyDriveServer :
    * connect
    * disconnect
    * getConnection
    * getShare
    * getLog

* SYNO.CloudStationServer :
    * connect
    * disconnect
    * getConnection
    * getLog

* SYNO.DownloadStation :
    * connect
    * disconnect
    * getInfo
    * getConfig
    * setConfig
    * getScheduleConfig
    * setScheduleConfig
    * getTaskList
    * getTaskInfo
    * addTask
    * deleteTask
    * pauseTask
    * resumeTask
    * getStatistics
    * getRssList
    * refreshRss
    * getRssFeedList

* SYNO.AudioStation:
    * connect
    * disconnect
    * getInfo
    * getObjects
    * getObjectInfo
    * getObjectCover
    * searchSong
    
* SYNO.FileStation:
    * connect
    * disconnect
    * getInfo
    * getShares
    * getObjectInfo
    * getList
    * search
    * download
    * createFolder
    
* SYNO.VideoStation:
    * connect
    * disconnect
    * getInfo
    * getObjects
    * searchObject
    * listObjects
    
* SYNO.SurveillanceStation:
    * connect
    * disconnect
    * getInfo
    * getCameraList
    * getHomeModeInfo
    * switchHomeMode

Usage for Synology Api:
```php
$synology = new Synology\Api('192.168.10.5', 5000, 'http', 1);
//$synology->activateDebug();
$synology->connect('admin', 'xxxx');
print_r($synology->getAvailableApi());
``` 
 
Usage for AudioStation:
```php
$synology = new Synology\Applications\AudioStation('192.168.10.5', 5000, 'http', 1);
$synology->connect('admin', 'xxxx');
print_r($synology->getInfo());
```
