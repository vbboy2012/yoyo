<?php if (!defined('THINK_PATH')) exit();?><div style="max-width: 680px;<?php if($can_hide): ?>[top_hide]<?php endif; ?>" data-role="id_weibo" id="weibo_<?php echo ($weibo["id"]); ?>" <?php if($can_hide): ?>class="top_can_hide"<?php else: ?>class=""<?php endif; ?>>
    <div class="all-wrap">
        <?php if($weibo['is_top'] == 1 AND $crowd_weibo_list != 1): ?><div class="ribbion-green"></div>
            <?php elseif($weibo['is_hot'] == 1 AND $crowd_weibo_list != 1): ?>
            <div class="hot-comment-weibo"></div>
            <?php elseif($weibo['is_first'] == 1 AND $crowd_weibo_list != 1): ?>
            <div class="new-user-first-weibo"></div><?php endif; ?>
        <?php if(($crowd_weibo_list) == "1"): if(($weibo["is_crowd_top"]) == "1"): ?><div class="ribbion-green"></div><?php endif; endif; ?>
        <div class="weibo-content">
            <div class="content-head">
                <div class="avat-box pull-left">
                    <a href="<?php echo ($weibo["user"]["space_url"]); ?>" ucard="<?php echo ($weibo["user"]["uid"]); ?>">
                        <?php echo ($weibo["user"]["avatar_html128"]); ?>
                    </a>
                    <div class="show-follow pull-right">
                        <div class="follow-btn" style="display: none;">
                            [follow:<?php echo ($weibo['uid']); ?>]
                        </div>
                    </div>
                </div>
                <div class="op-box pull-right">
                    <div class="op-tb op-top">
                        <a ucard="<?php echo ($weibo["user"]["uid"]); ?>" href="<?php echo ($weibo["user"]["space_url"]); ?>" class="user_name">
                            [nickname:<?php echo ($weibo['uid']); ?>]
                        </a>
                        <?php if(modC('SHOW_TITLE',1)): ?><small class="font_grey"><?php echo ($weibo["user"]["title"]); ?></small><?php endif; ?>
                        <?php echo W('Common/UserRank/render',array($weibo['uid']));?>
                        <!--隐藏操作列表-->
                        <div class="pull-right show-operate-wrap">
                            <a href="javascript:" class="show-operate pull-right icon-angle-down"></a>
                            <div class="operate-box" >
                                <?php if(check_auth('Weibo/Index/setTop')): if(($weibo["is_top"]) == "0"): ?><li data-weibo-id="<?php echo ($weibo["id"]); ?>" title="<?php echo L('_SET_TOP_');?>" data-role="weibo_set_top">
                                            置顶
                                        </li>
                                        <?php else: ?>
                                        <li data-weibo-id="<?php echo ($weibo["id"]); ?>" title="<?php echo L('_CANCEL_TOP_');?>" data-role="weibo_set_top">
                                            取消置顶
                                        </li><?php endif; endif; ?>
                                <?php if($weibo['can_set_top_crowd_weibo']): if(($weibo["is_crowd_top"]) == "0"): ?><li data-weibo-id="<?php echo ($weibo["id"]); ?>" title="圈内置顶" data-role="set_top_crowd_weibo">
                                            圈内置顶
                                        </li>
                                        <?php else: ?>
                                        <li data-weibo-id="<?php echo ($weibo["id"]); ?>" title="取消圈内置顶" data-role="set_top_crowd_weibo">
                                            取消置顶
                                        </li><?php endif; endif; ?>
                                <?php if($weibo['can_delete']): ?><li data-weibo-id="<?php echo ($weibo["id"]); ?>" title="<?php echo L('_DELETE_');?>" data-role="del_weibo">
                                        删除
                                    </li><?php endif; ?>
                                <?php if($can_hide): ?><li data-weibo-id="<?php echo ($weibo["id"]); ?>" title="<?php echo L('_HIDE_TOP_');?>" data-role="hide_top_weibo">
                                        隐藏
                                    </li><?php endif; ?>
                                <li><?php echo hook('report',array('type'=>$MODULE_ALIAS.'/'.$MODULE_ALIAS,'url'=>"Weibo/Index/weiboDetail?id=$weibo[id]",'data'=>array('weibo-id'=>$weibo['id'])));?></li>
                            </div>
                        </div>
                    </div>
                    <div class="op-tb op-bottom">
                        <a data-hover="查看详情" class="wb-time" href="<?php echo U('Weibo/Index/weiboDetail',array('id'=>$weibo['id']));?>">
                            [time:<?php echo ($weibo["create_time"]); ?>]
                            <?php if(!empty($weibo["pos"])): ?>&nbsp;<i class="os-icon-pointer"></i> <?php echo ($weibo["pos"]); endif; ?>
                        </a>
                    </div>
                </div>
            </div>
            <div class="content-info row">
                <?php echo ($weibo["fetchContent"]); ?>
                <div class="form-where">
                    <div class="where w-left">
                        <?php if(empty($weibo["crowd"])): ?><!--<span><?php echo L('_FROM_');?> <b> 全站动态</b></span>-->
                            <?php else: ?>
                            <span><img src="<?php echo (getthumbimagebyid($weibo['crowd_logo'],16,16)); ?>"/><a href="<?php echo U('Weibo/Index/index',array('crowd'=>$weibo['crowd_id']));?>"><b class="gcard" data-crowd-id="<?php echo ($weibo["crowd_id"]); ?>">  <?php echo ($weibo["crowd"]); ?></b></a></span><?php endif; ?>
                        <span><?php echo hook('giveReward',array('type'=>$MODULE_ALIAS.'/'.$MODULE_ALIAS,'url'=>"Weibo/Index/weiboDetail?id=$weibo[id]",'data'=>array('user-id'=>$weibo['user']['uid'])));?></span>
                    </div>
                    <div class="where w-right  bottom-operate" data-weibo-id="<?php echo ($weibo["id"]); ?>">
                        <?php $weiboCommentTotalCount = $weibo['comment_count']; ?>
                        <div class="col-xs-3 operate-color">
    <?php echo Hook('support',array('table'=>'weibo','row'=>$weibo['id'],'app'=>'Weibo','uid'=>$weibo['uid'],'jump'=>'weibo/index/weibodetail'));?>
