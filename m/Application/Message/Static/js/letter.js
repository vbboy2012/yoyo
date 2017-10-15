/**
 * Created by Administrator on 2017/9/9 0009.
 */
$(function(){
    var page = 0 ;
    var flag = false ;
    $('[data-role="get-letter"]').click(function(){
        location.hash = '私信' ;
        if(MID <= 0){
            $('.infinite-scroll-preloader').hide();
            return false;
        }
        flag = true ;
        if($('[data-role="letter-list"]').find('li').length == 0){
            $('.infinite-scroll-preloader').show();
            getLetterUser() ;
        }
    });
    $('[data-role="message"]').click(function(){
        location.hash = '消息' ;
        flag = false ;
    });
    function getLetterUser(){
        if(flag == false){
            return ;
        }
        var data = {} ;
        data.page = ++page ;
        $.post(U('Ucenter/Letter/getUserLetterList'), data, function(res){
            if(res.status == 1){
                $('[data-role="letter-list"]').append(res.data);
                $('.infinite-scroll-preloader').hide();
            }else{
                var div = "<div class='end-line'>—— 我是底线 ——</div>" ;
                $('[data-role="letter-list"]').append(div);
                $.detachInfiniteScroll($('.infinite-scroll'));
                $('.infinite-scroll-preloader').remove();
            }
        });
    }
    var loading = false ;
    $(document).on('infinite', '.infinite-scroll',function() {
        // 如果正在加载，则退出
        if (loading) return;
        // 设置flag
        loading = true;
        $('.infinite-scroll-preloader').show();
        setTimeout(function() {
            loading = false;
            getLetterUser();
        }, 1000);
    });
    var lHash = location.hash ;
    if(lHash == encodeURI('#私信') || lHash == '#私信'){
        $('[data-role="get-letter"]').click() ;
    }
});