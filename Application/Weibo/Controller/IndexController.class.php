<?php
namespace Weibo\Controller;

use Think\Controller;
use Think\Hook;

class IndexController extends BaseController
{
    /**
     * index   微博首页
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function index()
    {
        $moduleModel = D('Common/Module');
        $shop = $moduleModel->checkInstalled('Shop');
        $ping = $moduleModel->checkInstalled('Pingxx');
        $Charge = $moduleModel->checkInstalled('Recharge');
        $model = $this->checkInModel();
        $uid = is_login();
        $check = $model->getCheck($uid);
        $time = date('Y-m-d', time());
        $this->assign('uid', $uid);
        $this->assign('check', $check);
        $this->assign('time', $time);
        $this->assign('shop', $shop);
        $this->assign('ping', $ping);
        $this->assign('Charge', $Charge);

        $this->assign('tab', 'index');
        $tab_config = get_kanban_config('WEIBO_DEFAULT_TAB', 'enable', array('all', 'concerned', 'hot', 'fav'));

        if (!is_login()) {
            $need_login_tab = array('concerned', 'fav');
            $this->assign('need_login_tab', $need_login_tab);
        }

        //推荐信任
        $tag = 'suggested_rand_' . $uid;
        $rand = S($tag);
        if ($rand === false) {
            $random = modC('SUGGESTED_RAND', 80, 'WEIBO');
            if (mt_rand(1, 100) <= $random) {
                $rand = mt_rand(0, 29);
            } else {
                $rand = mt_rand(30, 49);
            }
            S($tag, $rand, 60);
        }
        $this->assign('rand', $rand);
        $this->_suggestedFollows($uid);

        //获取参数
        $aType = I('get.type', reset($tab_config), 'op_t');
        $aUid = I('get.uid', 0, 'intval');
        $aPage = I('get.page', 1, 'intval');
        $aSelect = I('get.select', 'all_', 'text');

        $crowdId = I('crowd', '0', 'intval');
        if (!in_array($aType, $tab_config)) {
            $this->error(L('_ERROR_PARAM_'));
        }
        if ($aType == 'concerned' && !is_login()) {
            $aType = 'all';
        }
        $param = array();
        //查询条件
        $weiboModel = D('Weibo');
        $param['field'] = 'id';
        if ($aPage == 1) {
            $param['limit'] = 10;
        } else {
            $aPage = $aPage + 2;
            $param['page'] = $aPage;
            $param['count'] = 10;
        }
        $this->assign('smallnav', $aSelect);
        $param = $this->filterWeibo($aType, $param);
        $param['where']['status'] = 1;
        $param['where']['is_top'] = 0;
        //查询
        if (!empty($crowdId)) {
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
            $this->assign('crowd_detail', $crowd);
            $this->assign('crowd_type', $crowdId);
            $this->assign('loadMoreUrl', U('loadWeiboByCrowd', array('uid' => $aUid)));
            $this->assign('crowd_weibo_list', 1);
            // 获取圈子置顶微博
            $crowd_top_list = $weiboModel->getWeiboList(array('where' => array('status' => 1, 'is_crowd_top' => 1, 'crowd_id' => $crowdId)));
            $this->assign('crowd_top_list', $crowd_top_list);
        } else {
            $invisibleList = D('Weibo/WeiboCrowd')->getInvisible();
            if (!empty($invisibleList)) {
                $invisible = array_column($invisibleList,'id');
                $param['where']['crowd_id'] = array('not in',$invisible);
            }
            $this->assign('loadMoreUrl', U('loadweibo', array('uid' => $aUid)));
            // 获取置顶微博
            $top_list = $weiboModel->getWeiboList(array('where' => array('status' => 1, 'is_top' => 1)));
            $this->assign('top_list', $top_list);
        }
        $list = $weiboModel->getWeiboList($param);
        $this->assign('list', $list);

        $this->assign('total_count', $weiboModel->getWeiboCount($param['where']) - 20);
        unset($param['where']['crowd_id']);
        $this->assign('page', $aPage);
        $this->assign('type', $aType);
        $this->assign('tab_config', $tab_config);
        if ($aType == 'concerned') {
            $this->assign('title', L('_MY_FOLLOW_'));
            $this->assign('filter_tab', 'concerned');
        } else if ($aType == 'hot') {
            $this->assign('title', L('_HOT_WEIBO_'));
            $this->assign('filter_tab', 'hot');
        } else if ($aType == 'fav') {
            $this->assign('title', L('_MY_FAV_'));
            $this->assign('filter_tab', 'fav');
        } else {
            $this->assign('title', L('_ALL_WEBSITE_WEIBO_'));
            $this->assign('filter_tab', 'all');
        }
        $this->setTitle('{$title}' . L('_LINE_LINE_') . L('_MODULE_'));
        $this->assignSelf();
        if (is_login() && check_auth('Weibo/Index/doSend')) {
            $this->assign('show_post', true);
        }


        $recommond_topic = S('weibo_recommond_topic');
        if ($recommond_topic === false) {
            $config_recom_topic = modC('RECOMMEND_TOPIC', '', 'Weibo');
            if ($config_recom_topic != '') {
                $recommond_topic = M('WeiboTopic')->where(array('name' => $config_recom_topic, 'status' => 1))->find();
                $recommond_topic['last_weibo_id'] = M('WeiboTopicLink')->where(array('topic_id' => $recommond_topic['id']))->field('weibo_id')->order('create_time desc')->find();
                $recommond_topic['last_weibo'] = D('Weibo')->getWeiboDetail($recommond_topic['last_weibo_id']['weibo_id']);
            }
            S('weibo_recommond_topic', $recommond_topic, 60);
        }
        $recommond_topic['last_weibo']['user']['nickname'] = query_user(array('nickname'), $recommond_topic['last_weibo']['user']['uid']);

        $this->assign('recom_topic', $recommond_topic);
        $this->_weiboRight();
        $this->display();
    }

    public function quickLongWeibo()
    {
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
        $this->assign('follow_crowd_list', $followCrowd);

        $long_weibo_min_length = modC('long_weibo_min_length', 50, 'Weibo');
        $this->assign('long_weibo_min_length', $long_weibo_min_length);
        $this->display('_quicklongweibo');
    }

    private function _weiboRight()
    {
        $moduleModel = D('Common/Module');
        $shop = $moduleModel->checkInstalled('Shop');
        $ping = $moduleModel->checkInstalled('Pingxx');
        $Charge = $moduleModel->checkInstalled('Recharge');
        $Tcenter = $moduleModel->checkInstalled('Tcenter');
        $model = $this->checkInModel();
        $uid = is_login();
        $check = $model->getCheck($uid);
        $time = date('Y-m-d', time());
        $this->assign('uid', $uid);
        $this->assign('check', $check);
        $this->assign('time', $time);
        $this->assign('shop', $shop);
        $this->assign('Tcenter', $Tcenter);
        $this->assign('ping', $ping);
        $this->assign('Charge', $Charge);
        $totalScore = S('today_score_' . $uid);
        if ($totalScore === false) {
            $todayScore = M('score_log')->where(array('uid' => $uid, 'type' => '1', 'action' => 'inc', 'create_time' => array('between', array(strtotime(date('Ymd')), strtotime(date('Ymd')) + 86400))))->field('value')->select();

            $totalScore = 0;
            foreach ($todayScore as $val) {
                $totalScore += $val['value'];
            }
            unset($val);
            S('today_score_' . $uid, $totalScore, 600);
        }
        $this->assign('today_score', $totalScore);

        //话题推荐start
        $hotTopicList = D('Weibo/Topic')->getHotTopicList();
        $this->assign('hot_topic_list', $hotTopicList);

        //圈子排行
        $crowdRank = D('WeiboCrowd')->getRank();
        foreach ($crowdRank as &$v) {
            $v['is_follow'] = D('Weibo/WeiboCrowdMember')->getIsJoin(is_login(), $v['id']);
        }
        unset($v);
        $this->assign('crowd_rank', $crowdRank);

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
        $this->assign('follow_crowd_list', $followCrowd);
        return true;
    }

    private function checkInModel()
    {
        return D('Addons://CheckIn/CheckIn');
    }

    public function CollectExpression()
    {
        $iexp = M('iexpression');
        $iex_log = M('iexpression_link');
        $uid = is_login();
        if ($uid == 0) {
            $res['info'] = '请登入后再收藏';
            $this->ajaxReturn($res);
        }
        $aExpressionId = I('post.expression_id', 0, 'intval');


        $result = $iexp->find($aExpressionId);
        $res['status'] = 0;
        if (!$result) {
            $res['info'] = '该表情不存在';
            $this->ajaxReturn($res);
        }
        $map1['iexpression_id'] = $aExpressionId;
        $map1['uid'] = $uid;
        $iexpression_link = $iex_log->where($map1)->getField('id');
        if ($iexpression_link) {
            $res['info'] = '已存在';
        } else {
            $data['iexpression_id'] = $aExpressionId;
            $data['uid'] = $uid;
            $res_add = $iex_log->add($data);
            if ($res_add) {
                $res['status'] = 1;
                $res['info'] = '收藏成功';
            } else {
                $res['info'] = '收藏失败';
            }
        }
        $this->ajaxReturn($res);


    }


    public function getPos()
    {
        $pos = get_ip_lookup();
        if ($pos) {
            echo '<input name="pos" type="text" value="' . $pos['province'] . $pos['city'] . '"><a onclick="confirm_pos()">确定</a>';
        } else {
            echo '<input name="pos" type="text" placeholder="输入位置"><a onclick="confirm_pos()">确定</a>';
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

    /**
     * loadweibo   滚动载入
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function loadweibo()
    {

        $expect_type = array('hot', 'fav');
        $aType = I('get.type', '', 'text');
        $aPage = I('get.page', 1, 'intval');
        $aLastId = I('get.lastId', 0, 'intval');
        $aLoadCount = I('get.loadCount', 0, 'intval');
        $aSelect = I('get.select', 'all_', 'text');

        $crowdId = I('get.crowd', '0', 'intval');

        $weiboModel = D('Weibo');
        $param['where'] = array(
            'status' => 1,
            'is_top' => 0,
        );
        switch ($aSelect) {
            case 'image':
                $param['where']['type'] = 'image';
                break;
            case 'video':
                $param['where']['type'] = array('like', array('%video%', '%LocalVideo%'), 'OR');
                break;
            case 'musics':
                $param['where']['type'] = array('like', '%xiami%');
                break;
            case 'longWeibo':
                $param['where']['type'] = 'long_weibo';
                break;
            default;
        }
        $param = $this->filterWeibo($aType, $param);

        if (!empty($crowdId)) {
            $param['where']['crowd_id'] = $crowdId;
            $param['where']['is_crowd_top'] = 0;
            $this->assign('crowd_type', $crowdId);
        } else {
            $invisibleList = D('Weibo/WeiboCrowd')->getInvisible();
            if (!empty($invisibleList)) {
                $invisible = array_column($invisibleList,'id');
                $param['where']['crowd_id'] = array('not in',$invisible);
            }
        }

        $param['field'] = 'id';
        if ($aPage == 1) {
            if (!in_array($aType, $expect_type)) {
                $param['limit'] = 10;
                $param['where']['id'] = array('lt', $aLastId);
            } else {
                $param['page'] = $aLoadCount;
                $param['count'] = 10;
            }
        } else {//不应该请求到这里，如果请求到，说明有错
            $aPage = $aPage + 2;
            $param['page'] = $aPage;
            $param['count'] = 10;
        }

        //推荐信任
        $uid = is_login();
        $tag = 'suggested_rand_' . $uid;
        $rand = S($tag);
        $this->assign('rand', $rand);
        $this->_suggestedFollows($uid);

        $list = $weiboModel->getWeiboList($param);
        $this->assign('list', $list);
        $this->assign('lastId', end($list));
        $this->display();
    }

    /**
     * @author:Andy(王杰) wj@ourstu.com
     * 为了能兼容圈子按最新回复排序
     */
    public function loadWeiboByCrowd()
    {
        $aType = I('get.type', '', 'text');
        $aPage = I('get.page', 1, 'intval');
        $aSelect = I('get.select', 'all_', 'text');
        $aLoadCount = I('get.loadCount', 0, 'intval');

        $crowdId = I('get.crowd', '0', 'intval');

        $weiboModel = D('Weibo');
        $param['where'] = array(
            'status' => 1,
            'is_top' => 0,
        );
        switch ($aSelect) {
            case 'image':
                $param['where']['type'] = 'image';
                break;
            case 'video':
                $param['where']['type'] = array('like', array('%video%', '%LocalVideo%'), 'OR');
                break;
            case 'musics':
                $param['where']['type'] = array('like', '%xiami%');
                break;
            case 'longWeibo':
                $param['where']['type'] = 'long_weibo';
                break;
            default;
        }
        $param = $this->filterWeibo($aType, $param);

        $crowd = D('WeiboCrowd')->getCrowd($crowdId);
        if (!empty($crowdId)) {
            $isJoin = D('Weibo/WeiboCrowdMember')->getIsJoin(is_login(),$crowdId);
            if ($isJoin != 1 && $crowd['invisible']) {
                $this->error('必须加入私密圈子');
            }
            $param['where']['crowd_id'] = $crowdId;
            $param['where']['is_crowd_top'] = 0;
            $this->assign('crowd_type', $crowdId);
            if ($crowd['order_type'] == 1) {
                $param['order'] = 'reply_time desc,create_time desc';
            }
        }

        $param['field'] = 'id';

        if ($aPage == 1) {
            $param['page'] = $aLoadCount;
            $param['count'] = 10;
        } else {
            $param['page'] = $aPage;
        }

        //推荐信任
        $uid = is_login();
        $tag = 'suggested_rand_' . $uid;
        $rand = S($tag);
        $this->assign('rand', $rand);
        $this->_suggestedFollows($uid);

        $list = $weiboModel->getWeiboList($param);
        $this->assign('list', $list);
        $this->assign('lastId', end($list));
        $this->display('loadweibo');
    }

