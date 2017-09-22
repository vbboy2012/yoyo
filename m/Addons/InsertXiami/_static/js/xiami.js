/**
 * Created by Administrator on 15-4-30.
 */


var bind_search_music = function () {
    $('[data-role="search_music"]').unbind('click');
    $('[data-role="search_music"]').click(function () {
        xiami.search_music(1);
    })
}
document.onkeydown=function(){
    if (event.keyCode == 13) {
        xiami.search_music(1);
    }
}
var bind_add_music = function () {
    $('[data-role="add_music"]').unbind('click');
    $('[data-role="add_music"]').click(function () {
        var $this = $(this);
        var song_id = $this.attr('data-id');
        var song_title = $this.attr('data-title');
        var song_author = $this.attr('data-author');
        var song_cover = $this.attr('data-cover');
        var song_src=$this.attr('data-src');
        var $hook_show = $this.closest('#hook_show');
        $hook_show.find('input[name="title"]').val(song_title);
        $hook_show.find('input[name="id"]').val(song_id);
        $hook_show.find('input[name="author"]').val(song_author);
        $hook_show.find('input[name="cover"]').val(song_cover);
        var $content = $this.closest('.weibo_post_box').find('#weibo_content');
        $content.val(' #音乐分享# ' + song_title + '--' + song_author);
    })
}


var xiami = {

    html: '<div class="xiami">' +
        '<a onclick="xiami.close($(this))" class="close-btn"><i class="icon icon-remove"></i></a><div id="music_input"><div class="v-wrap">' +
        '<input class="v-input" type="text" id="xiami_key" placeholder="输入歌曲或歌手"/>' +
        '<div  class="s-btn" data-role="search_music"><i class="icon icon-search"></i></div>' +
        '</div></div><div class="xiami_s_r"></div><input name="feed_type" value="xiami" type="hidden">' +
        '<input class="extra" name="title" value="" type="hidden"><input name="id" class="extra" value="" type="hidden"><input name="author" class="extra" value="" type="hidden">' +
        '<input name="cover" class="extra" value="" type="hidden"></div> ',

    show_box: function () {
        $('#hook_show').html(this.html);

        bind_search_music();
    },
    search_music: function (page) {
        toast.showLoading();
        var url = $('#insert_xiami_search_url').val();
        $.post(url, {page: page, key: $("#xiami_key").val()}, function (res) {
            if (res['status'] != -1) {
                if (res['status'] == 1) {
                    var html = '当前第' + page + '页';
                    for (var e in res.data) {
                        var song = res.data[e];
                        html += '<li><a href="javascript:" data-role="add_music" data-id="' + song['id'] + '" data-title="' + song['title'] + '" data-author="' + song['author'] + '" data-cover="' + song['cover'] + '" >' + song['title'] + '--' + song['author'] + '</a></li> ';
                    }
                } else {
                    html = '<div>没有数据</div>';
                }
                html += '<div class="pager"> <li><a onclick="xiami.search_music(1);">第一页</a></li> ';
                if (page != 1) {
                    html += '<li><a onclick="xiami.search_music(' + (parseInt(page) - 1) + ');">上一页</a></li>';
                }
                if (res.next != 0) {
                    html += '<li><a onclick="xiami.search_music(' + (parseInt(page) + 1) + ');">下一页</a></li>';
                }
                html += '</div>';
            }
            else {
                html = '<div>没有数据</div>';
            }
            $(".xiami_s_r").show().html(html);
            bind_add_music();
            toast.hideLoading();
        })
    },
    close:function(obj){
        if(confirm('是否确定取消发布虾米音乐？')){
            obj.parents('#hook_show').html('');
            clear_weibo()
        }

    }


}