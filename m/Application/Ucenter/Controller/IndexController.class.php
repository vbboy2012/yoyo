<?php
namespace Ucenter\Controller;

use Think\Controller;
require_once APP_PATH.'User/Common/common.php';
require_once(APP_PATH . '/User/Conf/config.php');
class IndexController extends Controller
{
    protected $attestModel,$attestTypeModel;
    public function _initialize()
    {
        $this->attestModel=D('Ucenter/Attest');
        $this->attestTypeModel=D('Ucenter/AttestType');
        $this->assign('bottom_flag','mine');
        $this->setTitle('广场');
    }

    public function index($uid=null)
    {

        $this->setTitle('我');
        $this->setKeywords("我");
        $this->setDescription("我");
        $aIsShare = I('get.is_share',0,'intval');
        if(empty($uid)){
            $uid=is_login();
        }
        if($uid!=is_login()){
           redirect('404');
        }
        $model=new \Core\Model\CheckInModel();
        $check = $model->getCheck($uid);
        if($check){
            $this->assign("check", true);
        }else{
            $this->assign("check", false);
        }
        $user_info = query_user(array('avatar64', 'nickname', 'uid', 'space_url','space_mob_url', 'icons_html', 'score', 'title', 'fans', 'following', 'weibocount', 'rank_link', 'signature','con_check', 'total_check'), $uid);
        $this->assign("uid", $uid);
        $this->assign('user_info', $user_info);
        $friend_list=$this->_myfriend($uid);
        $friends=count($friend_list);
        $this->assign('friends',$friends);
        $this->assign('is_share',$aIsShare);
        $this->display();

    }
    public function edit(){
        $this->setTitle('基本资料');
        $this->setKeywords("基本资料");
        $this->setDescription("基本资料");
        $aUid = I('get.uid', 0, 'intval');
        if (!$aUid) {
            redirect(U('Weibo/Index/index'));
        }
        $userData=$this->_userData($aUid);
        $userData['pos_province']=str_replace('省','',$userData['pos_province']);
        $userData['pos_province']=str_replace('区','',$userData['pos_province']);
        $userData['pos_city']=str_replace('市','',$userData['pos_city']);
        $this->assign('user',$userData);
        $this->assign('uid',$aUid);
        $this->display();
    }
    public function mine(){
        $this->setTitle('个人中心');
        $this->setKeywords("个人中心");
        $this->setDescription("个人中心");
        $aUid = I('get.uid', 0, 'intval');
        if (!$aUid) {
            redirect(U('Weibo/Index/index'));
        }
        $user_info =$this->_userData($aUid);
        $friend_list=$this->_myfriend($aUid);
        $weibo_list=$this->_myWeibo($aUid);
        $mycrowd_list=$this->_myCrowd($aUid);
        $crowd_list=$this->_crowd($aUid);
        $this->assign('weibo_list', $weibo_list);
        $this->assign('friend_list', $friend_list);
        $this->assign('mycrowd_list', $mycrowd_list);
        $this->assign('crowd_list', $crowd_list);
        $this->assign('uid', $aUid);
        $this->assign('page', 1);
        $this->assign('user_info', $user_info);
        $this->display();
    }
   public function follow(){
       if(!is_login()){
           $data['status']=0;
           $data['info']='先去登录吧';
           $this->ajaxReturn($data);
       }
       $aUid=I('post.uid',0,'intval');
       $aType=I('post.type','mufriend','text');
       $res=D('Follow')->$aType($aUid);
       S('userResult',null);
       $this->ajaxReturn($res);
   }
    /**
     * @param $uid
     * 获得我的好友
     */
    private function _myfriend($uid){
        $aPage = I('get.page', 1, 'intval');
        $aCount = I('get.count', 10, 'intval');
        $uids=D('Follow')->page($aPage,$aCount)->getMyFriends($uid);
        $k=0;
        foreach ($uids as $val){
            $user_info[$k] = query_user(array('avatar64', 'nickname', 'uid', 'space_url', 'space_mob_url', 'title', 'fans', 'mufriending', 'signature'), $val);
            $k++;
        }
        unset($val,$k);

        foreach ($user_info as &$v){
            $v['is_follow']=D('Common/Follow')->isFollow(is_login(),$v['uid']);
        }
        return $user_info;
    }
    public function addMoreFriend(){
        $aPage = I('post.page', 1, 'intval');
        $aCount = I('post.count', 10, 'intval');
        $aUid=I('post.uid',0,'intval');
        $uids=D('Follow')->page($aPage,$aCount)->getMyFriends($aUid);
        if(empty($uids)){
            $data['status']=0;
            $data['info']='没有更多了';
            $this->ajaxReturn($data);
        }
        $k=0;
        foreach ($uids as $val){
            $user_info[$k] = query_user(array('avatar64', 'nickname', 'uid', 'space_url', 'space_mob_url', 'title', 'fans', 'following', 'signature'), $val);
            $k++;
        }
        unset($val,$k);
        foreach ($user_info as &$v){
            $v['is_follow']=D('Common/Follow')->isFollow(is_login(),$v['uid']);
        }
        $this->assign('friend_list',$user_info);
        $html=$this->fetch('_friend');
        $this->ajaxReturn($html);
    }
    /**
     * @param $uid
     * @return mixed
     * 获取所有微博信息
     */
    private  function _myWeibo($uid){
        require_once('./Application/Weibo/Common/function.php');
        $aPage = I('get.page', 1, 'intval');
        $param = array();
        //查询条件
        $weiboModel = D('Weibo/Weibo');
        $param['field'] = 'id';
        $param['page'] = $aPage;
        $param['count'] = 10;
        $param['where']['status'] = 1;
        $param['where']['uid'] = $uid;
        $invisibleList = D('Weibo/WeiboCrowd')->getInvisible($uid, 1) ;
        if (!empty($invisibleList)) {
            $iArray = array_column($invisibleList, 'id') ;
            $param['where']['crowd_id'] = array('not in',$iArray) ;
        }
        $list = $weiboModel->getWeiboList($param);
        foreach ($list as $val) {
            $weibo[] = D('Weibo/Weibo')->getWeiboDetail($val);
        }
        unset($val);
        foreach ($weibo as &$v){
            $v['is_follow']=D('Common/Follow')->isFollow(is_login(),$v['uid']);
        }
        $this->assign('weibo', $weibo);
        $this->assign('weibo_page', $aPage);
        return $weibo;
    }


