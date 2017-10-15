<?php
namespace Question\Controller;

use Think\Controller;

require_once('./Application/Question/Conf/jssdk.php');
class IndexController extends Controller
{
    public function _initialize(){
        $this->setTitle('问答') ;
    }


    public function index()
    {
        $category=D('question_category');
        $question=D('question');
        $categoryResult=S('question_index_category');
        if ($categoryResult==false){
            $categoryResult=$category->where(array('status'=>1))->select();
            $i=1;
            foreach ( $categoryResult as &$item) {
                $item['categoryId']=$item['id'];
                $item['num']="tab".$i;
                $item['number']="tab".$i;
                $item['total']=$question->where(array('status'=>1,'category'=>$category->where(array("status"=>1,"title"=>$item['title']))->getfield("id")))->count();
                $i++;
            }
            unset($item);
            S('question_index_category',$categoryResult,3600);
        }

        $this->assign("categoryResult",$categoryResult);

        //查询页面第一个tab的分类id
        $categoryId=$category->where(array("status"=>1,"title"=>$categoryResult[0]['title']))->getfield("id");
        $result=S('question_index_result');
        if ($result==false){
            $result=$question->where(array('status'=>1,'category'=>$categoryId))->page(1,10)->order('create_time desc')->select();
            $result=$this->fetchNewsData($result);
            S('question_index_result',$result,360);
        }
        $this->assign("data", $result);
        $this->display();
    }

    /**
     * categoryClick
     * 问题小分类点击
     * @auth wb
     */
    public  function categoryClick(){
        $choiceId=I('post.choiceId', '', 'intval');
        $categoryId=I('post.categoryId', '', 'intval');
        $result=$this->fetchNewsData($this->category($choiceId,1,$categoryId));
        if ($result){
            $this->assign('data',$result);
            $data=$this->fetch('_list');
            $this->ajaxReturn(array(
                'info'=>'请求成功',
                'status'=>1,
                'data'=>$data
            ));
        }
        else{
            $this->ajaxReturn(array(
                'info'=>'请求失败',
                'status'=>0,
                'data'=>''
            ));
        }
    }

    /**
     * question
     * 下拉刷新
     * @auth wb
     */
    public function question()
    {
        $categoryId=I('post.categoryId', '', 'intval');
        $choiceId=I('post.choiceId', '', 'intval');
        $page=I('post.page',1,'intval');
        $result=$this->fetchNewsData($this->category($choiceId,$page,$categoryId));
        if ($result){
            $this->assign('data',$result);
            $data=$this->fetch('_list');
            header('Content-Type:application/json; charset=utf-8');
            exit(json_encode(array(
                'info'=>'请求成功',
                'status'=>1,
                'data'=>$data,
                'count'=>count($result)
            )));
        }else{
            $this->ajaxReturn(array(
                'info'=>'请求失败',
                'status'=>0,
                'data'=>''
            ));
        }

    }

    /**
     * all
     * 问题大分类点击
     * @auth wb
     */
    public function all(){
        if(!is_login()){
            $this->ajaxReturn(array(
                'info'=>'请求失败',
                'status'=>-1,
                'data'=>"请先登录！"
            ));
        }
        $choiceId=I('post.choiceId', '', 'intval');
        $id=I("post.id","","intval");
        $result=$this->fetchNewsData( $this->category($choiceId,1,$id));
        $this->assign('data',$result);
        $html=$this->fetch('_list');
        //dump($result);
       // dump($html);
        $this->ajaxReturn(array(
            'info'=>'请求成功',
            'status'=>1,
            'data'=>$html
        ));
    }

    public function invitation(){

        $followArray=I('post.array', '', '');
        for ($i=0;$i<count($followArray);$i++){
            $data=query_user(array("nickname"),get_uid());
            send_message($followArray[$i],"您有新消息了！","用户".$data['nickname']."邀请您去回答问题！","Question/index/detail",array("id"=>I('post.id', '', 'intval')));
        }

        $this->ajaxReturn(array(
            'info'=>'请求成功',
            'status'=>1
        ));
    }


    public function category($choiceId,$page,$categoryId){
        if($choiceId==1){
            $result=$this->see(array("status"=>1,'category'=>$categoryId),$page);
        }
        if($choiceId==2){
            $result=$this->see(array("status"=>1,'category'=>$categoryId,'answer_num'=>0),$page);
        }
        if($choiceId==3){
            $result=$this->see(array("status"=>1,'category'=>$categoryId),array('answer_num'=>'desc','create_time'=>'desc'),$page);
        }
        if($choiceId==4){
            $result=$this->see(array("status"=>1,'category'=>$categoryId,'uid'=>get_uid()),$page);
        }

        return $result;
    }

