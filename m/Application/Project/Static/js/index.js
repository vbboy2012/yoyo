/**
 * Created by Administrator on 2017/06/06.
 */
var flag=1;

//加载更多刷新操作
var loading = false;
var maxItems = 0;
var page=0;
var type='private';
//默认加载获取总条数
var total=$('[data-role="private"]').attr('data-total');
maxItems=total;
//获取个人项目
$('[data-role="private"]').click(function () {
    var total=$(this).attr('data-total');
    maxItems=total;
    type="private";
    $(this).addClass('h_active');
    $('[data-role="public"]').removeClass('h_active');
    $('[data-role="cache"]').removeClass('box-active');
    $('#tab li').remove();
    page=0;
    addItems(type,lastIndex);
});
//获取
$('[data-role="public"]').click(function () {
    var total=$(this).attr('data-total');
    maxItems=total;
    type="public";
    $(this).addClass('h_active');
    $('[data-role="private"]').removeClass('h_active');
    $('[data-role="cache"]').addClass('box-active');
    $('.down-menu').css('display','none');
    flag=1;
    $('#tab li').remove();
    page=0;
    addItems(type,lastIndex);
});

//首次加载数据
function addItems(type, lastIndex) {
    $('.infinite-scroll-preloader').css('display','');
    var html = '';
    $.ajax( {
        url:U('Project/index/loadIndex'),
        data:{
            page:++page,
            type:type,
        },
        type:'post',
        cache:false,
        dataType:'json',
        async:true,
        success:function(res) {
            if(res.status ==true){
                $('#tab').append(res.data);
                lastIndex = $('#tab li').length;
                if (lastIndex<10){
                    console.log(111)
                    $('.infinite-scroll-preloader').css('display','none');
                }
                if (res.data==''){
                    $('.infinite-scroll-preloader').css('display','none');
                }
            }
            else{
            }
        },
        error : function() {
            $.toast('数据加载异常！')
        }
    });
}
addItems(type, 0);
//分页条数每页十条 对应U('Forum/index/commonForumData') 里面10条
var lastIndex = 10;
$(document).on('infinite', '.infinite-scroll',function() {
    // 如果正在加载，则退出
    if (loading) return;

    // 设置flag
    loading = true;

    setTimeout(function() {
        loading = false;

        if (lastIndex >= maxItems) {
            $.detachInfiniteScroll($('.infinite-scroll'));
            $('.infinite-scroll-preloader').remove();
            return;
        }
        addItems(type, lastIndex);
        lastIndex = $('#tab li').length;
    }, 1000);
});
//弹窗
function modal(id,count) {
    $.post(U('Project/index/schedule'),{id:id},function (res) {
        if(res){
            var cModal = $.modal({
                zdywrap: 'signWrap',
                afterText: '<div class="check"><a href="'+U('Project/index/schedule',['id',id])+'" style="color:#fff">查看全部</a></div>' +
                '<div class="mPlan">' +
                '<div class="cover"><img src="'+res.cover+'" alt="封面"></div> ' +
                '<div class="nwInfo"> <a href="" class="cont">'+res.title+'<span class="text-color text-font">'+res.progress+'%</span> </a> ' +
                '<div class="textMore" style="display: flex">开发阶段</div> ' +
                '<div class="progress">' +
                '<div class="am-progress"> ' +
                '<div class="am-progress-bar" style="width: '+res.progress+'%"></div></div> </div> </div></div>' +
                '<div class="mt" data-distance="100" data-ptr-distance="55">'+
                '<div class="tabs tabs-height">'+
                '<ul id="tab1" class="tab active list" data-role="id"><include file="_progress" /></ul></div>'+
                '<div class="close" onclick="closemodal()"><i class="iconfont icon-guanbi"></i></div> ',
            })
            refreshData('Project/index/loadSchedule',count,'#tab1',{id:id});
        }
    })


}
function closemodal() {
    $.closeModal();
};
//订阅
$('[data-cache="cache"]').click(function () {
    if(flag){
        $('.down-menu').css('display','block');
        flag=0;
    }else {
        $('.down-menu').css('display','none');
        flag=1;
    }

});


//首次登录时订阅弹窗
function modal_two(userCount) {
    if(!userCount){
        $.modal({
            zdywrap: 'signWrap',
            title:'<div class="modal-title">收到一条订阅提醒</div>',
            afterText:'<div class="modal-content">开启订阅可以第一时间了解到公共项目的进度，方便您对我们的产品更深入的了解</div>',
            buttons:[
                {
                    text:'嫌弃 :(',
                    onClick:function() {
                        var type='false';
                        subscribe(type);
                    }
                },
                {
                    text:'订阅 :)',
                    onClick:function() {
                        var type='true';
                        subscribe(type);
                    }
                },
            ]
        });
    }
}

function subscribe(type) {
    $.post('Project/index/setuser',{type:type},function (res) {
        if(res.status==1){
            $.toast(res.info);
        }else{
            $.closeModal();
        }

    })
}

//订阅逻辑
$('[data-role="follow"]').click(function () {
    var $this = $(this);
    var follow=$(this).attr('data-follow')?$(this).attr('data-follow'):0;
    if (follow==1){
        $.post(U('Project/index/follow'),{follow:follow},function (res) {
            if (res.status=1){
                $('[data-role="changeClass"]').text('订阅');
                $this.attr("data-follow",0);
                $.toast(res.info)
            }else{
                $.toast(res.info)
            }
        })
    }else{
        $.post(U('Project/index/follow'),{follow:follow},function (res) {
            if (res.status=1){
                $('[data-role="changeClass"]').text('已订阅');
                $this.attr("data-follow",1);
                $.toast(res.info)
            }else{
                $.toast(res.info)
            }
        })
    }
})
