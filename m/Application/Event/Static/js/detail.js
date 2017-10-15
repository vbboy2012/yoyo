
$(function(){
    $(document).on('click','.open-about', function () {
        $.popup('.popup-about');
        $('.floatIcon').removeClass('icon-xiangxiajiantou');
        $('.floatIcon').addClass('icon-xiangshangjiantou');
    });
    $(document).on('click','.close-popup', function () {
        $.closeModal('.popup-about');
        $('.floatIcon').removeClass('icon-xiangshangjiantou');
        $('.floatIcon').addClass('icon-xiangxiajiantou');
    });

    $(document).on('click','.open-repost', function () {
        $.popup('.popup-repost');
    });

    $(document).on('click','.close-popup-repost', function () {
        $.closeModal('.popup-repost');
    });


    $('.sendArea').keypress(function (e) {
        if (e.which == 13 || e.which == 10) {
            $('[data-role="sendComment"]').click();
        }
    });


    $('[data-role="sendComment"]').click(function(){
        var id = $(this).attr('data-id');
        var html = $('.sendArea').html();
        var text = html.replace(/\<img.*?data\-title\="(.*?)" .*?>/g, "$1");
        text = emojione.toShort(text);
        text = text.replace(/\<a.*?data\-text\="(.*?)" .*?<\/a>/g, "$1");
        text = text.replace(' ', '/nb');
        text = text.replace(/&nbsp;/g, '/nb');
        text = text.replace(/\<br>/g, '/br');
        text = text.replace(/\<div>(.*?)<\/div>/g, '/br$1');
        var url = U('Event/Index/addComment');
        $.post(url,{content:text,event_id:id},function(res){
            if (res.status) {
                $('[data-list="comment-list"]').prepend(res.html);
                $('.sendArea').html('');
                $.toast('评论成功');
                $('.noWrap').hide();
            } else {
                $.toast(res.info);
            }
        })
    });
});
//活动分享
$('[data-role="shareComment"]').click( function () {
    var html = $('.shareArea').html();
    var query = $(this).attr('data-query');
    if (html == '') {
        $.toast('请输入分享感受~');
        return ;
    }
    var text = html.replace(/\<img.*?data\-title\="(.*?)" .*?>/g, "$1");
    text = emojione.toShort(text);
    text = text.replace(/\<a.*?data\-text\="(.*?)" .*?<\/a>/g, "$1");
    text = text.replace(' ', '/nb');
    text = text.replace(/&nbsp;/g, '/nb');
    text = text.replace(/\<br>/g, '/br');
    text = text.replace(/\<div>(.*?)<\/div>/g, '/br$1');
    var url = U('Weibo/Index/dosendshare');
    $.post(url,{content:text,query:query},function(res){
        if (res.status==1) {
            $('.shareArea').html('');
            $.toast('分享成功');
            $('.noWrap').hide();
        } else {
            $.toast('分享失败');
        }
    })
}) ;
function checkLogin(obj){
    var uid = obj.getAttribute('data-uid') ;
    if (uid <= 0) {
        no_login() ;
        return false ;
    }else{
        var url = obj.getAttribute('data-url') ;
        if (url == null) {
            $.toast('未被授权参加~') ;
            return false ;
        }
        location.href = url ;
    }
}
function liScroll(type) {
    if (type <= 0) {
        return false;
    }
    var acLi = $('[data-role="change-cate"][data-value="'+type+'"]') ;
    if(acLi.length>0){
        var li = acLi.parent().parent() ;
        var left = acLi.parent().position().left ;
        var ulWidth = (li.width())/2 ;
        if (left>ulWidth){
            left = left - ulWidth ;
            li.scrollLeft(left);
        }
    }else{
        var title = '{$type.title}' ;
        var first = $('[data-role="change-cate"][data-value="0"]') ;
        first.html(title) ;
        first.attr('data-value', type) ;
    }
}