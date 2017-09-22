<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/28
 * Time: 15:48
 */

namespace Mob\Model;

use Think\Model;

class WeiboCrowdScoreModel extends Model
{
    protected $tableName = 'weibo_crowd_score';

    public function addScore($crowd)
    {
        $res = $this->where(array('crowd_id'=>$crowd['id']))->setInc('score'.$crowd['pay_type'],$crowd['need_pay']);
        if (!$res) {
            $data['score'.$crowd['pay_type']] = $crowd['need_pay'];
            $data['crowd_id'] = $crowd['id'];
            $this->where(array('crowd_id'=>$crowd['id']))->add($data);
        }
    }

    public function getScore($crowd_id)
    {
        $tag = 'crowd_score_by_'.$crowd_id;
        $score = S($tag);
        if (empty($score)) {
            $score = $this->where(array('id'=>$crowd_id,'status'=>1))->find();
            S($tag,$score,60*60);
        }
        return $score;
    }
}