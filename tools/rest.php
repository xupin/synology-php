<?php
if ($_SERVER['HTTP_HOST'] != 'localhost' &&
    $_SERVER['HTTP_HOST'] != '127.0.0.1' &&
    $_SERVER['HTTP_HOST'] != 'diskstation' &&
    substr($_SERVER['HTTP_HOST'], 0, 8) != '192.168.') {
    echo "Hello World\n";
    exit;
}
// use output buffer so that we can clean it up if necessary
ob_start();

$content = [];
$content['success'] = true;
$content['data'] = [];

if (empty($_SERVER['PATH_INFO'])) {
    $content['success'] = false;
    $content['data'] = $_SERVER;
    $content['data']['query'] = $_SERVER['QUERY_STRING'];
    $content['error'] = ['code' => 101, 'errors' => []];
} else {
    $pieces = explode('/', $_SERVER['PATH_INFO']);
    $api = $pieces[1];
    $version = substr($pieces[2], 1);
    $method = $pieces[3];
    include 'rest_mapping.php';
    if ($api2url[$api]) {
        $path = $api2url[$api];
    } else {
        $path = $api." is unknown";
    }
    $query = $_SERVER['QUERY_STRING'];
    // FIXME: if we use the Swagger UI
    if (!empty($query) && strpos($query, 'api_key=') !== false) {
        $query = str_replace('api_key=', '_sid=', $query);
    }
    $url = 'http://diskstation:5000/webapi/'.$path.'?api='.$api.'&version='.$version.'&method='.$method;
    if ($query) {
        $url .= '&'.$query;
    }
    $content['data'] = ['api' => $api, 'version' => $version, 'method' => $method, 'path' => $path, 'query' => $query, 'url' => $url];
    
    // create a new cURL resource
    $ch = curl_init();
    /*
    if ($httpMethod !== 'post') {
        $url = $this->_getBaseUrl() . $path . '?' . http_build_query($params, null, $this->_separator, $this->enc_type);
        $this->log($url, 'Requested Url');

        curl_setopt($ch, CURLOPT_URL, $url);
    } else {
        $url = $this->_getBaseUrl() . $path;
        $this->log($url, 'Requested Url');
        $this->log($params, 'Post Variable');

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($params));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params, null, $this->_separator, $this->enc_type));
    }
    */
    curl_setopt($ch, CURLOPT_URL, $url);
    
    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 30000); //30s

    // Verify SSL or not
    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $this->_verifySSL);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->_verifySSL);

    // grab URL and pass it to the browser
    $result = curl_exec($ch);
    $info   = curl_getinfo($ch);

    $content['error']['code'] = $info['http_code'];
    $content['error']['errors']['info'] = $info;
    $content['error']['errors']['result'] = $result;
    if (200 == $info['http_code']) {
        /*
        if (preg_match('#(plain|text)#', $info['content_type'])) {
            return $this->_parseRequest($result);
        } else {
            return $result;
        }
        */
        //$content['error']['errors']['result'] = json_decode($result);
        $content = json_decode($result);
    } else {
        curl_close($ch);
        /*
        if ($info['total_time'] >= (self::CONNECT_TIMEOUT / 1000)) {
            throw new Exception('Connection Timeout');
        } else {
            $this->log($result, 'Result');
            throw new Exception('Connection Error');
        }
        */
    }
    // close cURL resource, and free up system resources
    //curl_close($ch);
}
ob_end_clean();
$type = 'application/json';
header("Content-type: $type");
//header("Content-disposition: attachment; filename=" . urlencode(basename($file)));
echo json_encode($content);
exit;
