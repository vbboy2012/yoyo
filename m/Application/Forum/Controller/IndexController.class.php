<?php
namespace  Forum\Controller;

use Forum\Model\ForumPostReplyModel;
use Think\Controller;

class IndexController extends Controller
{
    public function _initialize()
    {
        $this->setTitle(L('_MODULE_')) ;
        if (!D('Module')->checkInstalled('Forum')) {
            $this->error('模块未安装');
        }
    }

    public function index()
    {
        $i=0;
        $recommendedSection=D("forum");
        $parame['status'] = 1 ;
        if(IS_POST){
            $i=0;
            $data=array();
            $searchContent= I('post.condition', '', 'text');
            $result=$recommendedSection->where($parame)->select();
            foreach ($result as $item) {
              if(strstr($item["title"], $searchContent)){
                  $data[$i]["title"]=$item["title"];
                  $data[$i]["logo"]=getThumbImageById($item["logo"], 50, 50);
                  $data[$i]["description"]=$item["description"];
                  $data[$i]["id"]=U('Forum/index/section',array("id"=>$item["id"]));
                  $i++;
              }
            }
            unset($item);
            $this->ajaxReturn($data);
        }
        else {
            //推荐版块
            if (is_login()) {
                $userAllFollowId = D("forum_follow")->where(array("uid" => get_uid()))->getField("forum_id", true);
                if ($userAllFollowId) {
                    $parame['id'] = array( 'NOT IN', $userAllFollowId) ;
                }
                $followData=S('forum_attention_plate'.is_login());
            }
            $result = $recommendedSection->where($parame)->order("post_count desc")->limit(3)->select();
            foreach ($result as &$item) {
                $item["logo"] = getThumbImageById($item["logo"], 50, 50);
                $item["post_week_num"] = get_week_num($item["id"]) ;
            }
            unset($item);
            $this->assign('result', $result);
            //关注版块
            if ($followData===false){
                shuffle($userAllFollowId) ;
                $userFollowId = array_slice( $userAllFollowId, 0, 3) ;
                $followData=array();
                foreach ($userFollowId as $val){
                    $followData[$i]= $recommendedSection->where(array("id"=>$val))->find();
                    $i++;
                }
                unset($val);
                foreach ($followData as &$value) {
                    $value["logo"] = getThumbImageById($value["logo"], 50, 50);
                    $value["post_week_num"] = get_week_num($value["id"]) ;
                }
                unset($value);
                S('forum_attention_plate'.is_login(), $followData, 3600);
            }
            //判断用户是否有关注版块
            if(count($followData)>0){
                $this->assign('followData', $followData);
            }
            else{
                $noOne=1;
                $this->assign('noOne', $noOne);
            }
            $this->display();
        }
    }

    /**
     * 板块详情
     * @auth nkx nkx@ourstu.com
     */
    public function section(){
        $sectionId=I('get.id','','intval');
        //板块信息
        $sectionInfo=S('forum_plate'.$sectionId);
        if ($sectionInfo===false){
            $sectionInfo=D('forum')->where(array('id'=>$sectionId))->find();
            $sectionInfo['logo']=getThumbImageById($sectionInfo['logo'],80,80);
            S('forum_plate'.$sectionId,$sectionInfo,3600);
        }
        //获取当前板块的活跃用户
        $forumUsers=S('forum_active_user'.$sectionId) ;
        if ($forumUsers === false) {
            $forumUsers = get_active_users($sectionId) ;
            S('forum_active_user'.$sectionId,$forumUsers,3600) ;
        }
        //获取当前板块的活跃用户数量

        //获取板块发帖数
        $postCount=S('forum_post_count'.$sectionId);
        if ($postCount===false){
            $postCount = D('ForumPost')->where(array('forum_id' => $sectionId))->count();
            S('forum_post_count'.$sectionId,$postCount,3600);
        }
        //获取当前是否关注
        $isFollowed=D('forum_follow')->where(array('forum_id'=>$sectionId,'uid'=>is_login()))->field('id')->find();
        if ($isFollowed){
            $isFollowed=1;
        }else{
            $isFollowed=0;
        }
        //当前用户信息
        $userInfo=query_user(array('avatar64','uid', 'avatar128', 'avatar32', 'avatar256', 'avatar512','title','nickname'),is_login());
        //获取关注数
        $followCount=S('forum_attention_count'.$sectionId);
        if ($followCount===false){
            $followCount=D('forum_follow')->where(array('forum_id'=>$sectionId))->count();
            S('forum_attention_count'.$sectionId,$followCount,3600);
        }
        //获取置顶数据
        $topPost=S('forum_stick_count'.$sectionId);
        if ($topPost===false){
            $topPost=D('forum_post')->where(array('forum_id'=>$sectionId,'is_top'=>1))->order('create_time desc')->select();
            S('forum_stick_count'.$sectionId,$topPost,3600);
        }
        //获取精华帖子数量
        $essencePosttotal=S('forum_essence_count'.$sectionId);
        if ($essencePosttotal===false){
            $essencePosttotal=D('forum_post')->where(array('forum_id'=>$sectionId,'is_essence'=>1))->count();
            S('forum_essence_count'.$sectionId,$essencePosttotal,3600);
        }

        //获取普通帖子数量
        $commonPosttotal=S('forum_common_count'.$sectionId);
        if ($commonPosttotal===false){
            $commonPosttotal=D('forum_post')->where(array('forum_id'=>$sectionId,'is_top'=>0,'is_essence'=>0))->count();
            S('forum_common_count'.$sectionId,$commonPosttotal,3600);
        }

        //设置高度
        $height=count($topPost)*24+220;

        $this->assign(array(
            'sectionInfo'=>$sectionInfo,
            'forumUsers'=>$forumUsers,
            'isFollowed'=>$isFollowed,
            'userInfo'=>$userInfo,
            'followCount'=>$followCount,
            'topPost'=>$topPost,
            'postCount'=>$postCount,
            'sectionId'=>$sectionId,
            'essencePosttotal'=>$essencePosttotal,
            'commonPosttotal'=>$commonPosttotal,
            'height'=>$height
        ));
        $this->display();
    }

