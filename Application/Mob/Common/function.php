<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-3-26
 * Time: 上午10:43
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */


/**
 * send_weibo  发布微博
 * @param $content
 * @param $type
 * @param string $feed_data
 * @param string $from
 * @return bool
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function send_mob_weibo($content,$type,$feed_data = '', $from='', $crowdType='')
{

    $uid = is_login();
    $weibo_content=$content;
    $weiboTopicLink = D('Weibo/Topic')->addTopic($weibo_content);
    $weibo_id = D('Weibo')->addWeibo($uid, $weibo_content, $type, $feed_data,$from,$crowdType);
    if (!$weibo_id) {
        return false;
    }

    if (count($weiboTopicLink)) {
        foreach ($weiboTopicLink as &$val) {
            $val['weibo_id'] = $weibo_id;
        }
        unset($val);
        D('Weibo/WeiboTopicLink')->addDatas($weiboTopicLink);
    }
    action_log('add_weibo', 'weibo', $weibo_id, $uid);
    $uids = get_at_uids($content);
    send_at_message($uids, $weibo_id, $content);
    clean_query_user_cache(is_login(), array('weibocount'));
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
function send_comment($weibo_id, $content, $comment_id = 0){
    $uid = is_login();

    $result = D('WeiboComment')->addComment($uid, $weibo_id, $content, $comment_id);
    //行为日志
    action_log('add_weibo_comment', 'weibo_comment', $result, $uid);

  
    //通知微博作者
    $weibo =D('Weibo')->getWeiboDetail($weibo_id);
    D('Common/Message')->sendMessage($weibo['uid'], '评论消息' , "评论内容：$content",  'Weibo/Index/weiboDetail',array('id' => $weibo_id), is_login(), 1);
    //通知回复的人
    if ($comment_id) {
        $comment = D('WeiboComment')->getComment($comment_id);
        if ($comment['uid'] != $weibo['uid']) {
            D('Common/Message')->sendMessage($weibo['uid'], '评论消息' ,  "回复内容：$content",  'Weibo/Index/weiboDetail',array('id' => $weibo_id), is_login(), 1);
        }
    }
    D('Weibo/WeiboCache')->cleanCache($weibo_id);
    $uids = get_at_uids($content);
    $uids = array_subtract($uids, array($weibo['uid'], $comment['uid']));
    send_at_message($uids, $weibo_id, $content);
    return $result;
}



/*function send_comment_message($uid, $id, $message,$pid){

 //   $title = '评论消息';
    $from_uid = is_login();

    $type = 1;
    switch($pid){
        case 'weibo':
            $title = '评论消息';
            D('Common/Message')->sendMessage($uid,$title, $message,  'Weibo/Index/weiboDetail',array('id' => $id), $from_uid, $type);
            break;
        case 'issue':
            if($uid==is_login()){
                break;
            }
            $title =get_nickname($from_uid). '评论了您';
            D('Common/Message')->sendMessage($uid,$title, $message,  'Issue/Index/IssueContentDetail',array('id' => $id), $from_uid, $type);
            break;
        case 'news':
            if($uid==is_login()){
                break;
            }
            $title =get_nickname($from_uid). '评论了您';
            D('Common/Message')->sendMessage($uid,$title, $message,  'news/index/detail',array('id' => $id), $from_uid, $type);
            break;
    }

}*/


