/**
 * Created by Administrator on 15-4-30.
 */


var bind_search_video = function () {
    $('[data-role="search_video"]').unbind('click');
    $('[data-role="search_video"]').click(function () {
        video.search_video($(this));
    })

    $('#video_url').unbind('keypress');
    $('#video_url').keypress(function (e) {
        if ( e.which == 13 || e.which == 10) {
            $(this).closest('.video').find("[data-role='search_video']").click();
        }
    });

}

var video = {

    html: '<div class="video">' +
        '<a onclick="video.close($(this))" class="close-btn"><i class="icon icon-remove"></i></a>' +
        '<div id="music_input"><div class="v-wrap">'+
        '<input class="v-input" type="text" id="video_url" placeholder="视频网址(或flash地址)"/>' +
        '<div class="s-btn" data-role="search_video"><i class="icon icon-search"></i></div>' +
        '<div class="suppt-link"><span>支持</span><a href="http://www.youku.com/" target="_blank">优酷</a>、<a href="http://www.ku6.com/" target="_blank">酷6</a>、<a  href="http://video.sina.com.cn/" target="_blank">新浪</a>、<a href="http://www.tudou.com/" target="_blank">土豆网</a>、<a href="http://tv.sohu.com/" target="_blank">搜狐</a>、<a href="http://www.yinyuetai.com/" target="_blank">音悦台</a>、<a href="http://v.qq.com/" target="_blank">腾讯</a>、<a href="http://www.iqiyi.com/" target="_blank">爱奇艺</a>、<a href="http://www.bilibili.com/" target="_blank">哔哩哔哩</a></div>'+
        '</div></div>'+
        '<div class="video_s_r"></div><input name="feed_type" value="video" type="hidden">' +
        '<input name="title" class="extra" value="" type="hidden"><input name="video_url" class="extra" value="" type="hidden">' +
        '<input name="swf_src" class="extra" value="" type="hidden"><input name="img_url" class="extra" value="" type="hidden"> '+
        '</div>',

    show_box: function () {
        $('#hook_show').html(this.html);
        $('#video_url').focus();
        bind_search_video();
    },
    search_video: function ($this) {
        toast.showLoading();
        var url = $('#insert_video_search_url').val();
        var link = $("#video_url").val();
        $.post(url, {url:link }, function (res) {
            eval("var data=" + res);
            if (data.boolen == 1) {
                var $hook_show = $this.closest('#hook_show');
                var $content = $this.closest('.weibo_post_box').find('#weibo_content');
                $content.val(' #视频分享# ' + (data.is_swf==1?'':data.data.title) +' '+  (data.is_swf==1?'':link));
                $hook_show.find('input[name=title]').val(encodeURIComponent(data.data.title));
                $hook_show.find('input[name=swf_src]').val(encodeURIComponent(data.data.flash_url));
                $hook_show.find('input[name=img_url]').val(encodeURIComponent(data.data.img_url));
                $hook_show.find('input[name=video_url]').val( data.is_swf==1?'无':link);
                toast.success('搜索成功');
                $('.video_s_r').show().html('<div>'+ (data.is_swf==1?'':data.data.title)+'</div><embed src="' + data.data.flash_url + '" wmode="transparent" allowfullscreen="true" type="application/x-shockwave-flash" style="width: 100%;height:350px;"></embed>');
            } else {
                $('input[name=swf_src]').val('');
                toast.error(data.message);
            }
            toast.hideLoading();
        })
    },
    close:function(obj){
        if(confirm('是否确定取消发布视频？')){
            obj.parents('#hook_show').html('');
            clear_weibo()
        }
    }


}