/**
 * Created by Administrator on 2017/9/7 0007.
 */
$(function(){
    //加载数据
    var loading = false;
    var maxItems = 0;
    var page=0;
    var list_box = $('.list-crowd') ;
    var total = list_box.attr('data-total');
    var data = {} ;
    data.type = list_box.attr('data-type') ;
    maxItems=total;
    //首次加载数据
    function addItems(lastIndex) {
        $('.infinite-scroll-preloader').css('display','');
        data.page = ++page ;
        var html = '';
        $.ajax( {
            url:U('Weibo/Crowd/getListHtml'),
            data:data,
            type:'post',
            cache:false,
            dataType:'json',
            async:false,
            success:function(res) {
                if(res.status ==true){
                    list_box.append(res.data);
                    lastIndex = $('.list-crowd ul').length;
                    if(page == 1){
                        total = res.total ;
                    }
                    if (lastIndex<10){
                        $('.infinite-scroll-preloader').css('display','none');
                    }
                    if (res.data==''){
                        $('.infinite-scroll-preloader').css('display','none');
                    }
                }
                else{
                    var li = '<li class="noMore">'+res.info+'</li>' ;
                    $('.event-list').append(li);
                    $.toast('暂无数据');
                    $('.infinite-scroll-preloader').css('display','none');
                }
            },
            error : function() {
                $.toast('数据加载异常！')
            }
        });
    }
    addItems(0);
    // 分页条数每页十条 对应里面10条
    var lastIndex = 10;
    $(document).on('infinite', '.infinite-scroll',function() {
        // 如果正在加载，则退出
        if (loading) return;
        // 设置flag
        loading = true;
        setTimeout(function() {
            loading = false;
            if (lastIndex >= maxItems) {
                $.detachInfiniteScroll($('.infinite-scroll'));
                $('.infinite-scroll-preloader').remove();
                return;
            }
            addItems(lastIndex);
            lastIndex = $('.list-crowd ul').length;
        }, 1000);
    });
    //首页转换资讯分类
    $(document).on('click','[data-role="change-cate"]',function(){
        var eType = $(this).attr('data-value') ;
        data.id = eType ;
        var tname = $(this).html() ;
        if(eType>0){
            $('[data-role="list-title"]').html('<span style="color:#ec725d">'+tname+'</span> 的圈子') ;
        }else{
            $('[data-role="list-title"]').html("热门圈子") ;
        }
        var cateCode = $(this).attr('data-code') ;
        if (cateCode != 200) {
            showHide();
            $('[data-role="type-list"]').html(typeList) ;
        }
        allType = '' ;
        var nowCate = $("[data-code='200'][data-value='"+$(this).attr('data-value')+"']") ;
        $('[data-role="type-list"]').find('a.active').removeClass('active') ;
        if (nowCate.length >0){
            nowCate.addClass('active') ;
        } else {
            var li = "<li><a href='javascript:void(0);' class='active' data-role='first-cate' data-value='"+$(this).attr('data-value')+"'>"+$(this).text()+"</a></li>" ;
            $('[data-role="type-list"]').prepend(li) ;
        }
        page = 0 ;
        liScroll(eType) ;
        list_box.empty() ;
        addItems(0) ;
        if ($('.list-crowd ul').length==0){
            if(data.id != 'undefine' && data.id>0){
                list_box.append('<p class="noMore">暂无该分类圈子~</p>');
            }else{
                list_box.append('<p class="noMore">暂无圈子~</p>')
            }
        }
    }) ;

    //首页头部js
    var typeList = $('[data-role="type-list"]').html() ;
    var allType = '' ;
    $('[data-role="show-type"]').click(function(){
        showHide() ;
        changeInfo() ;
    });
    function showHide() {
        $('[data-role="all-type"]').toggle() ;
        $('[data-role="show-type"]').find('i').toggleClass('iconfont icon-xiangxiajiantou') ;
        $('[data-role="show-type"]').find('i').toggleClass('iconfont icon-xiangshangjiantou') ;
    }
    function changeInfo() {
        if (allType == '') {
            allType = '<li><a href="javascript:void(0);">全部分类</a></li>' ;
            $('[data-role="type-list"]').html(allType) ;
            getAllType() ;
        } else {
            allType = '' ;
            $('[data-role="type-list"]').html(typeList) ;
        }
    }
    function getAllType(){
        var html = $('[data-list="e-type"]').find('li') ;
        if(html.length > 0) {
            return ;
        }
        $('#head-loading').css('display','');
        var url = U("Weibo/Crowd/getCrowdsType") ;
        $.get(url, function(res){
            if (res.status == 1) {
                $('[data-list="e-type"]').html(res.data) ;
            } else {
                $('[data-list="e-type"]').html('<span class="noMore">暂无数据</span>') ;
            }
            $('#head-loading').css('display','none');
        });
    }
    $('[data-role="change-list"]').click(function(){
        var uid = $(this).attr('data-uid') ;
        if(uid == 0){
            $.toast('请登录~') ;
            return false;
        }
        var actList = $('.nav-list.active') ;
        actList.removeClass('active') ;
        actList.css('pointer-events', '') ;
        $(this).parent().addClass('active') ;
        $(this).parent().attr('disabled', true) ;
        $(this).parent().css('pointer-events', 'none') ;
        data.type = $(this).attr('data-type');
        data.id = 0 ;
        var tname = $(this).html() ;
        var mark = '圈子' ;
        if(data.type == 'mine'){
            mark = '我创建的圈子' ;
        }else if(data.type == 'join'){
            mark = '我参加的圈子' ;
        }
        $('[data-role="list-title"]').html('<span style="color:#ec725d">'+mark+'</span>') ;
        page = 0 ;
        list_box.html('') ;
        addItems(0) ;
        if ($('.list-crowd ul').length==0){
            list_box.append('<p class="noMore">暂无圈子~</p>')
        }
    });
});
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