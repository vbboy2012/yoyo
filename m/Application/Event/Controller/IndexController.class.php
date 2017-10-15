<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/15 0015
 * Time: 上午 11:24
 */
namespace Event\Controller ;
use Think\Controller;

class IndexController extends Controller {
    public function _initialize() {
        $this->setTitle(L('_MODULE_')) ;
        if (!D('Module')->checkInstalled('Event')) {
            $this->error('模块未安装');
        }
    }
    /**
     *活动首页
     * @author szh(施志宏) szh@ourstu.com
     */
    public function index() {
        $aType = I('type', 0, 'intval') ;
        $map = array() ;
        if($aType){
            $type = $this->getType($aType) ;
            $map['type_id'] = $aType ;
            $this->assign('type', $type) ;
        }
        $map['status'] = 1 ;
        $type = D('event_type')->where($map)->order('sort desc')->field('id,title')->limit(8)->select() ;

        $this->assign('uid', is_login()) ;
        $total = $this->getCount() ;
        $this->assign('typeList', $type) ;
        $this->assign('total', $total) ;
        $this->display() ;
    }

    /**
     *获取活动列表
     * @author szh(施志宏) szh@ourstu.com
     */
    public function eventList() {
        $page = I('page', 1, 'intval') ;
        $isPull = I('pull', 0, 'intval') ;
        $aType = I('type', 0, 'intval') ;
        $active = I('lora', 'all', 'string') ;

        $result = array() ;
        $result['status'] = 0 ;
        $result['info'] = '暂无数据~' ;

        $map = array() ;
        $uid = is_login() ;
        if($isPull)
            $page = 1 ;
        if($aType)
            $map['type_id'] = $aType ;
        switch($active){
            case 'attend':
                $exArray = D('EventAttend')->getJoinEvent($uid) ;
                $map['id'] = array('in', $exArray) ;
                $result['info'] = '快去参加一个活动吧~' ;
                break;
            case 'mine':
                $map['uid'] = $uid ;
                $result['info'] = '快去发起一个活动吧~' ;
                break;
        }
        $map['status'] = 1 ;
        $order = modC('EVENT_SHOW_ORDER_FIELD','create_time','EVENT').' '.modC('EVENT_SHOW_ORDER_TYPE','DESC','EVENT') ;
        $limit = modC('EVENT_LOCAL_COMMENT_COUNT', 10, 'EVENT') ;
        $content = D('Event')->where($map)->order($order)->page($page, $limit)->select();
        $content = $this->getListInfo($content, $uid) ;

        $this->assign('uid', $uid) ;
        $this->assign('list', $content) ;
        $this->assign('active', $active) ;
        $html = '' ;
        $html = $this->fetch('_list') ;

        if ($html) {
            $result['status'] = 1 ;
            $result['data'] = $html ;
            $result['info'] = '成功~' ;
        }
        $this->ajaxReturn($result) ;
    }

    /**获取活动列表需要的外表数据
     * @param $list
     * @param $uid
     * @return array
     * @author szh(施志宏) szh@ourstu.com
     */
    private function getListInfo($list ,$uid){
        if (empty($list)) return array() ;
        foreach ($list as &$val){
            $support['appname'] = 'Event';
            $support['table'] = 'event';
            $support['row'] = $val['id'];
            $support['uid'] = $uid;
            $val['support_count'] = D('Support')->where($support)->count();
        }
        unset($val) ;
        return $list ;
    }
    /**
     * 获取所有的分类
     * @author szh(施志宏) szh@ourstu.com
     */
    public function getAllType() {
        $result = array() ;
        $result['status'] = 0 ;
        $list = D('event_type')->where(array('status'=>1))->field('id,title')->select() ;
        $this->assign('list', $list) ;
        $html = '' ;
        $html = $this->fetch('_typelist') ;
        if($html) {
            $result['status'] = 1 ;
            $result['data'] = $html ;
        }
        $this->ajaxReturn($result) ;
    }

