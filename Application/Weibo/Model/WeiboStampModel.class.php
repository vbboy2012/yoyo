<?php
/**
 * Created by PhpStorm.
 * User: 王杰
 * Date: 2017/3/6
 * Time: 14:28
 */
namespace Weibo\Model;

use Think\Model;

class WeiboStampModel extends Model
{

    protected $tableName = 'stamp';

    public function getStamp($stamp_id)
    {
        $tag = 'stamp_by_'.$stamp_id;
        $stamp = S($tag);
        if (empty($stamp)) {
            $stamp = $this->where(array('id'=>$stamp_id))->find();
            if ($stamp['type'] == 'system') {
                $stamp['stamp_img'] = get_pic_src("Public/images/stamp/stamp".$stamp['pic'].".png");
            } else {
                $stamp['stamp_img'] = getThumbImageById($stamp['pic'],80,50);
            }
            S($tag,$stamp,60*60*6);
        }
        return $stamp;
    }

    public function getStampList()
    {
        $stampList = $this->where(array('status'=>1))->select();
        foreach ($stampList as &$v) {
            if ($v['type'] == 'system') {
                $v['stamp_img'] = get_pic_src("Public/images/stamp/stamp".$v['pic'].".png");
            } else {
                $v['stamp_img'] = getThumbImageById($v['pic'],80,50);
            }
        }
        unset($v);
        return $stampList;
    }

    public function setStatus($id,$status)
    {
        $res = $this->where(array('id'=>array('in',$id)))->save(array('status'=>$status));
        return $res;
    }

    public function addStamp($data)
    {
        $res = $this->add($data);
        return $res;
    }

    public function editStamp($id,$data)
    {
        $res = $this->where(array('id'=>$id))->save($data);
        return $res;
    }
}