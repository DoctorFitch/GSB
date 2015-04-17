$('#affichage').fadeIn(1700).queue(function(){
    $('#virgule').fadeIn(500);
    $(this).dequeue();
});

$.magneticScroll({
	'selector': 'div'
});

