$(document).ready(function(){

	$('select').select2();

	$('#lang-btn').click(function(){
		document.cookie = 'lang=' + $(this).data('lang');
	});

});