    /**
     * send
     * 发帖
     * @auth wb
     */
    public function send()
    {
        if (!is_login()){
            $this->error('请登录',U('ucenter/member/login'),1);
        }
        //获取版块id
        $sectionId=I('get.id','','intval');
        if(IS_POST){
            $post=D("forum_post");
            $data['file_id']=I('post.file_id',0,'intval');
            $data["uid"]=get_uid();
            $data["forum_id"]=I('post.sectionId','','intval');
            $data["title"]=I('post.title','','text');
            $data["content"]=I('post.content','');
            $data["hide"]=I('post.checked','','text');
            $data["pay_on"]=I('post.checkPay','','text');
            $data["pay_type"]=I('post.pay_type','','text');
            $data["pay_num"]=I('post.pay_num','','intval');
            $data["create_time"]=time();
            $data["status"]=1;
            if($data["title"]==''){
                $this->error('标题不能为空');
            }
            if($data["content"]==''){
                $this->error('内容不能为空');
            }
            if($data["pay_on"]=='on' && $data["pay_type"]==''){
                $this->error('请选择付费类型');
            }
            if($data["pay_on"]=='on'&&$data["file_id"]==0){
                $this->error('请上传付费附件');
            }
            $post->add($data);
            $mes=array("status"=>"1","info"=>"发帖成功","id"=>I('post.sectionId','','intval'));
            $recommendedSection=D("forum");
            //更新版块里的帖子数量
            $update["post_count"]=$post->where(array("forum_id"=>I('post.sectionId','','intval')))->count();
            $recommendedSection->where(array("id"=>I('post.sectionId','','intval')))->save($update);
            S('forum_post_count'.$data["forum_id"],false);
            S('forum_common_count'.$data["forum_id"],false);
            S('forum_section_comment'.$data["forum_id"].'common'.'1'.'reply_count',false);
            S('forum_section_comment'.$data["forum_id"].'common'.'1'.'last_reply_time',false);
            S('forum_section_comment'.$data["forum_id"].'common'.'1'.'create_time',false);
            S('forum_section_comment'.$data["forum_id"].'essence'.'1'.'reply_count',false);
            S('forum_section_comment'.$data["forum_id"].'essence'.'1'.'last_reply_time',false);
            S('forum_section_comment'.$data["forum_id"].'essence'.'1'.'create_time',false);
            S('forum_admin_post',false);
            $this->ajaxReturn($mes);
        }
        else{
            $this->assign("sectionId",$sectionId);
            $this->display();
        }

    }


    /**
     * 关注操作
     * @auth nkx nkx@ourstu.com
     */
    public function follow(){
        $follow=I('post.follow','','intval');
        $sectionID=I('post.sectionID','','string');
        if (!is_login()){
            $this->error('请登录',U('ucenter/member/login'),1);
        }
        if ($follow==1){
            $res=D('forum_follow')->where(array('uid'=>is_login(),'forum_id'=>$sectionID))->delete();
            if ($res){
                S('forum_attention_count'.$sectionID,false);
                S('forum_attention_plate'.is_login(), false);
                $this->success('取消关注成功');
            }else{
                $this->error('未知错误');
            }
        }else{
            $data['uid']=is_login();
            $data['forum_id']=$sectionID;
            $res=D('forum_follow')->add($data);
            if ($res){
                S('forum_attention_count'.$sectionID,false);
                S('forum_attention_plate'.is_login(), false);
                $this->success('关注成功');
            }else{
                $this->error('未知错误');
            }
        }
    }

