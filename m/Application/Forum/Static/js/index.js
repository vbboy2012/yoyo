$(function () {
    $("[data-role='search-ul']").addClass('hid-none') ;
});
$("[data-role='enter']").keydown(function (event) {
    //按下回车后
    if(event.keyCode==13){
        $("[data-role='search-ul']").removeClass('hid-none') ;
        $("[data-role='display-ul']").addClass('hid-none') ;
        $("[data-role='myFollow']").addClass('hid-none') ;
        $("#tab").addClass('hid-none') ;
        $("[data-role='forum-sort-title']").addClass('hid-none') ;
        $("[data-role='forum-search']").removeClass('hid-none') ;
        var url=U('Forum/Index/index');
        $.post(url,{condition:$("[data-role='enter']").val()},function (res) {
            if(res.length>0){
                $("[data-role='search-ul']").empty();
                for(var i=0;i<res.length;i++){
                    $("[data-role='search-ul']").append("<li>"+ "<a class='myLink'>"+"<div class='fCover'>"+
                        "<img class='myImg' alt='板块封面'>"+"</div>"+"<div class='fInfo'>"+" <p class='fName nameF'>23</p>"+
                        "<p class='fIntro introF textMore'></p>"+"</div>"+"</a></li>"
                    );
                    $(".nameF").eq(i).text(res[i].title);
                    $(".introF").eq(i).text(res[i].description);
                    $(".myImg").eq(i).attr('src',res[i].logo);
                    $(".myLink").eq(i).attr('href',res[i].id);
                }
            }
            else {
                $("[data-role='search-ul']").empty();
                $.toast("暂无搜索结果");
            }
        })
    }
    //退格事件
    if(event.keyCode==8){
        if($("[data-role='enter']").val().length==1){
            $("[data-role='search-ul']").addClass('hid-none') ;
            $("[data-role='display-ul']").removeClass('hid-none') ;
            $("[data-role='myFollow']").removeClass('hid-none') ;
            $("#tab").removeClass('hid-none') ;
            $("[data-role='forum-sort-title']").removeClass('hid-none') ;
            $("[data-role='forum-search']").addClass('hid-none') ;
        }
    }
});