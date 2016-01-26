<?php

/**
 * Generate new swagger files for Synology Web APIs
 *
 * This PHP script will analyze the API json files available in ../docs
 * when you install this package with composer, and generate new swagger
 * files based on the APIs and templates provided.
 *
 * If you install Swagger Editor or Swagger UI under your Web Station, and
 * adapt the .htaccess file as mentioned in the swagger file, you can then
 * explore some of the Synology Web APIs directly in your browser.
 *
 *
 * If you have an older DSM release or older packages that don't support the
 * latest API version, you can refresh the API files by calling the function
 * refresh_api_files() below and generate the swagger files again.
 */

function load_template($name) {
	$file = 'templates'.DIRECTORY_SEPARATOR.$name.'.yaml';
	return file_get_contents($file);
}

function replace_params($template, $params) {
	$search = [];
	$replace = [];
	foreach ($params as $key => $val) {
		array_push($search, '%'.$key.'%');
		array_push($replace, $val);
	}
	return str_replace($search, $replace, $template);
}

function get_ip_address() {
	$hostname = gethostname();
	echo $hostname;
	$ip = gethostbyname($hostname);
	echo $ip;
	if ($ip != $host) return $ip;
	return 'diskstation';
}

//refresh_api_files('../docs/');

// if running on the Synology, this should be enough to start
//$host = get_ip_address();
$host = 'diskstation';
// if running elsewhere, please adapt manually
//$host = '192.168.x.x';

$http = 'http';
if ($http == 'http') {
	$port = 5000;
} else {
	$port = 5001;
}

$header_tmpl = load_template('header');
$path_tmpl = load_template('path');
$query_tmpl = load_template('query');

$params = [];
$params['host'] = $host;
$params['port'] = $port;
$params['http'] = $http;
$params['location'] = 'path';
$path_output = replace_params($header_tmpl, $params);
$path_output .= load_template('api_path');
$params['location'] = 'query';
$query_output = replace_params($header_tmpl, $params);
$query_output .= load_template('api_query');

// Load API json files from ../docs directory
$dir = '..'.DIRECTORY_SEPARATOR.'docs';
$files = scandir($dir);
$paths = [];
foreach ($files as $file) {
	// skip API file itself
	if ($file == 'API.json' || $file == 'query.api') {
		continue;
	}
	$filepath = $dir.DIRECTORY_SEPARATOR.$file;
	if (!is_file($filepath)) {
		continue;
	}
	echo $file."\n";
	$contents = file_get_contents($filepath);
	$json = json_decode($contents, true);
	foreach ($json as $key => $values) {
		$params = [];
		$params['api'] = $key;
		echo "\t".$key."\n";
		$params['tag'] = explode('.', $key)[1];
		foreach ($values as $idx => $val) {
			echo "\t\t".$idx.":".$val."\n";
		}
		$path = $values['path'];
		// FIXME: dirty hack for relative PhotoStation URIs - this uses a PHP session id to authenticate?
		if ($params['tag'] == 'PhotoStation' && strpos($path, '/') === false) {
			$path = 'photo/webapi/' . $path;
		}
		if (array_key_exists($path, $paths)) {
			echo $path." is already defined: ".$paths[$path]."\n";
			//exit;
			continue;
		}
		$paths[$path] = $key;
		$params['path'] = $path;
		$params['version'] = $values['maxVersion'];
		$methods = $values['methods'][$params['version']];
		echo "\t\t(".count($methods).") ".implode(',', $methods)."\n";
		$params['hash'] = '';
		if (in_array('list', $methods)) {
			$params['default'] = 'list';
		} elseif (in_array('getinfo', $methods)) {
			$params['default'] = 'getinfo';
		} elseif (in_array('getconfig', $methods)) {
			$params['default'] = 'getconfig';
		} elseif (count($methods) == 1) {
			$params['default'] = $methods[0];
		} else {
			$params['default'] = '???';
			$params['hash'] = '#';
			//continue;
		}
		$params['methodlist'] = implode(', ', $methods);
		$query_output .= replace_params($query_tmpl, $params);
		foreach ($methods as $method) {
			$params['method'] = $method;
			$path_output .= replace_params($path_tmpl, $params);
		}
	}
}

$path_file = 'swagger_path.yaml';
file_put_contents($path_file, $path_output);
echo 'Generated '.$path_file."\n";

$query_file = 'swagger_query.yaml';
file_put_contents($query_file, $query_output);
echo 'Generated '.$query_file."\n";

function refresh_api_files($basedir) {
	// Create basedir if necessary
	if (!is_dir($basedir)) {
		mkdir($basedir);
	}
	// Find *Station packages and corresponding *.api files in the appstore (adapt volume if needed)
	$path = '/volume1/@appstore/';
	$found = `find $path -path '*Station/*' -name '*.api' -exec cp {} $basedir \;`;
	// Some cleanup of incomplete API files
	$checkme = ['Auth.api', 'Query.api'];
	foreach ($checkme as $file) {
		if (is_file($basedir.$file)) {
			unlink($basedir.$file);
		}
	}
	// Find *.api files in the webapi itself
	$path = '/usr/syno/synoman/webapi/';
	$found = `find $path -name '*.api' -exec cp {} $basedir \;`;
	$files = scandir($basedir);
	$count = count($files) - 2;
	echo "Found ".$count." API files...\n";
}
?>