    /**
     * _suggestedFollows   推荐信任渲染数据
     * @author:路飞 lf@ourstu.com
     */
    public function _suggestedFollows($uid)
    {
        $weibo_num = M('Weibo')->where(array('status' => 1, 'is_top' => 0))->max('id');
        $this->assign('weibo_num', $weibo_num);

        $value = modC('SUGGESTED_VALUE', 0, 'WEIBO');
        $limit = modC('SUGGESTED_NUM', 15, 'WEIBO');
        $user_list = D('Weibo')->getSuggestedFollows($uid, $value, $limit);
        $suggested_count = count($user_list);
        $groupNum = ceil($suggested_count / 3);
        $user_list = $this->splitArray($user_list, $groupNum);
        $this->assign('suggested_follows', $user_list);
        $this->assign('suggested_count', $suggested_count);
    }

    /**
     * clearSuggestedFollows   信任后清除对应缓存
     * @author 路飞<lf@ourstu.com>
     */
    public function clearSuggestedFollows()
    {
        $aUid = I('post.uid', '', 'intval');
        if ($aUid) {
            $uid = is_login();
            $tag = 'suggested_follows_' . $uid;
            $res = S($tag, null);
            if ($res) {
                $this->ajaxReturn(array('status' => 1, 'suggested_id' => $aUid));
            } else {
                $this->ajaxReturn(array('status' => 0));
            }

        }

    }