    public  function addMoreWeibo(){
        require_once('./Application/Weibo/Common/function.php');
        $aPage = I('post.page', 1, 'intval');
        $aUid=I('post.uid',0,'intval');
        $param = array();
        //查询条件
        $weiboModel = D('Weibo/Weibo');
        $param['field'] = 'id';
        $param['page'] = $aPage;
        $param['count'] = 10;
        $param['where']['status'] = 1;
        $param['where']['uid'] = $aUid;

        $list = $weiboModel->getWeiboList($param);
        if(empty($list)){
            $data['status']=0;
            $data['info']='没有更多了';
            $this->ajaxReturn($data);
        }
        foreach ($list as $val) {
            $weibo[] = D('Weibo/Weibo')->getWeiboDetail($val);
        }
        $this->assign('weibo_list',$weibo);
        $html=$this->fetch('_weibo');
        $this->ajaxReturn($html);

    }

    /**
     * @param $uid
     * @return mixed
     * 用户资料
     */
    private function _userData($uid){
        $userdata=D('Member')->where(array('uid'=>$uid))->find();
        $userdata['pos_province'] = M('district')->where(array('id' => $userdata['pos_province']))->getField('name');
        $userdata['pos_city'] = M('district')->where(array('id' => $userdata['pos_city']))->getField('name');
        $userdata['pos_district'] = M('district')->where(array('id' => $userdata['pos_district']))->getField('name');
        $userdata['user']=query_user(array('nickname','email','mobile','birthday','following','avatar64','signature','space_mob_url'), $uid);
        return $userdata;
    }

