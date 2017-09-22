<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/28
 * Time: 15:45
 */

namespace Mob\Model;

use Think\Model;

class WeiboCrowdModel extends Model
{
    protected $tableName = 'weibo_crowd';

    public function getAllCrowd()
    {
        $tag = 'all_crowd_list';
        $crowd = S($tag);
        if(empty($crowd)){
            $crowd = $this->where(array('status' => 1))->select();
            S($tag, $crowd , 60*60*24);
        }
        return $crowd;
    }
    
    public function getCrowd($crowd_id)
    {
        $tag = 'crowd_by_'.$crowd_id;
        $crowd = S($tag);
        if (empty($crowd)) {
            $crowd = $this->where(array('id'=>$crowd_id,'status'=>1))->find();
            S($tag,$crowd,60*60);
        }
        return $crowd;
    }

    public function delCrowd($crowd_id)
    {
        $res = $this->where(array('id' => $crowd_id))->setField(array('status' => -1, 'member_count' => 0));
        return $res;
    }

    public function getRank()
    {
        $tag = 'crowd_rank';
        $rank = S($tag);
        if (empty($crowd)) {
            $rank = $this->where(array('status'=>1))->order('member_count desc')->limit(5)->select();
            S($tag,$rank,60*60);
        }
        return $rank;
    }

    public function setMemberNum($crowd_id)
    {
        $num = D('Weibo/WeiboCrowdMember')->getMemberNum($crowd_id);
        $this->where(array('id'=>$crowd_id))->setField('member_count',$num);
    }

    public function crowdDefaultFollow($uid,$crowd,$data,$type = 0)
    {
        $member = D('Weibo/WeiboCrowdMember')->addMember($data);
        $content = "系统已默认将您加入" . "【{$crowd['title']}】";
        $admin_content = "系统默认将" .get_nickname($uid) .  "加入【{$crowd['title']}】";
        $member_type = 'member';
        if ($member) {
            if ($type == 1) {
                $content .= ",等待管理员审核";
                $admin_content .= ",请审核";
                $member_type = 'check';
            }
            send_message_without_check_self($uid, '系统默认加入圈子提醒', $content, 'Weibo/Index/index', array('crowd' => $crowd['id']), $crowd['uid'], 'Weibo_crowd');
            send_message($crowd['uid'], '默认加入圈子提醒', $admin_content , 'Weibo/Crowd/crowd', array('id' => $crowd['id'], 'type' => $member_type), $uid, 'Weibo_crowd');
            S('crowd_joined_' . $uid, null);
            $this->changeCrowdNum($crowd['id']);
        }
    }

    public function changeCrowdNum($crowdId,$num_type = 'member',$type = 'inc')
    {
        $type = $type == 'inc' ? 'inc' : 'dec' ;
        $num_type = $num_type == 'member' ? 'member' : 'post';
        $model = D('Weibo/WeiboCrowd');
        $model -> where(array('id'=>$crowdId));
        if ($type == 'inc') {
            $model->setInc($num_type.'_count');
        } elseif ($type == 'dec') {
            $model->setDec($num_type.'_count');
        }
        S('crowd_by_'.$crowdId,null);
    }

    public function getInvisible()
    {
        //全站微博不可见的私有圈子
        $tag = 'private_crowd_by_invisible';
        $list = S($tag);
        if (empty($list)) {
            $list = $this->where(array('status'=>1,'invisible'=>1,'type'=>'1'))->select();
            S($tag,$list,60*60);
        }
        return $list;
    }
}