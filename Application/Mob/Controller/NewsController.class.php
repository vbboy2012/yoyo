<?php


namespace Mob\Controller;

use Common\Model\ContentHandlerModel;
use Think\Controller;

class NewsController extends BaseController{

    public function _initialize()
    {
        parent::_initialize();
        $this->_top_menu_list =array(
            'left'=>array(
                array('type'=>'home','href'=>U('Mob/News/index')),
                array('type'=>'message'),
            ),
            'center'=>array('title'=>'全站资讯')
        );
        if(is_login()){
            $this->_top_menu_list['right'][]=array('type'=>'edit','href'=>U('Mob/News/addNews'));
        }else{
            $this->_top_menu_list['right'][]=array('type'=>'edit','info'=>'登录后才能操作！');
        }
        $this->setMobTitle('资讯');
        $this->assign('top_menu_list', $this->_top_menu_list);
    }


//渲染资讯
    public function index($mark=0,$title='',$title_id='')
    {
        $aPage = I('post.page', 1, 'op_t');
        $aCount = I('post.count',10, 'op_t');
        $map['dead_line']=array('gt',time());

        switch ($mark){
            case '0':                   //全站资讯
                $totalCount=D('News')->where(array('status' => 1,$map))->count();
                $news= D('News')->where(array('status' => 1,$map))->order('create_time desc')->page($aPage, $aCount)->select();
                $news_mark['mark']=0;
                break;
            case '1':               //热点资讯
                $this->setTopTitle('热点资讯');
                $totalCount=D('News')->where(array('status' => 1,$map))->count();
                $news= D('News')->where(array('status' => 1,$map))->order('create_time desc,view desc')->page($aPage, $aCount)->select();
                $news_mark['mark']=1;
                break;
            case '2':                  //我的投稿
                $this->setTopTitle('我的投稿');
                $totalCount= D('News')->where(array('uid'=>is_login()))->count();
                $news= D('News')->where(array('uid'=>is_login()))->order('create_time desc,view desc')->page($aPage, $aCount)->select();
                foreach($news as &$a){
                switch ($a['status']){
                    case '1':
                        $a['approval']='审核通过';
                        break;
                    case '2':
                        $a['approval']='待审核';
                        break;
                    case '-1':
                        $a['approval']='审核失败';
                        break;
                }if($a['dead_line']<=time()){
                        $a['approval']='已过期';
                    }
                }
                //dump($news);exit;
                $news_mark['mark']=2;
                break;
            case '3':                       //各级分类资讯
                $this->setTopTitle($title);
                $totalCount= D('News')->where(array('status' => 1, 'category' => $title_id,$map))->count();
                $news = D('News')->where(array('status' => 1, 'category' => $title_id,$map))->page($aPage, $aCount)->order('create_time desc,view desc')->select();
                $news_mark['mark']=3;
                $newstitle['title']=$title;
                $newstitle['title_id']=$title_id;
        }
        foreach ($news as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar32','uid'), $v['uid']);
            if(empty($v['cover'])){
                $v['cover_url']='no_img';
            }else{
                $v['cover_url'] = getThumbImageById($v['cover'],119,89);
            }

            $v['count']=D('LocalComment')->where(array('app'=>'News','mod'=>'index','status'=>1,'row_id'=>$v['id']))->order('create_time desc')->count();
        }
        if($totalCount<=$aPage*$aCount){
            $pid['count']=0;
        }else{
            $pid['count']=1;
        }
        $type=M('news_category')->where('status=1')->field('title,id')->select();
     
        $this->assign('type',$type);
        $this->assign('hotnews',$news);
        $this->assign('newsmark',$news_mark);
        $this->assign('newstitle',$newstitle);
        $this->assign('pid',$pid);
        $this->display();
    }
    //加载更多资讯（热点资讯）
    public function addMoreNews(){
        $aPage = I('post.page', 0, 'op_t');
        $aCount = I('post.count',10, 'op_t');
        $aMark= I('post.mark', 0, 'op_t');
        $aTitleId= I('post.titleid', '', 'op_t');

        $map['dead_line']=array('gt',time());
        switch ($aMark){
            case '0':            //全站资讯
                $news= D('News')->where(array('status' => 1,$map))->order('create_time desc')->page($aPage, $aCount)->select();
                break;
            case '1':            //热点资讯
                $news= D('News')->where(array('status' => 1,$map))->order('create_time desc,view desc')->page($aPage, $aCount)->select();
                break;
            case '2':           //我的投稿
                $news= D('News')->where(array('uid'=>is_login()))->order('create_time desc,view desc')->page($aPage, $aCount)->select();
                foreach($news as &$a){
                    switch ($a['status']){
                        case '1':
                            $a['approval']='审核通过';
                            break;
                        case '2':
                            $a['approval']='待审核';
                            break;
                        case '-1':
                            $a['approval']='审核失败';
                            break;
                    }if($a['dead_line']<=time()){
                        $a['approval']='已过期';
                    }
                }
                break;
            case '3':           //各级分类资讯
                $news = D('News')->where(array('status' => 1, 'category' => $aTitleId,$map))->page($aPage, $aCount)->order('create_time desc,view desc')->select();
        }

        foreach ($news as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar32','uid'), $v['uid']);
            $v['cover_url'] = getThumbImageById($v['cover'],119,89);
            $v['count']=D('LocalComment')->where(array('app'=>'News','mod'=>'index','status'=>1,'row_id'=>$v['id']))->order('create_time desc')->count();
        }
        if ($news) {
            $data['html'] = "";
            foreach ($news as $val) {
                $this->assign("vl", $val);
                $data['html'] .= $this->fetch("_newslist");
                $data['status'] = 1;
            }
        } else {
            $data['stutus'] = 0;
        }
        $this->ajaxReturn($data);
    }



