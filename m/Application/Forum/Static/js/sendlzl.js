/**
 * Created by Administrator on 2017/5/17.
 */
//论坛帖子楼中楼评论
$(function () {
    var titHeight = $('.dtTit').outerHeight(true) + 50;
    var dtlHeight = $('.dtDetail').outerHeight(true) + 90;
    var addHeight = titHeight + dtlHeight + 'px';
    $('.dtMargin').css('marginTop',addHeight);
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
    var url = U('Forum/Index/addlzl');
    $.post(url,{content:text,lzlId:id},function(res){
        console.log(res);
        if (res.status==1) {
            $('[data-role="all-comment"]').prepend(res.html);
            $('.sendArea').html('');
            var comment=$('[data-role="comment"]').attr('data-total');
            comment=parseInt(comment);
            comment++;
            $('[data-role="comment"]').text('评论数'+comment);
            $('[data-role="comment"]').attr('data-total',comment);
            $.toast('评论成功');
            $('.noWrap').hide();
        } else {
            $.toast(res.info);
        }
    })
});

//删除帖子楼中楼
$(document).on('click', '[data-role="delete-lzl"]', function () {
        var $this = $(this)
        var id = $this.attr('data-id');
        var uid = $this.attr('data-uid');
        var $lzlId = $('#lzl_'+id);
    $.confirm('你确定要删除吗？',
        function () {
            $.post(U('Forum/Index/doDellzl'), {id: id,uid:uid}, function (msg) {
                if (msg.status == 1) {
                    $lzlId.remove();
                    $.toast('删除成功');
                } else {
                    $.toast(msg.info);
                }
            }, 'json');
        },
        function () {
            return false;
        }
    );
    event.stopPropagation();
    });

