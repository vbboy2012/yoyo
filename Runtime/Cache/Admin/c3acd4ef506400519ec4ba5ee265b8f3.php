<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo L("_LOGIN_BACKGROUND_");?></title>
    <link rel="stylesheet" type="text/css" href="/yoyo/Application/Admin/Static/css/login.css" media="all">
    <link rel="stylesheet" type="text/css" href="/yoyo/Public/zui/css/zui.css" media="all">
</head>
<body>
<!---->
<div class="flex">
    <div class="login-wrap">
        <div id="particles-js"></div>
        <!-- 主体 -->
        <div class="lgMain">
            <form action="<?php echo U('login');?>" method="post" class="login-form">
                <h1 class="welcome"><?php echo L("_LANDING_BACKGROUND_");?></h1>
                <div id="itemBox" class="item-box">
                    <input type="text" name="username" class="input" placeholder='用户名/邮箱/手机'>
                    <input type="password" name="password"  class="input" placeholder=<?php echo L("_PASSWORD_WITH_DOUBLE_");?>>

                    <?php if(APP_DEBUG == false): ?><input type="text" name="verify"  class="input" placeholder=<?php echo L("_VERIFICATION_CODE_WITH_DOUBLE_");?>  autocomplete="off">
                        <div class="imgWrap">
                            <div class="vfWrap">
                                <img class="verifyimg reloadverify" alt=<?php echo L("_CLICK_SWITCH_WITH_DOUBLE_");?> src="<?php echo U('Public/verify');?>">
                            </div>
                            <div class="btnWrap">
                                <button  class="btn btn-default reloadverify" type="button"><i class="icon-refresh"></i></button>
                            </div>
                        </div><?php endif; ?>
                </div>
                <div class="login_btn_panel">
                    <button class="login-btn" type="submit">
                        <span class="in"><i class="icon-loading"></i><?php echo L("_RECORD_WITH_SPACE_");?> 中 ..</span>
                        <span class="on"><?php echo L("_RECORD_WITH_SPACE_");?></span>
                    </button>
                    <div class="check-tips"></div>
                </div>
            </form>
        </div>
    </div>
</div>

<!--[if lt IE 9]>
<script type="text/javascript" src="/yoyo/Public/static/jquery-1.10.2.min.js"></script>
<![endif]-->
<!--[if gte IE 9]><!-->
<script type="text/javascript" src="/yoyo/Public/js/jquery-2.0.3.min.js"></script>
<!--<![endif]-->
<script src="/yoyo/Public/js/particles.min.js"></script>
<script src="/yoyo/Public/js/canvas.js"></script>
<script type="text/javascript">
    css = " 'position: fixed; " +
        "bottom: " +
        "100px; " +
        "left: 50%; " +
        "width: 300px; " +
        "height: 40px; " +
        "margin-left: -150px; " +
        "border-radius: 20px; " +
        "background-color: #FF3030; " +
        "color: #fff; " +
        "font-size: 20px; " +
        "line-height: 40px; " +
        "text-align: center' ";


    function show($message) {
        html = "<div class='toast' style= " + css + ">" + $message + "</div>";
        $("body").append(html);
        setTimeout(function () {
            $("div.toast").remove();
        },3000);
    }

    //表单提交
    $(document)
            .ajaxStart(function(){
                $("button:submit").addClass("log-in").attr("disabled", true);
            })
            .ajaxStop(function(){
                $("button:submit").removeClass("log-in").attr("disabled", false);
            });

    $("form").submit(function(){
        var self = $(this);
        $.post(self.attr("action"), self.serialize(), success, "json");
        return false;

        function success(data){
            if(data.status){
                window.location.href = data.url;
            } else {
                $(document).ajaxError();
                show(data.info);
                //刷新验证码
                $('[name=verify]').val('');
                $(".reloadverify").click();
            }
        }
    });

    $(function(){
        //刷新验证码
        var verifyimg = $(".verifyimg").attr("src");
        $(".reloadverify").click(function(){
            if( verifyimg.indexOf('?')>0){
                $(".verifyimg").attr("src", verifyimg+'&random='+Math.random());
            }else{
                $(".verifyimg").attr("src", verifyimg.replace(/\?.*$/,'')+'?'+Math.random());
            }
        });

        //placeholder兼容性
        //如果支持
        function isPlaceholer(){
            var input = document.createElement('input');
            return "placeholder" in input;
        }
        //如果不支持
        if(!isPlaceholer()){
            $(".placeholder_copy").css({
                display:'block'
            })
            $("#itemBox input").keydown(function(){
                $(this).parents(".item").next(".placeholder_copy").css({
                    display:'none'
                })
            })
            $("#itemBox input").blur(function(){
                if($(this).val()==""){
                    $(this).parents(".item").next(".placeholder_copy").css({
                        display:'block'
                    })
                }
            })
        }
    });


    var count_particles, stats, update;
    stats = new Stats;
    stats.setMode(0);
    stats.domElement.style.position = 'absolute';
    stats.domElement.style.left = '0px';
    stats.domElement.style.top = '0px';
    document.body.appendChild(stats.domElement);
    count_particles = document.querySelector('.js-count-particles');
    update = function() {
        stats.begin();
        stats.end();
        if (window.pJSDom[0].pJS.particles && window.pJSDom[0].pJS.particles.array) {
            count_particles.innerText = window.pJSDom[0].pJS.particles.array.length;
        }
        requestAnimationFrame(update);
    };
    requestAnimationFrame(update);

</script>
</body>
</html>