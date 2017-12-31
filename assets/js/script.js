$(document).ready(function(){

	$('select').select2();

	$('#lang-btn').click(function(){
		document.cookie = 'lang=' + $(this).data('lang');
	});

	$('select[name=chassis]').change(function(){
		$('#accessory').hide();
		$('#paint').hide();
		$('#all_accessories, #all_paints').attr('checked',false);
		if($(this).val() !== ''){
			$.ajax({
				cache: false,
				dataType : 'json',
				type : 'POST',
				data : {
					'ajax' : true,
					'chassis' : $(this).val(),
					'lang' : getCookie('lang')
				},
				beforeSend : function(){
					$('#chassis').after(getPreloaderHtml('small'));
				},
				success : function(response){
					if(response.status === 'OK'){
						if(response.status === 'OK'){
							$.each(response.result, function(target, data){
								$('#'+target).show();
								$('select[name='+target+']').find('option').remove();
								$('select[name='+target+']').append('<option value="">'+data.first+'</option>');
								$.each(data.echo, function(def, name){
									$('select[name='+target+']').append('<option value="'+def+'">'+name+'</option>');
								});
							});
							$('select').select2();
						}
					}
				},
				complete : function(){
					$('.preloader-wrapper').remove();
				}
			});
		}
	});

	$('#all_accessories, #all_paints').change(function(){
		var target = $(this).data('target');
		$.ajax({
			cache: false,
			dataType : 'json',
			type : 'POST',
			data : {
				'ajax' : true,
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
	
});

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