    /**
     * 我发起的，我参加的活动列表
     * @author szh(施志宏) szh@ourstu.com
     */
    public function myEvent() {
        $uid = is_login() ;
        if ($uid <= 0) {
            $this->error('请登录~') ;
            exit;
        }
        $active = I('lora', 'mine', 'string') ;
        $total = 0 ;
        $title = L('_EVENT_') ;
        switch($active){
            case 'attend':
                $title = L('_MY_IN_').L('_EVENT_') ;
                $this->setTitle($title) ;
                $exArray = D('EventAttend')->getJoinEvent($uid) ;
                $total = count($exArray) ;
                break;
            case 'mine':
                $title = L('_MY_SPONSOR_').L('_EVENT_') ;
                $this->setTitle($title) ;
                $map['uid'] = $uid ;
                $map['status'] = 1 ;
                $total = D('Event')->where($map)->count();
                break;
        }
        $this->assign('active', $active) ;
        $this->assign('total', $total) ;
        $this->assign('title', $title) ;
        $this->display() ;
    }

    /**获取活动的数量
     * @return int
     * @author szh(施志宏) szh@ourstu.com
     */
    public function getCount() {
        $eType = I('type', 0, 'intval') ;

        $map = array() ;
        if($eType) {
            $map['type_id'] = $eType ;
        }
        $map['status'] = 1 ;
        $total = D('event')->where($map)->count() ;
        if ($total == false) {
            $total = 0 ;
        }
        if (IS_POST) {
            $this->ajaxReturn(array('status'=>1, 'data'=>$total)) ;
        }
        return $total ;
    }
    /**
     * ajax提前结束活动
     * @param  int $event_id
     * @author:xjw129xjt
     */
    public function doEndEvent()
    {
        $event_id = I('event_id', 0, 'intval') ;
        $event_content = $this->checkEventAlive($event_id) ;
        $this->checkAuth('Event/Index/doEndEvent', $event_content['uid'], L('_INFO_OVER_LIMIT_').L('_EXCLAMATION_'));
        $data['eTime'] = time();
        $data['deadline'] = time();
        $res = D('Event')->where(array('status' => 1, 'id' => $event_id))->setField($data);
        if ($res) {
            $this->success(L('_SUCCESS_DELETE_').L('_EXCLAMATION_'));
        } else {
            $this->error(L('_ERROR_OPERATION_FAIL_').L('_EXCLAMATION_'));
        }
    }
    /**
     * ajax删除活动
     * @param $event_id
     * @author:xjw129xjt
     */
    public function doDelEvent()
    {
        $event_id = I('event_id', 0, 'intval') ;
        $event_content = $this->checkEventAlive($event_id) ;
        $this->checkAuth('Event/Index/doDelEvent', $event_content['uid'],L('_INFO_DELETE_LIMIT_').L('_EXCLAMATION_'));
        $res = D('Event')->where(array('status' => 1, 'id' => $event_id))->setField('status', 0);
        if ($res) {
            $this->success(L('_SUCCESS_DELETE_').L('_EXCLAMATION_'), U('Event/Index/index'));
        } else {
            $this->error(L('_ERROR_OPERATION_FAIL_').L('_EXCLAMATION_'));
        }
    }
    /**
     * 活动详情页面
     * @author szh(施志宏) szh@ourstu.com
     */
    public function detail() {
        $eId = I('id', 0, 'intval') ;
        $tag = "EVENT_DEATIL_".$eId ;
        $uid = is_login() ;
        $event = S($tag) ;
        if ($event == false){
            $event = D('event')->where(array('id'=>$eId, 'status'=>1))->find() ;
            if (!$event) {
                $this->error(L('_NOT_FOUND_'));
            }else{
                if($event['status']==2 && (!check_auth('Admin/Event/verify',array($event['uid'])))){
                    $this->error(L('_NOT_AUTH_'));
                }
            }
            $event['user'] = query_user(array('id', 'nickname', 'avatar64'), $event['uid']) ;
            $event['type'] = $this->getType($event['type_id']) ;
            S($tag, $event, modC('EVENT_SHOW_CACHE_TIME' , 60*60,'EVENT')) ;
        }
        D('Event')->where(array('id' => $eId))->setInc('view_count');
        $eventTip = S('EVENT_TIP_TENDER') ;
        if($eventTip === false) {
            $eventTip = modC('EVENT_TIP_TENDER', '', 'EVENT') ;
            if($eventTip != false) {
                $time = modC('EVENT_SHOW_CACHE_TIME', 60*60, 'EVENT') ;
                S('EVENT_TIP_TENDER', $eventTip, $time) ;
            }
        }
        $support['appname'] = 'Event';
        $support['table'] = 'event';
        $support['row'] = $eId;
        $support['uid'] = $uid;
        $is_support_count = D('Support')->where($support)->count();
        $is_support = 0 ;
        if ($is_support_count)
            $is_support = '1';
        //已经参与活动的人数
        $joinCount = D('event_attend')->where(array('event_id'=>$eId, 'status'=>1))->count() ;
        $joinUser = D('event_attend')->where(array('event_id'=>$eId, 'status'=>1))->order('create_time desc')->field('uid')->limit(3)->select() ;
        foreach ($joinUser as &$val){
            $val['user'] = query_user(array('id', 'nickname', 'avatar64', 'space_url'), $val['uid']) ;
        }
        unset($val) ;

        //展示前三条评论
        $this->assign('uid', $uid) ;
        $this->assign('eid', $eId) ;
        $map = array() ;

        $comment = D('local_comment')->CountComments($eId) ;
        if($comment > 999)
            $comment = '999+' ;
        $map['app'] = 'Event' ;
        $map['mod'] = 'event' ;
        $map['status'] = 1 ;
        $map['row_id'] = $eId ;
        $comments = D('local_comment')->where($map)->limit(3)->order('create_time desc')->select() ;
        foreach ($comments as &$val){
            $val = D('LocalComment')->getComment($val) ;
        }
        unset($val) ;
        $this->assign('comments', $comments) ;
        $allComments = $this->fetch('_commentlist') ;
        $this->assign('allComments', $allComments) ;
        //关注
        if ($event['uid'] != $uid) {
            $follow['follow_who']=$event['uid'];
            $follow['who_follow']=$uid;
            $is_follow = D('follow')->where($follow)->count();
        }else{
            $is_follow = -1 ;
        }
        //分享
        $query = array(
            'title'=>$event['title'],
            'content'=>msubstr($event['explain'],0,200),
            'img'=>getThumbImageById($event['cover_id'],160,160),
            'from'=>L('_MODULE_'),
            'site_link'=>U('event/index/detail',array('id'=>$event['id']))
        );
        $this->assign('query', urlencode(http_build_query($query)));
        $this->assign('event', $event) ;
        $this->assign('is_support', $is_support) ;
        $this->assign('joinCount', $joinCount) ;
        $this->assign('joinUser', $joinUser) ;
        $this->assign('comment', $comment) ;
        $this->assign('follow', $is_follow) ;
        $this->assign('eventTip', $eventTip) ;
        $this->display() ;
    }

