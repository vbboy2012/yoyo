<?php if (!defined('THINK_PATH')) exit();?><div id="message_block_<?php echo ($message_session_info["name"]); ?>" class="list-block">
    <div class="center">
        <?php if(!empty($message_list)): ?><ul class="load-more-block">
                <?php if(is_array($message_list)): $i = 0; $__LIST__ = $message_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$one_message): $mod = ($i % 2 );++$i; if(($one_message["line"]) == "1"): ?><div class="line">
                            ——————以上为新消息——————
                        </div>
                        <hr>
                        <?php else: ?>
                        <?php echo ($one_message["html"]); endif; endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <div class="load-more">
                <a data-role="load-more" class="do-button" data-session="<?php echo ($message_session_info["name"]); ?>" data-already="<?php echo ($now_count); ?>">加载更多...</a>
            </div>
            <?php else: ?>
            <div class="no-message">
                <p>还没有消息~</p>
            </div><?php endif; ?>
    </div>

</div>
<script>
    $(function () {
        message_session.init_message_list();
    })
</script>