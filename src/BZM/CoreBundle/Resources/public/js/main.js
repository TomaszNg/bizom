/**
 * Main javascript file
 *
 * Author: Tomasz Ngondo <tomasz.ngondo@outlook.fr>
 * Copyright 2017
 */

$(document).ready(function() {
    animBgdFadeAuto($('#visit_bgd_fader'), ['rgb(130, 130, 130)', 'rgb(255, 255, 255)', 'rgb(200, 200, 200)']);
});

// Changes background colors automatically with fade effect
function animBgdFadeAuto($element, $colors) {
    var count = 0;

    setInterval(function() {
        if (count == 0) {
            $element.css({
                'background-color': $colors[count],
                'transition': '2s'
            });
            count++;
        } else if (count == 1) {
            $element.css({
                'background-color': $colors[count],
                'transition': '2s'
            });
            count++
        } else if (count == 2) {
            $element.css({
                'background-color': $colors[count],
                'transition': '2s'
            });
            count = 0;
        }
    }, 5000);
}