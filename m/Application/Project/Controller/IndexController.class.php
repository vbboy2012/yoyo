<?php
/**
 * Created by PhpStorm.
 * User: 王杰
 * Date: 2017/6/2
 * Time: 10:22
 */

namespace Project\Controller;
use Think\Controller;

class IndexController extends Controller
{
    public function _initialize()
    {
        if(!is_login()){
            $config = D('Weixin/WeixinConfig')->getWeixinConfig();
            if($config['WX_TYPE']==2){
                S('weixin_token', null);
                session_start();
                $_SESSION['projectUrl']='project';
                redirect('http://'.$_SERVER['HTTP_HOST'].str_replace('/index.php','/index.php',$_SERVER['SCRIPT_NAME']).'/ucenter/member/login');
            }
        }
    }

    public function index()
    {
        $this->setTitle('项目');
        $this->setKeywords("项目");
        $this->setDescription("项目");
        //广告
        $map['status']=1;
        $map['end_time']=array('gt',time());
        $advertisement=S('index_advertisement');
        if ($advertisement===false){
            $advertisement=D('project_advertisement')->where($map)->select();
            foreach ($advertisement as &$val){
                $val['img']=getThumbImageById($val['imgid'],5000,5000);
            }
            unset($val);
            S('index_advertisement',$advertisement,86400);
        }
        $advertisementCount=D('project_advertisement')->where($map)->count();
        $this->assign('advertisementCount', $advertisementCount);
        $this->assign('advertisement', $advertisement);
        //项目
        $model = D('Project/Project');
        $map['uid']=is_login();
        $map['status']=1;
        $private= $model->getList(array('field'=>'id','where'=>array('is_private'=>1,$map)));
        $public=$model->getList(array('field'=>'id','where'=>array('is_private'=>0,'status'=>1,'uid'=>1)));

        $is_follow = D('project_user')->where(array('uid'=>is_login()))->getField('subscribe');
        $userCount = D('project_user')->where(array('uid'=>is_login()))->count();
        //消息
        $count=D('message')->where(array('to_uid'=>get_uid(),'is_read'=>0,"type"=>"Project_project"))->count();

        $this->assign('count',$count);
        $this->assign('userCount',$userCount);
        $this->assign('is_follow',$is_follow);

        $this->assign('private_count',count($private));
        $this->assign('totalCount',count($public));
        $this->display();
    }


    /**
     * @param
     * @auth sun slf02@ourstu.com
     * @return 首页加载
     */
    public function loadIndex(){
        $page=I('post.page',1,'intval');
        $type=I('post.type','private','string');
        //个人项目
        $map=array();
        $map['uid']=is_login();
        if($type=='private'){
            $map['status']=1;
            $map['progress']=array('lt',100);
            $map['is_private']=1;
        }
        //公共项目
        if($type=='public'){
            $userCount = D('project_user')->where(array('uid'=>is_login(),'subscribe'=>1))->count();
            if(!$userCount){
                $html='<li class="follow"><p>抱歉您还没有订阅，不能查看公共项目。</p><p>赶紧去订阅吧，订阅按钮在右上方!!</p></li>';
                $arr=array('data'=>$html,'status'=>1,'info'=>'获取成功');
                $this->ajaxReturn($arr);
            }else{
                $map['status']=1;
                $map['progress']=array('lt',100);
                $map['is_private']=0;
            }
        }
        $model = D('Project/Project');
        $projects = $model->getList(array('field'=>'id','where'=>$map,'page'=>$page));

        foreach ($projects as &$v) {
            $v = $model->getProject($v);
            //最近的一条进度
            $v['last'] = D('Project/Progress')->lastProgress($v['id']);
            $v['count']=D('Project/Progress')->where(array('project_id'=>$v['id']))->count();
        }
        unset($v);
        if($projects){
            $this->assign('project',$projects);
            $html=$this->fetch('_list');
            $arr=array('data'=>$html,'status'=>1,'info'=>'获取成功');
            $this->ajaxReturn($arr);
        }else{
            $this->ajaxReturn(array('data'=>'','status'=>1,'info'=>'获取失败'));
        }

    }

    public function schedule($page=1){
        $this->setTitle('项目进度');
        $this->setKeywords("项目进度");
        $this->setDescription("项目进度");
        $id=I('get.id','','intval');
        if(IS_POST){
            $id=I('post.id','','intval');
            $model = D('Project/Project');
            $projects=$model->getProject($id);
            $progress=D('Project/Progress')->lastProgress($id);
            $projects['lasttitle']= empty($progress) ? '研发':$progress['title'];
            $projects['cover']=getThumbImageById($projects['cover'],150,150);
            $this->ajaxReturn($projects);
        }else{
            $model = D('Project/File');
            $map['status']=1;
            $map['project_id']=$id;
            $list = $model->getList(array('field'=>'id','where'=>$map,'order'=>'create_time desc'));
            foreach ($list as &$v) {
                $v = $model->getFile($v);
                $v['file_url']=get_file_url($v['file']);
                $v['file']=get_file($v['file']);
                $v['name']=$v['name'] ? $v['name']:$v['file']['name'];
            }
            unset($v);
            //$projects_file=D('Project/File')->getFile($id);
            $projects=D('Project/Project')->getProject($id);
            $progress=D('Project/Progress')->where(array('project_id'=>$id))->page($page,10)->order('create_time desc')->select();
            $this->assign('pro',$list);
            $this->assign('progress',$progress);
            $this->assign('project',$projects);
            //$this->assign('projects_file',$projects_file);

          //  dump($projects_file['file']['file_url']);exit();
            $this->display();
        }


    }

