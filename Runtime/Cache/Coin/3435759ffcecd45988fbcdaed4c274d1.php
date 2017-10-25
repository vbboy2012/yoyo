<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<?php echo hook('syncMeta');?>

<?php $oneplus_seo_meta = get_seo_meta($vars,$seo); ?>
<?php if($oneplus_seo_meta['title']): ?><title><?php echo ($oneplus_seo_meta['title']); ?></title>
    <?php else: ?>
    <title><?php echo modC('WEB_SITE_NAME',L('_OPEN_SNS_'),'Config');?></title><?php endif; ?>
<?php if($oneplus_seo_meta['keywords']): ?><meta name="keywords" content="<?php echo ($oneplus_seo_meta['keywords']); ?>"/><?php endif; ?>
<?php if($oneplus_seo_meta['description']): ?><meta name="description" content="<?php echo ($oneplus_seo_meta['description']); ?>"/><?php endif; ?>

<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" media="screen" />

<!-- zui -->
<link href="/yoyo/Public/zui/css/zui.css" rel="stylesheet">

<link href="/yoyo/Public/zui/css/zui-theme.css" rel="stylesheet">
<link href="/yoyo/Public/static/os-icon/simple-line-icons.min.css" rel="stylesheet">
<link href="/yoyo/Public/static/os-loading/loading.css" rel="stylesheet">
<link href="/yoyo/Public/css/core.css" rel="stylesheet"/>
<link type="text/css" rel="stylesheet" href="/yoyo/Public/js/ext/magnific/magnific-popup.css"/>

<!--<script src="/yoyo/Public/js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="/yoyo/Public/js/com/com.functions.js"></script>

<script type="text/javascript" src="/yoyo/Public/js/core.js"></script>-->
<script src="/yoyo/Public/js.php?f=js/jquery-2.0.3.min.js,js/com/com.functions.js,static/os-loading/loading.js,js/core.js,js/com/com.toast.class.js,js/com/com.ucard.js"></script>




    <link href="/yoyo/Application/Coin/Static/css/event.css" rel="stylesheet" type="text/css"/>

<!--合并前的js-->
<?php $config = api('Config/lists'); C($config); $count_code=C('COUNT_CODE'); ?>
<script type="text/javascript">
    var ThinkPHP = window.Think = {
        "ROOT": "/yoyo", //当前网站地址
        "APP": "/yoyo/index.php?s=", //当前项目地址
        "PUBLIC": "/yoyo/Public", //项目公共目录地址
        "DEEP": "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
        "MODEL": ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
        "VAR": ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"],
        'URL_MODEL': "<?php echo C('URL_MODEL');?>",
        'WEIBO_ID': "<?php echo C('SHARE_WEIBO_ID');?>"
    }
    var cookie_config={
        "prefix":"<?php echo C('COOKIE_PREFIX');?>",// cookie 名称前缀
        "path" :"<?php echo C('COOKIE_PATH');?>", // cookie 保存路径
        "domain":"<?php echo C('COOKIE_DOMAIN');?>" // cookie 有效域名
    }
    var Config={
        'GET_INFORMATION':<?php echo modC('GET_INFORMATION',1,'Config');?>,
        'GET_INFORMATION_INTERNAL':<?php echo modC('GET_INFORMATION_INTERNAL',10,'Config');?>*1000,
        'WEBSOCKET_ADDRESS':"<?php echo modC('WEBSOCKET_ADDRESS',gethostbyname($_SERVER['SERVER_NAME']),'Config');?>",
        'WEBSOCKET_PORT':<?php echo modC('WEBSOCKET_PORT',8000,'Config');?>
    }
    var weibo_comment_order = "<?php echo modC('COMMENT_ORDER',0,'WEIBO');?>";
</script>

<script src="/yoyo/Public/lang.php?module=<?php echo strtolower(MODULE_NAME);?>&lang=<?php echo LANG_SET;?>"></script>

<script src="/yoyo/Public/expression.php"></script>

<!-- Bootstrap库 -->
<!--
<?php $js[]=urlencode('/static/bootstrap/js/bootstrap.min.js'); ?>

&lt;!&ndash; 其他库 &ndash;&gt;
<script src="/yoyo/Public/static/qtip/jquery.qtip.js"></script>
<script type="text/javascript" src="/yoyo/Public/Core/js/ext/slimscroll/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="/yoyo/Public/static/jquery.iframe-transport.js"></script>
-->
<!--CNZZ广告管家，可自行更改-->
<!--<script type='text/javascript' src='http://js.adm.cnzz.net/js/abase.js'></script>-->
<!--CNZZ广告管家，可自行更改end-->
<!-- 自定义js -->
<!--<script src="/yoyo/Public/js.php?get=<?php echo implode(',',$js);?>"></script>-->

