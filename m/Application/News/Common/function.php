<?php

function send_newscomment($News_id, $content)
{
    $uid = is_login();
    $result = D('LocalComment')->addComment($uid, $News_id, $content);
//行为日志
    action_log('news_post_reply', 'news', $result, $uid);

//通知帖子作者
    $user_id = D('news')->where(array('id'=>$News_id))->getField('uid');
    send_message($user_id,'有人评论了您的资讯','有人评论了您的资讯，快去看看吧','News/Index/detail', array('id' => $News_id));

    return $result;
}

function send_lzlcomment($News_id, $content)
{
    $uid = is_login();
    $result = D('NewsReply')->addCommentlzl($uid, $News_id, $content);
//通知帖子作者
    $user_id = D('local_comment')->where(array('id'=>$News_id))->getField('uid');
    send_message($user_id,'有人评论了您的评论','有人评论了您的评论，快去看看吧','News/Index/commentbyid', array('id' => $News_id));

    return $result;
}

function shortDesc($desc) {
    if (is_string($desc)&&mb_strlen($desc,'utf-8')>70)
        $desc =  mb_substr($desc,0,70,'utf-8').'...' ;
    return  $desc ;
}

/**
 * @param $id  资讯id
 * @param int $number   资讯详情页会展示的评论数量
 * @return int  资讯所有评论数量
 * @author szh(施志宏) szh@ourstu.com
 */
function get_all_comment($id, $number=0){
    if (!is_numeric($id) || $number == 0)
        return 0 ;
    $all_count = $number ;
    $map['app']='News';
    $map['mod']='index';
    $map['row_id']=$id;
    $map['status']=1;
    $post_reply = M('local_comment')->where($map)->select() ;
    if ($post_reply) {
        foreach ($post_reply as $val) {
            $son_count = M('news_reply')->where('to_reply_id='.$val['id'].' and is_del=0')->count() ;
            $all_count += $son_count ;
        }
        unset($val) ;
    }
    return $all_count ;
}