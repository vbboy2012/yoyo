<?php
namespace News\Model;

use Think\Model;

class LocalCommentModel extends Model
{
    public function addComment($uid, $news_id, $content)
    {
        $content = str_replace(' ', '/nb', $content);
        $content = nl2br($content);
        $content = str_replace('<br />', '', $content);
        $content = text($content);
        //写入数据库
        $data['uid']=$uid;
        $data['content']=$content;
        $data['row_id']=$news_id;
        $data['app']='News';
        $data['mod']='index';
        $data['status']=1;
        $data['create_time']=time();
        $data = $this->create($data);
        if (!$data) return false;
        $comment_id = $this->add($data);
        //返回评论id
        return $comment_id;
    }

    public function getComment($id)
    {
        $comment = $this->find($id);
        $comment['content'] = $this->parseComment($comment['content']);
        $comment['user'] = query_user(array('uid', 'avatar32', 'avatar64', 'avatar128', 'space_url', 'nickname'), $comment['uid']);
        $comment['content'] = parse_at_users($comment['content'], true);
        $comment['content'] = parse_emoji($comment['content']);
        $comment['create_time'] = friendlyDate($comment['create_time']);

        $support['appname'] = 'News-comment';
        $support['table'] = 'news';
        $support['row'] = $id;
        $comment['support_all_count'] = D('Support')->where($support)->count();
        $support['uid'] = is_login();
        $comment['support_count'] = D('Support')->where($support)->count();
        $comment['all_count']=D('news_reply')->where(array('to_reply_id'=>$comment['id']))->count();
        if ($comment['support_count']) {
            $comment['is_support'] = '1';
        } else {
            $comment['is_support'] = '0';
        }
        return $comment;
    }

    public function parseComment($content)
    {
        $content = op_t($content, false);

        $content = parse_weibo_content($content);

        return $content;
    }

    public function deleteComment($comment_id)
    {
        $comment = $this->getComment($comment_id);
        //从数据库中删除评论、以及楼中楼
        $result = $this->where(array('id' => $comment_id))->delete();
        D('news_reply')->where(array('post_id' => $comment_id))->delete();
        $nickname = query_user(array('uid', 'avatar32', 'avatar64', 'avatar128', 'space_url', 'nickname'), is_login());
        send_message($comment['uid'],'您的评论已被管理员'.$nickname['nickname'].'删除。');
        return $result;
    }

}