<?php

namespace Weibo\Model;

use Think\Model;

class WeiboCommentModel extends Model
{
    protected $_validate = array(
        array('content', '1,500', '内容不能为空或内容太长,长度必须在1到500之间！', self::EXISTS_VALIDATE, 'length'),
    );

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
    );

    public function addComment($uid, $weibo_id, $content, $comment_id = 0,$img_id=0)
    {
        $content = str_replace(' ', '/nb', $content);
        $content = nl2br($content);
        $content = str_replace('<br />', '', $content);
        $content = text($content);
        //写入数据库
        $data = array('uid' => $uid, 'content' => $content, 'weibo_id' => $weibo_id, 'comment_id' => $comment_id,'img_id'=>$img_id);
        $data = $this->create($data);
        if (!$data) return false;
        $comment_id = $this->add($data);

        //增加动态评论数量
        D('Weibo/Weibo')->where(array('id' => $weibo_id))->setInc('comment_count');

        S('weibo_' . $weibo_id, null);
        //返回评论编号
        return $comment_id;
    }

    public function deleteComment($comment_id)
    {
        //获取动态编号
        $comment = D('Weibo/WeiboComment')->find($comment_id);
        if ($comment['status'] == -1) {
            return false;
        }
        $weibo_id = $comment['weibo_id'];

        //将评论标记为已经删除
        D('Weibo/WeiboComment')->where(array('id' => $comment_id))->setField('status', -1);

        //减少动态的评论数量
        D('Weibo/Weibo')->where(array('id' => $weibo_id))->setDec('comment_count');
        S('weibo_' . $weibo_id, null);
        clean_weibo_html_cache($weibo_id);
        return true;
    }

    public function getComment($id)
    {
        $comment = S('weibo_comment_' . $id);
        if (!$comment) {
            $comment = $this->find($id);
            $comment['content'] = $this->parseComment($comment['content']);
            $comment['user'] = query_user(array('uid', 'avatar32', 'avatar64', 'avatar128', 'space_url', 'nickname'), $comment['uid']);
            S('weibo_comment_' . $id, $comment);
        }
        //当前登录用户点赞
        $map_support['appname'] = 'Weibo';
        $map_support['table'] = 'weibo_comment';
        $map_support['uid'] = is_login();
        $map_support['row'] = $id;
        $comment['supported'] = D('Support')->where($map_support)->count() ;

        $comment['content'] = parse_at_users($comment['content'], true);
        $comment['content'] = parse_emoji($comment['content']);
        $comment['user']['nickname'] = get_nickname($comment['uid']);
        $comment['can_delete'] = check_auth('Weibo/Index/doDelComment', $comment['uid']);
        $comment['img']=explode(',',$comment['img_id']);
        return $comment;
    }

    public function parseComment($content)
    {
        $content = op_t($content, false);

        $content = parse_weibo_content($content);

        return $content;
    }

    public function getAllComment($weibo_id)
    {

        $order = modC('COMMENT_ORDER', 0, 'WEIBO') == 1 ? 'create_time asc' : 'create_time desc';
        $comment = $this->where(array('weibo_id' => $weibo_id, 'status' => 1))->order($order)->field('id')->select();
        $ids = getSubByKey($comment, 'id');
        $list = array();
        foreach ($ids as $v) {
            $list[$v] = $this->getComment($v);
        }
        return $list;
    }

    public function getCount($weibo_id)
    {
        $filter = $this->getSupportComment($weibo_id, 0) ;
        $map['weibo_id'] = $weibo_id ;
        $map['status'] = 1 ;
        if($filter[0] != false){
            $map['id'] = array('not in', $filter[0]) ;
        }
        $count = $this->where($map)->count();
        return $count;
    }

    public function getCommentList($weibo_id, $page = 1, $show_more = 0)
    {
        $filter = $this->getSupportComment($weibo_id, 0) ;
        $order = modC('COMMENT_ORDER', 0, 'WEIBO') == 1 ? 'create_time asc' : 'create_time desc';
        $map['weibo_id'] = $weibo_id ;
        $map['status'] = 1 ;
        if($filter[0] != false){
            $map['id'] = array('not in', $filter[0]) ;
        }
        $comment = $this->where($map)->order($order)->page($page, 10)->field('id')->select();
        $ids = getSubByKey($comment, 'id');
        $list = array();
        foreach ($ids as $v) {
            $list[$v] = $this->getComment($v);
        }
        return $list;
    }

    /**返回神评论数据或者神评论的id（一维数组）
     * @param $weibo_id
     * @param string $type   true => 返回含有评论列表数据的结果
     * @return array         $array[0] => id数组 ，$array[1] => 评论列表数据
     * @author szh(施志宏) szh@ourstu.com
     */
    public function getSupportComment($weibo_id, $type=true) {
        $limit = modC('LIMIT_REVIEW', 3, 'WEIBO') ;
        $number = modC('SUPPORT_NUMBER', 20, 'WEIBO') ;
        $map['appname'] = 'Weibo' ;
        $map['table'] = 'weibo_comment' ;
        $map['weibo_id'] = $weibo_id ;
        $map['support_down'] = array('egt', $number) ;
        $list = D('WeiboComment')->where($map)->order('support_down desc')->limit($limit)->field('id')->select() ;
        $ids = getSubByKey($list, 'id') ;
        $return = array() ;
        $return[0] = $ids ;
        if($type == true) {
            $result = array() ;
            foreach ($ids as $key=>$val){
                $result[] = $this->getComment($val);
            }
            unset($val) ;
            $return[1] = $result ;
        }
        return $return ;
    }
}