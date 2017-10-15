<?php
/**
 * Created by PhpStorm.
 * User: 王杰
 * Date: 2017/6/2
 * Time: 10:22
 */

namespace Project\Model;

use Think\Model;

class ProjectModel extends Model
{
    protected $tableName = 'project_lists';

    public function getProject($id)
    {
        $tag = 'project_by_'.$id;
        $project = S($tag);
        if (empty($project)) {
            $project = $this->where(array('id'=>$id))->find();
            S($tag,$project);
        }
        return $project;
    }

    public function addProject($data)
    {
        $res = $this->add($data);
        return $res;
    }

    public function editProject($id,$data)
    {
        $res = $this->where(array('id'=>$id))->save($data);
        return $res;
    }

    public function setStatus($id,$status)
    {
        $res = $this->where(array('id'=>array('in',$id)))->save(array('status'=>$status));
        return $res;
    }

    public function getTicket($map){

        $model=D('ticket_list');
        $map['uid']=is_login();
        $tag='ticket-by'.$map['uid'];
        $res=S($tag);
        if(empty($res)){
            $res=$model->where(array($map))->select();
            $data=get_ticket_status($res);
            S($tag,$data);
        }
        return $data;

    }
} 