    public function avatar(){
        $this->setTitle('头像');
        $this->setKeywords("头像");
        $this->setDescription("头像");
        if(IS_POST){
            $aUid=I('post.uid',0,'intval');
            if($aUid!=is_login()){
                $data['status']=0;
                $data['info']='无法修改';
            }
            else{
                $data['status']=1;
            }
            $this->ajaxReturn($data);
        }

        $data=query_user('avatar512',is_login());
        $this->assign('avatar',$data['avatar512']);
        $this->assign('uid',is_login());
        $this->display();
    }
    private function _myCrowd($uid){
        $aPage=I('get.page',1,'intval');
        $list=D('Weibo/WeiboCrowd')->getMyCrowd($aPage,$uid);
        return $list;
    }
    public function addMoreMyCrowd(){
        $aPage=I('post.page',1,'intval');
        $aUid=I('post.uid',0,'intval');
        $list=D('Weibo/WeiboCrowd')->getMyCrowd($aPage,$aUid);
        if(empty($list)){
            $data['status']=0;
            $data['info']='没有更多了';
            $this->ajaxReturn($data);
        }
        $this->assign('mycrowd_list',$list);
        $html=$this->fetch('_mycrowd');
        $this->ajaxReturn($html);
    }
    private function _crowd($uid){
        $aPage=I('get.page',1,'intval');
        $list=D('Weibo/WeiboCrowdMember')->getMyJoinCrowd($aPage,$uid);
        foreach ($list as &$v){
            $v['detail']=D('Weibo/WeiboCrowd')->getCrowd($v['crowd_id']);
        }
       return $list;
    }
    public function addMoreCrowd(){
        $aPage=I('post.page',1,'intval');
        $aUid=I('post.uid',0,'intval');
        $list=D('Weibo/WeiboCrowdMember')->getMyJoinCrowd($aPage,$aUid);
        if(empty($list)){
            $data['status']=0;
            $data['info']='没有更多了';
            $this->ajaxReturn($data);
        }
        foreach ($list as &$v){
            $v['detail']=D('Weibo/WeiboCrowd')->getCrowd($v['crowd_id']);
        }
        $this->assign('crowd_list',$list);
        $html=$this->fetch('_crowd');
        $this->ajaxReturn($html);
    }


    public  function rank(){
        $this->setTitle('排行榜');
        $this->setKeywords("排行榜");
        $this->setDescription("排行榜");
        if(IS_POST){
            $aUid=I('post.uid',0,'intval');
            if(!is_login()||is_login()!=$aUid){
                $data['status']=0;
                $data['info']='非正常登录';
                $this->ajaxReturn($data);
            }
            $addon   = new \Core\Controller\CheckInController();
            $res=$addon->doCheckIn();
            if ($res) {
                $check = query_user(array('con_check', 'total_check'), $aUid);
                $this->ajaxReturn(array('status' => 1, 'info' => '签到成功!', 'con_check' => $check['con_check'], 'total_check' => $check['total_check']));
            } else {
                $this->ajaxReturn(array('status' => 0,'info' =>'已经签到了！'));
            }
        }else{
            $memberModel = D('Member');
            $uid=is_login();
            $user=query_user(array('avatar512','nickname','con_check', 'total_check'), $uid);
            $model=new \Core\Model\CheckInModel();
            $con=$model->getRank('con');
            $total=$model->getRank('total');
            $rankList=array();
            $p=1;
            foreach ($con as $co){
                if($co['uid']==is_login()){
                    $rankList['con_check_rank'] = $p;
                }else{
                    $p++;
                }

            }

            $q=1;
            foreach ($total as $to){
                if($to['uid']==is_login()){
                    $rankList['total_check_rank'] =$q;
                }else{
                    $q++;
                }

            }
             unset($p,$q,$to,$co);
            //排行榜个人排名
            $userScore = $memberModel->where(array('uid' => $uid))->field('fans')->find();
            $rankList['my_fans']=count2str($userScore['fans']);

            $tag='fans_rank';
            $user_fans_list=S($tag);
            if(empty($user_fans_list)){
                $user_fans_list = $memberModel->where(array('status' => 1,'fans'=>array('gt',0)))->field('uid,fans,nickname')->order('fans desc,uid asc')->limit(10)->select();
                foreach ($user_fans_list as &$u) {
                    $temp_user = query_user(array('avatar512'), $u['uid']);
                    $u['avatar512'] = $temp_user['avatar512'];
                }
                S($tag,$user_fans_list,60*60);
            }

            $k=1;
            foreach ($user_fans_list as $vo){
                if($vo['uid']==is_login()){
                    $rankList['fans_rank'] = count2str($k);
                }else{
                    $k++;
                }

            }
            unset($k,$vo);
            $this->assign('user',$user);
            $this->assign('con',$con);
            $this->assign('total',$total);
            $this->assign('fans_list',$user_fans_list);
            $this->assign('rank',$rankList);
            $this->display();
        }

    }
    public function handleData($data){
        foreach ($data as &$v){
            $v['user']['is_follow']=D('Common/Follow')->isFollow(is_login(),$v['user']['uid']);
        }
        unset($v);
        return $data;
    }
    public function fansList($aType,$aUid,$page=1){
        switch ($aType) {
            case 'friends':
                $list = $this->_myfriend($aUid);
                $k=0;
                foreach ($list as $v){
                    $data[$k]['user']=$v;
                    $k++;
                }
                unset($v,$k);
                if($aUid==is_login()){
                    $title='我的好友';
                }else{
                    $title='他的好友';
                }
                $this->assign('type','friends');
                break;
            case 'fans':
                if($aUid==is_login()){
                    $title='我的粉丝';
                }else{
                    $title='他的粉丝';
                }
                $data = D('Follow')->getFans($aUid, $page, array('avatar128', 'uid', 'nickname', 'fans', 'following', 'weibocount', 'space_url', 'title','space_mob_url'));
                $data=$this->handleData($data);
                $this->assign('type','fans');
                break;
            case 'follow':
                if($aUid==is_login()){
                    $title='我的信任';
                }else{
                    $title='他的信任';
                }
                $this->assign('type','follow');
                $data = D('Follow')->getFollowing($aUid, $page, array('avatar128', 'uid', 'nickname', 'fans', 'following', 'weibocount', 'space_url', 'title','space_mob_url'));
                $data=$this->handleData($data);
                break;
            default:
                if($aUid==is_login()){
                    $title='我的信任';
                }else{
                    $title='他的信任';
                }
                $this->assign('type','follow');
                $data = D('Follow')->getFollowing($aUid, $page, array('avatar128', 'uid', 'nickname', 'fans', 'following', 'weibocount', 'space_url', 'title','space_mob_url'));
                $data=$this->handleData($data);
        }
       return array($title,$data);
    }
    //我的信任、粉丝、好友
    public function fans(){
            $aUid = I('get.uid', 0, 'intval');
            $aType = I('get.type', 'follow', 'text');
            $aPage=I('get.page',1,'intval');
            $aPull=I('get.is_pull',0,'intval');
            $result=$this->fansList($aType,$aUid,$aPage);

            $this->assign('first_num',count($result[1]));
            $this->setTitle($result[0]);
            $this->setKeywords($result[0]);
            $this->setDescription($result[0]);
            $this->assign('title',$result[0]);
            $this->assign('data',$result[1]);
            $this->assign('page',$aPage);
            $this->assign('uid',$aUid);
            if($aPull==0){
                $this->display();
            }else{
                $data['html'] = '';
                $data['status'] = 1;
                $data['html'] .= $this->fetch('_fans');
                $this->ajaxReturn($data);
            }

        }

