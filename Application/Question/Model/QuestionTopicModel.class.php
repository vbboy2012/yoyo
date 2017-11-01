<?php
/**
 * Created by PhpStorm.
 * User: 王杰
 * Date: 2017/3/14
 * Time: 10:45
 * @author 王杰(O记_Andy) wj@ourstu.com
 */

namespace Question\Model;

use Think\Model;

/**
 * Class QuestionTopicModel
 * @package Question\Model
 * @author 王杰(O记_Andy) wj@ourstu.com
 */
class QuestionTopicModel extends Model
{
    /**
     * @param $topic_id
     * @return mixed
     * @author 王杰(O记_Andy) wj@ourstu.com
     */
    public function getTopic($topic_id)
    {
        $tag = 'question_topic_'.$topic_id;
        $topic = S($tag);
        if (empty($topic)) {
            $topic = $this->where(array('id'=>$topic_id,'status' => array('egt', 0)))->find();
            S($tag,$topic,60*60*6);
        }
        return $topic;
    }

    /**
     * @param $content
     * @return mixed
     * @author 王杰(O记_Andy) wj@ourstu.com
     */
    public function getTopicByTitle($content)
    {
        $tag = 'question_topic_'.$content;
        $topic = S($tag);
        if (empty($topic)) {
            $topic = $this->where(array('title'=>$content))->find();
            S($tag,$topic,60*60*6);
        }
        return $topic;
    }

    /**
     * @param $content
     * @return string
     * @author 王杰(O记_Andy) wj@ourstu.com
     */
    public function addTopicArray($content)
    {
        $topicIds = array();
        foreach ($content as $v) {
            $topic = $this->getTopicByTitle($v);
            if ($topic) {
                array_push($topicIds,$topic['id']);
                $res = $this->where(array('title'=>$v))->setInc('num');
            } else {
                $data['status'] = 1;
                $data['create_time'] = time();
                $data['title'] = $v;
                $data['num'] = 1;
                $res = $this->add($data);
                array_push($topicIds,$res);
            }
        }
        return implode(',',$topicIds);
    }

    /**
     * @param $content
     * @return bool|mixed
     * @author 王杰(O记_Andy) wj@ourstu.com
     */
    public function addTopic($content)
    {
        $topic = $this->getTopicByTitle($content['title']);
        if ($topic) {
            array_push($topicIds,$topic['id']);
            $res = $this->where(array('title'=>$content['title'],'status'=>1))->setInc('num');
        } else {
            $res = $this->add($content);
            array_push($topicIds,$res);
        }
        return $res;
    }

    /**
     * @param $id
     * @param $data
     * @return bool
     * @author 王杰(O记_Andy) wj@ourstu.com
     */
    public function editTopic($id, $data)
    {
        $res = $this->where(array('id'=>$id))->save($data);
        return $res;
    }

    /**
     * @param $limit
     * @return mixed
     * @author 王杰(O记_Andy) wj@ourstu.com
     */
    public function getHotTopicList($limit)
    {
        $list = $this->where(array('status' => 1))->limit($limit)->order('num desc')->select();
        return $list;
    }
}