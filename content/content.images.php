<?php

$root = Symphony::Configuration()->get('imagepath','redactor');

General::realiseDirectory($root);
$files = General::listStructure($root, null, false, 'asc', $root);

$result = array();

if(isset($files) && isset($files['filelist'])) {
	foreach($files['filelist'] as $file) {
		$result[] = array(
			'thumb' => URL . '/symphony/extension/richtext_redactor/getfile/?name=' . $file,
			'image' => URL . '/symphony/extension/richtext_redactor/getfile/?name=' . $file,
			'title' => $file
		);
	}
}

echo stripslashes(json_encode($result));

die();
	
?>