<?php D('Pushing')->doRun(); $key = C('DATA_AUTH_KEY'); $timestamp = time(); $signature = md5(is_login().$timestamp.$key); ?>
<script>
    //全局内容的定义
    var _ROOT_ = "/yoyo";
    var MID = "<?php echo is_login();?>";
    var SIGNATURE = "<?php echo ($signature); ?>";
    var TIMESTAMP = "<?php echo ($timestamp); ?>";
    var MODULE_NAME="<?php echo MODULE_NAME; ?>";
    var ACTION_NAME="<?php echo ACTION_NAME; ?>";
    var CONTROLLER_NAME ="<?php echo CONTROLLER_NAME; ?>";
    var initNum = "<?php echo modC('WEIBO_NUM',140,'WEIBO');?>";
    function adjust_navbar(){
        $('#sub_nav').css('top',$('#nav_bar').height());
        $('#main-container').css('padding-top',$('#nav_bar').height()+$('#sub_nav').height()+20)
    }
</script>

<audio id="music" src="" autoplay="autoplay"></audio>
<!-- 页面header钩子，一般用于加载插件CSS文件和代码 -->
<?php echo hook('pageHeader');?>
</head>
<body>
	<!-- 头部 -->
	<script src="/yoyo/Public/js/com/com.talker.class.js"></script>
<?php if((is_login()) ): ?><div id="talker">

    </div><?php endif; ?>

<?php D('Common/Member')->need_login(); ?>
<!--[if lt IE 8]>
<div class="alert alert-danger" style="margin-bottom: 0"><?php echo L('_TIP_BROWSER_DEPRECATED_1_');?> <strong><?php echo L('_TIP_BROWSER_DEPRECATED_2_');?></strong>
    <?php echo L('_TIP_BROWSER_DEPRECATED_3_');?> <a target="_blank"
                                          href="http://browsehappy.com/"><?php echo L('_TIP_BROWSER_DEPRECATED_4_');?></a>
    <?php echo L('_TIP_BROWSER_DEPRECATED_5_');?>
</div>
<![endif]-->
<script src="/yoyo/Public/js/canvas.js"></script>
<style>
    body {
        color: #34495e;
        font-family: "Open Sans", sans-serif;
        padding: 0px !important;
        margin: 0px !important;
        direction: "ltr";
        font-size: 14px; }
    .navbar-brand{
        font-size: 20px;
        color: #43cb83;
    }
    .navbar-brand:hover{
        color: #2a985e;
    }
</style>

<script>

