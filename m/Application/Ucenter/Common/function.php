<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 前台公共库文件
 * 主要定义前台公共函数库
 */




/**
 * 检测验证码
 * @param  integer $id 验证码ID
 * @return boolean     检测结果
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function count2str($count){
    if($count>9999){
        $count=number_format($count/10000,1).'万';
        return $count;
    }
    else{
        return $count;
    }

}

function check_verify($code, $id = 1)
{
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}

function is_weixin()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        return true;
    }
    return false;
}

function wx_pay($status = 0,$out_trade_no = '',$openId = '' ,$uid = 0)
{
    M('wx_pay_log')->add(array(
        'uid'=>$uid,
        'open_id'=>$openId,
        'trade_no'=>$out_trade_no,
        'create_time' => time(),
        'status' => $status
    ));
}

/**广场页面根据要求获取标准列表
 * @param $table           数据表名
 * @param $cache           缓存名称
 * @param $config          后台配置名
 * @param string $_pk      主键名
 * @param array $where     查询条件
 * @param string $deOrder  获取后台设置列表的排序规则
 * @param string $order    获取补足后台设置列表的排序规则
 * @param int $limit       记录数
 * @return array|mixed     获取到的列表
 * @author szh(施志宏) szh@ourstu.com
 */
function get_square_list($table, $cache, $config, $_pk='id', $where=array(), $deOrder='', $order='', $limit=2) {
    $listResult = S($cache);
    if($listResult == false){
        $model = M($table) ;
        $ids = modC($config,'');
        $count = 0 ;
        $idArray = array() ;
        if($ids != ""){
            $idArray = explode(',', $ids);
            $idArray = array_filter($idArray) ;
            $map = $where ;
            $map[$_pk] = array('in', $idArray) ;
            $listResult = $model->where($map)->order($deOrder)->select();
            $count = count($listResult) ;
        }
        if($count < $limit){
            $limit = $limit - $count ;
            if($count > 0) {
                $where[$_pk] = array('not in', $idArray) ;
            }
            $list = $model->where($where)->order($order)->limit($limit)->select();
            if($list != false){
                if($listResult == false) $listResult = array() ;
                $listResult = array_merge($listResult, $list) ;
            }
        }else{
            shuffle($listResult) ;
            $listResult = array_slice($listResult, 0, $limit) ;
        }
        S($cache, $listResult, 60*60) ;
    }
    return $listResult ;
}

/**广场页面获取热门动态列表
 * @param int $limit
 * @return array|mixed
 * @author szh(施志宏) szh@ourstu.com
 */
function get_square_weibo($limit=2) {
    $listResult = S('weiboResult');
    if($listResult == false){
        $model = M('weibo') ;
        $ids = modC('SET_WEIBO', '');
        $count = 0 ;
        $idArray = array() ;
        if($ids != ""){
            $idArray = explode(',', $ids);
            $idArray = array_filter($idArray) ;
            $count = count($idArray) ;
        }
        if($count < $limit){
            $where['status'] = 1 ;
            if(!empty($idArray)){
                $where['id'] = array('not in', $idArray) ;
            }
            $list = $model->where($where)->order('comment_count desc')->limit($limit)->field('id')->select();
            $list = array_column($list, 'id') ;
            if($list != false){
                if($idArray == false) $idArray = array() ;
                $idArray = array_merge($idArray, $list) ;
            }
        }else{
            shuffle($idArray) ;
            $idArray = array_slice($idArray, 0, $limit) ;
        }
        $listResult = array() ;
        foreach ($idArray as $key=>$val) {
            $listResult[$key] = D('Weibo/Weibo')->getWeiboDetail($val);
            $listResult[$key]['is_follow'] = D('Common/Follow')->isFollow(is_login(), $listResult[$key]['uid']);
        }
        unset($val) ;
        S('weiboResult', $listResult, 60*60) ;
    }
    return $listResult ;
}