/**
 * send_at_message  发送@消息
 * @param $uids
 * @param $weibo_id
 * @param $content
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function send_at_message($uids, $weibo_id, $content)
{
    $my_username =get_nickname();
    foreach ($uids as $uid) {
        $message = '内容：' . $content;
        $title = $my_username . '@了您';
        $fromUid = get_uid();
        $messageType = 1;
        D('Common/Message')->sendMessage($uid, $title, $message, 'Weibo/Index/weiboDetail',array('id' => $weibo_id), $fromUid, $messageType);
    }
}

function parse_topic($content)
{
    //找出话题
    $topic = get_topic_back($content);
    if (isset($topic) && !is_null($topic)) {
        //将[topic:ID]替换成##链接
        $topics = D('Weibo/Topic')->getTopicByMap(array('id' => array('in', $topic)));
        foreach ($topics as $e) {
            $content = str_replace("[topic:" . $e['id'] . "]", "<a class='label label-badge label-info' target=\"_blank\">#" . $e['name'] . "# </a>", $content);
        }
    }

    //返回替换的文本
    return $content;
}

function get_topic_back($content)
{
    //正则表达式匹配
    $topic_pattern = "/\[topic:([0-9]+)\]/";
    preg_match_all($topic_pattern, $content, $users);

    //返回话题列表
    return array_unique($users[1]);
}

function get_topic($content){
    //正则表达式匹配
    $topic_pattern = "/#([^\\#|.]+)#/";
    preg_match_all($topic_pattern, $content, $topics);

    //返回话题列表
    return array_unique($topics[1]);
}

function group_is_exist($group_id){
    $group =  D('Group/Group')->getGroup($group_id);
    return $group ? true : false;
}

function get_group_admin($group_id){
    return D('Group/GroupMember')->getGroupAdmin($group_id);
}

//MOB模块得到用户头衔
function mob_get_head_title($user_id){
    $user_title = D('RankUser')->where(array('status' => 1, 'uid' => $user_id))->select();
    foreach($user_title as &$v){
        $v['title'] = D('Rank')->where(array( 'id' => $v['rank_id']))->find();
        $v['logo']= getThumbImageById($v['title']['logo']);
    }
    //  dump($user_title);exit;
    return $user_title;
}

function parse_weibo_mobile_content($content)
{
    $content = shorten_white_space($content);
    $content = op_t($content,false);
    $content = parse_expression($content);
    $content = parse_url_mobile_link($content);
    $content = parseWeiboContent($content);
    $content = parse_at_mob_users($content);
    $content=  parse_br_nb($content);
    $content = parse_topic($content);
    return $content;
}

/**
 * @param $content
 * @return mixed
 */
function parse_url_mobile_link($content)
{

    if (strpos($content,'forum')){
        $str=str_replace('/forum/index/detail/','/mob/forum/postDetail/',$content,$count);
        $str_new = preg_replace("#((http|https|ftp)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie",
            "'<a class=\"label label-badge\" href=\"$1\" target=\"_blank\"><i class=\"am-icon-link\" title=\"$1\"></i></a>$4'", $str
        );
        return $str_new;
    }
    elseif (strpos($content,'event')){
        $str_event=str_replace('/event/index/detail/','/mob/event/detail/',$content);
        $str_new = preg_replace("#((http|https|ftp)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie",
            "'<a class=\"label label-badge\" href=\"$1\" target=\"_blank\"><i class=\"am-icon-link\" title=\"$1\"></i></a>$4'", $str_event
        );
        return $str_new;
    }elseif(strpos($content,'group')){
        $str=str_replace('/group/index/detail/','/mob/group/detail/',$content,$count);
        $str_new = preg_replace("#((http|https|ftp)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie",
            "'<a class=\"label label-badge\" href=\"$1\" target=\"_blank\"><i class=\"am-icon-link\" title=\"$1\"></i></a>$4'", $str
        );
        return $str_new;
    }
    elseif(strpos($content,'expression_pic')){
        return $content;
    }
    else{
        $str_new = preg_replace("#((http|https|ftp)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie",
            "'<a class=\"label label-badge\" href=\"$1\" target=\"_blank\"><i class=\"am-icon-link\" title=\"$1\"></i></a>$4'", $content
        );
        return $str_new;
    }
}
function parse_br_nb($content){
    $content = str_replace('/br', '<br/>',$content);
    $content= str_replace('/nb', '&nbsp;', $content);
    return $content;
}

