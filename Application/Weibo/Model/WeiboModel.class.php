<?php
namespace Weibo\Model;

use Think\Model;
use Think\Hook;
use Think\Page;

require_once('./Application/Weibo/Common/function.php');

class WeiboModel extends Model
{
    protected $_validate = array(
        array('content', '1,99999', '内容不能为空', self::EXISTS_VALIDATE, 'length'),
        array('content', '0,65535', '内容太长', self::EXISTS_VALIDATE, 'length'),
    );

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
    );

    public function addWeibo($uid, $content = '', $type = 'feed', $feed_data = array(), $from = '', $pos = '',$crowdType = '')
    {
        $aContent = I('post.content', '', 'html');
        if ($content == '') {
            $content = str_replace(' ', '/nb', $aContent);
        } else {
            $content = str_replace(' ', '/nb', $content);
        }
        $content = nl2br($content);
        $content = str_replace('<br />', '/br ', $content);
        $content = text($content);
        //$topic = get_topic($content);
        preg_match_all('/[\x{4e00}-\x{9fa5}a-zA-Z0-9]/u', $pos, $matches);
        $pos = join('', $matches[0]);

        //写入数据库
        $data = array('uid' => $uid, 'content' => $content, 'type' => $type, 'data' => serialize($feed_data), 'from' => $from, 'pos' => $pos ,'crowd_id'=>$crowdType);
        $data = $this->create($data);
        if (!$data) return false;
        $weibo_id = $this->add($data);

        //返回动态编号
        return $weibo_id;
    }

    public function addLongWeibo($uid, $content, $crowdId, $long_content,$long_title){
        $content='str:'.$content;
        $content=text($content);
        $content = str_replace(' ', '/nb', $content);
        $content=substr($content,4);
        $content = nl2br($content);
        $content = str_replace('<br />', '/br ', $content);
        $content=substr($content,0,500);
        //写入数据库
        $data = array('uid' => $uid, 'content' => $content, 'type' => 'long_weibo', 'data' => '', 'from' => '', 'pos' => '' ,'crowd_id'=>$crowdId);
        $data = $this->create($data);
        if (!$data) return false;
        $weibo_id = $this->add($data);
        if(!$weibo_id){
            return false;
        }
        $longWeibo['weibo_id']=$weibo_id;
        $longWeibo['long_content']=$long_content;
        $longWeibo['title']=$long_title;
        $long_id=M('WeiboLong')->add($longWeibo);
        if(!$long_id){
            $this->deleteWeibo($weibo_id);
            return false;
        }
        return $weibo_id;
    }

    public function editLongWeibo($content, $crowdId, $long_content,$long_title,$id){
        $content='str:'.$content;
        $content=text($content);
        $content = str_replace(' ', '/nb', $content);
        $content=substr($content,4);
        $content = nl2br($content);
        $content = str_replace('<br />', '/br ', $content);
        $content=substr($content,0,500);
        //写入数据库
        $data = array( 'content' => $content, 'type' => 'long_weibo', 'data' => '', 'from' => '', 'pos' => '' ,'crowd_id'=>$crowdId,'id'=>$id);
        $data = $this->create($data);
        if (!$data) return false;

        $this->save($data);

        $longWeibo['long_content']=$long_content;
        $longWeibo['title']=$long_title;

        M('WeiboLong')->where(array('weibo_id'=>$id))->save($longWeibo);
        return true;
    }


    public function getWeiboCount($map)
    {
        return $this->where($map)->count();
    }

    public function getWeiboList($param = null)
    {
        !empty($param['field']) && $this->field($param['field']);
        !empty($param['where']) && $this->where($param['where']);
        !empty($param['limit']) && $this->limit($param['limit']);
        empty($param['order']) && $param['order'] = 'create_time desc';
        !empty($param['page']) && $this->page($param['page'], empty($param['count']) ? 10 : $param['count']);
        $this->order($param['order']);
        $list = $this->select();
        $list = getSubByKey($list, 'id');
        return $list;
    }


    public function getWeiboDetail($id)
    {
        $weibo = S('weibo_' . $id);
        $check_empty = empty($weibo);
        if ($check_empty) {
            $weibo = $this->where(array('status' => 1, 'id' => $id))->find();
            if (!$weibo) {
                return null;
            }
            $before_weibo = $this->where(array('status' => 1, 'create_time' => array('lt', $weibo['create_time']), 'uid' => $weibo['uid']))->find();
            if (!$before_weibo) {
                $weibo['is_first'] = 1;
            }
            $weibo_data = unserialize($weibo['data']);
            $class_exists = true;

            $type = array('repost', 'feed', 'image', 'share', 'redbag','long_weibo');
            if (!in_array($weibo['type'], $type)) {
                $class_exists = class_exists('Addons\\Insert' . ucfirst($weibo['type']) . '\\Insert' . ucfirst($weibo['type']) . 'Addon');
            }
            if($weibo['type']==='long_weibo'){
                $weibo['content'] = str_replace('/br', '<br/>', $weibo['content']);
                $weibo['content'] = str_replace('/nb', '&nbsp;', $weibo['content']);
                $weibo['long_weibo']=$long_weibo=M('WeiboLong')->where(array('weibo_id'=>$weibo['id']))->find();
            }else{
                $weibo['content'] = parse_topic(parse_weibo_content($weibo['content']));
            }

            if (!empty($weibo['crowd_id'])) {
                $crowd = D('Weibo/WeiboCrowd')->getCrowd($weibo['crowd_id']);
            }

            $stamp = M('stamp_weibo')->where(array('weibo_id'=>$id,'status'=>1))->find();
            $stampDetail = D('Weibo/WeiboStamp')->getStamp($stamp['stamp_id']);

            if($weibo['type'] !== 'feed' && $weibo['type'] == '' &&!$class_exists){
                $switch_i='';
            }else{
                $switch_i=$weibo['type'];
            }
            switch ($switch_i){
                case '':
                case 'feed':
                    $fetchContent = "<div class='word-wrap'>" . $weibo['content'] . "</div>";
                    break;
                case 'repost':
                    $fetchContent = A('Weibo/Type')->fetchRepost($weibo);
                    break;
                case 'image':
                    $fetchContent = A('Weibo/Type')->fetchImage($weibo);
                    break;
                case 'voice':
                    $fetchContent = '录音，暂时仅移动客户端支持';
                    break;
                case 'share':
                    $fetchContent = R('Weibo/Share/getFetchHtml', array('param' => unserialize($weibo['data']), 'weibo' => $weibo), 'Widget');
                    break;
                case 'redbag':
                    $fetchContent = Hook::exec('Addons\\RedBag' . '\\' . 'RedBagAddon', 'fetch' . ucfirst($weibo['type']), $weibo);
                    break;
                case 'long_weibo':
                    $fetchContent = A('Weibo/Type')->fetchLongWeibo($weibo);
                    break;
                case 'question' :
                    $fetchContent = A('Weibo/Type')->fetchQuestion($weibo);
                    break;
                default:
                    $fetchContent = Hook::exec('Addons\\Insert' . ucfirst($weibo['type']) . '\\Insert' . ucfirst($weibo['type']) . 'Addon', 'fetch' . ucfirst($weibo['type']), $weibo);
                    break;
            }
            if($weibo['comment_count']>=modC('HOT_WEIBO_COMMENT_NUM',10,'Weibo')){
                $is_hot=1;
            }
            $weibo = array(
                'id' => intval($weibo['id']),
                'content' => strval($weibo['content']),
                'create_time' => intval($weibo['create_time']),
                'type' => $weibo['type'],
                'data' => unserialize($weibo['data']),
                'weibo_data' => $weibo_data,
                'comment_count' => intval($weibo['comment_count']),
                'repost_count' => intval($weibo['repost_count']),
                'can_delete' => 0,
                'is_top' => $weibo['is_top'],
                'is_crowd_top' => $weibo['is_crowd_top'],
                'uid' => $weibo['uid'],
                'fetchContent' => $fetchContent,
                'from' => $weibo['from'],
                'pos' => $weibo['pos'],
                'crowd' => parse_crowd($weibo['crowd_id']),
                'crowd_id' => $weibo['crowd_id'],
                'crowd_logo' => $crowd['logo'],
                'user' => query_user(array('uid', 'avatar64', 'space_url', 'rank_link', 'title', 'nickname' ,'avatar_html64'), $weibo['uid']),
                'is_first' => $weibo['is_first'],
                'status' => $weibo['status'],
                'is_hot'=> $is_hot?1:0,
                'stamp_id'=> $stampDetail['id'],
                'stamp_img'=> $stampDetail['stamp_img']
            );
            if($weibo['type']==='long_weibo'){
                $weibo['long_weibo']=$long_weibo;
            }
            S('weibo_' . $id, $weibo);
        }

        $weibo['fetchContent'] = parse_at_users($weibo['fetchContent']);
        $weibo['user']['nickname'] = get_nickname($weibo['uid']);
        $weibo['can_delete'] = $this->canDeleteWeibo($weibo);
        $weibo['can_set_top_crowd_weibo'] = $this->_canSetTopCrowdWeibo($weibo);
        // 判断转发的原动态是否已经删除
        if ($weibo['type'] == 'repost') {
            $source_weibo = $this->getWeiboDetail($weibo['weibo_data']['sourceId']);
            if (!$source_weibo['uid']) {
                if (!$check_empty) {
                    S('weibo_' . $id, null);
                    $weibo = $this->getWeiboDetail($id);
                }
            }
        }
        return $weibo;
    }


    private function canDeleteWeibo($weibo)
    {
        //如果是管理员，则可以删除动态
        if (check_auth('Weibo/Index/doDelWeibo', $weibo['uid'])) {
            return true;
        }

        if (!empty($weibo['crowd_id'])) {
            if (get_crowd_admin($weibo['crowd_id']) == is_login()) {
                return true;
            }
        }

        //返回，不能删除动态
        return false;
    }


    public function deleteWeibo($weibo_id)
    {
        $weibo = $this->getWeiboDetail($weibo_id);


        //从数据库中删除动态、以及附属评论
        $result = $this->where(array('id' => $weibo_id))->save(array('status' => -1, 'comment_count' => 0));
        D('Weibo/WeiboComment')->where(array('weibo_id' => $weibo_id))->setField('status', -1);

        if ($weibo['type'] == 'repost') {
            $this->where(array('id' => $weibo['weibo_data']['sourceId']))->setDec('repost_count');
            S('weibo_' . $weibo['weibo_data']['sourceId'], null);
        }

        S('weibo_' . $weibo_id, null);
        return $result;
    }

    public function getSupportedPeople($weibo_id, $user_fields = array('nickname', 'space_url', 'avatar128', 'space_link'), $num = 8)
    {
        $user_fields == null ? array('nickname', 'space_url', 'avatar128', 'space_link') : $user_fields;
        $supported = D('Support')->getSupportedUser('Weibo', 'weibo', $weibo_id, $user_fields, $num);

        return $supported;
    }

    public function getCrowdWeiboNum($uid = '',$crowd_id = '')
    {
        $tag = 'crowd_in_'.$crowd_id.'_by_'.$uid;
        $num = S($tag);
        if (empty($num)) {
            $num = $this->where(array('uid'=>$uid,'crowd_id'=>$crowd_id))->count();
            S($tag,$num,60*60);
        }
        return $num;
    }

    public function getSuggestedFollows($uid = '',$value = '',$limit = '')
    {
        $follow_list = D('Follow')->getFollowList();
        $ids = implode(',', $follow_list);
        $memberModel = D('Member');
        $tag = 'suggested_follows_' . $uid;
        $user_list = S($tag);
        if($user_list === false) {
            $user_list = $memberModel->where(array('status' => 1, 'uid' => array('not in', $ids), 'fans' => array('gt', $value)))->field('uid,fans')->order('fans desc,uid asc')->limit($limit)->select();
            foreach ($user_list as &$val) {
                $temp_user = query_user(array('nickname', 'avatar32', 'space_url'), $val['uid']);
                $val['nickname'] = $temp_user['nickname'];
                $val['avatar128'] = $temp_user['avatar128'];
                $val['space_url'] = $temp_user['space_url'];
            }
            unset($val);
            S($tag, $user_list, 60*60);
        }

        return $user_list;
    }

    /**
     * 圈子功能，记录圈子动态最新回复时间
     */
    public function weiboNewReplyTime($weibo_id = '')
    {
        $this->where(array('id'=>$weibo_id))->setField('reply_time',time());
    }

    private function _canSetTopCrowdWeibo($weibo)
    {
        //如果是管理员，则可以置顶
        if (check_auth(null)) {
            $crowd = D('Weibo/WeiboCrowd')->getCrowd($weibo['crowd_id']);
            if (!$crowd) {
                return false;
            } else {
                return true;
            }
        }

        if (!empty($weibo['crowd_id'])) {
            if (get_crowd_admin($weibo['crowd_id']) == is_login()) {
                return true;
            }
        }

        //返回，不能删除动态
        return false;
    }

    /**修改微博圈子
     * @param $weibo_id
     * @param $crowd_id
     * @return bool
     * @author:zzl(郑钟良) zzl@ourstu.com
     */
    public function changeWeiboCrowd($weibo_id, $crowd_id)
    {
        $res = $this->where(array('id' => $weibo_id))->setField('crowd_id', $crowd_id);
        if ($res) {
            S('weibo_' . $weibo_id, null);
            clean_weibo_html_cache($weibo_id);
        }
        return $res;
    }
}