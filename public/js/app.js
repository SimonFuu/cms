var nav_width_resize = function () {

    var block = $('.nav-block');
    if (block.length > 0) {
        block.css('width', 1024 / block.length)
    }
};
$(document).ready(function () {
    nav_width_resize();
});