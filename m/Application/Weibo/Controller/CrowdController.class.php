<?php
/**
 * Created by PhpStorm.
 * User: 王杰 wj@ourstu.com
 * Date: 2016/12/7
 * Time: 11:07
 */
namespace Weibo\Controller;

use Think\Controller;

class CrowdController extends BaseController
{
    public function _initialize()
    {
        parent::_initialize();
        $this->setTitle("圈子");
        $this->setKeywords("圈子");
        $this->setDescription("圈子");
        $this->assign('bottom_flag', 'crowd');
    }

    public function crowd()
    {

        $data=S(get_uid().'_crowds');

        if($data===false){

            //创建
            $myCreateCrowd = D('WeiboCrowdMember')->getMyCreateCrowd(is_login());
            foreach ($myCreateCrowd as $key => &$v) {
                $res = D('WeiboCrowd')->getCrowd($v['crowd_id']);
                $v['crowd'] = $res;
                $v['crowd']['is_follow'] = D('WeiboCrowdMember')->getIsJoin(is_login(), $v['crowd']['id']);
                if (empty($res)) {
                    unset($myCreateCrowd[$key]);
                }
            }
            unset($v);
            //$this->assign('create_list', $myCreateCrowd);
            $data['create_list']=$myCreateCrowd;
            //加入
            $followCrowd = D('WeiboCrowdMember')->getUserJoin(is_login());
            foreach ($followCrowd as $key => &$v) {
                $res = D('WeiboCrowd')->getCrowd($v['crowd_id']);
                $v['crowd'] = $res;
                $v['crowd']['is_follow'] = D('WeiboCrowdMember')->getIsJoin(is_login(), $v['crowd']['id']);
                if (empty($res)) {
                    unset($followCrowd[$key]);
                }
            }
            unset($v);
            //$this->assign('follow_list', $followCrowd);
            $data['follow_list']=$followCrowd;

            $field = D('Ucenter/Score')->getTypeList(array('status' => 1));
            //$this->assign("field", $field);
            $data['field']=$field;
            S(get_uid().'_crowds',$data,600);
        }
        $this->assign($data);

        $crowdList=S('crowd_list');
        if($crowdList===false){
            //全部圈子
            $crowdList = D('WeiboCrowd')->getAllCrowd();
            foreach ($crowdList as &$val) {
                $val['crowd'] = D('WeiboCrowd')->getCrowd($val['id']);
                $val['crowd']['is_follow'] = D('WeiboCrowdMember')->getIsJoin(is_login(), $val['crowd']['id']);
            }
            unset($val);

            S('crowd_list',$crowdList);
        }
        $this->assign('crowd_list', $crowdList);

        //按分类获取圈子
        $list = $this->getCrowdsByType();
        $this->assign('crowd_type', $list);
        $crowdTypeCount = M('weibo_crowd_type')->where(array('status' => 1))->count();
        $this->assign('type_count', $crowdTypeCount);
        
        //按显示获取圈子
        $showList = $this->getCrowdByShow(1);
        $this->assign('show_list', $showList);
        $this->display();
    }

    private function getCrowdsByType()
    {
        $crowdType = D('WeiboCrowdType')->getCrowdTypes();
        foreach ($crowdType as &$val) {
            $val['crowd'] = D('WeiboCrowd')->getCrowdByType($val['id']);
            foreach ($val['crowd'] as &$value) {
                $value['is_follow'] = D('WeiboCrowdMember')->getIsJoin(is_login(), $value['id']);
            }
        }
        unset($val);
        unset($value);

        return $crowdType;
    }

