$(function () {
   $('[data-role="reward"]').click(function () {
       var modal = $.modal({
           title: '您的支持讲鼓励我继续创作！',
           afterText:'<div class="formWrap"><select data-role="moneyType">\n' +
           '</select>' +
           '<input data-role="number" type="number" placeholder="数量"></div>',
           zdywrap:'rewardWrap',
           buttons: [
               {
                   text: '取消'
               },
               {
                   text: '确认支付',
                   bold: true,
                   onClick: function () {
                       var ulList= $('[data-role="all-comment"]');
                       var  number= $('[data-role="number"]').val();
                       if(number==""){
                           $.toast("支付数量不能为空！");
                           return;
                       }
                       if(number<=0){
                           $.toast("请输入正确的打赏金额！");
                           return;
                       }
                       var url=U('News/Index/reward');
                       $.post(url,{detailId:ulList.attr("data-id"),be:ulList.attr("data-follow-who"),tableName:'news',score:number,type:$('[data-role="moneyType"]').val()},function (res) {
                                       if(res.status=="0"){
                                           $.toast(res.info);
                                       }
                                       else if(res.status=="-1"){
                                           $.toast(res.info);
                                       }
                                       else if(res.status=="2"){
                                           $.toast(res.info);
                                       }
                                       else if(res.status=="1"){
                                           $('[data-role="head"]').empty();
                                           $('[data-role="no"]').text("等"+res.count+"人");
                                           for (var i=0;i<res.head.length;i++){
                                               $('[data-role="head"]').append("<a href='javascript:'><img class='myImg' src='' ></a>");
                                               $('.myImg').eq(i).attr("src",res.head[i].avatar32);
                                           }
                                           $('[data-role="count"]').text("等"+res.count+"人");
                                           $('[data-role="ulList"]').empty();
                                           $('[data-role="ulList"]').append(res.html)
                                           $.toast(res.info);
                                       }
                       })
                   }
               }
           ]
       });
       addscore();
   })
    // 全局配置
    // ___E.config.menus = ['bold', 'color', 'quote'];

    // 生成编辑器
    var editor = new ___E('textarea1');

    // 自定义配置
    editor.config.uploadImgUrl = U('Core/File/uploadPictureByWangEditor');


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

    var $swiper = null;


//上传封面
$('[data-role="add_cover"]').click(function () {
    $(this).parent().css('height', '120px');
    $('.addCover').uploadImage({limit:1});
});

// 选择圈子所属分类
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

//完成创建
$('[data-role="complete"]').click(function () {
    var data = $("#add_crowd").serialize();
    var html = $editor.html();
    var url = $("#add_crowd").attr('data-url');
    $.post(url,{data:data,html:html}, function (msg) {
        handleAjax(msg);
    });
});
});

function addscore() {
    $.get(U('news/index/scoreType'), {}, function (data) {
        var option = '';
        for (var i in data) {
            var t = data[i];
            option += '<option  value="' + t.id + '" >' + t.title + '</option>';
        }
        $('[data-role="moneyType"]').prepend(option);
    })
}