    /**
     * 获取板块帖子数据
     * @auth nkx nkx@ourstu.com
     */
    public function commonForumData(){
        $page=I('post.page',1,'intval');
        $sectionID=I('post.sectionID','','intval');
        $type=I('post.type','','string');
        $order=I('post.sort','','string');
        //获取普通帖子数量
        $map=array();
        if ($type=='common'){
            $map['is_top']=0;
            $map['status']=1;
            $map['is_essence']=0;
            $map['is_top']=0;
        }
        if($type=='essence'){
            $map['status']=1;
            $map['is_essence']=1;

        }
        if ($sectionID) {
            $map['forum_id']=$sectionID;
        }
        $data=S('forum_section_comment'.$sectionID.$type.$page.$order);
        if ($data===false){
            $data=D('forum_post')->where($map)->page($page,10)->order($order.' desc')->select();
            foreach ($data as &$val){
                $val['uid']=query_user(array('nickname','uid', 'avatar128', 'space_url',),$val['uid']);
                $val['create_time']=friendlyDate($val['create_time'], 'normal');
                $val['forum'] = getForumInfo($val['forum_id'], array('title')) ;
            }
            unset($val);
            S('forum_section_comment'.$sectionID.$type.$page.$order,$data,360);
        }

        $count=D('forum_post')->where($map)->count();
        $this->assign(array('data'=>$data));
        $this->assign('count',$count);
        $html=$this->fetch('_list');
        $arr=array('data'=>$html,'status'=>1,'info'=>'获取成功');
        $this->ajaxReturn($arr);
    }

    public function  type(){
        $page = I('page', 1,'intval') ;
        $showType = I('type','','text') ;
        $type=D("forum_type");
        $follow = D("forum_follow");
        $recommendedSection=D("forum");
        $official=$type->where(array("status"=>"1","id"=>2))->find();
        $officialList = array() ;
        $fList = array() ;
        $count = 0 ;
        $uid = is_login() ;
        if ($showType == 'follow' && $uid) {
            $count = $follow->where('uid='.$uid)->count() ;
        } else {
            $showType = 'all' ;
            $param['status'] = 1 ;
            $param['type_id'] = array('eq',$official['id']) ;
            $officialList = $recommendedSection->where($param)->order('create_time desc')->select();
            foreach ($officialList as &$val) {
                $val["logo"] = getThumbImageById($val["logo"], 50, 50);
                $val["post_week_num"] = get_week_num($val["id"]) ;
            }
            unset($val) ;
            $param['type_id'] = array('neq',$official['id']) ;
            $count = $recommendedSection->where($param)->order('create_time desc')->count();
        }
        $this->assign('count',$count) ;
        $this->assign('isFollow',$showType) ;
        $this->assign('officialList',$officialList);
        $this->assign('follow',$fList);
        $this->display();
    }

    /**
     * 帖子详情
     * @auth nkx nkx@ourstu.com
     */
    public function detail()
    {
        $id=I('get.id',1,'intval');
        if (IS_POST){
            $page=I('post.page',1,'intval');
            $id=I('post.id',1,'intval');
            $arr=S('forum_detail_'.$id.$page);
            if ($arr===false){
                $comment=D('forum_post_reply')->where(array('post_id'=>$id))->page($page,10)->order('create_time desc')->select();
                $arr=array();
                foreach ($comment as &$val){
                    $temp=D('ForumPostReply')->getComment($val['id']);
                    array_push($arr,$temp);
                }
                unset($val);
                $this->assign('comment',$arr);
                $html=$this->fetch('_cmtlist');
                $this->ajaxReturn(array('status'=>1,'info'=>'请求成功','data'=>$html));

            }elseif ($arr){
                $this->assign('comment',$arr);
                $html=$this->fetch('_cmtlist');
                $this->ajaxReturn(array('status'=>1,'info'=>'请求成功','data'=>$html));
            }

        }
        //获取帖子详情
        $data=S('forum_detail'.$id);
        if ($data===false){
            $data=D('forum_post')->find($id);
            $data['create_time']=friendlyDate($data['create_time']);
            $data['user']=query_user(array('avatar64','uid', 'avatar128', 'avatar32', 'avatar256', 'avatar512','title','nickname'),$data['uid']);
            S('forum_detail'.$id,$data,3600);
        }
//        dump($data);
//        view_count($id);
        $download=D('file')->where(array('id'=>$data['file_id']))->find();
        $total=D('forum_post_reply')->where(array('post_id'=>$id))->count();
        $total_uid=D('forum_post_reply')->where(array('post_id'=>$id,'uid'=>is_login()))->count();
        $pay_uid=D('forum_pay')->where(array('forum_post_id'=>$id,'uid'=>is_login()))->count();
        $this->assign('data',$data);
        $this->assign('total',$total);
        $this->assign('totalUid',$total_uid);
        $this->assign('download',$download);
        $this->assign('payUid',$pay_uid);
        //打赏渲染
        $rewardResult=D("m_reward")->where(array("Articleid"=>$id,"authorid"=>$data['uid'],'table_name'=>'forum'))->order("create_time asc")->limit(5)->getfield("uid",true);
        foreach ($rewardResult as &$val){
            $val=query_user(array("avatar32"),$val);
        }
        unset($val);
        $myResult=S('forum_reward_result'.$id);
        if($myResult==false){
            $myResult=D("m_reward")->where(array("Articleid"=>$id,"authorid"=>$data['uid']))->order("create_time desc")->select();
            foreach ($myResult as &$vl){
                $vl['uid']=query_user(array("avatar32","nickname"),$vl['uid']);
                $vl['create_time']=friendlyDate($vl['create_time']);
            }
            unset($vl);
            S('forum_reward_result'.$id,$myResult,3600);
        }
        $this->assign('myResult',$myResult);
        $this->assign('rewardResult',$rewardResult);
        $this->assign('count',count($rewardResult));
        $this->display();
    }