    public function getInfo()
    {
        $aUid = I('get.uid',0,'intval');
        $user = query_user(array('uid', 'nickname', 'avatar64','avatar_html64', 'space_url', 'following', 'fans', 'weibocount', 'signature', 'rank_link','pos_province', 'pos_city', 'pos_district', 'pos_community'), $aUid);
        $cover = modC('WEB_SITE_LOGO','','Config');
        $user['logo'] = getThumbImageById($cover,80,80);
        if ($aUid == is_login()) {
            $user['is_self'] = 1;
        } else{
            $user['is_self'] = 0;
        }
        $res = D('Common/Follow')->isFollow(is_login(), $aUid);
        if ($res == 1) {
            $user['follow_status'] = '已信任';
            $user['is_follow'] = 'unfollow';
        } else {
            $user['follow_status'] = '信任';
            $user['is_follow'] = 'follow';
        }
        $user['is_login'] = is_login();
        $user['is_wechat'] = is_weixin();
        if ($user) {
            $this->ajaxReturn(array('status'=>1,'data'=>$user));
        } else {
            $this->ajaxReturn(array('status'=>0));
        }
    }
    public function favorite(){

        $collection = D('Collect')->where(array('module'=>'Mall'))->count();
        $count= D('Collect')->where(array('module'=>'News'))->count();
        $this->assign('collection',$collection);
        $this->assign('count',$count);
        $this->display();
    }