    /**
     * 获取活动类型
     * @param $type_id
     * @return mixed
     */
    private function getType($type_id){
        if(!is_numeric($type_id))
            return array() ;
        $type = D('EventType')->where('id=' . $type_id)->field('id,title')->find();
        return $type;
    }
    /**
     * 活动详情评论
     */
    public function addComment()
    {
        if (!is_login()) {
            $this->error('请您先登录', U('Ucenter/member/login'), 1);
        }

        if (IS_POST) {
            $aContent = I('post.content', '', 'op_t');              //评论内容
            $eId = I('post.event_id', 0, 'intval');             //要评论的活动的ID
            $id = I('post.id', 0, 'intval');             //要评论的评论的ID
            if ($aContent == "") {
                $res['status'] = -1;
                $res['info'] = '评论不能为空';
                $this->ajaxReturn($res);
            } elseif ($eId == 0) {
                $res['status'] = -1;
                $res['info'] = '没有相应的活动';
                $this->ajaxReturn($res);
            }
            $eventcomment = send_eventcomment($eId, $aContent, $id);
            D('event')->where(array('id'=> $eId))->setInc('reply_count');
            if ($eventcomment) {
                $data['html'] = "";
                $flag = 0 ;
                if($id>0){
                    $flag = 1 ;
                }
                $comment = D('LocalComment')->getComment($eventcomment);
                if (S('event_detail_'.$eId.'1')){
                    $arr=S('event_detail_'.$eId.'1');
                    array_unshift($arr,$comment);
                    S('event_detail_'.$eId.'1',$arr,3600);
                }
                $this->assign('flag', $flag) ;
                $comment=array(
                    '0'=>$comment
                );
                $this->assign('comments', $comment);
                $this->assign('eid', $eId) ;
                $data['html'] .= $this->fetch("_commentlist");
                $data['status'] = 1;
            } else {
                $data['status'] = 0;
                $data['info'] = '评论失败';
            }
            $this->ajaxReturn($data);
        }
    }

    /**
     * 活动添加页面
     * @author szh(施志宏) szh@ourstu.com
     */

