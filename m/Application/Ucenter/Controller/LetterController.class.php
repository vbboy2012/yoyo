<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/7 0007
 * Time: 下午 4:17
 */
namespace  Ucenter\Controller;

use Think\Controller ;

class LetterController extends Controller{
    public $uid = '' ;
    public function _initialize() {
        $this->uid = is_login() ;
        if($this->uid <= 0){
            $this->error('未登录~') ;
        }
        $user = query_user(array('nickname', 'uid', 'avatar128', 'space_url'), $this->uid);
        $this->assign('user', $user) ;
    }

    public function sendLetter() {
        if(IS_AJAX){
            $content = I('content', '', 'text') ;
            $pUid = I('uid', 0, 'intval') ;
            $this->checkIsFriend($this->uid, $pUid) ;
            $data['content'] = $content ;
            $data['puid'] = $pUid ;
            $data['uid'] = $this->uid ;
            $data = D('Letter')->create($data) ;
            if($data){
                $res = D('Letter')->add($data) ;
                if($res == false){
                    $this->ajaxReturn(array('status'=>0, 'info'=>'操作数据库失败~')) ;
                }
                $this->setCache($pUid) ;
                if(mb_strlen($content, 'utf-8')>20){
                    $content = mb_substr($content, 0, 20, 'utf-8').'...' ;
                }
                $this->getLastContent($pUid, array('uid'=>$this->uid, 'content'=>$content, 'create_time'=>time())) ;
                $this->ajaxReturn(array('status'=>1)) ;
            }else{
                $this->ajaxReturn(array('status'=>0, 'info'=>D('Letter')->getError())) ;
            }
        }
        $pUid = I('uid', 0, 'intval') ;
        if($pUid <= 0){
            $this->redirect('Weibo/index/index') ;
        }
        $pUser = query_user(array('nickname', 'uid', 'avatar128', 'space_url'), $pUid);
        $this->assign('pUser', $pUser) ;
        $time = time() ;
        $info = $this->getLetterList($pUid) ;
        $this->setIsRead($pUid, $time) ;
        $this->assign('letterList', $info['list']) ;
        $this->assign('total', $info['total']) ;
        $this->setTitle('发送私信') ;
        $this->display() ;
    }
    public function index() {
    }
    private function setCache($pUid) {
        $tag = S('Letter_read_'.$pUid) ;
        $tag = $tag == -1 ? $tag = array() : $tag ;
        if(is_array($tag) && !in_array($this->uid, $tag))
            array_push($tag, $this->uid) ;
        S('Letter_read_'.$pUid, $tag, 60*60) ;
    }

    /**获取私信内容列表
     * @param $pUid
     * @param int $page
     * @return array
     * @author szh(施志宏) szh@ourstu.com
     */
    public function getLetterList($pUid, $page=1) {
        if(!is_numeric($pUid)){
            $this->ajaxReturn(array('status'=>0, 'info'=>'操作数据库失败~')) ;
        }
        $map['status'] = 1 ;
        $toMap['uid'] = $this->uid ;
        $toMap['puid'] = $pUid ;
        $where['uid'] = $pUid ;
        $where['puid'] = $this->uid ;
        $allMap['_logic'] = 'or' ;
        $allMap[] = $where ;
        $allMap[] = $toMap ;
        $map['_complex'] = $allMap ;
        $letterList = D('Letter')->where($map)->order('create_time desc')->page($page, 10)->select() ;
        $letterList = array_reverse($letterList) ;
        if(IS_AJAX){
            $pUser = query_user(array('nickname', 'uid', 'avatar128', 'space_url'), $pUid);
            $this->assign('pUser', $pUser) ;
            $this->assign('letterList', $letterList) ;
            $html = $this->fetch('_list') ;
            if($letterList == false){
                $this->ajaxReturn(array('status'=>0, 'data'=>$html)) ;
            }
            $this->ajaxReturn(array('status'=>1, 'data'=>$html)) ;
        }
        //总数
        if($this->uid > $pUid){
            $tag = 'Letter_total_'.$this->uid.'_'.$pUid ;
        }else{
            $tag = 'Letter_total_'.$pUid.'_'.$this->uid ;
        }
        $total = S($tag) ;
        if($total == false) {
            $total = D('Letter')->where($map)->count() ;
            S($tag, $total, 60*60) ;
        }
        $info = array('total'=>$total, 'list'=>$letterList) ;
        return $info ;
    }

