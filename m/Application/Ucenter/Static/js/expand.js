/**
 * Created by 路飞 on 2017/8/31.
 */

$(function () {
    $('[data-role="submit_btn"]').click(function () {
        var button_id = $(this).attr('data-button-id');
        var data = $('#edit_expandinfo_'+button_id).serialize();
        // alert(data);
        var url = $('#edit_expandinfo_'+button_id).attr('data-url');
        $.post(url, data, function (res) {
            handleAjax(res);
        }, 'json');
    })

    // input
    /*$('[data-role="input"]').click(function () {
        var expand = $(this).attr('data-value');
        $.prompt('编辑',
            function (value) {
                if (value.length <= 0) {
                    $.alert('修改失败');
                    return false;
                }
                $('#'+expand).val(value);
            }
        );
    });*/

    // time
    $('[data-role="time"]').datePicker({
        toolbarTemplate: '<header class="bar bar-nav">\
        <button class="button button-link pull-right close-picker">确定</button>\
        <p class="title">选择时间</p>\
        </header>',

        onClose: function () {
            var expand = $('[data-role="time"]').attr('data-value');
            $('#'+expand).val();
        }
    });

    //select
    var titleArr = [];
    $('.type_by_title').each(function(){
        titleArr.push($(this).text());
    });
    $('[data-role="select"]').picker({
        formatValue:function(picker, value, displayValue){
            var expand = $('[data-role="select"]').attr('data-value');
            $('#'+expand).val(value);
            return displayValue;
        },
        toolbarTemplate: '<header class="bar bar-nav">\
  <button class="button button-link pull-right close-picker">确定</button>\
  <h1 class="title">选择分类</h1>\
  </header>',
        cols: [
            {
                textAlign: 'center',
                values: titleArr,
                displayValues: titleArr
            }
        ]
    });
    
});
