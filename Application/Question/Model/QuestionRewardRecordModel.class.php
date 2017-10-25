<?php
/**
 * Created by PhpStorm.
 * User: 王杰
 * Date: 2017/3/15
 * Time: 18:55
 * @author 王杰(O记_Andy) wj@ourstu.com
 */

namespace Question\Model;

use Think\Model;

/**
 * Class QuestionRewardRecordModel
 * @package Question\Model
 * @author 王杰(O记_Andy) wj@ourstu.com
 */
class QuestionRewardRecordModel extends Model
{
    /**
     * @param int $id
     * @return mixed
     * @author 王杰(O记_Andy) wj@ourstu.com
     */
    public function getRecord($id = 0)
    {
        $tag = 'question_reward_record_'.$id;
        $record = S($tag);
        if (empty($record)) {
            $record = $this->where(array('id'=>$id))->find();
            S($tag,$record);
        }
        return $record;
    }

    /**
     * @param array $data
     * @return mixed
     * @author 王杰(O记_Andy) wj@ourstu.com
     */
    public function addRecord($data = array())
    {
        $res = $this->add($data);
        return $res;
    }

}