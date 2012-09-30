<?php

	Class extension_richtext_redactor extends Extension{
		
		private $buttons = array('html', 'formatting', 'bold', 'italic', 'deleted', 'unorderedlist', 'orderedlist', 'outdent', 'indent', 'image', 'video', 'file', 'table', 'link', 'fontcolor', 'backcolor', 'alignleft', 'aligncenter', 'alignright', 'justify', 'horizontalrule');
		private $airbuttons = array('formatting', 'bold', 'italic', 'deleted', 'unorderedlist', 'orderedlist', 'outdent', 'indent', 'fontcolor', 'backcolor');
		private $allowedtags = array("code", "span", "div", "label", "a", "br", "p", "b", "i", "del", "strike", "u", "img", "video", "audio", "iframe", "object", "embed", "param", "blockquote",  "mark", "cite", "small", "ul", "ol", "li", "hr", "dl", "dt", "dd", "sup", "sub", "big", "pre", "code", "figure", "figcaption", "strong", "em", "table", "tr", "td", "th", "tbody", "thead", "tfoot", "h1", "h2", "h3", "h4", "h5", "h6");
		private $languages = array(
			"English" => "en",
			"Albanian" => "sq",
			"Arabic" => "ar",
			"Argentinian Spanish" => "es_ar",
			"Belorussian" => "by",
			"Bosnian" => "ba",
			"Brazilian Portuguese" => "pt_br",
			"Bulgarian" => "bg",
			"Catalan" => "ca",
			"Chinese Simplified" => "zh_cn",
			"Chinese Traditional" => "zh_tw",
			"Croatian" => "hr",
			"Czech" => "cs",
			"Danish" => "da",
			"Dutch" => "nl",
			"Esperanto" => "eo",
			"Finnish" => "fi",
			"French" => "fr",
			"German" => "de",
			"Greek" => "el",
			"Hungarian" => "hu",
			"Indonesian" => "id",
			"Italian" => "it",
			"Japanese" => "ja",
			"Korean" => "ko",
			"Latvian" => "lv",
			"Lithuanian" => "lt",
			"Norwegian" => "no_NB",
			"Persian" => "fa",
			"Polish" => "pl",
			"Portuguese" => "pt_pt",
			"Romanian" => "ro",
			"Russian" => "ru",
			"Serbian Cyrillic" => "sr-cir",
			"Serbian Latin" => "sr-lat",
			"Slovak" => "sk",
			"Spanish" => "es",
			"Swedish" => "sv",
			"Turkish" => "tr",
			"Ukrainian" => "ua",
			"Vietnamese" => "vi"
		);
		
		public function install() {

			Symphony::Configuration()->set('lang', 'en', 'redactor');
			Symphony::Configuration()->set('direction_ltr', 'yes', 'redactor');
			Symphony::Configuration()->set('enable_toolbar', 'yes', 'redactor');
			Symphony::Configuration()->set('enable_source', 'yes', 'redactor');
			Symphony::Configuration()->set('enable_focus', 'no', 'redactor');
			Symphony::Configuration()->set('enable_shortcuts', 'yes', 'redactor');
			Symphony::Configuration()->set('enable_autoresizing', 'yes', 'redactor');
			Symphony::Configuration()->set('enable_cleanup', 'yes', 'redactor');
			Symphony::Configuration()->set('enable_fixed', 'no', 'redactor');
			Symphony::Configuration()->set('enable_fixedbox', 'no', 'redactor');
			Symphony::Configuration()->set('enable_paragraphy', 'yes', 'redactor');
			Symphony::Configuration()->set('enable_convertlinks', 'yes', 'redactor');
			Symphony::Configuration()->set('enable_convertdivs', 'yes', 'redactor');
			Symphony::Configuration()->set('enable_fileupload', 'no', 'redactor');
			Symphony::Configuration()->set('enable_imageupload', 'yes', 'redactor');
			Symphony::Configuration()->set('enable_overlay', 'yes', 'redactor');
			Symphony::Configuration()->set('enable_observeimages', 'yes', 'redactor');
			Symphony::Configuration()->set('enable_airmode', 'no', 'redactor');
			Symphony::Configuration()->set('enable_wym', 'no', 'redactor');
			Symphony::Configuration()->set('enable_mobile', 'yes', 'redactor');
			Symphony::Configuration()->set('buttons', implode($this->buttons, ','), 'redactor');
			Symphony::Configuration()->set('airbuttons', implode($this->airbuttons, ','), 'redactor');
			Symphony::Configuration()->set('allowedtags', implode($this->allowedtags, ','), 'redactor');
			Symphony::Configuration()->set('filepath', WORKSPACE . '/redactor/files', 'redactor');
			Symphony::Configuration()->set('imagepath', WORKSPACE . '/redactor/images', 'redactor');
			Symphony::Configuration()->write();

			return (General::realiseDirectory(Symphony::Configuration()->get('filepath', 'redactor')) &&
					General::realiseDirectory(Symphony::Configuration()->get('imagepath', 'redactor')));
		}

    	/*-------------------------------------------------------------------------
    		Delegate
    	-------------------------------------------------------------------------*/

		public function getSubscribedDelegates(){
			return array(
				array(
					'page'		=> '/backend/',
					'delegate'	=> 'InitaliseAdminPageHead',
					'callback'	=> 'initaliseAdminPageHead'
				),
				array(
					'page' => '/system/preferences/',
					'delegate' => 'AddCustomPreferenceFieldsets',
					'callback' => 'appendPreferences'
				),
				array(
					'page' => '/system/preferences/',
					'delegate' => 'Save',
					'callback' => 'savePreferences'
				)
			);
		}

		/*-------------------------------------------------------------------------
			Delegated functions
		-------------------------------------------------------------------------*/	
		
		public function initaliseAdminPageHead($context) {
			$page = Administration::instance()->Page;
			
			// only on publish pages
			if(!$page instanceOf contentPublish) return;
			
			// which are showing new/edit form
			$callback = Administration::instance()->getPageCallback();
			if(!in_array($callback['context']['page'], array('new', 'edit'))) return;
			
			Administration::instance()->Page->addStylesheetToHead(URL . '/extensions/richtext_redactor/lib/redactor.css', 'screen', 200);
			Administration::instance()->Page->addStylesheetToHead(URL . '/extensions/richtext_redactor/assets/redactor.css', 'screen', 210);
			Administration::instance()->Page->addScriptToHead(URL . '/extensions/richtext_redactor/lib/jquery.redactor.js', 200);
			Administration::instance()->Page->addScriptToHead(URL . '/extensions/richtext_redactor/lib/redactor.min.js', 201);
			Administration::instance()->Page->addScriptToHead(URL . '/symphony/extension/richtext_redactor/redactor/', 202);

			$code = Symphony::Configuration()->get('lang','redactor');
			if(isset($code) && $code != 'en') {
				Administration::instance()->Page->addScriptToHead(URL . '/extensions/richtext_redactor/lib/lang/' . $code . '.js', 203);
			}
		}
		
		public function appendPreferences($context) {
			$group = new XMLElement('fieldset');
			$group->setAttribute('class', 'redactor settings');
			$group->appendChild(new XMLElement('legend', 'Redactor RichText Editor'));

    		$columns = new XMLElement('div',null, array("class" => "group"));

    			// First Column
    			$div = new XMLElement('div',null, array("class" => "options"));

					$label = Widget::Label();
						$input = Widget::Input('settings[redactor][enable_focus]', 'yes', 'checkbox');
						if(Symphony::Configuration()->get('enable_focus', 'redactor') == 'yes') $input->setAttribute('checked', 'checked');
						$label->setValue($input->generate() . ' ' . __('Set focus to Redactor editor on page load'));
						$div->appendChild($label);

					$label = Widget::Label();
						$input = Widget::Input('settings[redactor][enable_toolbar]', 'yes', 'checkbox');
						if(Symphony::Configuration()->get('enable_toolbar', 'redactor') == 'yes') $input->setAttribute('checked', 'checked');
						$label->setValue($input->generate() . ' ' . __('Display the Toolbar in the Redactor editor'));
						$div->appendChild($label);

					$label = Widget::Label();
						$input = Widget::Input('settings[redactor][enable_source]', 'yes', 'checkbox');
						if(Symphony::Configuration()->get('enable_source', 'redactor') == 'yes') $input->setAttribute('checked', 'checked');
						$label->setValue($input->generate() . ' ' . __('Enable the HTML source button on the toolbar'));
						$div->appendChild($label);

					$label = Widget::Label();
						$input = Widget::Input('settings[redactor][enable_airmode]', 'yes', 'checkbox');
						if(Symphony::Configuration()->get('enable_airmode', 'redactor') == 'yes') $input->setAttribute('checked', 'checked');
						$label->setValue($input->generate() . ' ' . __('Enable air-mode'));
						$div->appendChild($label);

					$label = Widget::Label();
						$input = Widget::Input('settings[redactor][enable_wym]', 'yes', 'checkbox');
						if(Symphony::Configuration()->get('enable_wym', 'redactor') == 'yes') $input->setAttribute('checked', 'checked');
						$label->setValue($input->generate() . ' ' . __('Enable visual structure mode'));
						$div->appendChild($label);

					$label = Widget::Label();
						$input = Widget::Input('settings[redactor][enable_autoresizing]', 'yes', 'checkbox');
						if(Symphony::Configuration()->get('enable_autoresizing', 'redactor') == 'yes') $input->setAttribute('checked', 'checked');
						$label->setValue($input->generate() . ' ' . __('Enable height autoresizing'));
						$div->appendChild($label);

					$label = Widget::Label();
						$input = Widget::Input('settings[redactor][enable_observeimages]', 'yes', 'checkbox');
						if(Symphony::Configuration()->get('enable_observeimages', 'redactor') == 'yes') $input->setAttribute('checked', 'checked');
						$label->setValue($input->generate() . ' ' . __('Enable image properties dialog'));
						$div->appendChild($label);

					$label = Widget::Label();
						$input = Widget::Input('settings[redactor][enable_paragraphy]', 'yes', 'checkbox');
						if(Symphony::Configuration()->get('enable_paragraphy', 'redactor') == 'yes') $input->setAttribute('checked', 'checked');
						$label->setValue($input->generate() . ' ' . __('Enable formatting to paragraphs'));
						$div->appendChild($label);

					$label = Widget::Label();
						$input = Widget::Input('settings[redactor][enable_cleanup]', 'yes', 'checkbox');
						if(Symphony::Configuration()->get('enable_cleanup', 'redactor') == 'yes') $input->setAttribute('checked', 'checked');
						$label->setValue($input->generate() . ' ' . __('Enable text cleanup on paste'));
						$div->appendChild($label);

					$label = Widget::Label();
						$input = Widget::Input('settings[redactor][enable_convertlinks]', 'yes', 'checkbox');
						if(Symphony::Configuration()->get('enable_convertlinks', 'redactor') == 'yes') $input->setAttribute('checked', 'checked');
						$label->setValue($input->generate() . ' ' . __('Automatically replace URLs with hyperlinks'));
						$div->appendChild($label);

					$label = Widget::Label();
						$input = Widget::Input('settings[redactor][enable_convertdivs]', 'yes', 'checkbox');
						if(Symphony::Configuration()->get('enable_convertdivs', 'redactor') == 'yes') $input->setAttribute('checked', 'checked');
						$label->setValue($input->generate() . ' ' . __('Automatically replace divs to paragraphs'));
						$div->appendChild($label);

					$label = Widget::Label();
						$input = Widget::Input('settings[redactor][enable_fileupload]', 'yes', 'checkbox');
						if(Symphony::Configuration()->get('enable_fileupload', 'redactor') == 'yes') $input->setAttribute('checked', 'checked');
						$label->setValue($input->generate() . ' ' . __('Enable file upload from the Redactor editor'));
						$div->appendChild($label);

					$label = Widget::Label();
						$input = Widget::Input('settings[redactor][enable_imageupload]', 'yes', 'checkbox');
						if(Symphony::Configuration()->get('enable_imageupload', 'redactor') == 'yes') $input->setAttribute('checked', 'checked');
						$label->setValue($input->generate() . ' ' . __('Enable image upload from the Redactor editor'));
						$div->appendChild($label);

					$label = Widget::Label();
						$input = Widget::Input('settings[redactor][enable_shortcuts]', 'yes', 'checkbox');
						if(Symphony::Configuration()->get('enable_shortcuts', 'redactor') == 'yes') $input->setAttribute('checked', 'checked');
						$label->setValue($input->generate() . ' ' . __('Enable keydown / keyup shortcuts functionality'));
						$div->appendChild($label);

					$label = Widget::Label();
						$input = Widget::Input('settings[redactor][enable_mobile]', 'yes', 'checkbox');
						if(Symphony::Configuration()->get('enable_mobile', 'redactor') == 'yes') $input->setAttribute('checked', 'checked');
						$label->setValue($input->generate() . ' ' . __('Enable the editor for mobile devices'));
						$div->appendChild($label);

					$label = Widget::Label();
						$input = Widget::Input('settings[redactor][enable_overlay]', 'yes', 'checkbox');
						if(Symphony::Configuration()->get('enable_overlay', 'redactor') == 'yes') $input->setAttribute('checked', 'checked');
						$label->setValue($input->generate() . ' ' . __('Enable overlay for modal Redactor windows'));
						$div->appendChild($label);

					$label = Widget::Label();
						$input = Widget::Input('settings[redactor][enable_fixed]', 'yes', 'checkbox');
						if(Symphony::Configuration()->get('enable_fixed', 'redactor') == 'yes') $input->setAttribute('checked', 'checked');
						$label->setValue($input->generate() . ' ' . __('The toolbar will remain at the top of the browser window (fixed) at all times'));
						$div->appendChild($label);

					$label = Widget::Label();
						$input = Widget::Input('settings[redactor][enable_fixedbox]', 'yes', 'checkbox');
						if(Symphony::Configuration()->get('enable_fixedbox', 'redactor') == 'yes') $input->setAttribute('checked', 'checked');
						$label->setValue($input->generate() . ' ' . __('This option makes a fixed toolbar to the width of the editor (in fixed mode)'));
						$div->appendChild($label);

					$label = Widget::Label();
						$input = Widget::Input('settings[redactor][direction_ltr]', 'yes', 'checkbox');
						if(Symphony::Configuration()->get('direction_ltr', 'redactor') == 'yes') $input->setAttribute('checked', 'checked');
						$label->setValue($input->generate() . ' ' . __('Text direction is Left-To-Right (ltr). Uncheck for Right-To-Left (rtl)'));
						$div->appendChild($label);

				$columns->appendChild($div);


				// Second Column
    			$div = new XMLElement('div');
		    		// Language options
    				$options = array();
    				$language = Symphony::Configuration()->get('lang','redactor');
    				foreach($this->languages as $lang=>$code)
    					$options[$code] = array($code, (($code == $language) ? true : false) ,__($lang));

		    		$label = Widget::Label();
							$input = Widget::Select('settings[redactor][lang]', $options);
		                    $label->setValue(__('Language') . $input->generate());
		                    $div->appendChild($label);

		    		// Toolbar buttons
		            $options = array();
		            $buttons = Symphony::Configuration()->get('buttons','redactor');
		            $buttons = explode(',', $buttons);
    				foreach($this->buttons as $item)
    					$options[$item] = array($item, (in_array($item, $buttons) ? true : false), __(ucfirst($item)));

		    		$label = Widget::Label();
							$input = Widget::Select('settings[redactor][buttons][]', $options, array('multiple' => 'multiple'));
		                    $label->setValue(__('Toolbar Buttons') . $input->generate());
		                    $div->appendChild($label);

		    		// Air buttons
		            $options = array();
		            $airbuttons = Symphony::Configuration()->get('airbuttons','redactor');
		            $airbuttons = explode(',', $airbuttons);
    				foreach($this->buttons as $item)
    					$options[$item] = array($item, (in_array($item, $airbuttons) ? true : false), __(ucfirst($item)));

		    		$label = Widget::Label();
							$input = Widget::Select('settings[redactor][airbuttons][]', $options, array('multiple' => 'multiple'));
		                    $label->setValue(__('Air Buttons (in air mode)') . $input->generate());
		                    $div->appendChild($label);

		    		// Allowed Tags
		            $options = array();
		            $allowedtags = Symphony::Configuration()->get('allowedtags','redactor');
		            $allowedtags = explode(',', $allowedtags);
    				$options = array();
    				foreach($this->allowedtags as $item)
    					$options[$item] = array($item, (in_array($item, $allowedtags) ? true : false), __(ucfirst($item)));

		    		$label = Widget::Label();
							$input = Widget::Select('settings[redactor][allowedtags][]', $options, array('multiple' => 'multiple'));
		                    $label->setValue(__('Allowed Tags') . $input->generate());
		                    $div->appendChild($label);

		            // File Upload location
		    		$label = Widget::Label();
		                    $input = Widget::Input('settings[redactor][filepath]', Symphony::Configuration()->get('filepath', 'redactor'), 'text');
		                    $label->setValue(__('File upload location') . $input->generate());
		                    $div->appendChild($label);
		    		$div->appendChild(new XMLElement('span',"The directory where uploaded files are stored (default: WORKSPACE/redactor/files)", array("class"=>"help")));

		            // File Upload location
		    		$label = Widget::Label();
		                    $input = Widget::Input('settings[redactor][imagepath]', Symphony::Configuration()->get('imagepath', 'redactor'), 'text');
		                    $label->setValue(__('Image upload location') . $input->generate());
		                    $div->appendChild($label);
		    		$div->appendChild(new XMLElement('span',"The directory where uploaded images are stored (default: WORKSPACE/redactor/images)", array("class"=>"help")));


				$columns->appendChild($div);
			$group->appendChild($columns);

			$context['wrapper']->appendChild($group);
		}

		public function savePreferences($context) {

			if(!isset($context['settings']['redactor']['enable_airmode']))
				$context['settings']['redactor']['enable_airmode'] = 'no';

			if(!isset($context['settings']['redactor']['enable_wym']))
				$context['settings']['redactor']['enable_wym'] = 'no';

			if(!isset($context['settings']['redactor']['enable_paragraphy']))
				$context['settings']['redactor']['enable_paragraphy'] = 'no';

			if(!isset($context['settings']['redactor']['enable_cleanup']))
				$context['settings']['redactor']['enable_cleanup'] = 'no';

			if(!isset($context['settings']['redactor']['enable_fileupload']))
				$context['settings']['redactor']['enable_fileupload'] = 'no';

			if(!isset($context['settings']['redactor']['enable_imageupload']))
				$context['settings']['redactor']['enable_imageupload'] = 'no';

			if(!isset($context['settings']['redactor']['enable_shortcuts']))
				$context['settings']['redactor']['enable_shortcuts'] = 'no';

			if(!isset($context['settings']['redactor']['enable_mobile']))
				$context['settings']['redactor']['enable_mobile'] = 'no';

			if(!isset($context['settings']['redactor']['enable_autoresizing']))
				$context['settings']['redactor']['enable_autoresizing'] = 'no';

			if(!isset($context['settings']['redactor']['enable_toolbar']))
				$context['settings']['redactor']['enable_toolbar'] = 'no';

			if(!isset($context['settings']['redactor']['enable_source']))
				$context['settings']['redactor']['enable_source'] = 'no';

			if(!isset($context['settings']['redactor']['enable_fixed']))
				$context['settings']['redactor']['enable_fixed'] = 'no';

			if(!isset($context['settings']['redactor']['enable_fixedbox']))
				$context['settings']['redactor']['enable_fixedbox'] = 'no';

			if(!isset($context['settings']['redactor']['enable_convertlinks']))
				$context['settings']['redactor']['enable_convertlinks'] = 'no';

			if(!isset($context['settings']['redactor']['enable_convertdivs']))
				$context['settings']['redactor']['enable_convertdivs'] = 'no';

			if(!isset($context['settings']['redactor']['enable_overlay']))
				$context['settings']['redactor']['enable_overlay'] = 'no';

			if(!isset($context['settings']['redactor']['enable_focus']))
				$context['settings']['redactor']['enable_focus'] = 'no';

			if(!isset($context['settings']['redactor']['direction_ltr']))
				$context['settings']['redactor']['direction_ltr'] = 'no';

			if(!isset($context['settings']['redactor']['enable_observeimages']))
				$context['settings']['redactor']['enable_observeimages'] = 'no';

			$buttons = $context['settings']['redactor']['buttons'];
			if(isset($buttons)) {
				$context['settings']['redactor']['buttons'] = implode(',', $buttons);
			} else {
				$context['settings']['redactor']['buttons'] = '';
			}

			$airbuttons = $context['settings']['redactor']['airbuttons'];
			if(isset($airbuttons)) {
				$context['settings']['redactor']['airbuttons'] = implode(',', $airbuttons);
			} else {
				$context['settings']['redactor']['airbuttons'] = '';
			}

			$allowedTags = $context['settings']['redactor']['allowedtags'];
			if(isset($allowedTags)) {
				$context['settings']['redactor']['allowedtags'] = implode(',', $allowedTags);
			} else {
				$context['settings']['redactor']['allowedtags'] = '';
			}
		}
	}