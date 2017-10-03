<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
<title><?php echo (strip_tags($seo["title"])); ?>|<?php echo modC('WEB_SITE_NAME','微社区','Config');?></title>
<meta name="keywords" content="<?php echo (strip_tags($seo["keywords"])); ?>"/>
<meta name="description" content="<?php echo (strip_tags($seo["description"])); ?>"/>


<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport"
      content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp"/>

<link rel="stylesheet" href="/yoyo/m/Public/light7/css/light7.min.css">
<link rel="stylesheet" href="/yoyo/m/Public/light7/css/light7-swipeout.css">
<link rel="stylesheet" href="/yoyo/m/Public/light7/css/light7-swiper.min.css">

<link rel="stylesheet" href="/yoyo/m/Public/css/core.css">
<link rel="stylesheet" href="/yoyo/m/Public/css/animate.min.css">
<link rel="stylesheet" href="//at.alicdn.com/t/font_v9h6rhwp9z9cc8fr.css">
<!--用于加载顶部css和js-->
<?php $config = api('Config/lists'); C($config); $count_code=C('_MOB_STATISTICALCODE'); if(empty($count_code)){ $count_code=C('COUNT_CODE'); } ?>

<script type="text/javascript">
    //全局内容的定义
    var _ROOT_ = "/yoyo/m";
    var MID = "<?php echo is_login();?>";
    var MODULE_NAME="<?php echo MODULE_NAME; ?>";
    var ACTION_NAME="<?php echo ACTION_NAME; ?>";
    var CONTROLLER_NAME ="<?php echo CONTROLLER_NAME; ?>";
    var ThinkPHP = window.Think = {
        "ROOT": "/yoyo/m", //当前网站地址
        "APP": "/yoyo/m/install.php?s=", //当前项目地址
        "PUBLIC": "/yoyo/m/Public", //项目公共目录地址
        "DEEP": "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
        "MODEL": ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
        "VAR": ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"],
        'URL_MODEL': "<?php echo C('URL_MODEL');?>",
        'WEIBO_ID': "<?php echo C('SHARE_WEIBO_ID');?>"
    }

    var weibo_comment_order = "<?php echo modC('COMMENT_ORDER',0,'WEIBO');?>";
</script>
<script>
    //全局内容的定义
    var  play=false;
    var _LOADING_ = "/yoyo/m/Application/Install/Static/images/loading.jpg";
</script>
</head>
<body>

    <style>
        .font{
            font-size: 25px;;
        }
        .bg{
            position: absolute;
            top:0;
            left: 0;
            right: 0;
            bottom: 0;
            background-size: contain;
            padding:400px 100px 0 100px;
        }
    </style>

    <?php
 $img_id = modC('JUMP_BACKGROUND','','config'); if($img_id){ $background =get_cover($img_id,'path'); }else{ $background = '/yoyo/m/Public/images/error.jpg'; } ?>

    <div class="bg" style="background-image: url(<?php echo($background); ?>)">

<div class="text-center " style="margin: 0 auto; ">

<?php if(isset($success_message)) {?>

<div class="alert alert-success with-icon">
        <i class="icon-ok-sign"></i>
        <div class="content">

<p class="font"><?php echo($success_message); ?></p>


</div>

</div>

<?php }else{?>

<div class="alert alert-danger with-icon">
    <i class="icon-remove-sign"></i>
    <div class="content">

        <p class="font"> <?php echo($error_message); ?></p>


</div>
</div>

<?php }?>


    <p class="jump">
        页面自动 <a id="href" style="color: green" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>。

        或 <a href="http://<?php echo ($_SERVER['HTTP_HOST']); ?>/yoyo/m" style="color: green">返回首页</a>
    </p>


    </div>

    </div>
<script type="text/javascript">
    (function(){
        var wait = document.getElementById('wait'),href = document.getElementById('href').href;
        var interval = setInterval(function(){
            var time = --wait.innerHTML;
            if(time <= 0) {
                location.href = href;
                clearInterval(interval);
            };
        }, 1000);
    })();
</script>



<script type='text/javascript' src='/yoyo/m/Public/js/jquery-3.1.1.min.js' charset='utf-8'></script>

<script>
	$.config = {router: false}
</script>

<script type='text/javascript' src='/yoyo/m/Public/light7/js/light7.js' charset='utf-8'></script>
<script type='text/javascript' src='/yoyo/m/Public/light7/js/light7-city-picker.min.js' charset='utf-8'></script>
<script type='text/javascript' src='/yoyo/m/Public/light7/js/light7-swipeout.js' charset='utf-8'></script>
<script type='text/javascript' src='/yoyo/m/Public/light7/js/light7-swiper.min.js' charset='utf-8'></script>
<script type='text/javascript' src='/yoyo/m/Public/light7/js/i18n/cn.js' charset='utf-8'></script>
<script type='text/javascript' src='/yoyo/m/Public/light7/js/i18n/cn.js' charset='utf-8'></script>
<script src="/yoyo/m/Application/Weibo/Static/js/autoplay.min.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js" type="text/javascript"></script>
<script src="/yoyo/m/Public/com/com.functions.js"></script>

<script>
	$(function(){
		// plan A
		var $bottom = $('.tab-item.external');
		var value = localStorage.getItem('bottom');
		if (value == '' || value == null) {
			$('.bar.bar-tab.bottomBar').children('.tab-item.external:first').addClass('active');
		} else {
			$bottom.each(function(){
				if (value == $(this).children('.tab-label').html()) {
					$(this).addClass('active');
				}
			});
		}
		$bottom.click(function(){
			var flag = $(this).children('.tab-label').html();
			localStorage.setItem('bottom',flag);
		});

		//plan B
		var $bottom2 = $('.tab-item.external .tab-label');
		var hash = window.location.hash;
		$bottom2.each(function(){
			if (hash == '#'+$(this).html()) {
				$(this).closest('.tab-item.external').addClass('active');
			}
		});
		$("[data-role='oc-channel']").click(function () {
			$(this).siblings().find('.aboveBox').hide();
			var myChild = $(this).find('.aboveBox');
			myChild.toggle();
		});
	});
	$.init();
</script>
</body>
</html>