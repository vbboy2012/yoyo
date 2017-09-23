var sectionID=0;
sectionID=$('[data-role="follow"]').attr('data-forum_id');
$(function () {
    //加载更多刷新操作
    var loading = false;
    var maxItems = 0;
    var page=0;
    var type='common';
    var sort='create_time';
    //默认加载获取总条数
    var total=$(this).attr('data-total');
    maxItems=total;
    //获取普通帖子总数
    $('[data-role="commonPost"]').click(function () {
        $('[data-role="sort-box"]').css('display','none') ;
        $('[data-role="hidd-list"]').css('display','none') ;
        var total=$(this).attr('data-total');
        maxItems=total;
        type="common";
        $(this).addClass('active')
        $('[data-role="essencePosttotal"]').removeClass('active');
        $('#tab li').remove();
        page=0;
        addItems(type,lastIndex);
    });
    //获取精华帖子总数
    $('[data-role="essencePosttotal"]').click(function () {
        $('[data-role="sort-box"]').css('display','none') ;
        $('[data-role="hidd-list"]').css('display','none') ;
        var total=$(this).attr('data-total');
        maxItems=total;
        type="essence";
        $(this).addClass('active');
        $('[data-role="commonPost"]').removeClass('active');
        $('#tab li').remove();
        page=0;
        addItems(type,lastIndex);
    });
    //首页排序
    $("[data-role='forum-sort-time']").click(function(){
        if($(this).hasClass('active')) return ;
        $(this).addClass('active') ;
        $("[data-role='forum-sort-hot']").removeClass('active') ;
        // $(".list").addClass('list-height') ;
        sort='create_time';
        $('#tab li').remove();
        page=0;
        addItems(type, lastIndex);
        // $(".list").removeClass('list-height') ;
    });
    $("[data-role='forum-sort-hot']").click(function(){
        if($(this).hasClass('active')) return ;
        $(this).addClass('active') ;
        $("[data-role='forum-sort-time']").removeClass('active') ;
        // $(".list").addClass('list-height') ;
        sort='last_reply_time';
        $('#tab li').remove();
        page=0;
        addItems(type, lastIndex);
        // $(".list").removeClass('list-height') ;
    });
    //首次加载数据
    function addItems(type, lastIndex) {
        $('.infinite-scroll-preloader').css('display','');
        var html = '';
        $.ajax({
            url:U('Forum/index/commonForumData'),
            data:{
                page:++page,
                type:type,
                sectionID:sectionID,
                sort:sort
            },
            type:'post',
            cache:false,
            dataType:'json',
            async:false,
            success:function(res) {
                if(res.status ==true){
                    $('#tab').append(res.data);
                    lastIndex = $('#tab li').length;
                    if (lastIndex<10){
                        console.log(111)
                        $('.infinite-scroll-preloader').css('display','none');
                    }
                    if (res.data==''){
                        $('.infinite-scroll-preloader').css('display','none');
                    }
                    $('.infinite-scroll-preloader').css('display','none');
                }
                else{
                }
            },
            error : function() {
               $.toast('数据加载异常！')
            }
        });
    }
    addItems(type, 0);
    //分页条数每页十条 对应U('Forum/index/commonForumData') 里面10条
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
            addItems(type, lastIndex);
            lastIndex = $('#tab li').length;
        }, 1000);
    });

    // 帖子排序
    $('[data-role="sort"]').click(function () {
        showHide() ;
        changeSort() ;
    });
    $('[data-role="hidd-list"]').click(function () {
        showHide() ;
        changeSort() ;
    });
    $('[data-role="sort-type"]').click(function () {
        showHide() ;
        changeSort();
        var sortType = $('[data-role="sort"]') ;
        $.each($('[data-role="sort-type"]'), function(){
            $(this).removeClass('active') ;
        });
        $(this).addClass('active') ;
        $(this).find('i').removeClass() ;
        $(this).find('i').addClass('iconfont icon-xiangshangjiantou') ;
        sortType.find('span').text($(this).text()) ;
        sort=$(this).attr('data-value') ;
        page=0;
        $('#tab li').remove();
        addItems(type, lastIndex);
    });
    function showHide() {
        $('[data-role="sort-box"]').toggle(50) ;
        $('[data-role="hidd-list"]').toggle(50) ;
        $('html,body').toggleClass('ovfHiden');
    }
    function changeSort() {
        var $this = $('[data-role="sort"]') ;
        if ($this.attr('data-value') == 'on') {
            $this.attr('data-value','off');
            $this.toggleClass('color') ;
            $this.find('i').removeClass() ;
            $this.find('i').addClass('iconfont icon-xiangxiajiantou') ;
            return;
        }
        $this.attr('data-value','on');
        $this.toggleClass('color') ;
        $this.find('i').removeClass() ;
        $this.find('i').addClass('iconfont icon-xiangshangjiantou') ;
    }
});