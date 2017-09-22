$(document).ready(function () {
    forward();
    redbag();
    del();
    doforward();
    submit();
    support();
    bigimg();
    bind_page();
    show_video();
    follow_crowd();
    bind_receive_crowd();
    bind_refuse_crowd();
    bind_remove_crowd_member();
    select_crowd();
    del_crowd();
});

//转发的弹窗
function forward() {
    $('.forward').magnificPopup({
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
//拆红包弹窗
function redbag() {
    $('.redbag').magnificPopup({
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

//转发微博
var doforward = function () {
    $('#cancel').click(function () {
        $('.mfp-close').click();
    });

    $('#conf').click(function () {
        var data = $("#forward").serialize();
        var url = $("#forward").attr('data-url');
        $.post(url, data, function (msg) {
            if (msg.status == 1) {
                $('.mfp-close').click();
                $(".ulclass").prepend(msg.html);
                toast.success('转发成功!');
                forward();
                support();
            } else {
                toast.error(msg.info);
            }
        }, 'json');
    })
}


//图片轮播
var bigimg = function () {
    $('.img-content').each(function () {
        $(this).magnificPopup({
            delegate: 'div',
            type: 'image',
            overflowY: 'scroll',
            overflowX: 'scroll',
            tLoading: '正在载入 #%curr%...',
            mainClass: 'mfp-img-mobile',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0, 1]
            },
            image: {
                tError: '<a href="%url%">图片 #%curr%</a> 无法被载入.',

                verticalFit: true
            }
        });
    });
};

//点赞
var support = function () {
    $('.support').unbind('click');
    $('.support').click(function () {
        var weibo_id = $(this).attr('weibo_id');
        var user_id = $(this).attr('user_id');
        var url = $(this).attr('url');
        var that = $(this);
        $.post(url, {id: weibo_id, uid: user_id}, function (msg) {
            if (msg.status == 1) {
                toast.success('谢谢您的支持!');
                that.parent().find('span').html(parseInt(that.parent().find('span').html()) + 1);
                that.find('i').removeClass('am-icon-thumbs-o-up');
                that.find('i').addClass('am-icon-thumbs-up');
            } else {
                toast.error(msg.info);
            }

        }, 'json')
    });
}

//微博页面评论
var submit = function () {
    $('.submit').unbind('click');
    $('.submit').click(function () {
        var weibo_conetnet = $(this).parent('#comment').find('.content').val();
        var weibo_Id = $(this).attr('weiboId');
        var url = $(this).attr('url');
        $.post(url, {weiboId: weibo_Id, weibocontent: weibo_conetnet}, function (msg) {
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
var del = function () {
    $('.delete').unbind('click');
    $('.delete').click(function () {
        if (confirm("你确定要删除此评论吗？")) {
            var comment_id = $(this).attr('comment_id');
            var weibo_id = $(this).attr('weibo_id');
            var url = $(this).attr('url');
            $.post(url, {commentId: comment_id, weiboId: weibo_id}, function (msg) {
                if (msg.status) {
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                    toast.success('删除成功!');

                } else {
                    toast.error(msg.info);
                }
            }, 'json')
        }
    });
}
//查看更多微博
var page = 1;
function bind_page() {
    $('#getmore').unbind('click');
    $('#getmore').click(function () {
        var url = $(this).attr('data-url');

        $("#getmore").html("查看更多" + '&raquo;');
        $.get(url, {page: page + 1}, function (msg) {

            if (msg.status) {
                $(".ulclass").append(msg.html);
                page++;
                forward();
                support();
                bigimg();
            } else {
                $("#getmore").html("全部加载完成！");
                $(".look-more").delay(3000).hide(0);
                bigimg();
            }
        }, 'json');
    });
    $('#getmorefocus').unbind('click');
    $('#getmorefocus').click(function () {
        var url = $(this).attr('data-url');

        $("#getmorefocus").html("查看更多" + '&raquo;');
        $.post(url, {page: page + 1}, function (msg) {

            if (msg.status) {
                $(".ulclass").append(msg.html);
                page++;
                forward();
                support();
                bigimg();
            } else {
                $("#getmorefocus").html("全部加载完成！");
                $(".look-more").delay(3000).hide(0);
                bigimg();
            }
        })
    });
    $('#getmorehotweibo').unbind('click');
    $('#getmorehotweibo').click(function () {
        var url = $(this).attr('data-url');

        $("#getmorehotweibo").html("查看更多" + '&raquo;');
        $.post(url, {page: page + 1}, function (msg) {

            if (msg.status) {
                $(".ulclass").append(msg.html);
                page++;
                forward();
                support();
                bigimg();
            } else {
                $("#getmorehotweibo").html("全部加载完成！");
                $(".look-more").delay(3000).hide(0);
                bigimg();
            }
        })
    });
    $('#getmoremyweibo').unbind('click');
    $('#getmoremyweibo').click(function () {
        var url = $(this).attr('data-url');

        $("#getmoremyweibo").html("查看更多" + '&raquo;');
        $.post(url, {page: page + 1}, function (msg) {

            if (msg.status) {
                $(".ulclass").append(msg.html);
                page++;
                forward();
                support();
                bigimg();
            } else {
                $("#getmoremyweibo").html("全部加载完成！");
                $(".look-more").delay(3000).hide(0);
                bigimg();
            }
        })
    });
}

var show_video = function () {
    $('[data-role="show_video"]').click(function () {
        var html = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,0,0" width="257" height="33" >' +
            '<param name="movie" value="' + $(this).attr('data-src') + '"  /> ' +
            '<param name="quality" value="high" />' +
            '<param name="menu" value="false" />' +
            ' <param name="wmode" value="transparent" />' +
            '<param name="allowScriptAccess" value="always" />' +
            ' <embed  src="'+ $(this).attr('data-src')+'" play="true" allowScriptAccess="always" quality="high" wmode="transparent" menu="false" pluginspage="http://www.adobe.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="100%" height="330"></embed >' +
            '</object>';
        $(this).html(html).removeAttr('style');
    });
}

window.onload=function () {
    var navtitle=$('#navtitle').val();
    switch (navtitle){
        case 'allweibo':
            $('#allweibo').addClass('cur');
            break;
        case 'hotweibo':
            $('#hotweibo').addClass('cur');
            break;
        case 'myfocus':
            $('#myfocus').addClass('cur');
            break;
        case 'myweibo':
            $('#myweibo').addClass('cur');
            break;
    }

}

var follow_crowd = function () {
    var $unfollow = $('[data-role="unfollow_crowd"]');
    var $follow = $('[data-role="follow_crowd"]');

    $unfollow.unbind('click');
    $unfollow.click(function () {
        var $this = $(this);
        var url = U('Mob/Weibo/quit');
        console.log($this.data('id'));
        $.post(url, {'crowd_id': $this.data('id')}, function (res) {
            console.log(JSON.stringify(res));
            if (res.status == 1) {
                $this.attr('data-role', 'follow_crowd');
                $this.html('加入');
                follow_crowd();
                toast.success(res.info);
            } else {
                toast.error(res.info);
            }
        });
    });

    $follow.unbind('click');
    $follow.click(function () {
        var $this = $(this);
        var url = U('Mob/Weibo/attend');
        var dom = "#"+$this.closest('.fade').attr('id');
        var $followBtn = $("[data-target="+dom+"]");
        $.post(url, {'crowd_id': $this.data('id')}, function (res) {
            if (res.status == 1) {
                var dom = "#"+$this.closest('.fade').attr('id');
                $followBtn.attr('data-role', 'unfollow_crowd');
                $followBtn.html('已加入');
                $this.attr('data-role', 'unfollow_crowd');
                $this.html('已加入');
                follow_crowd();
                toast.success(res.info);
            } else if (res.status == 2) {
                $followBtn.attr('data-role', 'wait_check');
                $followBtn.html('待审核');
                $this.attr('data-role', 'wait_check');
                $this.html('待审核');
                toast.success(res.info);
            } else {
                toast.error(res.info);
            }
        });
    });
};

var bind_receive_crowd = function () {
    var $receive_member = $('[data-role="receive_member"]');
    $receive_member.unbind('click');
    $receive_member.click(function () {
        var $this = $(this);
        var uid = $this.attr('data-uid');
        var crowd_id = $this.attr('data-crowd-id');
        $.post(U('Mob/Weibo/receiveMember'), {uid: uid, crowd_id: crowd_id}, function (res) {
            handleAjax(res);
        });
    })
};

var bind_refuse_crowd = function () {
    var $refuse_member = $('[data-role="refuse_member"]');
    $refuse_member.unbind('click');
    $refuse_member.click(function () {
        var $this = $(this);
        var uid = $this.attr('data-uid');
        var crowd_id = $this.attr('data-crowd-id');
        $.post(U('Mob/Weibo/refuseMember'), {uid:uid, crowd_id: crowd_id}, function (res) {
            handleAjax(res);
        })
    })
}

var bind_remove_crowd_member = function () {
    var $remove_member = $('[data-role="remove_crowd_member"]');
    $remove_member.unbind('click');
    $remove_member.click(function () {
        $this = $(this);
        if (confirm('确定要移除该成员么？')) {
            var $this = $(this);
            var uid = $this.attr('data-uid');
            var crowd_id = $this.attr('data-crowd-id');
            $.post(U('Mob/Weibo/removeMember'), {uid: uid, crowd_id: crowd_id}, function (res) {
                if (res.status == 1) {
                    $this.closest('.review').fadeOut('slow',function(){
                        $this.remove();
                    });
                    toast.success('移除成功');
                } else {
                    toast.error(res.info);
                }
            })
        }
    });
};

var select_crowd = function () {
    $('[data-role="crowd_title"]').click(function() {
        var $this = $(this);
        var title = $this.attr('data-title');
        var crowd_id = $this.attr('data-id');
        var img = $this.attr('data-img');
        $('.like-input').html(title);
        $('[data-role="title_image"]').attr('src',img);
        $('[data-role="send_weibo"]').attr('data-crowd',crowd_id);
        $('.types').css('display', 'none');
    })
};

var del_crowd = function () {
    $('[data-role="del_crowd"]').click(function () {
        var $this = $(this);
        if(confirm('确定要解散该圈子么？')) {
            var crowd_id = $this.attr('data-crowd-id');
            $.post(U('Mob/Weibo/delCrowd'),{crowd_id:crowd_id},function(res){
                handleAjax(res);
            }, 'json');
        }
    });
};