    /**私信需要互相关注
     * @param $uid
     * @param $puid
     * @return bool
     */
    public function checkIsFriend($uid, $puid){
        $friend = D('Follow')->eachFriend($uid, $puid) ;
        if($friend) {
            return true ;
        }
        $this->error('私信需要互相关注~') ;
    }

    /**查看了私信，清除缓存里的数据
     * @param $uid
     * @param $time
     */
    public function setIsRead($uid, $time) {
        $tag = S('Letter_read_'.$this->uid) ;
        $flag = is_array($tag)&&in_array($uid, $tag) ;
        if($tag == false || $flag){
            $where['uid'] = $uid ;
            $where['puid'] = $this->uid ;
            $where['status'] = 1 ;
            $where['create_time'] = array('elt', $time) ;
            $res = D('Letter')->where($where)->setField('is_read', 1) ;
            if($res && $flag){
                $key = array_search($uid, $tag);
                if ($key !== false)
                    array_splice($tag, $key, 1) ;
                if(empty($tag))
                    $tag = -1 ;
                S('Letter_read_'.$this->uid, $tag, 60*60) ;
            }
        }
    }

    /**
     * ajax 获取用户私信用户列表
     */
    public function getUserLetterList() {
        $page = I('page', 0, 'intval') ;
        $map['status'] = 1 ;
        $map['puid'] = $this->uid ;
        $list = D('letter')->where($map)->group('uid')->field('uid')->order('max(create_time) desc')->page($page, 10)->select() ;
        foreach ($list as &$val){
            $val['user'] = query_user(array('nickname', 'avatar128'), $val['uid']) ;
            $val['un_read'] = $this->countUnRead($val['uid']) ;
            $val['last_content'] = $this->getLastContent($val['uid']) ;
        }

        $this->assign('data', $list) ;
        $html = $this->fetch('_letterlist') ;
        if($list == false){
            $this->ajaxReturn(array('status'=>0, 'info'=>'没有了~')) ;
        }
        $this->ajaxReturn(array('status'=>1, 'data'=>$html)) ;
    }

    /**获取未读记录数
     * @param $uid
     * @return mixed
     */
    private function countUnRead($uid) {
        $map['status'] = 1 ;
        $map['uid'] = $uid ;
        $map['puid'] = $this->uid ;
        $map['is_read'] = 0 ;
        return D('Letter')->where($map)->count() ;
    }

    /**获取最后一条记录的内容
     * @param $uid
     * @return string
     */
    private function getLastContent($uid, $data='') {
        if($uid > $this->uid){
            $tag = 'Letter_last_content_'.$uid.'_'.$this->uid ;
        }else{
            $tag = 'Letter_last_content_'.$this->uid.'_'.$uid ;
        }
        //覆盖缓存
        if(is_array($data)){
            S($tag, $data, 60*60) ;
            return true;
        }
        //读取缓存
        $content = S($tag) ;
        if($content == false){
            $sMap['status'] = 1 ;
            $map['uid'] = $uid ;
            $map['puid'] = $this->uid ;
            $where['uid'] = $this->uid ;
            $where['puid'] = $uid ;
            $sData[] = $where ;
            $sData[] = $map ;
            $sData['_logic'] = 'or' ;
            $sMap['_complex'] = $sData ;
            $content = D('Letter')->where($sMap)->order('create_time desc')->field('content,uid,create_time')->find() ;
            if(mb_strlen($content['content'], 'utf-8')>20){
                $content['content'] = mb_substr($content['content'], 0, 20, 'utf-8').'...' ;
            }
            S($tag, $content, 60*60) ;
        }
        return $content ;
    }
}