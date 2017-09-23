<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/7 0007
 * Time: 下午 2:30
 */
namespace Forum\Model ;
use Think\Model\ViewModel ;

class ForumPostReplyViewModel extends ViewModel
{
    protected $viewFields = array (
        'ForumPostReply' => array('uid', '_type'=>'LEFT') ,
        'ForumPost' => array('id', '_on' => 'ForumPostReply.post_id = ForumPost.id') ,
    ) ;
}