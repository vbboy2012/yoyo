<?php

function send_forumcomment($Forum_id, $content, $comment_id = 0)
{
    $uid = is_login();
    $result = D('ForumPostReply')->addComment($uid, $Forum_id, $content, $comment_id);

    //通知帖子作者
    $user_id = D('forum_post')->where(array('id'=>$Forum_id))->getField('uid');
    send_message($user_id,'有人评论了您的帖子','有人评论了您的帖子，快去看看吧','Forum/Index/detail', array('id' => $Forum_id));

    return $result;
}

function send_lzlcomment($Forum_id, $content)
{
    $uid = is_login();
    $result = D('ForumPostReply')->addCommentlzl($uid, $Forum_id, $content);
//通知帖子作者
    $user_id = D('forum_post_reply')->where(array('id'=>$Forum_id))->getField('uid');
    send_message($user_id,'有人评论了您的评论','有人评论了您的评论，快去看看吧','Forum/Index/commentbyid', array('id' => $Forum_id));

    return $result;
}

function view_count($id){
    $viewCount=D('forum_post')->field('view_count')->find($id);
    $viewCount['view_count']++;
    $count['view_count']=$viewCount['view_count'];
    $res=D('forum_post')->where(array('id'=>$id))->save($count);
    if($res){
        return true;
    }else{
        return false;
    }
}

/**通过id获取板块信息
 * @param $id
 */
function getForumInfo( $id, $field = true) {
    $map = array() ;
    $map['id'] = $id ;
    return D('Forum')->field($field)->where($map)->find() ;
}

/**获取板块活跃用户
 * @param $id
 * @param string $start
 * @param string $end
 * @return array|bool
 * @author szh(施志宏) szh@ourstu.com
 */
function get_active_users($id, $start=0, $end=6) {
    $reasult = array() ;
    if (!is_numeric($id)) return false;
    $users = get_all_active_user($id) ;
    if (empty($users)) return false;
    $reasult['number'] = count($users) ;
    $showUser = array_slice($users, $start, $end) ;
    foreach ($showUser as &$val) {
        $val=query_user(array('nickname','uid', 'avatar128', 'space_url',),$val['uid']);
        $val['fans']=D('Member')->where(array('uid'=>$val['uid']))->getField('fans');
        $val['post']=D('forum_post')->where(array('uid'=>$val['uid']))->count();
        $uFollow = D('Common/Follow')->isFollow(is_login(), $val['uid']);
        if($uFollow == 1) {
            $follow = 'unfollow' ;
        } else {
            $follow = 'follow' ;
        }
        $val['ufollow'] = $follow ;
    }
    unset($val) ;
    $reasult['showUser'] = $showUser ;
    return $reasult ;
}
/**获取板块的回复用户
 * @param int $id 板块id
 * @param int $time 时间戳
 * @return array $return
 * @author szh(施志宏) szh@ourstu.com
 */
function get_reply_users($id, $time=0) {
    $param = array() ;
    $param['forum_id'] = $id ;
    if ($time) {
        $param['ForumPostReply.create_time'] = array('gt',$time) ;
    }
    $userData = D('ForumPostReplyView')->where($param)->group('ForumPostReply.uid')->order('ForumPostReply.create_time desc')->field('uid,create_time')->select();
    return $userData ;
}
/**获取板块发帖的活跃用户
 * @param int $id 板块id
 * @param int $time  时间戳
 * @return array $return
 * @author szh(施志宏) szh@ourstu.com
 */
function get_post_users($id, $time=0) {
    $param = array() ;
    $param['forum_id'] = $id ;
    if ($time) {
        $param['create_time'] = array('gt',$time) ;
    }
    $userData = D('ForumPost')->where($param)->group('uid')->order('create_time desc')->field('uid,create_time')->select();
    return $userData ;
}
/**合并二维数组
 * @param array $array
 * @param mixed $field
 * @return array
 * @author szh(施志宏) szh@ourstu.com
 */
function get_unique_array($array, $field) {
    $result = array() ;
    if (!is_array($array)) return $result;
    foreach($array as $val) {
        $mark = '' ;
        if (is_array($field)) {
            foreach ($field as $value) {
                $mark .= $val[$value] ;
            }
        } else {
            $mark = $val[$field] ;
        }
        if (!isset($result[$mark])){
            $result[$mark] = $val ;
        }
    }
    unset($val) ;
    return $result ;
}

/**获取设定时间内的所有活跃用户
 * @param $id  版块id
 * @return mixed
 * @author szh(施志宏) szh@ourstu.com
 */
function get_all_active_user($id) {
    if (!is_numeric($id)) return false ;
    $users = S('FORUM_ACTIVE_USER'.$id) ;
    if ($users === false) {
        $day = modC('ACTIVE_USER_TIME', 30, 'FORUM') ;
        $time = 0 ;
        if ($day) {
            $time = time() - ($day*24*60*60) ;
        }
        $postUser = get_post_users($id, $time) ;
        $replyUser = get_reply_users($id, $time) ;
        $allUser = array_merge($postUser, $replyUser) ;
        $users = get_unique_array($allUser, 'uid') ;
        $cTime = array();
        foreach ($users as $user) {
            $cTime[] = $user['create_time'];
        }
        array_multisort($cTime, SORT_DESC, $users);
        S('FORUM_ACTIVE_USER'.$id, $users, 3600) ;
    }
    return $users ;
}

/**获取论坛首页版块展示的最近帖子数量
 * @param $sid  版块id
 * @return int
 * @author szh(施志宏) szh@ourstu.com
 */
function get_week_num($sid) {
    if(!is_numeric($sid)) return 0 ;
    $day = modC('POST_WEEK_NUMBER', 7, 'FORUM') ;
    $param['status'] = 1 ;
    $param['forum_id'] = $sid ;
    if ($day) {
        $param['create_time'] = array('gt' ,(time() - ($day*24*60*60))) ;
    }
    $count = D('ForumPost')->where($param)->count() ;
    return $count ;
}