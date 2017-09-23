/**
 * Created by 駿濤 on 2016/12/20 0020.
 */


$(function () {
    var ori_height = window.innerHeight;
    var $editor = $('.editor');
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
        interval = setInterval(function () {
            selection = getSelection();
            $range = selection.getRangeAt(0);
            var textNode = $range.startContainer;
            var rangeStartOffset = $range.startOffset;
            save_range = {
                textNode: textNode,
                rangeStartOffset: rangeStartOffset
            };
        }, 500);
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
        var text = html.replace(/\<img.*?data\-title\="(.*?)" .*?>/g, "$1");
        var music_box = $('[data-type="xiami-music"]');
        var extra = music_box.find('.extra').serialize();
        var type = $('input[name="type"]').val() ;
        if ( type != 'xiami') {
            extra = '' ;
        }
        text = emojione.toShort(text);
        text = text.replace(/\<a.*?data\-text\="(.*?)" .*?<\/a>/g, "$1");
        text = text.replace(' ', '/nb');
        text = text.replace(/&nbsp;/g, '/nb');
        text = text.replace(/\<br>/g, '/br');
        text = text.replace(/\<div>(.*?)<\/div>/g, '/br$1');
        $.post(U('weibo/index/sendWeibo'), {
            content: text,
            crowd_id: $('#crowd_id').val(),
            goods_id: $('#goods_id').val(),
            type:type,
            extra: extra,
            data: {
                attach_ids: $('[name="image"]').val(),
                location: $('#location').val(),
                location_text: $('.location-text').attr('data-text'),

            }
        }, function (res) {
            if (res.status==0){
                $.toast(res.info);
                $('[data-role="submit-weibo"]').attr("disabled",false);
            }
            if (res.status) {
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
                if (is_weixin()&&is_android()) {
                    $('.send-bottom.image').addClass('image_uploader') ;
                }else{
                    if ($('.send-bottom.image').hasClass('image_uploader') == false) {
                        $('.send-bottom.image').html('') ;
                        $('.send-bottom.image').uploadImage();
                    }
                }
                break;
            case 'emoji':
                get_emoji();
                break;
        }
        var h = screen.width / 10 * 4 + 5;
        $('.send-bottom.' + type).css('height', h);
        $this.closest('.bar-footer').animate({bottom: h + 'px'}, 100);
    });
    var is_weixin = function() {
        var ua = navigator.userAgent.toLowerCase();
        if(ua.match(/MicroMessenger/i)=="micromessenger") {
            return true;
        } else {
            return false;
        }
    };
    var is_android = function(){
        var u = navigator.userAgent;
        if(u.indexOf('Android') > -1 || u.indexOf('Linux') > -1){
            return true;
        } else {
            return false;
        }
    };
    var bind_emoji = function () {
        var $emojione = $('.emojione');
        $emojione.unbind('click');
        $emojione.click(function () {
            if (save_range) {
                $range.setStart(save_range.textNode, save_range.rangeStartOffset);
                $range.setEnd(save_range.textNode, save_range.rangeStartOffset);
            }
            var $this = $(this);
            var img = '<img data-title="' + $this.attr('title') + '" src="' + $this.attr('src') + '" style="width: 24px;height: 24px" class="img-disable"/>'
            if ($range) {
                $range.insertNode($(img)[0]);
                save_range = {
                    textNode: $range.endContainer,
                    rangeStartOffset: $range.endOffset
                };
            } else {
                $editor.append(img);
            }
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
                var $obj =  $('.location-text');
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

    $('[data-role="show-huati"]').click(function () {
        $.popup('.popup-huati');
    });
    $(document).on('opened', '.popup-huati', function () {
        $('#slider2').atHuati();
    });

    $('[data-role="show-music"]').click(function () {
        $.popup('.popup-music');
    });
    $(document).on('opened', '.popup-music', function () {
        $('#slider2').atMusic();
    });

    $('[data-role="choose-crowd"]').click(function () {
        $.popup('.popup-crowd');
    });
    $(document).on('opened', '.popup-crowd', function () {
        $('.crowd-list').showCrowd();
    });


    var get_crowd_id = sessionStorage.getItem('crowd_id');
    var get_crowd_title = sessionStorage.getItem('crowd_title');
    if (get_crowd_id) {
        $('[data-role="choose-crowd"]').html(get_crowd_title);
        $('#crowd_id').val(get_crowd_id);
    }
    add_img();
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
        // console.log(friends);
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

$.fn.atHuati = function (option) {
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
    $.get(U('weibo/Topic/getRecommendTopicList'), {}, function (huati) {
        if (!huati.status) {
            // if (huati.status == -1) {
            //     $.toast('请登录');
            // }
            $list.find('ul').html('<div style="text-align: center">暂无话题</div>');
        } else {
            $list.prepend("<h4 style='margin: 0;padding-left:10px ;color: #ccc;'>推荐话题</h4>");
            var li = '';
            var huatiname='';
            console.log(huati.data) ;
            $.each(huati.data, function (n, value) {
                li += '<li id="' + value['id'] + '"><a name="' + value['id'] + '" class="slider-title huati-item">#' +  value['name'] + '#</a></li>';
            });
            var huatiname='';
            $list.find('ul').append(li);
            $list.addClass('has-li');
            $(document).on("click",".huati-item",function () {
                huatiname =$("#weibo_content").html()+ $(this).html();
                $(".popup-huati").hide();
                $(".popup-overlay").hide();
                $("#weibo_content").html(huatiname);
            });
            $(document).on("click",".ok-popup",function () {
                huatiname =$("#weibo_content").html() + "#"+ $("#intext").val()+"#";
                $(".popup-huati").hide();
                $(".popup-overlay").hide();
                $("#weibo_content").html(huatiname);
            })
        }
        $.hideIndicator();
    });

};
// 动态发布音乐时调用的js
$.fn.atMusic = function (option) {
    var url =  $('[data-role="music_url"]').val();
    var page =  $('[data-role="music_page"]').val();
    var nextPage = '' ;
    $('[data-role="get_music"]').on('click',function(){
        $.showIndicator();
        search_music(page) ;
        $.hideIndicator();
    });
    $(document).on('click', "[data-role='change_page']", function(){
        nextPage = $(this).attr('data-value') ;
        $.showIndicator();
        search_music(nextPage) ;
        $.hideIndicator();
    });
    $(document).on('click', '[data-role="add_music"]', function () {
        var $this = $(this);
        var song_id = $this.attr('data-id');
        var song_title = $this.attr('data-title');
        var song_author = $this.attr('data-author');
        var song_cover = $this.attr('data-cover');
        var song_src=$this.attr('data-src');
        var $music_box = $('[data-type="xiami-music"]');
        $music_box.find('input[name="title"]').val(song_title);
        $music_box.find('input[name="id"]').val(song_id);
        $music_box.find('input[name="author"]').val(song_author);
        $music_box.find('input[name="cover"]').val(song_cover);
        var $content = $('#weibo_content');
        $content.html(' #音乐分享# ' + song_title + '--' + song_author);
        $("#weibotype").val('xiami') ;
        $(".popup-music").hide();
        $(".popup-overlay").hide();
        $('[data-role="music_page"]').val('1') ;
        $('input[name="image"]').val('') ;
        $('.img').remove();
    });
    function search_music (page) {
        var key = $('[data-role="music-key"]').val() ;
        $('[data-role="music_page"]').val(page) ;

        $.post(url, {page: page, key: key}, function (res) {
            if (res['status'] != -1) {
                if (res['status'] == 1) {
                    var html = '<h4 style="margin: 0;padding-left:10px ;color: #ccc;">当前第' + page + '页</h4><ul class="music-list">';
                    for (var e in res.data) {
                        var song = res.data[e];
                        html += '<li><a class="slider-title huati-item" href="javascript:" data-role="add_music" data-id="' + song['id'] + '" data-title="' + song['title'] + '" data-author="' + song['author'] + '" data-cover="' + song['cover'] + '" >' + song['title'] + '--' + song['author'] + '</a></li> ';
                    }
                } else {
                    html = '<div class="music-list">没有数据</div>';
                }
                html += '</ul><div class="pager"> <li><button class="button button-small button-fill"  data-value="1" data-role="change_page">第一页</button></li> ';
                if (page != 1) {
                    html += '<li><button class="button button-small button-fill" data-value="' + (parseInt(page) - 1) + '" data-role="change_page">上一页</button></li>';
                } else {
                    html += '<li><button class="button button-small">上一页</button></li>';
                }
                if (res.next != 0) {
                    html += '<li><button class="button button-small button-fill"  data-value="' + (parseInt(page) + 1) + '" data-role="change_page">下一页</button></li>';
                }
                html += '</div>';
            }
            else {
                html = '<div>没有数据</div>';
            }
            $(".popup-huati").hide();
            $(".popup-overlay").hide();
            $(".slider-list").html(html);
        });
    };


}

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
//移除上传操作
function removeLi(li,file_id) {
    console.log(li)
    upAttachVal('remove', file_id, $('#image'))
    $(li).parent('.waitbox').remove();
}
//新上传图片
function add_img() {
    $('#weibotype').val('image');
    var filechooser = document.getElementById("choose");
    $("#upload").on("click", function () {
        filechooser.click();
    });
    filechooser.onchange = function () {
        if (!this.files.length) return;
        var files = Array.prototype.slice.call(this.files);
        if (files.length > 9) {
            alert("最多同时只可上传9张图片");
            return;
        }
        files.forEach(function (files, i) {
            if (!/\/(?:jpeg|png|gif)/i.test(files.type)){
                toast.error('上传图片格式不符！');
            }
            var div = ' <div class="waitbox loadingBox img-btn-sit">\
                            <img src= ' + _LOADING_ + '> \
                            </div> ';
            $('.add-img-btn').before(div);
            lrz(files, {
                width: 1200,
                height: 900,
                before: function () {
                    console.log('压缩开始');
                },
                fail: function (err) {
                    console.error(err);
                },
                always: function () {
                    console.log('压缩结束');
                },
                done: function (results) {
                    // 你需要的数据都在这里，可以以字符串的形式传送base64给服务端转存为图片。
                    var data=results.base64;
                    upload(data);
                }
            });
        })
    }
}
//图片上传，返回id ,地址
function upload(data) {
    var dataUrl = U('Core/File/uploadPictureBase64');
    $.post(dataUrl, {data: data}, function (msg) {
        if (msg.status == 1) {
            var ids = $('#img_ids').val();
            upAttachVal('add', msg.id, $('#image'));
            //上传成功显示图片
            var div = ' <div class="waitbox img-btn-sit">\
                    <img src= ' + msg.path + '> \
                    <a class="del-img-btn" onclick="removeLi(this, '+msg.id+')" data-id="26"><i class="iconfont icon-guanbi2"></i></a>\
                    </div> ';
            $('.loadingBox').remove();
            $('.add-img-btn').before(div);
        } else {
            toast.error(msg.info);
        }
    }, 'json')
}
//拼接图片ID
function upAttachVal(type, attachId, obj) {
    var $attach_ids = obj;
    var attachVal = $attach_ids.val();
    var attachArr = attachVal.split(',');
    var newArr = [];

    for (var i in attachArr) {
        if (attachArr[i] !== '' && attachArr[i] !== attachId.toString()) {
            newArr.push(attachArr[i]);
        }
    }
    type === 'add' && newArr.push(attachId);
    if (newArr.length <= 9) {
        $attach_ids.val(newArr.join(','));
        return newArr;
    } else {
        return false;
    }

}

//单图上传
function add_one_img() {
    var filechooser = document.getElementById("chooseOne");
    $("#upload").on("click", function () {
        filechooser.click();
    })
    filechooser.onchange = function () {
        if (!this.files.length) return;
        var files = Array.prototype.slice.call(this.files);
        if (files.length > 9) {
            alert("最多同时只可上传9张图片");
            return;
        }
        files.forEach(function (files, i) {
            lrz(files, {
                width: 1200,
                height: 900,
                before: function () {
                    console.log('压缩开始');
                },
                fail: function (err) {
                    console.error(err);
                },
                always: function () {
                    console.log('压缩结束');
                },
                done: function (results) {
                    // 你需要的数据都在这里，可以以字符串的形式传送base64给服务端转存为图片。
                    var data=results.base64;
                    upload(data);
                }
            });
        })
    }
    //图片上传，返回id ,地址
    function upload(data) {
        console.log(data);
        var dataUrl = U('Core/File/uploadPictureBase64');
        $.post(dataUrl, {data: data}, function (msg) {
            if (msg.status == 1) {
                console.log(msg);
                //上传成功显示图片
                var ids = $('#one_img_id').val(msg.id);
                if (!msg.id == null) {
                    $('.show_cover').hide();
                } else {
                    $('.show_cover').show();
                }
                $("#cover_url").html('');
                $("#cover_url").html('<img src="' + msg.path + '"style="width:72px;height:72px"  data-role="issue_cover" >');
            } else {
                toast.error(msg.info);
            }
        }, 'json')
    }
}



