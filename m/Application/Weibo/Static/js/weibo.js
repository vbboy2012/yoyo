$(document).ready(function () {
    var loading = false;
    var page = 1;
    // 点击查看全文
    var show_more= function () {
        $('.weibo-list-content').each(function () {
            var $obj = $(this).find('.word-wrap');
            if(typeof $obj == 'undefined'){
                return false;
            }
            if($obj[0].offsetHeight >= 90){
                $obj.closest('.proContent').find('.show-more').show();
            }
        });
    };
    show_more();
    // 点击显示操作列表
    $(document).on('click','.create-actions', function () {
        var $this = $(this);
        var weiboId = $this.closest('.operate').attr('data-id');
        var buttons1 = [
            {
                text: '请选择',
                label: true
            },
            {
                text: '删除',
                bold: true,
                color: 'danger',
                onClick: function() {
                    del_weibo(weiboId);
                }
            },
            {
                text: '置顶',
                onClick: function() {
                    set_top(weiboId,$this);
                }
            }
        ];
        var buttons2 = [
            {
                text: '取消',
                bg: 'danger'
            }
        ];
        var buttons = [buttons1, buttons2];

        $.actions(buttons);
    });
    //下拉刷新
    // 添加'refresh'监听器
    $(document).on('refresh', '.pull-to-refresh-content',function(e) {
        var url = U('Weibo/index/index');
        var type = $('.proList').attr('data-type');
        var crowd_id = $('.proList').attr('data-crowd-id');
        var topic_id = $('.proList').attr('data-topic-id');
        var invisible = $('.proList').attr('data-invisible');
        var data = {} ;
        var message = '' ;
        data.page = 1 ;
        data.is_pull = 1 ;
        console.log(topic_id) ;
        if (type != '') {
            data.type = type ;
            data.crowd_id = crowd_id ;
            if (invisible == 1) {
                message = "私密圈子,需加入后才能浏览" ;
            }else{
                message = "还没有动态~" ;
            }
        } else if(isNaN(topic_id)){
            url = U('Weibo/topic/topic');
            data.type = 'huati' ;
            message = "还没有话题~" ;
        } else {
            url = U('Weibo/topic/index');
            data.topk = topic_id ;
            message = "还没有动态~" ;
        }
        $.get(url,data,function(res){
            if (res.status) {
                if (res.html == '') {
                    $('.proList').html('<div class="noWeibo">'+message+'</div>');
                } else {
                    $('.proList').html(res.html);
                    if (topic_id != '') {
                        $("[data-role='card']").ucard({is_mine:0});
                    }
                    page = 1;
                }
                $.pullToRefreshDone('.pull-to-refresh-content');
                $.attachInfiniteScroll($('.infinite-scroll'));
                $('.infinite-scroll-preloader').css('display','');
                show_more();
            }
        });
    });

    // 注册'infinite'事件处理函数
    $(document).on('infinite', '.infinite-scroll-bottom',function() {
        if (loading) return;
        loading = true;
        var url = U('Weibo/index/index');
        var type = $('.proList').attr('data-type');
        var crowd_id = $('.proList').attr('data-crowd-id');
        $.get(url,{page:++page,is_pull:1,type:type,crowd_id:crowd_id},function(res){
            if (res.html != '') {
                $('.proList').append(res.html);
                $("[data-role='card']").ucard({is_mine:0});
            } else {
                $.detachInfiniteScroll($('.infinite-scroll'));
                $('.infinite-scroll-preloader').css('display','none');
            }
            loading = false;
        });
        //容器发生改变,如果是js滚动，需要刷新滚动
      //  $.refreshScroller();
    });

    //点击显示微博类型选择popup

    $(document).on('click','.open-about', function () {
        $.popup('.popup-about');

    });
    $(document).on('click','.close-popup', function () {
        $.closeModal('.popup-about');

    });

    $(document).on('open', '.popup-about', function () {
        $('.floatIcon').removeClass('icon-xiangxiajiantou');
        $('.floatIcon').addClass('icon-xiangshangjiantou');
    });

    $(document).on('close', '.popup-about', function () {
        $('.floatIcon').removeClass('icon-xiangshangjiantou');
        $('.floatIcon').addClass('icon-xiangxiajiantou');
    });

    $(document).on('click','.open-repost', function () {
        var $this = $(this);
        var sourceId = $this.attr('data-sourceId');
        var id = $this.closest('.operate').attr('data-id');
        sourceId = sourceId ? sourceId : id;
        $("[name='weiboId']").val(id);
        $("[name='sourceId']").val(sourceId);
        $.popup('.popup-repost');
    });

    $(document).on('click','.close-popup-repost', function () {
        $.closeModal('.popup-repost');
    });

    $('[data-role="repost"]').click(function(){
        var url = U('Weibo/Index/repost');
        var data = $('#repost').serialize();
        $.post(url,data,function(res){
            if (res.status) {
                $('.sendArea').val('');
                $('.proList').prepend(res.html);
                $.toast(res.info);
            } else {
                $.toast(res.info);
            }
        })
    });

    function del_weibo(weiboId) {
        var $this = $('#weibo_'+weiboId);
        $.post(U('Weibo/Index/doDelWeibo'), {weibo_id: weiboId}, function (msg) {
            if (msg.status) {
                $this.remove();
                $.toast('删除动态成功');
            } else {
                $.toast(msg.info);
            }
        }, 'json');
    }

    function set_top(weiboId,that){
        var html = '';
        var crowd_id = that.attr('data-crowd-id') ? that.attr('data-crowd-id') : '0';
        if (crowd_id == '0') {
            html = '<div class="oneDetail"><div class="topWrap"><p>置顶标题：</p>' +
                '<input type="text" name="top_title" class="modal-text-input" placeholder="输入置顶小标题">'+
                '</div>'
                +'<div class="topDays"><p>置顶天数:</p><input class="modal-text-input" type="text" name="top_dead" placeholder="请输入天数"></div>'
        } else {
            html = '<div class="oneDetail"><div class="topWrap"><p>置顶标题：</p>' +
            '<input type="text" name="top_title" class="modal-text-input" placeholder="输入置顶小标题">'+
            '</div>'
            +'<div class="topDays"><p>置顶天数:</p><input class="modal-text-input" type="text" name="top_dead" placeholder="请输入天数"></div>'
            +'<div class="topWhere"><input type="radio" name="top_type" value="1" checked/><span style="margin-right: 20px">圈内置顶</span>'
            +'<input type="radio" name="top_type" value="0"/>全站置顶</div>';
        }
        $.modal({
            title:'置顶设置',
            afterText:  html,
            zidiyi:'topBtn',
            extraClass:'topInput',
            buttons: [
                {
                    text: '关闭',
                    bold: true,
                    onClick: function () {
                        $.popup('.popup-product');
                    }
                },
                {
                    text: '置顶',
                    bold: true,
                    onClick: function () {
                        var is_crowd = $("input[name='top_type']:checked").val();
                        var title = $("input[name='top_title']").val();
                        var top_dead = $("input[name='top_dead']").val();
                        $.post(U('Weibo/Index/setTop'), {weibo_id: weiboId,is_crwod:is_crowd,title:title,top_dead:top_dead}, function (msg) {
                            if (msg.status) {
                                $.toast(msg.info+'正在跳转...', 1500);
                                setTimeout(function(){
                                    window.location.reload();
                                },1500);
                            } else {
                                $.toast(msg.info);
                            }
                        }, 'json');
                    }
                }
            ]
        });
    }

    function del_top(weiboId){
        var option = {};
        var crowd_id = sessionStorage.getItem('crowd_id');
        option.weibo_id = weiboId;
        if (typeof crowd_id == 'string') {
            option.is_crwod = 1;
        }
        $.post(U('Weibo/Index/cancelTop'), option, function (msg) {
            if (msg.status) {
                $.toast(msg.info+'正在跳转...', 1500);
                setTimeout(function(){
                    window.location.reload();
                },1500);
            } else {
                $.toast(msg.info);
            }
        }, 'json');
    }

    $cancelTop = $('[data-role="cancel_top"]');
    $cancelTop.unbind('click');
    $cancelTop.click(function () {
        var $this = $(this);
        var weibo_id = $this.data('id');
        $.confirm('确定取消置顶？', function () {
            del_top(weibo_id);
        });

    });
    

    $('[data-role="toSend"]').click(function(){
        location.href = "sendweibo"
    });

    $("[data-role='card']").ucard({is_mine:0});
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

});