    private function getCrowdByShow($is_show)
    {
        $crowdType = D('WeiboCrowdType')->getCrowdTypes();
        foreach ($crowdType as &$val) {
            $val['crowd'] = D('WeiboCrowd')->getCrowdByType($val['id'], $is_show);
            foreach ($val['crowd'] as &$value) {
                $value['is_follow'] = D('WeiboCrowdMember')->getIsJoin(is_login(), $value['id']);
            }
        }
        unset($val);
        unset($value);

        return $crowdType;
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
            $aOderType = I('post.order_type', 0, 'intval');
            $aLogo = I('post.image', 0, 'intval');
            $aTypeId = I('post.type_id', 0, 'intval');
            $aIntro = I('post.intro', '', 'text');
            $aNotice = I('post.notice', '', 'text');
            $aPayType = I('post.pay_type', 0, 'intval');
            $aInvisible = I('post.invisible', 0, 'intval');
            $needPay = 0;


            if (!empty($aPayType)) {
                $aPayNum = I('post.pay_num', 0, 'intval');
                if ($aPayNum <= 0) {
                    $this->error('付费不能小于0');
                }
                $needPay = $aPayNum;
            }
            if (empty($aTitle)) {
                $this->error('请填写圈子标题');
            }
            if (utf8_strlen($aTitle) > 20) {
                $this->error('圈子名称最多20个字');
            }
            if (empty($aTypeId)) {
                $this->error('请选择圈子分类');
            }
            if (empty($aIntro)) {
                $this->error('请填写圈子介绍');
            }
            $status = 1;
            $isEdit = $aId ? true : false;
            $flag = 0;
            if ($aId) {
                $crowd = D('WeiboCrowd')->getCrowd($aId);
                $flag = 1;
                foreach (I('post.') as $key => $val) {
                    if (!empty($crowd[$key]) && $key != 'notice') {
                        if (I('post.' . $key) != $crowd[$key]) {
                            $flag = $flag && 0;
                        }
                    }
                }


            }

            $message = $isEdit ? '编辑成功' : '发布成功';
            if (modC('CREATE_CROWD_CHECK', '0') == 1 && !$flag) {
                $message .= ',等待管理员审核';
                $status = 2;
            }
            $aInvisible = $aType == 0 ? 0 : $aInvisible;
            $data = array('title' => $aTitle, 'create_time' => time(), 'status' => $status, 'allow_user_post' => $aAllowUserPost, 'order_type' => $aOderType, 'logo' => $aLogo, 'type_id' => $aTypeId, 'intro' => $aIntro, 'notice' => $aNotice, 'type' => $aType, 'pay_type' => $aPayType, 'invisible' => $aInvisible);
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
                    $return['status'] = 0;
                    $return['info'] = L('_ERROR_CREATE_FAIL_');
                    $this->ajaxReturn($return);
                }
                S('crowd_by_' . $aId, null);
                $temp = D('WeiboCrowd')->where(array('id' => $aId))->find();
                if (modC('CREATE_CROWD_CHECK', '0') == 1) {
                    //todo 通知url
                    send_message_without_check_self(is_login(), '等待圈子修改审核', "您修改的圈子" . "【{$temp['title']}】正在审核中,请等待", '', '', is_login(), 'Weibo_crowd');
//                    send_message(array_column(AuthGroupModel::memberInGroup(6),'uid'), '等待圈子审核', get_nickname(is_login()) . "创建了圈子【{$temp['title']}】，请审核", '', array('status' => 2), is_login(), 'Weibo_crowd');
                }
            } else {
                $data['need_pay'] = $needPay;
                $data['pay_type'] = $aPayType;
                $data = $model->create($data);
                $data['uid'] = is_login();
                $result = $model->add($data);
                if (!$result) {
                    $return['status'] = 0;
                    $return['info'] = L('_ERROR_CREATE_FAIL_');
                    $this->ajaxReturn($return);
                }
                //添加创建者成员
                D('WeiboCrowdMember')->addMember(array('uid' => is_login(), 'crowd_id' => $result, 'status' => 1, 'position' => 3));
                D('WeiboCrowd')->changeCrowdNum($result, 'member', 'inc');
                $temp = D('WeiboCrowd')->where(array('id' => $result))->find();
                if (modC('CREATE_CROWD_CHECK', '0') == 1) {
                    //todo  通知url
                    send_message_without_check_self(is_login(), '等待圈子审核', "您创建的圈子" . "【{$temp['title']}】正在审核中,请等待", 'Weibo/Crowd/index', '', is_login(), 'Weibo_crowd');
//                    send_message(array_column(AuthGroupModel::memberInGroup(6),'uid'), '等待圈子审核', get_nickname(is_login()) . "创建了圈子【{$temp['title']}】，请审核", 'Admin/Weibo/Crowd', array('status' => 2), is_login(), 'Weibo_crowd');
                }
            }
            //显示成功消息
            S('crowd_create_by_' . is_login(), null);
            S('crowd_joined_' . is_login(), null);
            S('all_crowd_list', null);
            $url = $isEdit ? U('Weibo/Crowd/crowd', array('id' => $aId)) : U('Weibo/Crowd/crowd');
