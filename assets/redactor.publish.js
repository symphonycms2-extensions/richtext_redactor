
jQuery(document).ready(function() {

	jQuery('textarea.redactor').redactor({
		imageUpload: Symphony.Context.get('root') + '/symphony/extension/richtext_redactor/upload/',
		imageGetJson: Symphony.Context.get('root') + '/symphony/extension/richtext_redactor/images/'
	});

});
