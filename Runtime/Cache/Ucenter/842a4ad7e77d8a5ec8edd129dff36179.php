<?php if (!defined('THINK_PATH')) exit(); if($is_following && !$is_self){ ?>
<button type="button" class="<?php echo ((isset($after) && ($after !== ""))?($after):'btn btn-default'); ?>" data-after="<?php echo ((isset($before) && ($before !== ""))?($before):'btn btn-default'); ?>"  data-before="<?php echo ((isset($after) && ($after !== ""))?($after):'btn btn-primary'); ?>" data-role="unfollow" data-follow-who="<?php echo ($follow_who); ?>" data-follow-type="1" style="width: 65px;<?php echo ($hide); ?>">
    <?php echo L('_CANCEL_'); echo L('_FOLLOWERS_');?>
</button>
<?php }elseif(!$is_following && !$is_self){ ?>
<button type="button" class="<?php echo ((isset($before) && ($before !== ""))?($before):'btn btn-primary'); ?>" id="follow" data-after="<?php echo ((isset($before) && ($before !== ""))?($before):'btn btn-default'); ?>"  data-before="<?php echo ((isset($after) && ($after !== ""))?($after):'btn btn-primary'); ?>"  data-role="follow" data-follow-who="<?php echo ($follow_who); ?>" data-follow-type="1" style="width: 65px;<?php echo ($hide); ?>">
    <?php echo L('_FOLLOWERS_'); echo L('_USER_');?>
</button>
<?php } ?>