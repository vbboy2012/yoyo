/**
 *
 */
$(function(){

    $('[data-role="resetPasswordByPassword"]').click(function () {
        var $this = $(this);
        if ($("#oldPassword").val()==null){
            $.toast('密码不能为空');
            return false;
        }
        if ($("#newPassword").val()==null||$("#RenewPassword").val()==null){
            $.toast('新密码不能为空');
            return false;
        }
        if ($this.hasClass('disabled')) {
            return false;
        }
        var $form = $('[data-role="form-reset"]');
        var url = $form.attr('data-url');
        var data = $form.serialize();
        $.post(url,data,function(res){
            console.log(res);
            if (res.status == 0) {
                $.toast(res.info);
            } else {
                $.toast(res.info);
                setTimeout(function(){
                    window.location.href = U('weibo/index/index');
                },1500);
            }
        })

    })
});