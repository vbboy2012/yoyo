<?php if (!defined('THINK_PATH')) exit();?><!--会话列表start-->
<?php if(is_array($message_session_list)): $i = 0; $__LIST__ = $message_session_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$one_message_session): $mod = ($i % 2 );++$i;?><li class="session3">
        <a data-role="open-message-list" data-type="<?php echo ($one_message_session["type"]); ?>">
            <div class="message-t">
                <div class="message-wrap">
                    <div class="col-xs-3 pdl0 img-wrap">
                        <img src="<?php echo ($one_message_session["detail"]["logo"]); ?>" alt="" title="<?php echo ($one_message_session["detail"]["title"]); ?>">
                        <?php if(($one_message_session["count"]) > "0"): ?><span class="badge unread-tip"></span><?php endif; ?>
                    </div>
                    <div class="col-xs-9 info pdr0">
                        <div class="up-box">
                            <span class="title"><?php echo ($one_message_session["detail"]["title"]); ?></span>
                            <span class="time pull-right"><?php echo (time_format($one_message_session["last_message"]["create_time"])); ?></span>
                        </div>
                        <div class="down-box text-more" style="width: 100%">
                            <?php if(($one_message_session["count"]) > "0"): ?><span class="unread-num">[<?php echo ($one_message_session["count"]); ?>条]</span><?php endif; ?>
                            <?php echo (text((isset($one_message_session["last_message"]["tip"]) && ($one_message_session["last_message"]["tip"] !== ""))?($one_message_session["last_message"]["tip"]):'还没有消息哦~~')); ?>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </li><?php endforeach; endif; else: echo "" ;endif; ?>
<!--会话列表end-->
<script>
    $(function () {
        message_session.init_message_session();
    });
</script>