    /**
     * 获取积分类型
     * @auth qhy
     */
    public function scoreType(){
        //获取积分类型
        $score_type=D('ucenter_score_type')->select();
        $this->ajaxReturn($score_type);
    }

    /**
     * 付费下载
     * @auth qhy
     */
    public function payDownload(){
        if (IS_POST){
            $forum_id=I('post.id','','intval');
            $forum_uid=I('post.uid','','intval');
            $type_title=I('post.type','','text');
            $num=I('post.num','','intval');
            $type_id=D('ucenter_score_type')->where(array('title'=>$type_title))->getField('id');
            $type='score'.$type_id;
            if($forum_uid==is_login()){
                $this->error('自己不能对自己进行付费下载');
            }
            $uidScore=D('member')->where(array('uid'=>$forum_uid))->getField($type);
            $payUidScore=D('member')->where(array('uid'=>is_login()))->getField($type);
            if ($payUidScore < $num){
                $this->error('您的'.$type_title.'不足，请充值!');
            }
            $payEndScore=$payUidScore-$num;
            $uidEndScore=$uidScore+$num;
            $mes[$type]=$payEndScore;
            $mess[$type]=$uidEndScore;
            $res=D('member')->where(array('uid'=>is_login()))->save($mes);
            if (!$res){
                $this->error('数据库存入失败');
            }
            $suc=D('member')->where(array('uid'=>$forum_uid))->save($mess);
            if (!$suc){
                $this->error('数据库存入失败');
            }
            $data['uid']=is_login();
            $data['forum_post_id']=$forum_id;
            $data['create_time']=time();
            $success=D('forum_pay')->add($data);
            if ($success){
                $this->success('付费成功');
            }else{
                $this->error('数据库存入失败');
            }
        }
    }

    /**
     * 点击下载增加下载数
     * @auth qhy
     */
    public function clickDownload(){
        $id=I('post.id',1,'intval');
        D('file')->where(array('id'=>$id))->setInc('download_num');
        $this->success('正在下载');
    }

