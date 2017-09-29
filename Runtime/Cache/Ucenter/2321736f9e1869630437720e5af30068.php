<?php if (!defined('THINK_PATH')) exit(); if(($type) == "personal"): ?><div class="form-group">
        <label for="expand_<?php echo ($field_id); ?>" class="col-xs-2 control-label" style="text-align: right;">
            <?php if(($required) == "1"): ?><span style="color: #FF0047;vertical-align: middle;">*&nbsp;&nbsp;</span><?php endif; echo ($field_name); ?>
        </label>

        <div class="col-xs-6">
            <select class="form-control" id="expand_<?php echo ($field_id); ?>" name="expand_<?php echo ($field_id); ?>">
                <?php if(is_array($items)): $i = 0; $__LIST__ = $items;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vl): $mod = ($i % 2 );++$i;?><!--增加编辑扩展资料关联字段数据显示-->
                    <option value="<?php if(($vl["join"]) == "1"): echo ($vl["id"]); else: echo ($vl["value"]); endif; ?>" <?php echo ($vl["selected"]); ?>><?php echo (htmlspecialchars($vl["value"])); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <div class="clearfix"></div>
            <?php if(($input_tips) != ""): ?><span class="input-tips"><?php echo ($input_tips); ?></span><?php endif; ?>
        </div>
    </div>
    <?php else: ?>
    <dt class="expandinfo-dt">
        <?php echo ($field_name); ?>：
    </dt>
    <dd class="expandinfo-dd">
        <?php echo (htmlspecialchars($selected)); ?>
    </dd><?php endif; ?>
<div class="clearfix"></div>