//            $this->success($message, $url);
            $return['status'] = 1;
            $return['info'] = $message;
            $return['url'] = $url;
            $this->ajaxReturn($return);
        } else {
            $aId = I('get.crowd_id', 0, 'intval');
            $crowd = D('WeiboCrowd')->getCrowd($aId);
            if ($crowd['logo'] > 0) {
                $image = 1;
                $this->assign('logo', $image);
            }

            if ($aId) {
                $list['type_id'] = D('WeiboCrowdType')->getCrowdType($crowd['type_id']);
                $this->assign('list', $list);
            }

            $this->assign('crowd', $crowd);
            $this->display(T('addcrowd'));

        }

    }

    private function _isLogin()
    {
        if (!is_login()) {
            $data['status'] = 0;
            $data['info'] = '未登录';
            $this->ajaxReturn($data);
        }
    }

    public function attend()
    {
        if (IS_POST) {
            $crowdId = I('post.crowd_id', 0, 'intval');
            $uid = is_login();
            $num = count(D('WeiboCrowdMember')->getUserJoin($uid));
            if (empty($crowdId)) {
                $data['status'] = 0;
                $data['info'] = '参数错误';
                $this->ajaxReturn($data);
            }
            if (!$uid) {
                $data['status'] = 0;
                $data['info'] = '未登录';
                $this->ajaxReturn($data);
            }
            if (!crowd_exists($crowdId)) {
                $data['status'] = 0;
                $data['info'] = '圈子不存在';
                $this->ajaxReturn($data);
            }
            $isJoin = D('WeiboCrowdMember')->getIsJoin(is_login(), $crowdId);
            switch ($isJoin) {
                case 1:
                    $data['status'] = 0;
                    $data['info'] = '已加入该圈子';
                    $this->ajaxReturn($data);
                    break;
                case 2:
                    $data['status'] = 0;
                    $data['info'] = '正在审核中';
                    $this->ajaxReturn($data);
                    break;
            }
            if ($num >= modC('JOIN_CROWD_NUM', '5', 'Weibo')) {
                $data['status'] = 0;
                $data['info'] = '超出加入圈子上限';
                $this->ajaxReturn($data);
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
                $res = D('WeiboCrowdMember')->where(array('uid' => is_login(), 'crowd_id' => $crowdId))->save(array('status' => $data['status']));
            }
            if ($res) {
                if ($crowd['type'] == 1) {
                    send_message($crowd['uid'], '加入圈子审核', get_nickname($uid) . '请求加入圈子' . "【{$crowd['title']}】", 'Weibo/Crowd/crowdmanager', array('id' => $crowdId), $uid, 'Weibo_crowd');
                    S('crowd_joined_' . $uid, null);
                    $this->ajaxReturn(array('status' => 2, 'info' => '加入圈子成功，等待管理员审核！'));
                }
                S('crowd_joined_' . $uid, null);
                D('WeiboCrowd')->changeCrowdNum($crowdId);
                send_message($crowd['uid'], '加入圈子提醒', get_nickname($uid) . '已加入圈子' . "【{$crowd['title']}】", 'Weibo/Crowd/crowdmanager', array('id' => $crowdId), $uid, 'Weibo_crowd');
                $data['status'] = 1;
                $data['info'] = '加入圈子成功';
            } else {
                $data['status'] = 0;
                $data['info'] = '操作失败';
            }
            $this->ajaxReturn($data);
        }
    }

    public function quit()
    {
        if (IS_POST) {
            $crowdId = I('post.crowd_id', 0, 'intval');
            $uid = is_login();
            if (empty($crowdId)) {
                $data['status'] = 0;
                $data['info'] = '参数错误';
                $this->ajaxReturn($data);
            }
            if (!$uid) {
                $data['status'] = 0;
                $data['info'] = '未登录';
                $this->ajaxReturn($data);
            }
            if (!crowd_exists($crowdId)) {
                $data['status'] = 0;
                $data['info'] = '圈子不存在';
                $this->ajaxReturn($data);
            }
            if (!D('WeiboCrowdMember')->getIsJoin(is_login(), $crowdId)) {
                $data['status'] = 0;
                $data['info'] = '并未加入该圈子';
                $this->ajaxReturn($data);
            }
            $list = D('WeiboCrowdMember')->getMycreateCrowd($uid);
            $ids = getSubByKey($list, 'crowd_id');
            if (in_array($crowdId, $ids)) {
                $data['status'] = 0;
                $data['info'] = '圈子创始人只能解散圈子';
                $this->ajaxReturn($data);
            }
            $data['crowd_id'] = $crowdId;
            $data['uid'] = $uid;
            $crowd = D('WeiboCrowd')->getCrowd($crowdId);
            $res = D('WeiboCrowdMember')->delMember(array('crowd_id' => $crowdId, 'uid' => $uid));
            if ($res) {
                if ($crowd['member_count'] > 0) {
                    D('WeiboCrowd')->changeCrowdNum($crowdId, 'member', 'dec');
                }
                S('crowd_joined_' . $uid, null);
                send_message($crowd['uid'], '退出圈子提醒', get_nickname($uid) . '已退出圈子' . "【{$crowd['title']}】", 'Ucenter/Index/mine', array('uid' => $uid), $uid, 'Weibo_crowd');
                $data['status'] = 1;
                $data['info'] = '退出圈子成功';
            } else {
                $data['status'] = 0;
                $data['info'] = '操作失败';
            }
            $this->ajaxReturn($data);
        }
    }

    public function crowdManager()
    {
        $aId = I('get.id', 0, 'intval');
        $aType = I('get.type', 'check', 'text');
        $aPage = I('get.page', 1, 'intval');
        $aIsPull = I('get.is_pull', '', 'text');
        $crowd = D('WeiboCrowd')->getCrowd($aId);
        if (!$crowd) {
            $data['status'] = 0;
            $data['info'] = '圈子不存在';
            $this->ajaxReturn($data);
        }
        $isAdmin = check_auth('Weibo/Manage/*', get_crowd_admin($aId));
        if (!$isAdmin) {
            $data['status'] = 0;
            $data['info'] = '你不是圈子管理员';
            $this->ajaxReturn($data);
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

        $field = D('Ucenter/Score')->getTypeList(array('status' => 1));
        $this->assign("field", $field);

        $this->assign('crowd_id', $aId);
        $this->assign('member_list', $list);
        $this->assign('totalCount', $totalCount);
        if($aIsPull) {
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
            $aCrowd = I('post.crowd_id', 0, 'intval');
            $crowd = D('WeiboCrowd')->getCrowd($aCrowd);
            $title = '';
            if ($crowd['need_pay'] > 0) {
                $money = query_user('score' . $crowd['pay_type'], $aUid);
                if ($money['score' . $crowd['pay_type']] > $crowd['need_pay']) {
                    D('Ucenter/Score')->setUserScore($aUid, $crowd['need_pay'], $crowd['pay_type'], 'dec', 'weibo');
                    $title = '，并支付了' . $crowd['need_pay'] . $crowd['pay_type_title'];
                } else {
                    send_message($aUid, '余额不足', get_nickname($aUid) . $crowd['pay_type_title'] . '余额不足,加入' . "【{$crowd['title']}】" . "失败,请获得该类积分后再来", 'Weibo/Crowd/crowd', array('id' => $aCrowd, 'type' => 'member'), is_login(), 'Weibo_crowd');
                    D('Weibo/WeiboCrowdMember')->delMember(array('crowd_id' => $aCrowd, 'uid' => $aUid));
                    $this->error('该成员余额不足,无法支付入圈费！', 'refresh');
                }
            }
            $res = D('WeiboCrowdMember')->setStatus($aUid, $aCrowd, 1);
            if ($res) {
                D('WeiboCrowd')->changeCrowdNum($aCrowd);
                S('crowd_joined_' . $aUid, null);
                send_message($aUid, '您的加入圈子请求已通过', get_nickname($aUid) . '加入' . "【{$crowd['title']}】" . "成功" . $title, 'Weibo/Index/index', array('crowd_id' => $aCrowd), is_login(), 'Weibo_crowd');
                D('WeiboCrowdScore')->addScore($crowd);
                $data['status'] = 1;
                $data['info'] = '审核成功';
                $data['url'] = 'refresh';
            } else {
                $data['status'] = 0;
                $data['info'] = '审核失败';
            }
            $this->ajaxReturn($data);
        }
    }

    public function refuseMember()
    {
        if (IS_POST) {
            $aUid = I('post.uid', 0, 'intval');
            $aCrowd = I('post.crowd_id', 0, 'intval');
            $crowd = D('WeiboCrowd')->getCrowd($aCrowd);
            $title = '';
            $res = D('WeiboCrowdMember')->delApply($aUid, $aCrowd);
            if ($res) {
                send_message($aUid, '您的加入圈子请求未通过', "【{$crowd['title']}】的管理员拒绝了你的申请", 'Weibo/Crowd/crowd', '', is_login(), 'Weibo_crowd');
                $data['status'] = 1;
                $data['info'] = '审核成功';
                $data['url'] = 'refresh';
            } else {
                $data['status'] = 0;
                $data['info'] = '审核失败';
            }
            $this->ajaxReturn($data);
        }
    }

    public function removeMember()
    {
        if (IS_POST) {
            $aUid = I('post.uid', 0, 'intval');
            $aCrowd = I('post.crowd_id', 0, 'intval');
            $crowd = D('WeiboCrowd')->getCrowd($aCrowd);
            $model = D('WeiboCrowdMember');
            $model->where(array('uid' => $aUid, 'crowd_id' => $aCrowd));
            $data = $model->find();
            if ($data['position'] == 3) {
                $data['status'] = 0;
                $data['info'] = '无法移除圈子管理员';
                $this->ajaxReturn($data);
            }
            $res = $model->delete();
            if ($res) {
                D('WeiboCrowd')->changeCrowdNum($aCrowd, 'member', 'dec');
                S('crowd_joined_' . $aUid, null);
                send_message($aUid, '您已被移出圈子', get_nickname($aUid) . '被管理员移出圈子' . "【{$crowd['title']}】", 'weibo/index/index', array('crowd_id' => $aCrowd), is_login(), 'Weibo_crowd');
                $data['status'] = 1;
                $data['info'] = '移除成功';
                $data['url'] = 'refresh';
            } else {
                $data['status'] = 0;
                $data['info'] = '移除失败';
            }
            $this->ajaxReturn($data);
        }
    }

    public function delCrowd()
    {
        if (IS_POST) {
            $aCrowdId = I('post.crowd_id', 0, 'intval');
            $this->checkAuth('Weibo/Manager/dismiss', get_crowd_admin($aCrowdId), '您没有解散群组的权限');
            $crowd = D('WeiboCrowd')->getCrowd($aCrowdId);
            $userArray = D('WeiboCrowdMember')->where(array('crowd_id' => $aCrowdId, 'status' => 1))->field('uid')->select();
            $userArray = array_column($userArray, 'uid');
            $res = D('WeiboCrowd')->delCrowd($aCrowdId);
            $data = D('WeiboCrowdMember')->delMember(array('crowd_id' => $aCrowdId));
            $map = D('Weibo')->where(array('crowd_id' => $aCrowdId, 'status' => 1))->setField(array('crowd_id' => 0));
            if ($res !== false || $data !== false || $map !== false) {
                S('crowd_joined_' . is_login(), null);
                S('crowd_by_' . $aCrowdId, null);
                S('all_crowd_list', null);
                send_message($userArray, '您加入的圈子已被解散', '您加入的圈子' . "【{$crowd['title']}】" . "已被管理员解散，您已自动退出该圈子", 'Weibo/Crowd/crowd', '', is_login(), 'Weibo_crowd');
                $msg['status'] = 1;
                $msg['info'] = '解散圈子成功';
                $msg['url'] = U('Weibo/Crowd/crowd');
            } else {
                $msg['status'] = 0;
                $msg['info'] = '解散圈子失败';
            }
            $this->ajaxReturn($msg);
        }
    }
}
