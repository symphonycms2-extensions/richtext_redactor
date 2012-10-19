<?php

$name = $_GET["name"];

if(file_exists(Symphony::Configuration()->get('filepath','redactor') . '/' . $name)) {
	$path = Symphony::Configuration()->get('filepath','redactor') . '/' . $name;
} else if(file_exists(Symphony::Configuration()->get('imagepath','redactor') . '/' . $name)) {
	$path = Symphony::Configuration()->get('imagepath','redactor') . '/' . $name;
}

if(isset($path)) {
	try {
		if (function_exists('finfo_open')) {
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$type = finfo_file($finfo, $path);
			finfo_close($finfo);
		} else {
			$type = mime_content_type($path);
		}
		header('Content-Type: ' . $type);
		readfile($path);
	} catch (Exception $e) {
		header("HTTP/1.0 500 Server Error");
	}
} else {
	header("HTTP/1.0 404 Not Found");
}

die();

?>