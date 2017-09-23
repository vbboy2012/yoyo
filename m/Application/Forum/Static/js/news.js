$(function () {
    $(document).on('click','.open-about', function () {
        $.popup('.popup-type');

    });
    $(document).on('click','.close-popup', function () {
        $.closeModal('.popup-type');

    });

    $(document).on('open', '.popup-about', function () {
        $('.floatIcon').removeClass('icon-xiangxiajiantou');
        $('.floatIcon').addClass('icon-xiangshangjiantou');
    });

    $(document).on('close', '.popup-about', function () {
        $('.floatIcon').removeClass('icon-xiangshangjiantou');
        $('.floatIcon').addClass('icon-xiangxiajiantou');
    });

});