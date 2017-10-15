<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/8 0008
 * Time: 下午 1:58
 */
namespace Ucenter\Model ;
use Think\Model ;
class LetterModel extends Model{
    protected $_validate = array(
        array('content', '1,65535', '文字太长~', 1, 'length') ,
        array('puid', 'checkPuid', '意外的请求~', 1, 'function') ,
        array('puid', 'require', '意外的请求~') ,
    );
    protected $_auto = array(
        array('status','1') ,
        array('is_read','0') ,
        array('create_time', 'time', self::MODEL_INSERT, 'function') ,
    );

    public function checkPuid($uid) {
        $mine = is_login() ;
        $friend = D('Follow')->eachFriend($uid, $mine) ;
        if($friend)
            return $uid ;
        return false;
    }
}