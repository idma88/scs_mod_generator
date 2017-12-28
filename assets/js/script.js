$(document).ready(function(){

	$('select').select2();

	$('#lang-btn').click(function(){
		document.cookie = 'lang=' + $(this).data('lang');
	});

	$('select[name=chassis]').change(function(){
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
				success : function(response){
					if(response.status === 'OK'){
						$('#chassis').after(response.result);
						$('select').select2();
					}
				}
			});
		}else{
			$('#accessory').remove();
			$('#paint').remove();
		}
	});
	
});

function getCookie(name) {
	var matches = document.cookie.match(new RegExp(
		"(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
	));
	return matches ? decodeURIComponent(matches[1]) : undefined;
}