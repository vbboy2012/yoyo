/**
 * Created by 王杰 on 2016/12/13.
 */
$(function(){

    var commentPage = 1;
    var loading = false;

    $(document).on('click','.open-about', function () {
        $.popup('.popup-about');
        $('.floatIcon').removeClass('icon-xiangxiajiantou');
        $('.floatIcon').addClass('icon-xiangshangjiantou');
    });
    $(document).on('click','.close-popup', function () {
        $.closeModal('.popup-about');
        $('.floatIcon').removeClass('icon-xiangshangjiantou');
        $('.floatIcon').addClass('icon-xiangxiajiantou');
    });

    $(document).on('click','.open-repost', function () {
        $.popup('.popup-repost');
    });

    $(document).on('click','.close-popup-repost', function () {
        $.closeModal('.popup-repost');
    });


    $('.sendArea').keypress(function (e) {
        if (e.which == 13 || e.which == 10) {
            $('[data-role="sendComment"]').click();
        }
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
        var url = U('Weibo/Index/addComment');
        var img_id= $('[name="image"]').val();
        $.post(url,{content:text,weiboId:id,img_id:img_id},function(res){
            console.log(res);
            if (res.status) {
                $('.commentList').prepend(res.html);
                $('.sendArea').html('');
                $.toast('评论成功');
                $('.noWrap').hide();
            } else {
                $.toast(res.info);
            }
        })
    });
    
    var $swiper = null;
    var $textarea = $('.sendArea');
    var get_emoji = function () {
        if ($swiper != null) {
            return false;
        }
        $('.send-bottom.emoji .emoji-list').html('');
        $.get(U('core/expression/getEmoji'), {}, function (res) {
            for (var i in res) {
                $('.send-bottom.emoji .emoji-list').append('<div class="swiper-slide"></div>');
            }
            $swiper = $(".swiper-container").swiper({
                slidesPerView: 1,
                paginationClickable: true,
                spaceBetween: 30,
                pagination: '.swiper-pagination',
                loop: true,
                onSlideChangeStart: function (swiper) {
                    var index = 0;
                    if (swiper.snapIndex > 0 && swiper.snapIndex <= res.length) {
                        index = swiper.snapIndex - 1
                    }
                    if (swiper.snapIndex < 1) {
                        index = res.length - 1;
                    }
                    var t1 = res[index];
                    var html = '';
                    for (var j in t1) {
                        var t2 = t1[j];
                        html += emojione.toImage(':' + t2 + ':');
                    }
                    $('[data-swiper-slide-index="' + index + '"]').html(html);
                    bind_emoji();
                }
            });
        });
    };

    $textarea.focus(function () {
        $('.send-bottom').hide();
    });

    $('[data-role="open-emoji"]').click(function () {
        $('.send-bottom').show();
        get_emoji();
        var h = screen.width / 10 * 4 + 5;
        $('.send-bottom').css('height', h);
    });

    var bind_emoji = function () {
        var $emojione = $('.emojione');
        $emojione.unbind('click');
        $emojione.click(function () {
            var $this = $(this);
            var img = '<img data-title="' + $this.attr('title') + '" src="' + $this.attr('src') + '" style="width: 24px;height: 24px" class="img-disable"/>';
            $textarea.append(img);
        })
    };

    $('[data-role="repost"]').click(function(){
        var url = U('Weibo/Index/repost');
        var data = $('#repost').serialize();
        $.post(url,data,function(res){
            if (res.status) {
                $.toast(res.info);
            } else {
                $.toast(res.info);
            }
        })
    });

    $(document).on('infinite', '.infinite-scroll-bottom',function() {
        if (loading) return;
        loading = true;
        var id = $('[data-role="sendComment"]').attr('data-id');
        $.get(U('Weibo/Index/loadMoreComment'),{page:++commentPage,id:id},function(res){
            if (res.html != '') {
                $('.commentList').append(res.html);
            } else {
                $.detachInfiniteScroll($('.infinite-scroll'));
                $('.infinite-scroll-preloader').css('display','none');
            }
            loading = false;
        });
        //容器发生改变,如果是js滚动，需要刷新滚动
        $.refreshScroller();
    });
    // 点信任打开二维码
    $('[data-role="open-code"]').click(function(){
        var $site = $('[data-role="site_info"]');
        var name = $site.attr('data-name');
        var intro = $site.attr('data-intro');
        var qcode = $site.attr('data-qcode');
        $.modal({
            afterText:'<div class="codeWrap"><img src="'+ qcode +'" alt=""><p class="name">'+ name +'</p><p class="intro">'+ intro +'</p></div>'
        })
    });
});