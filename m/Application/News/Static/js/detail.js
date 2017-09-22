//资讯详情评论
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
    var url = U('News/Index/addComment');
    $.post(url,{content:text,newsId:id},function(res){
        if (res.status==1) {
            $('[data-role="all-comment"]').prepend(res.html);
            $('.sendArea').html('');
            var comment=$('[data-role="comment"]').attr('data-total');
            var all_comment = $('[data-role="comment"]').text();
            all_comment=parseInt(all_comment);
            comment=parseInt(comment);
            comment++;
            all_comment++;
            $('[data-role="comment"]').text(all_comment);
            $('[data-role="comment"]').attr('data-total',comment);
            $.toast('评论成功');
            $('.noWrap').hide();
            var safa = $('[data-role="shafa"]') ;
            if (safa.length >0){
                safa.css('display','none') ;
            }
        } else {
            $.toast(res.info);
        }
    })
});
//资讯收藏
$('[data-role="collect"]').click(function () {

    var url=U('Core/Collect/doCollect');
    var $this = $(this);
    var id=$this.attr('data-id');
    $.post(url,{module:'News',table:'news',row:id},function(res){
        if (res.status) {
            $this.css("color","#ec725d");
        } else {
            $this.css("color","black");
        }
        $.toast(res.info);
    })


});
//资讯分享
$('[data-role="shareComment"]').click( function () {
    var html = $('.shareArea').html();
    var query = $(this).attr('data-query');
    if (html == '') {
        $.toast('请输入分享感受~');
        return ;
    }
    var text = html.replace(/\<img.*?data\-title\="(.*?)" .*?>/g, "$1");
    text = emojione.toShort(text);
    text = text.replace(/\<a.*?data\-text\="(.*?)" .*?<\/a>/g, "$1");
    text = text.replace(' ', '/nb');
    text = text.replace(/&nbsp;/g, '/nb');
    text = text.replace(/\<br>/g, '/br');
    text = text.replace(/\<div>(.*?)<\/div>/g, '/br$1');
    var url = U('Weibo/Index/dosendshare');
    $.post(url,{content:text,query:query},function(res){
        if (res.status==1) {
            $('.shareArea').html('');
            $.toast('分享成功');
            $('.noWrap').hide();
        } else {
            $.toast('分享失败');
        }
    })
}) ;
