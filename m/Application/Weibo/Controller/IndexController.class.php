<?php
/**
 * Created by PhpStorm.
 * User: 王杰 wj@ourstu.com
 * Date: 2016/12/7
 * Time: 9:15
 */
namespace Weibo\Controller;

use Think\Controller;

require_once('./Application/Weibo/Conf/jssdk.php');
class IndexController extends BaseController
{
    public function _initialize()
    {
        parent::_initialize();
        $this->setTitle("动态");
        $this->setKeywords("动态");
        $this->setDescription("动态");
        $this->assign('bottom_flag', 'feed');
    }

    /**
     * 主页面显示
     */
    public function index()
    {
        //获取参数
        $aPage = I('get.page', 1, 'intval');
        $aSelect = I('get.select', 'all_', 'text');
        $aIsPull = I('get.is_pull', '', 'text');
        $crowdId = I('get.crowd_id', 0, 'intval');

        $param = array();
        $tab_config = get_kanban_config('WEIBO_DEFAULT_TAB', 'enable', array('all', 'hot', 'concerned' ,'huati'));
        if (empty($tab_config)) {
            $tab_config = array('all');
        }
        $aType = I('get.type', reset($tab_config), 'text');

        if (!in_array($aType, $tab_config)) {
            $this->error(L('_ERROR_PARAM_'));
        }
        //查询条件
        $weiboModel = D('Weibo/Weibo');
        $topModel = D('Weibo/WeiboTop');
        $param['field'] = 'id';
        if ($aPage == 1) {
            $param['limit'] = 10;
        } else {
            $param['page'] = $aPage;
            $param['count'] = 10;
        }
        $cover = modC('MOB_SITE_LOGO', '', 'Config');
        if ($cover) {
            $webSiteLogo = getThumbImageById($cover, 80, 80);
        }
        $this->assign('web_site_logo', $webSiteLogo);
        if (!empty($crowdId)) {
            $top = $topModel->getTopIds($crowdId);
            $param['where']['crowd_id'] = $crowdId;
            $param['where']['status'] = 1;
            if (!empty($top)) {
                $param['where']['id'] = array('not in', $top);
            }
            unset($top);

            $crowd = D('WeiboCrowd')->getCrowd($crowdId);
            if (empty($crowd)) {
                $this->error('圈子不存在');
            }
            $isJoin = D('Weibo/WeiboCrowdMember')->getIsJoin(is_login(), $crowdId);
            if ($crowd['invisible'] && $isJoin != 1) {
                $invisibles = 1 ;
                $this->assign('invisible', 1);
            }
            if ($crowd['order_type'] == 1) {
                $param['order'] = 'reply_time desc,create_time desc';
            }
            $crowd['is_admin'] = check_auth('Weibo/Manage/*', get_crowd_admin($crowdId));
            $crowd['check_num'] = D('WeiboCrowdMember')->where(array('crowd_id' => $crowdId, 'status' => 0))->count();
            $crowd['crowd_admin'] = query_user(array('nickname', 'avatar128', 'space_url', 'fans'), $crowd['uid']);
            $crowd['is_follow'] = D('WeiboCrowdMember')->getIsJoin(is_login(), $crowdId);
            $shareImg = getThumbImageById($crowd['logo'], 80, 80);
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
            $this->assign('share_img',$shareImg);
            $this->assign('crowd_detail', $crowd);
            $this->assign('crowd_type', $crowdId);
            $this->assign('crowd_weibo_list', 1);
            // 获取圈子置顶微博
            $top_crowd_list = D('WeiboTop')->getTop($crowdId);
            foreach ($top_crowd_list as $key => $val) {
                $crwod_top[] = D('Weibo/Weibo')->getWeiboDetail($val['weibo_id']);
                $crwod_top[$key]['title'] = get_short_sp($val['title'], 15);
            }
            $list = $weiboModel->getWeiboList($param);
            foreach ($list as $val) {
                $weibo[] = D('Weibo/Weibo')->getWeiboDetail($val);
            }
            unset($val);
            $this->assign('can_cancel_top_crowd_feed', check_auth(null, get_crowd_admin($crowdId)));
            $appid=modC('APP_ID','','weixin');
            $appsecret=modC('APP_SECRET','','weixin');
            $jssdk = new \JSSDK ($appid,$appsecret);
            $signPackage = $jssdk->GetSignPackage();
            $this->assign("signPackage",$signPackage);
        } else {
            if ($aType == 'concerned' && !is_login()) {
                $aType = 'all';
            }
            $this->assign('smallnav', $aSelect);
            $top = $topModel->getTopIds();
            $param = $this->filterWeibo($aType, $param);
            $param['where']['status'] = 1;
            if (!empty($top)) {
                $param['where']['id'] = array('not in', $top);
            }
            unset($top);

            if ($aPage == 1) {
                // 获取置顶微博
                $top_list = D('WeiboTop')->getTop();
                foreach ($top_list as $key => $val) {
                    $top[] = D('Weibo/Weibo')->getWeiboDetail($val['weibo_id']);
                    $top[$key]['title'] = get_short_sp($val['title'], 15);
                }
            }
            $invisibleList = D('Weibo/WeiboCrowd')->getInvisible();
            if (!empty($invisibleList)) {
                $invisible = array_column($invisibleList,'id');
                $param['where']['crowd_id'] = array('not in',$invisible);
            }
            $list = $weiboModel->getWeiboList($param);
            foreach ($list as $val) {
                $weibo[] = D('Weibo/Weibo')->getWeiboDetail($val);
            }
            unset($val);
            $this->assign('can_cancel_top_feed', check_auth(null, -1, L('_INFO_FAIL_STICK_AUTHORITY_LACK_') . L('_PERIOD_')));
        }

        foreach ($weibo as &$v) {
            $v['is_follow'] = D('Common/Follow')->isFollow(is_login(), $v['uid']);
        }
        unset($v);

        $this->assign('weibo', $weibo);
        $this->assign('first_weibo_num', count($weibo));
        unset($param['where']['crowd_id']);
        $this->assign('page', $aPage);
        $this->assign('type', $aType);
        $this->assign('crowd_id', $crowdId);
        $this->assign('uid', is_login());
        $this->assign('tab_type', $tab_config);
        $this->assign('member_count', D('Member')->where(array('status' => 1))->count());
        $this->assign('top', $top);
        $this->assign('crowd_top', $crwod_top);

        $uid = is_login();
        $crowdModel = D('WeiboCrowdMember');
        $my = $crowdModel->getUserJoin($uid);
        $ids = array_column($my, 'crowd_id');
        $crowds = array();
        foreach ($ids as $v) {
            $crowds[] = D('WeiboCrowd')->getCrowd($v);
        }
        $this->assign('crowds', $crowds);

        if (is_login() && check_auth('Weibo/Index/doSend')) {
            $this->assign('show_post', true);
        }
        if ($aIsPull) {
            $data['html'] = '';
            if ($invisibles) {
                $data['status'] = 1;
                $this->ajaxReturn($data);
            }
            $data['status'] = 1;
            $data['html'] .= $this->fetch('_list');
            $this->ajaxReturn($data);
        } else {
           // dump(C());exit;
            $this->display();
        }


    }

