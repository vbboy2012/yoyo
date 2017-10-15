<?php
/**
 * Created by PhpStorm.
 * User: 王杰
 * Date: 2016/12/20
 * Time: 18:51
 */
function get_message_title($type)
{
    switch ($type) {
        case 'Project_project':
            return '项目消息';
            break;
        case 'Ticket_ticket':
            return '工单消息';
            break;
        default;
    }
}

/**
 * @param
 * @auth sun slf02@ourstu.com
 * @return 根据id获取文件路径
 */
/*function get_file_url($id){
    $res = M('File')->where(array('id' => $id))->find();
    $resUrl = '';
    if($res) {
        $resUrl = $res['driver'] == 'local' ? 'http://'.$_SERVER['HTTP_HOST'].str_replace('/index.php','',$_SERVER['SCRIPT_NAME']). $res['savepath'] . $res['savename'] : $res['savepath'];
    }
    return $resUrl;
}*/

/**
 * @param
 * @auth sun slf02@ourstu.com
 * @return 根据id获取文件配置(如文件名，文件大小，文件后缀)
 */
function get_file($id){
    $res = M('File')->where(array('id' => $id))->field('name,size,ext')->find();
    $res['size']=round($res['size']/1024,1);
    if($res['size'] >= 1024) {
        $res['size']=round($res['size']/1024,1).'MB';
    }else{
        $res['size']=$res['size'].'KB';
    }
    return $res;
}

function is_business($uid){

    $uid= $uid ? $uid :is_login();
    $count=D('business_user')->where(array('uid'=>$uid,'status'=>1))->count();
    if($count){
        return true;
    }else{
        return false;
    }
}

function get_ticket_status($data){
    foreach ($data as &$val){
        switch ($val['status']){
            case 0;
                if(empty($val['engineer_uid'])){
                    $var['status']='未接单';
                }else{
                    $val['status']='已接单';
                }
                break;
            case 1;
                $val['status']='等待工程师回复';
                break;
            case 2;
                $val['status']='等待您回复';
                break;
            case 3;
                $val['status']='已解决，待您评价';
                break;
            case 4;
                $val['status']='已完成';
                break;
        }
    }
}