    public function add(){
        $uid = is_login() ;
        if($uid <= 0){
            $this->error('请您先登录', U('Ucenter/member/login'), 1);
        }
        $this->checkAuth('Event/Index/add', -1, L('_EVENT_PRIORITY_START_NOT_').L('_PERIOD_'));
        //分类
        $typeList = D('event_type')->where(array('status'=>1))->field('id, title')->select() ;
        $this->assign('typeList', $typeList) ;
        $this->setTitle(L('_EVENT_ADD_')) ;
        $this->display() ;
    }
    /**
     *活动编辑页面
     * @author szh(施志宏) szh@ourstu.com
     */
    public function edit() {
        $id = I('id', 0, 'intval') ;
        $event = $this->checkEventAlive($id) ;
        $this->checkAuth('Event/Index/edit', $event['uid'], L('_INFO_EVENT_EDIT_LIMIT_').L('_EXCLAMATION_'));
        $event['cover'] = getThumbImageById($event['cover_id'], 420, 320) ;
        $event['join'] = self_join($event['self_join']) ;
        $type = $this->getType($event['type_id']) ;
        //分类
        $typeList = D('event_type')->where(array('status'=>1))->field('id, title')->select() ;
        $this->assign('typeList', $typeList) ;
        $this->assign('event', $event) ;
        $this->assign('type', $type) ;
        $this->setTitle(L('_EVENT_EDIT_') . L('_DASH_').L('_MODULE_'));
        $this->setKeywords(L('_EDIT_') . L('_COMMA_').L('_MODULE_'));
        $this->display('add') ;
    }

    /**
     * 发布活动
     * 编辑活动
     */
    public function doPost()
    {
        $id = I('id', 0, 'intval') ;
        $cover_id = I('cover_id', '', 'intval') ;
        $title = I('title', '', 'string') ;
        $explain = I('explain', '', 'text') ;
        $sTime = I('sTime', '', 'string') ;
        $eTime = I('eTime', '', 'string') ;
        $address = I('address', '', 'string') ;
        $limitCount = I('limitCount', '', 'intval') ;
        $deadline = I('deadline', '', 'string') ;
        $type_id = I('type_id', '', 'intval') ;
        $self_join = I('self_join', '', 'intval') ;
        $price = I('price', '', 'intval') ;
        $pay_way = I('pay_way', '', 'string') ;
        $phone = I('phone', '', 'string') ;
        $user_id = is_login() ;
        if (!$user_id) {
            $this->error(L('_ERROR_LOGIN_'));
        }
        if (!$cover_id) {
            $this->error(L('_ERROR_COVER_'));
        }
        if (trim(op_t($title)) == '') {
            $this->error(L('_ERROR_TITLE_'));
        }
        if ($type_id == 0) {
            $this->error(L('_ERROR_CATEGORY_'));
        }
        if (trim(op_h($explain)) == '') {
            $this->error(L('_ERROR_CONTENT_'));
        }
        if (trim(op_h($address)) == '') {
            $this->error(L('_ERROR_SITE_'));
        }
        if ($eTime < $deadline) {
            $this->error(L('_ERROR_TIME_DEADLINE_'));
        }
        if ($deadline == '') {
            $this->error(L('_ERROR_DEADLINE_'));
        }
        if ($sTime > $eTime) {
            $this->error(L('_ERROR_TIME_START_'));
        }
        if (!is_numeric($price)) {
            $this->error('活动费用格式错误');
        }
        if ($limitCount <= 0 || ($self_join && $limitCount <= 1)) {
            $this->error(L('_NUMBER_LIMIT_').'，'.L('_ERROR_PARAM_'));
        }
        $content = D('Event')->create();
        if($content == false){
            $this->error(D('Event')->getError());
        }
        $content['explain'] = filter_content($content['explain']);
        $content['title'] = op_t($content['title']);
        $content['sTime'] = strtotime($content['sTime']);
        $content['eTime'] = strtotime($content['eTime']);
        $content['deadline'] = strtotime(str_replace(' ', '-', $content['deadline']));
        $content['type_id'] = intval($type_id);

        if ($id) {
            $content_temp = D('Event')->find($id);
            $this->checkAuth('Event/Index/edit', $content_temp['uid'], L('_INFO_EVENT_EDIT_LIMIT_'));
            $this->checkActionLimit('add_event', 'event', $id, $user_id, true);
            $content['uid'] = $content_temp['uid']; //权限矫正，防止被改为管理员
            $rs = D('Event')->save($content);
            if (D('Common/Module')->isInstalled('Weibo')) { //安装了微博模块
                $postUrl = "http://$_SERVER[HTTP_HOST]" . U('Event/Index/detail', array('id' => $id));
                $weiboModel = D('Weibo/Weibo');
                $weiboModel->addWeibo(L('_EVENT_CHANGED_')."【" . $title . "】：" . $postUrl);
            }
            if ($rs) {
                action_log('add_event', 'event', $id, $user_id);
                $tag = "EVENT_DEATIL_".$id ;
                S($tag,null) ;
                $this->success(L('_SUCCESS_DELETE_').L('_EXCLAMATION_'), U('Event/Index/detail', array('id' => $content['id'])));
            } else {
                $this->error(L('_ERROR_OPERATION_FAIL_').L('_EXCLAMATION_'),'');
            }
            exit;
        } else {
            $this->checkAuth('Event/Index/add', -1, L('_EVENT_PRIORITY_START_NOT_').L('_EXCLAMATION_'));
            $this->checkActionLimit('add_event', 'event', 0, $user_id, true);
            if (modC('NEED_VERIFY', 0) && !is_administrator()) //需要审核且不是管理员
            {
                $content['status'] = 2;
                $tip = L('_PLEASE_WAIT_').L('_PERIOD_');
                $user = query_user(array('username', 'nickname'), $user_id);
                D('Common/Message')->sendMessage(explode(',', C('USER_ADMINISTRATOR')), $title = L('_EVENT_SPONSOR_1_'), "{$user['nickname']}".L('_EVENT_SPONSOR_2_'), 'Admin/Event/verify', array(), is_login(), 2);
            }

            if ($self_join == false) {
                $content['attentionCount'] = 1;
                $content['signCount'] = 1;
            }
            $rs = D('Event')->add($content);

            if($self_join == false){
                $data['uid'] = $user_id;
                $data['event_id'] = $rs;
                $data['create_time'] = time();
                $data['status'] = 1;
                D('event_attend')->add($data);
            }

            if (D('Common/Module')->isInstalled('Weibo') && modC('SHENHE_SEND_WEIBO', '', 'EVENT')) { //安装了微博模块,并允许发布微博
                //同步到微博
                $postUrl = "http://$_SERVER[HTTP_HOST]" . U('Event/Index/detail', array('id' => $rs));

                $weiboModel = D('Weibo/Weibo');
                $weiboModel->addWeibo($user_id, L('_EVENT_I_SPONSOR_')."【" . $title . "】：" . $postUrl);
            }

            if ($rs) {
                action_log('add_event', 'event', $rs, $user_id);
                $this->success(L('_SUCCESS_POST_').L('_EXCLAMATION_'). $tip.$rs, U('index'));
            } else {
                $this->error(L('_ERROR_OPERATION_FAIL_').L('_EXCLAMATION_'));
            }

        }
    }

