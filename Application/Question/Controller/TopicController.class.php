<?php
/**
 * Created by PhpStorm.
 * User: 王杰
 * Date: 2017/3/14
 * Time: 13:29
 * @author 王杰(O记_Andy) wj@ourstu.com
 */

namespace Question\Controller;

use Think\Controller;

class TopicController extends Controller
{
    public function index()
    {
        $uid = is_login();

        $user['all_ask_num'] = D('Question/Question')->where(array('uid'=>$uid,'status'=>1))->count();
        $user['all_answer_num'] = D('Question/QuestionAnswer')->where(array('uid'=>$uid,'status'=>1))->count();
        $user['user'] = query_user(array('nickname','uid', 'avatar128', 'con_check', 'total_check', 'space_url'),$uid);
        $this->assign('user',$user);

        $list = D('QuestionTopic')->getHotTopicList(10);
        foreach ($list as &$v) {
            $v['seven_day_news'] = D('Question')->where(array('create_time'=>array('gt',time()-60*60*24*7),'topic_id'=>array('exp',"like '{$v['id']},%'"."or topic_id like '%,{$v['id']},%'"." or topic_id like '%,{$v['id']}'")))->count();
            $v['thirty_day_news'] = D('Question')->where(array('create_time'=>array('gt',time()-60*60*24*30),'topic_id'=>array('exp',"like '{$v['id']},%'"."or topic_id like '%,{$v['id']},%'"." or topic_id like '%,{$v['id']}'")))->count();
        }
        unset($v);
        $this->assign('list',$list);

        //最新问题
        $map['status'] = 1;
        $map['update_time'] = array('gt', get_time_ago('month', 1));
        $map['best_answer'] = 0;
        list($newsList, $totalCount) = D('Question')->getListPageByMap($map, 1, 'create_time desc', 10, '*');
        foreach ($newsList as &$val) {
            $val['info'] = mb_substr(text($val['description']), 0, 200,'utf-8');
            $val['title'] = mb_substr(text($val['title']), 0, 200,'utf-8');
            $val['img'] = get_pic($val['description']);
            $val['user'] = query_user(array('uid', 'space_link', 'nickname', 'avatar128'), $val['uid']);
        }
        unset($val);
        $this->assign('new_list', $newsList);
        $this->display();
    }

}