    public function see($data=array(),$page=1,$order=array('create_time'=>'desc')){
        return D('question')->where($data)->order($order)->page($page,10)->select();
    }


    /**
     * 提问
     * @auth qhy
     */
    public function ask()
    {
        if (!is_login()) {
            $this->error('请您先登录', U('Ucenter/member/login'), 1);
        }
        //渲染分类
        $map['status']=1;
        $type=D('QuestionCategory')->getCategoryList($map);
        $this->assign('type',$type);
        if(IS_POST){
            //接受数据
            parse_str(I('post.data'),$arr);
            $data['title']=$arr['title'];
            $data['description'] = $arr['intro'];
            $data['category'] =$arr['type_id'];
            $data['img_id'] = $arr['image'];
            $type_title = $arr['reward-type'];
            $data['score_num'] = $arr['reward-num'];
            if ($data['img_id']==''){              //如果没有图片则设置为0
                $data['img_id']=0;
            }
            if ($data['title']==''){
                $this->error('标题不能为空');
            }
            if ($data['description']==''){
                $this->error('问题描述不能为空');
            }
            if ($data['category']==''){
                $this->error('问题分类不能为空');
            }
            //悬赏
            if ($type_title){
                $type_id=D('ucenter_score_type')->where(array('title'=>$type_title))->getField('id');
                $data['leixing']='score'.$type_id;
                $score=D('member')->where(array('uid'=>is_login()))->getField($data['leixing']);
                $score_end=$score-$data['score_num'];
                if ($score_end < 0){
                    $this->error('余额不足');
                }else{
                    $mes[$data['leixing']]=$score_end;
                    $res=D('member')->where(array('uid'=>is_login()))->save($mes);
                    if (!$res){
                        $this->error('数据库存入失败');
                    }
                }
                $data['leixing']=$type_id;
            }

            $question_id=D('Question')->addQuestion($data);
            S('question_index_result',false);
            if ($question_id){
                $this->success('提问成功',U('Question/Index/detail',array('id'=>$question_id)));
            }else{
                $this->error('数据库写入失败');
            }
        }
        $this->display();
    }