</script>
<div class="container-fluid topp-box clearfloat">
    <div class="col-xs-2 box">
        <div class="">
            <a class="navbar-brand" href="<?php echo U('Home/Index/index');?>"><i class="icon icon-compass icon-2x"></i>YOYOCOINS</a>
        </div>
    </div>
    <div class="col-xs-7 box ">
        <div id="nav_bar" class="nav_bar">
            <div class=" sat-nav">
                <ul class="first-ul">
                    <?php $__NAV__ = D('Channel')->lists(true);$__NAV__ = list_to_tree($__NAV__, "id", "pid", "_"); if(is_array($__NAV__)): $i = 0; $__LIST__ = $__NAV__;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nav): $mod = ($i % 2 );++$i; if(($nav['_']) != ""): ?><li class="dropdown show-hide-ul">
                                <a title="<?php echo ($nav["title"]); ?>" class=" nav_item first-a"
                                   href="<?php echo U($nav['url']);?>">
                                    <i class="os-icon-<?php echo ($nav["icon"]); ?> app-icon"></i>
                                    <?php echo ($nav["title"]); ?>
                                    <i class="icon-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu nav-menu">
                                    <?php if(is_array($nav["_"])): $i = 0; $__LIST__ = $nav["_"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$subnav): $mod = ($i % 2 );++$i; if(($subnav["icon"] == 1) or ($subnav["icon"] == 2) or ($subnav["icon"] == 3) or ($subnav["icon"] == 4) or ($subnav["icon"] == 5) or ($subnav["icon"] == 6) or ($subnav["icon"] == 7) or ($subnav["icon"] == 8) or ($subnav["icon"] == 9) or ($subnav["icon"] == 10) or ($subnav["icon"] == 11) or ($subnav["icon"] == 12) or ($subnav["icon"] == 13) or ($subnav["icon"] == 14)): ?><li role="presentation">
                                                <a class="drop-a" role="menuitem" tabindex="-1" href="<?php echo (get_nav_url($subnav["url"])); ?>" target="<?php if(($subnav["target"]) == "1"): ?>_blank<?php else: ?>_self<?php endif; ?>">
                                                    <p>
                                                        <span><img  src="Application/Admin/Static/images/customedit/<?php echo ($subnav["icon"]); ?>.png"></span>
                                                        <span><?php echo ($subnav["title"]); ?></span>
                                                    </p>
                                                    <p><?php echo ($subnav["band_text"]); ?></p>
                                                </a>
                                            </li>
                                            <?php else: ?>
                                            <li role="presentation">
                                                <a class="drop-a" role="menuitem" tabindex="-1" href="<?php echo (get_nav_url($subnav["url"])); ?>" target="<?php if(($subnav["target"]) == "1"): ?>_blank<?php else: ?>_self<?php endif; ?>">
                                                    <p>
                                                        <i class="os-icon-<?php echo ($subnav["icon"]); ?>"></i>
                                                        <span><?php echo ($subnav["title"]); ?></span>
                                                    </p>
                                                    <p><?php echo ($subnav["band_text"]); ?></p>
                                                </a>
                                            </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                                </ul>
                            </li>
                            <?php else: ?>
                            <li class="<?php if((get_nav_active($nav["url"])) == "1"): ?>active<?php else: endif; ?>">
                                <a class="first-a" title="<?php echo ($nav["title"]); ?>" href="<?php echo (get_nav_url($nav["url"])); ?>" target="<?php if(($nav["target"]) == "1"): ?>_blank<?php else: ?>_self<?php endif; ?>">
                                    <i class="os-icon-<?php echo ($nav["icon"]); ?> app-icon "></i>
                                    <span ><?php echo ($nav["title"]); ?></span>
                                    <span class="label label-badge rank-label" title="<?php echo ($nav["band_text"]); ?>" style="background: <?php echo ($nav["band_color"]); ?> !important;color:white !important;"><?php echo ($nav["band_text"]); ?></span>
                                </a>
                            </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-xs-3 box c-b-right" style="text-align: right">
        <?php if(is_login()): ?><li class="li-hover">
                <a data-role="open-message-box" data-toggle="modal" data-target="#message-box">
                    <div class="message-num" data-role="now-message-num"  style="display: none;"></div>
                    <!--<i class="iconfont icon-lingdang"></i>-->
                    <img src="/yoyo/Public/images/information.png">
                </a>
            </li>
            <li class="dropdown li-hover self-info">
                <?php $uid = is_login(); $reg_time = D('member')->where(array('uid' => $uid))->getField('reg_time'); $reg_date = date('Y-m-d', $reg_time); $self = query_user(array('title', 'avatar128', 'nickname', 'uid', 'space_url', 'score', 'title', 'fans', 'following', 'weibocount', 'rank_link')); $map = getUserConfigMap('user_cover'); $map['role_id'] = 0; $model = D('Ucenter/UserConfig'); $cover = $model->findData($map); $self['cover_id'] = $cover['value']; $self['cover_path'] = getThumbImageById($cover['value'], 273, 80); ?>
                <a role="button" class="dropdown-toggle dropdown-toggle-avatar" data-toggle="dropdown">
                    <span><img src="<?php echo ($self["avatar32"]); ?>" class="avatar-img nav-img"></span>
                    <span class="user-name"><?php echo ($self["nickname"]); ?></span>
                </a>
                <ul class="dropdown-menu user-card" role="menu">
                    <div class="bg-wrap">
                        <?php if($self['cover_id']): ?><img class="cover uc_top_img_bg_weibo" src="<?php echo ($self['cover_path']); ?>">
                            <?php else: ?>
                            <img class="cover uc_top_img_bg_weibo" src="/yoyo/Application/Core/Static/images/bg.jpg"><?php endif; ?>
                        <?php if(is_login() && $self['uid'] == is_login()): ?><div class="change_cover"><a data-type="ajax" data-url="<?php echo U('Ucenter/Public/changeCover');?>"
                                                         data-toggle="modal" data-title="<?php echo L('_UPLOAD_COVER_');?>" style="color: white;"><img
                                    class="img-responsive" src="/yoyo/Application/Core/Static/images/fractional.png" style="width: 25px;"></a>
                            </div><?php endif; ?>
                    </div>

                    <div class="my-bg-info">
                        <div class="bg-avatar">
                            <a class="link-change-avatar" href="<?php echo U('Ucenter/Config/avatar');?>" title="更换头像">
                                <img src="<?php echo ($self["avatar128"]); ?>" class="avatar-img "/>
                            </a>
                        </div>
                        <span class="nickname">
                        <a ucard="<?php echo ($self["uid"]); ?>" href="<?php echo ($self["space_url"]); ?>" class="user_name"><?php echo (htmlspecialchars($self["nickname"])); ?></a>
                    </span>

                        <div class="bg-numb row ">
                            <a href="<?php echo U('Ucenter/index/applist',array('uid'=>$self['uid'],'type'=>'Weibo'));?>">
                                <div class="col-xs-4 num">
                                    <span>微博</span>
                                    <span><?php echo ($self["weibocount"]); ?></span>
                                </div>
                            </a>
                            <a href="<?php echo U('Ucenter/index/fans',array('uid'=>$self['uid']));?>" title="<?php echo L('_FANS_COUNT_');?>">
                                <div class="col-xs-4 num">
                                    <span><?php echo L('_FANS_');?></span>
                                    <span><?php echo ($self["fans"]); ?></span>
                                </div>
                            </a>
                            <a href="<?php echo U('Ucenter/index/following',array('uid'=>$self['uid']));?>" title="<?php echo L('_FOLLOW_COUNT_');?>">
                                <div class="col-xs-4 num" style="border: none">
                                    <span><?php echo L('_FOLLOW_');?></span>
                                    <span><?php echo ($self["following"]); ?></span>
                                </div>
                            </a>
                        </div>

                    </div>

                    <div class="row grade-box">
                        <?php $title=D('Ucenter/Title')->getCurrentTitleInfo(is_login()); ?>
                        <script>
                            $(function () {
                                $('#upgrade').tooltip({
                                            html: true,
                                            title: '<?php echo L("_CURRENT_LEVEL_");?>：<?php echo ($self["title"]); ?> <br/><?php echo L("_NEXT_LEVEL_");?>：<?php echo ($title["next"]); ?><br/><?php echo L("_NOW_");?>：<?php echo ($self["score"]); ?><br/><?php echo L("_NEED_");?>：<?php echo ($title["upgrade_require"]); ?><br/><?php echo L("_LAST_");?>： <?php echo ($title["left"]); ?><br/><?php echo L("_PROGRESS_");?>：<?php echo ($title["percent"]); ?>% <br/> '
                                        }
                                );
                            })
                        </script>
                        <div class="col-xs-2 l-box"><span>等级</span></div>
                        <div class="col-xs-10 r-box">
                            <div id="upgrade" class="upgrade" data-toggle="tooltip" data-placement="bottom" title="">
                                <div class="grade-bottom" ></div>
                                <div class="grade-top" style="width:<?php echo ($title["percent"]); ?>%;"></div>
                            </div>
                        </div>
                        <p class="pull-right"><span><?php echo ($self["score"]); ?></span>/<span style="color: #ccc"><?php echo ($title["upgrade_require"]); ?></span></p>
                    </div>

                    <div class="link-wrap">
                        <div class="link-box row">
                            <a href="<?php echo U('Ucenter/index/information',array('uid'=>$self['uid']));?>">
                                <div class="col-xs-6 l-p0">
                                    <i class="os-icon-user"></i>
                                    个人主页
                                </div>
                            </a>
                            <a href="<?php echo U('Ucenter/index/myad');?>">
                                <div class="col-xs-6 r-p0">
                                    <i class="icon-map-marker"></i>
                                    我的广告
                                </div>
                            </a>
                        </div>
                        <div class="link-box row" style="border: none;padding-top: 0">
                            <a href="<?php echo U('ucenter/Config/index');?>" title="<?php echo L('_EDIT_INFO_');?>">
                                <div class="col-xs-6 l-p0">
                                    <i class="os-icon-settings"></i>
                                    <?php echo L('_EDIT_INFO_');?>
                                </div>
                            </a>
                            <div class="col-xs-6 r-p0"  style="cursor: pointer" event-node="logout" >
                                <i class="os-icon-logout"></i>
                                <?php echo L('_LOGOUT_');?>
                            </div>
                        </div>
                    </div>
                </ul>
            </li>
            <li class="dropdown-toggle dropdown-toggle-avatar li-hover show-hide-ul">
                <a title="<?php echo L('_EDIT_INFO_');?>" href="#" data-toggle="dropdown" >
                    <!--<i class="iconfont icon-caidan"></i>-->
                    <img src="/yoyo/Public/images/list.png">
                </a>
                <ul class="dropdown-menu  drop-self nav-menu" role="menu">
                    <?php $user_nav=S('common_user_nav'); if($user_nav===false){ $user_nav=D('UserNav')->order('sort asc')->where('status=1')->select(); S('common_user_nav',$user_nav); } ?>

                    <?php if(is_array($user_nav)): $i = 0; $__LIST__ = $user_nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a style="color:<?php echo ($vo["color"]); ?>"
                               target="<?php if(($vo["target"]) == "1"): ?>_blank<?php else: ?>_self<?php endif; ?>" href="<?php echo get_nav_url($vo['url']);?>">
                            <?php echo ($vo["title"]); ?>
                            <span class="label label-badge rank-label" title="<?php echo ($vo["band_text"]); ?>"
                                  style="background: <?php echo ($vo["band_color"]); ?> !important;color:white !important;"><?php echo ($vo["band_text"]); ?></span></a>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>

                    <?php $register_type=modC('REGISTER_TYPE','normal','Invite'); $register_type=explode(',',$register_type); if(in_array('invite',$register_type)){ ?>
                    <li>
                        <a href="<?php echo U('ucenter/Invite/invite');?>"><?php echo L('_INVITE_FRIENDS_');?></a>
                    </li>
                    <?php } ?>

                    <?php echo hook('personalMenus');?>
                    <?php if(check_auth('Admin/Index/index')): ?><li>
                            <a href="<?php echo U('Admin/Index/index');?>" target="_blank"><?php echo L('_MANAGE_BACKGROUND_');?></a>
                        </li><?php endif; ?>
                </ul>
            </li>
            <?php else: ?>
            <?php $open_quick_login=modC('OPEN_QUICK_LOGIN', 0, 'USERCONFIG'); $register_type=modC('REGISTER_TYPE','normal','Invite'); $register_type=explode(',',$register_type); $only_open_register=0; if(in_array('invite',$register_type)&&!in_array('normal',$register_type)){ $only_open_register=1; } ?>
            <script>
                var OPEN_QUICK_LOGIN = "<?php echo ($open_quick_login); ?>";
                var ONLY_OPEN_REGISTER = "<?php echo ($only_open_register); ?>";
            </script>
            <div class="from">
                <div class=" a-div">
                    <a class="top-btn" data-login="do_login"><?php echo L('_LOGIN_');?></a>
                    <a class="top-btn" data-role="do_register" data-url="<?php echo U('Ucenter/Member/register');?>"><?php echo L('_REGISTER_');?></a>
                </div>
            </div><?php endif; ?>
    </div>
