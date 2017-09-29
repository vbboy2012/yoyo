<?php if (!defined('THINK_PATH')) exit(); if(($type) == "personal"): ?><div class="form-group">
        <label for="expand_<?php echo ($field_id); ?>" class="col-xs-2 control-label" style="text-align: right;">
            <?php if(($required) == "1"): ?><span style="color: #FF0047;vertical-align: middle;">*&nbsp;&nbsp;</span><?php endif; echo ($field_name); ?>
        </label>

        <div class="col-xs-6">
            <ul class="expandinfo-ul" id="expand_<?php echo ($field_id); ?>" style="-webkit-padding-start: 0px;">
                <?php if(is_array($items)): $i = 0; $__LIST__ = $items;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vl): $mod = ($i % 2 );++$i;?><li class="expandinfo-li">
                        <label><input class="radio-inline" style="margin: -2px 3px 0 0" type="radio" name="expand_<?php echo ($field_id); ?>" value="<?php echo ($vl["value"]); ?>" <?php echo ($vl["checked"]); ?>><?php echo (htmlspecialchars($vl["value"])); ?></label>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul>
            <div class="clearfix"></div>
            <?php if(($input_tips) != ""): ?><span class="help-block"><?php echo ($input_tips); ?></span><?php endif; ?>
        </div>
    </div>
    <?php else: ?>
    <dt class="expandinfo-dt">
        <?php echo ($field_name); ?>：
    </dt>
    <dd class="expandinfo-dd">
        <?php echo (htmlspecialchars($checked)); ?>
    </dd><?php endif; ?>
<div class="clearfix"></div>