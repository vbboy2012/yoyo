<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/22 0022
 * Time: 下午 5:02
 */
function send_eventcomment($event_id, $content, $id=0)
{
    $uid = is_login();
    $result = D('LocalComment')->addComment($uid, $event_id, $content, $id);
    $user_id = D('event')->where(array('id'=>$event_id))->getField('uid');
    if ($id > 0){
        //通知被评论的人
        send_message($user_id,'有人评论了您对活动的评论','有人评论了您对活动的评论，快去看看吧','Event/Index/lzlcomment', array('id'=>$result, 'eid' => $event_id));
    }else{
        //通知帖子作者
        send_message($user_id,'有人评论了您的活动','有人评论了您的活动，快去看看吧','Event/Index/detail', array('id' => $event_id));
    }


    return $result;
}

/**处理页面输出金额
 * @param $price
 * @return string
 */
function show_price($price) {
    if ($price == 0.00) return '面议' ;
    return $price ;
}

/**活动编辑页面时间控制
 * @param int $time  时间戳
 * @param  bool  $long  取时，分
 * @return mixed
 * @author szh(施志宏)  szh@ourstu.com
 */
function change_time($time, $long='') {
    if (is_numeric($time)&&$time != 0) {
        if ($long) {
            return date('Y-m-d H-i', $time) ;
        }else{
            return date('Y-m-d', $time) ;
        }
    }
    return '' ;
}

function self_join($is_join=1) {
    if($is_join) return '报名' ;
    return '不报名' ;
}
