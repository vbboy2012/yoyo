$(function () {
    $(document).on('click','.open-about', function () {
        $.popup('.popup-type');

    });
    $(document).on('click','.close-popup', function () {
        $.closeModal('.popup-type');

    });
    $(document).on('open', '.popup-about', function () {
        $('.floatIcon').removeClass('icon-xiangxiajiantou');
        $('.floatIcon').addClass('icon-xiangshangjiantou');
    });

    $(document).on('close', '.popup-about', function () {
        $('.floatIcon').removeClass('icon-xiangshangjiantou');
        $('.floatIcon').addClass('icon-xiangxiajiantou');
    });
    // 图片预览
    $('[data-role="open-cover"]').click(function () {
        myPhotoBrowserStandalone.open();
    });
    
    // 提问
    // 上传图片
    $('[data-role="add_cover"]').click(function () {
        $(this).parent().css('height', '120px');
        $(this).css('display', 'none') ;
        var add = $('.addCover') ;
        if (is_weixin()&&is_android()) {
            $('.img-list').css('display', 'inline-flex') ;
            add.addClass('image_uploader') ;
        }else{
            if (add.hasClass('image_uploader') == false) {
                add.html('') ;
                add.uploadImage({limit:1});
            }
        }
    });
    // 选择分类
    var crowdIdArr = [];
    var crowdTitleArr = [];
    $('.crowd_by_id').each(function(){
        crowdIdArr.push($(this).text());
    });
    $('.crowd_by_title').each(function(){
        crowdTitleArr.push($(this).text());
    });
    $('[data-role="chose_type"]').picker({
        formatValue:function(picker, value, displayValue){
            $('[data-role="crowd_type"]').val(value);
            return displayValue;
        },
        toolbarTemplate: '<header class="bar bar-nav">\
  <button class="button button-link pull-right close-picker">确定</button>\
  <h1 class="title">选择分类</h1>\
  </header>',
        cols: [
            {
                textAlign: 'center',
                values: crowdIdArr,
                displayValues: crowdTitleArr
            }
        ]
    });
    // 选择悬赏
    $('[data-role="reward"]').click(function () {
        var modal = $.modal({
            title: '请选择您的悬赏类型和数量',
            afterText:'<div class="formWrap"><select data-role="moneyType">\n' +
            '</select>' +
            '<input data-role="number" type="number" placeholder="数量"></div>',
            zdywrap:'rewardWrap',
            buttons: [
                {
                    text: '取消'
                },
                {
                    text: '确认',
                    bold: true,
                    onClick: function () {
                        var rewardType= $('[data-role="moneyType"]').val();
                        var  number= $('[data-role="number"]').val();
                        if(number==""){
                            $.toast("悬赏数量不能为空！");
                            return;
                        }
                        if(number<=0){
                            $.toast("请输入正确的悬赏金额！");
                            return;
                        }
                        $('[data-role="reward-type"]').attr('value',rewardType);
                        $('[data-role="reward-num"]').attr('value',number);
                    }
                }
            ]
        });
        addscore()
    })

    //下拉刷新
    var loading = false;
    var page=2;
    $(document).on('infinite', '.infinite-scroll',function() {
        //return;
        // 如果正在加载，则退出
        if($(".active").eq(0).attr("data-code")=="is"){
            return;
        }
        if (loading) return;

        // 设置flag
        loading = true;
        //判断用户选择哪个大类下的哪个分类
        var choiceId=$("#choice").attr("data-id");
        var url="Question/index/question";
        var id=$(".active").eq(0).attr("data-id");
        $('.infinite-scroll-preloader').css('display','block');
        $.post(url,{page:page,categoryId:id,choiceId:choiceId},function (mes) {
           if(mes.status==1){
               page++;
               $(".active").eq(1).find('ul').append(mes.data);
               $('.infinite-scroll-preloader').css('display','none');
               loading=false;
               if(mes.count<10){
                   $.toast("数据已全部加载！");
                   loading=false;
                   $(".active").eq(0).attr("data-code","is");
               }
           }
           else {
               $('.infinite-scroll-preloader').css('display','none');
               $.toast("数据已全部加载！");
               loading=false;
               $(".active").eq(0).attr("data-code","is");
           }


       });


    });
    //问题分类
    $(".tab-link").click(function () {
        var choiceId=$("#choice").attr("data-id");
        var url="Question/index/categoryClick";
        $.post(url,{categoryId:$(this).attr('data-id'),choiceId:choiceId},function (mes) {
              if(mes.status==1){
                  $(".active").eq(1).find('ul').empty().append(mes.data);
              }
              if(mes.status==0){
                  //$(".active").eq(1).find('ul').empty().append(" <p class='noMore'>该分类下暂无任何问题！</p>");
                  $(".active").eq(1).find('ul').empty().append(" <div class='noWrap'>"+"<p class='emojiText'>╭(╯^╰)╮</p>"+
                      "<p class='noState open-about'>该分类下暂无任何问题！</p>"+" </div>");
              }
        })
    });
    //全部问题/待回答分类
    $(".myType").click(function () {
        for (var i=0;i<$(".tab-link").length;i++){
            $(".tab-link").eq(i).attr("data-code","no")
        }
        var id=$(".active").eq(0).attr("data-id");
        var text=$.trim($(this).text());
        var url="Question/index/all";
        if(text=="全部问题"){
            $("#choice").empty().text("全部问题").append("<i class='iconfont icon-xiangxiajiantou floatIcon'></i>");
            $("#choice").attr("data-id","1");
            $("#type").attr("data-id","all");
        }
        if(text=="待回答"){
            $("#choice").empty().text("待回答").append("<i class='iconfont icon-xiangxiajiantou floatIcon'></i>");
            $("#choice").attr("data-id","2");
            $("#type").attr("data-id","wait");
        }
        if(text=="热门问题"){
            $("#choice").empty().text("热门问题").append("<i class='iconfont icon-xiangxiajiantou floatIcon'></i>");
            $("#choice").attr("data-id","3");
            $("#type").attr("data-id","hot");
        }
        if(text=="我的提问"){
            $("#choice").empty().text("我的提问").append("<i class='iconfont icon-xiangxiajiantou floatIcon'></i>");
            $("#choice").attr("data-id","4");
            $("#type").attr("data-id","my");
        }
        var choiceId=$("#choice").attr("data-id");
        $.post(url,{choiceId:choiceId,id:id},function (mes) {
            if(mes.status==-1){
                $.toast(mes.data);
            }
            else if(mes.status==1){
                $(".active").eq(1).find('ul').empty().append(mes.data);
            }
        });
    });
    //邀请回答逐个选择checkbox
    $("#askList li input").click(function () {
        if($(this).is(":checked")){
            $(this).addClass("myCheck");
            $("#already").text(parseInt($("#already").text())+1+" ");
            if($(".myCheck").length==$("#myCount").text()){
                $("#allChoice").prop("checked",true);
            }
        }
        else{
            $(this).removeClass("myCheck");
            $("#already").text(parseInt($("#already").text())-1+" ");
            if($(".myCheck").length!=$("#myCount").text()){
                $("#allChoice").prop("checked",false);
            }
        }

    });
    //全选/取消全选
    $("#allChoice").click(function () {
        if($("#allChoice").is(":checked")){
            $("#already").text($("#myCount").text()+" ");
            //全选
            $('input[name="check"]').prop("checked",true).addClass("myCheck");
        }
        else{
            $("#already").text('0 ');
            $("[name='check']").prop("checked",false).removeClass("myCheck");//取消全选
        }
    });
    //邀请回答
    $('[data-role="send-invite"]').click(function () {
        if($(".myCheck").length==0){
            $.toast("请选择邀请用户！")
            return;
        }
        var follow = new Array();
        for(var i=0;i<$(".myCheck").length;i++){
            follow[i]=$(".myCheck").eq(i).attr("data-id");
        }

        var url="Question/index/invitation";
        $.post(url,{array:follow,id:$('[data-role="all-answer"]').attr("data-id")},function (mes) {
            if(mes.status==1){
                $.toast("邀请成功")
            }
        })
    });
    add_img(5);
});

function addscore() {
    $.get(U('question/index/scoreType'), {}, function (data) {
        var option = '';
        for (var i in data) {
            var t = data[i];
            option += '<option  value="' + t.title + '" >' + t.title + '</option>';
        }
        $('[data-role="moneyType"]').prepend(option);
    })
}