    /**
     * reward
     * 打赏ajax
     * @auth wb
     */
    public function  reward(){
        if (!is_login()) {
            $this->error('请您先登录', U('Ucenter/member/login'), 1);
        }
        $reward=D("m_reward");
        $user=D("member");
        $beid=I("post.be","","intval");
        $detailId=I("post.detailId","","intval");
        $beScore=I("post.score","","intval");
        $type_id=I("post.type","","intval");
        $type='score'.$type_id;
        $type_title=D('ucenter_score_type')->where(array('id'=>$type_id))->getField('title');
        $table_name=I("post.tableName","","text");
        $is=$reward->where(array("authorid"=>$beid,"Articleid"=>$detailId,'table_name'=>$table_name))->getfield("uid",true);
        foreach ($is as $val){
            if($val==get_uid()){
                $this->ajaxReturn(array("status" => "2", "info" => "您已打赏过该用户！"));
            }
        }
        //判断是否是自己为自己打赏
        if(get_uid()==$beid){
            $this->ajaxReturn(array("status" => "0", "info" => "不能对自己进行打赏！"));
        }
        $addScore=$user->where(array("status"=>"1","uid"=>$beid))->getfield($type);
        $date[$type]=$beScore+$addScore;
        $score=$user->where(array("status"=>"1","uid"=>get_uid()))->getfield($type);
        if($score<$beScore){
            $this->ajaxReturn(array("status" => "-1", "info" => "您的".$type_title."不足，请充值后再试！"));
        }
        $myScore[$type]=$score-$beScore;
        send_message($beid,"您有新的消息了！","有人打赏了您的帖子，快去看看吧！","News/index/detail",array("id"=>$detailId));
        $user->where(array("status"=>"1","uid"=>$beid))->save($date);
        $user->where(array("status"=>"1","uid"=>get_uid()))->save($myScore);
        $myUser=query_user(array("nickname"),get_uid());
        $parameter['uid']=get_uid();
        $parameter['message']= $myUser['nickname']."赞赏了帖子,"."并打赏了".$beScore.$type_title;
        $parameter['authorid']=$beid;
        $parameter['Articleid']=$detailId;
        $parameter['table_name']=$table_name;
        $parameter['create_time']=time();
        $reward->add($parameter);
        if($reward){
            S('news_reward_result'.$detailId,null);
        }
        $rewardResult=D("m_reward")->where(array("Articleid"=>$detailId,"authorid"=>$beid))->order("create_time asc")->limit(5)->getfield("uid",true);
        foreach ($rewardResult as &$val){
            $val=query_user(array("avatar32"),$val);
        }
        unset($val);

        $myResult=D("m_reward")->where(array("Articleid"=>$detailId,"authorid"=>$beid))->order("create_time desc")->select();
        foreach ($myResult as &$vl){
            $vl['uid']=query_user(array("avatar32","nickname"),$vl['uid']);
            $vl['create_time']=friendlyDate($vl['create_time']);
        }
        unset($vl);
        S('news_reward_result'.$detailId,$myResult,3600);
        $this->assign('myResult',$myResult);
        $myHtml=$this->fetch("_rewardlist");
        $this->ajaxReturn(array("status"=>"1","head"=>$rewardResult,"html"=>$myHtml,"info"=>"打赏成功！","count"=>count($myResult)));

    }

    /**
     * 置顶操作
     * @auth nkx nkx@ourstu.com
     */
    public function setTop()
    {
        $id=I('post.id',1,'intval');
        $sectionId=D('forum_post')->where(array('id'=>$id))->getField('forum_id');
        $res=D('forum_post')->where(array('id'=>$id,'is_top'=>1))->find();
        if ($res==null){
            $data['is_top']=1;
            $res=D('forum_post')->where(array('id'=>$id))->save($data);
            if ($res){
                S('forum_section_comment'.$sectionId.'common'.'1',false);
                S('forum_section_comment'.$sectionId.'essence'.'1',false);
                S('forum_stick_count'.$sectionId,false);
                $this->success('置顶成功！');
            }else{
                $this->error('未知错误');
            }
        }else{
            $data['is_top']=0;
            $res=D('forum_post')->where(array('id'=>$id))->save($data);
            if ($res){
                S('forum_section_comment'.$sectionId.'common'.'1',false);
                S('forum_section_comment'.$sectionId.'essence'.'1',false);
                S('forum_stick_count'.$sectionId,false);
                $this->success('取消置顶成功！');
            }else{
                $this->error('未知错误');
            }
        }
    }
    public function setEssence()
    {
        $id=I('post.id',1,'intval');
        $sectionId=D('forum_post')->where(array('id'=>$id))->getField('forum_id');
        $res=D('forum_post')->where(array('id'=>$id,'is_essence'=>1))->find();
        if ($res==null){
            $data['is_essence']=1;
            $res=D('forum_post')->where(array('id'=>$id))->save($data);
            if ($res){
                S('forum_essence_count'.$sectionId,false);
                S('forum_section_comment'.$sectionId.'common'.'1',false);
                S('forum_section_comment'.$sectionId.'essence'.'1',false);
                $this->success('加精成功！');
            }else{
                $this->error('未知错误');
            }
        }else{
            $data['is_essence']=0;
            $res=D('forum_post')->where(array('id'=>$id))->save($data);
            if ($res){
                S('forum_essence_count'.$sectionId,false);
                S('forum_section_comment'.$sectionId.'common'.'1',false);
                S('forum_section_comment'.$sectionId.'essence'.'1',false);
                $this->success('取消加精成功！');
            }else{
                $this->error('未知错误');
            }
        }
    }

    /**
     * commentbyid
     * 楼中楼详情
     * @auth wb
     */

