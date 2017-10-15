/**
 * Created by Administrator on 2017/06/07.
 */


//加载更多刷新操作
var loading = false;
var maxItems = 0;
var page=0;
var type='progress';
var id=$('[data-role="id"]').attr('data-id');
//默认加载获取总条数
var total=$('[data-role="progress"]').attr('data-total');
maxItems=total;
//获取进行中总数
$('[data-role="progress"]').click(function () {
    var total=$(this).attr('data-total');
    maxItems=total;
    type="progress";
    $(this).addClass('active');
    $('[data-role="document"]').removeClass('active');
    $('#tab li').remove();
    $('#tab .teb-content').remove();
    page=0;
    addItems(type,lastIndex);
});
//获取已完成总数
$('[data-role="document"]').click(function () {
    var total=$(this).attr('data-total');
    maxItems=total;
    type="document";
    $(this).addClass('active');
    $('[data-role="progress"]').removeClass('active');
    $('#tab .teb-content').remove();
    page=0;
    addItems(type,lastIndex);
});

//首次加载数据
function addItems(type, lastIndex) {
    $('.infinite-scroll-preloader').css('display','');
    var html = '';
    $.ajax( {
        url:U('Project/index/loadSchedule'),
        data:{
            page:++page,
            type:type,
            id:id,
        },
        type:'post',
        cache:false,
        dataType:'json',
        async:true,
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