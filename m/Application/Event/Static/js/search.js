/**
 * Created by Administrator on 2017/5/23.
 */
$(function () {
    $('.infinite-scroll-preloader').css('display', 'none') ;
    var change=$("[data-role='hotChange']");
    var page=2;
    $("[data-role='change']").click(function () {
        $('.icon-shuaxin').addClass('rotate');
        var url=U('Event/Index/search');
        $.post(url,{page:page},function (res) {
            if(res.status==1){
                change.empty();
                var len = res.data.length ;
                for(var i=0;i<len;i++){
                    change.append("<a class='myId'>"+"<span class='hotContent'></span>"+"</a>");
                    $(".hotContent").eq(i).text(res.data[i]['title']);
                    $(".myId").eq(i).attr("href",res.data[i]['url'])
                }
                page++;
                //如果是最后一组了就从第一组开始
                if(res.is==0){
                    page=2;
                }
            }
            setTimeout(function () {
                $('.icon-shuaxin').removeClass('rotate');
            },500)

        })
    });

    //搜索功能
    $("#search").keydown(function (event) {
        if(event.keyCode==13){
            var content=$("#search").val();
            if(content==""){
                $.toast("搜索内容不能为空");
                return;
            }
            $('.infinite-scroll-preloader').css('display', 'block') ;
            doSearch(content, '') ;
        }
        if(event.keyCode==8){
            if($("#search").val().length==1){
                $("[data-role='myList']").empty();
                $("[data-role='hotChange']").css("display","block");
                $("[data-role='change']").css("display","block");
                $("[data-role='hotSearch']").text("热门搜索");
                $(".history").eq(0).css("display","flex");
                $("[data-role='search-head']").css("display","flex");
                $(".e-list").remove();
            }
        }
    });

})
//删除一条搜索记录
function deleteOneSearchHistory (id){
    var url=U('Event/Index/delete');
    var id=id;
    $.post(url,{id:id},function (res) {
        if(res.status=="1"){
            $("#myHistory").empty();
            $("#myHistory").append(res.html);
            if($("#myHistory li").length==1){
                $("#myHistory").empty();
            }
        }
    })
};

//清空历史记录
function allclear() {
    if($("#myHistory a").length<1) return ;
    console.log($("#myHistory a").length) ;
    var url=U('Event/Index/allDelete');
    $.post(url,{},function (res) {
        if(res.status=="1"){
            $("#myHistory").empty();
        }
    })
}

function historyClick(content) {
    $("#search").val(content);
    var url=U('Event/Index/allSearch');
    $('.infinite-scroll-preloader').css('display', 'block') ;
    doSearch(content, 'no') ;
}

function doSearch(content, is) {
    var url=U('Event/Index/allSearch');
    $.post(url,{title:content,is:is},function (res) {
        if(res.status=="1"){
            $("[data-role='myList']").empty();
            if(res.html=="none"){
                $("[data-role='event-list']").append(" <div class='noWrap'>"+"<p class='emojiText'>╭(╯^╰)╮</p>"+
                    "<p class='noState open-about'>暂无搜索结果</p>"+" </div>");
            }
            else{
                $("[data-role='event-list']").append(res.html);
            }
            $("[data-role='hotChange']").css("display","none");
            $("[data-role='change']").css("display","none");
            $("[data-role='hotSearch']").text("相关搜索");
            $(".history").eq(0).css("display","none");
            $("[data-role='search-head']").css("display","none");

            if(is != 'no'){
                $("#myHistory").empty();
                $("#myHistory").append(res.data);
            }
        }
        $('.infinite-scroll-preloader').css('display', 'none') ;
    })
}