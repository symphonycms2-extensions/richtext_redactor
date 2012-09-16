<?php

	Class extension_richtext_redactor extends Extension{
		
		public function install() {
			return General::realiseDirectory(WORKSPACE . '/redactor');
		}


		public function getSubscribedDelegates(){
			return array(
				array(
					'page'		=> '/backend/',
					'delegate'	=> 'InitaliseAdminPageHead',
					'callback'	=> 'initaliseAdminPageHead'
				)
			);
		}

		
		public function initaliseAdminPageHead($context) {
			$page = Administration::instance()->Page;
			
			// only on publish pages
			if(!$page instanceOf contentPublish) return;
			
			// which are showing new/edit form
			$callback = Administration::instance()->getPageCallback();
			if(!in_array($callback['context']['page'], array('new', 'edit'))) return;
			
			Administration::instance()->Page->addStylesheetToHead(URL . '/extensions/richtext_redactor/lib/redactor.css', 'screen', 200);
			Administration::instance()->Page->addScriptToHead(URL . '/extensions/richtext_redactor/lib/jquery.redactor.js', 200);
			Administration::instance()->Page->addScriptToHead(URL . '/extensions/richtext_redactor/lib/redactor.min.js', 201);
			Administration::instance()->Page->addScriptToHead(URL . '/extensions/richtext_redactor/assets/redactor.publish.js', 202);
		}
		
	}