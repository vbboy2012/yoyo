<?php

namespace Mob\Controller;

use Think\Controller;

class WeiboTypeController extends Controller
{
    /**
     * imageBox
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function imageBox()
    {
        $data['unid'] = substr(strtoupper(md5(uniqid(mt_rand(), true))), 0, 8);
        $data['status'] = 1;
        $data['total'] = 9;
        // 设置渲染变量
        $var['unid'] = $data['unid'];

        $var['fileSizeLimit'] = floor(2 * 1024).'KB';
        $var['total'] = $data['total'];
        $this->assign($var);
        $data['html'] = $this->fetch('imagebox');
        exit(json_encode($data));

    }

    /**
     * fetchImage  渲染图片微博
     * @param $weibo
     * @return string
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function fetchImage($weibo)
    {
        $weibo_data = unserialize($weibo['data']);
        $weibo_data['attach_ids'] = explode(',', $weibo_data['attach_ids']);

        foreach ($weibo_data['attach_ids'] as $k_i => $v_i) {
            $weibo_data['image'][$k_i]['small'] = getThumbImageById($v_i, 100, 100);
            $bi = M('Picture')->where(array('status' => 1))->getById($v_i);
            $weibo_data['image'][$k_i]['big']  = get_pic_src($bi['path']) ;
            $param['weibo'] = $weibo;
            $param['weibo']['weibo_data'] = $weibo_data;
        }
        $this->assign($param);
        return $this->fetch(T('Application://Mob@Type/fetchimage'));
    }

    /**
     * fetchRepost   渲染转发微博
     * @param $weibo
     * @return string
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function fetchRepost($weibo)
    {

        $weibo_data = unserialize($weibo['data']);
        $weibo_data['attach_ids'] = explode(',', $weibo_data['attach_ids']);
        $source_weibo = D('Mob/Weibo')->getWeiboDetail($weibo_data['sourceId']);
        $picture_ids=explode(',',$source_weibo['data']['attach_ids']);

        $source_weibo['user']=query_user(array('uid', 'nickname',  'avatar32', 'space_url',  'rank_link', 'title'), $source_weibo['uid']);
        foreach ($picture_ids as $a){
            $path=M('picture')->where(array('status'=>1,'id'=>$a))->getField('path');
          if($a) {
              if (!stripos($source_weibo['fetchContent'],'<img')){
                  if (substr($path, 0, 4) == 'http') {
                      $source_weibo['fetchContent'] = $source_weibo['fetchContent'] .'<div class="img-content am-cf am-avg-sm-3"> <div href='.$path.'    style="padding: 1px;float: left" >'. '<img src='.$path.'
                      height="100" width="100" style="margin-right: 7px;margin-bottom: 7px;" ></div></div>';
                  } else {
                      $source_weibo['fetchContent'] = $source_weibo['fetchContent'] . '<div class="img-content am-cf am-avg-sm-3"> <div href='.getRootUrl().$path.' style="padding: 1px;float: left" >'. '<img src='.getRootUrl().$path.'
                      height="100" width="100" style="margin-right: 7px;margin-bottom: 7px;" ></div></div>';
                  }
              }
          }
        }
        $weibo['content']=parse_expression($weibo['content']);
        $param['weibo'] = $weibo;
        $param['weibo']['source_weibo'] = $source_weibo;

        $this->assign($param);
        return $this->fetch(T('Application://Mob@Type/fetchrepost'));

    }

    public function fetchRedBag($weibo)
    {
        $weibo_data = unserialize($weibo['data']);
        $redbag = M('Redbag')->where(array('id' => $weibo_data['id']))->find();
        $surplus = $redbag['all_money'] - $redbag['sell_money'];
        $field = D('Ucenter/Score')->getTypeList(array('status' => 1));
        if ($surplus > 0&&$redbag['status']!=2){
            if((time()-$redbag['create_time'])>=86400){
                foreach ($field as &$v) {
                    if ($weibo_data['type'] == $v['id']) {
                        D('Ucenter/Score')->setUserScore($redbag['uid'],$surplus,$v['id'],'inc');
                        // D('Member')->where(array('uid' => $redbag['uid']))->setInc('score' . $v['id'], $surplus);
                        D('Redbag')->where(array('id' => $weibo_data['id']))->setField('status',2);
                        D('Message')->sendMessageWithoutCheckSelf($redbag['uid'], '您的红包并未全部领取，剩余' . $surplus.$v['title'].'已返还。', $v['title'] . '增加了' . $surplus);
                    }
                }
            }
        }
        $user = query_user(array('uid', 'nickname', 'avatar64', 'space_url', 'rank_link', 'title'), $weibo_data['uid']);
        $this->assign('user', $user);
        $weibo_data['weibo_id']=$weibo['id'];
        $this->assign('weibo', $weibo);
        unset($weibo_data['uid']);
        unset($weibo_data['num']);
        unset($weibo_data['all_money']);
        unset($weibo_data['sell_money']);
        unset($weibo_data['rank']);
        unset($weibo_data['type']);
        unset($weibo_data['content']);
        unset($weibo_data['redbag_type']);
        unset($weibo_data['create_time']);
        unset($weibo_data['status']);
        // dump($weibo_data);exit;
        $this->assign('weibo_data', $weibo_data);
        return $this->fetch(T('Application://Mob@Type/fetchredbag'));
    }
    public function fetchLocalVideo($weibo){
        $root=__ROOT__;
        $content=$weibo['content'];
        $position=strrpos($content,'|video:|');
        $savename=substr($content,$position+8,strlen($content)-$position);
        $map['savename']=$savename;
        $savepath=M('file')->where($map)->select();
        if($savepath[0]['driver']=='local'){
            $url = $root.$savepath[0]['savepath'].$savename;
        }else {
            $url = $savepath[0]['savepath'];
        }
        $this->assign('savename',$savename);
        $this->assign('url',$url);
        $this->assign('weibo',$weibo);
        return $this->fetch(T('Application://Mob@Type/fetchlocalvideo'));
    }

}