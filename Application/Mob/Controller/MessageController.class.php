<?php


namespace Mob\Controller;

use Think\Controller;

class  MessageController extends BaseController
{
    public function index()
    {
        $messageModel=D('Common/Message');
        $messageSessionList=$messageModel->getMyMessageSessionList();
        $this->assign('message_session',$messageSessionList);
        $this->display();
    }

    public function messageList()
    {
        $aType=I('get.type','','text');
        //根据消息类型获取消息列表
        $this->_messageList($aType);
        $this->display();
    }

    /**
     * 加载更多
     * @author 郑钟良<zzl@ourstu.com>
     */
    public function loadMore()
    {
        $aStart=I('post.start',0,'intval');
        $aMessageSession=I('post.type','','text');
        $aNum=I('post.num',5,'intval');
        $messageModel=D('Common/Message');
        $messageList=$messageModel->getSessionMessage($aMessageSession,$aStart,$aNum);
        $html='';
        $messageList=$this->_fetchHtml($messageList);
        foreach($messageList as $val){
            $html.=$val['html'];
        }
        unset($val);
        if(count($messageList)){
            $res['status']=1;
            $res['html']=$html;
            $res['num']=count($messageList);
        }else{
            $res['status']=0;
        }
        $this->ajaxReturn($res);
    }

    /**
     * 处理消息相关流程
     * @param $message_session
     * @return string 消息模板地址
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _messageList($message_session)
    {
        $messageModel=D('Common/Message');
        $info=$messageModel->getInfo($message_session);
        $this->assign('message_session_info',$info);
        //获取消息列表
        list($messageList,$count,$has_unread_message)=$messageModel->getSessionMessageFirst($message_session);
        $messageList=$this->_fetchHtml($messageList);
        $this->assign('message_list',$messageList);
        $this->assign('now_count',$count);
        //设置未读消息为已读
        $messageModel->setAllReaded(is_login(),$message_session);

        if($has_unread_message&&$message_session=="Common_announce"){//对于公告消息，设置公告送达;没有新消息时，不做该步骤
            D('Common/AnnounceArrive')->setAllArrive(is_login());
        }
        return true;
    }

    /**
     * 渲染消息模板
     * @param $list
     * @return mixed
     * @author 郑钟良<zzl@ourstu.com>
     */
    private function _fetchHtml($list)
    {
        foreach($list as &$val){
            if($val['line']){
                continue;
            }
            unset($tpl);
            if($val['tpl']){
                $message_tpl=get_message_tpl();
                $tpl_info=$message_tpl[$val['tpl']];
                if($tpl_info){
                    switch($tpl_info['tpl_name']){
                        case '_comment':
                        case '_announce':
                            $tpl=T('Application://Mob@default/Message/tpl/'.$tpl_info['tpl_name']);
                            break;
                        default:
                            break;
                    }
                }
            }
            if(!isset($tpl)){
                $tpl=T('Application://Mob@default/Message/tpl/_message_li');
            }
            if(getMobMessageUrl($val['content']['url'])){
                $val['content']['mob_url']=is_bool(strpos($val['content']['url'], 'http://')) ? U(getMobMessageUrl($val['content']['url']), $val['content']['args']) : $val['content']['url'];
            }
            $this->assign('data',$val['content']);
            $val['html']=$this->fetch($tpl);
        }
        return $list;
    }

    /**设置全部的系统消息为已读
     * @auth 陈一枭
     */
    public function setAllMessageReaded()
    {
        D('Message')->setAllReaded(is_login());
    }

    /**设置某条系统消息为已读
     * @param $message_id
     * @auth 陈一枭
     */
    public function readMessage($message_id)
    {
        exit(json_encode(array('status' => D('Common/Message')->readMessage($message_id))));
    }

    /*    public function readMessage($message_id)
        {
            return $this->where(array('id' => $message_id))->setField('is_read', 1);
        }*/
    public function setmessage()
    {

        $aIds = $_POST['message_ids'];

        if ($aIds) {
            $messageId = explode(',', $aIds);
            foreach ($messageId as &$v) {
                D('Message')->readMessage($v);
            }
            $this->ajaxreturn(array('status' => 1, 'info' => '设置成功'));
        } else {
            $this->ajaxreturn(array('status' => 0, 'info' => '请选择消息'));
        }

    }


    public function delmessage()
    {

        $aIds = $_POST['message_ids'];

        if ($aIds) {
            $messageId = explode(',', $aIds);

            M('Message')->where(array('id'=>array('in',$messageId)))->delete();
            M('MessageContent')->where(array('id'=>array('in',$messageId)))->delete();
            $this->ajaxreturn(array('status' => 1, 'info' => '删除成功'));
        } else {
            $this->ajaxreturn(array('status' => 0, 'info' => '请选择要删除的消息'));
        }

    }

}