    private function filterWeibo($aType, $param)
    {
        if ($aType == 'concerned') {
            $followList = D('Follow')->getFollowList();
            $param['where']['uid'] = array('in', $followList);
        }
        if ($aType == 'hot') {
            $hot_left = modC('HOT_LEFT', 3);
            $time_left = get_some_day($hot_left);
            $param['where']['create_time'] = array('gt', $time_left);
            $param['order'] = 'comment_count desc';
            $this->assign('tab', 'hot');
        }
        if ($aType == 'fav') {
            $map_fav['app_name'] = 'Weibo';
            $map_fav['table'] = 'weibo';
            $map_fav['uid'] = get_uid();
            $support = D('Support')->where($map_fav)->field('row')->select();
            $param['where']['id'] = array('in', implode(',', getSubByKey($support, 'row')));
            $param['order'] = 'create_time desc';

        }

        return $param;
    }

    public function detail()
    {
        $aId = I('get.id', '', 'intval');
        $weibo = D('Weibo')->getWeiboDetail($aId);
        if ($weibo === null) {
            $this->error(L('_INEXISTENT_404_'));
        }
        M('weibo')->where(array('id' => $aId))->setInc('read_count');  //阅读数+1
        $weibo['user'] = query_user(array('space_url', 'avatar128', 'nickname', 'title'), $weibo['uid']);
        $weibo['is_follow'] = D('Common/Follow')->isFollow(is_login(), $weibo['uid']);
        $this->assign('weibo', $weibo);

        //获取神回复
        $supportList = D('WeiboComment')->getSupportComment($aId) ;
        $this->assign('supportList', $supportList[1]) ;
        //回复列表
        $comment = D('WeiboComment')->getCommentList($weibo['id'], 1);
        $commentCount = D('WeiboComment')->getCount($weibo['id']);
        $shareImg = $weibo['user']['avatar128'];
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
        $this->assign('share_img',$shareImg);
        $this->setTitle(get_short_sp($weibo['content'], 30));
        $this->setKeywords(get_short_sp($weibo['content'], 30));
        $this->setDescription($weibo['content']);

        $qRcode = modC('SOCIAL_QRCODE', '', 'Weibo');
        $this->assign('qcode', getThumbImageById($qRcode, 80, 80));
        $this->assign('site_name', modC('MOB_SITE_NAME', '微社区', 'Config'));
        $this->assign('site_intro', modC('MOB_SITE_INTRO', '未填写社区简介~', 'Config'));

        if (!empty($_SERVER['HTTP_REFERER'])) {
            $back = $_SERVER['HTTP_REFERER'];
        } elseif ($weibo['crowd_id']) {
            $back = U('weibo/index/index', array('crowd_id' => $weibo['crowd_id']));
        } else {
            $back = U('weibo/index/index');
        }

        //获取最新五个点赞人头像
        $support=D('support')->where(array('appname'=>'Weibo','table'=>'weibo','row'=>$aId))->field('uid')->limit(5)->select();
        $supportCount=D('support')->where(array('appname'=>'Weibo','table'=>'weibo','row'=>$aId))->count();
        foreach ($support as &$item){
            $item['uid']=query_user(array('uid','avatar512','nickname'),$item['uid']);
        }
        unset($item);
        $appid=modC('APP_ID','','weixin');
        $appsecret=modC('APP_SECRET','','weixin');
        $jssdk = new \JSSDK ($appid,$appsecret);
        $signPackage = $jssdk->GetSignPackage();
        $this->assign("signPackage",$signPackage);
        $this->assign('support',$support);
        $this->assign('supportCount',$supportCount);
        //获取点赞人停止结束
        $this->assign('back', $back);
        $this->assign('read_count', M('weibo')->where(array('id' => $aId))->getField('read_count'));
        $this->assign('comment', $comment);
        $this->assign('comment_count', $commentCount);
        $this->setTitle(text($weibo['content']));
        $this->display();
    }

