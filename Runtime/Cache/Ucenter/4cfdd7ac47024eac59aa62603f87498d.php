<?php if (!defined('THINK_PATH')) exit();?>
<link rel="stylesheet" type="text/css" href="<?php echo getRootUrl();?>Addons/SyncLogin/_static/css/sync.css">
<?php if(!empty($config['type'])){ ?>

<div id="center_weibo">
    <div class="row">
        <div class="col-xs-12">
            <h4><i class="icon-lock"></i>&nbsp;绑定微博
            </h4>
            <hr class="center_line"/>
            </h4>
        </div>
    </div>
    <div id="weibo_panel" class="center_panel" >
        <div class="col-xs-12" >


            <div class="uc_config_info clearfix col-xs-8">
                <div class="other_login_link row" >
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="row">
                            <div class="col-xs-6">  <a href="javascript:" class="other_login other_login_<?php echo ($vo['name']); ?>"></a></div>
                            <div class="col-xs-6 text-right">
                                <?php if($vo['is_bind']): if($vo['info']){ ?>
                                    <a href="javascript:" class="btn btn-info" data-role="unbind" data-type="<?php echo ($vo["name"]); ?>"  data-url="<?php echo addons_url('SyncLogin://Ucenter/unbind');?>">
                                        已绑定 &nbsp; [<?php echo ($vo["info"]["nick"]); ?>]，取消绑定
                                    </a>
                                    <?php }else{ ?>
                                    <a href="javascript:" class="btn btn-info" data-role="unbind" data-type="<?php echo ($vo["name"]); ?>"  data-url="<?php echo addons_url('SyncLogin://Ucenter/unbind');?>">
                                        请点击此按钮解绑后重新绑定
                                    </a>
                                <?php } ?>


                                    <?php else: ?>

                                    <a href="<?php echo addons_url('SyncLogin://Base/login',array('type'=>$vo['name']));?>" class="btn btn-default ">
                                      未绑定，点击绑定
                                    </a><?php endif; ?>
                            </div>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            </div>
        </div>


    </div>
</div>

<script>
    $(function () {
        $('[data-role="unbind"]').unbind('click');
        $('[data-role="unbind"]').click(function(){
            if(confirm('确定要取消绑定么？')){
                var obj = $(this);
                var type =obj.attr('data-type');
                var url =  obj.attr('data-url');
                $.post(url,{type:type},function(res){
                    handleAjax(res);
                })
            }
        })
    })
</script>
<?php } ?>