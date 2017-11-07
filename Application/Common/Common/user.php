<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-1-23
 * Time: 上午11:26
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */


/**
 * check_username  根据type或用户名来判断注册使用的是用户名、邮箱或者手机
 * @param $username
 * @param $email
 * @param $mobile
 * @param int $type
 * @return bool
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function check_username(&$username, &$email, &$mobile, &$type = 0)
{

    if ($type) {
        switch ($type) {
            case 2:
                $email = $username;
                $username = '';
                $mobile = '';
                $type = 2;
                break;
            case 3:
                $mobile = $username;
                $username = '';
                $email = '';
                $type = 3;
                break;
            default :
                $mobile = '';
                $email = '';
                $type = 1;
                break;
        }
    } else {
        $check_email = preg_match("/[a-z0-9_\-\.]+@([a-z0-9_\-]+?\.)+[a-z]{2,3}/i", $username, $match_email);
        $check_mobile = preg_match("/^(1[0-9])[0-9]{9}$/", $username, $match_mobile);
        if ($check_email) {
            $email = $username;
            $username = '';
            $mobile = '';
            $type = 2;
        } elseif ($check_mobile) {
            $mobile = $username;
            $username = '';
            $email = '';
            $type = 3;
        } else {
            $mobile = '';
            $email = '';
            $type = 1;
        }
    }
    return true;
}

/**
 * check_reg_type  验证注册格式是否开启
 * @param $type
 * @return bool
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function check_reg_type($type){
    //$t[1] = $t['username'] ='username';
    $t[2] = $t['email'] ='email';
    $t[3] = $t['mobile'] ='mobile';

    $switch = modC('REG_SWITCH','email','USERCONFIG');
    if($switch){
        $switch = explode(',',$switch);
        if(in_array($t[$type],$switch)){
           return true;
        }
    }
    return false;

}


/**
 * check_login_type  验证登录提示信息是否开启
 * @param $type
 * @return bool
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function check_login_type($type){
    $t[1] = $t['username'] ='username';
    $t[2] = $t['email'] ='email';
    $t[3] = $t['mobile'] ='mobile';

    $switch = modC('LOGIN_SWITCH','username','USERCONFIG');
    if($switch){
        $switch = explode(',',$switch);
        if(in_array($t[$type],$switch)){
            return true;
        }
    }
    return false;

}

/**
 * get_next_step  获取注册流程下一步
 * @param string $now_step
 * @return string
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function get_next_step($now_step =''){

    $step = get_kanban_config('REG_STEP', 'enable','', 'USERCONFIG');
    if(empty($now_step) || $now_step == 'start'){
        $return = $step[0];
    }else{
        $now_key = array_search($now_step,$step);
        $return = $step[$now_key+1];
    }
    if(!in_array($return,array_keys(A('Ucenter/RegStep','Widget')->mStep)) || empty($return)){
        $return = 'finish';
    }
    return $return;
}


/**
 * check_step
 * @param string $now_step
 * @return string
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function check_step($now_step=''){
    $step = get_kanban_config('REG_STEP', 'enable','', 'USERCONFIG');
    if(array_search($now_step,$step)){
        $return = $now_step;
    }
    else{
        $return = $step[0];
    }
    return $return;
}


/**
 * set_user_status   设置用户状态
 * @param $uid
 * @param $status
 * @return bool
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function set_user_status($uid,$status){
    D('Member')->where(array('uid'=>$uid))->setField('status',$status);
    UCenterMember()->where(array('id'=>$uid))->setField('status',$status);
    return true;
}

/**
 * set_users_status   批量设置用户状态
 * @param $map
 * @param $status
 * @return bool
 * @author 郑钟良<zzl@ourstu.com>
 */
function set_users_status($map,$status){
    D('Member')->where($map)->setField('status',$status);
    UCenterMember()->where($map)->setField('status',$status);
    return true;
}

/**
 * check_step_can_skip  判断注册步骤是否可跳过
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function check_step_can_skip($step){
    $skip = modC('REG_CAN_SKIP','', 'USERCONFIG');
    $skip = explode(',',$skip);
    if(in_array($step,$skip)){
        return true;
    }
    return false;
}



function check_and_add($args){
    $Member = D('Member');
    $uid = $args['uid'];

    $check = $Member->find($uid);
    if(!$check){
        $args['status'] =1;
        $Member-> add($args);
    }
    return true;
}

function create_user_avatar($username,$uid = 0)
{
    if ($uid > 0){
        $str = strtoupper(substr($username,0,2));
        $im = imagecreatetruecolor(128, 128);
        $colorArray = array(array(21,99,174),array(137,251,232),array(160,219,48),
            array(213,36,112),array(12,47,238),array(209,52,226),array(144,197,196), array(21,137,158),array(30,112,25),array(205,30,68),array(152,240,105),
            array(63,209,209),array(5,19,17),array(223,79,49),array(42,221,235),
            array(125,38,238),array(226,160,73));
        $n = rand(0, 16);
        $white = imagecolorallocate($im, 255, 255, 255);
        $color = imagecolorallocate($im, $colorArray[$n][0], $colorArray[$n][1], $colorArray[$n][2]);
        imagefilledrectangle($im, 0, 0, 128, 128, $color);
        $font = '/Public/zui/fonts/msyhct.ttf';
        imagettftext($im,40,0,128/2-35,128/2+20,$white,$font,$str);
        $path = '/'.random(12).'.png';
        imagepng($im,'./Uploads/Avatar'.$path);
        imagedestroy($im);
        $data = M('avatar')->create();
        $data['uid'] = $uid;
        $data['path'] = $path;
        $data['driver'] = 'local';
        $data['create_time'] = time();
        $data['status'] = 1;
        M('avatar')->add($data);
    }
}

function random($length){
    $captchaSource = "0123456789abcdefghijklmnopqrstuvwxyz";
    for($i=0;$i<$length;$i++){
        $n = rand(0, 35);
        if($n >= 36){
            $n = 36 + ceil(($n-36)/3) * 3;
            $captchaResult .= substr($captchaSource, $n, 3);
        }else{
            $captchaResult .= substr($captchaSource, $n, 1);
        }
    }
    return $captchaResult;
}