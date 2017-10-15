/**
 * Created by Administrator on 2017/9/7 0007.
 */
$(function(){
    /*微博列表js start*/
    var show_more= function () {
        $('.weibo-list-content').each(function () {
            var $obj = $(this).find('.word-wrap');
            if(typeof $obj == 'undefined' || $obj.length <= 0){
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
    //转发
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
                $.toast(res.info);
            } else {
                $.toast(res.info);
            }
        })
    });
    /*微博列表js end*/
    /*推荐好友js start*/
    $("[data-role='change']").click(function () {
        $('.icon-shuaxin').addClass('rotate');
        var url=U('Ucenter/Index/getFriend');
        $.post(url,{},function (res) {
            if(res.status==1){
                var box = $('.friend-list') ;
                box.animate({opacity:.4}, 400) ;
                setTimeout(function() {
                    box.empty();
                    box.append(res.data) ;
                    box.animate({opacity:1}, 500) ;
                }, 400) ;
            }
            setTimeout(function () {
                $('.icon-shuaxin').removeClass('rotate');
            },500);
        })
    });
    /*推荐好友js end*/
});