function parse_at_mob_users($content,$disabel_hight=false)
{
    $content = $content . ' ';
    //找出被AT的用户
    $at_users = get_at_usersnickname($content);
    //将@用户替换成链接
    foreach ($at_users as $e) {
        $user = D('Member')->where(array('nickname' => $e))->find();
        if ($user) {
            $query_user = query_user(array('space_mob_url', 'avatar32', 'nickname'), $user['uid']);

            if(modC('HIGH_LIGHT_AT',1,'Weibo') && !$disabel_hight){
                $content = str_replace("@[$e]", " <a class='user-at hl ' ucard=\"$user[uid]\" href=\"$query_user[space_mob_url]\">@$query_user[nickname] </a> ", $content);
            }else{
                $content = str_replace("@[$e]", " <a ucard=\"$user[uid]\" href=\"$query_user[space_url]\">@$query_user[nickname] </a> ", $content);
            }

        }
    }
    //返回替换的文本
    return $content;
}

function check_verify($code, $id = 1){
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}




function local_comment_textarea($param=array()){
    $html = '<div class="am-form-group weibo_post_box am-cf am-topbar-fixed-bottom" style="padding:.5rem 1rem;background-color: #f5f5f5;">';
    if (!is_login()) {
        $html.='<div  style=" position: relative; "><div class="am-g am-text-center" style="position: absolute;padding: 50px;"><span> 请先<a href="'.U('Mob/member/index').'">登录</a>
        后再评论</span></div> <textarea  rows="5"   disabled style="height: 35px; width: 70%;border-radius: 5px;border-color: #eee"></textarea></div>';
    }else{
        $html.='<textarea class="content am-fl" rows="5" id="comment_content_text"   style="height: 35px; width: 62%;border-radius: 5px;border-color: #eee"></textarea>';
    }

    $html.=' <div class="am-cf am-fl" style="margin: 5px 18px"><a href="javascript:" data-url=" '.U('Core/Expression/getSmile').'" onclick="insertFace($(this))"><img src="'.C('TMPL_PARSE_STRING.__IMG__').'/bq.png"></a> </div><div id="emot_content" class="emot_content" style="display:none"></div>';
    $html .= '<p>';
    $html.= '<button style="float: right;width: 18%;border-radius: 5px;padding:8px 0"   data-role="do_local_comment" data-url="'.U('mob/base/addLocalComment',array('uid'=>$param['uid'],'count_model'=>$param['count_model'],'count_field'=>$param['count_field'])).'" data-this-url="'.$param['this_url'].'" data-path="'.$param['path'].'"  data-extra="'.http_build_query($param['extra']).'"  type="button"  class="am-btn-warning am-btn am-btn-block am-round ">提交</button>';
    $html .= '</p></div>';
    return $html;

}



function get_local_comment_list($path,$page=1){
    $html = '<div class="localcomment_list">';
    $html .=  A('Mob/Base')->getLocalCommentList($path,$page);
    $html .='</div>';
    $html.='    <div class="am-list-news-ft"><a class=" am-btn am-btn-secondary am-btn-block" href="javascript:" data-role="show_more_localcomment" data-path="'.$path.'">查看更多 &raquo;</a> </div>';
    return $html;
}


function mobU($url = '', $vars = '', $suffix = true, $domain = false){
    $url = preg_replace("/(?<=#)[\s\S]*$/","",$url);
    $link =  require('./Application/Mob/Conf/router.php');
    $url_mob = $link['router'][$url];
    return U($url_mob, $vars , $suffix, $domain);
}
//群组
function limit_picture_count($content){
    return   D('ContentHandler')->limitPicture($content,modC('GROUP_POST_IMG_COUNT',10,'GROUP'));
}

function get_group_name($group_id){
    $group =  D('Group')->getGroup($group_id);
    return $group['title'];
}
//群组
function is_joined($group_id)
{
    return D('Group/GroupMember')->getIsJoin(is_login(),$group_id);
}
function get_reply_admin($reply_id){
    return get_admin_ids($reply_id,2,1);
}
/**
 * 权限检测时获取要排除的uids(群创建者、群组管理员、自己)
 * @param int $id
 * @param int $type
 * @param int $with_self 是否包含记录的uid
 * @return array|int|mixed
 * @author 郑钟良<zzl@ourstu.com>
 */
