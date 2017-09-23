$(document).ready(function () {
    $('[data-role="update"]').click(function(){
        var url = U('Update/Index/update');
        var data = $('#repost').serialize();
        $.post(url,data,function(res){
            if (res.status) {
                toast.success(res.info);
                window.location.href = U('update');
            } else {
                toast.error(res.info);
            }
        })
    });

});