    public function commentbyid()
    {
        $id = I('get.id', '', 'intval');
        $forum_lzl_reply = D("forum_lzl_reply");
        if (IS_POST) {
            $page=I('post.page',1,'intval');
            $id=I('post.id',1,'intval');
            //楼中楼缓存
            $arr=S('forum_lzl_reply_'.$id.$page);
            if($arr==false){ //判断缓存存不存在
                $lzl_result=$forum_lzl_reply->where(array('to_reply_id'=>$id))->page($page,10)->order('ctime desc')->select();
                if ($lzl_result){
                    $arr=array();
                    foreach ($lzl_result as &$val){
                        $temp=D('ForumPostReply')->getCommentlzl($val['id']);
                        array_push($arr,$temp);
                    }
                    unset($val);
                    S('forum_lzl_reply_'.$id.$page,$arr,360);
                    $this->assign('lzl_result',$arr);
                    $html=$this->fetch('_comment');
                    $this->ajaxReturn(array('status'=>1,'info'=>'请求成功','data'=>$html));
                }else{
                    $this->ajaxReturn(array(
                        'info' => '请求失败',
                        'status' => 1,
                        'data' => ''
                    ));
                }
            }elseif($arr){
                $this->assign('lzl_result',$arr);
                $html=$this->fetch('_comment');
                $this->ajaxReturn(array('status'=>1,'info'=>'请求成功','data'=>$html));
            } else {
                $this->ajaxReturn(array(
                    'info' => '请求失败',
                    'status' => 1,
                    'data' => ''
                ));
            }
        }
        //获取帖子id
        $mes=S('forum_post_lzl'.$id);
        if ($mes===false){
            $forum_post_reply = D("forum_post_reply");
            $mes = $forum_post_reply->where(array("id" => $id))->find();
            $mes['content'] = parse_emoji(parse_at_users($mes['content'], true));
            $mes['create_time'] = friendlyDate($mes['create_time']);
            $support['appname'] = 'Forum';
            $support['table'] = 'forum';
            $support['row'] = $id;
            $mes['support_all_count'] = D('Support')->where($support)->count();
            $support['uid'] = is_login();
            $mes['support_count'] = D('Support')->where($support)->count();
            if ($mes['support_count']) {
                $mes['is_support'] = '1';
            } else {
                $mes['is_support'] = '0';
            }
            S('forum_post_lzl'.$id,$mes,360);
        }
        $mes['all_count']=$forum_lzl_reply->where(array('to_reply_id'=>$id))->count();
        $count=$forum_lzl_reply->where(array('to_reply_id'=>$id))->count();
        $user = query_user(array('uid', 'avatar32', 'avatar64', 'avatar128', 'space_url', 'nickname'), $mes['uid']);
        $this->assign('mes', $mes);
        $this->assign('user', $user);
        $this->assign('count', $count);
        $this->display('comment');

    }

    /**
     * 帖子楼中楼评论
     * @auth qhy qhy@ourstu.com
     */
    public function addlzl()
    {
        if (!is_login()) {
            $this->error('请您先登录', U('Ucenter/member/login'), 1);
        }

        if (IS_POST) {
            $aContent = I('post.content', '', 'op_t');              //评论内容
            $lzlId = I('post.lzlId', 0, 'intval');
            if ($aContent == "") {
                $res['status'] = -1;
                $res['info'] = '评论不能为空';
                $this->ajaxReturn($res);
            }
            $lzlcomment = send_lzlcomment($lzlId, $aContent);
            if ($lzlcomment) {
                $data['html'] = "";
                $lzl_result = D('ForumPostReply')->getCommentlzl($lzlcomment);
                if (S('forum_lzl_reply_'.$lzlId.'1')){
                    $arr=S('forum_lzl_reply_'.$lzlId.'1');
                    array_unshift($arr,$lzl_result);
                    S('forum_lzl_reply_'.$lzlId.'1',$arr,360);
                }
                $lzl_result=array(
                    '0'=>$lzl_result
                );
                $this->assign('lzl_result', $lzl_result);
                $data['html'] .= $this->fetch("_comment");
                $data['status'] = 1;
            } else {
                $data['status'] = 0;
            }
            $this->ajaxReturn($data);
        }
    }

