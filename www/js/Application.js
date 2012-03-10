/***************************************************************************
 *   Copyright (C) 2011 by Alexey Denisov                                  *
 *   alexeydsov@gmail.com                                                  *
 ***************************************************************************/

var Application = Application || {};

Application.ternaryRadio = null;
Application.emptyInputPattern = ':text[value=\'\'],:checkbox[checked=false],:radio[checked=false]';


Application.switchFilters = function(button, form) {
	if ($(button).val() == 'Hide') {
		Application.hideFilters(button, form);
	} else {
		Application.showFilters(button, form);
	}
}

Application.hideFilters = function(button, form) {
	var filter = function() {
		if ($(this).hasClass('error')) {
			return false;
		}
		var allEmpty = true;
		$(this).find(':input').each(function() {
			if ($(this).filter(':checkbox').length) {
				allEmpty = allEmpty && !$(this).attr('checked');
			} else {
				allEmpty = allEmpty && $(this).val() == '';
			}
		});
		return allEmpty;
	};
	$(form).find('div._filterBlock').filter(filter).hide();
	
	var filter = function() {return $(this).find('div._filterBlock:visible').length == 0;};
	$(form)
		.find('div._propertyBlock')
		.filter(filter)
		.hide();
	
	$(button).val('Show');
}

Application.showFilters = function(button, form) {
	$(form).find('div._filterBlock,div._propertyBlock').show();
	$(button).val('Hide');
}

Application.submit = function(form) {
	$(form.elements).filter(Application.emptyInputPattern + ',:submit').attr('disabled', 'true');
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
	inputs.datepicker({buttonImage: '/images/datepicker.gif', dateFormat: 'yy-mm-dd', buttonText: 'Choose'});

	var inputs = $('._hasDatepickerTime');
	inputs.removeClass('_hasDatepicker').addClass('_hasDatepickerInit');
	inputs.datepicker({buttonImage: '/images/datepicker.gif', dateFormat: 'yy-mm-dd 00:00:00', buttonText: 'Choose'});
}

Application.init = function () {
	$('a.js-pjax').pjax('#pjaxArea');
	$('form.js-pjax').live('submit', function(event){Application.submit(this);return false;});
	Application.initDatepicker();
	$('#pjaxArea').bind('end.pjax', function() {
		Application.initDatepicker();
	});
	$('body').bind('dialog.loaded', function() {
		Application.initDatepicker();
	});
	
	$('._ternary').live('click', function(event){
		TernaryRadio.switched($(this));
	});
}

var TernaryRadio = TernaryRadio || {};

TernaryRadio.switched = function (input) {
	var val = input.val();
	if (val == '1') {
		input.parent().prev().prev().children('input').attr('checked', false);
	} else if (val == '0') {
		input.parent().prev().children('input').attr('checked', false);
	} else {
		input.parent().next().children('input').attr('checked', false);
		input.parent().next().next().children('input').attr('checked', false);
	}
}