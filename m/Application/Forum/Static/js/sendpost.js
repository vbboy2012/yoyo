

$(function () {


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
    /*$editor.focus(function () {
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
*/

    $('[data-role="checkPay"]').click(function () {
        $('[data-role="pay-block"]').fadeToggle();
    })

    // 选择悬赏
    $('[data-role="pay-download"]').click(function () {
        var modal = $.modal({
            title: '请选择您的付费类型和数量',
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
                        $('[data-role="pay-type"]').attr('value',rewardType);
                        $('[data-role="pay-num"]').attr('value',number);
                    }
                }
            ]
        });
        addscore();
    })

    var myId= $("#myid").text();
    $('[data-role="Release"]').click(function () {
        var file_id=$('[data-role="fileByID"]').val()
        $('[data-role="Release"]').attr("disabled",true);
        var html = $editor.html();
        if(html=='<p><br></p>'){
            $.toast("内容不能为空");
            $('[data-role="Release"]').attr("disabled",false);
            return ;
        };
        var url=U('Forum/index/send');
        if($("[data-role='postTitle']").val()==""){
            $.toast("标题不能为空");
            $('[data-role="Release"]').attr("disabled",false);
            return ;
        }
        var checked=$("input[name='checkHide']:checked").val();
        var checkPay=$("input[name='checkPay']:checked").val();
        var pay_type=$('[data-role="pay-type"]').val();
        var pay_num=$('[data-role="pay-num"]').val();
        $.post(url,{content:html,title:$("[data-role='postTitle']").val(),sectionId:myId,checked:checked,file_id:file_id,checkPay:checkPay,pay_type:pay_type,pay_num:pay_num},function (mes) {
            if(mes.status==1){
                $.toast(mes.info)
                location.href =U('Forum/index/section',Array("id",mes.id));
            }
            else{
                $('[data-role="Release"]').attr("disabled",false);
                $.toast(mes.info)
            }
        })
    })


});

function addscore() {
    $.get(U('forum/index/scoreType'), {}, function (data) {
        var option = '';
        for (var i in data) {
            var t = data[i];
            option += '<option  value="' + t.title + '" >' + t.title + '</option>';
        }
        $('[data-role="moneyType"]').prepend(option);
    })
}