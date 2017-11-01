<?php
/**
 * Created by PhpStorm.
 * User: 王杰
 * Date: 2016/9/7
 * Time: 19:45
 */
namespace Weibo\Controller;

use Admin\Model\AuthGroupModel;
use Think\Controller;

class CrowdController extends BaseController
{
    /**
     * index   圈子首页
     *
     */
    public function index()
    {
        $type = D('WeiboCrowdType')->getCrowdTypes();
        $this->assign('type', $type);
        foreach ($type as &$v) {
            $param['where']['type_id'] = $v['id'];
            $param['where']['status'] = 1;
            $param['field'] = 'id';
            $v['list'] = D('WeiboCrowd')->getList($param);
            foreach ($v['list'] as &$item) {
                $item = D('WeiboCrowd')->getCrowd($item);
                $temp =  D('Ucenter/Score')->getType(array('status'=>1,'id'=>$item['pay_type']));
                $item['pay_type_title'] = $temp['title'];
                $item['is_follow'] = D('Weibo/WeiboCrowdMember')->getIsJoin(is_login(),$item['id']);
            }
        }
        unset($v);
        unset($item);
        //加入的圈子
        $followCrowd = D('WeiboCrowdMember')->getUserJoin(is_login());
        foreach ($followCrowd as $key => &$v) {
            $res = D('WeiboCrowd')->getCrowd($v['crowd_id']);
            $v['crowd'] = $res;
            if (empty($res)) {
                unset($followCrowd[$key]);
            }
        }
        unset($v);
        //我创建的圈子
        $myCreateCrowd = D('WeiboCrowdMember')->getMyCreateCrowd(is_login());
        foreach ($myCreateCrowd as $key => &$v) {
            $res = D('WeiboCrowd')->getCrowd($v['crowd_id']);
            $v['crowd'] = $res;
            if (empty($res)) {
                unset($myCreateCrowd[$key]);
            }
        }
        unset($v);
        $field = D('Ucenter/Score')->getTypeList(array('status' => 1));
        $this->assign("field", $field);
        $this->assign('follow_crowd_list', $followCrowd);
        $this->assign('my_create_crowd_list', $myCreateCrowd);
        $this->assign('list', $type);
        $this->display();
    }

