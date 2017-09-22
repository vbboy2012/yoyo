var sectionID=0;
sectionID=$('input[name="sectionId"]').val() ;
$(function () {
    //加载更多刷新操作
    var loading = false;
    var maxItems = 0;
    var page=0;
    //首次加载数据
    function addItems(lastIndex) {
        $('.infinite-scroll-preloader').css('display','');
        var html = '';
        $.ajax( {
            url:U('Forum/index/activeUser'),
            data:{
                page:++page,
                sectionID:sectionID,
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
    addItems(0);
    var maxItems=$("input[name='maxItems']").val();
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
            addItems(lastIndex);
            lastIndex = $('#tab li').length;
        }, 1000);
    });
});