    /**
     *  问题详情
     * @auth qhy
     */
    public function detail(){
        $id = I('get.id', '', 'intval');     //获取问题id
        if (IS_POST) {
            $page=I('post.page',1,'intval');
            $id=I('post.id',1,'intval');
            $arr=S('question_detail_'.$id.$page);
            if ($arr==false){
                $answer=D('question_answer')->where(array('question_id'=>$id,'status'=>1))->page($page,10)->order('create_time desc')->select();
                if ($answer){
                    $arr=array();
                    foreach ($answer as &$val){
                        $temp=D('QuestionAnswer')->getAnswer($val['id']);
                        array_push($arr,$temp);
                    }
                    unset($val);
                    S('question_detail_'.$id.$page,$arr,360);
                    $this->assign('answer',$arr);
                    $html=$this->fetch('_anslist');
                    $this->ajaxReturn(array('status'=>1,'info'=>'请求成功','data'=>$html));
                }else{
                    $this->ajaxReturn(array(
                        'info' => '请求失败',
                        'status' => 1,
                        'data' => ''
                    ));
                }
            }elseif ($arr){
                $this->assign('answer',$arr);
                $html=$this->fetch('_anslist');
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
        //问题详情渲染
        $data=S('question_detail_data'.$id);
        if ($data==false){
            $data=D('question')->getQuestion($id);
            S('question_detail_data',$data,3600);
        }
        //最佳回答
        $best=S('question_detail_best'.$id);
        if ($best==false){
            $best=D('QuestionAnswer')->getAnswer($data['best_answer']);
            S('question_detail_best',$best,3600);
        }
        $img=array();
        foreach ($data['img'] as $key=>$val){
            $img[$key]=getThumbImageById($val,50,50);
        }
        unset($val);
        $question=D('question')->where(array('id'=>$id,'status'=>1))->find();
        $head = query_user(array('avatar128'), $question['uid']);
        //不存在http://
        $not_http_remote = (strpos($head['avatar128'], 'http://') === false);
        //不存在https://
        $not_https_remote = (strpos($head['avatar128'], 'https://') === false);
        if ($not_http_remote && $not_https_remote) {
            //本地url
            $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
            $a=substr($head['avatar128'] , 0 , 2);
            if($a=='..'){
                $head['avatar128']=substr( $head['avatar128'] , 2 , strlen($head['avatar128'])-2);
            }else{
                $_SERVER['HTTP_HOST']=$_SERVER['HTTP_HOST'].'/m/';
            }
            $head['avatar128'] =  $http_type.$_SERVER['HTTP_HOST']. $head['avatar128'];
        }
        $this->assign('headimg',$head['avatar128']);
        $this->assign('data',$data);
        $this->assign('best',$best);
        $this->assign('img',$data['img']);
        $this->assign('image',$img[0]);
        //邀请回答列表
        $user=D('Follow')->where(array("who_follow"=>get_uid()))->field('follow_who')->order('create_time desc')->select();
        foreach ($user as &$item) {
            $item['user']=query_user(array('avatar32','nickname','uid'),$item['follow_who']);
            $item['info']=D('question_rank')->where(array('uid'=>$item['follow_who']))->find();
        }
        unset($item);
        $appid=modC('APP_ID','','weixin');
        $appsecret=modC('APP_SECRET','','weixin');
        $jssdk = new \JSSDK ($appid,$appsecret);
        $signPackage = $jssdk->GetSignPackage();
        $this->assign("signPackage",$signPackage);
        $this->assign('count',count($user));
        $this->assign('user',$user);
        $this->display();
    }

    /**
     * 回答问题
     * @auth qhy
     */
    public function addAnswer()
    {
        if (!is_login()) {
            $this->error('请您先登录', U('Ucenter/member/login'), 1);
        }

        if (IS_POST) {
            $aContent = I('post.content', '', 'op_t');              //回答内容
            $aQuestionId = I('post.questionId', 0, 'intval');             //要回答的问题ID
            if ($aContent == "") {
                $res['status'] = -1;
                $res['info'] = '回答不能为空';
                $this->ajaxReturn($res);
            }
            $questionAnswer = send_answer($aQuestionId, $aContent);
            $uid=D('question_rank')->where(array('uid'=>is_login()))->find();
            if ($uid){
                $success=D('question_rank')->where(array('uid'=>is_login()))->setInc('answer_count');
                if (!$success){
                    $this->error('数据库写入失败');
                }
            }else{
                $rank['answer_count']=1;
                $rank['uid']=is_login();
                $rank['best_answer_count']=0;
                $rank['support_count']=0;
                $success=D('question_rank')->add($rank);
                if (!$success){
                    $this->error('数据库写入失败');
                }
            }
            D('question')->where(array('id'=> $aQuestionId))->setInc('answer_num');
            if ($questionAnswer) {
                $data['html'] = "";
                $answer = D('QuestionAnswer')->getAnswer($questionAnswer);
                if (S('question_detail_'.$aQuestionId.'1')){
                    $arr=S('question_detail_'.$aQuestionId.'1');
                    array_unshift($arr,$answer);
                    S('question_detail_'.$aQuestionId.'1',$arr,360);
                }
                $answer=array(
                    '0'=>$answer
                );
                $this->assign('answer', $answer);
                $data['html'] .= $this->fetch("_anslist");
                $data['status'] = 1;
            } else {
                $data['status'] = 0;
            }
            $this->ajaxReturn($data);
        }
    }

    /**
     * 删除问题
     * @auth qhy
     */
    public function doDelQuestion()
    {
        $aQuestionId = I('post.id', 0, 'intval');
        $aQuestionUid = I('post.uid', 0, 'intval');
        if (!check_auth('Question/Index/delQuestion', $aQuestionUid)) {
            $this->error('您没有权限删除该评论');
        }
        $question=D('question')->where(array('id'=>$aQuestionId))->find();
        if ($question['best_answer']==0){
            $type='score'.$question['leixing'];
            $score=D('member')->where(array('uid'=>$question['uid']))->getField($type);
            $score_end=$score + $question['score_num'];
            $mes[$type]=$score_end;
            $res=D('member')->where(array('uid'=>$question['uid']))->save($mes);
            if (!$res){
                $this->error('数据库存入失败');
            }
        }
        $result=D('question')->where(array('id'=>$aQuestionId))->delete();
        D('question_answer')->where(array('question_id'=>$aQuestionId))->delete();
        $nickname = query_user(array('uid', 'avatar32', 'avatar64', 'avatar128', 'space_url', 'nickname'), is_login());
        send_message($question['uid'],'您的提问已被管理员'.$nickname['nickname'].'删除。如果还未设置最佳答案，悬赏会返回到您的账户。');
        if ($result) {
            S('question_index_result',false);
            $this->success('删除成功',U('Question/Index/index'),3);
        } else {
            $return['status'] = 0;
            $return['status'] = L('_ERROR_INSET_DB_');
        }
        //返回成功信息
        $this->ajaxReturn($return);
    }

    /**
     * 删除回答
     * @auth qhy
     */
    public function doDelAnswer()
    {
        $aAnswerId = I('post.answer_id', 0, 'intval');
        $aAnswerUid = I('post.answer_uid', 0, 'intval');
        if (!check_auth('Question/Index/delAnswer', $aAnswerUid)) {
            $this->error('您没有权限删除该评论');
        }
        $answer=D('question_answer')->where(array('id'=>$aAnswerId))->find();
        D('question')->where(array('id'=> $answer['question_id']))->setDec('answer_num');
        D('question_rank')->where(array('uid'=>$answer['uid']))->setDec('answer_count');
        $result=D('question_answer')->where(array('id'=>$aAnswerId))->delete();
        $nickname = query_user(array('uid', 'avatar32', 'avatar64', 'avatar128', 'space_url', 'nickname'), is_login());
        send_message($answer['uid'],'您的回答已被管理员'.$nickname['nickname'].'删除。');
        S('question_detail_'.$answer['question_id'].'1',false);
        if ($result) {
            $this->success('删除成功');
        } else {
            $return['status'] = 0;
            $return['status'] = L('_ERROR_INSET_DB_');
        }
        //返回成功信息
        $this->ajaxReturn($return);
    }

    /**
     * 设置最佳回答
     * @auth qhy
     */
    public function setBest()
    {
        $aAnswerId = I('post.answer_id', 0, 'intval');
        $data=D('question_answer')->where(array('id'=>$aAnswerId))->find();
        $question=D('question')->where(array('id'=>$data['question_id']))->find();
        if ($question['score_num'] > 0){
            $type='score'.$question['leixing'];
            $score=D('member')->where(array('uid'=>$data['uid']))->getField($type);
            $score_end=$score + $question['score_num'];
            $mes[$type]=$score_end;
            $reward=D('member')->where(array('uid'=>$data['uid']))->save($mes);
            if (!$reward){
                $this->error('数据库存入失败');
            }
        }
        S('question_detail_'.$data['question_id'].'1',false);
        $best['best_answer']=$aAnswerId;
        $res=D('question')->where(array('id'=>$data['question_id']))->save($best);
        D('question_rank')->where(array('uid'=>$data['uid']))->setInc('best_answer_count');
        if ($res){
            $this->success('设置成功',U('Question/Index/detail',array('id'=>$question['id'])),3);
        }else{
            $this->error('数据库写入失败');
        }

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
     * search
     * 查询热门搜索
     * @auth wb
     */
    public function search(){
        $change=D("question");
        if(IS_POST){
            $page=I("post.page","","intval");
            $result=$change->order("answer_num desc")->page($page,8)->select();
            //如果是最后一组了就从第一组查
            if($result==null){
                $result=$change->order("answer_num desc")->page(1,8)->select();

                $res['is']=0;
            }
            $res['status']="1";
            $res['data']=$result;

            $this->ajaxReturn($res);
        }
        else{
            $history=D("question");
            $result=$history->order("answer_num desc")->page(1,8)->select();
            $this->assign("result",$result);
            $historyResult=D("question_search")->where(array("uid"=>get_uid()))->order("create_time desc")->limit(3)->select();
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
        $change=D("question");
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
        $history=D("question_search");
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
        $history=D("question_search");
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
        $history=D("question_search");
        $history->where(array("uid"=>get_uid()))->delete();
        $this->ajaxReturn(array("status" => "1"));
    }


    /**
     * @param $data array 需要遍历处理的数据
     * @return array 返回处理后的数据
     * @auth nkx nkx@ourstu.com
     */
    public function fetchNewsData(array $data)
    {
        foreach ($data as &$datum){
            $datum['category']=D('question_category')->find($datum['category']);
            $datum['uid']=query_user(array('uid','nickname','avatar512'),$datum['uid']);
            $datum['create_time']=friendlyDate($datum['create_time']);
            $datum['support']=D('support')->where(array('row'=>$datum['id'],'Appname'=>'News'))->count();
            $datum['img_id']=explode(',',$datum['img_id']);
            $datum['leixing']=D('ucenter_score_type')->where(array('id'=>$datum['leixing']))->getField('title');
            foreach ($datum['img_id'] as &$val){
                $val=getThumbImageById($val,50,50);
            }
        }
        unset($datum);
        unset($val);
        return $data;
    }

}

