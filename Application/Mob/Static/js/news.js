$(document).ready(function () {
    submit();
    del();
    bind_page();
});



var submit=function() {
    $('.submit').unbind('click');
    $('.submit').click(function () {
        var news_conetnet = $(this).parent('#comment').find('.content').val();
        var news_id = $(this).attr('news_id');
        var uid = $(this).attr('uid');
        var url = $(this).attr('url');
        $.post(url, {content: news_conetnet, newsId: news_id,uid:uid}, function (msg) {
            if (msg.status == 1) {
                $(".addmore").prepend(msg.html);
                toast.success('评论成功!');
                $('#comment_content_text').val('');
                del();
                comment();
                $('.XT_face_close').click();
            } else {
                toast.error(msg.info);
            }
        }, 'json')
    });
}

//删除评论
var del=function() {
    $('.delete').unbind('click');
    $('.delete').click(function () {
        if (confirm("你确定要删除此评论吗？")) {
            var comment_id = $(this).attr('comment_id');
            var news_id = $(this).attr('news_id');
            var url = $(this).attr('url');
            $.post(url, {commentId: comment_id, newsId: news_id}, function (msg) {
                if (msg.status) {
                    toast.success('删除成功!');
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);

                } else {
                    toast.error(msg.info);
                }
            }, 'json')
        }
    });
}






//查看更多资讯
var page = 1;
function bind_page() {
    $('#getmorehotnews').unbind('click');
    $('#getmorehotnews').click(function(){
        var url=  $(this).attr('data-url');
        $("#getmorehotnews").html("查看更多...");
        $.post(url, {page: page + 1}, function (msg) {
            if (msg.status) {
                $(".ulclass").append(msg.html);
                page++;
            } else {
                $("#getmorehotnews").html("全部加载完成！");
                $(".look-more").delay(1000).hide(0);
            }
        });
    });

    $('#getmorecallnews').unbind('click');
    $('#getmorecallnews').click(function(){
        var url=  $(this).attr('data-url');
        $("#getmorecallnews").html("查看更多...");
        $.post(url, {page: page + 1,mark:1}, function (msg) {
            if (msg.status) {
                $(".ulclass").append(msg.html);
                page++;
            } else {
                $("#getmorecallnews").html("全部加载完成！");
                $(".look-more").delay(1000).hide(0);
            }
        })
    });

    $('#getmorecmynews').unbind('click');
    $('#getmorecmynews').click(function(){
        var url=  $(this).attr('data-url');
        $("#getmorecmynews").html("查看更多...");
        $.post(url, {page: page + 1,mark:2}, function (msg) {
            if (msg.status) {
                $(".ulclass").append(msg.html);
                page++;
            } else {
                $("#getmorecmynews").html("全部加载完成！");
                $(".look-more").delay(1000).hide(0);
            }
        })
    });

    $('#getmorecclassnews').unbind('click');
    $('#getmorecclassnews').click(function(){
        var url=  $(this).attr('data-url');
        var title_id=  $(this).attr('data-role');
        $("#getmorecclassnews").html("查看更多...");
        $.post(url, {page: page + 1,mark:3,titleid:title_id}, function (msg) {
            if (msg.status) {
                $(".ulclass").append(msg.html);
                page++;
            } else {
                $("#getmorecclassnews").html("全部加载完成！");
                $(".look-more").delay(1000).hide(0);
            }
        })
    });
}

$(function () {
   $('#news-type').next().find('#news-type').remove();
    $('.types').hide();
    $('#news-type').click(function () {
        $('.types').toggle();

    });
});


