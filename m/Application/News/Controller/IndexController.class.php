<?php
namespace News\Controller;

use Addons\ChinaCity\Model\DistrictModel;
use Think\Controller;

require_once('./Application/News/Conf/jssdk.php');
class IndexController extends Controller
{

    public function _initialize()
    {
        if (!D('Module')->checkInstalled('News')) {
            $this->error('模块未安装');
        }
        $this->setTitle(L('_MODULE_')) ;
    }

    /**
     * 首页展示
     * @auth nkx nkx@ourstu.com
     */
    public function index()
    {
        //查询热门分类
        $map['status']=1;
        $map['location']=0;
        $advertisement=S('news_index_advertisement');
        if ($advertisement===false){
            $advertisement=D('advertisement')->where($map)->select();
            foreach ($advertisement as &$val){
                $val['img']=getThumbImageById($val['imgid'],5000,5000);
            }
            unset($val);
            S('news_index_advertisement',$advertisement,86400);
        }
        $advertisementCount=D('advertisement')->where($map)->count();
        $this->assign('advertisementCount', $advertisementCount);
        $this->assign('advertisement', $advertisement);
        $category=D('news')->field('category,COUNT(category) as sort')->group('category')->order('COUNT(category) desc')->cache('category_news',3600)->limit(8)->select();
        foreach ($category as &$item){
            $categoryDetail=D('news_category')->find($item['category']);
            $item['category']=$categoryDetail;
        }
        unset($item);
        //查询资讯总数
        $total=D('news')->where(array('status'=>1))->count();
        //查询三条置顶的
        $systemTop=D('news')->where(array('status'=>1,'position'=>1))->limit(3)->select();
        foreach ($systemTop as &$item) {
            $item['cover']=getThumbImageById($item['cover'],500,300);
        }
        unset($item);
        $this->assign(array(
           'category'=>$category,
            'total'=>$total,
            'systemTop'=>$systemTop
        ));
        $this->display();
    }