    public function loadMoreComment()
    {
        $aPage = I('get.page', '2', 'intval');
        $aId = I('get.id', '', 'intval');
        $comment = D('WeiboComment')->getCommentList($aId, $aPage);
        $this->assign('comment', $comment);
        $data['html'] = '';
        $data['html'] .= $this->fetch("_comment");
        $data['status'] = 1;
        $this->ajaxReturn($data);
    }

    /**
     * doDelWeibo  删除微博
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function doDelWeibo()
    {
        $aWeiboId = I('post.weibo_id', 0, 'intval');
        $weiboModel = D('Weibo');

        $weibo = $weiboModel->getWeiboDetail($aWeiboId);

        if (!empty($weibo['crowd_id']) && $weibo['uid'] != is_login()) {
            if (!check_auth('Weibo/Manage/*', get_crowd_admin($weibo['crowd_id']))) {
                $this->error('您没有权限删除该动态');
            }
            D('Weibo/WeiboCrowd')->changeCrowdNum($weibo['crowd_id'], 'post', 'dec');
        } else {
            $this->checkAuth(null, $weibo['uid'], L('_INFO_AUTHORITY_COMMENT_DELETE_LACK_') . L('_PERIOD_'));
        }
        //删除微博
        $result = $weiboModel->deleteWeibo($aWeiboId);
        action_log('del_weibo', 'weibo', $aWeiboId, is_login());
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
     * setTop  置顶
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function setTop()
    {
        $aWeiboId = I('post.weibo_id', 0, 'intval');
        $aIsCrowd = I('post.is_crwod', 0, 'intval');
        $aTopTitle = I('post.title', '', 'text');
        $aTopDead = I('post.top_dead', 0, 'intval');
        if ($aTopDead < 0) {
            $this->error('请输入正确的天数');
        }
        if ($aTopDead == 0) {
            $deadTime = '';
        } else {
            $deadTime = time() + $aTopDead * 86400;
        }
        $weiboModel = D('Weibo');
        $topModel = D('WeiboTop');
        $weibo = $weiboModel->find($aWeiboId);
        if ($aIsCrowd) {
            $isTop = $topModel->isTop($weibo['id'], $weibo['crowd_id']);
        } else {
            $isTop = $topModel->isTop($weibo['id']);
        }
        if (!$weibo) {
            $this->error(L('_INFO_FAIL_STICK_WEIBO_CANNOT_EXIST_') . L('_PERIOD_'));
        }
        if ($aIsCrowd == 1) {
            $crowdId = $weibo['crowd_id'];
            $this->checkAuth(null, get_crowd_admin($crowdId), '您没有管理圈子的权限');

            if ($isTop == 0) {
                if ($topModel->addTop($weibo, $deadTime, $aTopTitle, true)) {
                    action_log('set_crowd_weibo_top', 'weibo', $aWeiboId, is_login());
                    S('weibo_' . $aWeiboId, null);
                    $this->success(L('_SUCCESS_STICK_') . L('_PERIOD_'));
                } else {
                    $this->error(L('_FAIL_STICK_') . L('_PERIOD_'));
                };
            } else {
                if ($aIsCrowd) {
                    $note = '圈内';
                } else {
                    $note = '全站';
                }
                $this->error('已经' . $note . '置顶了');
            }

        } else {
            $this->checkAuth(null, -1, L('_INFO_FAIL_STICK_AUTHORITY_LACK_') . L('_PERIOD_'));
            if ($isTop == 0) {
                if ($topModel->addTop($weibo, $deadTime, $aTopTitle)) {
                    action_log('set_weibo_top', 'weibo', $aWeiboId, is_login());
                    S('weibo_' . $aWeiboId, null);
                    $this->success(L('_SUCCESS_STICK_') . L('_PERIOD_'));
                } else {
                    $this->error(L('_FAIL_STICK_') . L('_PERIOD_'));
                };
            } else {
                if ($aIsCrowd) {
                    $note = '圈内';
                } else {
                    $note = '全站';
                }
                $this->error('已经' . $note . '置顶了');
            }
        }
    }

    public function cancelTop()
    {
        $aWeiboId = I('post.weibo_id', 0, 'intval');
        $aIsCrowd = I('post.is_crwod', 0, 'intval');
        $weiboModel = D('Weibo');
        $topModel = D('WeiboTop');
        $weibo = $weiboModel->find($aWeiboId);
        if ($aIsCrowd) {
            $isTop = $topModel->isTop($weibo['id'], $weibo['crowd_id']);
        } else {
            $isTop = $topModel->isTop($weibo['id']);
        }
        if (!$weibo) {
            $this->error(L('_INFO_FAIL_STICK_WEIBO_CANNOT_EXIST_') . L('_PERIOD_'));
        }
        if ($aIsCrowd == 1) {
            $crowdId = $weibo['crowd_id'];
            $this->checkAuth(null, get_crowd_admin($crowdId), '您没有管理圈子的权限');

            if ($isTop == 1) {
                if ($topModel->delTop($aWeiboId, $crowdId)) {
                    action_log('set_crowd_weibo_down', 'weibo', $aWeiboId, is_login());
                    S('weibo_' . $aWeiboId, null);
                    $this->success(L('_SUCCESS_STICK_CANCEL_') . L('_PERIOD_'));
                } else {
                    $this->error(L('_FAIL_STICK_CANCEL_') . L('_PERIOD_'));
                };
            } else {
                if ($aIsCrowd) {
                    $note = '圈内';
                } else {
                    $note = '全站';
                }
                $this->error('该动态没有被' . $note . '置顶');
            }

        } else {
            $this->checkAuth(null, -1, L('_INFO_FAIL_STICK_AUTHORITY_LACK_') . L('_PERIOD_'));
            if ($isTop == 1) {
                if ($topModel->delTop($aWeiboId)) {
                    action_log('set_weibo_down', 'weibo', $aWeiboId, is_login());
                    S('weibo_' . $aWeiboId, null);
                    $this->success(L('_SUCCESS_STICK_CANCEL_') . L('_PERIOD_'));
                } else {
                    $this->error(L('_FAIL_STICK_CANCEL_') . L('_PERIOD_'));
                };
            } else {
                if ($aIsCrowd) {
                    $note = '圈内';
                } else {
                    $note = '全站';
                }
                $this->error('该动态没有被' . $note . '置顶');
            }
        }
    }

    public function addComment()
    {
        if (!is_login()) {
            $this->error('请您先登录', U('Ucenter/member/login'), 1);
        }

        if (IS_POST) {
            $aContent = I('post.content', '', 'op_t');              //说点什么的内容
            $aWeiboId = I('post.weiboId', 0, 'intval');             //要评论的微博的ID
            $aCommentId = I('post.comment_id', 0, 'intval');
            $aImgId = I('post.img_id',0 , 'text');
            if (empty($aContent)) {
                $this->error('评论内容不能为空。');
            }

            $this->checkAuth('Weibo/Index/doComment', -1, '您无动态评论权限。');
            $return = check_action_limit('add_weibo_comment', 'weibo_comment', 0, is_login(), true);//行为限制
            if ($return && !$return['state']) {
                $this->error($return['info']);
            }
            $weibocomment = send_comment($aWeiboId, $aContent, $aCommentId,$aImgId);
            if ($weibocomment) {
                $data['html'] = "";
                $comment[] = D('WeiboComment')->getComment($weibocomment);
                $this->assign('comment', $comment);
                $data['html'] .= $this->fetch("_comment");
                $data['status'] = 1;
                //删除点赞评论转发数缓存
                S('weibo_count_by_' . $aWeiboId, null);
                //清除网站端微博缓存
                MS('weibo_list_detail_html_'. $aWeiboId, null);
            } else {
                $data['stutus'] = 0;
            }
            $this->ajaxReturn($data);
        }
    }

    public function repost()
    {
        $result = check_action_limit('add_weibo', 'weibo', 0, is_login(), true);
        if ($result && !$result['state']) {
            $this->error($result['info']);
        }
        if (!is_login()) {
            $this->error('请您先登录', U('Ucenter/member/login'), 1);
        }
        $aContent = I('post.content', '', 'op_t');

        $aType = I('post.type', '', 'op_t');

        $aSourceId = I('post.sourceId', 0, 'intval');

        $aWeiboId = I('post.weiboId', 0, 'intval');

        $aBeComment = I('post.becomment', 'false', 'op_t');

        if (empty($aContent)) {
            $this->error(L('_ERROR_CONTENT_CANNOT_EMPTY_'));
        }


        $weiboModel = D('Weibo');
        $feed_data = '';
        $source = $weiboModel->getWeiboDetail($aSourceId);
        $sourceweibo = $source['weibo'];
        $feed_data['source'] = $sourceweibo;
        $feed_data['sourceId'] = $aSourceId;
        //发布微博
        $new_id = send_weibo($aContent, $aType, $feed_data);

        if ($new_id) {
            D('weibo')->where('id=' . $aSourceId)->setInc('repost_count');
            $aWeiboId != $aSourceId && D('weibo')->where('id=' . $aWeiboId)->setInc('repost_count');
            S('weibo_' . $aWeiboId, null);
            S('weibo_' . $aSourceId, null);
            S('weibo_count_by_' . $aWeiboId, null);
            S('weibo_count_by_' . $aSourceId, null);
        }
        // 发送消息
        $toUid = D('weibo')->where(array('id' => $aWeiboId))->getField('uid');

        $message_content = array(
            'keyword1' => parse_content_for_message($aContent),
            'keyword2' => '转发了你的动态：',
            'keyword3' => $source['type'] == 'repost' ? "转发动态" : parse_content_for_message($source['content'])
        );
        send_message($toUid, L('_PROMPT_TRANSMIT_'), $message_content, 'Weibo/Index/detail', array('id' => $new_id), is_login(), 'Weibo', 'Common_comment');

        // 发布评论
        if ($aBeComment == 'on') {
            send_comment($aWeiboId, $aContent);
        }

        $weibo[] = D('Weibo')->getWeiboDetail($new_id);
        $this->assign('weibo', $weibo);
        $this->assign('uid', is_login());
        $result['html'] = $this->fetch('_list');
        $result['status'] = 1;
        $result['info'] = '转发成功！';
        $this->ajaxReturn($result);
    }


    public function sendWeibo()
    {
        if (!is_login()) {
            $this->error('请登录~', U('weibo/index/index'));
        }

        if (IS_POST) {
            $return = check_action_limit('add_weibo', 'weibo', 0, is_login(), true);
            if ($return && !$return['state']) {
                $this->error($return['info']);
            }
            $aContent = I('post.content', '', 'text');
            $aCrowd = I('post.crowd_id', 0, 'intval');
            $aGoods = I('post.goods_id', 0, 'intval');
            $sendType = I('post.type', 0, 'text');
            $aData = I('post.data', array(), 'text');
            $aExtra = I('post.extra', array(), 'convert_url_query');
            if (empty($aContent)) {
                $this->error('内容不能为空~');
            }
            $aType = 'feed';
            if ($aData['attach_ids']) {
                $aType = 'image';
            }
            if ($aGoods) {
                $aType = 'goods';
            }
            if ($sendType) {
                $aType = $sendType ;
            }
            $types = array('repost', 'feed', 'image', 'share');
            if (!in_array($aType, $types)) {
                $class_str = 'Addons\\Insert' . ucfirst($aType) . '\\Insert' . ucfirst($aType) . 'Addon';
                $class_exists = class_exists($class_str);
                if (!$class_exists) {
                    $this->error(L('_ERROR_CANNOT_SEND_THIS_'));
                } else {
                    $class = new $class_str();
                    if (method_exists($class, 'parseExtra')) {
                        $res = $class->parseExtra($aExtra);
                        if (!$res) {
                            $this->error($class->error);
                        }
                    }
                }
            }
            $this->checkAuth('Weibo/Index/doSend', -1, L('_INFO_AUTHORITY_LACK_'));
            if (!empty($aExtra)&&$aType=='xiami') $aData = $aExtra;

            $weibo_id = send_weibo($aContent, $aType, $aData, '', $aCrowd, $aGoods);
            if ($weibo_id) {
                if (!empty($aCrowd)) {
                    D('Weibo/WeiboCrowd')->changeCrowdNum($aCrowd, 'post', 'inc');
                }
                $this->success('发布成功');
            } else {
                $this->error('发布失败');
            }

        } else {
            $aId = I('topid','','intval') ;
            if ($aId) {
                $topic = M('WeiboTopic')->where('id='.$aId)->field('id,name')->find() ;
                if ($topic) {
                    $this->assign('topic' ,$topic) ;
                }
            }
            $this->display();
        }


    }


    public function sendArticle()
    {
        $uid = is_login();
        if (!$uid) {
            $this->error('请登录~', U('weibo/index/index'));
        }

        if (IS_POST) {
            $aTitle = I('post.title', '', 'text');
            $aContent = I('post.content', '', 'article_html');
            $aCrowd = I('post.crowd_id', 0, 'intval');
            $aData = I('post.data', array(), 'text');
            if ($aTitle=='') {
                $this->error('标题不能为空~');
            }
            if (empty($aContent)) {
                $this->error('内容不能为空~');
            }

            $weibo_id = D('Weibo')->addLongWeibo($uid, $aTitle, $aContent, $aCrowd, $aData);

            if ($weibo_id) {
                if (!empty($aCrowd)) {
                    D('Weibo/WeiboCrowd')->changeCrowdNum($aCrowd, 'post', 'inc');
                }
                $this->success('发布成功');
            } else {
                $this->error('发布失败');
            }

        } else {

            $this->display();
        }
    }

    public function getFriend()
    {
        $uid = is_login();
        if (!$uid) {
            $this->ajaxReturn('error');
        }
        $uids = D('Follow')->getMyFriends($uid);
        $friends = array();
        foreach ($uids as $val) {
            $t = query_user(array('avatar64', 'nickname', 'uid'), $val);
            $first = substr($t['pinyin'], 0, 1);
            unset($t['avatar128'], $t['avatar256'], $t['avatar512'], $t['avatar64'], $t['real_nickname']);
            $friends[$first][] = $t;
        }
        ksort($friends);

        $this->ajaxReturn(empty($friends) ? 'none' : $friends);

    }


    public function getCrowd()
    {
        $uid = is_login();
        if (!$uid) {
            $this->ajaxReturn('error');
        }
        $crowdModel = D('WeiboCrowdMember');
        $my = $crowdModel->getUserJoin($uid);
        $ids = array_column($my, 'crowd_id');
        $crowds = array();
        foreach ($ids as $v) {
            $crowds[] = D('WeiboCrowd')->getCrowd($v);
        }
        $this->ajaxReturn($crowds);

    }

    public function people()
    {
        $aPage = I('get.page', 1, 'intval');
        $aCount = I('get.count', 10, 'intval');
        $aIsAjax = I('get.ajax', 0, 'intval');
        $crowdAdmin = D('Weibo/WeiboCrowdMember')->getCrowdAllAdmin();
        $crowdAdmin = array_column($crowdAdmin, 'uid');
        $crowdList = $crowdAdmin;
        foreach ($crowdAdmin as &$v) {
            $v = query_user(array('uid', 'avatar128', 'nickname', 'signature', 'space_mob_url'), $v);
            $res = D('Common/Follow')->isFollow(is_login(), $v['uid']);
            if ($res == 1) {
                $v['follow_status'] = '已信任';
                $v['is_follow'] = 'unfollow';
            } else {
                $v['follow_status'] = '信任';
                $v['is_follow'] = 'follow';
            }
        }
        unset($v);
        $admin = get_administrator();
        $adminList = $admin;
        foreach ($admin as &$v) {
            $v = query_user(array('uid', 'avatar128', 'nickname', 'signature', 'space_mob_url'), $v);
            $res = D('Common/Follow')->isFollow(is_login(), $v['uid']);
            if ($res == 1) {
                $v['follow_status'] = '已信任';
                $v['is_follow'] = 'unfollow';
            } else {
                $v['follow_status'] = '信任';
                $v['is_follow'] = 'follow';
            }
        }
        unset($v);
        $memberModel = D('Member');
        $peoples = $memberModel->field('uid')->where(array('status' => 1))->page($aPage, $aCount)->select();
        $peoples = array_column($peoples, 'uid');
        foreach ($peoples as &$v) {
            $v = $memberModel->getPeople($v);
            $v['user'] = query_user(array('uid', 'avatar128', 'space_mob_url'), $v['uid']);
            $res = D('Common/Follow')->isFollow(is_login(), $v['uid']);
            if ($res == 1) {
                $v['follow_status'] = '已信任';
                $v['is_follow'] = 'unfollow';
            } else {
                $v['follow_status'] = '信任';
                $v['is_follow'] = 'follow';
            }
            if (in_array($v['uid'], $adminList)) {
                $v['flag'] = 'admin';
            } elseif (in_array($v['uid'], $crowdList)) {
                $v['flag'] = 'crowd_admin';
            } else {
                $v['flag'] = 'common';
            }
        }
        unset($v);
        $this->assign('crowd_admin', $crowdAdmin);
        $this->assign('admin', $admin);
        $this->assign('people', $peoples);
        if ($aIsAjax) {
            $data['html'] = '';
            $data['status'] = 1;
            $data['html'] .= $this->fetch('_people');
            $this->ajaxReturn($data);
        } else {
            $this->display();
        }
    }

    public function searchPeople()
    {
        $aPage = I('get.page', 1, 'intval');
        $aNickname = I('get.keywords', '', 'text');
        $res = D('Member')->searchPeople($aNickname, $aPage);
        $this->ajaxReturn($res);
    }

    public function result()
    {
        if (!is_login()) {
            $this->error('请先登录后操作');
        }
        $aId = I('get.id', '', 'intval');
        $token = I('get.token', '', 'text');
        $redbag = M('RedbagList')->where(array('redbagId' => $aId, 'uid' => is_login()))->find();
        $detail = M('Redbag')->where(array('id' => $aId))->find();
        if (md5($detail['id'] . 'xiaohehenilaipojiewoloa') != $token) {
            $this->error('非法操作~');
        }
        $redbag['user'] = query_user(array('uid', 'nickname', 'avatar128'), $redbag['uid']);
        $redbag['detail'] = $detail;
        $redbag['detail']['sell_num'] = M('RedbagList')->where(array('redbagId' => $aId))->count();
        $redbag['detail']['user'] = query_user(array('uid', 'nickname', 'avatar128'), $redbag['detail']['uid']);

        if ($redbag['detail']['status'] == 2) {
            $redbag['detail']['is_overtime'] = 1;
        }

        $list = M('RedbagList')->where(array('redbagId' => $aId))->select();
        foreach ($list as &$v) {
            $v['user'] = query_user(array('uid', 'nickname', 'avatar128'), $v['uid']);
        }
        unset($v);
        $this->assign('my', $redbag);
        $this->assign('list', $list);
        $this->display();
        $this->display();
    }

    public function sendRedBag()
    {
        $aContent = I('post.content', '', 'op_t');
        $aType = I('post.type', 4, 'op_t');
        $aNum = I('post.num', '', 'op_t');
        $aAllmoney = I('post.allmoney', 0, 'op_t');
        $aOnemoney = I('post.onemoney', 0, 'op_t');
        $aRed_bag_type = I('post.red_bag_type', '', 'op_t');
        $aKouLing = I('post.kouling', '', 'op_t');
        $aCrowd = I('post.crowd_id', 0, 'intval');

        if ($aAllmoney < 0) {
            $this->error('输入内容不能为负数');
        }
        if ($aOnemoney < 0) {
            $this->error('输入内容不能为负数');
        }
        if ($aNum < 0) {
            $this->error('红包个数不能为负');
        }
        if ($aNum > 100) {
            $this->error('红包个数不能打大于100');
        }
        if (empty($aNum)) {
            $this->error('请输入红包个数');
        }
        if ($aAllmoney == 0 && $aOnemoney == 0) {
            $this->error('请输入总额');
        }
        if (preg_match("/[\x7f-\xff]/", $aAllmoney)) {
            $this->error('请输入数字');
        }
        if (preg_match("/[\x7f-\xff]/", $aOnemoney)) {
            $this->error('请输入数字');
        }
        $data['uid'] = is_login();
        $data['num'] = $aNum;
        if ($aRed_bag_type == 1) {
            $data['all_money'] = $aOnemoney * $aNum;

            if ($aNum >= 1) {
                for ($i = 0; $i < $aNum; $i++) {
                    $total = $data['all_money'] - $aOnemoney * $i;
                    $data['rank'][] = array($i, $aOnemoney, $total);
                }
            }

            $data['rank'][] = array(intval($aNum), $aOnemoney, $total);
        } else {
            $data['all_money'] = $aAllmoney;

            /*随机红包分配方法*/
            $total = $data['all_money'];//红包总额
            $num = $aNum;// 分成8个红包，支持8人随机领取
            $total = intval($total * 100);
            $total_money = $total - $num;
            $i = $num;
            while ($i > 1) {
                $max = $total_money * 2 / $i;
                $now_bage = intval(mt_rand(0, $max));
                $bage[] = $now_bage;
                $total_money = $total_money - $now_bage;
                $i--;
            }
            $bage[] = $total_money;
            shuffle($bage);
            for ($j = 0; $j < $num; $j++) {
                $now_bage = $bage[$j] + 1;
                $total = $total - $now_bage;
                $money = $now_bage / 100;
                $data['rank'][] = array($j, $money, $total / 100);
                //   echo '第'.$i.'个红包：'.$money.' 元，余额：'.$total/100.' 元 <br/>';
            }
            /*随机红包分配方法END*/
            //$data['rank']=json_encode($data['rank'][]);
        }
        if ($aRed_bag_type == 3) {
            if (empty($aKouLing)) {
                $this->error('请输入红包口令！');
            } else {
                $data['content'] = $aKouLing;
            }

        } else {
            if (empty($aContent)) {
                $data['content'] = "恭喜发财，大吉大利！";
            } else {
                $data['content'] = $aContent;
            }
        }

        $data['rank'] = json_encode($data['rank']);
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

                $rs = D('Ucenter/Score')->setUserScore(is_login(), $data['all_money'], $v['id'], 'dec');

                //   $rs = D('Member')->where(array('uid' => is_login()))->setDec('score' . $v['id'], $data['all_money']);
                if ($rs) {
                    $res = M('Redbag')->add($data);
                    D('Message')->sendMessageWithoutCheckSelf(is_login(), '您发了个' . $v['title'] . '红包', $v['title'] . '减少了' . $data['all_money']);
                    expense_alendar('您发了个' . $v['title'] . '红包' . $v['title'] . '减少了' . number_format($data['all_money'], 2) . '元');
                    // action_log('send_redbag', 'redbag', $res, is_login());
                    if ($res) {
                        $redbag = M('Redbag')->where(array('id' => $res))->find();
                        send_weibo("我发布了一个" . $v['title'] . "红包【" . $data['content'] . "】：", 'redbag', $redbag, '', $aCrowd);
                        // D('Weibo/Weibo')->addWeibo(is_login(), "我发布了一个".$v['title']."红包【" . $data['content'] . "】：" ,'redbag',$redbag);
                        $this->success('红包发送成功！');
                    } else {
                        $this->error('红包发送失败！');
                    }

                } else {
                    $this->error('红包发送失败！');
                }
            }
        }
    }

    public function doOpenRedBag()
    {
        if (!is_login()) {
            $this->error('请先登录后操作');
        }
        $token = I('post.token', '', 'text');
        $aId = I('post.redBagId', '', 'op_t');
        $redbag = M('Redbag')->where(array('id' => $aId))->find();
        if (md5($redbag['id'] . 'xiaohehenilaipojiewoloa') != $token) {
            $this->ajaxReturn(array('status' => -1, 'info' => '非法操作~'));
        }
        $senduesr = query_user(array('uid', 'nickname', 'avatar64', 'space_url', 'rank_link', 'title'), $redbag['uid']);
        $getuesr = query_user(array('uid', 'nickname', 'avatar64', 'space_url', 'rank_link', 'title'), is_login());
        $isGet = M('RedbagList')->where(array('redbagId' => $aId, 'uid' => is_login()))->find();
        $countlist = M('RedbagList')->where(array('redbagId' => $aId))->count();

        if ($redbag['status'] == 2) {
            $this->ajaxReturn(array('status' => -1, 'info' => '红包已过期~'));
        }

        if ($countlist >= $redbag['num']) {
            $this->ajaxReturn(array('status' => 0, 'info' => '手慢了，红包已经抢完了~'));
        }
        if ($isGet) {
            $this->ajaxReturn(array('status' => 2, 'info' => '已经领取'));
        } else {
            $redbag['rank'] = json_decode($redbag['rank'], true);
            $rank = M('RedbagList')->where(array('redbagId' => $aId))->count();//第几个领红包的。

            foreach ($redbag['rank'] as &$v) {
                if ($v[0] == $rank) {
                    $data['get_bag'] = $v[1];
                }
            }
            $data['redbagId'] = $aId;
            $data['uid'] = is_login();
            $data['create_time'] = time();

            $field = D('Ucenter/Score')->getTypeList(array('status' => 1));
            foreach ($field as &$v) {
                if ($redbag['type'] == $v['id']) {
                    $type_title = $v['title'];
                }
            }
            foreach ($field as &$v) {
                if ($redbag['type'] == $v['id']) {
                    if ($redbag['redbag_type'] == 3) {
                        $aKouling = I('post.kouling', '', 'op_t');
                        if ($aKouling == $redbag['content']) {
                            $rs = D('Ucenter/Score')->setUserScore(is_login(), $data['get_bag'], $v['id'], 'inc');
                            $aWeiboId = I('post.weiboId', '', 'op_t');
                            send_comment_redbag($aWeiboId, $aKouling);
                        } else {
                            $this->ajaxReturn(array('status' => 0, 'info' => '口令错误，红包领取失败！'));
                        }
                    } else {
                        $rs = D('Ucenter/Score')->setUserScore(is_login(), $data['get_bag'], $v['id'], 'inc');
                    }

                    //  $rs = D('Member')->where(array('uid' => is_login()))->setInc('score' . $v['id'], $data['get_bag']);
                    if ($rs) {
                        M('RedbagList')->add($data);
                        M('Redbag')->where(array('id' => $aId))->setInc('sell_money', $data['get_bag']);
                        //判断是否已经领完了
                        $redbag = M('Redbag')->where(array('id' => $aId))->find();

                        if ($redbag['num'] == $rank) { // 如果已经领完了，设置最佳手气者
                            D('Redbag')->where(array('id' => $redbag['id']))->setField('status', 2);
                            if ($redbag['redbag_type'] == 2) {
                                $listGet = M('RedbagList')->where(array('redbagId' => $aId))->order('get_bag desc')->select();
                                M('RedbagList')->where(array('id' => $listGet[0]['id']))->setField('best_luck', 1);
                            } else {
                                $listGet = M('RedbagList')->where(array('redbagId' => $aId))->order('create_time asc')->select();
                                M('RedbagList')->where(array('id' => $listGet[0]['id']))->setField('best_luck', 2);
                            }
                        }


                        // action_log('get_redbag', 'redbag_list', $redbag['id'], is_login());
                        D('Message')->sendMessageWithoutCheckSelf(is_login(), '您领取了' . $senduesr['nickname'] . '的红包', $v['title'] . '增加了' . $data['get_bag']);
                        //记录消费记录
                        expense_alendar('您领取了' . $senduesr['nickname'] . '的红包'. $v['title'] . '增加了' . number_format($data['get_bag'],2).'元');
                        D('Message')->sendMessageWithoutCheckSelf($senduesr['uid'], $getuesr['nickname'] . '领取了您的的红包', '获得了' . $data['get_bag'] . $v['title']);
                        $this->ajaxReturn(array('status' => 1, 'info' => '领
                        取成功获得' . $v['title'] . $data['get_bag'], 'getRedBag' => $data['get_bag'], 'type_title' => $type_title));
                    }
                }
            }

        }
    }

    public function redbagDetail()
    {
        $aId = I('get.id', '', 'intval');
        $redbag = M('Redbag')->where(array('id' => $aId))->find();
        if (!$redbag) {
            $this->ajaxReturn('error');
        } else {
            $redbag['user'] = query_user(array('uid', 'nickname', 'avatar128'), $redbag['uid']);
            $redbag['is_get'] = M('RedbagList')->where(array('redbagId' => $aId, 'uid' => is_login()))->count();
            if (M('RedbagList')->where(array('redbagId' => $aId))->count() >= $redbag['num']) {
                $redbag['is_done'] = 1;
            }
            if ((time() - $redbag['create_time']) >= 86400) {
                if ($redbag['status'] != 2) {
                    D('Redbag')->where(array('id' => $aId))->setField('status', 2);
                }
                $redbag['is_overtime'] = 1;
            }
            $this->ajaxReturn(array('status' => 1, 'info' => $redbag));
        }
    }

    public function redbag()
    {
        $type = D('WeiboCrowd')->getAllCrowd();
        $this->assign('crowd_type', json_encode($type));
        $this->assign('count', D('Member')->where(array('status' => 1))->count());
        $this->display();
    }
    public function doSendShare(){
        $aContent = I('post.content','','text');
        $aQuery = I('post.query','','text');
        $aQuery = urldecode($aQuery) ;
        parse_str($aQuery,$feed_data);
        if(empty($aContent)){
            $this->error(L('_ERROR_CONTENT_CANNOT_EMPTY_'));
        }
        if(!is_login()){
            $this->error(L('_ERROR_SHARE_PLEASE_FIRST_LOGIN_'));
        }
        $new_id = send_weibo($aContent, 'share', $feed_data,$feed_data['from']);
        $info =  D('Weibo')->getInfo($feed_data);
        $toUid = $info['uid'];
        $message_content=array(
            'keyword1'=>  parse_content_for_message($aContent),
            'keyword2'=>'分享了你的：',
            'keyword3'=>$info['title']?$info['title']:"未知内容！"
        );
        send_message($toUid, L('_PROMPT_SHARE_'),$message_content,  'Weibo/Index/weiboDetail', array('id' => $new_id), is_login(), 'Weibo','Weibo_comment');
        //返回成功结果
        $result['status'] = 0;
        if ($new_id) {
            $result['status'] = 1;
        }
        $result['info'] = L('_SUCCESS_SHARE_').L('_EXCLAMATION_') . cookie('score_tip');;
        $this->ajaxReturn($result);
    }

    public function doSupport()
    {
        if (!is_login()) {
            exit(json_encode(array('status' => 0, 'info' => '请登陆后再点赞。')));
        }
        $appname = I('POST.appname');
        $table = I('POST.table');
        $row = I('POST.row');
        $aJump = I('POST.jump');
        $aWeiboId=I('post.weibo_id',0,'intval');

        $message_uid = intval(I('POST.uid'));
        $support['appname'] = $appname;
        $support['table'] = $table;
        $support['row'] = $row;
        $support['uid'] = is_login();

        $support_cache['appname'] = $appname;
        $support_cache['table'] = $table;

        if (D('Support')->where($support)->count()) {
            $res = D('Support')->where($support)->delete();
            if($aWeiboId){
                $support_cache['row'] = $aWeiboId;
                S('support_count_' . $appname . '_' . $table . '_' . $row, null);
            }else{
                $support_cache['row'] = $row;
            }
            D('Support')->clearCache($support_cache['appname'], $support_cache['table'], $support_cache['row']);
            if($res) {
                S('weibo_comment_' . $row , null);
                D('WeiboComment')->where(array('id'=> $row))->setDec('support_down') ;
                exit(json_encode(array('status' => 2, 'info' => '您取消了赞。')));
            }else{
                exit(json_encode(array('status' => 0, 'info' => '点赞失败~')));
            }
        } else {
            $support['create_time'] = time();
            if (D('Support')->where($support)->add($support)) {
                if($aWeiboId){
                    $support_cache['row'] = $aWeiboId;
                    S('support_count_' . $appname . '_' . $table . '_' . $row, null);
                }else{
                    $support_cache['row'] = $row;
                }
                D('Support')->clearCache($support_cache['appname'], $support_cache['table'], $support_cache['row']);
                $user = query_user(array('uid','nickname','space_url'),get_uid());
                send_message($message_uid,$title = $user['nickname'] . '赞了您', '快去看看吧^……^！',  $aJump , array('id' => $row),-1,'Ucenter');
                S('weibo_comment_' . $row , null);
                D('WeiboComment')->where(array('id'=> $row))->setInc('support_down') ;
                exit(json_encode(array('status' => 1, 'info' => '感谢您的支持。')));
            } else {
                exit(json_encode(array('status' => 0, 'info' => '写入数据库失败。')));
            }
        }
    }

    /**
     * 兼容pc端详情页链接
     */
    public function weibodetail() {
        $id = I('id', 0, 'intval') ;
        $this->redirect('weibo/index/detail',array('id'=>$id)) ;
    }
}