<?php
namespace Common\Widget;

use Think\Controller;

/**
 * Class FollowWidget
 * @package Common\Widget
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
class FollowWidget extends Controller
{
    /**
     * follow  信任按钮
     * @param int $follow_who
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function follow($follow_who = 0, $before, $after)
    {
        $follow_who = intval($follow_who);
        $who_follow = is_login();
        $is_Trusting= D('Follow')->isTrust($who_follow, $follow_who);
        $is_following = D('Follow')->isFollow($who_follow, $follow_who);
        $hide = "";
        if ($is_Trusting){
            $hide = 'visibility: hidden';
        }
        $this->assign('after', $after);
        $this->assign('before', $before);
        $this->assign('is_following', $is_following ? 1 : 0);
        $this->assign('is_trusting', $is_Trusting ? 1 : 0);
        $this->assign('hide', $hide);
        $this->assign('is_self', $who_follow == $follow_who);
        $this->assign('follow_who', $follow_who);
        $this->display(T('Application://Common@Widget/follow'));
    }

    /**
     * follow  屏蔽按钮
     * @param int $follow_who
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function trust($follow_who = 0, $before, $after)
    {
        $follow_who = intval($follow_who);
        $who_follow = is_login();
        $is_Trusting= D('Follow')->isTrust($who_follow, $follow_who);
        $this->assign('after', $after);
        $this->assign('before', $before);
        $this->assign('is_trusting', $is_Trusting ? 1 : 0);
        $this->assign('is_self', $who_follow == $follow_who);
        $this->assign('follow_who', $follow_who);
        $this->display(T('Application://Common@Widget/trust'));
    }

}