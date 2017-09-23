<?php

namespace Weibo\Controller;

use Think\Controller;

class TypeController extends Controller
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

        $var['fileSizeLimit'] = floor(2 * 1024) . 'KB';
        $var['total'] = $data['total'];
        $this->assign($var);
        $data['html'] = $this->fetch('imagebox');
        exit(json_encode($data));

    }

    /**
     * fetchImage  渲染图片动态
     * @param $weibo
     * @return string
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function fetchImage($weibo)
    {
        $weibo_data = unserialize($weibo['data']);
        $weibo_data['attach_ids'] = explode(',', $weibo_data['attach_ids']);
        $param = array();
        foreach ($weibo_data['attach_ids'] as $v) {
            $weibo_data['image'][$v]['small'] = getThumbImageById($v, 500, 500);
            $weibo_data['image'][$v]['big'] = get_cover($v, 'path');
            $param['weibo'] = $weibo;
            $param['weibo']['weibo_data'] = $weibo_data;
        }

        $count = count($weibo_data['attach_ids']);
        switch ($count) {
            case 1:
                $width = 'col-80';
                break;
            case 2:
                $width = 'col-50';
                break;
            case 4:
                $width = 'col-50';
                break;
            default :
                $width = 'col-33';
        }
        unset($v);
        $this->assign($param);
        $this->assign('width',$width);
        $this->assign('img_num',$count);
        return $this->fetch(T('Application://Weibo@Type/fetchimage'));
    }

    /**
     * fetchRepost   渲染转发动态
     * @param $weibo
     * @return string
     * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
     */
    public function fetchRepost($weibo)
    {
        $weibo_data = unserialize($weibo['data']);
        $weibo_data['attach_ids'] = explode(',', $weibo_data['attach_ids']);
        $source_weibo = D('Weibo/Weibo')->getWeiboDetail($weibo_data['sourceId']);
        $source_weibo['user'] = query_user(array('uid', 'nickname', 'avatar32', 'space_url', 'rank_link', 'title'), $source_weibo['uid']);
        $param['weibo'] = $weibo;
        $param['weibo']['source_weibo'] = $source_weibo;
        $this->assign($param);
        return $this->fetch(T('Application://Weibo@Type/fetchrepost'));
    }

    public function fetchLongWeibo($weibo)
    {
        $this->assign('weibo',$weibo);
        return $this->fetch(T('Application://Weibo@Type/fetchlongweibo'));
    }

    /**
     * fetchGoods 渲染商品动态
     * @param $weibo
     * @author:Andy(王杰) wj@ourstu.com
     */
    public function fetchGoods($weibo)
    {
        $weibo_data = unserialize($weibo['data']);
        $weibo_data['attach_ids'] = explode(',', $weibo_data['attach_ids']);
        $goods = D('Mall/goods')->getGoods($weibo['goods_id']);
        $this->assign('goods',$goods);
        $param['weibo'] = $weibo;
        $this->assign($param);
        return $this->fetch(T('Application://Weibo@Type/fetchgoods'));
    }

    /**
     * fetchGoods 渲染红包动态
     * @param $weibo
     * @author:Andy(王杰) wj@ourstu.com
     */
    public function fetchRedBag($weibo)
    {
        $data = unserialize($weibo['data']);
        $rank = M('RedbagList')->where(array('redbagId'=>$data['id']))->count();
        $data['token'] = md5($data['id'].'xiaohehenilaipojiewoloa');
        $param['weibo'] = $weibo;

        $redbag = M('Redbag')->where(array('id' => $data['id']))->find();
        $surplus = $redbag['all_money'] - $redbag['sell_money'];
        $field = D('Ucenter/Score')->getTypeList(array('status' => 1));
        if ($surplus > 0&&$redbag['status']!=2){
            if((time()-$redbag['create_time'])>=86400){
                foreach ($field as &$v) {
                    if ($data['type'] == $v['id']) {
                        D('Ucenter/Score')->setUserScore($redbag['uid'],$surplus,$v['id'],'inc');
                        D('Redbag')->where(array('id' => $data['id']))->setField('status',2);
                        D('Message')->sendMessageWithoutCheckSelf($redbag['uid'], '您发的红包并未全部领取，剩余' . $surplus.$v['title'].'已返还。', $v['title'] . '增加了' . $surplus);
                    }
                }
            }
        }

        $this->assign('checked',$data['num']-$rank);
        $this->assign('redbag',$data);
        $this->assign($param);
        return $this->fetch(T('Application://Weibo@Type/fetchredbag'));
    }
    public function fetchVoice($weibo){
        $voice=explode('[]',$weibo['content']);
        $voicePath=$voice[0];
        $voiceTranslate=$voice[1];
        $param['weibo'] = $weibo;
        $this->assign($param);
        $this->assign(array(
            'voice'=>$voicePath,
            'Translate'=>$voiceTranslate,
            'data'=>$voice,
        ));
        return $this->fetch(T('Application://Weibo@Type/fetchvoice'));
    }

    public function fetchShare($weibo){
        $data = unserialize($weibo['data']);
        $this->assign('content',$weibo['content']);
        $this->assign('data',$data);
        return $this->fetch(T('Application://Weibo@Type/fetchshare'));
    }
    public function fetchQuestion($weibo){
        $weibo['question'] = unserialize($weibo['data']);
        $this->assign('weibo',$weibo);
        return $this->fetch(T('Application://Weibo@Type/fetchquestion'));
    }

    public function fetchLocalVideo($weibo){
        $content=$weibo['content'];
        $position=strrpos($content,'|video:|');
        $savename=substr($content,$position+8,strlen($content)-$position);
        $map['savename']=$savename;
        $savepath=M('file')->where($map)->select();
        if($savepath[0]['driver']=='local'){
            $url = '..'.$savepath[0]['savepath'].$savename;
        }else {
            $url = $savepath[0]['savepath'];
        }
        $this->assign('savename',$savename);
        $this->assign('url',$url);
        $this->assign('weibo',$weibo);
        return $this->fetch(T('m://Weibo@Type/fetchlocalvideo'));
    }
}