    /**
     * 参加活动人员页面
     */
    public function member(){
        $id = I('id', 0, 'intval') ;
        $tip = I('tip', 'all', 'string') ;
        if ($tip == 'sign') {
            $map['status'] = 0;
        }
        if ($tip == 'attend') {
            $map['status'] = 1;
        }
        $check_isSign = D('event_attend')->where(array('uid' => is_login(), 'event_id' => $id))->select();
        $this->assign('check_isSign', $check_isSign);

        $event_content = $this->checkEventAlive($id) ;

        $map['event_id'] = $id;
        $event_content['user'] = query_user(array('id', 'username', 'nickname', 'space_url', 'space_link', 'avatar64', 'rank_html', 'signature'), $event_content['uid']);
        $menber = D('event_attend')->where($map)->select();
        foreach ($menber as $k => $v) {
            $event_content['member'][$k] = query_user(array('id', 'username', 'nickname', 'space_url', 'space_link', 'avatar64', 'avatar128', 'rank_html', 'signature'), $v['uid']);
            $event_content['member'][$k]['name'] = $v['name'];
            $event_content['member'][$k]['phone'] = $v['phone'];
            $event_content['member'][$k]['status'] = $v['status'];
        }

        $this->assign('all_count', D('event_attend')->where(array('event_id' => $id))->count());
        $this->assign('sign_count', D('event_attend')->where(array('event_id' => $id, 'status' => 0))->count());
        $this->assign('attend_count', D('event_attend')->where(array('event_id' => $id, 'status' => 1))->count());

        $this->assign('content', $event_content);
        $this->assign('tip', $tip);
        $this->setTitle(op_t($event_content['title']) . '——活动');
        $this->setKeywords(op_t($event_content['title']) . ',活动');
        $this->display();
    }