    /**
     * 帖子评论
     * @auth qhy qhy@ourstu.com
     */
    public function addComment()
    {
        if (!is_login()) {
            $this->error('请您先登录', U('Ucenter/member/login'), 1);
        }

        if (IS_POST) {

            $aContent = I('post.content', '', 'op_t');              //评论内容
            $aForumId = I('post.forumId', 0, 'intval');             //要评论的帖子的ID
            $aCommentId = I('post.comment_id', 0, 'intval');
            if ($aContent == "") {
                $res['status'] = -1;
                $res['info'] = '评论不能为空';
                $this->ajaxReturn($res);
            }
            $forumcomment = send_forumcomment($aForumId, $aContent, $aCommentId);
            D('forum_post')->where(array('id'=> $aForumId))->setInc('reply_count');
            $updateTime['last_reply_time']=time();
            D('forum_post')->where(array('id'=> $aForumId))->save($updateTime);
            S('forum_admin_comment',false);
            if ($forumcomment) {
                S('forum_reply_manager1',null);
                $data['html'] = "";
                $comment = D('ForumPostReply')->getComment($forumcomment);
                if (S('forum_detail_'.$aForumId.'1')){
                    $arr=S('forum_detail_'.$aForumId.'1');
                    array_unshift($arr,$comment);
                    S('forum_detail_'.$aForumId.'1',$arr,3600);
                }
                $comment=array(
                    '0'=>$comment
                );
                $this->assign('comment', $comment);
                $data['html'] .= $this->fetch("_cmtlist");
                $data['status'] = 1;
            } else {
                $data['status'] = 0;
            }
            $this->ajaxReturn($data);
        }
    }

    /**
     * doDelForum  删除评论
     * @author qhy qhy@ourstu.com
     */
    public function doDelComment()
    {
        $aForumId = I('post.forum_id', 0, 'intval');
        $ForumReplyModel = D('ForumPostReply');

        $forumreply = $ForumReplyModel->getComment($aForumId);

        if (!check_auth('Forum/Index/delPostReply', $forumreply['uid'])) {
            $this->error('您没有权限删除该评论');
        }
        $pos_id=D('forum_post_reply')->where(array('id'=>$aForumId))->getField('post_id');
        //删除评论
        $result = $ForumReplyModel->deleteComment($aForumId);
        D('forum_post')->where(array('id'=>$pos_id))->setDec('reply_count');
        S('forum_detail_'.$pos_id.'1',false);//todo
        action_log('forum_del_comment', 'forum', $aForumId, is_login());
        if (!$result) {
            $return['status'] = 0;
            $return['status'] = L('_ERROR_INSET_DB_');
        } else {
            $return['status'] = 1;
            $return['status'] = L('_SUCCESS_DELETE_');
        }
        //返回成功信息
        $this->ajaxReturn($return);
    }

    /**
     * doDelForum  删除帖子
     * @author qhy qhy@ourstu.com
     */
    public function doDelForum(){
        $aForumId = I('post.id', 0, 'intval');
        $aForumUid = I('post.uid', 0, 'intval');
        if (!check_auth('Forum/Index/delPost', $aForumUid)) {
            $this->error('您没有权限删除该帖子');
        }
        $forum_id=D('forum_post')->where(array('id'=>$aForumId))->getField('forum_id');
        $uid=D('forum_post')->where(array('id'=>$aForumId))->getField('uid');
        $result=D('forum_post')->where(array('id'=>$aForumId))->delete();
        D('forum_post_reply')->where(array('post_id'=>$aForumId))->delete();
        D('forum_lzl_reply')->where(array('post_id'=>$aForumId))->delete();
        $nickname = query_user(array('uid', 'avatar32', 'avatar64', 'avatar128', 'space_url', 'nickname'), is_login());
        send_message($uid,'您的帖子已被管理员'.$nickname['nickname'].'删除。');
        S('forum_section_comment'.$forum_id.'common'.'1'.'reply_count',false);
        S('forum_section_comment'.$forum_id.'common'.'1'.'last_reply_time',false);
        S('forum_section_comment'.$forum_id.'common'.'1'.'create_time',false);
        S('forum_section_comment'.$forum_id.'essence'.'1'.'reply_count',false);
        S('forum_section_comment'.$forum_id.'essence'.'1'.'last_reply_time',false);
        S('forum_section_comment'.$forum_id.'essence'.'1'.'create_time',false);
        if ($result) {
            $this->success('删除成功',U('section',array('id'=>$forum_id)),3);
        } else {
            $return['status'] = 0;
            $return['status'] = L('_ERROR_INSET_DB_');
        }
        //返回成功信息
        $this->ajaxReturn($return);
    }

