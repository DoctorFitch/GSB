// permet un affichage en fondu de la page ouverture visiteur/comptable.php

$('#affichage').fadeIn(1700).queue(function(){
    $('#virgule').fadeIn(500);
    $(this).dequeue();
});



// permet un effet aimant sur les pages

$.magneticScroll({
	'selector': 'div'
});



// fais apparaitre le loader

$('#test').click(function()
{ 
    $('#loader').fadeIn(1);
});



// permet de cacher le tableau de visiteur

$('#afficheT').click(function() {
  $('#tableau').fadeIn('slow');
});

