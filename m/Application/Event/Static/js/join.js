/**
 * Created by Administrator on 2017/8/29 0029.
 */
$(function(){
    $('[data-role="send-event"]').click(function(){
        var $this = $(this) ;
        var name = $('input[name="name"]').val() ;
        var phone = $('input[name="phone"]').val() ;
        var id = $('input[name="id"]').val() ;
        var url = $this.attr('data-url');
        var toUrl = $this.attr('data-to-url');
        if (id == ''){
            $.toast('请刷新页面~') ;
            return false ;
        }
        if (name == ''){
            $.toast('请输入姓名~') ;
            return false ;
        }
        if (phone == ''){
            $.toast('请输入常用号码~') ;
            return false ;
        }
        $this.attr('disabled', true);
        $.post(url, {id:id,name:name,phone:phone}, function(res){
            if(res.status == 1){
                $.toast(res.info) ;
                setTimeout(function () {
                    window.location.href = toUrl;
                }, 1000) ;
            }else{
                $.toast(res.info) ;
                $this.attr('disabled', false);
            }
        }) ;
    });
}) ;