    public function addList(){

        $aPage = I('post.page',1,'intval');
        $aCount = I('post.count',10,'intval');
        $aModule=I('post.module','Mall','text');

        $collection = D('Collect')->getCollection($aModule,'',$aPage,$aCount);
        foreach ($collection as &$v) {
            switch ($v['table']) {
                case 'collect':
                    $v['detail'] = D('Mall/Goods')->getGoods($v['row']);
                    if ($v['detail']['uid']) {
                        $v['user'] = query_user(array('nickname', 'uid', 'avatar128'), $v['detail']['uid']);
                    } else {
                        $v['user'] = query_user(array('nickname', 'uid', 'avatar128'), $collection['uid']);
                    }
                    break;
                case 'news':
                    $v=D('News/news')->where(array('id'=>$v['row']))->find();
                    $v['category']=D('New/news_category')->where(array('id'=>$v['category']))->getField('title');
                    $v['uid']=query_user(array('nickname','uid','avatar512'),$v['uid']);
                    break;
            }
        }
        unset($v);
        if($collection){
            if($aModule == 'Mall'){
                $this->assign('collection',$collection);
                $collection=$this->fetch('_favorite');
                $this->ajaxReturn(array(
                    'info'=>'请求成功',
                    'status'=>1,
                    'data'=>$collection
                ));
            }else{
                $this->assign('data',$collection);
                $collection=$this->fetch('_list');
                $this->ajaxReturn(array(
                    'info'=>'请求成功',
                    'status'=>1,
                    'data'=>$collection
                ));
            }
        }else{
            $this->ajaxReturn(array(
                'info'=>'请求失败',
                'status'=>1,
                'data'=>''
            ));
        }
    }
    public function wallet(){
        $this->setTitle('我的钱包');
        $this->setKeywords("我的钱包");
        $this->setDescription("我的钱包");
        //查询总金额
        $data=D('member')->field('score4')->where(array('uid'=>is_login()))->find();
        //查询领红包情况
        $data['score4']=number_format($data['score4'],2);
        if(IS_POST){
            $page=I('post.page',1,'intval');
            $dataList=D('consumption_log')->where(array('uid'=>is_login()))->page($page,10)->order('create_time desc')->select();
            foreach ($dataList as &$vo){
                $username=D('member')->where(array('id'=>$vo['id']))->field('nickname')->find();
                $vo['uid']=$username['nickname'];
                $vo['create_time']=friendlyDate($vo['create_time']);
            }
            unset($vo);
            if($dataList){
                $this->assign('dataList',$dataList);
                $html=$this->fetch('_walletlist');
                $this->ajaxReturn(array('status'=>1,'info'=>'请求成功','data'=>$html));
            }else{
                $this->ajaxReturn(array('data' => '', 'status' => 1, 'info' => '获取失败'));
            }
        }else{
            $count=D('consumption_log')->where(array('uid'=>is_login()))->count();
            //获取后台配置提现字段
            $fields=D('Order/Withdraw')->get_wi_field();
            $this->assign(array(
                'totalMoney'=>$data['score4'],
                'count'=>$count,
                'fields'=>$fields
            ));
            $this->display();
        }
    }

    public function pay()
    {
        include_once(APP_PATH."Ucenter/Lib/WxPayPubHelper.php");

        $jsApi = new \JsApi_pub();
        redirect($jsApi->createOauthUrlForCode(U('chooseMoney','',true,true)));

    }

    public function chooseMoney()
    {
        include_once(APP_PATH."Ucenter/Lib/WxPayPubHelper.php");
        $code = I('get.code','','html');
        $jsApi = new \JsApi_pub();
        $jsApi->code = $code;
        $openId = $jsApi->getOpenid();
        $this->assign('open_id',$openId);
        $this->display();
    }

