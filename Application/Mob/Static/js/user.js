$(document).ready(function () {
    bind_page2();
    cancelapply();
    reapply();
    applytitle();
    bind_email();
});

//绑定邮箱
function bind_email() {
    $('.email').magnificPopup({
        type: 'ajax',
        overflowY: 'scroll',
        modal: true,
        callbacks: {
            ajaxContentAdded: function () {
                console.log(this.content);
            }
        }
    })
}


var cancelapply = function () {
    $('.cancelapply').unbind('click');
    $('.cancelapply').click(function () {

        var rank_id = $(this).attr('rank_id');
        var url = $(this).attr('data-url');
        $.post(url, { rank_id: rank_id}, function (msg) {
            if (msg.status == 1) {
                toast.success(msg.info);
                setTimeout(function () {
                    window.location.reload();
                },1000);
            } else {
                toast.error(msg.info);
            }
        }, 'json')
    });
}

var reapply = function () {
    $('.reapply').unbind('click');
    $('.reapply').click(function () {
        var data = $("#reapplytitle").serialize();
        var url = $(this).attr('data-url');
        $.post(url, data, function (msg) {
            if (msg.status == 1) {
                toast.success(msg.info);
                setTimeout(function () {
                    window.location.reload();
                },1000);
            } else {
                toast.error(msg.info);
            }
        }, 'json')
    });
}

var applytitle = function () {
    $('.applytitle').unbind('click');
    $('.applytitle').click(function () {
        var data = $("#ranktitle").serialize();
        var url = $(this).attr('data-url');
        $.post(url, data, function (msg) {
            if (msg.status == 1) {
                toast.success(msg.info);
                setTimeout(function () {
                    window.location.reload();
                },1000);
            } else {
                toast.error(msg.info);
            }
        }, 'json')
    });
}
//查看更多微博
var page = 1;
function bind_page2() {
    $('#getmoreweibolist').unbind('click');
    $('#getmoreweibolist').click(function () {

        var url = $(this).attr('data-url');
        var uid = $(this).attr('data-role');
        $("#getmoreweibolist").html("查看更多" + '&raquo;');
        $.post(url, {page: page + 1,uid:uid}, function (msg) {

            if (msg.status) {
                $(".ulclass").append(msg.html);
                page++;
                forward();
                support();
                bigimg();
            } else {
                $("#getmoreweibolist").html("全部加载完成！");
                $(".look-more").delay(3000).hide(0);
                bigimg();
            }
        });
    });

    $('#getmorenewslist').unbind('click');
    $('#getmorenewslist').click(function(){
        var url=  $(this).attr('data-url');
        $("#getmorenewslist").html("查看更多...");
        $.post(url, {page: page + 1,uid:uid}, function (msg) {
            if (msg.status) {
                $(".ulclass").append(msg.html);
                page++;
            } else {
                $("#getmorenewslist").html("全部加载完成！");
                $(".look-more").delay(1000).hide(0);
            }
        });
    });
}