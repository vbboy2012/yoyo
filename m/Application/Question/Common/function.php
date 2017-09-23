<?php

function send_answer($Question_id, $content)
{
    $uid = is_login();
    $result = D('QuestionAnswer')->addAnswer($uid, $Question_id, $content);
//行为日志
    //action_log('question_post_reply', 'question', $result, $uid);

//通知提问者
    $user_id = D('question')->where(array('id'=>$Question_id))->getField('uid');
    send_message($user_id,'有人回答了您的问题','有人回答了您的问题，快去看看吧','Question/Index/detail', array('id' => $Question_id));

    return $result;
}