    public function create()
    {
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
            if ($aTypeId == 0) {
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
                $temp = D('Weibo/WeiboCrowd')->where(array('id' => $aId))->find();
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
                D('Weibo/WeiboCrowdMember')->addMember(array('uid' => is_login(), 'crowd_id' => $result, 'status' => 1, 'position' => 3));
                D('Weibo/WeiboCrowd')->changeCrowdNum($result, 'member', 'inc');
                $temp = D('Weibo/WeiboCrowd')->where(array('id' => $result))->find();
                if (modC('CREATE_CROWD_CHECK', '0') == 1) {
                    send_message_without_check_self(is_login(), '等待圈子审核', "您创建的圈子" . "【{$temp['title']}】正在审核中,请等待", 'Weibo/Crowd/index', '', is_login(), 'Weibo_crowd');
                    send_message(array_column(AuthGroupModel::memberInGroup(6),'uid'), '等待圈子审核', get_nickname(is_login()) . "创建了圈子【{$temp['title']}】，请审核", 'Admin/Weibo/Crowd', array('status' => 2), is_login(), 'Weibo_crowd');
                }
            }
            //显示成功消息
            S('crowd_create_by_' . is_login(), null);
            S('crowd_joined_' . is_login(), null);
            S('private_crowd_by_invisible', null);
            $url = $isEdit ? U('Weibo/Crowd/crowd',array('id'=>$aId)).'#change' : U('Weibo/Crowd/index');
            $this->success($message, $url);
        }
    }

    public function crowd()
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

        $field = D('Ucenter/Score')->getTypeList(array('status' => 1));
        $this->assign("field", $field);

        $this->assign('crowd_id',$aId);
        $this->assign('list', $list);
        $this->assign('totalCount', $totalCount);
        $this->display(T('manage'));
    }

    public function member()
    {
        $aId = I('get.id', 0, 'intval');
        $aPage = I('get.page', 1, 'intval');
        $crowd = D('WeiboCrowd')->getCrowd($aId);
        if (!$crowd) {
            $this->error('圈子不存在');
        }
        $this->assign('crowd', $crowd);
        $map['status'] = 1;
        $map['crowd_id'] = $aId;
        $list = D('WeiboCrowdMember')->getMemberList($map, $aPage);
        foreach ($list as &$v) {
            $v['user'] = query_user(array('avatar128', 'avatar64', 'nickname', 'uid', 'space_url'), $v['uid']);
            $v['user_post_num'] = get_crowd_weibo_num($v['uid'], $aId);
        }
        unset($v);
        $totalCount = D('WeiboCrowdMember')->where($map)->count();
        $this->assign('list', $list);
        $this->assign('totalCount', $totalCount);
        $this->display();
    }

    public function gcard()
    {
        $this->display();
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
            $isJoin = D('Weibo/WeiboCrowdMember')->getIsJoin(is_login(),$crowdId);
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
                D('Weibo/WeiboCrowd')->changeCrowdNum($crowdId);
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
            if (!D('Weibo/WeiboCrowdMember')->getIsJoin(is_login(),$crowdId)) {
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
                    D('Weibo/WeiboCrowd')->changeCrowdNum($crowdId, 'member', 'dec');
                }
                S('crowd_joined_' . $uid, null);
                send_message($crowd['uid'], '退出圈子提醒', get_nickname($uid) . '已退出圈子' . "【{$crowd['title']}】", 'Ucenter/Index/information', array('uid' => $uid), $uid, 'Weibo_crowd');
                $this->success('退出圈子成功');
            } else {
                $this->error('操作失败');
            }
        }
    }

    public function card()
    {
        $crowdId = I('get.crowd', '', 'text');
        $crowd = D('WeiboCrowd')->getCrowd($crowdId);
        $crowd['is_follow'] = D('Weibo/WeiboCrowdMember')->getIsJoin(is_login(),$crowd['id']);
        $crowd['logo'] = getThumbImageById($crowd['logo'], 80, 80);
        $this->ajaxReturn(array('status' => 1, 'info' => $crowd));
    }

    private function _isLogin()
    {
        if (!is_login()) {
            $this->error('未登录');
        }
    }

    public function crowdInvite()
    {
        $aCrowdId = I('crowd_id', 0, 'intval');
        if (IS_POST) {
            $this->_isLogin();
            $uids = I('post.uids',0,'intval');
            $crowd = D('Weibo/WeiboCrowd')->getCrowd($aCrowdId);
            foreach ($uids as $uid) {
                if (D('Weibo/WeiboCrowdMember')->getIsJoin($uid,$aCrowdId) == 0) {
                    D('Weibo/WeiboCrowdMember')->addMember(array('uid' => $uid, 'crowd_id' => $aCrowdId, 'status' => -2, 'position' => 1));
                    send_message($uid, '圈子邀请', get_nickname(is_login()).'邀请你加入圈子【'.$crowd['title'].'】,点击加入', 'Weibo/Crowd/index', array(), is_login(), 'Weibo_crowd');
                }
            }
            $result = array('status' => 1, 'info' => L('_SUCCESS_INVITE_'));
            $this->ajaxReturn($result);
        } else {
            $friendList = D('Follow')->getAllFriends(is_login());
            $friendIds = getSubByKey($friendList, 'follow_who');
            foreach ($friendIds as $key => $v) {
                $status = D('Weibo/WeiboCrowdMember')->getIsJoin($v,$aCrowdId);
                if ($status != 0) {
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

    public function setContribution()
    {
        $aUid = I('post.uid',0,'intval');
        $aScore = I('post.score',0,'intval');
        $aCrowd = I('post.crowd',0,'intval');
        $isAdmin = check_auth('Weibo/Manage/*',get_crowd_admin($aCrowd));
        if (!$isAdmin) {
            $this->error('你不是圈子管理员');
        }
//        $res = D('Weibo/WeiboCrowdMember')->setContribution($aCrowd,$aUid,$aScore);
        $res = M('weibo_crowd_member')->where(array('crowd_id'=>$aCrowd,'uid'=>$aUid))->setField('contribution',$aScore);
        if ($res) {
            $this->success('变更成功');
        } else {
            $this->error('操作失败');
        }
    }
}