    public function payapi()
    {
        include_once(APP_PATH."Ucenter/Lib/WxPayPubHelper.php");
        $openId = I('post.open_id',0,'html');
        $money = I('post.money',1,'intval');

        if (!is_login()) {
            $this->ajaxReturn(array('status'=>0,'info'=>'未登录'));
        }
//        $user = M('sync_login')->where(array('type'=>'weixin','oauth_token_secret'=>$openId))->find();
//        $rs = D('Ucenter/Score')->setUserScore(is_login(), $money/100, 4, 'dec');

        $jsApi = new \JsApi_pub();
        $unifiedOrder = new \UnifiedOrder_pub();

        $unifiedOrder->setParameter("openid",$openId);
        $unifiedOrder->setParameter("body","this is a test");
        $timeStamp = time();
        $out_trade_no = \WxPayConf_pub::APPID.$timeStamp;
        $unifiedOrder->setParameter("out_trade_no",$out_trade_no);
        $unifiedOrder->setParameter("total_fee",$money);//总金额
        $unifiedOrder->setParameter("notify_url",\WxPayConf_pub::NOTIFY_URL);
        $unifiedOrder->setParameter("trade_type","JSAPI");

        $prepay_id = $unifiedOrder->getPrepayId();
        $jsApi->setPrepayId($prepay_id);

        $jsApiParameters = $jsApi->getParameters();

        wx_pay(0,$out_trade_no,$openId,is_login());

        exit($jsApiParameters);
    }
    //修改密码
    public function resetPassword(){
        if (!is_login()){
            $this->error('请先登录');
        }
        if (IS_POST){
            $oldPassword=I('post.oldPassword','string');
            $newPassword=trim(I('post.newPassword','','string'));
            $RenewPassword=trim(I('post.RenewPassword','','string'));
            if ($oldPassword==null){
                $this->error('新密码不能为空');
            }
            if ($newPassword!=$RenewPassword||$newPassword==null||$RenewPassword==null){
                $this->error('密码不一致');
            }
            $oldPassword=think_ucenter_md5($oldPassword, UC_AUTH_KEY);
            $id=D('ucenter_member')->field('id')->where(array('password'=>$oldPassword,'id'=>is_login()))->find();
            if ($id==null){
                $this->error('旧密码不正确');
            }else{
                $newPassword=think_ucenter_md5($newPassword,UC_AUTH_KEY);
                if ($newPassword==$oldPassword){
                    $this->error('新旧密码一致，不能更改');
                }else{
                    $data['password']=$newPassword;
                    $res=D('ucenter_member')->where(array('id'=>is_login()))->save($data);
                    if ($res){
                        $this->success('修改成功！');
                    }else{
                        $this->error('修改失败，未知错误！');
                    }
                }
            }


        }
        $this->display();
    }

    public function square(){
        $map['status']=1;
        $map['location']=1;
        $advertisement=S('ucenter_square_advertisement');
        if ($advertisement===false){
            $advertisement=D('advertisement')->where($map)->select();
            foreach ($advertisement as &$val){
                $val['img']=getThumbImageById($val['imgid'],5000,5000);
            }
            unset($val);
            S('ucenter_square_advertisement',$advertisement,86400);
        }
        $count=D('advertisement')->where($map)->count();
        $this->assign('count', $count);
        $this->assign('advertisement', $advertisement);
        //渲染配置资讯
        $newsResult=S('newsResult');
        if($newsResult==false){
            $newsId = modC('SET_NEWS','');
            if($newsId!=""){
                $newsId=explode(',', $newsId);
                foreach ($newsId as $item) {
                    $newsResult[]=D('news')->where(array('status'=>'1','id'=>$item))->order("create_time desc")->find();
                }
                S('newsResult', $newsResult,3600);
            }

        }

        foreach ($newsResult as &$datum){
            $datum['category']=D('news_category')->where(array('id'=>$datum['category']))->getfield('title');
            $datum['uid']=query_user(array('uid','nickname','avatar512'),$datum['uid']);
            $datum['create_time']=friendlyDate($datum['create_time']);
        }
        unset($datum);
        $this->assign('data', $newsResult);
        //渲染配置达人
       $userResult=S('userResult');
        if($userResult==false){
            $userId = modC('SET_USER_ID','');
            if($userId!=""){
                $userId=explode(',', $userId);
                $follow=D('Follow')->where(array('who_follow'=>get_uid()))->getfield('follow_who',true);

                foreach ($userId as $item) {
                    $list=query_user(array('nickname','avatar512','signature','uid'),$item);
                    if(in_array($item,$follow)) {
                        $list=array_merge(array('is_follow' => 1), $list);
                    }
                    $userResult[]=$list;
                }
                unset($item);
                S('userResult', $userResult,3600);
            }

        }
        $this->assign('friend_list', $userResult);
        $this->display();
    }

    //申请认证
    public function condition(){
        //D('Ucenter/AttestType')->getData;
        $this->_checkAttestConditions();
        $this->_checkAttestStatus();

        $this->display();
    }

    //认证类型
    public function identify(){

        $attestType=$this->attestTypeModel->getTypeList();
        $this->assign('attest_type',$attestType);
        //dump($attestType);exit;
        $this->_checkAttestStatus();

        $this->display();
    }

