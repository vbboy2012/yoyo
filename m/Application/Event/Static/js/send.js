/**
 * Created by Administrator on 2017/8/25 0025.
 */
$(function(){
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


    var ori_height = window.innerHeight;

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
    // 选择活动分类所属分类
    var typeIdArr = [];
    var typeTitleArr = [];
    $('.type_by_id').each(function(){
        typeIdArr.push($(this).text());
    });
    $('.type_by_title').each(function(){
        typeTitleArr.push($(this).text());
    });
    $('[data-role="chose_type"]').picker({
        formatValue:function(picker, value, displayValue){
            $('[data-role="event_type"]').val(value);
            return displayValue;
        },
        toolbarTemplate: '<header class="bar bar-nav">\
                  <button class="button button-link pull-right close-picker">确定</button>\
                  <h1 class="title">选择分类</h1>\
                  </header>',
        cols: [
            {
                textAlign: 'center',
                values: typeIdArr,
                displayValues: typeTitleArr
            }
        ]
    });
    // 选择活动分类所属分类
    $('[data-role="chose_join"]').picker({
        formatValue:function(picker, value, displayValue){
            $('[data-role="join_type"]').val(value);
            return displayValue;
        },
        toolbarTemplate: '<header class="bar bar-nav">\
                  <button class="button button-link pull-right close-picker">确定</button>\
                  <h1 class="title">自己是否报名活动</h1>\
                  </header>',
        cols: [
            {
                textAlign: 'center',
                values: ['0', '1'],
                displayValues: ['报名', '不报名']
            }
        ]
    });

    //提交表单
    $('[data-role="send-event"]').click(function(){
        // $(this).attr('disabled', true) ;
        var data = $('[data-role="upload-form"]').serialize() ;
        var content = $editor.html() ;
        data += "&"+"explain="+content ;
        var url = $(this).attr('data-url') ;
        var toUrl = $(this).attr('data-to-url') ;
        $.post(url, data, function(res){
            if (res.status == 1){
                $.toast('发布成功！') ;
                setTimeout(function () {
                    window.location.href = toUrl ;
                }, 1000) ;
            }else{
                $.toast(res.info) ;
                $(this).attr('disabled', false) ;
            }
        });
    });

    //上传封面
    $('[data-role="upload_picture_cover"]').change(function(){
        $('.infinite-scroll-preloader').css('display', 'block') ;
        var id = $('[data-role="cover_id"]').val() ;
        if (id > 0){
            $('[data-role="upload-cover-box"]').show();
        } else {
            $('[data-role="upload-cover-box"]').hide();
        }
        var pic = $(this)[0].files[0];
        var fd = new FormData();
        var url = $(this).attr('data-url');
        fd.append('file', pic);
        $.ajax({
            url:url,
            type:"post",
            // Form数据
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            success:function(data){
                if(data.status == 1) {
                    var files = data.data.file ;
                    $('[data-role="cover_id"]').val(files.id);
                    $('[data-role="upload-title"]').hide() ;
                    $('[data-role="upload_picture_cover"]').css('height', '169px') ;
                    var src = '' ;
                    if (files.type == 'local'){
                        src = '..' + (files.url || files.path) ;
                    }else{
                        src = files.url || files.path ;
                    }
                    $('[data-role="upload-cover-box"]').html(
                        '<div class="upload-pre-item"><img src="' + src + '"/></div>'
                    );
                } else {
                    $.toast(data.info) ;
                }
                $('[data-role="upload-cover-box"]').show();
                $('.infinite-scroll-preloader').css('display', 'none') ;
            }
        });
    });
    editor.$txt.append();
    $('.wangEditor-mobile-txt').focus(function (){
        $(this).find('.place-info').remove() ;
    });
    $('.wangEditor-mobile-txt').blur(function (){
        var html = $(this).html() ;
        if (html == ''){
            $(this).html(titlep) ;
        }
    });
    var cid = $('[data-role="cover_id"]').val();
    if (cid > 0) {
        $('[data-role="upload-title"]').hide() ;
        $('[data-role="upload_picture_cover"]').css('height', '169px') ;
        $('[data-role="upload-cover-box"]').show();
    }
}) ;