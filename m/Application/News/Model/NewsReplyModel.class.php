<?php
namespace News\Model;

use Think\Model;

class NewsReplyModel extends Model
{
    public function addCommentlzl($uid, $news_id, $content)
    {
        $content = str_replace(' ', '/nb', $content);
        $content = nl2br($content);
        $content = str_replace('<br />', '', $content);
        $content = text($content);
        //写入数据库

        $postId=D('local_comment')->where(array('id'=>$news_id))->getField('row_id');
        $data['post_id']=$postId;
        $data['to_reply_id']=$news_id;
        $data['uid']=$uid;
        $data['content']=$content;
        $data['ctime']=time();
        $data = $this->create($data);
        if (!$data) return false;
        $comment_id = $this->add($data);
        //返回评论id
        return $comment_id;
    }

    public function getCommentlzl($id)
    {
        $lzl_result = $this->find($id);
        $lzl_result['content'] = $this->parseComment($lzl_result['content']);
        $lzl_result['user'] = query_user(array('uid', 'avatar32', 'avatar64', 'avatar128', 'space_url', 'nickname'), $lzl_result['uid']);
        $lzl_result['content'] = parse_at_users($lzl_result['content'], true);
        $lzl_result['content'] = parse_emoji($lzl_result['content']);
        $lzl_result['ctime'] = friendlyDate($lzl_result['ctime']);

        $support['appname'] = 'News-lzl';
        $support['table'] = 'news';
        $support['row'] = $id;
        $lzl_result['support_all_count'] = D('Support')->where($support)->count();
        $support['uid'] = is_login();
        $lzl_result['support_count'] = D('Support')->where($support)->count();
        if ($lzl_result['support_count']) {
            $lzl_result['is_support'] = '1';
        } else {
            $lzl_result['is_support'] = '0';
        }
        return $lzl_result;
    }

    public function parseComment($content)
    {
        $content = op_t($content, false);

        $content = parse_weibo_content($content);

        return $content;
    }

}