</div>


<!--换肤插件钩子-->
<?php echo hook('setSkin');?>
<!--换肤插件钩子 end-->
<div id="tool" class="tool ">
    <?php echo hook('tool');?>
    <?php if(check_auth('Core/Admin/View')): ?><!--  <a href="javascript:;" class="admin-view"></a>--><?php endif; ?>
    <a  id="go-top" href="javascript:;" class="go-top "></a>

</div>
<?php if(is_login()&&(get_login_role_audit()==2||get_login_role_audit()==0)){ ?>
<div id="top-role-tip" class="alert alert-danger">
    <?php echo L('_TIP_ROLE_NOT_AUDITED1_');?> <strong><?php echo L('_TIP_ROLE_NOT_AUDITED2_');?></strong><?php echo L('_TIP_ROLE_NOT_AUDITED3_');?>
    <a target="_blank" href="<?php echo U('ucenter/config/role');?>"><?php echo L('_TIP_ROLE_NOT_AUDITED4_');?></a>
</div>
<script>
    $(function () {
        $('#top-role-tip').css('margin-top', $('#nav_bar').height() + 15);
        $('#top-role-tip').css('margin-bottom', -$('#nav_bar').height()+15);
    });
</script>
<?php } ?>



<script>

    function displaySubMenu(li) {
        var subMenu = li.getElementsByTagName("ul")[0];
        subMenu.style.display = "block";
    }
    function hideSubMenu(li) {
        var subMenu = li.getElementsByTagName("ul")[0];
        subMenu.style.display = "none";
    }
