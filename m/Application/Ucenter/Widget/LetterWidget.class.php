<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/9 0009
 * Time: 上午 10:17
 */
namespace Ucenter\Widget;

use Think\Controller ;

class LetterWidget extends Controller{
    public function point($msg=0) {
        $uid = is_login() ;
        if($uid <= 0) return false ;
        $tag = 'Letter_read_'.$uid ;
        $flag = S($tag) ;
        if($flag == false){
            $list = D('Letter')->where(array('status'=>1, 'puid'=>$uid, 'is_read'=>0))->group('uid')->field('uid')->select() ;
            if($list == false){
                S($tag, '-1', 60*60) ;
                return false ;
            }else{
                $flag = array_column($list, 'uid') ;
                S($tag, $flag, 60*60) ;
            }
        }
        if(is_array($flag)&&!empty($flag)){
            if(!is_numeric($msg)){
                //消息列表
                $top = '-22px' ;
                $left = '50%' ;
            }else{
                $top = '1px' ;
                $left = '57%' ;
            }
            echo "<div class='letter-point' style='margin: ".$top." 0 0 ".$left."'></div>" ;
        }
    }
}