    private function _checkAttestStatus($redirect=0)
    {
        $map['uid']=is_login();
        $map['status']=array('in','1,2,0');
        $attest=$this->attestModel->getData($map);
        //dump($attest);exit;
        if(!$attest){
            return false;
        }
        
        $attest['prove_image']=explode(',',$attest['prove_image']);
        $attest['image']=explode(',',$attest['image']);
        $attest['other_image']=explode(',',$attest['other_image']);
        $attest['type']=$this->attestTypeModel->getData($attest['attest_type_id'],1);
        if($attest['status']==1){
            if($redirect){
                redirect(U('Ucenter/Attest/process'));
            }
        }
        if($attest['status']==2||$attest['status']==0){
            $aChange=I('change',0,'intval');
            if(!$aChange){
                if($redirect){
                   $this->success("正在申请当中!");
                }
            }else{
                $this->assign('change',1);
            }
        }
        $this->assign('attest',$attest);
        return $attest;
    }
    private function _checkAttestConditions($attest_old=null)
    {
        if($attest_old){
            $attestType=$this->_checkTypeExist($attest_old['attest_type_id']);
        }else{

            $aId=I('get.id',0,'intval');
            //dump($aId);exit;
            $attestType=$this->_checkTypeExist($aId);
            $this->assign('myId',$aId);

        }
        $this->assign('attest_type',$attestType);

        //检测申请条件 start
        $can_apply=1;
        if($attestType['conditions']['avatar']==1){
            $avatar_user=query_user(array('avatar128'));
            $this->assign('user_avatar',$avatar_user['avatar128']);

            $map['uid']=is_login();
            $map['status']=1;
            $avatar=M('Avatar')->where($map)->find();
            if($avatar){
                $this->assign('avatar_ok',1);
            }else{
                $can_apply=0;
            }
        }
        if($attestType['conditions']['phone']==1){
            $mobile=query_user(array('mobile'));
            if($mobile['mobile']!=''){
                $this->assign('phone_ok',1);
            }else{
                $can_apply=0;
            }
        }
        $followModel=D('Follow');
        if($attestType['conditions']['follow']>0){
            $map_follow['who_follow']=is_login();
            $map_follow['follow_who']=array('neq','');
            $follow_count=$followModel->where($map_follow)->count();
            if($follow_count>$attestType['conditions']['follow']){
                $this->assign('follow_ok',1);
            }else{
                $can_apply=0;
            }
        }
        if($attestType['conditions']['fans']>0){
            $map_fans['follow_who']=is_login();
            $map_fans['who_follow']=array('neq','');
            $fans_count=$followModel->where($map_fans)->count();
            if($fans_count>$attestType['conditions']['fans']){
                $this->assign('fans_ok',1);
            }else{
                $can_apply=0;
            }
        }
        if($attestType['conditions']['friends']>0){
            $friendUids=$followModel->getMyFriends();
            $map_friend['uid']=array('in',$friendUids);
            $map_friend['status']=1;
            $friends_count=$this->attestModel->where($map_friend)->count();
            if($friends_count>$attestType['conditions']['fans']){
                $this->assign('friends_ok',1);
            }else{
                $can_apply=0;
            }
        }
        $this->assign('can_apply',$can_apply);
        //检测申请条件 end
        return $can_apply;
    }
    private function _checkTypeExist($id)
    {
        //dump($id);exit;
        $data=$this->attestTypeModel->getData($id,1);

        if(!$data){
            $this->error('该认证类型不存在！');
        }
        return $data;
    }