</script>
	<!-- /头部 -->
	
	<!-- 主体 -->
	<div class="main-wrapper">
    
    <!--顶部导航之后的钩子，调用公告等-->
<!--<?php echo hook('afterTop');?>-->
<!--顶部导航之后的钩子，调用公告等 end-->
    <div id="main-container" class="container">
        <div class="row">
            
    <!--导航-->
    <div class="col-xs-12">
        <div class="forum_module" style="background: none">
            <div class="ad-title">
                <?php $type = $order['coin_type'] == 1?'BTC':'ETH'; $coinNum = floatval($order['coin_num']); $price = number_format($order['price'],2); $params = ''; if($order['type'] == 1){ $params = 'sellonline-'; }else if($order['type'] == 2){ $params = 'buyonline-'; }else if($order['type'] == 3){ $params = 'selllocal-'; }else if($order['type'] == 4){ $params = 'buylocal-'; } $params.=$order['payType']."-".$order['countryEn']; $pay_time = $order['pay_time'] * 60; $timer = (time()-$order['create_time']); $remainTime = ceil(($pay_time-$timer)/60); if($order['status'] ==1){ $statusText = L('_TRADE_STATUS1_'); }else if($order['status'] == 2){ $statusText = L('_TRADE_STATUS2_'); }else if($order['status'] == 3){ $statusText = L('_TRADE_STATUS3_'); }else if($order['status'] == 4){ $statusText = L('_TRADE_STATUS4_'); }else if($order['status'] == 0){ $statusText = L('_TRADE_STATUS0_'); } ?>
                <div class="no-event">订单#<?php echo ($order['order_id']); ?>：以 <?php echo ($order["trade_price"]); ?> CNY 购买 <?php echo ($coinNum); ?> <?php echo ($type); ?></div>
                <div><a href="<?php echo U('Ucenter/index/information',array('uid'=>$order['ad_uid']));?>"><?php echo ($order['nickname']); ?></a> 的交易广告# <a href="tradead/<?php echo ($order['ad_id']); ?>/<?php echo ($params); ?>"><?php echo ($order['ad_id']); ?></a>，价格 <?php echo ($order["price"]); ?> <?php echo ($order["currency"]); ?>/<?php echo ($type); ?> </div>
            </div>

        </div>
    </div>
    <div class="col-xs-9">
        <div class="panel panel-info" style="min-height: 800px">
            <div class="panel-heading">
                <div id="search">
                    <div id="searchForm">
                        <input id="chat_id" type="hidden" value="0">
                        <?php $talk_self=query_user(array('avatar128')); ?>
                        <script>
                            var myhead = "<?php echo ($talk_self["avatar128"]); ?>";
                        </script>
                        <input type="text" class="form-control input-lg" id="chat_content"/>
                        <span id="web_uploader_wrapper_gallary_image"><i class="icon icon-paper-clip icon-2x"></i></span>
                        <button id="searchHelpBtn" type="button" class="btn btn-link" onclick="talker.post_message()"><img src="/yoyo/Application/Coin/Static/images/send.png" /></button>
                    </div>
                </div>
            </div>
            <?php $currentSession=D('Common/Talk')->getCurrentOrderSessions($order['order_id']); D('Common/TalkPush')->clearAll(); ?>
            <?php if(count($currentSession) != 0): ?><script>
                    $(function () {
                        talker.open("<?php echo ($currentSession["0"]["id"]); ?>");
                    })
                </script><?php endif; ?>
            <div class="panel-body" id="chat_box">
                <div class="row talk-body">
                    <div id="" class="row">
                        <div id="scrollContainer_chat">
                            <div class="text-muted" style="line-height: 258px;text-align: center;font-size: 32px"><?php echo L('_TALK_NONE_');?></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-3" style="z-index: 99">
        <div class="common_block_border event_right">
    <div class="ardor">
        <?php if(($order['type'] == 1 or $order['type'] == 3) and $order['get_uid'] == get_uid()): ?><p class="qtHead"><label><?php echo L('_TRADE_OPER_');?></label><label><?php echo L('_TRADE_TIME_');?> <span id="timer"></span> <?php echo L('_TIME_MINUTE_');?></label></p>
            <div class="alert alert-info" style="margin-top: 0px">
                <h4>交易信息</h4>
                <hr>
                <p>现在对方的数字货币已被托管锁定，您需要在<?php echo ($order["pay_time"]); ?>分钟内完成付款并点击 "付款已完成" 按钮，转账时请在留言中附上交易参考号。</p>
                <hr>
                <h4 style="text-align: center">付款信息</h4>
                <hr>
                <p>付款详细信息：<label><?php echo ($order["pay_remark"]); ?></label></p>
                <p>金额：<label><?php echo ($order["trade_price"]); ?> <?php echo ($order["currency"]); ?></label></p>
                <p>付款参考码：<label><?php echo ($order["pay_code"]); ?></label></p>
            </div>
            <div class="alert alert-warning" style="margin-top: 0px;">
                <h4>交易状态</h4>
                <hr>
                <p><?php echo ($statusText); ?></p>
                <hr>
                <?php if($order['status'] == 1){ ?>
                <a href="javascript:void(0)" class="btn btn-info" name="pay-ok">付款已完成</a>
                <a href="javascript:void(0)" class="btn btn-danger" id="cancel-trade" style="float: right">取消交易</a>
                <?php }else if($order['status'] == 1 || $order['status'] == 2){ ?>
                <a href="javascript:void(0)" class="btn btn-danger" id="cancel-trade">取消交易</a>
                <?php } ?>
            </div>
            <div class="alert alert-info" style="margin-top: 0px">
                <p>当托管启用时，只有买家和YOYOCOINS工作人员可以取消这笔交易。<a href="">了解托管策略</a></p>
                <p>如果交易过程中遇到问题，请查找帮助中心文档，或者联系客服<a href="">提交问题</a></p>
            </div>
            <?php elseif(($order['type'] == 1 or $order['type'] == 3) and $order['ad_uid'] == get_uid()): ?>
            <p class="qtHead"><label><?php echo L('_TRADE_OPER_');?></label></p>
            <div class="alert alert-info" style="margin-top: 0px">
                <h4 style="text-align: center">交易信息</h4>
                <hr>
                <p>买家：<?php echo ($getUser["getName"]); ?>(<?php echo ($getUser["tradeCount"]); ?>;<?php echo ($getUser["tradeScore"]); ?>%)</p>
                <p>已注资金额：<?php echo ($coinNum); ?> <?php echo ($type); ?></p>
                <p>向买家显示的付款详细信息:</p>
                <p>金额：<label><?php echo ($order["trade_price"]); ?> <?php echo ($order["currency"]); ?></label></p>
                <p>付款参考码：<label><?php echo ($order["pay_code"]); ?></label></p>
            </div>
            <div class="alert alert-warning" style="margin-top: 0px">
                <h4>交易状态</h4>
                <hr>
                <p><?php echo ($statusText); ?></p>
                <hr>
                <?php if($order['status'] == 2): ?><p>放行比特币之前，请确认您已收到相应的交易金额！</p>
                    <div style="margin-top: 10px">
                        <a href="" class="btn btn-info" name="send-coin">放行比特币</a>
                    </div><?php endif; ?>
            </div>
            <?php elseif(($order['type'] == 2 or $order['type'] == 4) and $order['get_uid'] == get_uid()): ?>
            <p class="qtHead"><label><?php echo L('_TRADE_OPER_');?></label></p>
            <div class="alert alert-info" style="margin-top: 0px">
                <h4>交易信息</h4>
                <hr>
                <p>买家：<?php echo ($order["nickname"]); ?>(<?php echo ($order["trade_count"]); ?>;<?php echo ($order["trade_score"]); ?>%)</p>
                <p>已注资金额：<?php echo ($coinNum); ?> <?php echo ($type); ?></p>
                <p>向买家显示的付款详细信息:</p>
                <p>金额：<label><?php echo ($order["trade_price"]); ?> <?php echo ($order["currency"]); ?></label></p>
                <p>付款参考码：<label><?php echo ($order["pay_code"]); ?></label></p>
            </div>
            <div class="alert alert-warning" style="margin-top: 0px">
                <h4>交易状态</h4>
                <hr>
                <p><?php echo ($statusText); ?></p>
                <hr>
                <?php if($order['status'] == 2): ?><p>放行比特币之前，请确认您已收到相应的交易金额！</p>
                    <div style="margin-top: 10px">
                        <a href="" class="btn btn-info" name="send-coin">放行比特币</a>
                    </div><?php endif; ?>
            </div>
            <?php elseif(($order['type'] == 2 or $order['type'] == 4) and $order['ad_uid'] == get_uid()): ?>
            <p class="qtHead"><label><?php echo L('_TRADE_OPER_');?></label><label><?php echo L('_TRADE_TIME_');?> <span id="timer"></span> <?php echo L('_TIME_MINUTE_');?></label></p>
            <div class="alert alert-info" style="margin-top: 0px">
                <h4>交易信息</h4>
                <hr>
                <p>现在对方的数字货币已被托管锁定，您需要在<?php echo ($order["pay_time"]); ?>分钟内完成付款并点击 "付款已完成" 按钮，转账时请在留言中附上交易参考号。</p>
                <hr>
                <h4 style="text-align: center">付款信息</h4>
                <hr>
                <p>付款详细信息：<label><?php echo ($order["pay_remark"]); ?></label></p>
                <p>金额：<label><?php echo ($order["trade_price"]); ?> <?php echo ($order["currency"]); ?></label></p>
                <p>付款参考码：<label><?php echo ($order["pay_code"]); ?></label></p>
            </div>
            <div class="alert alert-warning" style="margin-top: 0px">
                <h4>交易状态</h4>
                <hr>
                <p><?php echo ($statusText); ?></p>
                <hr>
                <?php if($order['status'] == 1){ ?>
                <a href="javascript:void(0)" class="btn btn-info" name="pay-ok">付款已完成</a>
                <a href="javascript:void(0)" class="btn btn-danger" id="cancel-trade" style="float: right">取消交易</a>
                <?php }else if($order['status'] != 0){ ?>
                <a href="javascript:void(0)" class="btn btn-danger" id="cancel-trade">取消交易</a>
                <?php } ?>
            </div>
            <div class="alert alert-info" style="margin-top: 0px">
                <p>当托管启用时，只有买家和YOYOCOINS工作人员可以取消这笔交易。<a href="">了解托管策略</a></p>
                <p>如果交易过程中遇到问题，请查找帮助中心文档，或者联系客服<a href="">提交问题</a></p>
            </div><?php endif; ?>
    </div>