    public function splitArray($array, $groupNum)
    {
        if (empty($array)) return array();

        //数组的总长度
        $allLength = count($array);

        //个数
        $groupNum = intval($groupNum);

        //开始位置
        $start = 0;

        //分成的数组中元素的个数
        $enum = 3;

        //结果集
        $result = array();

        if ($enum > 0) {
            //被分数组中 能整除 分成数组中元素个数 的部分
            $firstLength = $enum * $groupNum;
            $firstArray = array();
            for ($i = 0; $i < $firstLength; $i++) {
                array_push($firstArray, $array[$i]);
                unset($array[$i]);
            }
            for ($i = 0; $i < $groupNum; $i++) {

                //从原数组中的指定开始位置和长度 截取元素放到新的数组中
                $result[] = array_slice($firstArray, $start, $enum);

                //开始位置加上累加元素的个数
                $start += $enum;
            }
            //数组剩余部分分别加到结果集的前几项中
            $secondLength = $allLength - $firstLength;
            for ($i = 0; $i < $secondLength; $i++) {
                array_push($result[$i], $array[$i + $firstLength]);
            }
        } else {
            for ($i = 0; $i < $allLength; $i++) {
                $result[] = array_slice($array, $i, 1);
            }
        }
        return $result;
    }

    /**
     * doSend   发布微博
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function doSend()
    {
        $aContent = I('post.content', '', 'op_t');
        $aType = I('post.type', 'feed', 'op_t');
        $aAttachIds = I('post.attach_ids', '', 'op_t');
        $aExtra = I('post.extra', array(), 'convert_url_query');
        $aPos = I('post.pos', '', 'text');
        $crowdId = I('post.crowd', '', 'intval');
        $aSyncSina = I('post.sync_sina', '0', 'intval');
        if (!empty($crowdId)) {
            $this->_checkCrowdExists($crowdId);
            $this->_checkIsAttend($crowdId);
            $this->_checkIsAllowPost($crowdId);
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

        //权限判断
        $this->checkIsLogin();
        $this->checkAuth(null, -1, L('_INFO_AUTHORITY_LACK_'));
        $return = check_action_limit('add_weibo', 'weibo', 0, is_login(), true);
        if ($return && !$return['state']) {
            $this->error($return['info']);
        }

        $feed_data = array();
        if (!empty($aAttachIds)) {
            $feed_data['attach_ids'] = $aAttachIds;
        } else {
            if ($aType == 'image') {
                $this->error(L('_ERROR_AT_LEAST_ONE_'));
            } elseif ($aType == 'file') {
                $this->error(L('_WEIBO_FILE_AT_LAST_ONE'));
            }
        }

        if (!empty($aExtra)) $feed_data = array_merge($feed_data, $aExtra);

        // 执行发布，写入数据库
        $weibo_id = send_weibo($aContent, $aType, $feed_data, '', $aPos, $crowdId);
        if (!$weibo_id) {
            $this->error(L('_FAIL_PUBLISH_'));
        }

        if (!empty($crowdId)) {
            D('Weibo/WeiboCrowd')->changeCrowdNum($crowdId, 'post', 'inc');
        }
        if ($aSyncSina) {
            $result['sync'] = $this->_syncSinaWeibo(is_login(), $aContent);
        }
        $result['html'] = R('WeiboDetail/weibo_html', array('weibo_id' => $weibo_id), 'Widget');
        $result['status'] = 1;
        $result['info'] = L('_SUCCESS_PUBLISH_') . L('_EXCLAMATION_');
        //返回成功结果
        $this->ajaxReturn($result);
    }

    /**
     * 发布长微博
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    public function doSendLongWeibo()
    {
        $aContent = I('post.weibo_content', '', 'html');
        $aPlainContent = I('post.plain_txt', '', 'html');
        $crowdId = I('post.crowd', '', 'intval');
        if (!empty($crowdId)) {
            $this->_checkCrowdExists($crowdId);
            $this->_checkIsAttend($crowdId);
            $this->_checkIsAllowPost($crowdId);
        }
        $aTitle = I('post.title', '', 'text');
        if ($aTitle == '') {
            $this->error('请输入长文标题！');
        }
        if (strlen($aTitle) > 100) {
            $this->error('标题过长！');
        }

        $long_weibo_min_length = modC('long_weibo_min_length', 50, 'Weibo');
        if (strlen($aPlainContent) < $long_weibo_min_length) {
            $this->error('内容长度不能小于' . $long_weibo_min_length . '！');
        }
        //权限判断
        $this->checkIsLogin();
        $this->checkAuth('Weibo/Index/doSend', -1, L('_INFO_AUTHORITY_LACK_'));
        $return = check_action_limit('add_weibo', 'weibo', 0, is_login(), true);
        if ($return && !$return['state']) {
            $this->error($return['info']);
        }

        $weibo_id = D('Weibo')->addLongWeibo(is_login(), $aPlainContent, $crowdId, $aContent, $aTitle);
        if (!$weibo_id) {
            $this->error('发布失败 ' . D('Weibo')->getError());
        }

        action_log('add_weibo', 'weibo', $weibo_id, is_login());
        clean_query_user_cache(is_login(), array('weibocount'));

        if (!empty($crowdId)) {
            D('Weibo/WeiboCrowd')->changeCrowdNum($crowdId, 'post', 'inc');
        }
        $result['html'] = R('WeiboDetail/weibo_html', array('weibo_id' => $weibo_id), 'Widget');

        $result['status'] = 1;
        $result['info'] = L('_SUCCESS_PUBLISH_') . L('_EXCLAMATION_');
        //返回成功结果
        $this->ajaxReturn($result);
    }

    /**
     * sendrepost  发布转发页面
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function sendrepost()
    {
        $aSourceId = I('get.sourceId', 0, 'intval');
        $aWeiboId = I('get.weiboId', 0, 'intval');

        $weiboModel = D('Weibo');
        $result = $weiboModel->getWeiboDetail($aSourceId);

        $this->assign('sourceWeibo', $result);
        $weiboContent = '';
        if ($aSourceId != $aWeiboId) {
            $weibo1 = $weiboModel->getWeiboDetail($aWeiboId);
            $weiboContent = '//@' . $weibo1['user']['nickname'] . ' ：' . $weibo1['content'];

        }
        $this->assign('weiboId', $aWeiboId);
        $this->assign('weiboContent', $weiboContent);
        $this->assign('sourceId', $aSourceId);

        $this->display();
    }

    /**
     * doSendRepost   执行转发
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function doSendRepost()
    {
        $this->checkIsLogin();
        $aContent = I('post.content', '', 'op_t');

        $aType = I('post.type', '', 'op_t');

        $aSourceId = I('post.sourceId', 0, 'intval');

        $aWeiboId = I('post.weiboId', 0, 'intval');

        $aBeComment = I('post.becomment', 'false', 'op_t');


        $this->checkAuth(null, -1, L('_INFO_AUTHORITY_TRANSMIT_LACK_'));

        $return = check_action_limit('add_weibo', 'weibo', 0, is_login(), true);
        if ($return && !$return['state']) {
            $this->error($return['info']);
        }

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
            //清除html缓存
            clean_weibo_html_cache($aSourceId);
            //清除html缓存
            clean_weibo_html_cache($aWeiboId);
        }
        // 发送消息
        $toUid = D('weibo')->where(array('id' => $aWeiboId))->getField('uid');

        $message_content = array(
            'keyword1' => parse_content_for_message($aContent),
            'keyword2' => '转发了你的微博：',
            'keyword3' => $source['type'] == 'repost' ? "转发微博" : parse_content_for_message($source['content'])
        );
        send_message($toUid, L('_PROMPT_TRANSMIT_'), $message_content, 'Weibo/Index/weiboDetail', array('id' => $new_id), is_login(), 'Weibo', 'Common_comment');

        // 发布评论
        //  dump($aBeComment);exit;
        if ($aBeComment == 'true') {
            send_comment($aWeiboId, $aContent);

        }

        $result['html'] = R('WeiboDetail/weibo_html', array('weibo_id' => $new_id), 'Widget');
        //返回成功结果

        $result['status'] = 1;
        $result['info'] = '转发成功！';
        $this->ajaxReturn($result);
    }


    /**
     * doComment  发布评论
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function doComment()
    {
        $this->checkIsLogin();
        $aWeiboId = I('post.weibo_id', 0, 'intval');
        $aContent = I('post.content', 0, 'op_t');
        $aCommentId = I('post.comment_id', 0, 'intval');

        $this->checkAuth(null, -1, L('_INFO_AUTHORITY_COMMENT_LACK_') . L('_PERIOD_'));
        $return = check_action_limit('add_weibo_comment', 'weibo_comment', 0, is_login(), true);
        if ($return && !$return['state']) {
            $this->error($return['info']);
        }


        if (empty($aContent)) {
            $this->error(L('_ERROR_CONTENT_CANNOT_EMPTY_'));
        }
        //发送评论
        $result['data'] = send_comment($aWeiboId, $aContent, $aCommentId);
        if (!$result['data']) {
            $res['info'] = D('WeiboComment')->getError();
            $res['status'] = 0;
            //返回成功结果
            $this->ajaxReturn($res);
        }

        $result['html'] = R('Comment/comment_html', array('comment_id' => $result['data']), 'Widget');

        $result['status'] = 1;
        $result['info'] = L('_SUCCESS_COMMENT_') . L('_EXCLAMATION_');
        //返回成功结果
        $this->ajaxReturn($result);
    }

    /**
     * checkIsLogin  判断是否登录
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    private function checkIsLogin()
    {
        if (!is_login()) {
            $this->error(L('_ERROR_PLEASE_FIRST_LOGIN_'));
        }
    }

    /**
     * commentlist  评论列表
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function commentlist()
    {
        $aWeiboId = I('post.weibo_id', 0, 'intval');
        $aPage = I('post.page', 1, 'intval');
        $aShowMore = I('post.show_more', 0, 'intval');
        $list = D('WeiboComment')->getCommentList($aWeiboId, $aPage, $aShowMore);
        $this->assign('list', $list);
        $this->assign('page', $aPage);
        $this->assign('weiboId', $aWeiboId);
        $weobo = D('Weibo')->getWeiboDetail($aWeiboId);
        $this->assign('weiboCommentTotalCount', $weobo['comment_count']);
        $this->assign('show_more', $aShowMore);
        $html = $this->fetch('commentlist');
        $this->ajaxReturn($html);

    }

    /**
     * doDelComment  删除评论
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function doDelComment()
    {

        $aCommentId = I('post.comment_id', 0, 'intval');
        $this->checkIsLogin();
        $comment = D('Weibo/WeiboComment')->getComment($aCommentId);
        $this->checkAuth(null, $comment['uid'], L('_INFO_AUTHORITY_COMMENT_DELETE_LACK_') . L('_PERIOD_'));


        //删除评论
        $result = D('Weibo/WeiboComment')->deleteComment($aCommentId);
        action_log('del_weibo_comment', 'weibo_comment', $aCommentId, is_login());
        if ($result) {
            $return['status'] = 1;
            $return['info'] = L('_SUCCESS_DELETE_');
        } else {
            $return['status'] = 0;
            $return['info'] = L('_FAIL_DELETE_');
        }
        //返回成功信息
        $this->ajaxReturn($return);
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
            D('Weibo/Topic')->afterDelWeibo($aWeiboId);
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
        $aType = I('post.type', '', 'text');
        $weiboModel = D('Weibo');
        $weibo = $weiboModel->find($aWeiboId);
        if (!$weibo) {
            $this->error(L('_INFO_FAIL_STICK_WEIBO_CANNOT_EXIST_') . L('_PERIOD_'));
        }
        if ($aType == 'crowd') {
            $crowdId = $weibo['crowd_id'];
            $this->_checkCrowdExists($crowdId);
            $this->checkAuth(null, get_crowd_admin($crowdId), '您没有管理圈子的权限');

            if ($weibo['is_crowd_top'] == 0) {
                if ($weiboModel->where(array('id' => $aWeiboId))->setField('is_crowd_top', 1)) {
                    action_log('set_crowd_weibo_top', 'weibo', $aWeiboId, is_login());
                    S('weibo_' . $aWeiboId, null);
                    D('WeiboCache')->cleanCache($aWeiboId);
                    $this->success(L('_SUCCESS_STICK_') . L('_PERIOD_'));
                } else {
                    $this->error(L('_FAIL_STICK_') . L('_PERIOD_'));
                };
            } else {
                if ($weiboModel->where(array('id' => $aWeiboId))->setField('is_crowd_top', 0)) {
                    action_log('set_crowd_weibo_down', 'weibo', $aWeiboId, is_login());
                    S('weibo_' . $aWeiboId, null);
                    D('WeiboCache')->cleanCache($aWeiboId);
                    $this->success(L('_SUCCESS_STICK_CANCEL_') . L('_PERIOD_'));
                } else {
                    $this->error(L('_FAIL_STICK_CANCEL_') . L('_PERIOD_'));
                };
            }

        } else {
            $this->checkAuth(null, -1, L('_INFO_FAIL_STICK_AUTHORITY_LACK_') . L('_PERIOD_'));
            $weiboTopicLink = D('Weibo/WeiboTopicLink');
            if ($weibo['is_top'] == 0) {
                if ($weiboModel->where(array('id' => $aWeiboId))->setField('is_top', 1)) {
                    $weiboTopicLink->setWeiboTop($aWeiboId, 1);
                    action_log('set_weibo_top', 'weibo', $aWeiboId, is_login());
                    S('weibo_' . $aWeiboId, null);
                    $this->success(L('_SUCCESS_STICK_') . L('_PERIOD_'));
                } else {
                    $this->error(L('_FAIL_STICK_') . L('_PERIOD_'));
                };
            } else {
                if ($weiboModel->where(array('id' => $aWeiboId))->setField('is_top', 0)) {
                    $weiboTopicLink->setWeiboTop($aWeiboId, 0);
                    action_log('set_weibo_down', 'weibo', $aWeiboId, is_login());
                    S('weibo_' . $aWeiboId, null);
                    $this->success(L('_SUCCESS_STICK_CANCEL_') . L('_PERIOD_'));
                } else {
                    $this->error(L('_FAIL_STICK_CANCEL_') . L('_PERIOD_'));
                };
            }
        }
    }

    /**
     * assignSelf  输出当前登录用户信息
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    private function assignSelf()
    {
        $self = query_user(array('title', 'avatar128', 'nickname', 'uid', 'space_url', 'score', 'title', 'fans', 'following', 'weibocount', 'rank_link'));
        //获取用户封面id
        $map = getUserConfigMap('user_cover');
        $map['role_id'] = 0;
        $model = D('Ucenter/UserConfig');
        $cover = $model->findData($map);
        $self['cover_id'] = $cover['value'];
        $self['cover_path'] = getThumbImageById($cover['value'], 300, 180);
        $this->assign('self', $self);
    }

    /**
     * weiboDetail  微博详情页
     * @param $id
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function weiboDetail($id)
    {
        //读取微博详情

        $weibo = D('Weibo')->getWeiboDetail($id);
        $crowd = D('Weibo/WeiboCrowd')->getCrowd($weibo['crowd_id']);
        $isJoin = D('Weibo/WeiboCrowdMember')->getIsJoin(is_login(),$crowd['id']);
        if ($isJoin!=1 && $crowd['invisible']) {
            $this->error('必须加入该私密圈子才能查看此微博');
        }
        if ($weibo === null) {
            $this->error(L('_INEXISTENT_404_'));
        }
        $weibo['user'] = query_user(array('space_url', 'avatar128', 'nickname', 'title'), $weibo['uid']);
        //显示页面

        $this->assign('weibo', $weibo);

        $this->userInfo($weibo['uid']);

        $supported = D('Weibo')->getSupportedPeople($weibo['id'], array('nickname', 'space_url', 'avatar128', 'space_link'), 12);
        $this->assign('supported', $supported);
        if ($weibo['type'] == 'long_weibo') {
            $this->setTitle($weibo['long_weibo']['title'] . L('_LINE_LINE_') . L('_WEIBO_DETAIL_'));
        } else {
            $this->setTitle('{$weibo.content|op_t}' . L('_LINE_LINE_') . L('_WEIBO_DETAIL_'));
        }

        $this->assign('tab', 'index');
        $this->display();
    }


    private function userInfo($uid = null)
    {
        $user_info = query_user(array('avatar128', 'nickname', 'uid', 'space_url', 'score', 'title', 'fans', 'following', 'weibocount', 'rank_link', 'signature'), $uid);
        //获取用户封面id
        $map = getUserConfigMap('user_cover', '', $uid);
        $map['role_id'] = 0;
        $model = D('Ucenter/UserConfig');
        $cover = $model->findData($map);
        $user_info['cover_id'] = $cover['value'];
        $user_info['cover_path'] = getThumbImageById($cover['value'], 300, 180);

        $user_info['tags'] = D('Ucenter/UserTagLink')->getUserTag($uid);
        $this->assign('user_info', $user_info);
        return $user_info;
    }

    public function loadComment()
    {
        $aWeiboId = I('post.weibo_id', 0, 'intval');
        $return['html'] = R('Comment/someCommentHtml', array('weibo_id' => $aWeiboId), 'Widget');
        $return['status'] = 1;
        //返回成功信息
        $this->ajaxReturn($return);
    }


    public function search()
    {

        $aKeywords = $this->parseSearchKey('keywords');
        $aKeywords = text($aKeywords);
        $aPage = I('get.page', 1, 'intval');
        $r = 30;
        $param['where']['content'] = array('like', "%{$aKeywords}%");
        $param['where']['status'] = 1;
        $param['order'] = 'create_time desc';
        $param['page'] = $aPage;
        $param['count'] = $r;
        //查询
        $list = D('Weibo')->getWeiboList($param);
        $totalCount = D('Weibo')->where($param['where'])->count();
        $this->assign('totalCount', $totalCount);
        $this->assign('r', $r);
        $this->assign('list', $list);
        $this->assign('search_keywords', $aKeywords);
        $this->assignSelf();

        $this->_weiboRight();
        $this->display();
    }


    protected function parseSearchKey($key = null)
    {
        $action = MODULE_NAME . '_' . CONTROLLER_NAME . '_' . ACTION_NAME;
        $post = I('post.');
        if (empty($post)) {
            $keywords = cookie($action);
        } else {
            $keywords = $post;
            cookie($action, $post);
            $_GET['page'] = 1;
        }

        if (!$_GET['page']) {
            cookie($action, null);
            $keywords = null;
        }
        return $key ? $keywords[$key] : $keywords;
    }

    public function uploadMyExp()
    {

        $flag = 1;
        $uid = is_login();
        $mycollection = 'face';
        $iexpression = M('iexpression');
        $iexplog = M('iexpression_link');
        $driver = modC('PICTURE_UPLOAD_DRIVER', 'local', 'config');
        $driver = check_driver_is_exist($driver);
        $uploadConfig = get_upload_config($driver);
        if ($driver == 'local') {
            $config = array(
                'maxSize' => 5 * 1024 * 1024,
                'rootPath' => './Uploads/',
                'savePath' => 'Expression/' . $mycollection . '/',
                'saveName' => '',
                'exts' => array('jpg', 'gif', 'png', 'jpeg'),
                'autoSub' => true,
                'subName' => '',
                'replace' => true,
            );
            $upload = new \Think\Upload($config); // 实例化上传类
            $info = $upload->upload($_FILES);
            if (!$info) { // 上传错误提示错误信息
                echo json_encode('-1');
                $flag = 0;
            }
            $k = 0;
            foreach ($_FILES['myexp']['name'] as $name) {
                $allname = $name;
                // $iname=substr($allname,0,strrpos($allname,'.'));
                $rp = $this->ROOT_PATH = str_replace('/Application/Weibo/Controller/IndexController.class.php', '', str_replace('\\', '/', __FILE__));
                $path = $rp . "/Uploads/Expression/";
                if (!file_exists($path . $mycollection)) {
                    mkdir($path . $mycollection, 0777, true);
                }
                $path0 = $rp . '/Uploads/Expression/' . $mycollection . '/' . $allname;
                $file = file_get_contents($path0);
                $map['md5'] = md5($file);
                $iexp_id = $iexpression->where($map)->getField('id');
                if ($iexp_id) {
                    $map1['iexpression_id'] = $iexp_id;
                    $map1['uid'] = $uid;
                    $res = $iexplog->where($map1)->select();
                    if ($res) {
                        echo json_encode('0');
                        $iexp_path = $iexpression->where($map)->getField('path');
                        if ($iexp_path != "/Uploads/Expression/" . $mycollection . '/' . $allname) {
                            unlink($path0);
                        }

                        $flag = 0;

                    } else {
                        $data0['iexpression_id'] = $iexp_id;
                        $data0['uid'] = $uid;
                        $iexplog->add($data0);
                        $exppath = $iexpression->where('id=' . $iexp_id)->getField('path');
                        $data3[$k]['name'] = $iexp_id;
                        $data3[$k]['path'] = __ROOT__ . $exppath;
                        $data3[$k]['from'] = $mycollection;
                        $k++;
                    }
                } else {
                    if ($flag == 1) {
                        $data['path'] = '/Uploads/Expression/' . $mycollection . '/' . $allname;
                        $data['driver'] = 'local';
                        $data['md5'] = md5($file);
                        $data['create_time'] = time();
                        $iexpression->add($data);
                        $map['path'] = '/Uploads/Expression/' . $mycollection . '/' . $allname;
                        $iex_id = $iexpression->where($map)->getField('id');

                        $data1['iexpression_id'] = $iex_id;
                        $data1['uid'] = $uid;
                        $iexplog->add($data1);

                        $data3[$k]['name'] = $iex_id;
                        $data3[$k]['path'] = __ROOT__ . '/Uploads/Expression/' . $mycollection . '/' . $allname;
                        $data3[$k]['from'] = $mycollection;
                        $k++;
                    }
                }
            }
            unset($k);
            if (!empty($data3)) {
                echo json_encode($data3);
            }
        } else if ($driver == 'QiNiu') {
            $config = array(
                'maxSize' => 5 * 1024 * 1024,//文件大小
                'rootPath' => './',
                'saveName' => array('uniqid', ''),
                'driver' => 'Qiniu',
                'driverConfig' => array(
                    'secrectKey' => $uploadConfig['secrectKey'],
                    'accessKey' => $uploadConfig['accessKey'],
                    'domain' => $uploadConfig['domain'],
                    'bucket' => $uploadConfig['bucket'],
                )
            );
            $upload = new \Think\Upload($config); // 实例化上传类
            $info = $upload->upload($_FILES);
            if (!$info) { // 上传错误提示错误信息
                echo json_encode('-1');
            }
            $k = 0;
            foreach ($info as $i) {
                $picInfo = $iexpression->where(array('md5' => $i['md5']))->find();
                if ($picInfo) {
                    $result = $iexplog->where(array('iexpression_id' => $picInfo['id'], 'uid' => is_login()))->getField('id');
                    if ($result) {
                        echo json_encode('0');
                    } else {
                        $data3['iexpression_id'] = $picInfo['id'];
                        $data3['uid'] = is_login();
                        $iexplog->add($data3);

                        $pic[$k]['name'] = $picInfo['id'];
                        $pic[$k]['path'] = $i['url'];
                        $pic[$k]['from'] = $mycollection;
                        $k++;
                    }

                } else {
                    $data4['path'] = $i['url'];
                    $data4['driver'] = 'qiniu';
                    $data4['md5'] = $i['md5'];
                    $data4['create_time'] = time();
                    $id = $iexpression->add($data4);
                    $data5['iexpression_id'] = $id;
                    $data5['uid'] = is_login();
                    $iexplog->add($data5);

                    $pic[$k]['name'] = $id;
                    $pic[$k]['path'] = $i['url'];
                    $pic[$k]['from'] = $mycollection;
                    $k++;
                }
            }
            unset($k);
            unset($i);
            if (!empty($pic)) {
                echo json_encode($pic);
            }

        }


    }

    public function delMyExp()
    {
        $id = I('post.id', 0, 'intval');
        $uid = is_login();
        $iexplink = M('iexpression_link');
        $map['iexpression_id'] = $id;
        $map['uid'] = $uid;
        $res = $iexplink->where($map)->delete();
        echo json_encode($res);
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
        $res = D('Weibo/WeiboCrowdMember')->getIsJoin(is_login(), $crowdId);
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

    public function doSupport()
    {
        if (!is_login()) {
            exit(json_encode(array('status' => 0, 'info' => '请登陆后再点赞。')));
        }
        $appname = I('POST.appname');
        $table = I('POST.table');
        $row = I('POST.row');
        $aJump = I('POST.jump');
        $weibo_id = I('POST.weibo_id');

        $message_uid = intval(I('POST.uid'));
        $support['appname'] = $appname;
        $support['table'] = $table;
        $support['row'] = $row;
        $support['uid'] = is_login();

        $support_comment['appname'] = $appname;
        $support_comment['table'] = $table;
        $support_comment['row'] = $weibo_id;

        if (D('Support')->where($support)->count()) {

            exit(json_encode(array('status' => 0, 'info' => '您已经赞过，不能再赞了。')));
        } else {
            $support['create_time'] = time();
            if (D('Support')->where($support)->add($support)) {

                $this->clearCache($support_comment);

                $user = query_user(array('nickname'), get_uid());
                send_message($message_uid, $title = $user['nickname'] . '赞了您', '快去看看吧^……^！', $aJump, array('id' => $row), -1, 'Ucenter');
                exit(json_encode(array('status' => 1, 'info' => '感谢您的支持。')));
            } else {
                exit(json_encode(array('status' => 0, 'info' => '写入数据库失败。')));
            }

        }
    }

    private function clearCache($support)
    {
        D('Support')->clearCache($support['appname'], $support['table'], $support['row']);
    }

    public function getNewWeibo()
    {
        $aCount = I('post.count', 0, 'intval');
        $aCrowd = I('post.crowd', 0, 'intval');
        $weiboModel = D('Weibo');
        $param['where'] = array('uid' => array('neq', is_login()));
        $aCrowd && $param['where']['crowd_id'] = $aCrowd;
        $param['limit'] = $aCount;
        $param['field'] = 'id';
        $list = $weiboModel->getWeiboList($param);
        $html = '';
        foreach ($list as $v) {
            if ($v) {
                $weibo = $weiboModel->getWeiboDetail($v);
                if ($weibo['status']) {
                    $html .= R('WeiboDetail/weibo_html', array('weibo_id' => $v), 'Widget');
                }
            }
        }
        $this->ajaxReturn(array('status' => 1, 'html' => $html));

    }

    public function parseXiami()
    {
        $id = I('post.id', '', 'intval');
        $data = getXiaMiUrl($id);
        if ($data) {
            $location = ipcxiami($data['location']);
            $this->ajaxReturn(array('status' => 1, 'src' => $location));
        }
    }

    /**
     * 同步到新浪微博
     * @author:Andy(王杰) wj@ourstu.com
     */
    private function _syncSinaWeibo($uid, $content)
    {
        // todo 优化代码
        $url = "https://api.weibo.com/2/statuses/update.json";
        $syncData = M('sync_login')->where(array('uid' => $uid))->find();
        if ($syncData) {
            $token = $syncData['oauth_token'];
            $params = array('access_token' => $token, 'status' => $content);
            $opts = array(
                CURLOPT_TIMEOUT => 30,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_HTTPHEADER => array()
            );
            $opts[CURLOPT_URL] = $url;
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = http_build_query($params);
            $ch = curl_init();
            curl_setopt_array($ch, $opts);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            $data = curl_exec($ch);
            $error = curl_error($ch);
            curl_close($ch);
            return array('sync_data' => json_decode($data, true), 'sync_error' => json_decode($error, true));
        } else {
            //todo
            return array('sync_data' => '未绑定');
        }
    }