    /**
     * 活动审核页面
     * @author szh(施志宏) szh@ourstu.com
     */
    public function getAttendList() {
        $id = I('id', 0, 'intval') ;
        $tip = I('tip', 'all', 'string') ;
        $page = I('page', 1, 'intval') ;
        if ($tip == 'sign') {
            $map['status'] = 0;
        }
        if ($tip == 'attend') {
            $map['status'] = 1;
        }
        $event_content = $this->checkEventAlive($id) ;
        $map['event_id'] = $id;
        $event_content['user'] = query_user(array('id', 'username', 'nickname', 'space_url', 'space_link', 'avatar64', 'rank_html', 'signature'), $event_content['uid']);
        $menber = D('event_attend')->where($map)->page($page, 10)->order('create_time desc')->select();
        foreach ($menber as $k => $v) {
            $event_content['member'][$k] = query_user(array('id', 'username', 'nickname', 'space_url', 'space_link', 'avatar64', 'avatar128', 'rank_html', 'signature'), $v['uid']);
            $event_content['member'][$k]['name'] = $v['name'];
            $event_content['member'][$k]['phone'] = $v['phone'];
            $event_content['member'][$k]['status'] = $v['status'];
        }
        $level = 1 ;
        if (is_administrator()) $level=2 ;
        if(is_login() == $event_content['uid']) $level=3 ;
        $this->assign('level', $level) ;
        $this->assign('list', $event_content) ;
        $html = $this->fetch('_mlist') ;
        $this->ajaxReturn(array('status'=>1, 'info'=>'成功~', 'data'=>$html)) ;
    }

    /**
     * 报名活动页面
     * @author szh(施志宏) szh@ourstu.com
     */
    public function join(){
        $id = I('id', 0, 'intval') ;
        $event = $this->checkEventAlive($id) ;
        $this->checkAuth('Event/Index/doSign',$event['uid'],L('_NOT_AUTH_')) ;

        $event['cover'] = getThumbImageById($event['cover_id'], 420, 320) ;
        $this->assign('event', $event) ;
        $this->display() ;
    }

    /**
     * 报名参加活动
     * @param $event_id
     * @param $name
     * @param $phone
     * @author:xjw129xjt
     */
    public function doSign()
    {
        $event_id = I('id', 0, 'intval') ;
        $name = I('name', '', 'string') ;
        $phone = I('phone', '', 'string') ;
        if (!is_login()) {
            $this->error(L('_ERROR_REGISTER_AFTER_LOGIN_').L('_PERIOD_'));
        }
        if (!$event_id) {
            $this->error(L('_ERROR_PARAM_').L('_PERIOD_'));
        }
        if (trim(op_t($name)) == '') {
            $this->error(L('_ERROR_NAME_').L('_PERIOD_'));
        }
        if (trim($phone) == '') {
            $this->error(L('_ERROR_PHONE_').L('_PERIOD_'));
        }
        $check = D('event_attend')->where(array('uid' => is_login(), 'event_id' => $event_id))->select();

        $event_content = $this->checkEventAlive($event_id) ;

        $this->checkAuth('Event/Index/doSign', $event_content['uid'], L('_INFO_LIMIT_').L('_EXCLAMATION_'));
        $this->checkActionLimit('event_do_sign', 'event', $event_id, is_login());

        /*      if ($event_content['attentionCount'] + 1 > $event_content['limitCount']) {
                  $this->error('超过限制人数，报名失败');
              }*/
        if (time() > $event_content['deadline']) {
            $this->error(L('_REGISTRATION_HAS_OVER_'));
        }
        if (!$check) {
            $data['uid'] = is_login();
            $data['event_id'] = $event_id;
            $data['name'] = $name;
            $data['phone'] = $phone;
            $data['create_time'] = time();
            $res = D('event_attend')->add($data);
            if ($res) {
                D('Message')->sendMessageWithoutCheckSelf($event_content['uid'], L('_TOAST_SIGN_1_'), get_nickname(is_login()) . L('_TOAST_SIGN_2_') . $event_content['title'] . L('_TOAST_SIGN_3_'), 'Event/Index/member', array('id' => $event_id));

                D('Event')->where(array('id' => $event_id))->setInc('signCount');
                action_log('event_do_sign', 'event', $event_id, is_login());
                $this->success(L('_SUCCESS_SIGN_').L('_PERIOD_'), 'refresh');
            } else {
                $this->error(L('_FAIL_SIGN_').L('_PERIOD_'), '');
            }
        } else {
            $this->error(L('_SIGN_ED_').L('_PERIOD_'), '');
        }
    }


