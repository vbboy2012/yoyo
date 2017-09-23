<?php
/**
 * Created by PhpStorm.
 * User: 王杰
 * Date: 2016/12/7
 * Time: 11:07
 */
namespace Weibo\Model;

use Think\Model;

class WeiboCrowdMemberModel extends Model
{
    protected $tableName = 'weibo_crowd_member';

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
        array('activity', '0', self::MODEL_INSERT),
    );
    public function cleanUserCache($uid,$crowd_id)
    {

        $tag='crowd_is_join_'.$crowd_id.'_'.$uid;
        S($tag,null);
        $tag = 'crowd_joined_'.$uid;
        S($tag,null);
        $tag = 'crowd_create_by_'.$uid;
        S($tag,null);
    }

    public function addMember($data)
    {
        $data = $this->create($data);
        $res = $this->add($data);
        $this->cleanUserCache($data['uid'],$data['crowd_id']);
        return $res;
    }

    public function delMember($map)
    {

        $rs= $this->where($map)->delete();
        $this->cleanUserCache($map['uid'],$map['crowd_id']);
        return $rs;
    }

    public function getUserJoin($uid)
    {
        $tag = 'crowd_joined_'.$uid;
        $list = S($tag);
        if (empty($list)) {
            $list = $this->where(array('uid'=>$uid,'status'=>1))->select();
            S($tag,$list,60*60);
        }
        return $list;
    }

    public function getIsJoin($uid, $crowd_id)
    {
        $tag='crowd_is_join_'.$crowd_id.'_'.$uid;
        $status = S($tag);
        if($status===false){
            $check = $this->where(array('crowd_id' => $crowd_id, 'uid' => $uid))->limit(1)->find();
            if (!$check) {
                //未加入圈子
                $status = 0;
            } else {
                if ($check['status'] == 1) {
                    // 已加入圈子并已审核
                    $status = 1;
                } elseif ($check['status'] == -2) {
                    //被邀请
                    $status = -2;
                } else {
                    //未审核
                    $status = 2;
                }
            }
            S($tag,$status,60*60);
        }

        return $status;
    }

    public function setStatus($uid, $crowd_id, $status)
    {
        $res = $this->where(array('uid' => $uid, 'crowd_id' => $crowd_id))->save(array('status'=>$status,'update_time'=>time()));
        return $res;
    }

    public function delApply($uid, $crowd_id)
    {
        $res = $this->where(array('uid' => $uid, 'crowd_id' =>$crowd_id))->delete();
        return $res;
    }

    /**
     * 后台设置圈子状态时，改变圈子加入列表成员状态
     */
    public function setStatusByAdmin($map,$data)
    {
        $res = $this->where($map)->save($data);
        return $res;
    }

    public function getCrowdAdmin($crowd_id)
    {
        $data = $this->where(array('crowd_id'=>$crowd_id,'position'=>3))->find();
        return $data;
    }

    public function getCrowdAllAdmin()
    {
        $admin = S('crowd_all_admin');
        if (empty($admin)) {
            $admin = $this->distinct(true)->field('uid')->where(array('status'=>1,'position'=>3))->select();
            S('crowd_all_admin',$admin,60*60*2);
        }
        return $admin;
    }

    public function getMemberList($map = array(),$page,$r = 10, $order = 'position desc , create_time asc')
    {
        $list = $this->where($map)->order($order)->page($page, $r)->select();
        return $list;
    }

    public function getMycreateCrowd($uid)
    {
        $tag = 'crowd_create_by_'.$uid;
        $list = S($tag);
        if (empty($list)) {
            $list = $this->where(array('status'=>1,'uid'=>$uid,'position'=>3))->select();
            S($tag,$list,60*60);
        }
        return $list;
    }

    public function getMember($crowdId)
    {
        //todo 优化
        $res = $this->field('uid')->where(array('crowd_id'=>$crowdId))->select();
        return $res;
    }

    public function getMemberNum($crowdId)
    {
        $res = $this->where(array('crowd_id'=>$crowdId,'status'=>1))->count('distinct(uid)');
        return $res;
    }

    public function setContribution($crowdId,$uid,$score)
    {
        $res = $this->where(array('crowd_id'=>$crowdId,'uid'=>$uid))->setField('contribution',$score);
        return $res;
    }

    public function transferCrowdAdmin($crowdId ,$crowdAdminUid ,$toUid)
    {
        $res = $this->where(array('crowd_id'=>$crowdId,'uid'=>$toUid))->setField('position',3);
        if ($res) {
            D('Weibo/WeiboCrowd')->where(array('id'=>$crowdId))->setField('uid',$toUid);
            return $this->where(array('crowd_id'=>$crowdId,'uid'=>$crowdAdminUid))->setField('position',1);
        } else {
            return false;
        }
    }
    public function getMyJoinCrowd($page,$uid){
        $tag='my_join_crowd_list_'.$uid.'_'.$page;
        $crowd=S($tag);
        if(empty($corwd)){
            $crowd = $this->where(array('status' => 1,'uid'=>$uid))->page($page,10)->select();
            S($tag, $crowd , 60*60);
        }
        return $crowd;
    }
}