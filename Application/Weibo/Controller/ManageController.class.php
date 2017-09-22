<?php
/**
 * Created by PhpStorm.
 * User: 王杰
 * Date: 2016/9/7
 * Time: 19:45
 */
namespace Weibo\Controller;

use Think\Controller;

class ManageController extends BaseController
{
    private $crowdId = '';

    public function _initialize()
    {
        $aCrowdId = I('post.crowd_id', 0, 'intval');
        $this->crowdId = $aCrowdId;
        parent::_initialize();
        if(!is_login()){
            $this->error('请先登录');
        }
        $this->checkAuth('Weibo/Manage/*',get_crowd_admin($this->crowdId),'您没有管理圈子的权限');
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
                D('Weibo/WeiboCrowd')->changeCrowdNum($aCrowd);
                S('crowd_joined_'.$aUid,null);
                send_message($aUid, '您的加入圈子请求已通过', get_nickname($aUid) . '加入' . "【{$crowd['title']}】"."成功".$title,  'Weibo/Crowd/crowd', array('id' => $aCrowd, 'type' => 'member'), is_login(), 'Weibo_crowd');
                D('Weibo/WeiboCrowdScore')->addScore($crowd);
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
            $model->where(array('uid' => $aUid, 'crowd_id' => $this->crowdId));
            $data = $model->find();
            if ($data['position'] == 3) {
                $this->error('无法移除圈子管理员');
            }
            $res = $model->delete();
            if ($res) {
                D('Weibo/WeiboCrowd')->changeCrowdNum($aCrowd,'member','dec');
                S('crowd_joined_'.$aUid,null);
                send_message($aUid, '您已被移出圈子', get_nickname($aUid) . '被管理员移出圈子' . "【{$crowd['title']}】", 'Weibo/Crowd/member', array('id' => $aCrowd), is_login(), 'Weibo_crowd');
                $this->success('移除成功', U('Weibo/Crowd/crowd',array('id'=>$aCrowd)));
            } else {
                $this->error('移除失败');
            }
        }
    }

    public function delCrowd()
    {
        if (IS_POST) {
            $this->checkAuth('Weibo/Manager/dismiss',get_crowd_admin($this->crowdId),'您没有解散群组的权限');
            $crowd = D('WeiboCrowd')->getCrowd($this->crowdId);
            $userArray = D('WeiboCrowdMember')->where(array('crowd_id'=>$this->crowdId,'status'=>1))->field('uid')->select();
            $userArray = array_column($userArray,'uid');
            $res = D('WeiboCrowd')->delCrowd($this->crowdId);
            $data = D('WeiboCrowdMember')->delMember(array('crowd_id'=>$this->crowdId));
            $map=D('Weibo')->where(array('crowd_id'=>$this->crowdId,'status'=>1))->setField(array('crowd_id'=>0));
            if ($res !== false || $data !== false || $map !== false) {
                S('crowd_joined_'.is_login(),null);
                S('crowd_by_'.$this->crowdId,null);
                send_message($userArray, '您加入的圈子已被解散',  '您加入的圈子' . "【{$crowd['title']}】"."已被管理员解散，您已自动退出该圈子", 'Weibo/Crowd/index', '', is_login(), 'Weibo_crowd');
                $this->success('解散圈子成功',U('Index/index'));
            } else {
                $this->error('解散圈子失败');
            }
        }
    }
}