    /**
     * 审核
     * @param $uid
     * @param $event_id
     */
    public function shenhe(){
        $uid = I('uid', '', 'intval') ;
        $event_id = I('event_id', 0, 'intval') ;
        $event_uid = is_login() ;
        $event_content = D('Event')->where(array('id' => $event_id, 'status' => 1, 'uid'=>$event_uid))->find();
        if (!$event_content || $event_content['deadline'] < time()) {
            $this->error(L('_LIMIT_YOU_AUDIT_NOT_').L('_EXCLAMATION_'));
        }
        $this->checkAuth('Event/Index/shenhe', $event_content['uid'], L('_EVENT_NOT_EXIST_OR_OVER_').L('_EXCLAMATION_'));
        $res = D('event_attend')->where(array('uid' => $uid, 'event_id' => $event_id))->setField('status', 1);

        if ($res) {
            if ($event_content['attentionCount'] + 1 == $event_content['limitCount']) {
                $data['deadline'] = time();
                $data['attentionCount'] = $event_content['limitCount'];
                D('Event')->where(array('id' => $event_id))->setField($data);
            } else {
                D('Event')->where(array('id' => $event_id))->setInc('attentionCount');
            }
            D('Message')->sendMessageWithoutCheckSelf($uid, L('_MESSAGE_AUDIT_APPLY_1_'), get_nickname( is_login()) . L('_MESSAGE_AUDIT_APPLY_2_') . $event_content['title'] . L('_MESSAGE_AUDIT_APPLY_3_'), 'Event/Index/detail', array('id' => $event_id));
            $this->success(L('_SUCCESS_DELETE_').L('_EXCLAMATION_'));
        } else {
            $this->error(L('_ERROR_OPERATION_FAIL_').L('_EXCLAMATION_'));
        }
    }

    public function comment() {
        $id = I('id', 0, 'intval') ;
        $event = $this->checkEventAlive($id) ;

        $map['app'] = 'Event' ;
        $map['mod'] = 'event' ;
        $map['status'] = 1 ;
        $map['row_id'] = $id ;
        $count = D('local_comment')->where($map)->count() ;
        $this->assign('count', $count) ;
        $this->assign('event', $event) ;
        $this->setTitle('活动讨论区') ;
        $this->display() ;
    }

    public function getComment(){
        $id = I('id', 0, 'intval') ;
        $cid = I('cid', 0, 'intval') ;
        $page = I('page', 1, 'intval') ;
        $flag = 0 ;//控制评论列表，评论数量的图标功能
        $event = $this->checkEventAlive($id) ;
        $map['app'] = 'Event' ;
        $map['status'] = 1 ;
        if ($cid>0){
            $comment = D('LocalComment')->checkCommentAlive($cid, $id) ;
            if ($comment == false) $this->error('评论不存在，或已删除~') ;
            $map['mod'] = 'lzl_comment' ;
            $map['row_id'] = $cid ;
            $flag = 1 ;
        }else{
            $map['mod'] = 'event' ;
            $map['row_id'] = $id ;
        }

        $comments = D('local_comment')->where($map)->order('create_time desc')->page($page, 10)->select() ;
        foreach ($comments as &$val){
            $val = D('LocalComment')->getComment($val) ;
        }
        unset($val) ;
        $this->assign('flag', $flag) ;
        $this->assign('eid', $event['id']) ;
        $this->assign('comments', $comments) ;
        $html = $this->fetch('_commentlist') ;
        $this->ajaxReturn(array('status'=>1 ,'data'=>$html, 'info'=>L('_SUCCESS_'))) ;
    }

    /**获取活动信息
     * @param int $id
     * @return mixed
     * @author szh(施志宏) szh@ourstu.com
     */
    private function checkEventAlive($id=0) {
        $event = D('event')->where(array('id'=>$id, 'status'=>1))->find() ;
        if ($event == false){
            $this->error(L('_EVENT_NOT_EXIST_').L('_EXCLAMATION_'));
            exit;
        }
        return $event ;
    }

