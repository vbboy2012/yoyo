/**
 * Created by 王杰 on 2017/1/20.
 */
$(function(){
    var page = 1;
    $('#search').keyup(function (e) {
        if (e.which == 13 || e.which == 10) {
            var $container = $('.allPeople');
            var $this = $(this);
            var keyword = $this.val();
            if (keyword == '') {

            } else {
                var li = '<p class="mtTitle">全部会员</p>';
                $.get(U('Weibo/Index/searchPeople'), {
                    keywords: keyword
                }, function (res) {
                    if (res == 'none') {
                        li += '<div style="text-align: center;padding-top: 10px;">暂无此人</div>'
                    } else {
                        for (var i in res) {
                            var t = res[i];
                            console.log(t);
                            li += '<a href="'+ t.user.space_mob_url+'" external>'+
                                '<div class="flexDiv mtFlex">'+
                                '<div class="flexLeft">'+
                                '<div class="leftDiv mCover">'+
                                '<img src="'+ t.user.avatar128 +'" alt="'+ t.nickname +'">'+
                                '</div>'+
                                '<div class="rightDiv mInfo">'+
                                '<p class="mName textMore">'+ t.nickname+ '<span class="identity">'+ t.flag +'</span></p>'+
                                '<p class="mIntro textMore">'+ t.signature +'</p>'+
                                '</div>'+
                                '</div>'+
                                '<div class="flexRight"><a class="mButton do-active" href="javascript:" data-uid="'+ t.uid +'" data-value="'+ t.is_follow +'">'+ t.follow_status +'</a></div>'+
                                '</div>'+
                                '</a>';
                        }
                    }
                    $container.html('');
                    $container.append(li);
                }, 'json');
            }
        }
    });

    var loadMore = $('[data-role="loadMore"]');
    loadMore.click(function(){
        var $this = $(this);
        var url = U('Weibo/Index/people');
        $.get(url,{page:++page,ajax:1},function(res){
            console.log(res);
            console.log(res == '');
            if (res.html != '') {
                $('.allPeople').append(res.html);
            } else {
                $this.remove();
                $.toast('没有更多了~');
            }
        })
    });
});