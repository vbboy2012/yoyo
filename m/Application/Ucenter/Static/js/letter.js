/**
 * Created by Administrator on 2017/9/8 0008.
 */
$(function() {
    var list = $('.content') ;
    var list_box = $('[data-role="letter-list"]') ;
    var numb = 0 ;
    var total = $('[data-role="letter-list"]').attr('data-total') ;
    var uid = $('[data-role="send-letter"]').attr('data-uid') ;
    $('[data-role="send-letter"]').click(function (){
        var flag = numb++ ;
        var content = $('[data-role="content"]') ;
        var letter = content.val() ;
        if(letter == '' || letter == 'undefined') {
            $('[data-role="content"]')[0].focus() ;
            return false;
        }
        putInLetter(letter, flag) ;
        var data = {} ;
        data.content = letter ;
        data.uid = uid ;
        doSend(data, flag) ;
        content.val('') ;
        list.scrollTop( list[0].scrollHeight );

    });
    $('[data-role="content"]').keydown(function(event){
        if(event.keyCode == 13){
            $('[data-role="send-letter"]').click() ;
        }
    });
    //加载历史消息
    var lastIndex = 10;
    var loading = false ;
    var page = 1 ;
    $(document).on('refresh', '.pull-to-refresh-content',function(e) {
        // 如果正在加载，则退出
        if (loading) return;
        // 设置flag
        loading = true;
        getHistory() ;
    });
    function getHistory() {
        $.post(U('Ucenter/Letter/getLetterList'),{pUid:uid,page:++page},function(res){
            if(res.status != 1){
                $('.pull-to-refresh-layer').remove() ;
                var refresh_box = $('.pull-to-refresh-content') ;
                refresh_box.removeClass('infinite-scroll-preloader') ;
                refresh_box.removeClass('infinite-scroll') ;
                refresh_box.removeClass('pull-to-refresh-content') ;
            }
            var height = $('[data-role="letter-list"]').outerHeight() ;
            list_box.prepend(res.data) ;
            height = $('[data-role="letter-list"]').outerHeight() - height ;
            $('.content').scrollTop(height);
            $.pullToRefreshDone('.pull-to-refresh-content');
            $.attachInfiniteScroll($('.infinite-scroll'));
            $('.infinite-scroll-preloader').css('display','');
            loading = false ;
        });
    }
    //内容推进消息列表
    function putInLetter(content, flag) {
        var hide_box = $('[data-role="hide-box"]') ;
        var box = hide_box.clone();
        box.attr('data-role', flag) ;
        box.css('display', 'flex') ;
        box.find('.right-cont').html(content) ;
        // var now = getNowFormatDate() ;
        box.find('.right-time').html('刚刚') ;
        list_box.append(box) ;
        box.animate({opacity:1},500);
        $('[data-role="content"]')[0].focus() ;
    }
    function doSend(data, flag){
        $.post(U('Ucenter/Letter/sendLetter'), data, function(res){
            if(res.status == 1){
                $(document).find('[data-role="'+flag+'"]').find('.show-loading').remove() ;
            }else{
                $.toast(res.info) ;
            }
        });
    }
    $('[data-role="content"]')[0].focus() ;
});

function getNowFormatDate() {
    var date = new Date();
    var seperator1 = "-";
    var seperator2 = ":";
    var month = date.getMonth() + 1;
    var strDate = date.getDate();
    if (month >= 1 && month <= 9) {
        month = "0" + month;
    }
    if (strDate >= 0 && strDate <= 9) {
        strDate = "0" + strDate;
    }
    var currentdate = date.getFullYear() + seperator1 + month + seperator1 + strDate
        + " " + date.getHours() + seperator1 + date.getMinutes();
    return currentdate;
}