    public function loadSchedule(){

        $id=I('post.id',1,'intval');
        $page=I('post.page',1,'intval');

        $progress=D('Project/Progress')->where(array('project_id'=>$id))->page($page,10)->order('create_time desc')->select();
        if($progress){
            $this->assign('progress',$progress);
            $html=$this->fetch('_progress');
            $arr=array('data'=>$html,'status'=>1,'info'=>'获取成功');
            $this->ajaxReturn($arr);
        }else{
            $arr=array('data'=>'','status'=>1,'info'=>'获取失败');
            $this->ajaxReturn($arr);
        }

    }
    /**
     * @param
     * @auth sun slf02@ourstu.com
     * @return 消息会话
     */
    public function message()
    {
        $uid = is_login();
        $messageModel = D('Common/Message');
        $messageTypeList = $messageModel->getMyMessageSessionList($uid);
        //dump($messageTypeList);exit();
//        foreach($messageTypeList as &$value){
//            $value['detail']['logo'] = get_attach_path($value['detail']['logo']);
//        }
//        $this->ajaxReturn($messageTypeList);
//        dump($messageTypeList);exit;
        $count=D('message')->where(array('to_uid'=>get_uid(),'is_read'=>0,"type"=>"Project_project"))->count();

        $this->assign('count',$count);
        $this->assign('message',$messageTypeList);
        $this->display();
    }

