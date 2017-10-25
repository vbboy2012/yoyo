<?php if (!defined('THINK_PATH')) exit(); if($is_trusting && !$is_self){ ?>
<button type="button" class="<?php echo ((isset($after) && ($after !== ""))?($after):'btn btn-default'); ?>" data-after="<?php echo ((isset($before) && ($before !== ""))?($before):'btn btn-default'); ?>"  data-before="<?php echo ((isset($after) && ($after !== ""))?($after):'btn btn-primary'); ?>" data-role="unfollow" data-follow-who="<?php echo ($follow_who); ?>" data-follow-type="2" style="width: 65px">
    <?php echo L('_CANCEL_'); echo L('_TRUST_');?>
</button>
<?php }elseif(!$is_trusting && !$is_self){ ?>
<button type="button" class="<?php echo ((isset($before) && ($before !== ""))?($before):'btn btn-primary'); ?>" data-after="<?php echo ((isset($before) && ($before !== ""))?($before):'btn btn-default'); ?>"  data-before="<?php echo ((isset($after) && ($after !== ""))?($after):'btn btn-primary'); ?>"  data-role="follow" data-follow-who="<?php echo ($follow_who); ?>" data-follow-type="2" style="width: 65px">
    <?php echo L('_TRUST_'); echo L('_USER_');?>
</button>
<?php } ?>