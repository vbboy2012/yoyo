<?php
/**
 * Created by PhpStorm.
 * User: Yixiao Chen
 * Date: 2015/5/5 0005
 * Time: 上午 9:47
 */

namespace Weibo\Controller;


use Think\Controller;

class BaseController extends  Controller{

    public function _initialize(){
        if(D('Common/Module')->isInstalled('Mob')) {
            $sign = modC('JUMP_MOB', 0, 'mob');
            if(is_mobile() && ($sign == 0)) {
                if(ACTION_NAME!="weibodetail") {
                    redirect(U('Mob/Weibo/index'));
                }else{
                    $id = I('id','0','intval') ;
                    redirect(U('Mob/Weibo/weibodetail',array('id'=>$id)));
                }
            }
        }
        
        $sub_menu =
            array(
                'left' =>
                    array(
                        array('tab' => 'index', 'title' => L('_MY_') . L('_MODULE_'), 'href' =>  U('index/index')),
                        array('tab' => 'hot', 'title' => L('_HOT_').L('_MODULE_'), 'href' => U('index/index',array('type'=>'hot'))),
                        array('tab' => 'topic', 'title' =>L('_HOT_TOPIC_'), 'href' => U('topic/topic')),
                    ),
                'right'=>array(
                    array('type'=>'search', 'input_title' => L('_INPUT_KEYWORDS_'),'input_name'=>'keywords','from_method'=>'post', 'action' =>U('Weibo/index/search')),
                )
            );
        $this->assign('sub_menu', $sub_menu);
        $this->assign('current', 'index');
    }
}