    /**
     * doDellzl  删除楼中楼
     * @author qhy qhy@ourstu.com
     */
    public function doDellzl(){
        $Id = I('post.id', 0, 'intval');
        $Uid=I('post.uid', 0, 'intval');
        if (!check_auth('Forum/Index/delLZLReply', $Uid)) {
            $this->error('您没有权限删除该评论');
        }
        $uid=D('forum_lzl_reply')->where(array('id'=>$Id))->getField('uid');
        $pid=D('forum_lzl_reply')->where(array('id'=>$Id))->getField('to_reply_id');
        $result=D('forum_lzl_reply')->where(array('id'=>$Id))->delete();
        $nickname = query_user(array('uid', 'avatar32', 'avatar64', 'avatar128', 'space_url', 'nickname'), is_login());
        send_message($uid,'您的楼中楼评论已被管理员:'.$nickname['nickname'].'删除。');
        $support['appname'] = 'Forum-lzl';
        $support['table'] = 'forum';
        $support['row'] = $Id;
        D('support')->where($support)->delete();
        S('forum_lzl_reply_'.$pid.'1',false);  //todo
        if ($result) {
            $return['status'] = 1;
        } else {
            $return['status'] = 0;
            $return['status'] = L('_ERROR_INSET_DB_');
        }
        //返回成功信息
        $this->ajaxReturn($return);
    }

    /**
     * 获取全部分类
     * @auth nkx nkx@ourstu.com
     */
    public function alltype()
    {

        $this->display();
    }

    /**
     * @auth nkx nkx@ourstu.com
     */
    public function uploadfile(){
        dump($_FILES);
    }

    /**版块活跃用户
     * @author szh szh@ourstu.com
     */
    public function active() {
        $sectionId = I('sid','0','intval') ;
        //版主信息
        $user = array() ;
        $follow = '' ;
        $forum_uid=D('forum')->where(array('id'=>$sectionId))->getField('admin');
        if ($forum_uid) {
            $user['info']=query_user(array('uid', 'avatar32', 'avatar64', 'avatar128', 'space_url', 'nickname', 'following'),$forum_uid);
            $user['fans']=D('Member')->where(array('uid'=>$forum_uid))->getField('fans');
            $user['post']=D('forum_post')->where(array('uid'=>$forum_uid))->count();
            $uFollow = D('Common/Follow')->isFollow(is_login(), $forum_uid);
            if($uFollow == 0) {
                $follow = 'follow' ;
            } else {
                $follow = 'unfollow' ;
            }
        }
        $active_user = get_active_users($sectionId, 0, 1) ;
        $this->assign('count', $active_user['number']) ;
        $this->assign('sectionId',$sectionId) ;
        $this->assign('isFollow', $follow);
        $this->assign('user', $user);
        $this->display() ;
    }

    /**版块活跃用户输出列表
     * @author szh szh@ourstu.com
     */
    public function activeUser(){
        $page = I('page','1','intval') ;
        $sectionId = I('sectionID','','intval') ;
        $start = ($page-1)*10 ;
        $end = $start+10 ;
        $user = get_active_users($sectionId, $start, $end) ;
        $this->assign('user', $user['showUser']) ;
        $this->assign('count', $user['number']) ;
        $html = $this->fetch('_alist') ;
        $return = array('status'=>1, 'data'=>$html, 'info'=>'成功~') ;
        $this->ajaxReturn($return) ;
    }

    public function forumList() {
        $page = I('page','0','intval') ;
        $type = I('type','all','string') ;
        $forum = D('forum') ;
        $forumfollow = D('forum_follow') ;
        $uid = is_login() ;
        $items = 40 ;
        if ($type == 'follow' && $uid) {
            $fList = S('FORUM_ALL_FOLLOW_LIST'.$page.$uid) ;
            if ($fList == false) {
                $list = $forumfollow->where('uid='.$uid)->order('id')->page($page ,$items)->field('forum_id')->select() ;
                $fList = array() ;
                foreach ($list as $val) {
                    $oneForum = $forum->where('id='.$val['forum_id'])->find() ;
                    if($oneForum) {
                        $oneForum['logo'] = getThumbImageById($oneForum["logo"], 50, 50);
                        $oneForum["post_week_num"] = get_week_num($oneForum["id"]) ;
                        $fList[] = $oneForum ;
                    }
                }
                unset($val) ;
                S('FORUM_ALL_FOLLOW_LIST'.$page.$uid, $fList, 3600) ;
            }
        } else {
            $fList = S('FORUM_ALL_LIST'.$page.$uid) ;
            if ($fList == false){
                $param['type_id'] = array('neq',2) ;
                $param['status'] = 1 ;
                $fList = $forum->where($param)->order('post_count desc')->page($page ,$items)->select() ;
                foreach ($fList as &$val) {
                    $val['logo'] = getThumbImageById($val["logo"], 50, 50);
                    $val["post_week_num"] = get_week_num($val["id"]) ;
                }
                unset($val) ;
                S('FORUM_ALL_LIST'.$page.$uid, $fList, 3600) ;
            }
        }
        $this->assign('list', $fList) ;
        $html = $this->fetch('_flist') ;
        $result = array('status'=>1,'data'=>$html,'info'=>'成功~') ;
        $this->ajaxReturn($result) ;
    }
}

