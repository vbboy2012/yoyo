<?php if (!defined('THINK_PATH')) exit(); if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$weibo): $mod = ($i % 2 );++$i; echo W('Weibo/WeiboDetail/detail',array('weibo_id'=>$weibo));?>
    <?php if($weibo == $weibo_num-$rand){ ?>
    <?php if(!empty($suggested_follows)): ?><div class="all-wrap main_visual" style="max-width: 680px;margin-bottom: 10px;height: 160px">
            <h2>推荐用户</h2>
            <?php if($suggested_count > 3): ?><div class="pager" style="float: left;margin-top: 20px">
                    <li><a href="javascript:;" id="btn_prev" style="border-radius: 100%!important;padding: 0 5px;"><</a></li>
                </div><?php endif; ?>

            <div class="col-xs-10 main_image" style="margin-left: 40px">
                <?php if(is_array($suggested_follows)): $i = 0; $__LIST__ = $suggested_follows;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$user_list): $mod = ($i % 2 );++$i; if(is_array($user_list)): $i = 0; $__LIST__ = $user_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(count($vo)): ?><div class="col-xs-4" id="suggested_<?php echo ($vo["uid"]); ?>">
                                <div class="col-xs-6">
                                    <a ucard="<?php echo ($vo["uid"]); ?>" href="<?php echo ($vo["space_url"]); ?>">
                                        <img src="<?php echo ($vo["avatar128"]); ?>" style="width: 64px;border-radius: 100%">
                                    </a>
                                </div>
                                <div>
                                    <span><?php echo ($vo["nickname"]); ?></span><br>
                                    <span>粉丝 <?php echo ($vo["fans"]); ?></span>
                                    <div class="suggested-follows-btn" data-id="<?php echo ($vo["uid"]); ?>" data-role="suggested_follows">
                                        <?php echo W('Common/Follow/follow',array('follow_who'=>$vo['uid']));?>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="col-xs-4" style="display: none">
                                <div class="col-xs-6">
                                    <a ucard="<?php echo ($vo["uid"]); ?>" href="<?php echo ($vo["space_url"]); ?>">
                                        <img src="<?php echo ($vo["avatar128"]); ?>" style="width: 64px;border-radius: 100%">
                                    </a>
                                </div>
                                <div>
                                    <span><?php echo ($vo["nickname"]); ?></span><br>
                                    <span>粉丝 <?php echo ($vo["fans"]); ?></span>
                                    <div class="suggested-follows-btn">
                                        <?php echo W('Common/Follow/follow',array('follow_who'=>$vo['uid']));?>
                                    </div>
                                </div>
                            </div><?php endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <?php if($suggested_count > 3): ?><div class="pager" style="margin-top: 20px">
                    <li><a href="javascript:;" id="btn_next" style="border-radius: 100%!important;padding: 0 5px;">></a></li>
                </div><?php endif; ?>


        </div>

        <script type="text/javascript">
            $('[data-role="suggested_follows"]').click(function() {
                var uid = $(this).attr('data-id');
                $.post(U('Weibo/Index/clearSuggestedFollows'), {uid:uid}, function(msg) {
//                if(msg.status) {
//                    setTimeout(function() {
//                        if(uid == msg.suggested_id) {
//                            $('#suggested_'+uid).hide();
//                        }
//                    }, 1000);
//                }
                },'json')
            });
            $(document).ready(function () {
                $(".main_visual").hover(function () {
                    $("#btn_prev,#btn_next").fadeIn()
                }, function () {
                    $("#btn_prev,#btn_next").fadeOut()
                });

                $dragBln = false;

                $(".main_image").touchSlider({
                    flexible: true,
                    speed: 1000,
                    view : 6,
                    btn_prev: $("#btn_prev"),
                    btn_next: $("#btn_next")
                });
                $('.main_visual').fadeIn();

                $(".main_image").bind("mousedown", function () {
                    $dragBln = false;
                });

                $(".main_image").bind("dragstart", function () {
                    $dragBln = true;
                });

                $(".main_image a").click(function () {
                    if ($dragBln) {
                        return false;
                    }
                });

                timer = setInterval(function () {
                    $("#btn_next").click();
                }, 5000);

                $(".main_visual").hover(function () {
                    clearInterval(timer);
                }, function () {
                    timer = setInterval(function () {
                        $("#btn_next").click();
                    }, 5000);
                });

                $(".main_image").bind("touchstart", function () {
                    clearInterval(timer);
                }).bind("touchend", function () {
                    timer = setInterval(function () {
                        $("#btn_next").click();
                    }, 5000);
                });

            });
        </script><?php endif; ?>

    <?php } endforeach; endif; else: echo "" ;endif; ?>
<?php if(empty($lastId) == false): ?><script>
        weibo.lastId = '<?php echo ($lastId); ?>';
    </script><?php endif; ?>