// permet un affichage en fondu de la page ouverture visiteur/comptable.php

$('#affichage').fadeIn(1700).queue(function () {
    $('#virgule').fadeIn(500);
    $(this).dequeue();
});


// permet un effet aimant sur les pages

$.magneticScroll({
    'selector': 'div'
});


// fais apparaitre le loader

$('#test').click(function () {
    $('#loader').fadeIn(1);
});


// permet de cacher le tableau de visiteur

$('#afficheT').click(function () {
    $('#tableau').fadeIn('slow');
});

// cacher affichage douteux des dates

// Si le champ est rempli lorsque l'on se focus decu alors...
$('#dateHF').focus(function() {
    var estRempli = false;
    if ($('#dateHF').val() != "") {
        estRempli = true;
    }

    if(estRempli == true) {
        $('#dateHF').css('color', 'white');
    }
    else {
        $('#dateHF').css('color', '#de4957');
    }
});

// Si le champs est vide ou rempli lorsque l'on relache le focus...
$('#dateHF').blur(function() {
    var estRempli= false;
    if ($('#dateHF').val() != "") {
        estRempli = true;
    }

    if(estRempli == true) {
        $('#dateHF').css('color', 'white');
    }
    else {
        $('#dateHF').css('color', '#de4957');
    }
});




