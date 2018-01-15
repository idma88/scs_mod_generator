$(document).ready(function(){

	$('select').select2({
        minimumResultsForSearch : 15
    });

	$('.tooltipped').tooltip({
		position : 'left',
		exitDelay : 200
	});

	$('.modal').modal();

	$('.tabs').tabs();

	$('.collapsible').collapsible();

	$('.show-skin').collapsible({
		onOpenStart : function(){
			var ul = $(this)[0].$el;
			$.ajax({
				cache: false,
				dataType : 'json',
				type : 'POST',
				data : {
					'ajax' : true,
					'game' : ul.data('game'),
					'chassis' : ul.data('trailer'),
					'lang' : getCookie('lang')
				},
				beforeSend : function(){
					ul.find('.collapsible-header').append(getPreloaderHtml('tiny'));
				},
				success : function(response){
					if(response.status === 'OK'){
						var html ='<ul class="ac-list browser-default">';
						$.each(response.result, function(key, value){
							html += '<li>'+value;
							if(key.indexOf('.jpg') !== -1) html += '<img class="responsive-img" src="assets/img/trailers/'+response.chassis+'/'+key+'">';
							html += '</li>';
						});
						html += '</ul>';
						ul.find('.collapsible-body').append(html);
					}
				},
				complete : function(){
					$('.preloader-wrapper').remove();
				}
			});
		},
		onCloseEnd : function(){
			var ul = $(this)[0].$el;
			ul.find('.ac-list').remove();
		}
	});

	$('#lang-btn').click(function(){
		document.cookie = 'lang=' + $(this).data('lang');
	});

	$('select[name=chassis]').change(function(){
		$('#accessory').hide();
		$('#paint').hide();
		$('.colors').hide();
		$('#all_accessories, #all_paints').attr('checked',false);
		if($(this).val() !== ''){
			$.ajax({
				cache: false,
				dataType : 'json',
				type : 'POST',
				data : {
					'ajax' : true,
					'game' : $('input[name=target]').val(),
					'chassis' : $(this).val(),
					'lang' : getCookie('lang')
				},
				beforeSend : function(){
					$('#chassis').after(getPreloaderHtml('small'));
				},
				success : function(response){
					if(response.status === 'OK'){
						$.each(response.result, function(target, data){
							$('#'+target).show();
							$('select[name='+target+']').find('option').remove();
							var value = target === 'accessory' ? '' : 'all';
							$('select[name='+target+']').append('<option value="'+value+'">'+data.first+'</option>');
							$.each(data.echo, function(def, name){
								$('select[name='+target+']').append('<option value="'+def+'">'+name+'</option>');
							});
						});
						$('select').select2({
							minimumResultsForSearch : 15
						});
					}
				},
				complete : function(){
					$('.preloader-wrapper').remove();
				}
			});
		}
	});

	$('form').submit(function(){
		if($('input[name=title]').val() === ''){
			$('input[name=title]').val($('select[name=chassis] option:selected').text().replace(/(\(.+|-.+)/, ''));
		}
	});

	$('#all_accessories, #all_paints').change(function(){
		$('.colors').hide();
		var target = $(this).data('target');
		$.ajax({
			cache: false,
			dataType : 'json',
			type : 'POST',
			data : {
				'ajax' : true,
				'game' : $('input[name=target]').val(),
				'all' : $(this)[0].checked,
				'target' : target,
				'chassis' : $('select[name=chassis]').val(),
				'lang' : getCookie('lang')
			},
			beforeSend : function(){
				$('#'+$(this).data('target')+' h5').append(getPreloaderHtml('tiny'));
			},
			success : function(response){
				if(response.status === 'OK'){
					$.each(response.result, function(target, data){
						$('select[name=' + target + ']').find('option').remove();
						$('select[name=' + target + ']').append('<option value="">' + data.first + '</option>');
						$.each(data.echo, function(def, name){
							$('select[name=' + target + ']').append('<option value="' + def + '">' + name + '</option>');
						});
					});
				}
			},
			complete : function(){
				$('.preloader-wrapper').remove();
			}
		});
	});

	$('select[name=paint]').change(function(){
		var val = $(this).val();
		val = val.split('/');
		if(val[val.length - 1] === 'default.sii'){
			$('.colors').show();
		}else{
			$('.colors').hide();
		}
	});

	$('.colors input[type=color]').change(function(){
		var rgb = hexToRgb($(this).val());
		var scs = rgbToScs(rgb.r, rgb.g, rgb.b);
		setColors($(this).val(), rgb, scs);
	});

	$('#color_hex').keyup(function(){
		var val = $(this).val();
		if(val !== ''){
			var regexp = new RegExp('^#?[A-Fa-f0-9]{6}$');
			if(regexp.test(val)){
				var hex = val.replace('#', '');
				var rgb = hexToRgb(hex);
				var scs = rgbToScs(rgb.r, rgb.g, rgb.b);
				setColors('#'+hex, rgb, scs);
			}
		}
	});

	$('#color_rgb_b, #color_rgb_g, #color_rgb_r').keyup(function(){
		var val = parseInt($(this).val());
		console.log(val);
		if(!isNaN(val)){
			if(val > 255){
				$(this).val(255);
				$(this).trigger('keyup');
			}else{
				var rgb = {
					r : parseInt($('#color_rgb_r').val()),
					g : parseInt($('#color_rgb_g').val()),
					b : parseInt($('#color_rgb_b').val())
				};
				console.log(rgb);
				var hex = rgbToHex(rgb.r, rgb.g, rgb.b);
				var scs = rgbToScs(rgb.r, rgb.g, rgb.b);
				setColors(hex, rgb, scs);
			}
		}
	});

	$('#color_scs_b, #color_scs_g, #color_scs_r').keyup(function(){
		var val = parseFloat($(this).val());
		if(!isNaN(val)){
			if(val > 1){
				$(this).val(1);
				$(this).trigger('keyup');
			}else{
				var scs = {
					r: parseFloat($('#color_scs_r').val()),
					g: parseFloat($('#color_scs_g').val()),
					b: parseFloat($('#color_scs_b').val())
				};
				var rgb = {
					r: Math.round(scs.r * 255),
					g: Math.round(scs.g * 255),
					b: Math.round(scs.b * 255)
				};
				var hex = rgbToHex(rgb.r, rgb.g, rgb.b);
				$('#color_palette').val(hex);
				$('#color_hex').val(hex);
				$('#color_rgb_r').val(rgb.r);
				$('#color_rgb_g').val(rgb.g);
				$('#color_rgb_b').val(rgb.b);
			}
		}
	});

	$('#weight').keyup(function(){
		var val = $(this).val();
		var newVal = $(this).val();
		var regexpDigits = new RegExp('^\d*$');
		if(!regexpDigits.test(val)){
			newVal = val.replace(/\D/, '');
			$(this).val(newVal);
		}
		if(newVal.length > 10){
			$(this).val(newVal.substr(0, 10));
		}
	});

	$('#image').change(function(){
		var _URL = window.URL || window.webkitURL;
		if(this.files[0].size > 5500000){
			alert($(this).data('size'));
			$(this).val('');
			return false;
		}
		var file, img, dimensions = $(this).data('dimensions');
		if ((file = this.files[0])) {
			img = new Image();
			img.src = _URL.createObjectURL(file);
			img.onload = function () {
				if(this.width > 3000 || this.height > 3000){
					alert(dimensions);
					$('#image').val('');
					$('#image-path').val('');
					return false;
				}
			};
		}
	});

});

function setColors(hex, rgb, scs){
	$('#color_palette').val(hex);
	$('#color_hex').val(hex);
	$('#color_rgb_r').val(rgb.r);
	$('#color_rgb_g').val(rgb.g);
	$('#color_rgb_b').val(rgb.b);
	$('#color_scs_r').val(parseFloat(scs.r.toFixed(3)));
	$('#color_scs_g').val(parseFloat(scs.g.toFixed(1)));
	$('#color_scs_b').val(parseFloat(scs.b.toFixed(1)));
}

function rgbToHex(r, g, b) {
	return "#" + componentToHex(r) + componentToHex(g) + componentToHex(b);
}

function componentToHex(c) {
	var hex = c.toString(16);
	return hex.length == 1 ? "0" + hex : hex;
}

function hexToRgb(hex) {
	var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
	return result ? {
		r: parseInt(result[1], 16),
		g: parseInt(result[2], 16),
		b: parseInt(result[3], 16)
	} : null;
}

function rgbToScs(r, g, b){
	return {
		r : r / 255,
		g : g / 255,
		b : b / 255
	};
}

function getCookie(name) {
	var matches = document.cookie.match(new RegExp(
		"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
	));
	return matches ? decodeURIComponent(matches[1]) : undefined;
}

function getPreloaderHtml(preloaderClass, color){
	if(preloaderClass === undefined) preloaderClass = '';
	if(color === undefined) color = 'spinner-red-only';
	return '<div class="preloader-wrapper active '+preloaderClass+'">'+
		'<div class="spinner-layer '+color+'">'+
		'<div class="circle-clipper left">'+
		'<div class="circle"></div>'+
		'</div>' +
		'<div class="gap-patch">'+
		'<div class="circle"></div>'+
		'</div>' +
		'<div class="circle-clipper right">'+
		'<div class="circle"></div>'+
		'</div>'+
		'</div>'+
		'</div>';
}

$(window).resize(function() {
	$('select').select2({
		minimumResultsForSearch : 15
	});
});