    public function newsDetail($id){
        $aPage = I('post.page', 1, 'op_t');
        $aCount = I('post.count', 10, 'op_t');
        $this->setTopTitle('资讯详情');
        $news_detail= D('News')->where(array('id'=>$id))->find();

        $news_detail['meta']['description']=mb_substr($news_detail['description'],0,50,'UTF-8');//取得前50个字符
        $news_detail['meta']['keywords']=D('NewsCategory')->where(array('id'=>$news_detail['category']))->field('title')->find();
        $news_detail['user'] = query_user(array('nickname', 'avatar32','uid'), $news_detail['uid']);

//dump($news_detail);exit;

      if($news_detail['cover']==0){
          $news_detail['cover_url']='no_img';
      }else{
      //获得原图
          $bi = M('Picture')->where(array('status' => 1))->getById($news_detail['cover']);
          if(!is_bool(strpos( $bi['path'],'http://'))){
              $news_detail['cover_url'] = $bi['path'];
          }else{
              $news_detail['cover_url'] =getRootUrl(). substr( $bi['path'],1);
          }
      }

        D('News')->where(array('id' => $id))->setInc('view');//查看数加1

        $news_content= D('NewsDetail')->where(array('news_id'=>$id))->find();
        $news_content['content']= parse_expression(($news_content['content']));

        $news_comment=D('LocalComment')->where(array('app'=>'News','mod'=>'index','status'=>1,'row_id'=>$id))->order('create_time desc')->page($aPage, $aCount)->select();
        $totalCount=D('LocalComment')->where(array('app'=>'News','mod'=>'index','status'=>1,'row_id'=>$id))->count();
        if($totalCount<=$aPage*$aCount){
            $pid['count']=0;
        }else{
            $pid['count']=1;
        }
        $news_detail['count']=count($news_comment);
        foreach ($news_comment as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar32','uid'), $v['uid']);
            $v['cover_url'] = getThumbImageById($v['cover']);
            $v['content']=parse_weibo_mobile_content($v['content']);

        }

        $news_uid = D('IssueContent')->where(array('status' => 1, 'id' => $id))->find();//根据微博ID查找专辑发送人的UID
        if (is_administrator(get_uid()) || $news_uid['uid'] == get_uid() && is_login()) {                                     //如果是管理员，则可以删除评论
            $news_detail['is_admin_or_mine'] = '1';
        } else {
            $news_detail['is_admin_or_mine'] = '0';
        }


        if($news_detail['status']==2||$news_detail['status']==-1 ){
            $news_detail['can_edit']=1;
        }
        $this->setMobTitle($news_detail['title']);
        $this->setMobDescription( $news_detail['meta']['description']);
        $this->setMobKeywords($news_detail['user']['nickname']);
//dump($news_detail);exit;
        $this->assign('newsdetail',$news_detail);
        $this->assign('newscontent',$news_content);
        $this->assign('newscomment',$news_comment);
        $this->assign('pid',$pid);              //标识数据是否还有第二页。
        $this->display();
    }

