<?php
/**
 * Created by PhpStorm.
 * User: 王杰
 * Date: 2017/1/25
 * Time: 13:46
 */

namespace Common\Model;


use Think\Model;

class CollectModel extends Model
{
  protected $tableName = 'collect';

  public function getCollection($module = '',$uid = '',$page = 1 ,$count = 10)
  {
      $uid = empty($uid) ? is_login()  : $uid;
      if ( !empty($module) ) {
          $map['module'] = $module;
      }
      $map['uid'] = $uid;
      $collects = $this->where($map)->page($page,$count)->select();
      return $collects;
  }

  public function isCollect($map = array())
  {
      return $this->where($map)->count();
  }
}