    /**
     * @param
     * @auth sun slf02@ourstu.com
     * @return 消息列表
     */
    public function messageList(){
        $mid = is_login();
        $messageType = I('get.type','','text');
        $aPage = I('get.page',1,'intval');
        $aIsPull = I('get.is_pull','0','intval');
        $msgMod = M('Message');
        $map['to_uid'] = $mid;
        if($messageType){
            $messageType = ucfirst($messageType);
            $map['type'] = $messageType;
        }
        $map['status'] = 1;
        $messages = $msgMod->where($map)->order('id desc')->page($aPage,10)->select();
        foreach($messages as &$messageInfo){
            $messageInfo = $this->_dealMessage($messageInfo);
        }
        unset($messageInfo);
        //设置未读消息为已读
        D('Common/Message')->setAllReaded($mid,$messageType);
        $this->assign('first_message_num',count($messages));
        $this->assign('type',$messageType);
        $this->assign('type_title',get_message_title($messageType));
        $this->assign('message',$messages);
        if ($aIsPull) {
            $data['html'] = '';
            $data['status'] = 1;
            $data['html'] .= $this->fetch('_messagelist');
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    /**
     * 设置已读
     */
    public function setAllReaded()
    {
        $uid = $this->requireIsLogin();
        $id = I_POST('id','intval');
        if($id){
            $messages = D('message')->where(array('to_uid='=> $uid, 'is_read'=>0 ,'id'=>$id))->setField('is_read', 1);
        }else{

            $messages = D('message')->where(array('to_uid='=> $uid, 'is_read'=>0))->setField('is_read', 1);
        }
    }

    public function _dealMessage($messageInfo){
        if (in_array($messageInfo['type'], array('1', '2', '3', '0', '4', '5'))) {
            $messageInfo['type'] = 'Common_system';
        }
        $messageInfo['content'] = D('Common/Message')->getContent($messageInfo['content_id']);
        if (is_array($messageInfo['content']['content'])) {
            $messageInfo['content']['untoastr'] = 1;
        }
        if(!$messageInfo['content']['user']){
            $messageInfo['content']['user'] = array();
        } else {
            $messageInfo['content']['user']['space_mob_url'] = U('Ucenter/Index/mine/', array('uid' => $messageInfo['content']['user']['uid']));
        }
        if($messageInfo['type'] == 'Weibo'){
            $messageInfo['content']['weibo_data'] = D('Weibo/weibo')->getWeiboDetail($messageInfo['content']['args']['id']);
        }
        return $messageInfo;
    }

    public function user()
    {
        if(!is_login()){
            $this->_initialize();
        }
        else{
            $uid=is_login();

            $model=D('Project/Project');
            if(IS_POST){
                $sales=D('business_user')->where(array('uid'=>$uid))->getField('code');
                $sales_img=getThumbImageById($sales['code'],150,150);
                $this->ajaxReturn($sales_img);
            }
            $user = query_user(array('nickname','avatar128'),$uid);
            $totalCount=$model->where(array('uid'=>$uid,'status'=>array('egt',1)))->count();
            $sales=D('business_user')->where(array('uid'=>$uid))->find();
            $sales_user = query_user(array('nickname','avatar128'),$sales['sales_uid']);

            //进行中的工单
            $map['status']=array('neq',4);
            $underway_ticket=$model->getticket($map);
            $is_mobile = query_user('mobile', $uid);
            $this->assign('is_mobile', $is_mobile['mobile']);
            $is_sync = M('sync_login')->where(array('uid' => $uid, 'status' => 1))->find();
            $this->assign('is_sync', $is_sync);
            $this->assign('user',$user);
            $this->assign('sales_user',$sales_user);
            $this->assign('totalCount',$totalCount);
            $this->assign('ticket',$underway_ticket);
            $this->display();
        }
    }

    public function safe()
    {
        $uid = is_login();
        if(IS_POST) {
            $sync = I('post.sync', '', 'intval');
            $mobile = I('post.mobile', '', 'intval');

            if($sync == 1) {
                $tel = query_user('mobile', $uid);
                if(!$tel['mobile']) {
                    $this->error('请先绑定手机号再解绑微信！');
                }
                $res = M('sync_login')->where(array('uid' => $uid))->delete();
//                $res = M('sync_login')->where(array('uid' => $uid))->setField('status', -1);
                if($res) {
                    $this->success('解绑成功~，即将刷新页面');
                } else {
                    $this->error('解绑失败！');
                }
            }

            if($mobile == 1) {
                $res = UCenterMember()->where(array('id' => $uid))->setField('mobile', null);
                clean_query_user_cache($uid, 'mobile');
                if($res) {
                    $this->success('解绑成功~，即将刷新页面');
                } else {
                    $this->error('解绑失败！');
                }
            }

        }
    }

    /**
     * @param
     * @auth sun slf02@ourstu.com
     * @return 工单列表
     */
    public function ticketList(){

        $model=D('Project/Project');
        if(IS_POST){
            $map['status']=array('eq',4);
            $finish_ticket=$model->getticket($map);
            $this->assign('ticket',$finish_ticket);
            $html=$this->fetch('_ticketfinish');
            $arr=array('data'=>$html,'status'=>1,'info'=>'获取成功');
            $this->ajaxReturn($arr);
        }else{
            $map['status']=array('neq',4);
            $underway_ticket=$model->getticket($map);
            $count=D('ticket_list')->where(array('status'=>array('eq',4),'uid'=>get_uid()))->count;
            $this->assign('count',$count);
            $this->assign('ticket',$underway_ticket);
            $this->display();
        }


    }
    /**
     * @param
     * @auth sun slf02@ourstu.com
     * @return  进度报告详情页面
     */
    public function detail(){

        $id=I('get.id','','intval');

        $model=D('Project/Progress');
        $model->where(array('id'=>$id))->setField('is_check',1);
        $list=$model->getProgress($id);
        $this->assign('list',$list);
        $this->display();

    }

    /**
     * @param
     * @auth sun slf02@ourstu.com
     * @return 完成项目
     */
    public function finish(){

        $page=I('post.page',1,'intval');

        $map['status']=1;
        $map['progress']=100;

        $model = D('Project/Project');
        $projects = $model->getList(array('field'=>'id','where'=>$map,'$page'=>$page));
        $userCount = D('project_user')->where(array('uid'=>is_login(),'subscribe'=>0))->count();
        foreach ($projects as &$v) {
            $v = $model->getProject($v);
        }
        unset($v);
        if(IS_POST){
            $this->assign('project',$projects);
            $this->assign('userCount',$userCount);
            $html=$this->fetch('_finish');
            $arr=array('data'=>$html,'status'=>1,'info'=>'获取成功');
            $this->ajaxReturn($arr);
        }else{
            $this->assign('project',$projects);
            $this->assign('userCount',$userCount);
            $this->display();
        }

    }

    /**
     * @param
     * @auth sun slf02@ourstu.com
     * @return  订阅弹窗
     */
    public function setUser(){

        if(IS_POST){
            $type=I('post.type','','text');

            $data['uid']=is_login();
            if($type=='false'){
                $data['is_subscribe']=0;
                D('Project/User')->add($data);
                $arr=array('status'=>0);
                $this->ajaxReturn($arr);
            }elseif($type == 'true'){
                $data['subscribe']=1;
                $res=D('project_user')->add($data);
                $arr=array('status'=>$res,'info'=>'订阅成功');
                $this->ajaxReturn($arr);
            }
        }else{
            $this->error('非法操作');
        }

    }

    /**
     * @param
     * @auth sun slf02@ourstu.com
     * @return 取消和订阅按钮
     */
    public function follow(){
        $follow=I('post.follow','','intval');
        if (!is_login()){
            $this->error('请登录');
        }
        if ($follow==1){
            $res=D('project_user')->where(array('uid'=>is_login()))->setField('subscribe',0);
            if ($res){
                $this->success('取消订阅成功');
            }else{
                $this->error('未知错误');
            }
        }else{
            $res=D('project_user')->where(array('uid'=>is_login()))->setField('subscribe',1);
            if ($res){
                $this->success('订阅成功');
            }else{
                $this->error('未知错误');
            }
        }
    }
}