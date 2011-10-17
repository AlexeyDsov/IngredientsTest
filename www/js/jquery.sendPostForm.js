/* $Id: sendPostForm.js 1371 2010-01-15 15:38:52Z stalkerxey $ */

jQuery.extend({
	sendPostForm: function( form, element, callback ) {
		var url = form.attr('action');
		var params = form.serializeArray();
		element.load(url, params, callback);
		return true;
	}
});


