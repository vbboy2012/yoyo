<?php
/**
 * Created by PhpStorm.
 * User: 王杰
 * Date: 2017/3/6
 * Time: 14:28
 */
namespace Weibo\Model;

use Think\Model;

class WeiboStampDetailModel extends Model
{

    protected $tableName = 'stamp_weibo';

    public function getStampByWeibo($weibo_id)
    {
        $tag = 'stamp_weibo_by_'.$weibo_id;
        $stamp = S($tag);
        if (empty($stamp)) {
            $res = $this->where(array('weibo_id'=>$weibo_id))->find();
            $stamp = D('Weibo/WeiboStamp')->getStamp($res['stamp_id']);
            S($tag,$stamp,60*60*2);
        }
        return $stamp;
    }

    /**
     * 设置微博图章
     * @param $weibo_id
     * @param $stamp_id
     * @return bool
     * @author:Andy(王杰) wj@ourstu.com
     */
    public function setStamp($weibo_id, $stamp_id)
    {
        $weibo = $this->where(array('weibo_id'=>$weibo_id))->find();
        if (empty($weibo)) {
            $res = $this->add(array('stamp_id'=>$stamp_id,'weibo_id'=>$weibo_id,'create_time'=>time(),'status'=>1));
        } else {
            $res = $this->where(array('weibo_id'=>$weibo_id))->setField('stamp_id',$stamp_id);
        }
        if($res){
            S('weibo_' . $weibo_id,null);
            S('stamp_weibo_by_' . $weibo_id , null);
            clean_weibo_html_cache($weibo_id);
        }
        return $res;
    }
}