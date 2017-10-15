<?php
/**
 * Created by PhpStorm.
 * User: 王杰
 * Date: 2016/12/7
 * Time: 15:58
 */
namespace Weibo\Model;

use Think\Model;
use Think\Hook;

require_once('./Application/Weibo/Common/function.php');

class WeiboModel extends Model
{

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
    );

    public function addWeibo($uid, $content = '', $type = 'feed', $feed_data = array(), $from = '', $crowdType = '', $goods)
    {
        $aContent = I('post.content', '', 'html');
        $content = $content == '' ? $aContent : $content;
        $content = render_url_to_short($content);
        $content = str_replace(' ', '/nb', $content);
        $content = nl2br($content);
        $content = str_replace('<br />', '/br ', $content);
        $content = text($content);

        $data = array('uid' => $uid, 'content' => $content, 'type' => $type, 'data' => serialize($feed_data), 'from' => $from, 'crowd_id' => $crowdType, 'goods_id' => $goods);
        $data = $this->create($data);
        if (!$data) return false;
        $weibo_id = $this->add($data);

        //返回动态编号
        return $weibo_id;
    }


    public function addLongWeibo($uid, $title, $content, $crowdId, $feed_data = array(), $from = '')
    {

        $aContent = I('post.content', '', 'article_html');

        $content = $content == '' ? $aContent : $content;
        $content1 = get_short_sp(text($content), 100);
        //写入数据库
        $data = array('uid' => $uid, 'content' => $content1, 'type' => 'long_weibo', 'data' => serialize($feed_data), 'from' => $from, 'pos' => '', 'crowd_id' => $crowdId);
        $data = $this->create($data);
        if (!$data) return false;
        $weibo_id = $this->add($data);
        if (!$weibo_id) {
            return false;
        }
        $longWeibo['weibo_id'] = $weibo_id;
        $longWeibo['long_content'] = $content;
        $longWeibo['title'] = $title;
        $long_id = M('WeiboLong')->add($longWeibo);
        if (!$long_id) {
            $this->deleteWeibo($weibo_id);
            return false;
        }
        return $weibo_id;
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

            $type = array('repost', 'LocalVideo', 'feed', 'image', 'share', 'goods','redbag', 'long_weibo','voice','question');
            if (!in_array($weibo['type'], $type)) {
                $class_exists = class_exists('Addons\\Insert' . ucfirst($weibo['type']) . '\\Insert' . ucfirst($weibo['type']) . 'Addon');
            }
            $weibo['content'] = parse_topic(parse_weibo_content($weibo['content']));

            if ($weibo['type'] === 'feed' || $weibo['type'] == '' || !$class_exists) {
                $fetchContent = '<p class="word-wrap">' . $weibo['content'] . '</p>';
            } elseif ($weibo['type'] === 'repost') {
                $fetchContent = A('Weibo/Type')->fetchRepost($weibo);
            } elseif ($weibo['type'] === 'image') {
                $fetchContent = A('Weibo/Type')->fetchImage($weibo);
            } elseif ($weibo['type'] === 'goods') {
                $fetchContent = A('Weibo/Type')->fetchGoods($weibo);
            } elseif ($weibo['type'] === 'redbag') {
                $fetchContent = A('Weibo/Type')->fetchRedBag($weibo);
            } elseif ($weibo['type'] == 'long_weibo') {
                $weibo['long_weibo'] = $long_weibo = M('WeiboLong')->where(array('weibo_id' => $weibo['id']))->find();
                $fetchContent = A('Weibo/Type')->fetchLongWeibo($weibo);
            }elseif ($weibo['type']=='voice'){
                $fetchContent=A('Weibo/Type')->fetchVoice($weibo);
            }elseif ($weibo['type'] === "LocalVideo") {
                $fetchContent=A('Weibo/Type')->fetchlocalvideo($weibo);
            }
            elseif ($weibo['type']=='share'){
                $fetchContent = A('Weibo/Type')->fetchShare($weibo);
            }
            elseif ($weibo['type']=='question'){
                $fetchContent=A('Weibo/Type')->fetchQuestion($weibo);
            }
            else {
                $fetchContent = Hook::exec('Addons\\Insert' . ucfirst($weibo['type']) . '\\Insert' . ucfirst($weibo['type']) . 'Addon', 'fetch' . ucfirst($weibo['type']), $weibo);
            }
            if ($weibo['comment_count'] >= modC('HOT_WEIBO_COMMENT_NUM', 10, 'Weibo')) {
                $is_hot = 1;
            }
            $weibo = array(
                'id' => intval($weibo['id']),
                'uid' => $weibo['uid'],
                'content' => strval($weibo['content']),
                'create_time' => intval($weibo['create_time']),
                'comment_count' => intval($weibo['comment_count']),
                'type' => $weibo['type'],
                'repost_count' => intval($weibo['repost_count']),
                'from' => $weibo['from'],
                'pos' => $weibo['pos'],
                'weibo_data' => $weibo_data,
                'can_delete' => 0,
                'is_first' => $weibo['is_first'],
                'fetchContent' => $fetchContent,
                'crowd_id' => $weibo['crowd_id'],
                'is_hot' => $is_hot ? 1 : 0
            );
            if ($weibo['type'] === 'long_weibo') {
                $long_weibo = M('WeiboLong')->where(array('weibo_id' => $weibo['id']))->find();
                $weibo['long_weibo'] = $long_weibo;
            }
            S('weibo_' . $id, $weibo);
        }
        $count = $this->_renderCount($id);
        $weibo['comment_count'] = $count['comment_count'];
        $weibo['repost_count'] = $count['repost_count'];
        $weibo['support_count'] = $count['support_count'];
        $weibo['user'] = query_user(array('avatar_html128','uid', 'nickname', 'avatar64', 'space_url', 'rank_link', 'title'), $weibo['uid']);
        $weibo['can_delete'] = $this->canDeleteWeibo($weibo);
        // 判断转发的原微博是否已经删除
        if ($weibo['type'] == 'repost') {
            $source_weibo = $this->getWeiboDetail($weibo['weibo_data']['sourceId']);
            if (!$source_weibo['uid']) {
                if (!$check_empty) {
                    S('weibo_' . $id, null);
                    $weibo = $this->getWeiboDetail($id);
                }
            }
        }
        $support['appname'] = 'Weibo';
        $support['table'] = 'weibo';
        $support['uid'] = is_login();
        $support['row'] = $id;
        $suport = D('Support')->where($support)->count();
        if ($suport) {
            $weibo['is_support'] = '1';
        } else {
            $weibo['is_support'] = '0';
        }
        return $weibo;
    }

    private function canDeleteWeibo($weibo)
    {
        if (is_administrator(get_uid()) || check_auth('deleteWeibo')) {
            return true;
        }
        if ($weibo['uid'] == get_uid()) {
            return true;
        }
        return false;
    }

    private function _renderCount($id)
    {
        $tag = 'weibo_count_by_' . $id;
        $count = S($tag);
        if (empty($count)) {
            $count = $this->where(array('id' => $id))->field('comment_count,repost_count')->find();
            $count['support_count'] = M('Support')->where(array('appname' => 'Weibo', 'row' => $id))->count();
            S($tag, $count, 60 * 60);
        }
        return $count;
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

    public function getWeiboCount($map)
    {
        return $this->where($map)->count();
    }

    public function deleteWeibo($weibo_id)
    {
        $weibo = $this->getWeiboDetail($weibo_id);
        //从数据库中删除动态、以及附属评论
        $result = $this->where(array('id' => $weibo_id))->save(array('status' => -1, 'comment_count' => 0));
        D('Weibo/WeiboComment')->where(array('weibo_id' => $weibo_id))->setField('status', -1);
        D('Weibo/WeiboTop')->where(array('weibo_id' => $weibo_id))->setField('status', -1);
        if ($weibo['type'] == 'repost') {
            $this->where(array('id' => $weibo['weibo_data']['sourceId']))->setDec('repost_count');
            S('weibo_' . $weibo['weibo_data']['sourceId'], null);
        }

        S('weibo_' . $weibo_id, null);
        return $result;
    }

    public function getCrowdWeiboNum($uid = '', $crowd_id = '')
    {
        $tag = 'crowd_in_' . $crowd_id . '_by_' . $uid;
        $num = S($tag);
        if (empty($num)) {
            $num = $this->where(array('uid' => $uid, 'crowd_id' => $crowd_id))->count();
            S($tag, $num, 60 * 60);
        }
        return $num;
    }
    public function getInfo($param)
    {
        $info = array();
        if(!empty($param['app']) && !empty($param['model']) && !empty($param['method'])){
            $info = D($param['app'].'/'.$param['model'])->$param['method']($param['id']);
        }
        return $info;
    }
}