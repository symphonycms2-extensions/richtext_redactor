<?php

	Class formatterredactor extends TextFormatter{
		
		function about() {
			return array(
						 'name' => 'Rich Text (Redactor)',
						 'version' => '1.0.0',
						 'release-date' => '2012-09-28',
						 'author' => array('name' => 'Remie Bolte',
										   'website' => 'https://github.com/remie')
				 		);
		}
		
		function run($string) {
			$string = str_replace('&nbsp;', '&#160;', $string);
			$string = preg_replace('/<(img|hr|br|link|meta|input|param|base|area|meta)((\s+\w+(\s*=\s*("[^"]*"|\'[^\']*\'|\w+))?)*)\s*>/', '<$1$2/>', $string);
	        return $string;
		}
	}
?>