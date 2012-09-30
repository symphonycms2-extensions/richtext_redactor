<?php

	header('Content-Type: application/javascript');

	function showButtons($buttons) {
		$result = "";
		if(in_array('html', $buttons)) { $result .= "'html', '|',"; }
		if(in_array('formatting', $buttons)) { $result .="'formatting', '|',"; }

		$sep=false;
		if(in_array('bold', $buttons)) { $result .="'bold',"; $sep=true; }
		if(in_array('italic', $buttons)) { $result .="'italic',"; $sep=true; }
		if(in_array('deleted', $buttons)) { $result .="'deleted',"; $sep=true; }
		if($sep) { $result .="'|',"; $sep=false; }

		$sep=false;
		if(in_array('unorderedlist', $buttons)) { $result .="'unorderedlist',";$sep=true; }
		if(in_array('orderedlist', $buttons)) { $result .="'orderedlist',";$sep=true; }
		if(in_array('outdent', $buttons)) { $result .="'outdent',";$sep=true; }
		if(in_array('indent', $buttons)) { $result .="'indent',";$sep=true; }
		if($sep) { $result .="'|',"; $sep=false; }

		$sep=false;
		if(Symphony::Configuration()->get('enable_imageupload','redactor') == 'yes') {
			if(in_array('image', $buttons)) { $result .="'image',";$sep=true; }
		}
		if(in_array('video', $buttons)) { $result .="'video',";$sep=true; }
		if(Symphony::Configuration()->get('enable_fileupload','redactor') == 'yes') {
			if(in_array('file', $buttons)) { $result .="'file',";$sep=true; }
		}
		if(in_array('table', $buttons)) { $result .="'table',";$sep=true; }
		if(in_array('link', $buttons)) { $result .="'link',";$sep=true; }
		if($sep) { $result .="'|',"; $sep=false; }

		$sep=false;
		if(in_array('fontcolor', $buttons)) { $result .="'fontcolor',";$sep=true; }
		if(in_array('backcolor', $buttons)) { $result .="'backcolor',";$sep=true; }
		if($sep) { $result .="'|',"; $sep=false; }

		$sep=false;
		if(in_array('alignleft', $buttons)) { $result .="'alignleft',";$sep=true; }
		if(in_array('aligncenter', $buttons)) { $result .="'aligncenter',";$sep=true; }
		if(in_array('alignright', $buttons)) { $result .="'alignright',";$sep=true; }
		if(in_array('justify', $buttons)) { $result .="'justify',";$sep=true; }
		if($sep) { $result .="'|',"; $sep=false; }

		if(in_array('horizontalrule', $buttons)) { $result .="'horizontalrule', '|'"; }
		return $result;
	}
?>

jQuery(document).ready(function() {

	var options = {

<?php

		$code = Symphony::Configuration()->get('lang','redactor');
		if(!isset($code)) { $code = 'en'; }
		echo "lang: '" . $code . "',";

		if(Symphony::Configuration()->get('enable_toolbar','redactor') == 'no') { echo "toolbar: false,"; }

		if(Symphony::Configuration()->get('enable_wym','redactor') == 'yes') { echo "wym: true,"; }
		else { echo "wym: false,"; }

		if(Symphony::Configuration()->get('enable_airmode','redactor') == 'yes') { echo "air: true,"; }
		else { echo "air: false,"; }

		if(Symphony::Configuration()->get('enable_paragraphy','redactor') == 'yes') { echo "paragraphy: true,"; }
		else { echo "paragraphy: false,"; }

		if(Symphony::Configuration()->get('enable_cleanup','redactor') == 'yes') { echo "cleanup: true,"; }
		else { echo "cleanup: false,"; }

		if(Symphony::Configuration()->get('enable_cleanup','redactor') == 'yes') { echo "cleanup: true,"; }
		else { echo "cleanup: false,"; }

		if(Symphony::Configuration()->get('enable_shortcuts','redactor') == 'yes') { echo "shortcuts: true,"; }
		else { echo "shortcuts: false,"; }

		if(Symphony::Configuration()->get('enable_mobile','redactor') == 'yes') { echo "mobile: true,"; }
		else { echo "mobile: false,"; }

		if(Symphony::Configuration()->get('enable_autoresizing','redactor') == 'yes') { echo "autoresize: true,"; }
		else { echo "autoresize: false,"; }

		if(Symphony::Configuration()->get('enable_source','redactor') == 'yes') { echo "source: true,"; }
		else { echo "source: false,"; }

		if(Symphony::Configuration()->get('enable_fixedbox','redactor') == 'yes') { echo "fixedBox: true,"; }
		else { echo "fixedBox: false,"; }

		if(Symphony::Configuration()->get('enable_convertlinks','redactor') == 'yes') { echo "convertLinks: true,"; }
		else { echo "convertLinks: false,"; }

		if(Symphony::Configuration()->get('enable_convertdivs','redactor') == 'yes') { echo "convertDivs: true,"; }
		else { echo "convertDivs: false,"; }

		if(Symphony::Configuration()->get('enable_overlay','redactor') == 'yes') { echo "overlay: true,"; }
		else { echo "overlay: false,"; }

		if(Symphony::Configuration()->get('enable_focus','redactor') == 'yes') { echo "focus: true,"; }
		else { echo "focus: false,"; }

		if(Symphony::Configuration()->get('direction_ltr','redactor') == 'yes') { echo "direction: 'ltr',"; }
		else { echo "direction: 'rtl',"; }

		if(Symphony::Configuration()->get('enable_observeimages','redactor') == 'yes') { echo "observeImages: true,"; }
		else { echo "observeImages: false,"; }

		if(Symphony::Configuration()->get('enable_airmode','redactor') == 'yes') { 
	        $buttons = explode(',', Symphony::Configuration()->get('airbuttons','redactor'));
			echo "airButtons: [" . showButtons($buttons) . "],";
		} else {
	        $buttons = explode(',', Symphony::Configuration()->get('buttons','redactor'));
			echo "buttons: [" . showButtons($buttons) . "],";
		}

		if(Symphony::Configuration()->get('enable_source','redactor') == 'yes') {
			$tags = Symphony::Configuration()->get('allowedtags','redactor');
			if(isset($tags)) {
				$tags = explode(',', $tags);

				echo "allowedTags: [";
				foreach($tags as $tag) { echo '"' . $tag . '",';}
				echo "],";
			}
		}

		if(Symphony::Configuration()->get('enable_fileupload','redactor') == 'yes') { 
			echo "fileUpload: Symphony.Context.get('root') + '/symphony/extension/richtext_redactor/fileupload/',"; 
		}

		if(Symphony::Configuration()->get('enable_imageupload','redactor') == 'yes') { 
			echo "imageUpload: Symphony.Context.get('root') + '/symphony/extension/richtext_redactor/imageupload/',"; 
		}
?>
		imageGetJson: Symphony.Context.get('root') + '/symphony/extension/richtext_redactor/images/'
	}

	jQuery('textarea.redactor').redactor(options);

});

<?php

die();

?>