</div>
    </div>
    <link rel="stylesheet" href="/yoyo/Application/Coin/Static/css/style.css">
    <script type="text/javascript" charset="utf-8" src="/yoyo/Public/static/ueditor/third-party/webuploader/webuploader.js"></script>
    <script type="text/javascript" src="/yoyo/Public/static/uploadify/jquery.uploadify.min.js"></script>
    <script>
        var gallary_num_image="<?php echo count($info['image']) ?>";
        $(function () {

            //image start
            var id_image = "#web_uploader_wrapper_gallary_image";
            if($(id_image).length>0) {
                var uploader_gallary_image = WebUploader.create({
                    // swf文件路径
                    swf: 'Uploader.swf',
                    // 文件接收服务端。
                    server: "<?php echo U('Core/File/uploadPicture',array('session_id'=>session_id()));?>",
                    fileNumLimit: 9,
                    // 选择文件的按钮。可选。
                    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                    pick: {'id': id_image, 'multi': true}
                });
                uploader_gallary_image.on('beforeFileQueued', function (file) {
                    if (gallary_num_image >= 9) {
                        toast.error('图片不能超过9张');
                        return false;
                    }
                });
                uploader_gallary_image.on('fileQueued', function (file) {
                    gallary_num_image = parseInt(gallary_num_image) + 1;

                    uploader_gallary_image.upload();
                    $("#web_uploader_file_name_gallary_image").text('正在上传...');
                });

                /*上传成功*/
                uploader_gallary_image.on('uploadSuccess', function (file, ret) {
                    if (ret.status == 0) {
                        $("#web_uploader_file_name_gallary_image").text(ret.info);
                    } else {
                        talker.post_message(2,ret.data.file.path);
                        $('#web_uploader_input_gallary_image').focus();
                        $('#web_uploader_input_gallary_image').val(ret.data.file.id);
                        $('#web_uploader_input_gallary_image').blur();
                        $("#web_uploader_picture_list_gallary_image").append('<img class="gallary_thumb" onclick="remove_file(this,' + "'image'" + ')" src="' + ret.data.file.path + '"/><input type="hidden" name="image[]" value="' + ret.data.file.id + '"/>');
                    }
                });
            }
            //image end

        })

        $("a[name='pay-ok']").click(function () {
            if (confirm("确定要标记付款已完成吗？")){
                $.post("<?php echo U('/order');?>", {orderId:"<?php echo $order['order_id']; ?>",type:1}, success, "json");
                return false;
                function success(data) {
                    if (data.status) {
                        toast.success(data.status);
                        window.location.reload();
                    }
                }
            }else{
                return false;
            }
        });
        $("a[name='send-coin']").click(function () {
            if (confirm("确定要放行比特币吗？")){
                $.post("<?php echo U('/order');?>", {orderId:"<?php echo $order['order_id']; ?>",type:1}, success, "json");
                return false;
                function success(data) {
                    if (data.status) {
                        toast.success(data.status);
                        window.location.reload();
                    }
                }
            }else{
                return false;
            }
        });
        $("#cancel-trade").click(function () {
            if (confirm("确定要取消交易吗？")){
                $.post("<?php echo U('/order');?>", {orderId:"<?php echo $order['order_id']; ?>",type:2}, success, "json");
                return false;
                function success(data) {
                    if (data.status) {
                        toast.success(data.status);
                        window.location.reload();
                    }
                }
            }else{
                return false;
            }
        });
        $(function () {
            var status = <?php echo ($order["status"]); ?>;
            var code;
            if(status == 1){
                var remainTime = <?php echo ($remainTime); ?>;
                if(remainTime > 0){
                    $("#timer").text(remainTime);
                    code = setInterval(GetRTime,60*1000);
                }else{
                    $.post("<?php echo U('/timeOver');?>", {orderId:"<?php echo $order['order_id']; ?>"}, success, "json");
                    return false;
                    function success(data) {
                        if (data.status) {
                            window.location.reload();
                            window.clearInterval(code);
                        }
                    }
                }
            }
            function GetRTime(){
                remainTime-=1;
                $("#timer").text(remainTime);
            }
        })
    </script>

        </div>
    </div>