    /**
     * search
     * 查询热门搜索
     * @auth wb
     */
    public function search(){
        $change=D("news");
        if(IS_POST){
            $page=I("post.page","","intval");
            $result=$change->order("view desc")->page($page,8)->getfield("title",true);
            $myId=$change->order("view desc")->page($page,8)->getfield("id",true);
            //如果是最后一组了就从第一组查
            if($result==null){
                $result=$change->order("view desc")->page(1,8)->getfield("title",true);
                $myId=$change->order("view desc")->page(1,8)->getfield("id",true);
                $res['is']=0;
            }
            $res['status']="1";
            $res['data']=$result;
            $res['id']=$myId;
            $this->ajaxReturn($res);
        }
        else{
            $history=D("news_search");
            $result=$change->order("view desc")->page(1,8)->select();
            $this->assign("result",$result);
            $historyResult=$history->where(array("uid"=>get_uid()))->order("create_time desc")->limit(3)->select();
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
        $change=D("news");
        if(I("post.is","","text")=="no"){
            $result=$change->where($parame)->select();
            if(!$result){
                $this->ajaxReturn(array("status"=>"1","html"=>"none"));
            }
            $result=$this->fetchNewsData($result);
            unset($item);
            $this->assign("data", $result);
            $html = $this->fetch('_list');
            $this->ajaxReturn(array("status" => "1","html" => $html));
        }
        $result=$change->where($parame)->select();
        $history=D("news_search");
        $historyContent=$history->where(array('uid'=>get_uid()))->select();
        foreach ($historyContent as $val){
            if($val["historical"]==$title){
                $is=1;
            } ;
        }
        if($is==0){
            $data["uid"]=get_uid();
            $data["historical"]=$title;
            $data["create_time"]=time();
            $history->add($data);
        }

        $historyResult=$history->where(array("uid"=>get_uid()))->order("create_time desc")->limit(3)->select();
        $this->assign("historyResult",$historyResult);
        $myHtml=$this->fetch("_history");
        if(!$result){
            $this->ajaxReturn(array("status"=>"1","html"=>"none","data"=>$myHtml));
        } else {
            $result=$this->fetchNewsData($result);
            unset($item);
            $this->assign("data", $result);
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
        $history=D("news_search");
        $id=I("post.id","","intval");
        $history->where(array("uid"=>get_uid(),"id"=>$id))->delete();
        $historyResult=$history->where(array("uid"=>get_uid()))->order("create_time desc")->limit(3)->select();
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
        $history=D("news_search");
        $history->where(array("uid"=>get_uid()))->delete();
        $this->ajaxReturn(array("status" => "1"));
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
        $parameter['message']= $myUser['nickname']."赞赏了文章,"."并打赏了".$beScore.$type_title;
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
     * 获取积分类型
     * @auth qhy
     */
    public function scoreType(){
        //获取积分类型
        $score_type=D('ucenter_score_type')->select();
        $this->ajaxReturn($score_type);
    }

    /**
     * 资讯详情
     * @auth qhy qhy@ourstu.com
     */
    public function detail(){
        $id = I('get.id', '', 'intval');     //获取资讯id
        if (IS_POST) {
            $page=I('post.page',1,'intval');
            $id=I('post.id',1,'intval');
            $arr=S('news_detail_'.$id.$page);
            if ($arr==false){
                $map['app']='News';
                $map['mod']='index';
                $map['row_id']=$id;
                $map['status']=1;
                $comment=D('local_comment')->where($map)->page($page,10)->order('create_time desc')->select();
                if ($comment){
                    $arr=array();
                    foreach ($comment as &$val){
                        $temp=D('LocalComment')->getComment($val['id']);
                        array_push($arr,$temp);
                    }
                    unset($val);
                    S('news_detail_'.$id.$page,$arr,3600);
                    $this->assign('comment',$arr);
                    $html=$this->fetch('_cmtlist');
                    $this->ajaxReturn(array('status'=>1,'info'=>'请求成功','data'=>$html));
                }else{
                    $this->ajaxReturn(array(
                        'info' => '请求失败',
                        'status' => 1,
                        'data' => ''
                    ));
                }
            }elseif ($arr){
                $this->assign('comment',$arr);
                $html=$this->fetch('_cmtlist');
                $this->ajaxReturn(array('status'=>1,'info'=>'请求成功','data'=>$html));
            }
            else {
                $this->ajaxReturn(array(
                    'info' => '请求失败',
                    'status' => 1,
                    'data' => ''
                ));
            }
        }
        $map['app']='News';
        $map['mod']='index';
        $map['row_id']=$id;
        $comment=D('local_comment')->field('id')->where($map)->order('create_time desc')->select();
        $arr=array();
        foreach ($comment as &$val){
            $temp=D('LocalComment')->getComment($val['id']);
            array_push($arr,$temp);
        }
        unset($val);
        $data=D('News')->getData($id);
        if($data['dead_line']<=time() || $data['status'] != 1){ //资讯过期了或者不是正常状态
            if(!check_auth('News/Index/edit',array($data['uid']))){//没有管理权限
                $this->error(L('_ERROR_EXPIRE_'));
            }
        }
        $data = illegal_status($data) ;//资讯非正常状态处理
        $where = array() ;
        $where['id'] = array('neq',$data['id']) ;
        $where['category'] = array('eq',$data['category']) ;
        $recommend = D('News')->getRecommend($where);
        $total=D('local_comment')->where($map)->count();
        $collect=D('Collect')->where(array('row'=>$id,'uid'=>get_uid()))->find();
        D('news')->where(array('id'=>$id))->setInc('view');

        $this->assign('recommend',$recommend) ;
        $this->assign('collect',$collect);
        $this->assign('data',$data);
        $this->assign('comment',$arr);
        $this->assign('total',$total);
        //打赏渲染
        $rewardResult=D("m_reward")->where(array("Articleid"=>$id,"authorid"=>$data['uid'],'table_name'=>'news'))->order("create_time asc")->limit(5)->getfield("uid",true);
        foreach ($rewardResult as &$val){
            $val=query_user(array("avatar32"),$val);
        }
        unset($val);
        $myResult=S('news_reward_result'.$id);
        if($myResult==false){
            $myResult=D("m_reward")->where(array("Articleid"=>$id,"authorid"=>$data['uid']))->order("create_time desc")->select();
            foreach ($myResult as &$vl){
                $vl['uid']=query_user(array("avatar32","nickname"),$vl['uid']);
                $vl['create_time']=friendlyDate($vl['create_time']);
            }
            unset($vl);
            S('news_reward_result'.$id,$myResult,3600);
        }
        $query = array(
            'title'=>$data['title'],
            'content'=>msubstr($data['description'],0,200),
            'img'=>getThumbImageById($data['cover'],160,160),
            'from'=>L('_MODULE_'),
            'site_link'=>U('news/index/detail',array('id'=>$data['id']))
        );
        $shareImg = getThumbImageById($data['cover'],160,160);
        //不存在http://
        $not_http_remote = (strpos($shareImg, 'http://') === false);
        //不存在https://
        $not_https_remote = (strpos($shareImg, 'https://') === false);
        if ($not_http_remote && $not_https_remote) {
            //本地url
            $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
            $a=substr($shareImg , 0 , 2);
            if($a=='..'){
                $shareImg=substr( $shareImg , 2 , strlen($shareImg)-2);
            }else{
                $_SERVER['HTTP_HOST']=$_SERVER['HTTP_HOST'].'/m/';
            }
            $shareImg =  $http_type.$_SERVER['HTTP_HOST']. $shareImg;
        }
        $appid=modC('APP_ID','','weixin');
        $appsecret=modC('APP_SECRET','','weixin');
        $jssdk = new \JSSDK ($appid,$appsecret);
        $signPackage = $jssdk->GetSignPackage();
        $this->assign("signPackage",$signPackage);
        $this->assign('share_img',$shareImg);
        $this->assign('query', urlencode(http_build_query($query)));
        $this->assign('myResult',$myResult);
        $this->assign('rewardResult',$rewardResult);
        $this->assign('count',count($rewardResult));
        $this->display();
    }
    /**
     * 资讯详情评论
     * @auth qhy qhy@ourstu.com
     */
    public function addComment()
    {
        if (!is_login()) {
            $this->error('请您先登录', U('Ucenter/member/login'), 1);
        }

        if (IS_POST) {

            $aContent = I('post.content', '', 'op_t');              //评论内容
            $aNewsId = I('post.newsId', 0, 'intval');             //要评论的资讯的ID
            if ($aContent == "") {
                $res['status'] = -1;
                $res['info'] = '评论不能为空';
                $this->ajaxReturn($res);
            }
            $newscomment = send_newscomment($aNewsId, $aContent);
            D('news')->where(array('id'=> $aNewsId))->setInc('comment');
            if ($newscomment) {
                $data['html'] = "";
                $comment = D('LocalComment')->getComment($newscomment);
                if (S('news_detail_'.$aNewsId.'1')){
                    $arr=S('news_detail_'.$aNewsId.'1');
                    array_unshift($arr,$comment);
                    S('news_detail_'.$aNewsId.'1',$arr,3600);
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
     * 资讯楼中楼详情
     * @auth qhy qhy@ourstu.com
     */
    public function commentbyid()
    {
        $id = I('get.id', '', 'intval');
        $news_lzl_reply = D("news_reply");
        if (IS_POST) {
            $page=I('post.page',1,'intval');
            $id=I('post.id',1,'intval');
            $arr=S('news_lzl_reply_'.$id.$page);
            if ($arr===false) {
                $lzl_result = $news_lzl_reply->where(array('to_reply_id' => $id))->page($page, 10)->order('ctime desc')->select();
                if($lzl_result){
                    $arr = array();
                    foreach ($lzl_result as &$val) {
                        $temp = D('NewsReply')->getCommentlzl($val['id']);
                        array_push($arr, $temp);
                    }
                    unset($val);
                    S('news_lzl_reply_' . $id .$page, $arr, 360);
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
            }elseif ($arr){
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
        //获取资讯id
        $mes=S('news_post_reply'.$id);
        if($mes===false){
            $news_post_reply = D("local_comment");
            $mes = $news_post_reply->where(array("id" => $id))->find();
            $mes['content'] = parse_emoji(parse_at_users($mes['content'], true));
            $mes['create_time'] = friendlyDate($mes['create_time']);
            $support['appname'] = 'News-comment';
            $support['table'] = 'news';
            $support['row'] = $id;
            $mes['support_all_count'] = D('Support')->where($support)->count();
            $support['uid'] = is_login();
            $mes['support_count'] = D('Support')->where($support)->count();
            if ($mes['support_count']) {
                $mes['is_support'] = '1';
            } else {
                $mes['is_support'] = '0';
            }
            S('news_post_reply'.$id,$mes,360);
        }
        $mes['all_count']=$news_lzl_reply->where(array('to_reply_id'=>$id))->count();
        $user = query_user(array('uid', 'avatar32', 'avatar64', 'avatar128', 'space_url', 'nickname'), $mes['uid']);
        $count=$news_lzl_reply->where(array('to_reply_id'=>$id))->count();
        $this->assign('mes', $mes);
        $this->assign('user', $user);
        $this->assign('count', $count);
        $this->display('comment');
    }

    /**
     * 资讯楼中楼评论
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
                $lzl_result = D('NewsReply')->getCommentlzl($lzlcomment);
                if (S('news_lzl_reply_'.$lzlId.'1')){
                    $arr=S('news_lzl_reply'.$lzlId.'1');
                    array_unshift($arr,$lzl_result);
                    S('news_lzl_reply_'.$lzlId.'1',$arr,360);
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
     * doDellzl  删除楼中楼
     * @author qhy qhy@ourstu.com
     */
    public function doDellzl(){
        $Id = I('post.id', 0, 'intval');
        $Uid = I('post.uid', 0, 'intval');
        if (!check_auth('Forum/Index/delLZLReply', $Uid)) {
            $this->error('您没有权限删除该评论');
        }
        $uid=D('news_reply')->where(array('id'=>$Id))->getField('uid');
        $pid=D('news_reply')->where(array('id'=>$Id))->getField('to_reply_id');
        $result=D('news_reply')->where(array('id'=>$Id))->delete();
        $nickname = query_user(array('uid', 'avatar32', 'avatar64', 'avatar128', 'space_url', 'nickname'), is_login());
        send_message($uid,'您的楼中楼评论已被管理员:'.$nickname['nickname'].'删除。');
        $support['appname'] = 'News-lzl';
        $support['table'] = 'news';
        $support['row'] = $Id;
        D('support')->where($support)->delete();
        S('news_lzl_reply_'.$pid.'1',false);
        if ($result) {
            $return['status'] = 1;
        } else {
            $return['status'] = 0;
            $return['status'] = L('_ERROR_INSET_DB_');
        }
        //返回成功信息
        $this->ajaxReturn($return);
    }
    public function all()
    {
        $html = '' ;
        $category=D('news_category')->where(array('status'=>1))->cache('NEWS_ALL_TYPE_DATA',360)->select();
        $this->assign('category', $category) ;
        $html = $this->fetch('all') ;
        $result = array('status'=>1,'data'=>$html,'info'=>'成功~') ;
        $this->ajaxReturn($result);
    }

    /**
     * @param  前台发资讯
     * @auth sun slf02@ourstu.com
     * @return
     */
    public function send(){
        $newsModel = D('News/News');
        if (!is_login()) {
            $this->error('请您先登录', U('Ucenter/member/login'), 1);
        }
        $map['status']=1;
        $map['pid']=0;
        $type=D('NewsCategory')->where($map)->field('id,title')->select();
        foreach ($type as &$val){
            $val['sub']=D('NewsCategory')->where(array('pid'=>$val['id'],'status'=>1))->field('id,pid,title')->select();
        }
        unset($val);
        $this->assign('type',json_encode($type));
        if(IS_POST){
            $this->_isLogin();
            parse_str(I('post.data'),$arr);
            $data['title']=$arr['title'];
            $data['cover'] = $arr['image'];
            $data['content'] = I('post.html', '', 'op_h');
            $category=explode(',',$arr['type_id']);
            $data['category']=$category[1] ? $category[1]:$category[0];
            $imgIds = $arr['image'];
            $imgId = explode(',', $imgIds) ;
            if(is_numeric($imgId[0])&&is_numeric($imgId[1])){
                if ($imgId[0]==false && $imgId[1]==false){
                    $pic = get_pic($data['content']) ;
                    if($pic==null){
                        $data['cover']=0;
                        $data['banner']=0;
                    }else{
                        if(substr($pic,0,4)=='http'){
                            $str=$pic;
                        }else{
                            $str=substr($pic,1,strlen($pic)-1);
                            $str=substr($str,strpos($str,'/'),strlen($str)-strpos($str,'/'));
                        }
                        $coverId=M('picture')->where(array('path'=>$str))->getField('id');
                        //$this->error($coverId['id']);
                        $data['cover']=$coverId;
                        $data['banner']=$coverId;
                    }
                }elseif($imgId[0]>0 && $imgId[1]==false){
                    $data['cover']=$imgId[0];
                    $data['banner']=$imgId[0];
                }elseif($imgId[0]==false && $imgId[1]>0){
                    $data['cover']=$imgId[1];
                    $data['banner']=$imgId[1];
                }else{
                    $data['cover']=$imgId[0];
                    $data['banner']=$imgId[1];
                }
            }else{
                $data['cover']=0;
                $data['banner']=0;
            }
            $status = $this->_checkOk($data);
            $data['status']=$status;
            $data['dead_line']=2147483640;
            $data['uid']=get_uid();
            $data['template']='';
            if($status==2){
                if(check_auth()){
                    $data['status']=1;
                    $msg ='发布成功';
                }else{
                    $msg ='等待管理员审核';
                }
            }else{
                $msg ='发布成功';
            }
            $result=$newsModel->editData($data);
            if($result) {
                S('news_home_data', null);
                $return['status'] = 1;
                $return['info'] = $msg;
                $return['url'] = U('News/index/index');
            }else{
                $return['info']='数据库写入失败';
            }
            $this->ajaxReturn($return);
        }else{
            $this->display();
        }


    }

    public function _isLogin(){
        if (!is_login()) {
            $data['status'] = 0;
            $data['info'] = '未登录';
            $this->ajaxReturn($data);
        }
    }

    public function _checkOk($data){
        if (empty($data['title'])) {
            $this->error('请填写资讯标题');
        }
        if (utf8_strlen($data['title']) > 50) {
            $this->error('资讯名称最多20个字');
        }
        if (empty($data['category'])) {
            $this->error('请选择资讯分类');
        }
        if(utf8_strlen($data['content']) < 20){
            $this->error('资讯内容不得少于20个字');
        }

        $list=D('news_category')->where(array('id'=>$data['category']))->find();
        if($list['can_post'] != 1){
            $this->error('该分类前台不能投稿');
        }else{
            if($list['need_audit'] != 1){
                $status = 1;
            }else{
                $status = 2;
            }
        }
        return $status;
    }
    /**
     * 获取首页热门资讯分页展示
     * @auth nkx nkx@ourstu.com
     */
    public function hotNews()
    {
        $page=I('post.page',1,'intval');
        $typeId=I('post.id','','intval');
        $param['status'] = 1 ;
        $param['dead_line'] = array('gt',time()) ;
        $param['post_time'] = array('lt',time()) ;
        if ($typeId) {
            $param['category'] = $typeId ;
        }
        $data=D('news')->where($param)->order('create_time desc')->page($page,10)->select();
        $data=$this->fetchNewsData($data);
        unset($datum);
        if ($data){
            $this->assign('data',$data);
            $data=$this->fetch('_list');
            $this->ajaxReturn(array(
                'info'=>'请求成功',
                'status'=>1,
                'data'=>$data
            ));
        }else{
            $this->ajaxReturn(array(
                'info'=>'请求失败',
                'status'=>1,
                'data'=>''
            ));
        }
    }

    /**
     * 获取资讯分类详情
     * @auth nkx nkx@ourstu.com
     */
    public function single()
    {
        $category=I('get.category',1,'intval');
        $title=I('get.title','求真相','string');
        $total=D('news')->where(array('category'=>$category,'status'=>1))->count();
        S('news_category'.$category,null);
        $this->assign(array(
            'category'=>$category,
            'title'=>$title,
            'total'=>$total
        ));
        $this->display();
    }

    public function getCategoryListById()
    {
        $category=I('post.category',1,'intval');
        $page=I('post.page',1,'intval');
        $data=D('news')->where(array('status'=>1,'category'=>$category))->page($page,10)->select();
        $data=$this->fetchNewsData($data);
        if ($data){
            $this->assign('data',$data);
            $data=$this->fetch('_list');
            $this->ajaxReturn(array(
                'info'=>'请求成功',
                'status'=>1,
                'data'=>$data
            ));
        }else{
            $this->ajaxReturn(array(
                'info'=>'请求失败',
                'status'=>1,
                'data'=>''
            ));
        }
    }

    /**
     * @param $data array 需要遍历处理的数据
     * @return array 返回处理后的数据
     * @auth nkx nkx@ourstu.com
     */
    public function fetchNewsData(array $data)
    {
        foreach ($data as &$datum){
            $datum['category']=D('news_category')->find($datum['category']);
            $datum['uid']=query_user(array('uid','nickname','avatar512'),$datum['uid']);
            $datum['create_time']=friendlyDate($datum['create_time']);
            $datum['support']=D('support')->where(array('row'=>$datum['id'],'Appname'=>'News'))->count();
        }
        unset($datum);
        return $data;
    }

    public function getCount() {
        $id = I('id','','intval') ;
        $count = 0 ;
        if ($id) {
            $param['status'] = 1 ;
            $param['dead_line'] = array('gt',time()) ;
            $param['post_time'] = array('lt',time()) ;
            $param['category'] = $id ;
            $count=D('news')->where($param)->count();
        }
        $result = array('status'=>1,'data'=>$count,'info'=>'成功~') ;
        $this->ajaxReturn($result) ;
    }
}

