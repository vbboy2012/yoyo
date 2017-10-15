//论坛帖子评论
$(function () {
});
$('[data-role="sendComment"]').click(function(){
    var login = no_login();
    if(login == undefined){
        return ;
    }
    var id = $(this).attr('data-id');
    var html = $('.sendArea').html();
    var text = html.replace(/\<img.*?data\-title\="(.*?)" .*?>/g, "$1");
    text = emojione.toShort(text);
    text = text.replace(/\<a.*?data\-text\="(.*?)" .*?<\/a>/g, "$1");
    text = text.replace(' ', '/nb');
    text = text.replace(/&nbsp;/g, '/nb');
    text = text.replace(/\<br>/g, '/br');
    text = text.replace(/\<div>(.*?)<\/div>/g, '/br$1');
    var url = U('Forum/Index/addComment');
    $.post(url,{content:text,forumId:id},function(res){
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
            $('#count_hide').css('display','block');
            $('#count_show').css('display','none');
        } else {
            $.toast(res.info);
        }
    })
});

$('[data-role="top"]').click(function () {
    var $this=$(this)
    var id=$this.attr('data-id');
    console.log(id);
    $.post(U('Forum/index/setTop'),{id:id},function (res) {
        var text=$('[data-role="top"]').text()
        // console.log(text)
        // console.log(res.status)
        if (res.status==1){
            if (text=='置顶'){
                // alert(text);
                $('[data-role="top"]').html('取消置顶');
                $.toast(res.info)
            }else{
                $('[data-role="top"]').html('置顶');
                $.toast(res.info)
            }
        }else{
            $.toast(res.info)
        }
    })
});
$('[data-role="essence"]').click(function () {
    var id=$(this).attr('data-id');
    console.log(id);
    $.post(U('Forum/index/setEssence'),{id:id},function (res) {
        var text=$('[data-role="essence"]').text()
        if (res.status==1){
            if (text=='加精'){
                // alert(text);
                $('[data-role="essence"]').html('取消加精');
                $.toast(res.info)
            }else{
                $('[data-role="essence"]').html('加精');
                $.toast(res.info)
            }
        }else{
            $.toast(res.info)
        }
    })
});

//点击付费下载
$(document).on('click', '[data-role="payForDownload"]', function (){
    var login = no_login();
    if(login == undefined){
        return ;
    }
    var id = $(this).attr('data-id');
    var uid = $(this).attr('data-uid');
    var type = $(this).attr('data-type');
    var num = $(this).attr('data-num');
    var url = U('Forum/Index/payDownload');
    $.confirm('你确定要付费吗？',
        function () {
            $.post(url, {id:id,uid:uid,type:type,num:num}, function (msg) {
                if (msg.status==1) {
                    $.toast(msg.info);
                    $('#payNone').css('display','block');
                    $('#payBlock').css('display','none');
                } else {
                    $.toast(msg.info);
                }
            }, 'json');
        },
        function () {
            return false;
        }
    );
})

//点击下载增加下载数
$(document).on('click', '[data-role="upDownloadNum"]', function (){
    var id = $(this).attr('data-id');
    var url = U('Forum/Index/clickDownload');
    $.post(url, {id:id}, function (msg) {
        if (msg.status==1) {
            $.toast(msg.info);
        } else {
            $.toast(msg.info);
        }
    }, 'json');
})

//删除评论
$(document).on('click', '[data-role="delete-comment"]', function () {
    var $this = $(this);
    var forumId = $this.attr('data-id');
    var forumUid = $this.attr('data-uid');
    var $id = $('#comment_' + forumId);
    $.confirm('你确定要删除吗？',
        function () {
            $.post(U('Forum/Index/doDelComment'), {forum_id: forumId,forum_uid: forumUid}, function (msg) {
                if (msg.status) {
                    $id.remove();
                    $.toast('删除评论成功');
                } else {
                    $.toast(msg.info);
                }
            }, 'json');
        },
        function () {
            return false;
        }
    );
});

//打赏帖子
$('[data-role="reward"]').click(function () {
    var modal = $.modal({
        title: '您的支持讲鼓励我继续创作！',
        afterText:'<div class="formWrap"><select data-role="moneyType">\n' +
        '</select>' +
        '<input data-role="number" type="number" placeholder="数量"></div>',
        zdywrap:'rewardWrap',
        buttons: [
            {
                text: '取消'
            },
            {
                text: '确认支付',
                bold: true,
                onClick: function () {
                    var ulList= $('[data-role="reward"]');
                    var  number= $('[data-role="number"]').val();
                    if(number==""){
                        $.toast("支付数量不能为空！");
                        return;
                    }
                    if(number<=0){
                        $.toast("请输入正确的打赏金额！");
                        return;
                    }
                    var url=U('Forum/Index/reward');
                    $.post(url,{detailId:ulList.attr("data-id"),be:ulList.attr("data-uid"),tableName:'forum',score:number,type:$('[data-role="moneyType"]').val()},function (res) {
                        if(res.status=="0"){
                            $.toast(res.info);
                        }
                        else if(res.status=="-1"){
                            $.toast(res.info);
                        }
                        else if(res.status=="2"){
                            $.toast(res.info);
                        }
                        else if(res.status=="1"){
                            $('[data-role="head"]').empty();
                            $('[data-role="no"]').text("等"+res.count+"人");
                            for (var i=0;i<res.head.length;i++){
                                $('[data-role="head"]').append("<a href='javascript:'><img class='myImg' src='' ></a>");
                                $('.myImg').eq(i).attr("src",res.head[i].avatar32);
                            }
                            $('[data-role="count"]').text("等"+res.count+"人");
                            $('[data-role="ulList"]').empty();
                            $('[data-role="ulList"]').append(res.html)
                            $.toast(res.info);
                        }
                    })
                }
            }
        ]
    });
    addscore();
})
//删除帖子
$('[data-role="delete"]').click(function () {
    var $this=$(this)
    var id=$this.attr('data-id');
    var uid=$this.attr('data-uid');
    console.log(id);
    $.confirm('你确定要删除吗？',
        function () {
            $.post(U('Forum/index/doDelForum'),{id:id,uid:uid},function (res) {
                if (res.status==1){
                    $.toast(res.info);
                    setTimeout(function () {
                        location.href=res.url;
                    },1500);
                }else{
                    $.toast(res.info)
                }
            })
        },
        function () {
            return false;
        }
    );
});

function addscore() {
    $.get(U('forum/index/scoreType'), {}, function (data) {
        var option = '';
        for (var i in data) {
            var t = data[i];
            option += '<option  value="' + t.id + '" >' + t.title + '</option>';
        }
       $('[data-role="moneyType"]').prepend(option);
    })
}