    public function addMoreNewsComment(){
        $aPage = I('post.page', 0, 'op_t');
        $aCount = I('post.count', 10, 'op_t');
        $aId = I('post.id', '', 'op_t');
        $news_comment=D('LocalComment')->where(array('app'=>'News','mod'=>'index','status'=>1,'row_id'=>$aId))->order('create_time desc')->page($aPage, $aCount)->select();
        foreach ($news_comment as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar32','uid'), $v['uid']);
            $v['cover_url'] = getThumbImageById($v['cover']);
            $v['content']=parse_weibo_mobile_content($v['content']);
        }
        if ($news_comment) {
            $data['html'] = "";
            foreach ($news_comment as $val) {
                $this->assign("vl", $val);
                $data['html'] .= $this->fetch("_newscomment");
                $data['status'] = 1;
            }
        } else {
            $data['stutus'] = 0;
        }
        $this->ajaxReturn($data);


    }


    public function doAddComment(){
        if (!is_login()) {
            $this->error('请您先进行登录', U('Mob/member/index'), 1);
        }

        $aContent = I('post.content', '', 'op_t');              //获取评论内容
        $aNewsId = I('post.newsId', 0, 'intval');             //获取当前专辑ID
        $aUid = I('post.uid', 0, 'intval');


        $uid = is_login();

        if(empty($aContent)){
            $this->error('评论内容不能为空');
        }
        $result = D('LocalComment')->addNewsComment($uid, $aNewsId, $aContent);
        $title =get_nickname(is_login()). '评论了您';
        D('Common/Message')->sendMessage($aUid,$title, "评论内容：$aContent",  'news/index/detail',array('id' => $aNewsId), is_login(), 1);
        action_log('add_issue_comment', 'local_comment', $result, $uid);

        $news_comment=D('LocalComment')->where(array('app'=>'News','mod'=>'index','status'=>1,'id'=>$result))->order('create_time desc')->select();
        foreach ($news_comment as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar32','uid'), $v['uid']);
            $v['cover_url'] = getThumbImageById($v['cover']);
        }

        if ($news_comment) {
            $data['html'] = "";
            foreach ($news_comment as $val) {
                $this->assign("vl", $val);
                $data['html'] .= $this->fetch("_newscomment");
                $data['html']=parse_expression($data['html']);
                $data['status'] = 1;
            }

        } else {
            $data['stutus'] = 0;
            $data['info'] = '评论失败!';
        }
        $this->ajaxReturn($data);
    }