    public function apply()
    {
        $attest_old=$this->_checkAttestStatus(1);

        $this->checkAuth('Ucenter/attest/apply',-1,'你没有申请权限');
        if(IS_POST){
            $attest=$this->attestModel->create();
            //检测认证资料 start
            $res=$this->_checkAttestConditions($attest);
            if(!$res){
                $this->error('未满足认证申请条件！');
            }

            $attest_type=$this->_checkTypeExist($attest['attest_type_id']);
            $attest_type['fields']['child_type_option']=explode(',',str_replace('，',',',$attest_type['fields']['child_type_option']));
            $attest_type['fields']['image_type_option']=explode(',',str_replace('，',',',$attest_type['fields']['image_type_option']));
            if(!in_array($attest['child_type'],$attest_type['fields']['child_type_option'])){
                $this->error('非法操作！');
            }
            if($attest_type['fields']['company_name']!=0){
                if($attest_type['fields']['company_name']==1&&strlen($attest['company_name'])==0){
                    $this->error('企业、组织名称不能为空！');
                }
                if(strlen($attest['company_name'])<2||strlen($attest['company_name'])>100){
                    $this->error('名称长度应该在2到100之间');
                }
            }
            if($attest_type['fields']['name']!=0){
                if($attest_type['fields']['name']==1&&strlen($attest['name'])==0){
                    $this->error('真实姓名不能为空！');
                }
                if(preg_match('/^[\x4e00-\x9fa5]{2,8}$/',$attest['name'])===false){
                    $this->error('请填写真实姓名');
                }
            }
            if($attest_type['fields']['name']!=0){
                if($attest_type['fields']['id_num']==1&&strlen($attest['id_num'])==0){
                    $this->error('身份证号不能为空！');
                }
                if(preg_match('/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/',$attest['id_num'])===false&&preg_match('/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/',$attest['id_num'])===false){
                    $this->error('请填写正确身份证号码');
                }
            }
            if($attest_type['fields']['phone']!=0){
                if($attest_type['fields']['phone']==1&&strlen($attest['phone'])==0){
                    $this->error('联系方式不能为空！');
                }
                if(preg_match('/^(1[3|4|5|7|8])[0-9]{9}$/',$attest['phone'])===false&&preg_match('/^((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/',$attest['phone'])===false){
                    $this->error('请填写正确联系方式');
                }
            }
            if(!in_array($attest['image_type'],$attest_type['fields']['image_type_option'])){
                $this->error('非法操作！');
            }
            if($attest_type['fields']['prove_image']==1&&!count($attest['prove_image'])){
                $this->error('请上传组织或企业证件的高清照片！');
            }
            if($attest_type['fields']['image']==1&&!count($attest['image'])){
                $this->error('请上传证件正反面的高清照片！');
            }
            if($attest_type['fields']['other_image']==1&&!count($attest['other_image'])){
                $this->error('请上传其他证明材料的高清照片！');
            }
            if($attest_type['fields']['info']!=0){
                if($attest_type['fields']['info']==1&&strlen($attest['info'])==0){
                    $this->error('认证补充不能为空！');
                }
            }
            //检测认证资料 end

            $attest['prove_image']&&$attest['prove_image']=implode(',',$attest['prove_image']);
            $attest['image']&&$attest['image']=implode(',',$attest['image']);
            $attest['other_image']&&$attest['other_image']=implode(',',$attest['other_image']);
            $attest['uid']=is_login();
            $attest['status']=2;
            $res=$this->attestModel->editData($attest);
            if($res!==false){
                $uids=get_auth_user('Admin/Attest/setAuditStatus');
                $user=query_user(array('nickname'));
                send_message($uids,'用户申请认证','用户'.$user['nickname'].'申请'.$attest_type['title'].'，请速去审核',U('Admin/attest/attestlist',array('attest_type_id'=>$attest['attest_type_id'],'status'=>3),true,true),array('status'=>2),-1);
                $this->success('申请成功，请等待审核！',U('Ucenter/index/index'));
            }else{
                $this->success('申请失败，请重试！');
            }
        }else{
            //dump($attest_old);exit;
            $res=$this->_checkAttestConditions($attest_old);
            if(!$res){
                $this->error('未满足认证申请条件！');
            }

            if($attest_old){
                $attestType=$this->_checkTypeExist($attest_old['attest_type_id']);
            }else{
                $aId=I('get.id',0,'intval');

                $attestType=$this->_checkTypeExist($aId);
            }
            $attestType['fields']['child_type_option']=explode(',',str_replace('，',',',$attestType['fields']['child_type_option']));
            $attestType['fields']['image_type_option']=explode(',',str_replace('，',',',$attestType['fields']['image_type_option']));
            $this->assign('attest_type',$attestType);

            $this->display();
        }
    }
    
    public function setting()
    {
        $this->display();
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

        } else {
            $is_sync = M('sync_login')->where(array('uid' => $uid, 'status' => 1))->find();
            if($is_sync) {
                $user = query_user(array('nickname', 'avatar512'), $uid);
                $this->assign('user_info', $user);
            }

            $this->assign('is_sync', $is_sync);

            //手机号
            $is_mobile = query_user('mobile', $uid);
            $this->assign('is_mobile', $is_mobile['mobile']);
        }
        $this->display();
    }
    
    public function checkVerify()
    {
        $aAccount = I('post.account', '', 'text');
        $aVerify = I('post.verify', '', 'text');
        $aUid = is_login();
        $aType = 'mobile';

        if (!is_login()) {
            $this->error('请先登录');
        }

        $res = D('Verify')->checkVerify($aAccount, $aType, $aVerify, $aUid);
        if (!$res) {
            $this->error(L('_FAIL_VERIFY_'));
        }
        UCenterMember()->where(array('id' => $aUid))->save(array($aType => $aAccount));
        $this->success(L('_SUCCESS_VERIFY_'), U('Ucenter/Index/safe'));
    }
}