/**
 * Created by Administrator on 2017/8/29 0029.
 */
$(function(){
    $(document).on('click', '[data-role="agree"]', function(){
        var $this = $(this) ;
        var nickname = $this.attr('data-name') ;
        $this.attr('disabled', true) ;
        $.confirm('审核后无法取消【'+nickname+'】参与活动~', function() {
            var uid = $this.attr('data-uid') ;
            var event_id = $this.attr('data-id') ;
            var $listBox = $('[data-role="get-url"]') ;
            var url = $listBox.attr('data-url') ;
            var mark = $listBox.attr('data-mark') ;
            $.post(url, {uid:uid,event_id:event_id}, function(res){
                if(res.status == 1){
                    $.toast(res.info) ;
                    $this.removeClass('agg') ;
                    $this.addClass('down') ;
                    $this.html(mark) ;
                    $this.attr('data-role', '') ;
                }else{
                    $.toast(res.info) ;
                    $this.attr('disabled', false) ;
                }
            });
        });
    });
    // $(document).on('click', '[data-role="deny"]', function(){
    //     var $this = $(this) ;
    //     var nickname = $this.attr('data-name') ;
    //     $this.attr('disabled', true) ;
    //     $.confirm('确定要拒绝【'+nickname+'】加入吗？', function() {
    //         var uid = $this.attr('data-uid') ;
    //         var event_id = $this.attr('data-id') ;
    //         var $listBox = $('[data-role="get-url"]') ;
    //         var url = $listBox.attr('data-url') ;
    //         var mark = $listBox.attr('data-mark') ;
    //         $.post(url, {uid:uid,event_id:event_id}, function(res){
    //             if(res.status == 1){
    //                 $.toast(res.info) ;
    //                 $this.removeClass('agg') ;
    //                 $this.addClass('down') ;
    //                 $this.html(mark) ;
    //                 $this.attr('data-role', '') ;
    //             }else{
    //                 $.toast(res.info) ;
    //                 $this.attr('disabled', false) ;
    //             }
    //         });
    //     });
    // });
}) ;
$('[data-role="change-list"]').click(function(){
    var actList = $('.nav-list.active') ;
    actList.removeClass('active') ;
    actList.css('pointer-events', '') ;
    $(this).addClass('active') ;
    $(this).attr('disabled', true) ;
    $(this).css('pointer-events', 'none') ;
    var detailID = $(this).attr('data-id');
    var total = $(this).attr('data-total');
    var type = $(this).attr('data-type');
    $('[data-list="member-list"]').html('') ;
    refreshData('Event/index/getAttendList',total,'[data-list="member-list"]',{id:detailID,tip:type,page:0});
});