    /**
     * @param $id
     * @param $user
     * 资讯评论模态弹窗内的内容
     */
    public function atComment($id, $user,$uid)
    {
        //$id是发帖人的IDa
        //$user是用户名


        $map['id'] = array('eq', $id);
        $news = D('News')->where(array('status' => 1, $map))->select();
        $news[0]['user_uid']=$uid;
        // dump($issue);exit;


        foreach ($news as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar64','uid'), $v['uid']);
            $v['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $v['id']))->count();

            $v['at_user_id'] = $user;

        }

       // dump($news);exit;
//dump($news[0]);
        $this->assign('news', $news[0]);
        $this->display(T('Application://Mob@News/atcomment'));

    }


    /**
     * 删除评论
     */
    public function delNewsComment()
    {
        $aCommentId = I('post.commentId', 0, 'intval');              //接收评论ID
        $aNewsId = I('post.newsId', 0, 'intval');                   //接收资讯ID
       // dump($aCommentId);
       // dump($aNewsId);exit;


        $news_uid = D('News')->where(array('status' => 1, 'id' => $aNewsId))->find();//根据资讯ID查找资讯发送人的UID
        $comment_uid = D('LocalComment')->where(array('status' => 1, 'id' => $aCommentId))->find();//根据评论ID查找评论发送人的UID
        if (!is_login()) {
            $this->error('请登陆后再进行操作');
        }


        if (is_administrator(get_uid()) || $news_uid['uid'] == get_uid() || $comment_uid['uid'] == get_uid()) {                                     //如果是管理员，则可以删除评论
            $result = D('LocalComment')->deleteNewsComment($aCommentId);
        }
        if ($result) {
            $return['status'] = 1;
        } else {
            $return['status'] = 0;
            $return['info'] = '删除失败';
        }
        $this->ajaxReturn($return);
    }

    /**
     * 资讯分类内容显示
     */
   /* public function newsType()
    {
        $this->_top_menu_list =array(
            'left'=>array(
                array('type'=>'back','need_confirm'=>1,'confirm_info'=>'确定要返回？','a_class'=>'','span_class'=>''),
            ),
        );
        $level = D('NewsCategory')->where(array('status' => 1, 'pid' => 0))->select();        //查找顶级分类pid=0的
        foreach (  $level as &$a){
            $a['two']=D('NewsCategory')->where(array('status' => 1, 'pid' => $a['id']))->select();
        }
        $this->setTopTitle('资讯分类');
        $this->assign('top_menu_list', $this->_top_menu_list);
        $this->assign("level",   $level);         //顶级分类
        $this->display();
    }*/



    /**
     * 发布帖子页面内容渲染
     */
    public function addNews($id=0){
        if($id>0){          //根据是否有ID传入判断是否是编辑请求
            $news_detail= D('News')->where(array('id'=>$id))->find();
            if(empty($news_detail['cover'])){
                $news_detail['cover_url']='';
            }else{
                $news_detail['cover_url']=getThumbImageById($news_detail['cover'],72,72);
            }
            $news_detail['detail']= D('NewsDetail')->where(array('news_id'=>$id))->find();
            $this->_top_menu_list =array(
                'left'=>array(
                    array('type'=>'back','need_confirm'=>1,'confirm_info'=>'确定要返回？','a_class'=>'','span_class'=>''),
                ),
            );
            $this->assign('top_menu_list', $this->_top_menu_list);
            $this->setTopTitle('编辑资讯');

        }else{
            $this->_top_menu_list =array(
                'left'=>array(
                    array('type'=>'back','need_confirm'=>1,'confirm_info'=>'确定要返回？','a_class'=>'','span_class'=>''),
                ),
            );
            $this->assign('top_menu_list', $this->_top_menu_list);
            $this->setTopTitle('发布资讯');
            $news_detail['cover']=0;
        }


            $category=D('News/NewsCategory')->getCategoryList(array('status'=>1,'can_post'=>1),1);
            $this->assign('category',$category);
//dump($news_detail);exit;
            $this->assign('news',$news_detail);
        $this->display();

    }



    public function doSendNews(){
        $aId=I('post.id',0,'intval');
        $data['category']=I('post.category',0,'intval');
        if($aId){
            $data['id']=$aId;
            $now_data=D('News/News')->getData($aId);
            if($now_data['status']==1){
                $this->error('该资讯已被审核，不能被编辑！');
            }
            $category=D('News/newsCategory')->where(array('status'=>1,'id'=>$data['category']))->find();

            if($category){
                if($category['can_post']){
                    if($category['need_audit']){
                        $data['status']=2;
                    }else{
                        $data['status']=1;
                    }
                }else{
                    $this->error('该分类不能投稿！');
                }
            }else{
                $this->error('该分类不存在或被禁用！');
            }
            $data['status']=2;
            $data['template']=$now_data['detail']['template']?:'';
        }else{
            $this->checkActionLimit('add_news','news',0,is_login(),true);
            $data['uid']=get_uid();
            $data['sort']=$data['position']=$data['view']=$data['comment']=$data['collection']=0;
            $category=D('News/NewsCategory')->where(array('status'=>1,'id'=>$data['category']))->find();

            if($category){
                if($category['can_post']){
                    if($category['need_audit']){
                        $data['status']=2;
                    }else{
                        $data['status']=1;
                    }
                }else{
                    $this->error('该分类不能投稿！');
                }
            }else{
                $this->error('该分类不存在或被禁用！');
            }
            $data['template']='';
        }
        $data['title']=I('post.title','','text');
        $data['description']=I('post.description','','text');
        $data['dead_line']=I('post.dead_line','','text');
        if($data['dead_line']==''){
            $data['dead_line']=99999999999;
        }else{
            $data['dead_line']=strtotime($data['dead_line']);
        }
        $data['source']=I('post.source','','text');
        $data['content']=I('post.content','','html');

        /**
         * 封面判断
         * 范佳炜 fjw@ourstu.com
         */
      $aCoverId=I('post.one_attach_id',0,'intval');
         $aContentImgId =I('post.attach_ids','','text');
         $contentId=explode(',',$aContentImgId);
         if($aCoverId==0){
             if(!empty($aContentImgId))
             {
                 $data['cover']=$contentId[0];
                 $data['content_img'] =$aContentImgId;

             }
         }else{
             $data['cover']=I('post.one_attach_id',0,'intval');
             $data['content_img'] =I('post.attach_ids','','text');
         }


        if(!mb_strlen($data['title'],'utf-8')){
            $this->error('标题不能为空！');
        }
        if(mb_strlen($data['content'],'utf-8')<20){
            $this->error('内容不能少于20个字！');
        }
if( $data['content_img']){
    $img_ids = explode(',',  $data['content_img']);              //把图片和内容结合
    foreach($img_ids as &$v){
        $v = M('Picture')->where(array('status' => 1))->getById($v);
        if(!is_bool(strpos( $v['path'],'http://'))){
            $v = $v['path'];
        }else{
            $v =getRootUrl(). substr( $v['path'],1);
        }
        $v='<p><img src="'. $v.'" style=""/></p><br>';
    };
    $img_ids = implode('', $img_ids);
    $data['content']=$img_ids.$data['content'];
    $contentHandler=new ContentHandlerModel();
    $data['content']=$contentHandler->filterHtmlContent($data['content']);    //把图片和内容结合END
}


        //  dump($data['content']);exit;


        $res=D('News/news')->editData($data);
          // dump(D('News/newsModel')->getLastSql());exit;

        $title=$aId?"编辑":"新增";
        if($res){
            if(!$aId){
                $aId=$res;
                if($category['need_audit']){
                    $return['status'] = 1;
                    $return['info'] =  $title.'资讯成功！请等待审核~';
                }
            }
            $return['status'] = 1;
            $return['info'] =  $title.'资讯成功！';

        }else{
            $return['status'] = 0;
            $return['info'] = $title.'资讯失败！';
        }
        $this->ajaxReturn($return);
    }
    public function forward(){
        if(IS_POST){
            $aNewsid=I('post.newsid','','op_t');
            $aNewstitle=I('post.newstitle','','op_t');
            $aContent=I('post.content','','op_t');

            $postUrl = "http://$_SERVER[HTTP_HOST]" . U('Mob/News/newsdetail', array('id' => $aNewsid));

            $res=D('Weibo')->addWeibo(is_login(),"转发资讯【" . $aNewstitle . "】".$aContent."：" . $postUrl);
            if($res){
                $data['status']=1;
            }else{
                $data['status']=0;
            }
            $this->ajaxReturn($data);
        }else{
            $aNewsid=I('newsid');
        $aNewstitle=I('newstitle');
        $uid=is_login();
        if(!$uid){
            $this->error('请先登录!');
        }else{
            $this->assign('newsid',$aNewsid);
            $this->assign('newstitle',$aNewstitle);
            $this->display(T('Application://Mob@News/forward'));
        }
        }
        
    }
}
