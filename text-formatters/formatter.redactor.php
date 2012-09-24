<?php

	Class formatterredactor extends TextFormatter{
		
		function about(){
			return array(
						 'name' => 'Rich Text (Redactor)',
						 'version' => '0.9.0',
						 'release-date' => '2012-09-16',
						 'author' => array('name' => 'Remie Bolte',
										   'website' => 'https://github.com/remie')
				 		);
		}
		
		function run($string){
			return htmlentities($string);
		}
	}
?>