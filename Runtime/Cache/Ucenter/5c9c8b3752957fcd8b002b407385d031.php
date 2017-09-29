<?php if (!defined('THINK_PATH')) exit();?><style>
    .modal-dialog {
        width: 430px;
    }

    .select_box_this {
        margin: 10px 0 20px;
        width: 100%;
        text-align: right;
        font-size: 16px;
    }

    .select_box_this .form-group {
        margin-top: 5px;;
    }

    .select_box_this .title {
        width: 100%;
        text-align: center;
        margin-top: -17px;
        margin-bottom: 10px;
        color: #A8A7A7;
    }
</style>

<div>
    <form id="create_code" action="/yoyo/index.php?s=/ucenter/invite/createcode.html&id=1" method="post" class="ajax-form">
        <?php if(!empty($invite_type['can_num'])): ?><div class="select_box_this">
                <div class="title"><span style="font-size: 14px;"><?php echo L('_INV_AVAILABLE_'); echo ($invite_type['can_num']); ?>~</span></div>
                <input type="hidden" name="invite_type" value="<?php echo ($invite_type["id"]); ?>">

                <div data-role="can_num" data-value="<?php echo ($invite_type['can_num']); ?>"></div>
                <div class="form-group">
                    <label class="col-xs-4 control-label" style="text-align: right;">
                        <?php echo L('_INV_GE_COUNT_'); echo L('_COLON_');?>
                    </label>

                    <div class="col-xs-8">
                        <input type="text" name="code_num" class="form-control" value="1">

                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="form-group">
                    <label class="col-xs-4 control-label" style="text-align: right;">
                        <?php echo L('_INV_REG_COUNT_'); echo L('_COLON_');?>
                    </label>

                    <div class="col-xs-8">
                        <input type="text" name="can_num" class="form-control" value="1">

                        <div class="clearfix"></div>
                    </div>
                    <div class="col-xs-8" style="color: #b3b3b3;margin-left: 41px;font-size: 14px;"><?php echo L('_INV_PER_CODE_REG_NUMBER_');?></div>

                </div>
                <div class="clearfix"></div>
            </div>
            <div style="width: 100%;text-align: center;">
                <a class="btn btn-primary" data-role="submit"><?php echo L('_INV_GE_');?></a>
                <a onclick="$('.close').click();" class="btn btn-default"><?php echo L('_CANCEL_');?></a>
            </div>
            <?php else: ?>
            <div style="width: 100%;text-align: center;padding: 0 0 10px;"><span style="font-size: 14px;"><?php echo L('_INV_QUOTA_1_');?><a href="<?php echo U('');?>"></a><?php echo L('_INV_QUOTA_2_'); echo L('_WAVE_');?></span></div>
            <div style="width: 100%;text-align: center;">
                <a class="btn btn-primary" disabled="disabled" data-role="submit"><?php echo L('_INV_GE_');?></a>
                <a onclick="$('.close').click();" class="btn btn-default"><?php echo L('_CANCEL_');?></a>
            </div><?php endif; ?>


    </form>
</div>
<script>
    $(function () {
        $('[data-role="submit"]').click(function () {
            query = $('#create_code').serialize();
            var url = $('#create_code').attr('action');
            var num = $('[data-role="can_num"]').attr('data-value');
            var num_buy = $('[name="code_num"]').val();
            var can_num = $('[name="can_num"]').val();
            if (num_buy * can_num > num) {
                toast.error("<?php echo L('_INV_QUOTA_LIMIT_'); echo L('_EXCLAMATION_');?>");
            }
            $.post(url, query, function (msg) {
                if (msg.status) {
                    toast.success("<?php echo L('_SUCCESS_GE_'); echo L('_EXCLAMATION_');?>");
                    setTimeout(function () {
                        window.location.href = msg.url;
                    }, 1500);
                } else {
                    handleAjax(msg);
                }
            }, 'json');
        });
    });
</script>