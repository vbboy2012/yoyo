<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/06/15
 * Time: 9:33
 */

namespace Project\Model;


use Think\Model;

class FileModel extends Model
{
    protected $tableName = 'project_file';

    
    public function getFile($id){
        $tag = 'file_by'.$id;
        $list = S($tag);
        if(empty($list)){
            $list=$this->where(array('id'=>$id))->find();
            S($tag,$list);
        }
        return $list;
    }
    public function addFile($data)
    {
        $res = $this->add($data);
        return $res;
    }

    public function editFile($id,$data)
    {
        $res = $this->where(array('id'=>$id))->save($data);
        return $res;
    }

    public function setStatus($id,$status)
    {
        $res = $this->where(array('id'=>array('in',$id)))->save(array('status'=>$status));
        return $res;
    }
}