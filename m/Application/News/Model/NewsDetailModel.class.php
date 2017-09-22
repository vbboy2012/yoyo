<?php

namespace News\Model;


use Common\Model\ContentHandlerModel;
use Think\Model;

class NewsDetailModel extends Model
{

    public function editData($data = array())
    {
        if ($this->find($data['news_id'])) {
            $res = $this->save($data);
        } else {
            $res = $this->add($data);
        }
        return $res;
    }

    public function getData($id)
    {
        $contentHandler = new ContentHandlerModel();
        $res = $this->where(array('news_id' => $id))->find();
        $res['content'] = $contentHandler->displayHtmlContent($res['content']);
        return $res;
    }

    public function getDetail($id)
    {
        $data=D('news')->find($id);
        $data['content']=D('news_detail')->where(array('news_id'=>$id))->getField('content');
        $data['create_time']=friendlyDate($data['create_time']);
        $data['user']=query_user(array('avatar64', 'uid','avatar128', 'avatar32', 'avatar256', 'avatar512','title','nickname','space_url','signature'),$data['uid']);
        $data['comments'] = get_all_comment($id, $data['comment']);
        $follow['follow_who']=$data['uid'];
        $follow['who_follow']=is_login();
        $data['follow']=D('follow')->where($follow)->count();

        $support['appname'] = 'News';
        $support['table'] = 'news';
        $support['row'] = $id;
        $data['support_all_count'] = D('Support')->where($support)->count();
        $support['uid'] = is_login();
        $data['support_count'] = D('Support')->where($support)->count();
        if ($data['support_count']) {
            $data['is_support'] = '1';
        } else {
            $data['is_support'] = '0';
        }
        return $data;
    }
}