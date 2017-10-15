/**
 * Created by Administrator on 2017/8/30 0030.
 */
$(function(){

    $('.sendArea').keypress(function (e) {
        if (e.which == 13 || e.which == 10) {
            $('[data-role="sendComment"]').click();
        }
    });

    $('[data-role="sendComment"]').click(function(){
        var eid = $(this).attr('data-eid');
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
        $.post(url,{content:text,event_id:eid,id:id,type:'lzl'},function(res){
            if (res.status) {
                var listBox = $('[data-list="comment-list"]') ;
                if(listBox.attr('data-total') == 0){
                    listBox.html('') ;
                }
                listBox.prepend(res.html);
                $('.sendArea').html('');
                $.toast('评论成功');
                $('.noWrap').hide();
            } else {
                $.toast(res.info);
            }
        })
    });
});