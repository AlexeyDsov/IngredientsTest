/***************************************************************************
 *   Copyright (C) 2011 by Alexey Denisov                                  *
 *   alexeydsov@gmail.com                                                  *
 ***************************************************************************/

var DialogController = DialogController || {};

DialogController.currentDialog = null;

DialogController.spawnByLink = function (event, link, dialogId) {
	if ( event.which > 1 || event.metaKey ) {
		return true
	}
	var url = $(link).attr('href');
	this.spawnByUrl(url, dialogId, link);
	return false
};

DialogController.spawnByUrl = function (url, dialogId, initiateObject) {
	var self = this;
	$.ajax({
		url: url,
		type: 'GET',
		dataType: "html",
		cache: false,
		// Complete callback (responseText is used internally)
		complete: function(jqXHR, status, responseText) {
			// Store the response as specified by the jqXHR object
			responseText = jqXHR.responseText;
			// If successful, inject the HTML into all the matched elements
			if (jqXHR.isResolved()) {
				jqXHR.done(function(r) {
					responseText = r;
				});
				var dialog = self.markCurrent(self.getDialog(dialogId));
				if (typeof(initiateObject) !== 'undefined') {
					initiateObject = $(initiateObject);
					dialog.dialog('option', "position", [initiateObject.offset().left, initiateObject.offset().top]);
				}
				dialog.html($("<div>").append(responseText));
				dialog.dialog("open").dialog( "moveToTop" );
				$('body').trigger('dialog.loaded');
			} else {
				//If not success - making window with error msg
				var dialog = self.markCurrent(self.spawn());
				dialog.html($("<div>").append("Loading page error..."));
				if (typeof(initiateObject) !== 'undefined') {
					initiateObject = $(initiateObject);
					dialog.dialog('option', "position", [initiateObject.offset().left, initiateObject.offset().top]);
				}
				dialog.dialog('option', {dialogClass: 'ui-state-error', title: 'Error'});
				dialog.dialog('open');
			}
		}
	});
	return false;
};

DialogController.markCurrent = function (dialog) {
	this.currentDialog = dialog;
	return dialog;
};

DialogController.getDialog = function (dialogId) {
	var dialog = $('#' + dialogId);
	if (dialog.length != 1) {
		dialog = this.spawn(dialogId);
	}
	return dialog;
};

DialogController.spawn = function(dialogId) {
	if (typeof(dialogId) == 'undefined') {
		dialogId = this.generateId();
	}
	var html = "<div id=" + dialogId + "><!-- --></div>";
	$('body').append(html);
	var dialog = $('#' + dialogId).dialog({
		disabled: true,
		autoOpen: false
	});
	return dialog;
}

DialogController.generateId = function () {
	var min = 10000;
	var max = 99999;
	var time = Math.floor(new Date().getTime() / 1000);
	var rand = Math.floor(Math.random() * (max - min + 1)) + min;
	return "" + time + rand;
}