    /**
     * 检测是否绑定新浪微博
     * @author:Andy(王杰) wj@ourstu.com
     */
    public function checkBindSinaWeibo()
    {
        $uid = is_login();
        $syncData = M('sync_login')->where(array('uid' => $uid))->find();
        if ($syncData) {
            $this->success('已绑定新浪微博');
        } else {
            $this->error('未绑定新浪微博');
        }
    }

    public function editLongWeibo()
    {
        if(IS_POST){
            $aId = I('post.id', 0, 'intval');
            $aContent = I('post.weibo_content', '', 'html');
            $aPlainContent = I('post.plain_txt', '', 'html');
            $crowdId = I('post.crowd','','intval');
            if (!empty($crowdId)){
                $this->_checkCrowdExists($crowdId);
                $this->_checkIsAttend($crowdId);
                $this->_checkIsAllowPost($crowdId);
            }
            $aTitle=I('post.title','','text');
            if($aTitle==''){
                $this->error('请输入长文标题！');
            }
            if(strlen($aTitle)>100){
                $this->error('标题过长！');
            }
            $long_weibo_min_length=modC('long_weibo_min_length',50,'Weibo');
            if(strlen($aPlainContent)<$long_weibo_min_length){
                $this->error('内容长度不能小于'.$long_weibo_min_length.'！');
            }
            //权限判断
            $this->checkIsLogin();
            $this->checkAuth('Weibo/Index/editWeibo', -1,'你无操作权限！');
            $return = check_action_limit('edit_weibo', 'weibo', 0, is_login(), true);
            if ($return && !$return['state']) {
                $this->error($return['info']);
            }
            $oldWeibo=D('Weibo')->getWeiboDetail($aId);
            if(!$oldWeibo||$oldWeibo['type']!='long_weibo'){
                $this->error('该微博不存在！');
            }
            $res = D('Weibo')->editLongWeibo( $aPlainContent, $crowdId, $aContent,$aTitle,$aId);
            if (!$res) {
                $this->error('编辑失败 '.D('Weibo')->getError());
            }
            action_log('edit_weibo', 'weibo', $aId, is_login());

            if ($oldWeibo['crowd_id']!=$crowdId) {
                $weiboCrowdModel=D('Weibo/WeiboCrowd');
                if($oldWeibo['crowd_id']!=0){
                    $weiboCrowdModel->changeCrowdNum($crowdId,'post','dec');
                }
                if(!empty($crowdId)){
                    $weiboCrowdModel->changeCrowdNum($crowdId,'post','inc');
                }
            }
            S('weibo_' . $aId,null);
            clean_weibo_html_cache($aId);
            $this->ajaxReturn(array('info'=>'编辑成功！','status'=>1,'url'=>U('Weibo/index/weiboDetail',array('id'=>$aId))));
        }else{
            $aId=I('id',0,'intval');
            $weibo=D('Weibo/Weibo')->getWeiboDetail($aId);
            if(!$weibo||$weibo['type']!='long_weibo'){
                $this->error('非法操作！');
            }
            $this->checkAuth('Weibo/Index/editWeibo',$weibo['uid'],'你无操作权限！');
            $this->assign('weibo',$weibo);

            $this->userInfo($weibo['uid']);

            $supported = D('Weibo')->getSupportedPeople($weibo['id'], array('nickname', 'space_url', 'avatar128', 'space_link'), 12);
            $this->assign('supported', $supported);
            if ($weibo['type'] == 'long_weibo') {
                $this->setTitle($weibo['long_weibo']['title']. L('_LINE_LINE_') . L('_WEIBO_DETAIL_'));
            } else {
                $this->setTitle('{$weibo.content|op_t}' . L('_LINE_LINE_') . L('_WEIBO_DETAIL_'));
            }

            //圈子
            $followCrowd = D('WeiboCrowdMember')->getUserJoin(is_login());
            foreach ($followCrowd as $key => &$v) {
                $res = D('WeiboCrowd')->getCrowd($v['crowd_id']);
                $v['crowd'] = $res;
                if (empty($res)){
                    unset($followCrowd[$key]);
                }
                if($v['crowd']['id']==$weibo['crowd_id']){
                    $now_crowd=$v['crowd'];
                }
            }
            unset($v);
            $this->assign('follow_crowd_list',$followCrowd);
            $this->assign('now_crowd',$now_crowd);

            $this->assign('tab', 'index');
            $this->display();
        }
    }

