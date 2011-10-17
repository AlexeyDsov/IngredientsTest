/***************************************************************************
 *   Copyright (C) 2011 by Alexey Denisov                                  *
 *   alexeydsov@gmail.com                                                  *
 ***************************************************************************/

var Application = Application || {};

Application.submit = function(form) {
	var filter = ':text[value=\'\'],:checkbox[checked=false],:radio[checked=false],:submit';
	$(form.elements).filter(filter).attr('disabled', 'true');
	form = $(form);
	var params = form.serializeArray();
	var url = form.attr('action');
	var method = (form.attr('method') == 'post') ? 'POST' : 'GET';
	var options = {container: '#pjaxArea', type: method};

	if (method != 'POST') {
		options.url = (url + (/\?/.test(url) ? "&" : "?") + $.param(params));
	} else {
		options.url = url;
		options.data = params;
	}

	$.pjax(options);
	return false;
}

Application.goUrl = function(url) {
	var linkId = 'link' + DialogController.generateId();
	var html = '<a class="js-pjax" href="' + url + '" id="' + linkId + '" style="display: none;">redirect</a>';
	$('#pjaxArea').append(html);
	$('#' + linkId).click();
}

Application.initDatepicker = function () {
	var inputs = $('._hasDatepicker');
	inputs.removeClass('_hasDatepicker').addClass('_hasDatepickerInit');
	inputs.datepicker({ buttonImage: '/images/datepicker.gif', dateFormat: 'yy-mm-dd', buttonText: 'Choose' });

	var inputs = $('._hasDatepickerTime');
	inputs.removeClass('_hasDatepicker').addClass('_hasDatepickerInit');
	inputs.datepicker({ buttonImage: '/images/datepicker.gif', dateFormat: 'yy-mm-dd 00:00:00', buttonText: 'Choose' });
}