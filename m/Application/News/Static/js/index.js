$(function(){
    //加载数据
    var loading = false;
    var maxItems = 0;
    var page=0;
    var total = $('.listWrap').attr('data-total');
    var data = {} ;
    maxItems=total;
    //首次加载数据
    function addItems(lastIndex) {
        $('.infinite-scroll-preloader').css('display','');
        var html = '';
        data.page=++page;
        $.ajax( {
            url:U('News/index/hotNews'),
            data:data,
            type:'post',
            cache:false,
            dataType:'json',
            async:false,
            success:function(res) {
                if(res.status ==true){
                    $('.listWrap').append(res.data);
                    lastIndex = $('.listWrap li').length;
                    if (lastIndex<10){
                        $('.infinite-scroll-preloader').css('display','none');
                    }
                    if (res.data==''){
                        $('.infinite-scroll-preloader').css('display','none');
                    }
                }
                else{
                }
            },
            error : function() {
                $.toast('数据加载异常！')
            }
        });
    }
    addItems(0);
    if (typeof callback === 'function') {
        callback();
    }
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
            lastIndex = $('.listWrap li').length;
        }, 1000);
    });
    //首页转换资讯分类
    $(document).on('click','[data-role="change-cate"]',function(){
        var cateCode = $(this).attr('data-code') ;
        if (cateCode != 200) {
            showHide();
            $('[data-role="category-list"]').html(categoryList) ;
        }
        all_cate = '' ;
        var nowCate = $("[data-code='200'][data-value='"+$(this).attr('data-value')+"']") ;
        $('[data-role="category-list"]').find('a.active').removeClass('active') ;
        console.log($('[data-role="category-list"]').find('a.active')) ;
        if (nowCate.length >0){
            nowCate.addClass('active') ;
        } else {
            if ($(this).attr('data-value') != 'all'){
                var li = "<li><a href='javascript:void(0);' class='active' data-role='first-cate' data-value='"+$(this).attr('data-value')+"'>"+$(this).text()+"</a></li>" ;
                $('[data-role="category-list"]').prepend(li) ;
            }
        }
        var id = $(this).attr('data-value') ;
        data.id = id ;
        page = 0 ;
        var url = U("News/Index/getCount") ;
        $.get(url, {id:id}, function(res){
            if (res.status == 1) {
                total= res.data ;
            }
        });
        $('[data-role="datalist"]').empty() ;
        addItems(lastIndex) ;
        if ($('.listWrap li').length==0){
            $(".listWrap").append('<p class="noMore">暂无资讯</p>')
        }
        $('#head-loading').css('display','none');
    }) ;
    //首页头部js
    var categoryList = $('[data-role="category-list"]').html() ;
    var all_cate = '' ;
    $('[data-role="all-category"]').click(function(){
        showHide() ;
        changeInfo() ;
    });
    function showHide() {
        $('[data-role="category"]').toggle() ;
        $('[data-role="all-category"]').find('i').toggleClass('iconfont icon-fabu1') ;
        $('[data-role="all-category"]').find('i').toggleClass('iconfont icon-more_unfold') ;
    }
    function changeInfo() {
        if (all_cate == '') {
            all_cate = '<li><a href="javascript:void(0);">全部选项</a></li>' ;
            $('[data-role="category-list"]').html(all_cate) ;
            getAllCategory() ;
        } else {
            all_cate = '' ;
            $('[data-role="category-list"]').html(categoryList) ;
        }
    }
    function getAllCategory(){
        var html = $('[data-list="category"]').find('li') ;
        if(html.length > 0) {
            return ;
        }
        $('#head-loading').css('display','');
        var url = U("News/Index/all") ;
        $.get(url, function(res){
            if (res.status == 1) {
                $('[data-list="category"]').html(res.data) ;
            } else {
                $('[data-list="category"]').html('<span class="noMore">暂无数据</span>') ;
            }
            $('#head-loading').css('display','none');
        });
    }
});