    public function transferCrowdAdmin()
    {
        if (IS_POST) {
            $aCrowdId = I('post.crowd_id',0,'intval');
            $aUid = I('post.uid',0,'intval');
            $crowd = D('Weibo/WeiboCrowd')->getCrowd($aCrowdId);
            if (empty($crowd)) {
                $this->error('圈子不存在');
            }
            if (empty($aUid)) {
                $this->error('转移目标不能为空');
            }
            $toUid = query_user('nickname',$aUid);
            if (!$toUid) {
                $this->error('目标人物不存在');
            }
            $isJoin = D('Weibo/WeiboCrowdMember')->getIsJoin($toUid,$crowd['id']);
            if ($isJoin) {
                $this->error('目标未加入该圈子');
            }
            $crowdAdmin = D('Weibo/WeiboCrowdMember')->getCrowdAdmin($crowd['id']);
            if (is_login() != $crowdAdmin['uid']) {
                $this->error('只有圈子创始人才有转移管理权限');
            }
            $res = D('Weibo/WeiboCrowdMember')->transferCrowdAdmin($crowd['id'],$crowdAdmin['uid'],$aUid);
            if ($res) {
                S('crowd_by_'.$crowd['id'],null);
                send_message($aUid, "您已成为【{$crowd['title']}】圈子---圈子管理员", get_nickname(is_login()) . "转移了【{$crowd['title']}】权利权限给你，", 'Weibo/Crowd/Crowd', array('id' => $crowd['id']), is_login(), 'Weibo_crowd');
                $this->success('转移管理权限成功',U('Weibo/Index/index'));
            } else {
                $this->error('操作失败');
            }
        } else {
            $aCrowdId = I('get.crowd_id', 0, 'intval');
            $friendList = D('Follow')->getAllFriends(is_login());
            $friendIds = getSubByKey($friendList, 'follow_who');
            foreach ($friendIds as $key => $v) {
                $status = D('Weibo/WeiboCrowdMember')->getIsJoin($v,$aCrowdId);
                if ($status != 1) {
                    unset($friendIds[$key]);
                }
            }
            $friends = array();
            foreach ($friendIds as $v) {
                $friends[$v] = query_user(array('avatar128', 'avatar64', 'nickname', 'uid', 'space_url'), $v);
            }
            $this->assign('friends', $friends);
            $this->assign('crowd_id',$aCrowdId);
            $this->display();
        }
    }
}