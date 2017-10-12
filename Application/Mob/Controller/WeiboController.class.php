<?php


namespace Mob\Controller;

use Think\Controller;
use Think\Hook;

class WeiboController extends BaseController
{

    public function _initialize()
    {
        parent::_initialize();
        $this->_top_menu_list =array(
            'left'=>array(
                array('type'=>'home','href'=>U('Mob/Weibo/index')),
                array('type'=>'message'),
            ),
            'center'=>array('title'=>'微博')
        );
        if(D('Member')->need_login()||is_login()){
            if(check_auth('Weibo/Index/doSend')){
                $this->_top_menu_list['right'][]=array('type'=>'edit','href'=>U('Mob/Weibo/addWeibo'));
            }else{
                $this->_top_menu_list['right'][]=array('type'=>'edit','info'=>'你没有权限发布微博！');
            }
        }else{
            $this->_top_menu_list['right'][]=array('type'=>'edit','info'=>'登录后才能操作！');
        }
        //dump($this->_top_menu_list);exit;
        $this->setMobTitle('微博');
        $this->assign('top_menu_list', $this->_top_menu_list);
    }



    /**
     * 主页面显示
     */
    public function index()
    {

        $aPage = I('post.page', 1, 'op_t');
        $aCount = I('post.count',10, 'op_t');
        $totalCount = D('Weibo')->where(array('status' => 1))->count();
        $crowdId = I('get.crowd_id', 0, 'intval');
        
        
        if(!empty($crowdId)) {
            $weiboModel = D('Weibo');

            $param['where']['crowd_id'] = $crowdId;
            $crowd = D('WeiboCrowd')->getCrowd($crowdId);
            if (empty($crowd)) {
                $this->error('圈子不存在');
            }
            $isJoin = D('Weibo/WeiboCrowdMember')->getIsJoin(is_login(),$crowdId);
            if ($crowd['invisible'] && !$isJoin) {
                $this->assign('invisible',1);
            }
            if ($crowd['order_type'] == 1) {
                $param['order'] = 'reply_time desc,create_time desc';
            }
            $crowd['is_admin'] = check_auth('Weibo/Manage/*', get_crowd_admin($crowdId));
            $crowd['check_num'] = D('WeiboCrowdMember')->where(array('crowd_id' => $crowdId, 'status' => 0))->count();
            $crowd['crowd_admin'] = query_user(array('nickname', 'avatar128', 'space_url', 'fans'), $crowd['uid']);
            $crowd['is_follow'] = D('Mob/WeiboCrowdMember')->getIsJoin(is_login(),$crowdId);
            $this->assign('crowd_detail', $crowd);
            $this->assign('crowd_type', $crowdId);
//        $this->assign('loadMoreUrl', U('loadWeiboByCrowd', array('uid' => $aUid)));
            $this->assign('crowd_weibo_list', 1);
            // 获取圈子置顶微博
            $crowd_top_list = $weiboModel->getWeiboList(array('where' => array('status' => 1, 'is_crowd_top' => 1, 'crowd_id' => $crowdId)));
            $this->assign('crowd_top_list', $crowd_top_list);

            $list = $weiboModel->getWeiboList($param);
            $weibo = M('Weibo')->where(array('status' => 1, 'id' => array('in', $list)))->page($aPage, $aCount)->order('create_time desc')->select();
        } else {
            $map['status'] = 1;
            $invisibleList = D('Weibo/WeiboCrowd')->getInvisible();
            if (!empty($invisibleList)) {
                $invisible = array_column($invisibleList,'id');
                $map['crowd_id'] = array('not in',$invisible);
            }
            $weibo = D('Weibo')->where($map)->page($aPage, $aCount)->order('create_time desc')->select();
        }
        

        $support['appname'] = 'Weibo';                              //查找是否点赞
        $support['table'] = 'weibo';
        $support['uid'] = is_login();
        $is_zan = D('Support')->where($support)->select();
        $is_zan = array_column($is_zan, 'row');

        foreach ($weibo as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar64', 'uid'), $v['uid']);
            $v['rand_title'] = mob_get_head_title($v['uid']);
            $v['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $v['id']))->count();

            if($v['type']==="repost"){
                $v['content']=parse_br_nb( $v['content']);
                $v['content']= A('Mob/WeiboType')->fetchRepost($v);
            }else if($v['type']==="xiami") {
                $v['content'] = parse_br_nb($v['content']);
                $v['content'] = Hook::exec('Addons\\Insert' . ucfirst($v['type']) . '\\Insert' . ucfirst($v['type']) . 'Addon', 'fetch' . 'Mobile' . ucfirst($v['type']), $v);
            }else if($v['type']=="video"){
                $v['content'] = parse_br_nb($v['content']);
                $v['content'] = Hook::exec('Addons\\Insert' . ucfirst($v['type']) . '\\Insert' . ucfirst($v['type']) . 'Addon', 'fetch' . ucfirst($v['type']), $v);
            }else if($v['type']=="redbag"){
                $v['content'] = parse_br_nb($v['content']);
                $v['content']= A('Mob/WeiboType')->fetchRedbag($v);
            }
            else if($v['type']=="LocalVideo"){
                $v['content'] = parse_br_nb($v['content']);
                $v['content']= A('Mob/WeiboType')->fetchlocalvideo($v);
            }
            else{
                $v['content'] = parse_weibo_mobile_content($v['content']);
            }

         //   $v['content'] = parse_weibo_mobile_content($v['content']);


            if (empty($v['from'])) {
                $v['from'] = "网站端";
            }
            $v['data'] = unserialize($v['data']);              //字符串转换成数组,获取微博源ID
            if ($v['data']['sourceId']) {                        //判断是否是源微博
                $v['sourceId'] = $v['data']['sourceId'];
                $v['is_sourceId'] = '1';
            } else {
                $v['sourceId'] = $v['id'];
                $v['is_sourceId'] = '0';
            }
            $v['sourceId_user'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->find();           //源微博用户名
            $v['sourceId_user'] = $v['sourceId_user']['uid'];
            $v['sourceId_user'] = query_user(array('nickname', 'uid'), $v['sourceId_user']);

            $v['sourceId_content'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('content')->find();          //源微博内容
           if(!is_null($v['sourceId_content'])){
               $v['sourceId_content'] = parse_weibo_mobile_content($v['sourceId_content']['content']);                                          //把表情显示出来。
           }


            $v['sourceId_repost_count'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('repost_count')->find();    //源微博转发数
            $v['sourceId_from'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('from')->find();       //源微博来源


            if (!empty($v['crowd_id'])) {
                $crowd = D('Mob/WeiboCrowd')->getCrowd($v['crowd_id']);
                $v['crowd_logo'] = $crowd['logo'];
                $v['crowd_title'] = $crowd['title'];
            }

            if (empty($v['sourceId_from']['from'])) {
                $v['sourceId_from'] = "网站端";
            } else {
                $v['sourceId_from'] = "手机网页版";
            }

            $v['sourceId_img'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('data')->find();    //为了获取源微图片
            $v['sourceId_img'] = unserialize($v['sourceId_img']['data']);
            $v['sourceId_img'] = explode(',', $v['sourceId_img']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['sourceId_img'] as &$b) {
                $v['sourceId_img_path'][] = getThumbImageById($b, 100, 100);                      //获得缩略图
//获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if (!is_bool(strpos($bi['path'], 'http://'))) {
                    $v['sourceId_img_big'][] = $bi['path'];
                } else {
                    $v['sourceId_img_big'][] = getRootUrl() . substr($bi['path'], 1);
                }
            }


            $v['cover_url'] = explode(',', $v['data']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['cover_url'] as &$a) {
                $v['img_path'][] = getThumbImageById($a, 100, 100);
                //获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if (!is_bool(strpos($bi['path'], 'http://'))) {
                    $v['sourceId_img_big'][] = $bi['path'];
                } else {
                    $v['sourceId_img_big'][] = getRootUrl() . substr($bi['path'], 1);
                }
            }

            if (in_array($v['id'], $is_zan)) {                         //判断是否已经点赞
                $v['is_support'] = '1';
            } else {
                $v['is_support'] = '0';
            }

            if (empty($v['data']['attach_ids'])) {            //判断是否是图片
                $v['is_img'] = '0';
            } else {
                $v['is_img'] = '1';
            }
            if (empty($v['sourceId_img']['0'])) {
                $v['sourceId_is_img'] = '0';
            } else {
                $v['sourceId_is_img'] = '1';
            }

        }


        if ($totalCount <= $aPage * $aCount) {
            $pid['count'] = 0;
        } else {
            $pid['count'] = 1;
        }
        $pid['is_allweibo'] = 1;

//dump($weibo);
        
        $this->assign("weibo", $weibo);
        $this->assign("pid", $pid);
        $this->assign('navtitle','allweibo');
        $this->display();

    }

    /**
     * 查看更多功能实现
     */

    public function addMoreWeibo()
    {

        $aPage = I('get.page', 1, 'op_t');
        $aCount = I('post.count', 10, 'op_t');
        $weibo = D('Weibo')->where(array('status' => 1,))->page($aPage, $aCount)->order('create_time desc')->select();

        $support['appname'] = 'Weibo';                              //查找是否点赞
        $support['table'] = 'weibo';
        $support['uid'] = is_login();
        $is_zan = D('Support')->where($support)->select();
        $is_zan = array_column($is_zan, 'row');

        foreach ($weibo as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar64', 'uid'), $v['uid']);
            $v['rand_title'] = mob_get_head_title($v['uid']);
            $v['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $v['id']))->count();
            if($v['type']==="repost"){
                $v['content']=parse_br_nb( $v['content']);
                $v['content']= A('Mob/WeiboType')->fetchRepost($v);
            }else if($v['type']==="xiami") {
                $v['content'] = parse_br_nb($v['content']);
                $v['content'] = Hook::exec('Addons\\Insert' . ucfirst($v['type']) . '\\Insert' . ucfirst($v['type']) . 'Addon', 'fetch'.'mobile'. ucfirst($v['type']), $v);
            }else if($v['type']=="video"){
                $v['content'] = parse_br_nb($v['content']);
                $v['content'] = Hook::exec('Addons\\Insert' . ucfirst($v['type']) . '\\Insert' . ucfirst($v['type']) . 'Addon', 'fetch' . ucfirst($v['type']), $v);
            }else if($v['type']=="redbag"){
                $v['content'] = parse_br_nb($v['content']);
                $v['content']= A('Mob/WeiboType')->fetchRedbag($v);
            }
            else if($v['type']=="LocalVideo"){
                $v['content'] = parse_br_nb($v['content']);
                $v['content']= A('Mob/WeiboType')->fetchlocalvideo($v);
            }
            else{
                $v['content'] = parse_weibo_mobile_content($v['content']);
            }
            if (empty($v['from'])) {
                $v['from'] = "网站端";
            }

            $v['data'] = unserialize($v['data']);              //字符串转换成数组,获取微博源ID
            if ($v['data']['sourceId']) {                        //判断是否是源微博
                $v['sourceId'] = $v['data']['sourceId'];
                $v['is_sourceId'] = '1';
            } else {
                $v['sourceId'] = $v['id'];
                $v['is_sourceId'] = '0';

            }
            $v['sourceId_user'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->find();           //源微博用户名
            $v['sourceId_user'] = $v['sourceId_user']['uid'];
            $v['sourceId_user'] = query_user(array('nickname', 'uid'), $v['sourceId_user']);


            $v['sourceId_content'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('content')->find();          //源微博内容
            if(!is_null($v['sourceId_content'])){
                $v['sourceId_content'] = parse_weibo_mobile_content($v['sourceId_content']['content']);                                          //把表情显示出来。
            }
            $v['sourceId_content'] = parse_weibo_mobile_content($v['sourceId_content']['content']);

            $v['sourceId_repost_count'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('repost_count')->find();    //源微博转发数
            $v['sourceId_from'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('from')->find();       //源微博来源
            if (empty($v['sourceId_from']['from'])) {
                $v['sourceId_from'] = "网站端";
            } else {
                $v['sourceId_from'] = "手机网页版";
            }

            $v['sourceId_img'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('data')->find();    //为了获取源微图片
            $v['sourceId_img'] = unserialize($v['sourceId_img']['data']);
            $v['sourceId_img'] = explode(',', $v['sourceId_img']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['sourceId_img'] as &$b) {
                $v['sourceId_img_path'][] = getThumbImageById($b, 100, 100);
//获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if (!is_bool(strpos($bi['path'], 'http://'))) {
                    $v['sourceId_img_big'][] = $bi['path'];
                } else {
                    $v['sourceId_img_big'][] = getRootUrl() . substr($bi['path'], 1);
                }
            }


            $v['cover_url'] = explode(',', $v['data']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['cover_url'] as &$a) {
                $v['img_path'][] = getThumbImageById($a, 100, 100);
                //获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if (!is_bool(strpos($bi['path'], 'http://'))) {
                    $v['sourceId_img_big'][] = $bi['path'];
                } else {
                    $v['sourceId_img_big'][] = getRootUrl() . substr($bi['path'], 1);
                }
            }

            if (in_array($v['id'], $is_zan)) {                         //判断是否已经点赞
                $v['is_support'] = '1';
            } else {
                $v['is_support'] = '0';
            }

            if (empty($v['data']['attach_ids'])) {            //判断是否是图片
                $v['is_img'] = '0';
            } else {
                $v['is_img'] = '1';
            }
            if (empty($v['sourceId_img']['0'])) {
                $v['sourceId_is_img'] = '0';
            } else {
                $v['sourceId_is_img'] = '1';
            }
        }
        $this->assign('weibo', $weibo);
        if ($weibo) {
            $data['html'] = "";
            $data['status'] = 1;
            $data['html'] .= $this->fetch("_weibolist");
        } else {
            $data['stutus'] = 0;
        }
        $this->ajaxReturn($data);

    }

    /**
     * @param $id
     * 微博细节
     */

    public function weiboDetail($id)
    {
        $aPage = I('post.page', 1, 'op_t');
        $aCount = I('post.count', 10, 'op_t');
        $this->setTopTitle('微博详情');
        $weibodetail = D('Weibo')->where(array('id'=>$id,'status'=>1))->find();
        if(is_null($weibodetail)){
            $this->error('微博不存在');
        }
        if($weibodetail['type']=="redbag"){
            $weibo_data = unserialize($weibodetail['data']);
            $redbag = M('Redbag')->where(array('id' => $weibo_data['id']))->find();
            $surplus = $redbag['all_money'] - $redbag['sell_money'];
            $field = D('Ucenter/Score')->getTypeList(array('status' => 1));
            if ($surplus > 0&&$redbag['status']!=2){
                if((time()-$redbag['create_time'])>=86400){
                    foreach ($field as &$v) {
                        if ($weibo_data['type'] == $v['id']) {
                            D('Ucenter/Score')->setUserScore($redbag['uid'],$surplus,$v['id'],'inc');
                            // D('Member')->where(array('uid' => $redbag['uid']))->setInc('score' . $v['id'], $surplus);
                            D('Redbag')->where(array('id' => $weibo_data['id']))->setField('status',2);
                            D('Message')->sendMessageWithoutCheckSelf($redbag['uid'], '您的红包并未全部领取，剩余' . $surplus.$v['title'].'已返还。', $v['title'] . '增加了' . $surplus);
                        }
                    }
                }
            }
            $user = query_user(array('uid', 'nickname', 'avatar64', 'space_url', 'rank_link', 'title'), $weibo_data['uid']);
            $this->assign('user', $user);
            $weibo_data['weibo_id']=$weibodetail['id'];
            $this->assign('weibo', $weibodetail);
            unset($weibo_data['uid']);
            unset($weibo_data['num']);
            unset($weibo_data['all_money']);
            unset($weibo_data['sell_money']);
            unset($weibo_data['rank']);
            unset($weibo_data['type']);
            unset($weibo_data['content']);
            unset($weibo_data['redbag_type']);
            unset($weibo_data['create_time']);
            unset($weibo_data['status']);
            // dump($weibo_data);exit;
            $this->assign('weibo_data', $weibo_data);
        }else if($weibodetail['type']==="repost") {
            $weibodetail['content']=parse_br_nb($weibodetail['content']);
            $weibodetail['content']= A('Mob/WeiboType')->fetchRepost($weibodetail);
        }else if($weibodetail['type']==="xiami") {
            $weibodetail['content'] = parse_br_nb($weibodetail['content']);
            $weibodetail['content'] = Hook::exec('Addons\\Insert' . ucfirst($weibodetail['type']) . '\\Insert' . ucfirst($weibodetail['type']) . 'Addon', 'fetch' . 'Mobile' . ucfirst($weibodetail['type']), $weibodetail);
        }else {
            $weibodetail['content'] = parse_weibo_mobile_content($weibodetail['content']);
        }


        $weibodetail['meta']['description'] = mb_substr($weibodetail['content'], 0, 50, 'UTF-8');//取得前50个字符

        $support['appname'] = 'Weibo';
        $support['table'] = 'weibo';
        $support['uid'] = is_login();
        $is_zan = D('Support')->where($support)->select();
        $is_zan = array_column($is_zan, 'row');

        if (!empty($weibodetail['crowd_id'])) {
            $crowd = D('Mob/WeiboCrowd')->getCrowd($weibodetail['crowd_id']);
            $weibodetail['crowd_logo'] = $crowd['logo'];
            $weibodetail['crowd_title'] = $crowd['title'];
        }

        if (empty($weibodetail['from'])) {
            $weibodetail['from'] = "网站端";
        }

        $weibodetail['user'] = query_user(array('nickname', 'avatar64', 'uid'), $weibodetail['uid']);
        $weibodetail['rand_title'] = mob_get_head_title($weibodetail['uid']);

        $weibodetail['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $weibodetail['id']))->count();


        $weibodetail['data'] = unserialize($weibodetail['data']);              //字符串转换成数组,获取微博源ID
        if ($weibodetail['data']['sourceId']) {                        //
            $weibodetail['sourceId'] = $weibodetail['data']['sourceId'];
            $weibodetail['is_sourceId'] = '1';
        } else {
            $weibodetail['sourceId'] = $weibodetail['id'];
            $weibodetail['is_sourceId'] = '0';
        }


        $weibodetail['sourceId_user'] = D('Weibo')->where(array('status' => 1, 'id' => $weibodetail['sourceId']))->find();           //源微博用户名

        $weibodetail['sourceId_user'] = $weibodetail['sourceId_user']['uid'];
        $weibodetail['sourceId_user'] = query_user(array('nickname', 'uid'), $weibodetail['sourceId_user']);
        $weibodetail['sourceId_content'] = D('Weibo')->where(array('status' => 1, 'id' => $weibodetail['sourceId']))->find();          //源微博内容

        if(!is_null($weibodetail['sourceId_content'])){

            if($weibodetail['sourceId_content']['type']=="redbag"){
                $weibo_data = unserialize($weibodetail['sourceId_content']['data']);

                $redbag = M('Redbag')->where(array('id' => $weibo_data['id']))->find();
                $surplus = $redbag['all_money'] - $redbag['sell_money'];
                $field = D('Ucenter/Score')->getTypeList(array('status' => 1));
                if ($surplus > 0&&$redbag['status']!=2){
                    if((time()-$redbag['create_time'])>=86400){
                        foreach ($field as &$v) {
                            if ($weibo_data['type'] == $v['id']) {
                                D('Ucenter/Score')->setUserScore($redbag['uid'],$surplus,$v['id'],'inc');
                                // D('Member')->where(array('uid' => $redbag['uid']))->setInc('score' . $v['id'], $surplus);
                                D('Redbag')->where(array('id' => $weibo_data['id']))->setField('status',2);
                                D('Message')->sendMessageWithoutCheckSelf($redbag['uid'], '您的红包并未全部领取，剩余' . $surplus.$v['title'].'已返还。', $v['title'] . '增加了' . $surplus);
                            }
                        }
                    }
                }
                $user = query_user(array('uid', 'nickname', 'avatar64', 'space_url', 'rank_link', 'title'), $weibo_data['uid']);
                $this->assign('user', $user);
                $weibo_data['weibo_id']=$weibodetail['sourceId_content']['id'];
                $this->assign('weibo', $weibodetail['sourceId_content']);
                unset($weibo_data['uid']);
                unset($weibo_data['num']);
                unset($weibo_data['all_money']);
                unset($weibo_data['sell_money']);
                unset($weibo_data['rank']);
                unset($weibo_data['type']);
                unset($weibo_data['content']);
                unset($weibo_data['redbag_type']);
                unset($weibo_data['create_time']);
                unset($weibo_data['status']);
                // dump($weibo_data);exit;
                $this->assign('weibo_data', $weibo_data);
            }

            $weibodetail['sourceId_content'] = parse_weibo_mobile_content($weibodetail['sourceId_content']['content']);                                          //把表情显示出来。
        }
        $weibodetail['sourceId_repost_count'] = D('Weibo')->where(array('status' => 1, 'id' => $weibodetail['sourceId']))->field('repost_count')->find();    //源微博转发数
        $weibodetail['sourceId_from'] = D('Weibo')->where(array('status' => 1, 'id' => $weibodetail['sourceId']))->field('from')->find();       //源微博来源

        if (empty($weibodetail['sourceId_from']['from'])) {
            $weibodetail['sourceId_from'] = "网站端";
        } else {
            $weibodetail['sourceId_from'] = "手机网页版";
        }

        $weibodetail['sourceId_img'] = D('Weibo')->where(array('status' => 1, 'id' => $weibodetail['sourceId']))->field('data')->find();    //为了获取源微图片
        $weibodetail['sourceId_img'] = unserialize($weibodetail['sourceId_img']['data']);
        $weibodetail['sourceId_img'] = explode(',', $weibodetail['sourceId_img']['attach_ids']);        //把attach_ids里的图片ID转出来
        foreach ($weibodetail['sourceId_img'] as &$b) {                                     //取得转发后源微博图片
            $weibodetail['sourceId_img_path'][] = getThumbImageById($b, 100, 100);
            //获得原图
            $bi = M('Picture')->where(array('status' => 1))->getById($b);
            if (!is_bool(strpos($bi['path'], 'http://'))) {
                $weibodetail['sourceId_img_big'][] = $bi['path'];
            } else {
                $weibodetail['sourceId_img_big'][] = getRootUrl() . substr($bi['path'], 1);
            }

        }

        $weibodetail['cover_url'] = explode(',', $weibodetail['data']['attach_ids']);        //把attach_ids里的图片ID转出来
        foreach ($weibodetail['cover_url'] as &$a) {                                   //取得转发的微博的图片
            $weibodetail['img_path'][] = getThumbImageById($a, 100, 100);

        }

        if (empty($weibodetail['data']['attach_ids'])) {            //判断是否是图片
            $weibodetail['is_img'] = '0';
        } else {
            $weibodetail['is_img'] = '1';
        }

        if (in_array($weibodetail['id'], $is_zan)) {                         //判断是否已经点赞
            $weibodetail['is_support'] = '1';
        } else {
            $weibodetail['is_support'] = '0';
        }

        if (empty($weibodetail['sourceId_img']['0'])) {                     //判断源微博是否有图片
            $weibodetail['sourceId_is_img'] = '0';
        } else {
            $weibodetail['sourceId_is_img'] = '1';
        }

        $mapl['weibo_id'] = array('eq', $id);
        $weibocomment = D('Weibo_comment')->where(array('status' => 1, $mapl))->page($aPage, $aCount)->order('create_time desc')->select();
        $totalCount = D('Weibo_comment')->where(array('status' => 1, $mapl))->count();
        if ($totalCount <= $aPage * $aCount) {
            $pid['count'] = 0;
        } else {
            $pid['count'] = 1;
        }
        foreach ($weibocomment as &$k) {
            $k['user'] = query_user(array('nickname', 'avatar32', 'uid'), $k['uid']);
            $k['rand_title'] = mob_get_head_title($k['uid']);
            $k['content'] = parse_weibo_mobile_content($k['content']);
        }
        $this->setMobTitle($weibodetail['user']['nickname']);
        $this->setMobDescription($weibodetail['meta']['description']);
        $this->setMobKeywords($weibodetail['user']['nickname']);
//dump($weibodetail);exit;
        $this->assign("weibodetail", $weibodetail);
        $this->assign("pid", $pid);           //判断评论数量是否大于10
        $this->assign('weibocomment', $weibocomment);                //微博评论

        $this->display();
    }

    /**
     * @param $id
     * 微博细节
     */

    public function addMoreComment()
    {

        $aPage = I('post.page', 1, 'op_t');
        $aCount = I('post.count', 10, 'op_t');

        $aId = I('post.id', '', 'op_t');

        $map['weibo_id'] = array('eq', $aId);
        $weibocomment = D('WeiboComment')->where(array('status' => 1, $map))->page($aPage, $aCount)->order('create_time desc')->select();

        foreach ($weibocomment as &$k) {
            $k['user'] = query_user(array('nickname', 'avatar32', 'uid'), $k['uid']);
            $k['rand_title'] = mob_get_head_title($k['uid']);
            $k['content'] = parse_weibo_mobile_content($k['content']);
        }

        if ($weibocomment) {
            $data['html'] = "";
            foreach ($weibocomment as $val) {
                $this->assign("vl", $val);
                $data['html'] .= $this->fetch("_weibocomment");

                $data['status'] = 1;
            }
        } else {
            $data['stutus'] = 0;
        }
        $this->ajaxReturn($data);
    }

    /**
     * 渲染发布微博页面
     */

    public function addWeibo(){
        //圈子
        $followCrowd = D('WeiboCrowdMember')->getUserJoin(is_login());
        foreach ($followCrowd as $key => &$v) {
            $res = D('WeiboCrowd')->getCrowd($v['crowd_id']);
            $v['crowd'] = $res;
            if (empty($res)) {
                unset($followCrowd[$key]);
            }
        }
        unset($v);
//        dump($followCrowd);exit;
        $this->assign('follow_crowd_list', $followCrowd);
        
        $this->_top_menu_list =array(
            'left'=>array(
                array('type'=>'back','need_confirm'=>1,'confirm_info'=>'确定要返回？','a_class'=>'','span_class'=>''),
            ),
        );
       // dump($this->_top_menu_list);exit;
        $this->assign('top_menu_list', $this->_top_menu_list);
        $this->setTopTitle('发布微博');
        $this->display();
    }
    /**
 * 发微博
 */
    public function doSend()
    {
        // dump(is_login());exit;
        $aContent = I('post.content', '', 'op_t');
        $aType = I('post.type', 'image', 'op_t');
        $aAttachIds = I('post.attach_ids', '', 'op_t');
        $crowdId = I('post.crowd', '', 'intval');


        if (!empty($crowdId)) {
            $this->_checkCrowdExists($crowdId);
            $this->_checkIsAttend($crowdId);
            $this->_checkIsAllowPost($crowdId);
        }
        
        $num=explode(',',$aAttachIds);
        if(count($num)>9){
            $this->error('图片数量超过限制！');
        }
        //权限判断
        if (!is_login()) {
            $this->error('请登陆后再进行操作');
        }


        if (!check_auth('Weibo/Index/doSend')) {
            $this->error('您无微博发布权限。');
        }
        if (empty($aContent)) {
            $this->error('发布内容不能为空。');
        }

        $return = check_action_limit('add_weibo', 'weibo', 0, is_login(), true);
        if ($return && !$return['state']) {
            $this->error($return['info']);
        }
        $feed_data = array();
        $feed_data['attach_ids'] = $aAttachIds;
        if($aType=='LocalVideo'){
        $aType = 'LocalVideo';
      
     }
        elseif (empty($aAttachIds)) {
            $aType = 'feed';
        }


        // 执行发布，写入数据库
        $weibo_id = send_mob_weibo($aContent, $aType, $feed_data, $from = '手机网页版', $crowdId);

        if ($weibo_id) {
            $return['status'] = '1';
        } else {
            $return['status'] = ' 0';
            $return['info'] = '发布失败！';
        }
        $this->ajaxReturn($return);
    }

    /**
     * 我的信任
     */
    public function myFocus()
    {
        $aPage = I('post.page', 1, 'op_t');
        $aCount = I('post.count', 10, 'op_t');
        $this->setTopTitle('我的信任');
        $follow_who_ids = D('Follow')->where(array('who_follow' => is_login()))->field('follow_who')->select();
        $follow_who_ids = array_column($follow_who_ids, 'follow_who');//简化数组操作。
        $follow_who_ids = array_merge($follow_who_ids, array(is_login()));//加上自己的微博
        $map['uid'] = array('in', $follow_who_ids);
        $weibo = D('Weibo')->where(array('status' => 1, $map))->page($aPage, $aCount)->order('create_time desc')->select();//我信任的人的微博
        $totalCount = D('Weibo')->where(array('status' => 1, $map))->count();
        if ($totalCount <= $aPage * $aCount) {
            $pid['count'] = 0;
        } else {
            $pid['count'] = 1;
        }

        $support['appname'] = 'Weibo';                              //查找是否点赞
        $support['table'] = 'weibo';
        $support['uid'] = is_login();
        $is_zan = D('Support')->where($support)->select();
        $is_zan = array_column($is_zan, 'row');

        foreach ($weibo as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar64', 'uid'), $v['uid']);
            $v['rand_title'] = mob_get_head_title($v['uid']);
            $v['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $v['id']))->count();
            if($v['type']==="repost"){
                $v['content']=parse_br_nb( $v['content']);
                $v['content']= A('Mob/WeiboType')->fetchRepost($v);
            }else if($v['type']==="xiami") {
                $v['content'] = parse_br_nb($v['content']);
                $v['content'] = Hook::exec('Addons\\Insert' . ucfirst($v['type']) . '\\Insert' . ucfirst($v['type']) . 'Addon', 'fetch'.'mobile'. ucfirst($v['type']), $v);
            }else if($v['type']=="redbag"){
                $v['content'] = parse_br_nb($v['content']);
                $v['content']= A('Mob/WeiboType')->fetchRedbag($v);
            }
            else if($v['type']=="LocalVideo"){
                $v['content'] = parse_br_nb($v['content']);
                $v['content']= A('Mob/WeiboType')->fetchlocalvideo($v);
            }
            else if($v['type']=="video"){
                $v['content'] = parse_br_nb($v['content']);
                $v['content'] = Hook::exec('Addons\\Insert' . ucfirst($v['type']) . '\\Insert' . ucfirst($v['type']) . 'Addon', 'fetch' . ucfirst($v['type']), $v);
            }else{
                $v['content'] = parse_weibo_mobile_content($v['content']);
            }
            if (empty($v['from'])) {
                $v['from'] = "网站端";
            }

            $v['data'] = unserialize($v['data']);              //字符串转换成数组,获取微博源ID
            if ($v['data']['sourceId']) {                        //判断是否是源微博
                $v['sourceId'] = $v['data']['sourceId'];
                $v['is_sourceId'] = '1';
            } else {
                $v['sourceId'] = $v['id'];
                $v['is_sourceId'] = '0';

            }
            $v['sourceId_user'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->find();           //源微博用户名
            $v['sourceId_user'] = $v['sourceId_user']['uid'];
            $v['sourceId_user'] = query_user(array('nickname', 'uid'), $v['sourceId_user']);

            $v['sourceId_content'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('content')->find();          //源微博内容
            if(!is_null($v['sourceId_content'])){
                $v['sourceId_content'] = parse_weibo_mobile_content($v['sourceId_content']['content']);                                          //把表情显示出来。
            }

            $v['sourceId_repost_count'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('repost_count')->find();    //源微博转发数

            $v['sourceId_from'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('from')->find();       //源微博来源
            if (empty($v['sourceId_from']['from'])) {
                $v['sourceId_from'] = "网站端";
            } else {
                $v['sourceId_from'] = "手机网页版";
            }

            $v['sourceId_img'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('data')->find();    //为了获取源微图片
            $v['sourceId_img'] = unserialize($v['sourceId_img']['data']);
            $v['sourceId_img'] = explode(',', $v['sourceId_img']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['sourceId_img'] as &$b) {
                $v['sourceId_img_path'][] = getThumbImageById($b, 100, 100);                      //获得缩略图
                //获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if (!is_bool(strpos($bi['path'], 'http://'))) {
                    $v['sourceId_img_big'][] = $bi['path'];
                } else {
                    $v['sourceId_img_big'][] = getRootUrl() . substr($bi['path'], 1);
                }
            }


            $v['cover_url'] = explode(',', $v['data']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['cover_url'] as &$a) {
                $v['img_path'][] = getThumbImageById($a, 100, 100);
                //获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if (!is_bool(strpos($bi['path'], 'http://'))) {
                    $v['sourceId_img_big'][] = $bi['path'];
                } else {
                    $v['sourceId_img_big'][] = getRootUrl() . substr($bi['path'], 1);
                }
            }

            if (in_array($v['id'], $is_zan)) {                         //判断是否已经点赞
                $v['is_support'] = '1';
            } else {
                $v['is_support'] = '0';
            }

            if (empty($v['data']['attach_ids'])) {            //判断是否是图片
                $v['is_img'] = '0';
            } else {
                $v['is_img'] = '1';
            }
            if (empty($v['sourceId_img']['0'])) {
                $v['sourceId_is_img'] = '0';
            } else {
                $v['sourceId_is_img'] = '1';
            }

        }

        $pid['is_myfocus'] = 1;

        $this->assign("weibo", $weibo);
        $this->assign("pid", $pid);
        $this->assign('navtitle','myfocus');
        $this->display(T('Application://Mob@Weibo/index'));

    }

    /**
     * 加载更多我的信任
     */

    public function addMoreMyFocus()
    {
        $aPage = I('post.page', 1, 'op_t');
        $aCount = I('post.count', 10, 'op_t');
        $follow_who_ids = D('Follow')->where(array('who_follow' => is_login()))->field('follow_who')->select();
        $follow_who_ids = array_column($follow_who_ids, 'follow_who');//简化数组操作。
        $follow_who_ids = array_merge($follow_who_ids, array(is_login()));//加上自己的微博
        $map['uid'] = array('in', $follow_who_ids);
        $weibo = D('Weibo')->where(array('status' => 1, $map))->page($aPage, $aCount)->order('create_time desc')->select();


        $support['appname'] = 'Weibo';                              //查找是否点赞
        $support['table'] = 'weibo';
        $support['uid'] = is_login();
        $is_zan = D('Support')->where($support)->select();
        $is_zan = array_column($is_zan, 'row');

        foreach ($weibo as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar64', 'uid'), $v['uid']);
            $v['rand_title'] = mob_get_head_title($v['uid']);
            $v['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $v['id']))->count();
            if($v['type']==="repost"){
                $v['content']=parse_br_nb( $v['content']);
                $v['content']= A('Mob/WeiboType')->fetchRepost($v);
            }else if($v['type']==="xiami") {
                $v['content'] = parse_br_nb($v['content']);
                $v['content'] = Hook::exec('Addons\\Insert' . ucfirst($v['type']) . '\\Insert' . ucfirst($v['type']) . 'Addon', 'fetch'.'mobile'. ucfirst($v['type']), $v);
            }else if($v['type']=="redbag"){
                $v['content'] = parse_br_nb($v['content']);
                $v['content']= A('Mob/WeiboType')->fetchRedbag($v);
            }
            else if($v['type']=="LocalVideo"){
                $v['content'] = parse_br_nb($v['content']);
                $v['content']= A('Mob/WeiboType')->fetchlocalvideo($v);
            }
            else if($v['type']=="video"){
                $v['content'] = parse_br_nb($v['content']);
                $v['content'] = Hook::exec('Addons\\Insert' . ucfirst($v['type']) . '\\Insert' . ucfirst($v['type']) . 'Addon', 'fetch' . ucfirst($v['type']), $v);
            }else{
                $v['content'] = parse_weibo_mobile_content($v['content']);
            }
                if (empty($v['from'])) {
                $v['from'] = "网站端";
            }

            $v['data'] = unserialize($v['data']);              //字符串转换成数组,获取微博源ID
            if ($v['data']['sourceId']) {                        //判断是否是源微博
                $v['sourceId'] = $v['data']['sourceId'];
                $v['is_sourceId'] = '1';
            } else {
                $v['sourceId'] = $v['id'];
                $v['is_sourceId'] = '0';

            }
            $v['sourceId_user'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->find();           //源微博用户名
            $v['sourceId_user'] = $v['sourceId_user']['uid'];
            $v['sourceId_user'] = query_user(array('nickname', 'uid'), $v['sourceId_user']);

            $v['sourceId_content'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('content')->find();          //源微博内容
            if(!is_null($v['sourceId_content'])){
                $v['sourceId_content'] = parse_weibo_mobile_content($v['sourceId_content']['content']);                                          //把表情显示出来。
            }

            $v['sourceId_repost_count'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('repost_count')->find();    //源微博转发数

            $v['sourceId_from'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('from')->find();       //源微博来源
            if (empty($v['sourceId_from']['from'])) {
                $v['sourceId_from'] = "网站端";
            } else {
                $v['sourceId_from'] = "手机网页版";
            }

            $v['sourceId_img'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('data')->find();    //为了获取源微图片
            $v['sourceId_img'] = unserialize($v['sourceId_img']['data']);
            $v['sourceId_img'] = explode(',', $v['sourceId_img']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['sourceId_img'] as &$b) {
                $v['sourceId_img_path'][] = getThumbImageById($b, 100, 100);                      //获得缩略图
//获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if (!is_bool(strpos($bi['path'], 'http://'))) {
                    $v['sourceId_img_big'][] = $bi['path'];
                } else {
                    $v['sourceId_img_big'][] = getRootUrl() . substr($bi['path'], 1);
                }
            }


            $v['cover_url'] = explode(',', $v['data']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['cover_url'] as &$a) {
                $v['img_path'][] = getThumbImageById($a, 100, 100);
                //获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if (!is_bool(strpos($bi['path'], 'http://'))) {
                    $v['sourceId_img_big'][] = $bi['path'];
                } else {
                    $v['sourceId_img_big'][] = getRootUrl() . substr($bi['path'], 1);
                }
            }

            if (in_array($v['id'], $is_zan)) {                         //判断是否已经点赞
                $v['is_support'] = '1';
            } else {
                $v['is_support'] = '0';
            }

            if (empty($v['data']['attach_ids'])) {            //判断是否是图片
                $v['is_img'] = '0';
            } else {
                $v['is_img'] = '1';
            }
            if (empty($v['sourceId_img']['0'])) {
                $v['sourceId_is_img'] = '0';
            } else {
                $v['sourceId_is_img'] = '1';
            }

        }


        if ($weibo) {
            $data['html'] = "";
            foreach ($weibo as $val) {
                $this->assign("vl", $val);
                $data['html'] .= $this->fetch("_weibolist");
                $data['status'] = 1;
            }
        } else {
            $data['stutus'] = 0;
        }
        $this->ajaxReturn($data);


    }

    /**
     * 我的微博
     */
    public function myWeibo()
    {
        $aPage = I('post.page', 1, 'op_t');
        $aCount = I('post.count', 10, 'op_t');
        $this->setTopTitle('我的微博');
        $map = is_login();
        $weibo = D('Weibo')->where(array('status' => 1, 'uid' => $map))->page($aPage, $aCount)->order('create_time desc')->select();
        $totalCount = D('Weibo')->where(array('status' => 1))->count();
        if ($totalCount <= $aPage * $aCount) {
            $pid['count'] = 0;
        } else {
            $pid['count'] = 1;
        }

        $support['appname'] = 'Weibo';                              //查找是否点赞
        $support['table'] = 'weibo';
        $support['uid'] = is_login();
        $is_zan = D('Support')->where($support)->select();
        $is_zan = array_column($is_zan, 'row');

        foreach ($weibo as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar64', 'uid'), $v['uid']);
            $v['rand_title'] = mob_get_head_title($v['uid']);
            $v['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $v['id']))->count();
            if($v['type']==="repost"){
                $v['content']=parse_br_nb( $v['content']);
                $v['content']= A('Mob/WeiboType')->fetchRepost($v);
            }else if($v['type']==="xiami") {
                $v['content'] = parse_br_nb($v['content']);
                $v['content'] = Hook::exec('Addons\\Insert' . ucfirst($v['type']) . '\\Insert' . ucfirst($v['type']) . 'Addon', 'fetch'.'mobile'. ucfirst($v['type']), $v);
            }else if($v['type']=="redbag"){
                $v['content'] = parse_br_nb($v['content']);
                $v['content']= A('Mob/WeiboType')->fetchRedbag($v);
            }
            else if($v['type']=="LocalVideo"){
                $v['content'] = parse_br_nb($v['content']);
                $v['content']= A('Mob/WeiboType')->fetchlocalvideo($v);
            }
            else if($v['type']=="video"){
                $v['content'] = parse_br_nb($v['content']);
                $v['content'] = Hook::exec('Addons\\Insert' . ucfirst($v['type']) . '\\Insert' . ucfirst($v['type']) . 'Addon', 'fetch' . ucfirst($v['type']), $v);
            }else{
                $v['content'] = parse_weibo_mobile_content($v['content']);
            }
            if (empty($v['from'])) {
                $v['from'] = "网站端";
            }

            $v['data'] = unserialize($v['data']);              //字符串转换成数组,获取微博源ID
            if ($v['data']['sourceId']) {                        //判断是否是源微博
                $v['sourceId'] = $v['data']['sourceId'];
                $v['is_sourceId'] = '1';
            } else {
                $v['sourceId'] = $v['id'];
                $v['is_sourceId'] = '0';

            }
            $v['sourceId_user'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->find();           //源微博用户名
            $v['sourceId_user'] = $v['sourceId_user']['uid'];
            $v['sourceId_user'] = query_user(array('nickname', 'uid'), $v['sourceId_user']);

            $v['sourceId_content'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('content')->find();          //源微博内容
            if(!is_null($v['sourceId_content'])){
                $v['sourceId_content'] = parse_weibo_mobile_content($v['sourceId_content']['content']);                                          //把表情显示出来。
            }

            $v['sourceId_repost_count'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('repost_count')->find();    //源微博转发数

            $v['sourceId_from'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('from')->find();       //源微博来源
            if (empty($v['sourceId_from']['from'])) {
                $v['sourceId_from'] = "网站端";
            } else {
                $v['sourceId_from'] = "手机网页版";
            }

            $v['sourceId_img'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('data')->find();    //为了获取源微图片
            $v['sourceId_img'] = unserialize($v['sourceId_img']['data']);
            $v['sourceId_img'] = explode(',', $v['sourceId_img']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['sourceId_img'] as &$b) {
                $v['sourceId_img_path'][] = getThumbImageById($b, 100, 100);                      //获得缩略图
                //获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if (!is_bool(strpos($bi['path'], 'http://'))) {
                    $v['sourceId_img_big'][] = $bi['path'];
                } else {
                    $v['sourceId_img_big'][] = getRootUrl() . substr($bi['path'], 1);
                }
            }


            $v['cover_url'] = explode(',', $v['data']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['cover_url'] as &$a) {
                $v['img_path'][] = getThumbImageById($a, 100, 100);
                //获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if (!is_bool(strpos($bi['path'], 'http://'))) {
                    $v['sourceId_img_big'][] = $bi['path'];
                } else {
                    $v['sourceId_img_big'][] = getRootUrl() . substr($bi['path'], 1);
                }
            }

            if (in_array($v['id'], $is_zan)) {                         //判断是否已经点赞
                $v['is_support'] = '1';
            } else {
                $v['is_support'] = '0';
            }

            if (empty($v['data']['attach_ids'])) {            //判断是否是图片
                $v['is_img'] = '0';
            } else {
                $v['is_img'] = '1';
            }
            if (empty($v['sourceId_img']['0'])) {
                $v['sourceId_is_img'] = '0';
            } else {
                $v['sourceId_is_img'] = '1';
            }

        }

        $pid['is_myweibo'] = 1;

        $this->assign("weibo", $weibo);
        $this->assign("pid", $pid);
        $this->assign('navtitle','myweibo');
        $this->display(T('Application://Mob@Weibo/index'));

    }

    /**
     * 加载更多我的微博
     */
    public function addMoreMyWeibo()
    {
        $aPage = I('post.page', 1, 'op_t');
        $aCount = I('post.count', 10, 'op_t');
        $map = is_login();
        $weibo = D('Weibo')->where(array('status' => 1, 'uid' => $map))->page($aPage, $aCount)->order('create_time desc')->select();


        $support['appname'] = 'Weibo';                              //查找是否点赞
        $support['table'] = 'weibo';
        $support['uid'] = is_login();
        $is_zan = D('Support')->where($support)->select();
        $is_zan = array_column($is_zan, 'row');

        foreach ($weibo as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar64', 'uid'), $v['uid']);
            $v['rand_title'] = mob_get_head_title($v['uid']);
            $v['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $v['id']))->count();
            if($v['type']==="repost"){
                $v['content']=parse_br_nb( $v['content']);
                $v['content']= A('Mob/WeiboType')->fetchRepost($v);
            }else if($v['type']==="xiami") {
                $v['content'] = parse_br_nb($v['content']);
                $v['content'] = Hook::exec('Addons\\Insert' . ucfirst($v['type']) . '\\Insert' . ucfirst($v['type']) . 'Addon', 'fetch'.'mobile'. ucfirst($v['type']), $v);
            }else if($v['type']=="redbag"){
                $v['content'] = parse_br_nb($v['content']);
                $v['content']= A('Mob/WeiboType')->fetchRedbag($v);
            }
            else if($v['type']=="LocalVideo"){
                $v['content'] = parse_br_nb($v['content']);
                $v['content']= A('Mob/WeiboType')->fetchlocalvideo($v);
            }
            else if($v['type']=="video"){
                $v['content'] = parse_br_nb($v['content']);
                $v['content'] = Hook::exec('Addons\\Insert' . ucfirst($v['type']) . '\\Insert' . ucfirst($v['type']) . 'Addon', 'fetch' . ucfirst($v['type']), $v);
            }else{
                $v['content'] = parse_weibo_mobile_content($v['content']);
            }
            if (empty($v['from'])) {
                $v['from'] = "网站端";
            }

            $v['data'] = unserialize($v['data']);              //字符串转换成数组,获取微博源ID
            if ($v['data']['sourceId']) {                        //判断是否是源微博
                $v['sourceId'] = $v['data']['sourceId'];
                $v['is_sourceId'] = '1';
            } else {
                $v['sourceId'] = $v['id'];
                $v['is_sourceId'] = '0';

            }
            $v['sourceId_user'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->find();           //源微博用户名
            $v['sourceId_user'] = $v['sourceId_user']['uid'];
            $v['sourceId_user'] = query_user(array('nickname', 'uid'), $v['sourceId_user']);

            $v['sourceId_content'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('content')->find();          //源微博内容
            if(!is_null($v['sourceId_content'])){
                $v['sourceId_content'] = parse_weibo_mobile_content($v['sourceId_content']['content']);                                          //把表情显示出来。
            }

            $v['sourceId_repost_count'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('repost_count')->find();    //源微博转发数

            $v['sourceId_from'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('from')->find();       //源微博来源
            if (empty($v['sourceId_from']['from'])) {
                $v['sourceId_from'] = "网站端";
            } else {
                $v['sourceId_from'] = "手机网页版";
            }

            $v['sourceId_img'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('data')->find();    //为了获取源微图片
            $v['sourceId_img'] = unserialize($v['sourceId_img']['data']);
            $v['sourceId_img'] = explode(',', $v['sourceId_img']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['sourceId_img'] as &$b) {
                $v['sourceId_img_path'][] = getThumbImageById($b, 100, 100);                      //获得缩略图
                //获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if (!is_bool(strpos($bi['path'], 'http://'))) {
                    $v['sourceId_img_big'][] = $bi['path'];
                } else {
                    $v['sourceId_img_big'][] = getRootUrl() . substr($bi['path'], 1);
                }
            }


            $v['cover_url'] = explode(',', $v['data']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['cover_url'] as &$a) {
                $v['img_path'][] = getThumbImageById($a, 100, 100);
                //获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if (!is_bool(strpos($bi['path'], 'http://'))) {
                    $v['sourceId_img_big'][] = $bi['path'];
                } else {
                    $v['sourceId_img_big'][] = getRootUrl() . substr($bi['path'], 1);
                }
            }

            if (in_array($v['id'], $is_zan)) {                         //判断是否已经点赞
                $v['is_support'] = '1';
            } else {
                $v['is_support'] = '0';
            }

            if (empty($v['data']['attach_ids'])) {            //判断是否是图片
                $v['is_img'] = '0';
            } else {
                $v['is_img'] = '1';
            }
            if (empty($v['sourceId_img']['0'])) {
                $v['sourceId_is_img'] = '0';
            } else {
                $v['sourceId_is_img'] = '1';
            }

        }

        if ($weibo) {
            $data['html'] = "";
            foreach ($weibo as $val) {
                $this->assign("vl", $val);
                $data['html'] .= $this->fetch("_weibolist");
                $data['status'] = 1;
            }
        } else {
            $data['stutus'] = 0;
        }
        $this->ajaxReturn($data);

    }

    /**
     * @param $id
     * @param $uid
     * 点赞
     */
    public function support($id, $uid)
    {
        //$id是发帖人的微博ID
        //$uid是发帖人的ID
        if (!is_login()) {
            $this->error('请登陆后再进行操作');
        }
        $row = $id;
        $message_uid = $uid;
        $support['appname'] = 'Weibo';
        $support['table'] = 'weibo';
        $support['row'] = $row;
        $support['uid'] = is_login();

        if (D('Support')->where($support)->count()) {
            $return['status'] = '0';
            $return['info'] = '亲，您已经支持过我了！';
        } else {
            $support['create_time'] = time();
            if (D('Support')->where($support)->add($support)) {
                D('Weibo/WeiboCache')->cleanCache($row);
                $this->clearCache($support);

                $user = query_user(array('username', 'uid'));

                D('Common/Message')->sendMessage($message_uid, $user['username'] . '给您点了个赞。', $title = $user['username'] . '赞了您。', 'Weibo/Index/weiboDetail',array('id' => $id), is_login(), 1);
                $return['status'] = '1';
            } else {
                $return['status'] = ' 0';
                $return['info'] = '亲，您已经支持过我了！';
            }


        }
        $this->ajaxReturn($return);
    }


    private function clearCache($support)
    {
        unset($support['uid']);
        unset($support['create_time']);
        $cache_key = "support_count_" . implode('_', $support);
        //S($cache_key, null);
    }

    /**
     * @param $id
     * @param $uid
     * 转发内容获取展示
     */

    public function forward($id, $uid)
    {

        //$id是发帖人的微博ID
        //$uid是发帖人的ID

        $map['id'] = array('eq', $id);
        $weibo = D('Weibo')->where(array('status' => 1, $map))->order('create_time desc')->select();
        // dump($weibo);


        foreach ($weibo as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar64', 'uid'), $v['uid']);

            $v['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $v['id']))->count();

            $v['data'] = unserialize($v['data']);              //字符串转换成数组
            if ($v['data']['sourceId']) {
                $v['sourceId'] = $v['data']['sourceId'];
                $v['is_sourceId'] = '1';
            } else {
                $v['sourceId'] = $v['id'];
                $v['is_sourceId'] = '0';
            }
            $v['sourceId_user'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->find();           //源微博用户名
            $v['sourceId_user'] = $v['sourceId_user']['uid'];
            $v['sourceId_user'] = query_user(array('nickname', 'uid'), $v['sourceId_user']);
            $v['sourceId_content'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('content')->find();
        }

        $this->assign('weibo', $weibo[0]);
        $this->display(T('Application://Mob@Weibo/forward'));

    }

    /**
     * 转发功能实现
     */
    public function  doForward()
    {
        if (!is_login()) {
            $this->error('请您先登录', U('Mob/member/index'), 1);
        }

        $aContent = I('post.content', '', 'op_t');              //说点什么的内容
        $aType = I('post.type', '', 'op_t');                    //类型
        $aSoueseId = I('post.sourceId', 0, 'intval');           //获取该微博源ID
        $aWeiboId = I('post.weiboId', 0, 'intval');             //要转发的微博的ID
        $aBeComment = I('post.release', 'false', 'op_t');       //是否作为评论发布

        if (empty($aContent)) {
            $this->error('转发内容不能为空');
        }

        $this->checkAuth('Weibo/Index/doSendRepost', -1, '您无微博转发权限。');

        $return = check_action_limit('add_weibo', 'weibo', 0, is_login(), true);
        if ($return && !$return['state']) {
            $this->error($return['info']);
        }

        $weiboModel = D('Weibo');
        $feed_data = '';
        $source = $weiboModel->getWeiboDetail($aSoueseId);

        $sourceweibo = $source['weibo'];
        $feed_data['source'] = $sourceweibo;
        $feed_data['sourceId'] = $aSoueseId;

        $new_id = send_mob_weibo($aContent, $aType, $feed_data, $from = '手机网页版');        //发布微博


        if ($new_id) {
            D('weibo')->where('id=' . $aSoueseId)->setInc('repost_count');
            $aWeiboId != $aSoueseId && D('weibo')->where('id=' . $aWeiboId)->setInc('repost_count');
          //  S('weibo_' . $aWeiboId, null);
          //  S('weibo_' . $aSoueseId, null);
            D('Weibo/WeiboCache')->cleanCache($aWeiboId);
            D('Weibo/WeiboCache')->cleanCache($aSoueseId);
        }
// 发送消息
        $user = query_user(array('nickname', 'uid'), is_login());
        $toUid = D('weibo')->where(array('id' => $aWeiboId))->getField('uid');
        D('Common/Message')->sendMessage($toUid, '转发提醒' ,  $user['nickname'] . '转发了您的微博！',  'Weibo/Index/weiboDetail',array('id' => $new_id), is_login(), 1);
        // 发布评论

        if ($aBeComment == 'on') {
            send_comment($aWeiboId, $aContent);
        }


        //转发后的微博内容获取
        $weibo = D('Weibo')->where(array('status' => 1, 'id' => $new_id))->order('create_time desc')->select();
        $support['appname'] = 'Weibo';                              //查找是否点赞
        $support['table'] = 'weibo';
        $support['uid'] = is_login();
        $is_zan = D('Support')->where($support)->select();
        $is_zan = array_column($is_zan, 'row');

        foreach ($weibo as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar64', 'uid'), $v['uid']);
            $v['rand_title'] = mob_get_head_title($v['uid']);
            $v['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $v['id']))->count();
            if($v['type']==="repost"){
                $v['content']=parse_br_nb( $v['content']);
                $v['content']= A('Mob/WeiboType')->fetchRepost($v);
            }else if($v['type']==="xiami") {
                $v['content'] = parse_br_nb($v['content']);
                $v['content'] = Hook::exec('Addons\\Insert' . ucfirst($v['type']) . '\\Insert' . ucfirst($v['type']) . 'Addon', 'fetch'.'mobile'. ucfirst($v['type']), $v);
            }else if($v['type']=="video"){
                $v['content'] = parse_br_nb($v['content']);
                $v['content'] = Hook::exec('Addons\\Insert' . ucfirst($v['type']) . '\\Insert' . ucfirst($v['type']) . 'Addon', 'fetch' . ucfirst($v['type']), $v);
            }
            else{
                $v['content'] = parse_weibo_mobile_content($v['content']);
            }

            $v['data'] = unserialize($v['data']);              //字符串转换成数组,获取微博源ID
            if ($v['data']['sourceId']) {                        //判断是否是源微博
                $v['sourceId'] = $v['data']['sourceId'];
                $v['is_sourceId'] = '1';
            } else {
                $v['sourceId'] = $v['id'];
                $v['is_sourceId'] = '0';
            }
            $v['sourceId_user'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->find();           //源微博用户名
            $v['sourceId_user'] = $v['sourceId_user']['uid'];
            $v['sourceId_user'] = query_user(array('nickname', 'uid'), $v['sourceId_user']);

            $v['sourceId_content'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('content')->find();          //源微博内容
            if(!is_null($v['sourceId_content'])){
                $v['sourceId_content'] = parse_weibo_mobile_content($v['sourceId_content']['content']);                                          //把表情显示出来。
            }

            $v['sourceId_repost_count'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('repost_count')->find();    //源微博转发数
            $v['sourceId_from'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('from')->find();       //源微博来源

            if (empty($v['sourceId_from']['from'])) {
                $v['sourceId_from'] = "网站端";
            } else {
                $v['sourceId_from'] = "手机网页版";
            }

            $v['sourceId_img'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('data')->find();    //为了获取源微图片
            $v['sourceId_img'] = unserialize($v['sourceId_img']['data']);
            $v['sourceId_img'] = explode(',', $v['sourceId_img']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['sourceId_img'] as &$b) {
                $v['sourceId_img_path'][] = getThumbImageById($b, 100, 100);                      //获得缩略图
//获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if (!is_bool(strpos($bi['path'], 'http://'))) {
                    $v['sourceId_img_big'][] = $bi['path'];
                } else {
                    $v['sourceId_img_big'][] = getRootUrl() . substr($bi['path'], 1);
                }
            }

            $v['cover_url'] = explode(',', $v['data']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['cover_url'] as &$a) {
                $v['img_path'][] = getThumbImageById($a, 100, 100);
                //获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if (!is_bool(strpos($bi['path'], 'http://'))) {
                    $v['sourceId_img_big'][] = $bi['path'];
                } else {
                    $v['sourceId_img_big'][] = getRootUrl() . substr($bi['path'], 1);
                }
            }

            if (in_array($v['id'], $is_zan)) {                         //判断是否已经点赞
                $v['is_support'] = '1';
            } else {
                $v['is_support'] = '0';
            }

            if (empty($v['data']['attach_ids'])) {            //判断是否是图片
                $v['is_img'] = '0';
            } else {
                $v['is_img'] = '1';
            }
            if (empty($v['sourceId_img']['0'])) {
                $v['sourceId_is_img'] = '0';
            } else {
                $v['sourceId_is_img'] = '1';
            }

        }

        if ($weibo) {
            $data['html'] = "";
            foreach ($weibo as $val) {
                $this->assign("vl", $val);
                $data['html'] .= $this->fetch("_weibolist");
                $data['status'] = 1;
            }
        } else {
            $data['stutus'] = 0;
            $data['info'] = '转发失败！';
        }
        $this->ajaxReturn($data);

    }

    /**
     * @param $id
     * @param $user
     * 增加评论时显示的信息
     */
    public function addComment($id, $user)
    {
        //$id是发帖人的微博ID
        //$uid是发帖人的ID

        $map['id'] = array('eq', $id);
        $weibo = D('Weibo')->where(array('status' => 1, $map))->order('create_time desc')->select();


        foreach ($weibo as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar64', 'uid'), $v['uid']);

            $v['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $v['id']))->count();

            $v['data'] = unserialize($v['data']);              //字符串转换成数组
            if ($v['data']['sourceId']) {
                $v['sourceId'] = $v['data']['sourceId'];
                $v['is_sourceId'] = '1';
            } else {
                $v['sourceId'] = $v['id'];
                $v['is_sourceId'] = '0';
            }
            $v['sourceId_user'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->find();           //源微博用户名
            $v['sourceId_user'] = $v['sourceId_user']['uid'];
            $v['sourceId_user'] = query_user(array('nickname', 'uid'), $v['sourceId_user']);
            $v['sourceId_content'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('content')->find();


            $v['at_user_id'] = $user;

        }
//dump($weibo);exit;
        $this->assign('weibo', $weibo[0]);
        $this->display(T('Application://Mob@Weibo/addcomment'));

    }

    /**
     * 增加评论实现
     */
    public function doAddComment()
    {
        if (!is_login()) {
            $this->error('请您先登录', U('Mob/member/index'), 1);
        }

        $aContent = I('post.weibocontent', '', 'op_t');              //说点什么的内容
        $aWeiboId = I('post.weiboId', 0, 'intval');             //要评论的微博的ID
        $aCommentId = I('post.comment_id', 0, 'intval');

        if (empty($aContent)) {
            $this->error('评论内容不能为空。');
        }

        $this->checkAuth('Weibo/Index/doComment', -1, '您无微博评论权限。');
        $return = check_action_limit('add_weibo_comment', 'weibo_comment', 0, is_login(), true);//行为限制
        if ($return && !$return['state']) {
            $this->error($return['info']);
        }
        $new_id = send_comment($aWeiboId, $aContent, $aCommentId);        //发布评论


        $weibocomment = D('WeiboComment')->where(array('status' => 1, 'id' => $new_id))->order('create_time desc')->select();

        foreach ($weibocomment as &$k) {
            $k['user'] = query_user(array('nickname', 'avatar32', 'uid'), $k['uid']);
            $k['rand_title'] = mob_get_head_title($k['uid']);
            $k['content'] = parse_weibo_mobile_content($k['content']);
        }

        if ($weibocomment) {
            $data['html'] = "";
            foreach ($weibocomment as $val) {
                $this->assign("vl", $val);
                $data['html'] .= $this->fetch("_weibocomment");

                $data['status'] = 1;
            }
        } else {
            $data['stutus'] = 0;
        }
        $this->ajaxReturn($data);
    }


    public function delComment()
    {
        $comment_id = I('post.commentId', 0, 'intval');              //接收评论ID
        $weibo_id = I('post.weiboId', 0, 'intval');                   //接收微博ID

        $weibo_uid = D('Weibo')->where(array('status' => 1, 'id' => $weibo_id))->find();//根据微博ID查找微博发送人的UID
        $comment_uid = D('WeiboComment')->where(array('status' => 1, 'id' => $comment_id))->find();//根据评论ID查找评论发送人的UID

        if (!is_login()) {
            $this->error('请登陆后再进行操作');
        }


        if (is_administrator(get_uid()) || $weibo_uid['uid'] == get_uid() || $comment_uid['uid'] == get_uid()) {                                     //如果是管理员，则可以删除评论
            $result = D('WeiboComment')->deleteComment($comment_id);
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
     * 热门微博
     */
    public function hotWeibo()
    {
        $aPage = I('post.page', 1, 'op_t');
        $aCount = I('post.count', 10, 'op_t');
        $this->setTopTitle('热门微博');
        $hot_left = modC('HOT_LEFT', 3);
        $time_left = get_some_day($hot_left);
        $param['create_time'] = array('gt', $time_left);
        $param['status'] = 1;
        $param['is_top'] = 0;
        $weibo = D('Weibo')->where(array('status' => 1, $param))->page($aPage, $aCount)->order('comment_count desc')->select();
        $totalCount = D('Weibo')->where(array('status' => 1, $param))->count();
        if ($totalCount <= $aPage * $aCount) {
            $pid['count'] = 0;
        } else {
            $pid['count'] = 1;
        }

        $support['appname'] = 'Weibo';                              //查找是否点赞
        $support['table'] = 'weibo';
        $support['uid'] = is_login();
        $is_zan = D('Support')->where($support)->select();
        $is_zan = array_column($is_zan, 'row');

        foreach ($weibo as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar64', 'uid'), $v['uid']);
            $v['rand_title'] = mob_get_head_title($v['uid']);
            $v['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $v['id']))->count();
            if($v['type']==="repost"){
                $v['content']=parse_br_nb( $v['content']);
                $v['content']= A('Mob/WeiboType')->fetchRepost($v);
            }else if($v['type']==="xiami") {
                $v['content'] = parse_br_nb($v['content']);
                $v['content'] = Hook::exec('Addons\\Insert' . ucfirst($v['type']) . '\\Insert' . ucfirst($v['type']) . 'Addon', 'fetch'.'mobile'. ucfirst($v['type']), $v);
            }else if($v['type']=="video"){
                $v['content'] = parse_br_nb($v['content']);
                $v['content'] = Hook::exec('Addons\\Insert' . ucfirst($v['type']) . '\\Insert' . ucfirst($v['type']) . 'Addon', 'fetch' . ucfirst($v['type']), $v);
            }else{
                $v['content'] = parse_weibo_mobile_content($v['content']);
            }
            if (empty($v['from'])) {
                $v['from'] = "网站端";
            }

            $v['data'] = unserialize($v['data']);              //字符串转换成数组,获取微博源ID
            if ($v['data']['sourceId']) {                        //判断是否是源微博
                $v['sourceId'] = $v['data']['sourceId'];
                $v['is_sourceId'] = '1';
            } else {
                $v['sourceId'] = $v['id'];
                $v['is_sourceId'] = '0';

            }
            $v['sourceId_user'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->find();           //源微博用户名
            $v['sourceId_user'] = $v['sourceId_user']['uid'];
            $v['sourceId_user'] = query_user(array('nickname', 'uid'), $v['sourceId_user']);

            $v['sourceId_content'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('content')->find();          //源微博内容
            if(!is_null($v['sourceId_content'])){
                $v['sourceId_content'] = parse_weibo_mobile_content($v['sourceId_content']['content']);                                          //把表情显示出来。
            }

            $v['sourceId_repost_count'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('repost_count')->find();    //源微博转发数

            $v['sourceId_from'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('from')->find();       //源微博来源
            if (empty($v['sourceId_from']['from'])) {
                $v['sourceId_from'] = "网站端";
            } else {
                $v['sourceId_from'] = "手机网页版";
            }

            $v['sourceId_img'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('data')->find();    //为了获取源微图片
            $v['sourceId_img'] = unserialize($v['sourceId_img']['data']);
            $v['sourceId_img'] = explode(',', $v['sourceId_img']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['sourceId_img'] as &$b) {
                $v['sourceId_img_path'][] = getThumbImageById($b, 100, 100);                      //获得缩略图
                //获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if (!is_bool(strpos($bi['path'], 'http://'))) {
                    $v['sourceId_img_big'][] = $bi['path'];
                } else {
                    $v['sourceId_img_big'][] = getRootUrl() . substr($bi['path'], 1);
                }
            }


            $v['cover_url'] = explode(',', $v['data']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['cover_url'] as &$a) {
                $v['img_path'][] = getThumbImageById($a, 100, 100);
                //获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if (!is_bool(strpos($bi['path'], 'http://'))) {
                    $v['sourceId_img_big'][] = $bi['path'];
                } else {
                    $v['sourceId_img_big'][] = getRootUrl() . substr($bi['path'], 1);
                }
            }

            if (in_array($v['id'], $is_zan)) {                         //判断是否已经点赞
                $v['is_support'] = '1';
            } else {
                $v['is_support'] = '0';
            }

            if (empty($v['data']['attach_ids'])) {            //判断是否是图片
                $v['is_img'] = '0';
            } else {
                $v['is_img'] = '1';
            }
            if (empty($v['sourceId_img']['0'])) {
                $v['sourceId_is_img'] = '0';
            } else {
                $v['sourceId_is_img'] = '1';
            }

        }

        $pid['is_hotweibo'] = 1;

        $this->assign("weibo", $weibo);
        $this->assign("pid", $pid);
        $this->assign('navtitle','hotweibo');
        $this->display(T('Application://Mob@Weibo/index'));

    }

    /**
     * 加载更多热门微博
     */
    public function addMoreHotWeibo()
    {
        $aPage = I('post.page',1, 'op_t');
        $aCount = I('post.count', 10, 'op_t');
        $hot_left = modC('HOT_LEFT', 3);
        $time_left = get_some_day($hot_left);
        $time_left = get_some_day($hot_left);
        $param['create_time'] = array('gt', $time_left);
        $param['status'] = 1;
        $param['is_top'] = 0;
        $weibo = D('Weibo')->where(array('status' => 1, $param))->page($aPage, $aCount)->order('comment_count desc')->select();


        $support['appname'] = 'Weibo';                              //查找是否点赞
        $support['table'] = 'weibo';
        $support['uid'] = is_login();
        $is_zan = D('Support')->where($support)->select();
        $is_zan = array_column($is_zan, 'row');

        foreach ($weibo as &$v) {
            $v['user'] = query_user(array('nickname', 'avatar64', 'uid'), $v['uid']);
            $v['rand_title'] = mob_get_head_title($v['uid']);
            $v['support'] = D('Support')->where(array('appname' => 'Weibo', 'table' => 'weibo', 'row' => $v['id']))->count();
            if($v['type']==="repost"){
                $v['content']=parse_br_nb( $v['content']);
                $v['content']= A('Mob/WeiboType')->fetchRepost($v);
            }else if($v['type']==="xiami") {
                $v['content'] = parse_br_nb($v['content']);
                $v['content'] = Hook::exec('Addons\\Insert' . ucfirst($v['type']) . '\\Insert' . ucfirst($v['type']) . 'Addon', 'fetch'.'mobile'. ucfirst($v['type']), $v);
            }else if($v['type']=="video"){
                $v['content'] = parse_br_nb($v['content']);
                $v['content'] = Hook::exec('Addons\\Insert' . ucfirst($v['type']) . '\\Insert' . ucfirst($v['type']) . 'Addon', 'fetch' . ucfirst($v['type']), $v);
            }else{
                $v['content'] = parse_weibo_mobile_content($v['content']);
            }
            if (empty($v['from'])) {
                $v['from'] = "网站端";
            }

            $v['data'] = unserialize($v['data']);              //字符串转换成数组,获取微博源ID
            if ($v['data']['sourceId']) {                        //判断是否是源微博
                $v['sourceId'] = $v['data']['sourceId'];
                $v['is_sourceId'] = '1';
            } else {
                $v['sourceId'] = $v['id'];
                $v['is_sourceId'] = '0';

            }
            $v['sourceId_user'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->find();           //源微博用户名
            $v['sourceId_user'] = $v['sourceId_user']['uid'];
            $v['sourceId_user'] = query_user(array('nickname', 'uid'), $v['sourceId_user']);

            $v['sourceId_content'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('content')->find();          //源微博内容
            if(!is_null($v['sourceId_content'])){
                $v['sourceId_content'] = parse_weibo_mobile_content($v['sourceId_content']['content']);                                          //把表情显示出来。
            }

            $v['sourceId_repost_count'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('repost_count')->find();    //源微博转发数

            $v['sourceId_from'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('from')->find();       //源微博来源
            if (empty($v['sourceId_from']['from'])) {
                $v['sourceId_from'] = "网站端";
            } else {
                $v['sourceId_from'] = "手机网页版";
            }

            $v['sourceId_img'] = D('Weibo')->where(array('status' => 1, 'id' => $v['sourceId']))->field('data')->find();    //为了获取源微图片
            $v['sourceId_img'] = unserialize($v['sourceId_img']['data']);
            $v['sourceId_img'] = explode(',', $v['sourceId_img']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['sourceId_img'] as &$b) {
                $v['sourceId_img_path'][] = getThumbImageById($b, 100, 100);                      //获得缩略图
//获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if (!is_bool(strpos($bi['path'], 'http://'))) {
                    $v['sourceId_img_big'][] = $bi['path'];
                } else {
                    $v['sourceId_img_big'][] = getRootUrl() . substr($bi['path'], 1);
                }
            }


            $v['cover_url'] = explode(',', $v['data']['attach_ids']);        //把attach_ids里的图片ID转出来
            foreach ($v['cover_url'] as &$a) {
                $v['img_path'][] = getThumbImageById($a, 100, 100);
                //获得原图
                $bi = M('Picture')->where(array('status' => 1))->getById($b);
                if (!is_bool(strpos($bi['path'], 'http://'))) {
                    $v['sourceId_img_big'][] = $bi['path'];
                } else {
                    $v['sourceId_img_big'][] = getRootUrl() . substr($bi['path'], 1);
                }
            }

            if (in_array($v['id'], $is_zan)) {                         //判断是否已经点赞
                $v['is_support'] = '1';
            } else {
                $v['is_support'] = '0';
            }

            if (empty($v['data']['attach_ids'])) {            //判断是否是图片
                $v['is_img'] = '0';
            } else {
                $v['is_img'] = '1';
            }
            if (empty($v['sourceId_img']['0'])) {
                $v['sourceId_is_img'] = '0';
            } else {
                $v['sourceId_is_img'] = '1';
            }

        }

        if ($weibo) {
            $data['html'] = "";
            foreach ($weibo as $val) {
                $this->assign("vl", $val);
                $data['html'] .= $this->fetch("_weibolist");
                $data['status'] = 1;
            }
        } else {
            $data['stutus'] = 0;
        }
        $this->ajaxReturn($data);


    }

    //红包插件内容
    public function openRedBag($param)
    {


        parse_str($_GET['param'],$param);


        $this->assign('weiboId',$param['weibo_id']);

        $this->assign('redbagConetnt',$param);
        $isGet=M('RedbagList')->where(array('redbagId'=>$param['id'],'uid'=>is_login()))->find();
        if($isGet){
            $this->assign('getRedBag',$isGet['get_bag']);
        }
        //判断是否已经领完了
        $countlist=M('RedbagList')->where(array('redbagId'=>$param['id']))->count();
        $redbag=M('Redbag')->where(array('id'=>$param['id']))->find();

        $this->assign('redbag',$redbag);
        $this->assign('redbagcount',$countlist);
        //获得单位
        $field = D('Ucenter/Score')->getTypeList(array('status' => 1));
        foreach ($field as &$v) {
            if ($redbag['type'] == $v['id']) {
                $this->assign('type_title',$v['title']);
                $this->assign('unit',$v['unit']);
            }
        }
        if($redbag['status']==2){
            $this->assign('noRedbag',0);//红包已抢完
        }else{
            $this->assign('noRedbag',1);//红包未抢完
        }
        $getList=M('RedbagList')->where(array('redbagId'=>$param['id']))->order('create_time desc')->select();
        $getListType=M('Redbag')->where(array('id'=>$getList[0]['redbagId']))->field('type')->find();
        foreach($getList as &$v){
            $v['user']=query_user(array('uid', 'nickname', 'avatar64', 'space_url', 'rank_link', 'title'), $v['uid']);
            foreach ($field as &$k) {
                if($getListType['type']==$k['id']){
                    $v['type_title']=$k['title'];
                    $v['unit']=$k['unit'];
                }
            }
        }
        $this->assign('getList',$getList);
        $this->display(T('Application://Mob@Weibo/openredbag'));
    }

    public function addRedBag(){
        $field = D('Ucenter/Score')->getTypeList(array('status' => 1));
        $user=M('Member')->where(array('uid'=>is_login()))->find();

        foreach($field as &$v){
            $v['score']=$user['score'.$v['id']];
        };
        $this->assign("field", $field);

        $this->display();
    }


    public function sendRedBag()
    {
        $aContent = I('post.content', '', 'op_t');
        $aType = I('post.type', '', 'op_t');
        $aNum = I('post.num', '', 'op_t');
        $aAllmoney = I('post.allmoney', 0, 'op_t');
        $aOnemoney = I('post.onemoney', 0, 'op_t');
        $aRed_bag_type = I('post.red_bag_type', '', 'op_t');
        $aKouLing = I('post.kouling', '', 'op_t');


        if($aAllmoney<0){
            $this->error('输入内容不能为负数');
        }
        if($aOnemoney<0){
            $this->error('输入内容不能为负数');
        }
        if($aNum<0){
            $this->error('红包个数不能为负');
        }
        if($aNum>100){
            $this->error('红包个数不能打大于100');
        }
        if(empty($aNum)){
            $this->error('请输入红包个数');
        }
        if($aAllmoney==0&&$aOnemoney==0){
            $this->error('请输入总额');
        }
        if(preg_match("/[\x7f-\xff]/", $aAllmoney)){
            $this->error('请输入数字');
        }
        if(preg_match("/[\x7f-\xff]/", $aOnemoney)){
            $this->error('请输入数字');
        }
        $data['uid'] = is_login();
        $data['num'] = $aNum;
        if ($aRed_bag_type == 1) {
            $data['all_money'] = $aOnemoney * $aNum;

            if($aNum>1){
                for ($i = 1; $i < $aNum; $i++) {
                    $total =  $data['all_money'] - $aOnemoney*$i;
                    $data['rank'][] =array($i,$aOnemoney,$total);
                }
            }

            $data['rank'][] = array(intval($aNum), $aOnemoney, $total);

        } else {
            $data['all_money'] = $aAllmoney;

            /*随机红包分配方法*/
            $total = $data['all_money'];//红包总额
            $num = $aNum;// 分成8个红包，支持8人随机领取
            $min = 0.01;//每个人最少能收到0.01元

            for ($i = 1; $i < $num; $i++) {
                $safe_total = ($total - ($num - $i) * $min) / ($num - $i);//随机安全上限
                $money = mt_rand($min * 100, $safe_total * 100) / 100;
                $total = $total - $money;
                $data['rank'][] = array($i, $money, $total);
                //   echo '第'.$i.'个红包：'.$money.' 元，余额：'.$total.' 元 <br/>';
            }
            $data['rank'][] = array(intval($num), $total, 0);
            //  echo '第'.$num.'个红包：'.$total.' 元，余额：0 元';
            /*随机红包分配方法END*/
            //$data['rank']=json_encode($data['rank'][]);
        }
        if($aRed_bag_type==3){
            if(empty($aKouLing)){
                $this->error('请输入红包口令！');
            }else{
                $data['content'] = $aKouLing;
            }

        }else{
            if (empty($aContent)) {
                $data['content'] = "恭喜发财，大吉大利！";
            } else {
                $data['content'] = $aContent;
            }
        }

        $data['rank']= json_encode($data['rank']);
        $data['type'] = $aType;
        $data['redbag_type'] = $aRed_bag_type;
        $data['create_time'] = time();
        $data['status'] = 1;
        $field = D('Ucenter/Score')->getTypeList(array('status' => 1));

        foreach ($field as &$v) {

            if ($aType == $v['id']) {
                $user = D('Member')->where(array('uid' => is_login()))->find();
                if (($user['score' . $aType] - $data['all_money']) < 0) {
                    $this->error('你的' . $v['title'] . '不足，红包发送失败。');
                }

                $rs=D('Ucenter/Score')->setUserScore(is_login(),$data['all_money'],$v['id'],'dec');

                //   $rs = D('Member')->where(array('uid' => is_login()))->setDec('score' . $v['id'], $data['all_money']);
                if ($rs) {
                    $res = M('Redbag')->add($data);
                    if($aRed_bag_type==3){
                        D('Message')->sendMessageWithoutCheckSelf(is_login(), '您发了个口令红包', $v['title'] . '减少了' . $data['all_money']);
                    }else{
                        D('Message')->sendMessageWithoutCheckSelf(is_login(), '您发了个' . $v['title'] . '红包', $v['title'] . '减少了' . $data['all_money']);
                    }

                    // action_log('send_redbag', 'redbag', $res, is_login());
                    if ($res) {
                        $redbag=M('Redbag')->where(array('id'=>$res))->find();
                        if($aRed_bag_type==3){
                            send_mob_weibo("我发布了一个口令红包【#" . $data['content'] . "】：",'redbag',$redbag,'手机网页版');
                        }else{

                            send_mob_weibo("我发布了一个".$v['title']."红包【" . $data['content'] . "】：",'redbag',$redbag,'手机网页版');
                        }

                        // D('Weibo/Weibo')->addWeibo(is_login(), "我发布了一个".$v['title']."红包【" . $data['content'] . "】：" ,'redbag',$redbag);
                        $this->success('红包发送成功！');
                    } else {
                        $this->error('红包发送失败！');
                    }

                }else{
                    $this->error('红包发送失败！');
                }
            }
        }
    }

    public function crowd($tab='all')
    {
        switch ($tab){
            case 'all':
                $crowdList = D('WeiboCrowd')->getAllCrowd();
                foreach ($crowdList as &$val){
                    $val['crowd'] = D('WeiboCrowd')->getCrowd($val['id']);
                    $val['crowd']['is_follow'] = D('Mob/WeiboCrowdMember')->getIsJoin(is_login(),$val['crowd']['id']);
                }
                unset($val);
                $this->assign('crowd_list', $crowdList);
                break;
            case 'create':
                //我创建的圈子
                $myCreateCrowd = D('WeiboCrowdMember')->getMyCreateCrowd(is_login());
                foreach ($myCreateCrowd as $key => &$v) {
                    $res = D('WeiboCrowd')->getCrowd($v['crowd_id']);
                    $v['crowd'] = $res;
                    $v['crowd']['is_follow'] = D('Mob/WeiboCrowdMember')->getIsJoin(is_login(),$v['crowd']['id']);
                    if (empty($res)) {
                        unset($myCreateCrowd[$key]);
                    }
                }
                unset($v);
                $this->assign('crowd_list', $myCreateCrowd);
                break;
            case 'join':
                //加入的圈子
                $followCrowd = D('WeiboCrowdMember')->getUserJoin(is_login());
                foreach ($followCrowd as $key => &$v) {
                    $res = D('WeiboCrowd')->getCrowd($v['crowd_id']);
                    $v['crowd'] = $res;
                    $v['crowd']['is_follow'] = D('Mob/WeiboCrowdMember')->getIsJoin(is_login(),$v['crowd']['id']);
                    if (empty($res)) {
                        unset($followCrowd[$key]);
                    }
                }
                unset($v);
                $this->assign('crowd_list', $followCrowd);
                break;
            default:
                break;
        }

        $field = D('Ucenter/Score')->getTypeList(array('status' => 1));
        $this->assign("field", $field);
        $this->display();
    }

    public function create()
    {
        $type = D('WeiboCrowdType')->getCrowdTypes();
        $this->assign('type', $type);
        if (IS_POST) {
            $this->_isLogin();
            $aId = I('post.crowd_id', 0, 'intval');
            $aTitle = I('post.title', '', 'text');
            $aType = I('post.type', 0, 'intval');
            $aAllowUserPost = I('post.allow_user_post', 0, 'intval');
            $aOderType = I('post.order_type',0,'intval');
            $aLogo = I('post.logo', 0, 'intval');
            $aTypeId = I('post.type_id', 0, 'intval');
            $aIntro = I('post.intro', '', 'text');
            $aNotice = I('post.notice', '', 'text');
            $aPayType = I('post.pay_type',0,'intval');
            $aInvisible = I('post.invisible',0,'intval');
            $needPay = 0;
            if (!empty($aPayType)) {
                $aPayNum = I('post.pay_num',0,'intval');
                if ($aPayNum <= 0) {
                    $this->error('付费不能小于0');
                }
                $needPay = $aPayNum;
            }
            if (empty($aLogo)) {
                $this->error('请上传圈子封面');
            }
            if (empty($aTitle)) {
                $this->error('请填写圈子标题');
            }
            if (utf8_strlen($aTitle) > 20) {
                $this->error('圈子名称最多20个字');
            }
            if ($aTypeId == -1) {
                $this->error('请选择圈子分类');
            }
            if (empty($aIntro)) {
                $this->error('请填写圈子介绍');
            }
            $status = 1;
            $isEdit = $aId ? true : false;
            $message = $isEdit ? '编辑成功' : '发布成功';
            if (modC('CREATE_CROWD_CHECK', '0') == 1) {
                $message .= ',等待管理员审核';
                $status = 2;
            }
            $aInvisible = $aType == 0 ? 0 : $aInvisible;
            $data = array('title' => $aTitle, 'create_time' => time(), 'status' => $status, 'allow_user_post' => $aAllowUserPost,'order_type' => $aOderType, 'logo' => $aLogo, 'type_id' => $aTypeId, 'intro' => $aIntro, 'notice' => $aNotice, 'type' => $aType,'pay_type'=>$aPayType,'invisible'=>$aInvisible);
            //写入数据库
            $model = M('WeiboCrowd');
            if ($isEdit) {
                if (!check_auth('Weibo/Manage/*', get_crowd_admin($aId))) {
                    $this->error('非圈子管理员无法修改');
                }
                $data['id'] = $aId;
                if ($data['type'] == 1) {
                    $data['need_pay'] = $needPay;
                } else {
                    $data['pay_type'] = 0;
                    $data['need_pay'] = 0;
                }
                $data = $model->create($data);
                $result = $model->where(array('id' => $aId))->save($data);
                if (!$result) {
                    $this->error(L('_ERROR_CREATE_FAIL_'));
                }
                S('crowd_by_' . $aId, null);
                $temp = D('Mob/WeiboCrowd')->where(array('id' => $aId))->find();
                if (modC('CREATE_CROWD_CHECK', '0') == 1) {
                    send_message_without_check_self(is_login(), '等待圈子修改审核', "您修改的圈子" . "【{$temp['title']}】正在审核中,请等待", 'Weibo/Crowd/index', '', is_login(), 'Weibo_crowd');
                    send_message(array_column(AuthGroupModel::memberInGroup(6),'uid'), '等待圈子审核', get_nickname(is_login()) . "创建了圈子【{$temp['title']}】，请审核", 'Admin/Weibo/Crowd', array('status' => 2), is_login(), 'Weibo_crowd');
                }
            } else {
                $data['need_pay'] = $needPay;
                $data['pay_type'] = $aPayType;
                $data = $model->create($data);
                $data['uid'] = is_login();
                $result = $model->add($data);
                if (!$result) {
                    $this->error(L('_ERROR_CREATE_FAIL_'));
                }
                //添加创建者成员
                D('Mob/WeiboCrowdMember')->addMember(array('uid' => is_login(), 'crowd_id' => $result, 'status' => 1, 'position' => 3));
                D('Mob/WeiboCrowd')->changeCrowdNum($result, 'member', 'inc');
                $temp = D('Mob/WeiboCrowd')->where(array('id' => $result))->find();
                if (modC('CREATE_CROWD_CHECK', '0') == 1) {
                    send_message_without_check_self(is_login(), '等待圈子审核', "您创建的圈子" . "【{$temp['title']}】正在审核中,请等待", 'Weibo/Crowd/index', '', is_login(), 'Weibo_crowd');
                    send_message(array_column(AuthGroupModel::memberInGroup(6),'uid'), '等待圈子审核', get_nickname(is_login()) . "创建了圈子【{$temp['title']}】，请审核", 'Admin/Weibo/Crowd', array('status' => 2), is_login(), 'Weibo_crowd');
                }
            }
            //显示成功消息
            S('crowd_create_by_' . is_login(), null);
            S('crowd_joined_' . is_login(), null);
            S('all_crowd_list', null);
            $url = $isEdit ? U('Weibo/Crowd/crowd',array('id'=>$aId)).'#change' : U('Weibo/Crowd/index');
            $this->success($message, $url);
        } else {
            $aId = I('get.crowd_id', 0, 'intval');
            $crowd = D('WeiboCrowd')->getCrowd($aId);
            $this->assign('crowd_list', $crowd);
        }

        $this->display(T('addcrowd'));
    }

    private function _isLogin()
    {
        if (!is_login()) {
            $this->error('未登录');
        }
    }

    public function attend()
    {
        if (IS_POST) {
            $crowdId = I('post.crowd_id', 0, 'intval');
            $uid = is_login();
            $num = count(D('WeiboCrowdMember')->getUserJoin($uid));
            if (empty($crowdId)) {
                $this->error('参数错误');
            }
            if (!$uid) {
                $this->error('未登录');
            }
            if (!crowd_exists($crowdId)) {
                $this->error('圈子不存在');
            }
            $isJoin = D('Mob/WeiboCrowdMember')->getIsJoin(is_login(),$crowdId);
            switch ($isJoin) {
                case 1:
                    $this->error('已加入该圈子');
                    break;
                case 2:
                    $this->error('正在审核中');
                    break;
            }
            if ($num >= modC('JOIN_CROWD_NUM', '5', 'Weibo')) {
                $this->error('超出加入圈子上限');
            }
            $crowd = D('WeiboCrowd')->getCrowd($crowdId);
            $data['crowd_id'] = $crowdId;
            $data['uid'] = $uid;

            if ($crowd['type'] == 1) {
                // 圈子为私有的。
                $data['status'] = 0;
            } else {
                // 圈子为公共的
                $data['status'] = 1;
            }
            if (empty($isJoin)) {
                $res = D('WeiboCrowdMember')->addMember($data);
            } else {
                $res = D('WeiboCrowdMember')->where(array('uid'=>is_login(),'crowd_id'=>$crowdId))->save(array('status'=>$data['status']));
            }
            if ($res) {
                if ($crowd['type'] == 1) {
                    send_message($crowd['uid'], '加入圈子审核', get_nickname($uid) . '请求加入圈子' . "【{$crowd['title']}】", 'Weibo/Crowd/crowd', array('id' => $crowdId, 'type' => 'check'), $uid, 'Weibo_crowd');
                    S('crowd_joined_' . $uid, null);
                    $this->ajaxReturn(array('status' => 2, 'info' => '加入圈子成功，等待管理员审核！'));
                }
                S('crowd_joined_' . $uid, null);
                D('Mob/WeiboCrowd')->changeCrowdNum($crowdId);
                send_message($crowd['uid'], '加入圈子提醒', get_nickname($uid) . '已加入圈子' . "【{$crowd['title']}】", 'Weibo/Crowd/crowd', array('id' => $crowdId, 'type' => 'member'), $uid, 'Weibo_crowd');
                $this->success('加入圈子成功');
            } else {
                $this->error('操作失败');
            }
        }
    }
    
    public function quit()
    {
        if (IS_POST) {
            $crowdId = I('post.crowd_id', 0, 'intval');
            $uid = is_login();
            if (empty($crowdId)) {
                $this->error('参数错误');
            }
            if (!$uid) {
                $this->error('未登录');
            }
            if (!crowd_exists($crowdId)) {
                $this->error('圈子不存在');
            }
            if (!D('Mob/WeiboCrowdMember')->getIsJoin(is_login(),$crowdId)) {
                $this->error('并未加入该圈子');
            }
            $list = D('WeiboCrowdMember')->getMycreateCrowd($uid);
            $ids = getSubByKey($list, 'crowd_id');
            if (in_array($crowdId, $ids)) {
                $this->error('圈子创始人只能解散圈子');
            }
            $data['crowd_id'] = $crowdId;
            $data['uid'] = $uid;
            $crowd = D('WeiboCrowd')->getCrowd($crowdId);
            $res = D('WeiboCrowdMember')->delMember(array('crowd_id' => $crowdId, 'uid' => $uid));
            if ($res) {
                if ($crowd['member_count'] > 0) {
                    D('Mob/WeiboCrowd')->changeCrowdNum($crowdId, 'member', 'dec');
                }
                S('crowd_joined_' . $uid, null);
                send_message($crowd['uid'], '退出圈子提醒', get_nickname($uid) . '已退出圈子' . "【{$crowd['title']}】", 'Ucenter/Index/information', array('uid' => $uid), $uid, 'Weibo_crowd');
                $this->success('退出圈子成功');
            } else {
                $this->error('操作失败');
            }
        }
    }
    
    public function crowdManager()
    {
        $aId = I('get.id', 0, 'intval');
        $aType = I('get.type', 'member', 'text');
        $aPage = I('get.page', 1, 'intval');
        $crowd = D('WeiboCrowd')->getCrowd($aId);
        if (!$crowd) {
            $this->error('圈子不存在');
        }
        $isAdmin = check_auth('Weibo/Manage/*',get_crowd_admin($aId));
        if (!$isAdmin) {
            $this->error('你不是圈子管理员');
        }
        $crowd['is_admin'] = $isAdmin;
        $type = D('WeiboCrowdType')->getCrowdTypes();
        $this->assign('type', $type);
        $this->assign('crowd', $crowd);
        if(!$aType) {
            $aType = 'member';
        }
        if ($aType == 'member') {
            $map['status'] = 1;
            $map['crowd_id'] = $aId;
            $this->assign('tab', 'member');
        }
        if ($aType == 'check') {
            $map['crowd_id'] = $aId;
            $map['status'] = 0;
            $this->assign('tab', 'check');
        }
        $list = D('WeiboCrowdMember')->getMemberList($map, $aPage);
        foreach ($list as &$v) {
            $v['user'] = query_user(array('avatar128', 'avatar64', 'nickname', 'uid', 'space_url'), $v['uid']);
            $v['user_post_num'] = get_crowd_weibo_num($v['uid'], $aId);
        }
        unset($v);
        $totalCount = D('WeiboCrowdMember')->where($map)->count();
        if($aPage*10 < $totalCount) {
            $this->assign('page', 1);
        }

        $field = D('Ucenter/Score')->getTypeList(array('status' => 1));
        $this->assign("field", $field);

        $this->assign('crowd_id',$aId);
        $this->assign('member_list', $list);

        if($aPage > 1) {
            $data['html'] = '';
            $data['status'] = 1;
            $data['html'] .= $this->fetch('_member_list');
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    public function receiveMember()
    {
        if (IS_POST) {
            $aUid = I('post.uid', 0, 'intval');
            $aCrowd = I('post.crowd_id',0,'intval');
            $crowd = D('WeiboCrowd')->getCrowd($aCrowd);
            $title = '';
            if ($crowd['need_pay'] > 0) {
                $money = query_user('score'.$crowd['pay_type'],$aUid);
                if ($money['score'.$crowd['pay_type']] > $crowd['need_pay']) {
                    D('Ucenter/Score')->setUserScore($aUid, $crowd['need_pay'], $crowd['pay_type'], 'dec', 'weibo');
                    $title = '，并支付了'.$crowd['need_pay'].$crowd['pay_type_title'];
                } else {
                    send_message($aUid, '余额不足', get_nickname($aUid) . $crowd['pay_type_title'].'余额不足,加入' . "【{$crowd['title']}】"."失败,请获得该类积分后再来",  'Weibo/Crowd/crowd', array('id' => $aCrowd, 'type' => 'member'), is_login(), 'Weibo_crowd');
                    D('Weibo/WeiboCrowdMember')->delMember(array('crowd_id'=>$aCrowd,'uid'=>$aUid));
                    $this->error('该成员余额不足,无法支付入圈费！', 'refresh');
                }
            }
            $res = D('WeiboCrowdMember')->setStatus($aUid, $aCrowd, 1);
            if ($res) {
                D('Mob/WeiboCrowd')->changeCrowdNum($aCrowd);
                S('crowd_joined_'.$aUid,null);
                send_message($aUid, '您的加入圈子请求已通过', get_nickname($aUid) . '加入' . "【{$crowd['title']}】"."成功".$title,  'Weibo/Crowd/crowd', array('id' => $aCrowd, 'type' => 'member'), is_login(), 'Weibo_crowd');
                D('Mob/WeiboCrowdScore')->addScore($crowd);
                $this->success('审核成功', 'refresh');
            } else {
                $this->error('审核失败');
            }
        }
    }

    public function refuseMember()
    {
        if(IS_POST){
            $aUid = I('post.uid', 0, 'intval');
            $aCrowd = I('post.crowd_id',0,'intval');
            $crowd = D('WeiboCrowd')->getCrowd($aCrowd);
            $title = '';
            $res = D('WeiboCrowdMember')->delApply($aUid, $aCrowd);
            if($res) {
                send_message($aUid, '您的加入圈子请求未通过', get_nickname($aUid) . '加入' . "【{$crowd['title']}】"."失败".$title,  'Weibo/Crowd/crowd', array('id' => $aCrowd, 'type' => 'member'), is_login(), 'Weibo_crowd');
                $this->success('审核成功', 'refresh');
            } else {
                $this->error('审核失败');
            }
        }
    }

    public function removeMember()
    {
        if (IS_POST) {
            $aUid = I('post.uid', 0, 'intval');
            $aCrowd = I('post.crowd_id',0,'intval');
            $crowd = D('WeiboCrowd')->getCrowd($aCrowd);
            $model = D('WeiboCrowdMember');
            $model->where(array('uid' => $aUid, 'crowd_id' => $aCrowd));
            $data = $model->find();
            if ($data['position'] == 3) {
                $this->error('无法移除圈子管理员');
            }
            $res = $model->delete();
            if ($res) {
                D('Mob/WeiboCrowd')->changeCrowdNum($aCrowd,'member','dec');
                S('crowd_joined_'.$aUid,null);
                send_message($aUid, '您已被移出圈子', get_nickname($aUid) . '被管理员移出圈子' . "【{$crowd['title']}】", 'Weibo/Crowd/member', array('id' => $aCrowd), is_login(), 'Weibo_crowd');
                $this->success('移除成功', U('Weibo/Crowd/crowd',array('id'=>$aCrowd)));
            } else {
                $this->error('移除失败');
            }
        }
    }

    private function _checkCrowdExists($crowd_id)
    {
        $crowd = D('WeiboCrowd')->getCrowd($crowd_id);
        if (!$crowd) {
            $this->error('圈子不存在');
        }
    }

    private function _checkIsAttend($crowdId)
    {
        $res = D('Mob/WeiboCrowdMember')->getIsJoin(is_login(), $crowdId);
        if ($res == 0 || $res == 2) {
            $this->error('未加入该圈子');
        }
    }

    private function _checkIsAllowPost($crowd_id)
    {
        $crowd = D('WeiboCrowd')->getCrowd($crowd_id);
        if ($crowd['allow_user_post'] == -1) {
            if (check_auth('Weibo/Manage/*', get_crowd_admin($crowd_id))) {
                return true;
            }
            $this->error('该圈子已设为非管理员不能发送');
        }
    }

    public function delCrowd()
    {
        if (IS_POST) {
            $aCrowdId = I('post.crowd_id', 0, 'intval');
            $this->checkAuth('Weibo/Manager/dismiss',get_crowd_admin($aCrowdId),'您没有解散群组的权限');
            $crowd = D('WeiboCrowd')->getCrowd($aCrowdId);
            $userArray = D('WeiboCrowdMember')->where(array('crowd_id'=>$aCrowdId,'status'=>1))->field('uid')->select();
            $userArray = array_column($userArray,'uid');
            $res = D('WeiboCrowd')->delCrowd($aCrowdId);
            $data = D('WeiboCrowdMember')->delMember(array('crowd_id'=>$aCrowdId));
            $map=D('Weibo')->where(array('crowd_id'=>$aCrowdId,'status'=>1))->setField(array('crowd_id'=>0));
            if ($res !== false || $data !== false || $map !== false) {
                S('crowd_joined_'.is_login(),null);
                S('crowd_by_'.$aCrowdId,null);
                S('all_crowd_list', null);
                send_message($userArray, '您加入的圈子已被解散',  '您加入的圈子' . "【{$crowd['title']}】"."已被管理员解散，您已自动退出该圈子", 'Weibo/Crowd/index', '', is_login(), 'Weibo_crowd');
                $this->success('解散圈子成功',U('Weibo/crowd'));
            } else {
                $this->error('解散圈子失败');
            }
        }
    }
}