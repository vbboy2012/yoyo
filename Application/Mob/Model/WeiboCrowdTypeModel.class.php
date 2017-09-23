<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/28
 * Time: 15:44
 */

namespace Mob\Model;

use Think\Model;

class WeiboCrowdTypeModel extends Model
{
    protected $tableName='weibo_crowd_type';

    public function getCrowdTypes(){
        $types = $this->where(array('status' => 1))->order('sort asc')->select();
        return $types;
    }


    public function getCrowdType($id){
        $type = S('crowd_type_'.$id);
        if(empty($type)){
            $type =  $this->where(array('id'=>$id,'status'=>1))->find();
            S('crowd_type_'.$id,$type,300);
        }
        return $type;
    }
}