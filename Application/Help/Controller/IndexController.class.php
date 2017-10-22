<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-4-28
 * Time: 上午11:30
 * @author 郑钟良<zzl@ourstu.com>
 */

namespace Help\Controller;


use Think\Controller;

class IndexController extends Controller{

    function _initialize()
    {
        $ticketMenu =
            array(
                'left' =>
                    array(
                        array('tab' => 'support', 'title' => L('_LIST_TICKET_'), 'href' => U('/support')),
                        array('tab' => 'request', 'title' => L('_ADD_TICKET_'), 'href' => U('/support/request')),
                    ),
            );
        $this->assign('ticketMenu', $ticketMenu);

    }

    public function support()
    {
        $uid = is_login();
        if ($uid) {
            $ticket = M('ticket')->where('uid='.$uid)->select();
            $this->assign('ticket', $ticket);
        }
        $this->assign('current', 'support');
        $this->display();
    }

    public function request()
    {
        $this->assign('current', 'request');
        $this->display();
    }

    public function doPost($id =0,$type='',$question_id='',$email='',$content='',$image='')
    {
        if ($type == '' || $question_id == '' || $email == ''|| $content == ''){
            $this->error(L('_ERROR_TICKET_'));
        }
        $data = D('ticket')->create();
        $data['type'] = $type;
        $data['question_id'] = $question_id;
        $data['email'] = $email;
        $data['content'] = $content;
        $images = '';
        foreach ($image as $item){
            $images .= $item.',';
        }
        $data['images'] = $images;
        $uid = is_login();
        if ($id > 0){
            $data['update_time'] = time();
            D('ticket')->where('id='.$id)->save($data);
        }else{
            $data['uid'] = $uid;
            $data['create_time'] = time();
            D('ticket')->add($data);
        }
        session(array('name'=>'ticket_send','expire'=>3600));
        session('ticket_send',$email);
        $this->success(L('_SUCCESS_TICKET_'), U('/support'));
    }

} 