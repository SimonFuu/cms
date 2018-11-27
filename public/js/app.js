var nav_width_resize = function () {

    var block = $('.nav-block');
    var maxLength = block.length > 7 ? 7 : block.length;
    if (block.length > 0) {
        block.css('width', 1024 / maxLength)
    }
};
$(document).ready(function () {
    nav_width_resize();
});