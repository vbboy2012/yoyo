<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 2017/6/2
 * Time: 15:39
 */

namespace Project\Model;

use Think\Model;

class ProgressModel extends Model
{
    protected $tableName = 'project_progress';

    public function getProgress($id)
    {
        $tag = 'progress_by_'.$id;
        $progress = S($tag);
        if (empty($progress)) {
            $progress = $this->where(array('id'=>$id))->find();
            S($tag,$progress);
        }
        return $progress;
    }


    public function addProgress($data)
    {
        $res = $this->add($data);
        return $res;
    }

    public function editProgress($id,$data)
    {
        $res = $this->where(array('id'=>$id))->save($data);
        return $res;
    }

    public function setStatus($id,$status)
    {
        $res = $this->where(array('id'=>array('in',$id)))->save(array('status'=>$status));
        return $res;
    }

    public function lastProgress($project_id)
    {
        $progress = $this->where(array('project_id'=>$project_id))->order('create_time desc')->find();
        return $progress;
    }

    public function getCount($project_id){
        $count=$this->where(array('project_id'=>$project_id,'status'=>1))->count();
        return $count;
    }
}