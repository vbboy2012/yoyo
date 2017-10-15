<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/25 0025
 * Time: 上午 8:47
 */
namespace Event\Model;
use Think\Model ;

class LocalCommentModel extends Model{

    public function addComment($uid, $event_id, $content, $id=0) {
        $content = str_replace(' ', '/nb', $content);
        $content = nl2br($content);
        $content = str_replace('<br />', '', $content);
        $content = text($content);
        //写入数据库
        $data['uid']=$uid;
        $data['content']=$content;
        $data['app']='Event';
        if($id>0){
            $data['mod']='lzl_comment';
            $data['row_id']=$id;
        }else{
            $data['mod']='event';
            $data['row_id']=$event_id;
        }
        $data['status']=1;
        $data['create_time']=time();
        $data = $this->create($data);
        if (!$data) return false;
        $comment_id = $this->add($data);
        //返回评论id
        return $comment_id;
    }

    public function getComment($event)
    {
        if(is_array($event)){
            $id = $event['id'] ;
            $comment = $event ;
        }else{
            $comment = $this->find($event);
            $id = $event ;
        }
        if ($comment) {
            $comment['content'] = $this->parseComment($comment['content']);
            $comment['user'] = query_user(array('uid', 'avatar32', 'avatar64', 'avatar128', 'space_url', 'nickname'), $comment['uid']);
            $comment['content'] = parse_at_users($comment['content'], true);
            $comment['content'] = parse_emoji($comment['content']);
            $comment['create_time'] = friendlyDate($comment['create_time']);

            $support['appname'] = 'Event-comment';
            $support['table'] = 'local_comment';
            $support['row'] = $id;
            $comment['support_all_count'] = D('Support')->where($support)->count();
            $support['uid'] = is_login();
            $comment['support_count'] = D('Support')->where($support)->count();
            $map['app'] = 'Event';
            $map['mod'] = 'lzl_comment';
            $map['row_id'] = $comment['id'];
            $map['status'] = 1;
            $comment['all_count']=D('local_comment')->where($map)->count();
            if ($comment['support_count']) {
                $comment['is_support'] = '1';
            } else {
                $comment['is_support'] = '0';
            }
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
        $comment = $this->field('uid')->find($comment_id);
        //从数据库中删除评论
        $result = $this->where(array('id' => $comment_id))->delete();
        $nickname = query_user(array('nickname'), is_login());
        send_message($comment['uid'],'您的评论已被管理员'.$nickname['nickname'].'删除。');
        return $result;
    }

    /**检验是否是有效的评论
     * @param $id
     * @param $event_id
     * @return bool
     * @author szh(施志宏) szh@ourstu.com
     */
    public function checkCommentAlive($id, $event_id) {
        $comment = $this->where(array('id'=> $id, 'status'=>1))->find() ;
        if ($comment['row_id'] != $event_id){
            return false ;
        }
        return true ;
    }

    /**获取某一级别下的评论总数（活动或者某一评论）
     * @param $id
     * @param int $type
     * @return int
     * @author szh(施志宏) szh@ourstu.com
     */
    public function CountComments($id, $type=0) {
        if (!is_numeric($id) || $id <= 0) return 0 ;
        $map['app'] = 'Event' ;
        if ($type == 0){
            $map['mod'] = 'event' ;
        } else{
            $map['mod'] = 'lzl_comment' ;
        }
        $map['status'] = 1 ;
        $map['row_id'] = $id ;
        $number = 0 ;
        $list = $this->where($map)->field('id')->select() ;
        foreach ($list as $val) {
            $number++ ;
            $son_number = $this->CountComments($val['id'], 1) ;
            $number += $son_number ;
        }
        unset($val) ;
        return $number ;
    }
}