function get_admin_ids($id=0,$type=0,$with_self=1)
{
    $uid=0;
    switch($type){
        case '1'://根据贴子楼中楼回复id查询排除者id
            $lzl_reply=M('GroupLzlReply')->find($id);
            $uid=$lzl_reply['uid'];
            $post_id=$lzl_reply['post_id'];
            break;
        case '2'://根据贴子回复id查询排除者id
            $reply = M('GroupPostReply')->find($id);
            $uid=$reply['uid'];
            $post_id=$reply['post_id'];
            break;
        case '3'://根据贴子id查询排除者id
            $post_id=$id;
            break;
        case '4'://根据群组 id查询排除者id
            $group_id=$id;
            break;
        default:
            return -1;
    }
    if($post_id){
        $post=M('GroupPost')->where(array('id' => $post_id, 'status' => 1))->find();
        $group_id=$post['group_id'];
        if(!$uid){
            $uid=$post['uid'];
        }
    }
    $expect_ids=D('GroupMember')->getGroupAdmin($group_id);
    $group=M('Group')->find($group_id);
    if($uid&&$with_self&&$uid!=$group['uid']){
        $expect_ids[]=$group['uid'];
    }
    return $expect_ids;
}

function get_lzl_admin($lzl_id){
    return get_admin_ids($lzl_id,1,1);
}
function post_is_exist($post_id){
    $post =  D('GroupPost')->getPost($post_id);
    return $post ? true : false;
}
function get_post_admin($post_id){
    return get_admin_ids($post_id,3,1);
}
function get_group_creator($group_id){
    $group = D('Group')->getGroup($group_id);
    return $group['uid'];
}

//分类信息
function CheckCanRead($uid, $info_id)
{
    $info = D('cat_info')->find($info_id);
    return CheckCanReadEntity($uid, $info['entity_id']);
}
function CheckCanReadEntity($uid, $entity_id)
{
    return CheckCan($uid, $entity_id, 'can_read_gid');
}
function CheckCan($uid, $entity_id, $can_type)
{
    if(is_administrator())
        return true;
    $entity = D('cat_entity')->find($entity_id);
    if(trim($entity[$can_type])=='' || intval($entity[$can_type])==0){
        return true;
    }
    $gids = explode(',', $entity[$can_type]);

    $group_result = D('auth_group_access')->field('group_id')->where(array('uid' => $uid))->select();

    $user_groups = getSubByKey($group_result, 'group_id');
    $has = array_intersect($gids, $user_groups);

    if (count($has))
        return true;
    else
        return false;

}

function CheckCanPostEntity($uid, $entity_id)
{

    return CheckCan($uid, $entity_id, 'can_post_gid');
}
/**解析选项
 * @param $option_str
 * @return array
 * @auth 陈一枭
 */
function parseOption($option_str)
{
    $option_str = str_replace("\r", '', $option_str);
    $values = explode("\n", $option_str);
    foreach ($values as &$v) {
        $v = trim($v);
    }
    return $values;
}

//微店
function encodeGallary($gallary)
{
    foreach ($gallary as $g) {
        $gallary_array[] = array('id' => $g);
    }

    return json_encode($gallary_array);

}
/**解析相册
 * @param $gallary
 * @return array
 * @auth 陈一枭
 */
function decodeGallary($gallary)
{
    $gallary = json_decode($gallary, true);
    foreach ($gallary as $g) {
        $gallary_array[] = array('id' => $g['id'], 'img' => getThumbImageById($g['id'], 80, 80));
    }
    return $gallary_array;
}
function getFinalPrice($order)
{
    return $order['total_cny'] + $order['adj_cny'];
}
//获取手机网页版主页
function getIndexUrl(){
$indexId=modC('TITLE',0,'MOB');
    $indexUrl= M('MobChannel')->where(array('status'=>1,'id'=>$indexId+1))->find();
return $indexUrl['url'];
}

