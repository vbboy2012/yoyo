<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-3-26
 * Time: 上午10:43
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */


/**
 * send_weibo  发布动态
 * @param $content
 * @param $type
 * @param string $feed_data
 * @param string $from
 * @return bool
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function send_weibo($content, $type, $feed_data = '', $from = '', $crowdType = '' , $goods = '')
{
    $topicModel=D('Weibo/Topic');
    $topicFollowModel=D('Weibo/TopicFollow');
    $weiboTopicLink = $topicModel->addTopic($content);
    $uid = is_login();
    $weibo_id = D('Weibo')->addWeibo($uid, $content, $type, $feed_data, $from, $crowdType, $goods );
    if (count($weiboTopicLink)) {
        foreach ($weiboTopicLink as &$val) {
            $val['weibo_id'] = $weibo_id;
        }
        unset($val);
        D('Weibo/WeiboTopicLink')->addDatas($weiboTopicLink);

        //信任话题的用户接收到话题更新通知
        $k=0;
        foreach ($weiboTopicLink as $topk){
            $topks[$k]['topk']=$topicModel->getTopicInfo($topk['topic_id']);
            $topks[$k]['uid']=$topicFollowModel->getTopicFollow($topk['topic_id']);
            $k++;
        }
        unset($k);

        foreach ($topks as $vo)
        {
            if(!empty($vo['uid'])){
                //排除自己
                if(in_array($uid,$vo['uid'])){
                    $key=array_search($uid,$vo['uid']);
                    unset($vo['uid'][$key]);
                }
                // 未读过该话题的用户不再提醒
                // $readUids=D('Message')->topicMessageRead($vo['topk']['name'],$vo['uid']);
                // D('Message')->sendALotOfMessageWithoutCheckSelf($readUids,'话题通知','您信任的#'.$vo['topk']['name'].'#话题已更新。','Weibo/Topic/index',array('topk'=>$vo['topk']['id']),1,'Weibo');
            }
        }

    }
    // $to_uid = get_at_users($content);
    // send_message($to_uid, get_nickname($uid) . "@了您", $content, 'Weibo/Index/detail', array('id' => $weibo_id), is_login(), 'Weibo', 'Common_comment');
    return $weibo_id;
}

/**
 * send_comment  发布评论
 * @param $weibo_id
 * @param $content
 * @param int $comment_id
 * @return bool
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function send_comment($weibo_id, $content, $comment_id = 0,$img_id = 0)
{
    $uid = is_login();

    $result = D('WeiboComment')->addComment($uid, $weibo_id, $content, $comment_id,$img_id);
    //行为日志
    action_log('add_weibo_comment', 'weibo_comment', $result, $uid);

    //通知动态作者
    $weibo = D('Weibo')->getWeiboDetail($weibo_id);
    $message_content = array(
        'keyword1' => parse_content_for_message($content),
        'keyword2' => '评论我的微博：',
        'keyword3' => $weibo['type'] == 'repost' ? "转发微博" : parse_content_for_message($weibo['content'])
    );
    send_comment_message($weibo['uid'], $weibo_id, $message_content);
    //通知回复的人
    if ($comment_id) {
        $comment = D('WeiboComment')->getComment($comment_id);
        if ($comment['uid'] != $weibo['uid']) {
            send_comment_message($comment['uid'], $weibo_id, $message_content);
        }
    }
    return $result;
}

/**
 * send_comment_message 发送评论消息
 * @param $uid
 * @param $weibo_id
 * @param $message
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function send_comment_message($uid, $weibo_id, $message)
{
    $title = L('_COMMENT_MESSAGE_');
    $from_uid = is_login();
    send_message($uid, $title, $message, 'Weibo/Index/detail', array('id' => $weibo_id), $from_uid, 'Weibo', 'Common_comment');
}

/**
 * 筛选出可以管理圈子的人
 * @return mixed
 */
function get_crowd_admin($crowd_id)
{
    $data = D('WeiboCrowdMember')->getCrowdAdmin($crowd_id);
    if (empty($data)) {
        return -1;
    }
    return $data['uid'];
}

/**
 * 圈子是否存在
 */
function crowd_exists($crowd_id = '')
{
    $crowd = D('WeiboCrowd')->getCrowd($crowd_id);
    return $crowd ? true : false;
}

function get_crowd_weibo_num($uid = '', $crow_id = '')
{
    return D('weibo')->getCrowdWeiboNum($uid, $crow_id);
}

function parse_content_for_message($content)
{
    $content = shorten_white_space($content);
    $content = op_t($content, false);
    $content = parse_emoji($content);
    //at转化
    $list = get_replace_list($content, 'at');
    foreach ($list as $val) {
        $user = query_user(array('nickname', 'space_url'), $val);
        $content = str_replace('[at:' . $val . ']', '<span ucard="' . $val . '">@' . $user['real_nickname'] . '</span>', $content);
    }
    unset($val);
    //at转化 end

    return $content;
}

function get_replace_list($html, $type)
{
    //正则表达式匹配
    $pattern = "/\[" . $type . ":([0-9]+)\]/";
    preg_match_all($pattern, $html, $list);

    //返回话题列表
    return array_unique($list[1]);
}

function get_crowd_title($id)
{
    $crowd = D('Weibo/WeiboCrowd')->getCrowd($id);
    return $crowd['title'];
}

function parse_weibo_type_title($type)
{
    $title = '';
    switch ($type) {
        case 'all':
            $title = '全站动态';
            break;
        case 'hot':
            $title = '热门动态';
            break;
        case 'concerned':
            $title = '我的信任';
            break;
        case 'fav':
            $title = '我的点赞';
            break;
        case 'huati':
            $title = '热门话题';
            break;
    }
    return $title;
}


function render_weibo_length($content = '', $weibo_id = 0)
{
    $content = get_short_sp($content, 120);
    $content .= '<a href="'.U('weibo/index/detail',array('id'=>$weibo_id)).'">查看全文</a>';
    return $content;
}
/**
 * 以下微信相关函数
 */
function https_request($url)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
    // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
}

/**
 *微信相关函数结束
 */

/**
 * 处理话题名称过长
 * @author szh(施志宏) szh@ourstu.com
 */
function longTopicName ($name) {
    if (mb_strlen( $name, 'UTF-8') > 12) {
        $start = mb_substr( $name, 0, 5, 'UTF-8') ;
        $end = mb_substr( $name, -5, 5, 'UTF-8') ;
        $aName = $start.'...'.$end ;
    } else {
        $aName = $name ;
    }
    return $aName ;
}

/**微博动态中分享的资源的url兼容微社区
 * @param $url
 * @return mixed
 * @author szh(施志宏) szh@ourstu.com
 */
function cutUrl($url) {
    $reUrl = $url ;
    if (is_string($url)) {
        preg_match('/s=\/(.+)/', $url, $info) ;
        if ($info[1]) {
            $reUrl = $info[1] ;
        }
    }
    return $reUrl ;
}