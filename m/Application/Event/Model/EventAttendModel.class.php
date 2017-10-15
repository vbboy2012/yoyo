<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/22 0022
 * Time: 下午 4:56
 */
namespace Event\Model ;
use Think\Model ;

class EventAttendModel extends Model{

    /**获取所有用户加入的活动
     * @param string $uid
     * @return array
     */
    public function getJoinEvent($uid='') {
        $result = array() ;
        if(!is_numeric($uid) || $uid<0) $uid = is_login() ;
        $tag = "EVENT_JOIN_LIST_".$uid ;
        $eList = S($tag) ;
        if($eList == false){
            $attend = $this->where(array('uid'=>$uid))->select() ;
            if ($attend) {
                $result = array_column($attend, 'event_id') ;
                S($tag, $result, 60*60) ;
            }
        }
        return $result ;
    }
}