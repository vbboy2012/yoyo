<?php
namespace Event\Model;
use Think\Model;
use Think\Page;

/**
 * 活动模型
 * Class EventModel
 * @package Event\Model
 * autor:xjw129xjt
 */
class EventModel extends Model{
    protected $_validate = array(
        array('title', '1,100', '标题长度不合法', self::EXISTS_VALIDATE, 'length'),
        array('explain', '1,40000', '内容长度不合法', self::EXISTS_VALIDATE, 'length'),
        array('phone', 'checkPhone', '咨询电话格式错误', self::EXISTS_VALIDATE, 'callback')
    );

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
        array('status', '1', self::MODEL_INSERT),
        array('uid', 'is_login',3, 'function'),
    );

    public function checkPhone($phone){
        if ($phone == false) return true ;
        if (!preg_match($meg, $phone)){
            $tele = "/^(1[3|4|5|7|8])[0-9]{9}$/" ;
            if (!preg_match($tele, $phone)){
                return false ;
            }
        }
        return true ;
    }

}