</div>
	<!-- /主体 -->

	<!-- 底部 -->
	<footer class="footer">
    <div class="container ftTop">
        <div class="ftBox">
            <p class="ftTit">关于我们</p>
            <div class="ftMain">
                <?php echo modC('ABOUT_US',L('_NOT_SET_NOW_'),'Config');?>
            </div>
        </div>
        <div class="ftBox">
            <p class="ftTit">友情链接</p>
            <div class="ftLink">
                <?php echo W('Common/SuperLinks/superLinks');?>
            </div>
        </div>
        <div class="ftBox codeBox">
            <?php $logo = get_cover(modC('QRCODE_BOTTOM',0,'Config'),'path'); $logo = $logo?$logo:'/yoyo/Public/images/code.png'; ?>
            <div class="code">
                <div class="ftCircle" style="background-color: #6cc775"><i class="iconfont icon-weixin1"></i></div>
                <p>官方微信</p>
                <div class="imgWrap"><img src="<?php echo ($logo); ?>" alt=""></div>
            </div>
        </div>
    </div>
    <div class="container ftOther">
        <?php echo modC('COMPANY', L('_NOT_SET_NOW_'), 'Config');?>
    </div>
    <div class="container ftBottom text-center">
        <p>
            <span><?php echo modC('COPY_RIGHT',L('_NOT_SET_NOW_'),'Config');?></span>
            <span><?php echo modC('ICP','浙ICP备12042711号-5', 'Config');?><br><a href="http://www.opensns.cn" target="_blank">Powered by OpenSNS</a></span>
        </p>
        <?php echo ($count_code); ?>
    </div>
