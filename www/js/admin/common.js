Function.prototype.scope = function(obj, args, appendArgs){
	var method = this;
	return function() {
		var callArgs = args || arguments;
		if(appendArgs === true){
			callArgs = Array.prototype.slice.call(arguments, 0);
			callArgs = callArgs.concat(args);
		}else if(typeof appendArgs == "number"){
			callArgs = Array.prototype.slice.call(arguments, 0); // copy arguments first
			var applyArgs = [appendArgs, 0].concat(args); // create method call params
			Array.prototype.splice.apply(callArgs, applyArgs); // splice them in
		}
		return method.apply(obj || window, callArgs);
	};
};

Function.prototype.defer = function(millis, obj, args, appendArgs){
	var fn = this.scope(obj, args, appendArgs);
	if(millis){
		return setTimeout(fn, millis);
	}
	fn();
	return 0;
};

var showMessage = function(msg, cls) {
	$('#message').html(msg).parent().show().addClass(cls);
	$(document.body).one('click', function() {
		hideMessage.defer(5000);
	});
};

var hideMessage = function() {
	$('#message').parent().fadeOut('slow');
};

$.fn.activateRow = function() {
	return this.each(function() {
		if (!$(this).is('tr')) return;
		$(this).siblings().removeClass('active').end().addClass('active');
	});
};

$.fn.expandTableRow = function(tpl, data) {
	var template = tpl, items = data;
	return this.each(function(index, row) {
		var __data = ($.isArray(items))?items[index]:items;
		var content = $($.trim(tmpl(template, __data)));
		$(this)
			.siblings().remove('tr.extended').end()
			.after(
				$('<td>')
					.attr('colspan', $(this).children('td').length)
					.append(content)
					.wrap('<tr class="extended"></tr>')
					.parent()
			);
		$(this).find('.button-plus').removeClass('button-plus').addClass('button-minus');
	});
};

$.fn.collapseTableRow = function() {
	return this.each(function(index, row) {
		$(this).next('tr.extended').remove();
		$(this).find('.button-minus').removeClass('button-minus').addClass('button-plus').end();
	});
};

$.fn.showEmptyGridMessage = function(text) {
	var msgText = text || 'No items were found';
//	var msgText = text || 'No items were found.You can add them <a href="/admin/products/add/">here</a>'
	return this.each(function (index, el) {
		var emptyGridAlert =
			$('<div class="emptyGridAlert"><span>' + msgText + '</span></div>')
			.appendTo($(this).empty());
		var onResizeHandler = function(e) {
			var newH = $('.footer-wrapper').offset().top - emptyGridAlert.offset().top - 15;
			emptyGridAlert.height(newH).children('span').css('line-height', newH + 'px');
		};
		$(window).bind('resize', onResizeHandler);
		onResizeHandler();
	});
};

Date.prototype.getMyCoolDate = Date.prototype.getMyCoolDate || function (arg){
	var str;
	if ( arg == 'date' ) {
		return this.getDate().toString().pad(2,'0') + '/' + (this.getMonth() + 1).toString().pad(2,'0') +'/'+ this.getFullYear();
	} else if ( arg == 'time' ) {
		return this.getHours() +':'+ this.getMinutes().toString().pad(2,'0') +':'+ this.getSeconds().toString().pad(2,'0');
	} else {
		return this.getDate().toString().pad(2,'0') + '/' + (this.getMonth() + 1).toString().pad(2,'0') +'/'+ this.getFullYear() + ' ' + this.getHours() +':'+ this.getMinutes().toString().pad(2,'0') +':'+ this.getSeconds().toString().pad(2,'0');
	}
};

String.prototype.pad = String.prototype.pad || function (count,letter) {
	var strCount = this.length, s = this;
	if ( strCount < count ) {
		for ( var i=0; i < (count-strCount); i++ ) {
			s = letter.concat(this);
		}
	}
	return s;
};

String.prototype.capitalize = function() {
	return this.substr(0, 1).toUpperCase() + this.substr(1);
};

String.prototype.wikify = function() {
	return this.replace(/[^a-zA-Z0-9а-яА-Я]+/ig, '_');
};

$(function() {
	$('input.select-all').listchecks();
	//Initialize AJAX with standard success/error handlers
	$.ajaxSetup({
		success : function(data) {
			if (data.error) {
				showMessage(data.error, 'error');
				return;
			}
			if (typeof this.onSuccess == 'function') {
				this.onSuccess.call(this, data);
			}
		},
		error : function(xhr, status, e) {
			showMessage('Error during request. Please refresh page and try again.', 'error');
			console.error(e.message);
			if (typeof this.onError == 'function') {
				this.onError.call(this, data);
			}
		}
	});

	$('input.key-source').change(function(e) {
		var $target = $('input.key-target');
		if ($target.val() == '') {
			$target.val($(this).val().wikify());
		}
	});

	$('input.key-target').change(function(e) {
		$(this).val($(this).val().wikify());
	});

	$('input.key-target').parents('form').submit(function(e) {
		$('input.key-source, input.key-target').change();
	});
});
