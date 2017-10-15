/**
 * Created by Administrator on 2017/8/22 0022.
 */
$(function(){
    //加载数据
    var loading = false;
    var maxItems = 0;
    var page=0;
    var total = $('.event-list').attr('data-total');
    var data = {} ;
    data.type = $('.event-list').attr('data-type') ;
    data.lora = $('.event-list').attr('data-active');
    maxItems=total;
    //首次加载数据
    function addItems(lastIndex) {
        $('.infinite-scroll-preloader').css('display','');
        var html = '';
        data.page=++page;
        $.ajax( {
            url:U('Event/index/eventList'),
            data:data,
            type:'post',
            cache:false,
            dataType:'json',
            async:false,
            success:function(res) {
                if(res.status ==true){
                    $('.event-list').append(res.data);
                    lastIndex = $('.event-list li').length;
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
    //分页条数每页十条 对应里面10条
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
            lastIndex = $('.event-list li').length;
        }, 1000);
    });
    //首页转换资讯分类
    $(document).on('click','[data-role="change-cate"]',function(){
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
        var eType = $(this).attr('data-value') ;
        data.type = eType ;
        page = 0 ;
        liScroll(eType) ;
        var url = U("Event/Index/getCount") ;
        $.post(url, {type:eType}, function(res){
            if (res.status == 1) {
                total= res.data ;
            }
        });
        $('[data-list="event-list"]').empty() ;
        addItems(0) ;
        if ($('.event-list li').length==0){
            $(".event-list").append('<p class="noMore">暂无活动</p>')
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
        $('[data-role="show-type"]').find('i').toggleClass('iconfont icon-fabu1') ;
        $('[data-role="show-type"]').find('i').toggleClass('iconfont icon-more_unfold') ;
    }
    function changeInfo() {
        if (allType == '') {
            allType = '<li><a href="javascript:void(0);">全部选项</a></li>' ;
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
        var url = U("Event/Index/getAllType") ;
        $.get(url, function(res){
            if (res.status == 1) {
                $('[data-list="e-type"]').html(res.data) ;
            } else {
                $('[data-list="e-type"]').html('<span class="noMore">暂无数据</span>') ;
            }
            $('#head-loading').css('display','none');
        });
    }

});