</footer>

<!-- jQuery (ZUI中的Javascript组件依赖于jQuery) -->


<!-- 为了让html5shiv生效，请将所有的CSS都添加到此处 -->
<link type="text/css" rel="stylesheet" href="/yoyo/Public/static/qtip/jquery.qtip.css"/>


<!--<script type="text/javascript" src="/yoyo/Public/js/com/com.notify.class.js"></script>-->

<!-- 其他库-->
<!--<script src="/yoyo/Public/static/qtip/jquery.qtip.js"></script>
<script type="text/javascript" src="/yoyo/Public/js/ext/slimscroll/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="/yoyo/Public/static/jquery.iframe-transport.js"></script>

<script type="text/javascript" src="/yoyo/Public/js/ext/magnific/jquery.magnific-popup.min.js"></script>-->

<!--<script type="text/javascript" src="/yoyo/Public/js/ext/placeholder/placeholder.js"></script>
<script type="text/javascript" src="/yoyo/Public/js/ext/atwho/atwho.js"></script>
<script type="text/javascript" src="/yoyo/Public/zui/js/zui.js"></script>-->
<link type="text/css" rel="stylesheet" href="/yoyo/Public/js/ext/atwho/atwho.css"/>

<script src="/yoyo/Public/js.php?t=js&f=js/com/com.notify.class.js,static/qtip/jquery.qtip.js,js/ext/slimscroll/jquery.slimscroll.min.js,js/ext/magnific/jquery.magnific-popup.min.js,js/ext/placeholder/placeholder.js,js/ext/atwho/atwho.js,zui/js/zui.js&v=<?php echo ($site["sys_version"]); ?>.js"></script>
<script type="text/javascript" src="/yoyo/Public/static/jquery.iframe-transport.js"></script>

<script src="/yoyo/Public/js/ext/lazyload/lazyload.js"></script>

<script src="/yoyo/Public/js/socket.io.js"></script>

<!-- 用于加载js代码 -->
<!-- 页面footer钩子，一般用于加载插件JS文件和JS代码 -->
<?php echo hook('pageFooter', 'widget');?>
<!-- 调用全站公告部件-->
<?php echo W('Common/Announce/render');?>

<!-- 调用消息部件-->
<?php echo W('Common/Message/render');?>
<div class="hidden"><!-- 用于加载统计代码等隐藏元素 -->
    <?php echo ($count_code); ?>
    
</div>

<script>
    // VERSION_NAME 替换为项目的版本，VERSION_CODE 替换为项目的子版本
  //  new Bugtags('d6023daa6c7467634636c87b3f16213e','8.12','VERSION_CODE');
</script>

	<!-- /底部 -->
</body>
</html>