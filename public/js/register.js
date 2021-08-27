const const_form_container = document.getElementById('form-container');
const const_form_slider_content = document.getElementById('form-slider-content');

var slideCount = 0;
var previousSlideCount = 0;
var maxSlideCount = 0;

var canSlide = true;

maxSlideCount = const_form_slider_content.childElementCount-1;

function slideLeft() {
    if (canSlide === true) {
        if (slideCount > 0) {
            previousSlideCount = slideCount;
            slideCount--;
            animateSlide(slideCount, previousSlideCount);
        }
    }
}
function slideRight() {
    if (canSlide === true) {
        if (slideCount < maxSlideCount) {
            previousSlideCount = slideCount;
            slideCount++;
            animateSlide(slideCount, previousSlideCount);
        }
    }
}

function animateSlide(s, ps) {
    let element_width = parseInt(getComputedStyle(const_form_container, null).getPropertyValue('width')),
        element_border_width = parseInt(getComputedStyle(const_form_container, null).getPropertyValue('border-left-width'));

    let form_container_width = element_width - element_border_width * 2;
    const_form_slider_content.style.left = (const_form_slider_content.offsetLeft + ( (ps - s) * form_container_width)) + "px"

    canSlide = false;
    setTimeout(activateSlide, 500);
}

function activateSlide() {
    canSlide = true;
}