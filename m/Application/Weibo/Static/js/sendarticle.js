/**
 * Created by 駿濤 on 2016/12/20 0020.
 */


$(function () {


    // 全局配置
    // ___E.config.menus = ['bold', 'color', 'quote'];

    // 生成编辑器
    var editor = new ___E('textarea1');

    // 自定义配置
    editor.config.uploadImgUrl = U('Core/File/uploadPictureByWangEditor');
    editor.config.uploadImgFileName = 'photo' ;
    editor.config.uploadTimeout = 15000 ;

    editor.config.menus = [
        'head',
        'bold',
        'color',
        'quote',
        'list',
        'img'
    ];

    // 初始化
    editor.init();
    var $txt = editor.$txt;

    var $editor = $txt;


    var ori_height = window.innerHeight;

    var selection = null;
    var $range = null;
    var save_range = null;
    var interval = null;
    var $swiper = null;
    $editor.focus(function () {
        var $this = $(this);
        $('.bar-footer').animate({bottom: '0px'}, 100);
        $('.send-bottom').hide();
        setTimeout(function () {
            var now_height = window.innerHeight;
            var height = 0;
            if ($.device.android) {
                height = now_height - ((ori_height == now_height) ? 0 : 50);
            } else {
                height = now_height;
            }
            $('body').css('height', height + 'px').scrollTop(0);
        }, 300);
    });
    $(window).resize(function () {

        var now_height = window.innerHeight;
        if (ori_height == now_height) {
            clearInterval(interval);
            $('body').css('height', now_height + 'px')
        } else {
            $('body').css('height', (now_height - 50) + 'px').scrollTop(0);
        }
    });

    $editor.blur(function () {
        clearInterval(interval);
        setTimeout(function () {
            $('body').css('height', ori_height + 'px')
        }, 300)
    });

    $('[data-role="submit-weibo"]').click(function () {
        $('[data-role="submit-weibo"]').attr("disabled",true);
        var $this = $(this);
        var html = $editor.html();
        var title=$('input[name="title"]').val();
        var text = html.replace(/\<img.*?data\-title\="(.*?)" .*?>/g, "$1");
        text = emojione.toShort(text);
        if(title==''){
            $.toast("标题不能为空");
            $('[data-role="submit-weibo"]').attr("disabled",false);
            return ;
        }
        if(text=='<p><br></p>'){
            $.toast("内容不能为空");
            $('[data-role="submit-weibo"]').attr("disabled",false);
            return ;
        };
        $.post(U('weibo/index/sendArticle'), {
            content: text,
            title: title,
            crowd_id: $('#crowd_id').val(),
            goods_id: $('#goods_id').val(),
            data: {
                location: $('#location').val(),
                location_text: $('.location-text').attr('data-text')
            }
        }, function (res) {
            if (res.status==0) {
                $.toast(res.info);
                $('[data-role="submit-weibo"]').attr("disabled",false);

            }else {
                $.toast(res.info);
                location.href = U('weibo/index/index')
            }
        });
    });


    var get_emoji = function () {
        if ($swiper != null) {
            return false;
            // $swiper.destroy(true, true);
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

    $('[data-role="show-bottom"]').click(function () {
        var $this = $(this);
        var type = $this.attr('data-type');
        $('.send-bottom').hide();
        $('.send-bottom.' + type).show();
        switch (type) {
            case 'image':
                $('.send-bottom.image').uploadImage();
                break;
            case 'emoji':
                get_emoji();
                break;
        }
        var h = screen.width / 10 * 4 + 5;
        $('.send-bottom.' + type).css('height', h);
        $this.closest('.bar-footer').animate({bottom: h + 'px'}, 100);

    });

    var bind_emoji = function () {
        var $emojione = $('.emojione');
        $emojione.unbind('click');
        $emojione.click(function () {
            var $this = $(this);
            var img = '<img data-title="' + $this.attr('title') + '" src="' + $this.attr('src') + '" style="width: 24px;height: 24px" class="img-disable"/>'
            editor.customCommand(true, function () {
                document.execCommand('insertHTML', false, img);
            });
        })
    };

    // 显示地址搜素页面
    $('[data-role="show_address"]').click(function () {
        $.popup('.popup-about');
    });


    $(document).on('opened', '.popup-about', function () {
        var bind_choose_place = function () {
            var $obj = $('[data-role="choose-place"]');
            $obj.unbind('click');
            $obj.click(function () {
                var $this = $(this);
                var location = $this.attr('data-location');
                $('.addressList ul li').find('i').addClass('hidden');
                $this.find('i').removeClass('hidden');
                var $obj = $('.location-text');
                if (location != '0') {
                    $('#location').val(location);
                    $obj.text($this.find('p').eq(0).text().textMore(8));
                    $obj.attr('data-text', $this.find('p').eq(0).text());
                } else {
                    $('#location').val('');
                    $obj.text('分享所在位置');
                    $obj.attr('data-text', '分享所在位置');
                }

            })
        };
        var render_li = function (data) {
            for (var i in data) {
                var t = data[i];
                var location = t.location.lat + ',' + t.location.lng;
                var html = '<li class="close-popup" data-role="choose-place" data-location="' + location + '"><p>' + t.title + '</p><p>' + t.address + '</p><i class="iconfont icon-iconfontduigou fr hidden"></i></li>';
                $('.addressList ul').append(html);
            }
            bind_choose_place();
        };
        var geolocation = new qq.maps.Geolocation("TTKBZ-DMPKX-BQ54K-TPPX5-F5OL7-NFBIA", "os");
        var options = {};
        geolocation.getLocation(function (position) {
            var location = position.lat + ',' + position.lng;
            var get_geolocation = function () {
                $('.addressList ul li').not('.first').remove();
                $.get(U('core/position/getNearby'), {location: location}, function (res) {
                    var pois = res.result.pois;
                    render_li(pois);
                }, 'json');
            };
            if ($('.addressList ul li').length == '1') {
                get_geolocation();
            }
            $('#search').keyup(function (e) {
                if (e.which == 13 || e.which == 10) {
                    var $this = $(this);
                    var keyword = $this.val();
                    if (keyword == '') {
                        get_geolocation();
                    } else {
                        $.get(U('core/position/search'), {
                            keyword: keyword,
                            location: location
                        }, function (res) {
                            $('.addressList ul li').not('.first').remove();
                            var data = res.data;
                            render_li(data);
                        }, 'json');
                    }
                }
            })
        }, function (error) {
        }, options);


    });

    $('[data-role="show-friend"]').click(function () {
        $.popup('.popup-friend');
    });
    $(document).on('opened', '.popup-friend', function () {
        $('#slider').atFriend();
    });


    $('[data-role="show-product"]').click(function () {
        $.popup('.popup-product');
    });

    $('[data-role="choose-crowd"]').click(function () {
        $.popup('.popup-crowd');
    });
    $(document).on('opened', '.popup-crowd', function () {
        $('.crowd-list').showCrowd();
    });

    $(document).on('opened', '.popup-product', function () {
        $('.oneProduct').showMall();
    });

    var get_crowd_id = sessionStorage.getItem('crowd_id');
    var get_crowd_title = sessionStorage.getItem('crowd_title');
    if (get_crowd_id) {
        $('[data-role="choose-crowd"]').html(get_crowd_title);
        $('#crowd_id').val(get_crowd_id);
    }
});
$.fn.showCrowd = function (option) {
    var defaults = {};
    option = $.extend(defaults, option);
    var $container = $(this);
    if ($container.find('ul li').length == '1') {
        $.showIndicator();
        $.get(U('weibo/index/getCrowd'), {}, function (data) {
            var li = '';
            if (data != 'error') {
                for (var i in data) {
                    var t = data[i];
                    li += '<li class="close-popup" data-role="set-crowd" data-id="' + t.id + '" >' + t.title + '<i class="iconfont icon-iconfontduigou fr hidden"></i></li>';
                }
            }
            $container.find('ul').append(li);

            $('[data-role="set-crowd"]').click(function () {
                var $this = $(this);
                var id = $this.attr('data-id');
                $('.crowd-list ul li').find('i').addClass('hidden');
                $this.find('i').removeClass('hidden');
                $('#crowd_id').val(id);
                if (id != '0') {
                    $('[data-role="choose-crowd"]').text($this.text().textMore(6));
                } else {
                    $('[data-role="choose-crowd"]').text('全站动态');
                }
            });

            $.hideIndicator();
        })
    }
};


$.fn.showMall = function (options) {
    var defaults = {};
    option = $.extend(defaults, options);
    var $container = $(this);
    if ($container.find('li').length == '1') {
        $.showIndicator();
        $.get(U('mall/index/getGoods'), {}, function (data) {
            if (data.length == 0) {
                $container.html('<div style="text-align: center">暂无商品</div>');
            } else {
                var li = '';
                if (data != 'error') {
                    for (var i in data) {
                        var t = data[i];
                        li += '<li class="product" data-id="' + t.id + '" data-role="set-goods">' +
                            '<div class="left">' +
                            '<div class="imgWrap">' +
                            '<img src="' + t.img + '" alt="">' +
                            '</div>' +
                            '<p class="textMore">' + t.name + '</p>' +
                            '</div>' +
                            '<div class="right">￥' + t.price + '元</div>' +
                            '<input class="goods-intro" type="hidden" value="' + t.instro + '"/>' +
                            '</li>';
                    }
                }
                $container.append(li);
                $('[data-role="set-goods"]').click(function () {
                    var $this = $(this);
                    var id = $this.attr('data-id');
                    var img = $this.find('img').attr('src');
                    var title = $this.find('.textMore').html();
                    var price = $this.find('div.right').html();
                    var intro = $this.find('input.goods-intro').val();
                    console.log(intro);
                    $.modal({
                        afterText: '<div class="oneDetail"><div class="infoWrap">' +
                        '<div class="cover"><img src="' + img + '" alt=""></div></div>'
                        + '<p>' + title + '</p><p>' + price + '</p>'
                        + '<div class="moreInfo">' + intro + '</div></div>',
                        buttons: [
                            {
                                text: '关闭',
                                bold: true,
                                onClick: function () {
                                    //$.popup('.popup-product');
                                }
                            },
                            {
                                text: '插入',
                                bold: true,
                                onClick: function () {
                                    $('.send-bottom').hide();
                                    $('.send-bottom.product').show();
                                    var h = screen.width / 10 * 4 + 5;
                                    $('.send-bottom.product').css('height', h);
                                    $('.bar-footer').animate({bottom: h + 'px'}, 100);
                                    var oneProduct = '<div class="onePt"><div class="oneWrap"><div class="oneCover">' +
                                        '<img src="' + img + '" alt="">' +
                                        '</div><div class="oneName"><p class="textMore">' + title + '</p><p>' + price + '</p></div></div> <p style="color: #999;" data-role="delete-product">删除</p></div>';
                                    $('.send-bottom.product').append(oneProduct);
                                    $('#goods_id').val(id);

                                    $('[data-role="delete-product"]').unbind('click');
                                    $('[data-role="delete-product"]').click(function () {
                                        $('.send-bottom.product').html('');
                                        $('.send-bottom').hide();
                                        $('.bar-footer').animate({bottom: 0}, 100);
                                        $('#goods_id').val(id, '');
                                    });
                                }
                            }
                        ]
                    });
                });
            }
            $.hideIndicator();

        })
    }
};

$.fn.atFriend = function (option) {
    var defaults = {
        items: []
    };
    option = $.extend(defaults, option);
    var $slider = $(this);
    var $list = $slider.find('.slider-list');
    if ($list.hasClass('has-li')) {
        return false;
    }

    $.showIndicator();
    $.get(U('weibo/index/getFriend'), {}, function (friends) {

        if (typeof friends == 'string') {
            if (friends == 'error') {
                $.toast('请登录');
            }
            $list.find('ul').html('<div style="text-align: center">暂无好友</div>');
        } else {


            var li = '';
            $.each(friends, function (n, value) {

                option.items.push(n);
                li += '<li id="' + n + '"><a name="' + n + '" class="slider-title">' + n + '</a><ul>';
                var c_ul = '';
                for (var i in value) {
                    var t = value[i];
                    c_ul += '<li><a class="close-popup" data-role="at_someone" data-text="[at:' + t.uid + ']" href="javascript:">' + t.nickname + '</a></li>';
                }
                li += c_ul + '</ul></li>';
            });
            $list.find('ul').html(li);
            $list.addClass('has-li');

            $slider.addClass('slider');
            $list.find('li').eq(0).addClass('selected');
            $slider.append('<div class="slider-right"><ul></ul></div>');
            var $right = $slider.find('.slider-right');
            for (var i in option.items) {
                var t = option.items[i];
                $right.find('ul').append("<li><a alt='#" + t + "'>" + t + "</a></li>");
            }
            var $par = $slider.parent();
            var par_height = $par.height();
            $right.find('li a').css('line-height', par_height / 30 + 'px');
            $right.css('margin-top', par_height / 10);
            $list.css('height', par_height);
            $right.css('height', par_height);

            $('[data-role="at_someone"]').click(function () {
                var $this = $(this);
                $('.editor').append('<a class="edit-at" data-text="' + $this.attr('data-text') + '" href="javascript:">@' + $this.text() + ' </a>');
            });

            $right.find('a').click(function (event) {
                var $this = $(this);
                var target = $this.attr('alt');
                var cOffset = $list.offset().top;
                var tOffset = $list.find(target).offset().top;
                var height = $right.height();
                var pScroll = (tOffset - cOffset) - height / 8;
                $list.find('li').removeClass('selected');
                $(target).addClass('selected');
                $list.scrollTo({toT: $list.scrollTop() + pScroll})
            });
        }
        $.hideIndicator();
    });

};


$.fn.scrollTo = function (options) {
    var defaults = {
        toT: 0,    //滚动目标位置
        durTime: 500,  //过渡动画时间
        delay: 30,     //定时器时间
        callback: null   //回调函数
    };
    var opts = $.extend(defaults, options),
        timer = null,
        _this = this,
        curTop = _this.scrollTop(),//滚动条当前的位置
        subTop = opts.toT - curTop,    //滚动条目标位置和当前位置的差值
        index = 0,
        dur = Math.round(opts.durTime / opts.delay);
    var smooth_scroll = function (t) {
        index++;
        var per = Math.round(subTop / dur);
        if (index >= dur) {
            _this.scrollTop(t);
            window.clearInterval(timer);
            if (opts.callback && typeof opts.callback == 'function') {
                opts.callback();
            }
            return;
        } else {
            _this.scrollTop(curTop + index * per);
        }
    };
    timer = window.setInterval(function () {
        smooth_scroll(opts.toT);
    }, opts.delay);
    return _this;
};



