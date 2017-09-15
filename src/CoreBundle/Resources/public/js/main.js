/**
 * Main javascript file
 *
 * Author: Tomasz Ngondo <tomasz.ngondo@outlook.fr>
 * Copyright 2017
 */

var $visitorCarousel = $('#visitor_carousel');

$(document).ready(function() {

    // Fade carousel effect
    setInterval(function() { 
        animFade($visitorCarousel); 
    }, 5000);
});

function animFade($element) {
    if ($element.hasClass('visit-background-1')) {
        $element.addClass('visit-background-2').removeClass('visit-background-1');
    } else if ($element.hasClass('visit-background-2')) {
        $element.addClass('visit-background-3').removeClass('visit-background-2');
    } else if ($element.hasClass('visit-background-3')) {
        $element.addClass('visit-background-1').removeClass('visit-background-3');
    }
}