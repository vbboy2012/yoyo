/**
 * Created by 路飞 on 2016/12/13.
 */
$(document).ready(function () {
    follow_crowd();
    bind_receive_crowd();
    bind_refuse_crowd();
    bind_remove_crowd_member();
});

var follow_crowd = function () {
    var $unfollow = $('[data-role="unfollow_crowd"]');
    var $follow = $('[data-role="follow_crowd"]');

    $unfollow.unbind('click');
    $unfollow.click(function () {
        var $this = $(this);
        var crowd_id = $this.data('id');
        $.confirm('确定要退出该圈子么？', function () {
            var url = U('Weibo/Crowd/quit');
            $.post(url, {'crowd_id': crowd_id}, function (res) {
                if (res.status == 1) {
                    $this.attr('data-role', 'follow_crowd');
                    $this.html('加入');
                    follow_crowd();
                    $.toast(res.info);
                } else {
                    $.toast(res.info);
                }
            });
        });

    });

    $follow.unbind('click');
    $follow.click(function () {
        var $this = $(this);
        var url = U('Weibo/Crowd/attend');
        var crowd_id = $this.data('id');
        $.post(url, {'crowd_id': crowd_id}, function (res) {
            if (res.status == 1) {
                // $this.attr('data-role', 'unfollow_crowd');
                // $this.html('已加入');
                $this.attr('data-role', 'unfollow_crowd');
                $this.html('已加入');
                follow_crowd();
                $.toast(res.info);
            } else if (res.status == 2) {
                // $this.attr('data-role', 'wait_check');
                // $this.html('待审核');
                $this.attr('data-role', 'wait_check');
                $this.html('待审核');
                $.toast(res.info);
            } else {
                $.toast(res.info);
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
        $.post(U('Weibo/Crowd/receiveMember'), {uid: uid, crowd_id: crowd_id}, function (res) {
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
        $.post(U('Weibo/Crowd/refuseMember'), {uid:uid, crowd_id: crowd_id}, function (res) {
            handleAjax(res);
        })
    })
}

var bind_remove_crowd_member = function () {
    var $remove_member = $('[data-role="remove_crowd_member"]');
    $remove_member.unbind('click');
    $remove_member.click(function () {
        var $this = $(this);
        $.confirm('确定要移除该成员么？', function () {
            var uid = $this.attr('data-uid');
            var crowd_id = $this.attr('data-crowd-id');
            $.post(U('Weibo/Crowd/removeMember'), {uid: uid, crowd_id: crowd_id}, function (res) {
                if (res.status == 1) {
                    $this.closest('.review').fadeOut('slow',function(){
                        $this.remove();
                    });
                    handleAjax(res);
                } else {
                    $.toast(res.info);
                }
            })
        });
    });
};

//上传封面
$('[data-role="add_cover"]').click(function () {
    $(this).parent().css('height', '120px');
    $(this).css('display', 'none') ;
    var add = $('.addCover') ;
    if (is_weixin()&&is_android()) {
        $('.img-list').css('display', 'inline-flex') ;
        add.addClass('image_uploader') ;
    }else{
        if (add.hasClass('image_uploader') == false) {
            add.html('') ;
            add.uploadImage({limit:1});
        }
    }
});
//删除封面
$('[data-role="delete_cover"]').click(function () {

});
// 选择圈子所属分类
var crowdIdArr = [];
var crowdTitleArr = [];
$('.crowd_by_id').each(function(){
    crowdIdArr.push($(this).text());
});
$('.crowd_by_title').each(function(){
    crowdTitleArr.push($(this).text());
});
$('[data-role="chose_type"]').picker({
    formatValue:function(picker, value, displayValue){
        $('[data-role="crowd_type"]').val(value);
        return displayValue;
    },
    toolbarTemplate: '<header class="bar bar-nav">\
  <button class="button button-link pull-right close-picker">确定</button>\
  <h1 class="title">选择分类</h1>\
  </header>',
    cols: [
        {
            textAlign: 'center',
            values: crowdIdArr,
            displayValues: crowdTitleArr
        }
    ]
});
// 选择圈子所属分类
$('[data-role="if_open"]').picker({
    formatValue:function(picker, value, displayValue){
        $('[data-role="if_open"]').val(value);
        return displayValue;
    },
    toolbarTemplate: '<header class="bar bar-nav">\
  <button class="button button-link pull-right close-picker">确定</button>\
  <h1 class="title">圈子类型</h1>\
  </header>',
    cols: [
        {
            textAlign: 'center',
            values: ['0', '1'],
            displayValues: ['公共圈子', '私有圈子']
        }
    ]
});
// 选择圈子所属分类
$('[data-role="if_free"]').picker({
    formatValue:function(picker, value, displayValue){
        $('[data-role="if_free"]').val(value);
        return displayValue;
    },
    toolbarTemplate: '<header class="bar bar-nav">\
  <button class="button button-link pull-right close-picker">确定</button>\
  <h1 class="title">自由发言</h1>\
  </header>',
    cols: [
        {
            textAlign: 'center',
            values: ['0', '-1'],
            displayValues: ['是', '否']
        }
    ]
});
//圈子公告
$('[data-role="set_sign"]').click(function () {
    $.popup('.popup-about');
});
//完成创建
$('[data-role="complete"]').click(function () {
    var data = $("#add_crowd").serialize();
    var url = $("#add_crowd").attr('data-url');
    $.post(url, data, function (msg) {
        handleAjax(msg);
    }, 'json');
});

$('[data-role="del_crowd"]').click(function () {
    var $this = $(this);
    $.confirm('确定要解散该圈子么？', function () {
        var crowd_id = $this.attr('data-crowd-id');
        $.post(U('Weibo/Crowd/delCrowd'),{crowd_id:crowd_id},function(res){
            handleAjax(res);
        }, 'json');
    });
});

var loading = false;
var page = 1;
$(document).on('infinite', '.infinite-scroll-bottom',function() {
    if (loading) return;
    loading = true;
    var url = U('Weibo/Crowd/crowdManager');
    var type = $('.proList').attr('data-type');
    var id = $('.proList').attr('data-crowd-id');
    $.get(url,{page:++page,is_pull:1,type:type,id:id},function(res){
        if (res.html != '') {
            $('.proList').append(res.html);
        } else {
            $.detachInfiniteScroll($('.infinite-scroll'));
            $('.infinite-scroll-preloader').css('display','none');
        }
        loading = false;
    });
    //容器发生改变,如果是js滚动，需要刷新滚动
    //  $.refreshScroller();
});