</div>
<div class=" col-xs-3 operate-color" data-role="weibo_comment_btn"  data-weibo-id="<?php echo ($weibo["id"]); ?>">
   <i class="os-icon-bubbles"></i> <?php echo ($weiboCommentTotalCount); ?>
</div>
<div class="col-xs-3 operate-color">
    <?php $sourceId =$weibo['data']['sourceId']?$weibo['data']['sourceId']:$weibo['id']; ?>
    <a title="<?php echo L('_REPOST_');?>"  data-role="send_repost"  href="<?php echo U('Weibo/Index/sendrepost',array('sourceId'=>$sourceId,'weiboId'=>$weibo['id']));?>"><i class="os-icon-share-alt"></i> <?php echo ($weibo["repost_count"]); ?></a>
</div>
<div class="share_button col-xs-3  operate-color" style="padding: 0px;position: relative;">
    <span class="cpointer weibo_share_btn_<?php echo ($weibo["id"]); ?>" data-weibo-id="<?php echo ($weibo["id"]); ?>">
        <a data-role="weibo_share_btn" class="share-btn" title="分享"><i class="os-icon-share"></i><!--<span class="share_count" title="累计分享0次" style="margin-left: 5px;">0</span>--></a>
    </span>
    <div class="share_block" data-url="<?php echo U('Weibo/Index/weibodetail',array('id'=>$weibo['id']),true,true);?>" data-text="<?php echo (text($weibo['content'])); ?>" data-dec="分享微博" style="display: none;">
        <div class="bdsharebuttonbox" data-tag="share_feedlist">
            <a class="bds_qzone" data-cmd="qzone" title="分享到QQ空间" data-id="<?php echo ($weibo["id"]); ?>">QQ空间</a>
            <a class="bds_tsina" data-cmd="tsina" title="分享到新浪微博" data-id="<?php echo ($weibo["id"]); ?>">新浪微博</a>
            <a class="bds_tqq" data-cmd="tqq" title="分享到腾讯微博" data-id="<?php echo ($weibo["id"]); ?>">腾讯微博</a>
            <a class="bds_weixin" data-cmd="weixin" title="分享到微信" data-id="<?php echo ($weibo["id"]); ?>">微信</a>
            <a class="bds_sqq" data-cmd="sqq" title="分享到QQ好友" data-id="<?php echo ($weibo["id"]); ?>">QQ好友</a>
            <!--<a class="bds_count" data-cmd="count" style="display: none;"></a>-->
        </div>
        <div style="position: relative;">
            <div class="tip"></div>
            <div class="tip-xs"></div>
        </div>
    </div>
    <script src="/yoyo/Application/Weibo/Static/js/bdshare.js"></script>
</div>
                    </div>
                </div>
            </div>
        </div>

        </div>
    <div class="weibo-comment-list row" <?php if(modC('SHOW_COMMENT',1)): ?>style="display: block;margin:0;" <?php else: ?> style="display: none;"<?php endif; ?> data-weibo-id="<?php echo ($weibo["id"]); ?>">
    <?php if(modC('SHOW_COMMENT',1)): ?><div class=" weibo-comment-block">
            <div class="weibo-comment-container">
                <?php echo W('Weibo/Comment/someComment',array('weibo_id'=>$weibo['id'],'un_prase_comment'=>$un_prase_comment));?>
            </div>
        </div><?php endif; ?>
    </div>
    </div>
</if>
<style>
    .suofang {MARGIN: auto;WIDTH: 200px;}
    .suofang img{MAX-WIDTH: 100%!important;HEIGHT: auto!important;width:expression(this.width > 300 ? "300px" :this.width)!important;}
</style>