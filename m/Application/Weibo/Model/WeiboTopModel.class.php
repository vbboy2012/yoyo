<?php
/**
 * Created by PhpStorm.
 * User: 王杰
 * Date: 2017/1/17
 * Time: 10:19
 */
namespace Weibo\Model;

use Think\Model;

class WeiboTopModel extends Model
{
    public function addTop($weibo,$time = '',$title = '',$isCrowd = '',$type='')
    {
        $top = $this->where(array('weibo_id'=>$weibo['id']))->find() ;
        $time = $time ?: 2145916800;
        $type = $type ?: 'title' ;
        $title = $title ?: $weibo['content'];
        if($top == false){
            $data['weibo_id'] = $weibo['id'];
            $data['type'] = $type;
            $data['title'] = $title;
            $data['dead_time'] = $time;
            $data['create_time'] = time();
            $data['status'] = 1;
            $data = $this->create($data);
            S('top_list_ids' ,null);
            if (!$data) return false;

            $res = $this->add();
        }else{
            $save['dead_time'] = $time ;
            $save['type'] = $type ;
            $save['title'] = $title ;
            $save['status'] = 1 ;
            $res = $this->where(array('weibo_id'=>$weibo['id']))->save($save) ;
        }
        return $res;
    }

    public function delTop($weibo_id,$crowd_id = '')
    {
        $map['weibo_id'] = $weibo_id;
        if ($crowd_id) {
            $map['crowd_id'] = $crowd_id;
        } else {
            $map['crowd_id'] = 0;
        }
        $res = $this->where($map)->setField('status',0);
        $weibo = D('Weibo/Weibo')->getWeiboDetail($weibo_id);
        S('top_list_ids_'.$weibo['crowd_id'],null);
        return $res;
    }

    public function getTop($crowd = '')
    {
        $time = time();
        if (empty($crowd)) {
            $crowd = 0;
        }
        $top = $this->where(array('dead_time'=>array('gt',$time),'crowd_id'=>$crowd,'status'=>1))->order('create_time desc')->select();
        return $top;
    }

    public function getTopIds($crowd = '')
    {
        $ids = S('top_list_ids_'.$crowd);
        if (empty($ids)) {
            $time = time();
            if (empty($crowd)) {
                $crowd = 0;
            }
            $ids = $this->where(array('dead_time'=>array('gt',$time),'crowd_id'=>$crowd,'status'=>1))->select();
            S('top_list_ids_'.$crowd,$ids,60*60*2);
        }
        return array_column($ids,'weibo_id');
    }

    public function isTop($weibo_id,$crowd_id='')
    {
        if (!empty($crowd_id)) {
            $map['crowd_id'] = $crowd_id;
        } else {
            $map['crowd_id'] = 0;
        }
        $map['weibo_id'] = $weibo_id;
        $top = $this->where($map)->order('create_time desc')->find();
        if (empty($top)) {
            return false;
        } elseif ($top['dead_time'] < time()) {
            return false;
        } elseif ($top['status'] != 1) {
            return false;
        } else {
            return true;
        }
    }
}