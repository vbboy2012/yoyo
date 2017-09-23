<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Weibo\Widget;

use Think\Controller;


class CommentWidget extends Controller
{

    /* 显示指定分类的同级分类或子分类列表 */
    public function detail($comment_id,$un_prase_comment=0)
    {
        $comment = D('Weibo/WeiboComment')->getComment($comment_id);
        $this->assign('comment', $comment);
        $html = $this->fetch(T('Weibo@default/Widget/comment/comment'));
        if(!$un_prase_comment){
            $html = replace_weibo_html($html);
        }
        $this->show($html);
    }

    public function comment_html($comment_id)
    {
        $comment = D('Weibo/WeiboComment')->getComment($comment_id);
        $this->assign('comment', $comment);
        $html = $this->fetch(T('Weibo@default/Widget/comment/comment'));
        $html = replace_weibo_html($html);
        return $html;
    }

    public function someComment($weibo_id,$un_prase_comment=0)
    {

        $comments = D('Weibo/WeiboComment')->getCommentList($weibo_id, 1);

        $weobo = D('Weibo/Weibo')->getWeiboDetail($weibo_id);

        $this->assign('weiboCommentTotalCount', $weobo['comment_count']);
        $this->assign('comments', $comments);
        $this->assign('weiboId', $weibo_id);
        $this->assign('un_prase_comment',$un_prase_comment);
        $this->assign('page', 1);
        $this->display(T('Weibo@default/Widget/comment/somecomment'));
    }

    public function someCommentHtml($weibo_id)
    {

        $comments = D('Weibo/WeiboComment')->getCommentList($weibo_id, 1);
        $weibo = D('Weibo/Weibo')->getWeiboDetail($weibo_id);
        $this->assign('weiboCommentTotalCount', $weibo['comment_count']);
        $this->assign('comments', $comments);
        $this->assign('weiboId', $weibo_id);
        $this->assign('page', 1);
        return $this->fetch(T('Weibo@default/Widget/comment/somecomment'));
    }


}