    /**
     * 对单条评论的延伸评论
     */
    public function lzlComment() {
        $id = I('id', 0, 'intval') ;
        $eid = I('eid', 0, 'intval') ;
        $event = $this->checkEventAlive($eid) ;

        $comment = D('LocalComment')->getComment($id) ;
        if ($comment == false || $comment['status'] != 1){
            $this->error('评论不存在，或已删除！', U('Index/detail',array('id'=>$eid))) ;
        }

        $map['app'] = 'Event' ;
        $map['mod'] = 'lzl_comment' ;
        $map['status'] = 1 ;
        $map['row_id'] = $id ;
        $count = D('LocalComment')->where($map)->count() ;
        $this->assign('comments', array($comment)) ;
        $html = $this->fetch('_commentlist') ;

        $this->assign('event', $event) ;
        $this->assign('comment', $comment) ;
        $this->assign('count', $count) ;
        $this->display() ;
    }

    /**
     * search
     * 查询热门搜索
     * @auth wb
     */
    public function search(){
        $change=D("event");
        if(IS_POST){
            $page=I("post.page","","intval");
            $result=$change->where(array('status'=>1))->order("view_count desc")->page($page,8)->field("id,title")->select();
            //如果是最后一组了就从第一组查
            if($result==null){
                $result=$change->where(array('status'=>1))->order("view_count desc")->page(1,8)->field("id,title")->select();
                $res['is']=0;
            }
            foreach ($result as &$val) {
                $val['url'] = U('Event/index/detail',array('id'=>$val['id'])) ;
            }
            unset($val) ;
            $res['status']="1";
            $res['data']=$result;
            $this->ajaxReturn($res);
        }
        else{
            $history=D("event_search");
            $result=$change->where(array('status'=>1))->order("view_count desc")->page(1,8)->select();
            $this->assign("result",$result);
            $historyResult=$history->where(array("uid"=>get_uid()))->order("create_time desc")->limit(10)->select();
            $this->assign("historyResult",$historyResult);
            $this->display();
        }
    }

    /**
     * allSearch
     * 历史记录
     * @auth wb
     */
    public function allSearch(){
        $is=0;
        $title=I("post.title","","text");
        $parame['status']=1;
        $parame['title']=array("like","%".$title."%");
        $change=D("event");
        $uid = is_login() ;
        if(I("post.is","","text")=="no"){
            $result=$change->where($parame)->select();
            if(!$result){
                $this->ajaxReturn(array("status"=>"1","html"=>"none"));
            }
            $result = $this->getListInfo($result, $uid) ;
            $this->assign("list", $result);
            $html = $this->fetch('_list');
            $this->ajaxReturn(array("status" => "1","html" => $html));
        }
        $result=$change->where($parame)->select();
        $history=D("event_search");
        $historyContent=$history->where(array('uid'=>get_uid()))->select();
        foreach ($historyContent as $val){
            if($val["historical"]==$title){
                $is=1;
            } ;
        }
        unset($val) ;
        if($is==0 && $uid>0){
            $data["uid"]=$uid;
            $data["historical"]=$title;
            $data["create_time"]=time();
            $history->add($data);
        }
        if ($uid > 0){
            $historyResult=$history->where(array("uid"=>$uid))->order("create_time desc")->limit(3)->select();
            $this->assign("historyResult",$historyResult);
        }
        $myHtml=$this->fetch("_history");
        if(!$result){
            $this->ajaxReturn(array("status"=>"1","html"=>"none","data"=>$myHtml));
        } else {
            $result = $this->getListInfo($result, $uid) ;
            $this->assign("list", $result);
            $html = $this->fetch('_list');
            $this->ajaxReturn(array("status" => "1", "html" => $html,"data"=>$myHtml));
        }
    }

    /**
     * delete
     * 删除一条历史记录
     * @auth wb
     */
    public function delete(){
        $history=D("event_search");
        $id=I("post.id","","intval");
        $uid = is_login() ;
        $history->where(array("uid"=>$uid,"id"=>$id))->delete();
        $historyResult=$history->where(array("uid"=>$uid))->order("create_time desc")->limit(3)->select();
        $this->assign("historyResult",$historyResult);
        $myHtml=$this->fetch("_history");
        $this->ajaxReturn(array("status" => "1", "html" => $myHtml));
    }

    /**
     * allDelete
     * 清空历史记录
     * @auth wb
     */
    public function allDelete(){
        $history=D("event_search");
        $history->where(array("uid"=>is_login()))->delete();
        $this->ajaxReturn(array("status" => "1"));
    }
}