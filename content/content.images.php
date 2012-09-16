<?php

$root = WORKSPACE . '/redactor/';

General::realiseDirectory($root);
$files = General::listStructure($root, null, false, 'asc', $root);

$result = array();

foreach($files['filelist'] as $file) {
	$result[] = array(
		'thumb' => URL . '/redactor/' . $file,
		'image' => URL . '/redactor/' . $file,
		'title' => $file
	);
}

echo stripslashes(json_encode($result));

die();
	
?>