/**
 * Created by Administrator on 2017/8/23 0023.
 */
$(function(){
    var onListId = '' ;
    //我发起的活动页面，编辑菜单
    $(document).on('click', '[data-role="edit-event"]', function(){
        showHide($(this).attr('data-id'));
        changeSort($(this)) ;
        eventScroll($(this).attr('data-id')) ;
    });
    $(document).on('click', '.hidd-list', function(){
        showHide($(this).attr('data-value'));
        changeSort($('[data-role="edit-event"][data-value="on"]')) ;
    });
    function showHide(id) {
        $('[data-role="edit-box"][data-value="'+id+'"]').toggle(500) ;
        $('[data-role="hidd-list"][data-value="'+id+'"]').toggle(500) ;
    }
    function changeSort($this) {
        if ($this.attr('data-value') == 'on') {
            onListId = '' ;
            $('.content').removeClass('ovfHiden');
            $this.attr('data-value','off');
            $this.find('i').removeClass() ;
            $this.find('i').addClass('iconfont icon-xiangxiajiantou') ;
            return;
        }
        $('.content').addClass('ovfHiden');
        onListId = $this.attr('data-id') ;
        $this.attr('data-value','on');
        $this.find('i').removeClass() ;
        $this.find('i').addClass('iconfont icon-xiangshangjiantou') ;
    }
});
function eventScroll(type) {
    if (type <= 0) {
        return false;
    }
    var acLi = $('[data-role="li-box"][data-id="'+type+'"]') ;
    if(acLi.length>0){
        var div = $('.content.infinite-scroll') ;
        var top = acLi[0].offsetTop ;
        div.animate({scrollTop: top}, 500);
    }
}