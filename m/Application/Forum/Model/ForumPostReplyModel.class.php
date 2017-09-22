<?php
namespace Forum\Model;

use Think\Model;

class ForumPostReplyModel extends Model
{
    public function addComment($uid, $forum_id, $content, $comment_id = 0)
    {
        $content = str_replace(' ', '/nb', $content);
        $content = nl2br($content);
        $content = str_replace('<br />', '', $content);
        $content = text($content);
        //写入数据库
        $data['uid']=$uid;
        $data['content']=$content;
        $data['post_id']=$forum_id;
        $data['status']=1;
        $data['create_time']=time();
        $data['update_time']=time();
        $data = $this->create($data);
        if (!$data) return false;
        $comment_id = $this->add($data);
        //返回评论id
        return $comment_id;
    }

    public function addCommentlzl($uid, $forum_id, $content)
    {
        $content = str_replace(' ', '/nb', $content);
        $content = nl2br($content);
        $content = str_replace('<br />', '', $content);
        $content = text($content);
        //写入数据库
        $postId=D('forum_post_reply')->where(array('id'=>$forum_id))->getField('post_id');
        $data['post_id']=$postId;
        $data['to_reply_id']=$forum_id;
        $data['uid']=$uid;
        $data['content']=$content;
        $data['ctime']=time();
        $data = D('forum_lzl_reply')->create($data);
        if (!$data) return false;
        $comment_id = D('forum_lzl_reply')->add($data);
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

        $support['appname'] = 'Forum';
        $support['table'] = 'forum';
        $support['row'] = $id;
        $comment['support_all_count'] = D('Support')->where($support)->count();
        $support['uid'] = is_login();
        $comment['support_count'] = D('Support')->where($support)->count();
        if ($comment['support_count']) {
            $comment['is_support'] = '1';
        } else {
            $comment['is_support'] = '0';
        }
        return $comment;
    }

    public function getCommentlzl($id)
    {
        $lzl_result = D('forum_lzl_reply')->find($id);
        $lzl_result['content'] = $this->parseComment($lzl_result['content']);
        $lzl_result['user'] = query_user(array('uid', 'avatar32', 'avatar64', 'avatar128', 'space_url', 'nickname'), $lzl_result['uid']);
        $lzl_result['content'] = parse_at_users($lzl_result['content'], true);
        $lzl_result['content'] = parse_emoji($lzl_result['content']);
        $lzl_result['ctime'] = friendlyDate($lzl_result['ctime']);

        $support['appname'] = 'Forum-lzl';
        $support['table'] = 'forum';
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

    public function deleteComment($comment_id)
    {
        $comment = $this->getComment($comment_id);
        //从数据库中删除评论、以及楼中楼
        $result = $this->where(array('id' => $comment_id))->delete();
        D('forum_lzl_reply')->where(array('post_id' => $comment_id))->delete();
        $nickname = query_user(array('uid', 'avatar32', 'avatar64', 'avatar128', 'space_url', 'nickname'), is_login());
        send_message($comment['uid'],'您的评论已被管理员'.$nickname['nickname'].'删除。');
        return $result;
    }
}