//传ID，直接获取省份。
function getProvince($pid){
    $province=M('District')->where(array('id'=>$pid,'level'=>1))->find();
        return $province['name'];
}
//传ID，直接获取城市。
function getCity($cid){
    $city=M('District')->where(array('id'=>$cid,'level'=>2))->find();
    return $city['name'];
}
//传ID，直接获取县。
function getDistrict($cid){
    $district=M('District')->where(array('id'=>$cid,'level'=>3))->find();
    return $district['name'];
}
/**
 * 资讯友好时间显示
 * 路飞
 */
function friendlyDateNews($sTime,$type = 'normal',$alt = 'false') {
    if (!$sTime)
        return '';
    //sTime=源时间，cTime=当前时间，dTime=时间差
    $cTime      =   time();
    $dTime      =   $cTime - $sTime;
    $dDay       =   intval(date("z",$cTime)) - intval(date("z",$sTime));
    //$dDay     =   intval($dTime/3600/24);
    $dYear      =   intval(date("Y",$cTime)) - intval(date("Y",$sTime));
    //normal：n秒前，n分钟前，n小时前，日期
    if($type=='normal'){
        if( $dTime < 60 ){
            if($dTime < 10){
                return L('_JUST_');    //by yangjs
            }else{
                return intval(floor($dTime / 10) * 10).L('_SECONDS_AGO_');
            }
        }elseif( $dTime < 3600 ){
            return intval($dTime/60).L('_MINUTES_AGO_');
            //今天的数据.年份相同.日期相同.
        }elseif( $dYear==0 && $dDay == 0  ){
            //return intval($dTime/3600).L('_HOURS_AGO_');
            return date('H:i',$sTime);
        }elseif($dYear==0){
            return date("m月d日",$sTime);
        }else{
            return date("Y-m-d",$sTime);
        }
    }elseif($type=='mohu'){
        if( $dTime < 60 ){
            return $dTime.L('_SECONDS_AGO_');
        }elseif( $dTime < 3600 ){
            return intval($dTime/60).L('_MINUTES_AGO_');
        }elseif( $dTime >= 3600 && $dDay == 0  ){
            return intval($dTime/3600).L('_HOURS_AGO_');
        }elseif( $dDay > 0 && $dDay<=7 ){
            return intval($dDay).L('_DAYS_AGO_');
        }elseif( $dDay > 7 &&  $dDay <= 30 ){
            return intval($dDay/7) . L('_WEEK_AGO_');
        }elseif( $dDay > 30 ){
            return intval($dDay/30) . L('_A_MONTH_AGO_');
        }
        //full: Y-m-d , H:i:s
    }elseif($type=='full'){
        return date("Y-m-d , H:i:s",$sTime);
    }elseif($type=='ymd'){
        return date("Y-m-d",$sTime);
    }else{
        if( $dTime < 60 ){
            return $dTime.L('_SECONDS_AGO_');
        }elseif( $dTime < 3600 ){
            return intval($dTime/60).L('_MINUTES_AGO_');
        }elseif( $dTime >= 3600 && $dDay == 0  ){
            return intval($dTime/3600).L('_HOURS_AGO_');
        }elseif($dYear==0){
            return date("Y-m-d H:i:s",$sTime);
        }else{
            return date("Y-m-d H:i:s",$sTime);
        }
    }
}

function getMobMessageUrl($web_url)
{
    $mobMessageUrl = S('Mob_Message_Url_config');
    if ($mobMessageUrl === false) {
        $mobMessageUrl = include_once(APP_PATH . 'Mob/Conf/message_url.php');
        S('Mob_Message_Url_config', $mobMessageUrl);
    }
    return $mobMessageUrl[strtolower($web_url)];
}
/**
 * 筛选出可以管理圈子的人
 * @return mixed
 */
function get_crowd_admin($crowd_id)
{
    $data = D('Mob/WeiboCrowdMember')->getCrowdAdmin($crowd_id);
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