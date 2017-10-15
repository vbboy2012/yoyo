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



<!--Style-->
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
<script src="/yoyo/Public/css/fa.css"></script>
<style>
    body {
        color: #34495e;
        font-family: "Open Sans", sans-serif;
        padding: 0px !important;
        margin: 0px !important;
        direction: "ltr";
        font-size: 14px; }
    .navbar-brand{
        font-size: 25px;
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
        <div class="img-wrap" style="">
            <a class="navbar-brand" href="<?php echo U('Home/Index/index');?>"><i class="icon icon-compass icon-2x"></i>YoYoCoins</a>
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
            
    <style>
        #main-container {
            width: 1000px;
            margin-top: 70px;
        }
    </style>
    <script type="text/javascript" src="/yoyo/Public/js/ajaxfileupload.js"></script>
    <link href="/yoyo/Application/Weibo/Static/css/weibo.css" type="text/css" rel="stylesheet"/>
    <link href="/yoyo/Application/Weibo/Static/css/circle.css" type="text/css" rel="stylesheet"/>
    <link href="//at.alicdn.com/t/font_lnovro4c22ihpvi.css" type="text/css" rel="stylesheet"/>
   <link rel="stylesheet" href="<?php echo getRootUrl();?>Addons/InsertXiami/_static/css/xiami.css">
    <!--微博内容列表部分-->
    <div class="weibo_middle pull-left">
        <?php if($show_post): ?><div style="display: none" class="weibo_content weibo-content-post weibo_post_box" id="send_box">
        <div class="weibo_content_p">
            <div class="send-top  row" id="input_tip">
                <div class="pull-left chose-circle">
                    <div class="selectt">
                        <div class="dropdown">
                            <img data-role="title_image" class="crowd-img" src="/yoyo/Application/Weibo/Static/images/all.png">
                            <div class="like-input dropdown-toggle" data-toggle="dropdown">
                                <?php echo ((isset($crowd_detail["title"]) && ($crowd_detail["title"] !== ""))?($crowd_detail["title"]):'全站动态'); ?>
                            </div>
                            <span class="caret"></span>
                            <ul class="dropdown-menu cc-menu" role="menu" aria-labelledby="dropdownMenu1">
                                <li>
                                    <a href="javascript:" data-role="crowd_title" data-id="0" data-title="全站动态"  data-img="/yoyo/Application/Weibo/Static/images/all.png"><img src="/yoyo/Application/Weibo/Static/images/all.png" class="crowd-img-title"/>全站动态</a>
                                </li>
                                <?php if(is_array($follow_crowd_list)): $i = 0; $__LIST__ = $follow_crowd_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><li>
                                        <a href="javascript:" data-role="crowd_title" data-id="<?php echo ($data["crowd"]["id"]); ?>" data-title="<?php echo ($data['crowd']['title']); ?>" data-img="<?php echo (getthumbimagebyid($data['crowd']['logo'],20,20)); ?>"><img class="crowd-img-title" src="<?php echo (getthumbimagebyid($data['crowd']['logo'],20,20)); ?>"/><?php echo ($data['crowd']['title']); ?></a>
                                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="pull-right" data-role="sync_sina_weibo" title="同步到新浪微博" data-url="<?php echo addons_url('SyncLogin://Base/login',array('type'=>'sina'));?>">
                    <i class="iconfont icon-sina weibo-icon"></i>
                </p>
                <input type="hidden" value="0" id="sync_sina_weibo">
            </div>
            <div class="tare">
                 <textarea class="form-control weibo-word" id="weibo_content" placeholder="<?php echo modC('WEIBO_INFO',L('_TIP_SOMETHING_TO_SAY_'));?>" onfocus="startCheckNum_quick($(this))"
                           onblur="endCheckNum_quick()"></textarea>
                <div class="limit-num weibo-num-position text-right">
                    <span><?php echo modC('WEIBO_NUM',140,'WEIBO');?></span> / <span><?php echo modC('WEIBO_NUM',140,'WEIBO');?></span>
                </div>
            </div>

            <div class="op-wrap ">
                <!--插件-->
                <div class="pull-left row addons-wrap">
                    <a title="插入表情" href="javascript:" onclick="insertFace($(this))" data-role="insert_face">
                        <i class="iconfont icon-biaoqing i-bq" ></i>
                    </a>
                    <?php if(modC('CAN_IMAGE',1)): ?><a title="插入图片" href="javascript:" id="insert_image" onclick="insert_image.insertImage(this)" data-role="hook_show">
                            <i class="iconfont icon-tupian i-tp" ></i>
                        </a><?php endif; ?>
                    <?php if(modC('CAN_TOPIC',1)): ?><a title="插入话题" href="javascript:" onclick="insert_topic.InsertTopic(this)">
                            <i class="iconfont icon-tianjiahuati i-ht"></i>
                        </a><?php endif; ?>
                    <a title="当前位置" href="javascript:" onclick="show_pos()">
                        <i class="iconfont icon-dingwei11 i-dw" style=""></i>
                    </a>
                    <div class="get-pos" id="show-pos" style="display: none"><span class="text-muted" style="margin-left: 10px">正在获取位置……</span></div>
                    <?php echo hook('weiboType');?>
                    <div id="emot_content" class="emot_content"></div>
                    <div id="hook_show" class="emot_content"></div>
                </div>
                <script>
                    function  show_pos() {
                        $('#show-pos').show();
                        $("#show-pos").load("<?php echo U('Weibo/Index/getPos');?>");


                    }
                    function confirm_pos(e) {
                        if($('[name=pos]').val()!=''){
                            $('.get-pos i').css('color','#333');
                            $('#show-pos').hide()
                        }else{
                            initPos();
                        }
                    }
                </script>
                <div class="pull-right right-wrong">
                    <a href="javascript:" class="send-right" data-role="send_weibo" data-crowd="<?php echo ($crowd_type); ?>" data-url="<?php echo U('Weibo/Index/doSend');?>" ><i class="iconfont icon-dui"></i></a>
                    <a href="javascript:" class="send-wrong" data-role="change_back"><i class="iconfont icon-cuo"></i></a>
                </div>
                <!--话题-->
                <div class="pull-right"><?php echo use_topic();?></div>
            </div>
        </div>
    </div>
    <script>
        var ID_setInterval;
        function checkNum_quick(obj) {
            var value = obj.val();
            var value_length = value.length;
            var can_in_num = initNum - value_length;
            if (can_in_num < 0) {
                value = value.substr(0, initNum);
                obj.val(value);
                can_in_num = 0;
            }
            var html =  can_in_num + " / "+initNum;
            $('.limit-num').html(html);
        }
        function startCheckNum_quick(obj) {
            ID_setInterval = setInterval(function () {
                checkNum_quick(obj);
            }, 250);
        }
        function endCheckNum_quick() {
            clearInterval(ID_setInterval);
        }

        $('[data-role="insert_face"]').click(function() {
            $("#hook_show").css("display", "none");
            $("#emot_content").css("display", "block");
        });
        $('[data-role="hook_show"]').click(function() {
            $("#emot_content").css("display", "none");
            $("#hook_show").css("display", "block");
        });
        $('[data-role="change_back"]').click(function() {
            $("#send_box").hide();
            $(".black-filter").show();
            $.cookie("wb_type","");
        });
        $('[data-role="crowd_title"]').click(function() {
            var $this = $(this);
            var title = $this.attr('data-title');
            var crowd_id = $this.attr('data-id');
            var img = $this.attr('data-img');
            $('.like-input').html(title);
            $('[data-role="title_image"]').attr('src',img);
            $('[data-role="send_weibo"]').attr('data-crowd',crowd_id);
        })
    </script>
    <script type="text/javascript" charset="utf-8" src="/yoyo/Public/js/ext/webuploader/js/webuploader.js"></script>
    <link href="/yoyo/Public/js/ext/webuploader/css/webuploader.css" type="text/css" rel="stylesheet"><?php endif; ?>



        <!--  筛选部分-->
        <div class="black-filter row">
            <div class="s-wb-box" data-role="show-sendBox">
                <div class="s-wb-icon">
                    <i class="iconfont icon-fabu"></i>
                </div>
                <p>发文字</p>
            </div>
            <div class="s-wb-box" data-role="show-long-sendBox">
                <div class="s-wb-icon" style="background-color: #66CDCC">
                    <i class="iconfont icon-dongtaidongtai"></i>
                </div>
                <p>发文章</p>
            </div>
        </div>
        <div class="weibo-filter-wrap">
            <div class="add-weibo" data-role="switch_sendBox">
                <span><?php echo modC('WEIBO_INFO',L('_TIP_SOMETHING_TO_SAY_'));?></span><i class="send-icon"></i>
            </div>
            <?php if(!is_login()) $style='margin-top:0' ?>
            <div id="weibo_filter">
                <div class="weibo_icon">
                    <?php $show_icon_eye_open=0; if(count($top_list)){ $hide_ids=cookie('Weibo_index_top_hide_ids'); if(mb_strlen($hide_ids,'utf-8')){ $hide_ids=explode(',',$hide_ids); foreach($top_list as $val){ if(in_array($val,$hide_ids)){ $show_icon_eye_open=1; break; } }}} if(count($top_list)){ if($show_icon_eye_open){ ?>
                    <li data-weibo-id="<?php echo ($weibo["id"]); ?>" title="<?php echo L('_SHOW_ALL_TOP_'); echo ($MODULE_ALIAS); ?>"
                        data-role="show_all_top_weibo">
                        <i class="icon icon-eye-open"></i>
                    </li>
                    <?php }else{ ?>
                    <li data-weibo-id="<?php echo ($weibo["id"]); ?>" title="<?php echo L('_SHOW_ALL_TOP_'); echo ($MODULE_ALIAS); ?>"
                        data-role="show_all_top_weibo" style="display: none;">
                        <i class="icon icon-eye-open"></i>
                    </li>
                    <?php }} ?>
                </div>
                <?php if(is_array($tab_config)): $i = 0; $__LIST__ = $tab_config;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tab): $mod = ($i % 2 );++$i;?><li class="a-wrap">
                        <a class="a-wrap-a" id="<?php echo ($tab); ?>"
                        <?php if(in_array($tab,$need_login_tab)): ?>href="javascript:toast.error('请先登录！');"
                            <?php else: ?>
                            href="<?php echo U('Weibo/Index/index',array('type'=>$tab));?>"<?php endif; ?>
                        >
                        <?php switch($tab): case "concerned": ?><div class="show-circle" data-role="show-circle">
                                    <i class="icon icon-flow"></i>
                                    <span class="mg-bt0"><?php echo L('_MY_FOLLOWING_');?></span>
                                </div><?php break;?>
                            <?php case "hot": ?><i class="icon icon-hot"></i>
                                <span class="mg-bt0"><?php echo L('_HOT_WEIBO_');?></span><?php break;?>
                            <?php case "all": ?><i class="icon icon-all"></i>
                                <span class="mg-bt0"><?php echo L('_ALL_WEBSITE_WEIBO_');?></span><?php break;?>
                            <?php case "fav": ?><i class="icon icon-my"></i>
                                <span class="mg-bt0"><?php echo L('_MY_FAV_');?></span><?php break; endswitch;?>
                        </a>
                    </li><?php endforeach; endif; else: echo "" ;endif; ?>
                <!--圈子触发按钮-->
                <div class="show-circle" data-role="show-circle"></div>
                <!--圈子触发按钮-->
                <!--我信任的圈子列表stat-->
                <div class="my-follow-circle" data-role="close-circle">
                    <div class="follow-left">
                        <div class="l-circle">
                            <p>我<br/>的<br/>圈<br/>子</p>
                        </div>
                        <div class="m-circle">
                            <a href=<?php echo U('Weibo/Index/index',array('type'=>concerned));?>>
                                <div class="my-circle">
                                    <img class="m-cover" src="/yoyo/Application/Weibo/Static/images/follow.png" alt="" title="我的信任">
                                    <p class="text-more" style="display: block" title="我的信任">我的信任</p>
                                </div>
                            </a>
                            <?php if(is_array($follow_crowd_list)): $i = 0; $__LIST__ = $follow_crowd_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Weibo/Index/index',array('crowd'=>$data['crowd']['id']));?>">
                                    <div class="my-circle">
                                        <img class="m-cover" src="<?php echo (getthumbimagebyid($data["crowd"]["logo"],80,80)); ?>" alt="" title="社群封面">
                                        <p class="text-more" style="display: block" title="<?php echo ($data["crowd"]["title"]); ?>"><?php echo ($data["crowd"]["title"]); ?></p>
                                    </div>
                                </a><?php endforeach; endif; else: echo "" ;endif; ?>
                        </div>
                    </div>
                    <a href="<?php echo U('Weibo/Crowd/index');?>">
                        <div class="follow-right l-circle">
                            <p>查<br/>看<br/>全<br/>部</p>
                        </div>
                    </a>
                </div>
                <!--我信任的圈子列表end-->
            </div>
            <script>
                $('#weibo_filter #<?php echo ($filter_tab); ?>').addClass('active');
                $('[data-role="show-circle"]').mouseover(function () {
                    $('.my-follow-circle').fadeIn("slow");
                    var leftHeight = $(".follow-left").height();
                    $(".l-circle").css('height', leftHeight + "px");
                    $(".follow-right").css('height', (leftHeight+20) + "px");
                });
                $('[data-role="close-circle"]').mouseleave(function () {
                    $('.my-follow-circle').fadeOut("slow");
                })
            </script>
        </div>
        <?php if(!empty($recom_topic["last_weibo"]["content"])): ?><div class="hot-topic clearfix">
                <div class="new-hot-topic row">
                    <!--正在热议角标-->
                    <div class="now-hot">热议</div>
                    <div class="new-wb">
                        <div class="col-md-2" style="max-width: 85px">
                            <a href="<?php echo ($recom_topic["last_weibo"]["user"]["space_url"]); ?>"><img class="avatar-img" src="<?php echo ($recom_topic["last_weibo"]["user"]["avatar64"]); ?>" style="width: 64px;"/></a>
                        </div>
                        <div class="col-md-10">
                            <p><a href="<?php echo ($recom_topic["last_weibo"]["user"]["space_url"]); ?>"><?php echo ($recom_topic["last_weibo"]["user"]["nickname"]["nickname"]); ?></a></p>
                            <p class="text-muted text-ellipsis last-weibo-content" style="max-width: 335px"><?php echo (text($recom_topic["last_weibo"]["content"])); ?></p>
                            <p class="text-muted" style="font-size: 12px"><?php echo (friendlydate($recom_topic["last_weibo"]["create_time"])); ?></p>
                        </div>
                    </div>
                    <div class="topic-wrap row">
                        <div class="cover-wrap">
                            <a href="<?php echo U('Topic/index',array('topk'=>$recom_topic['id']));?>">
                                <img class="cover" src="<?php if($recom_topic["logo"] != 0): echo (thumb($recom_topic["logo"],250,250)); else: ?>/yoyo/Application/Weibo/Static/images/topicavatar.png<?php endif; ?>"/>
                            </a>
                        </div>
                        <a href="<?php echo U('Topic/index',array('topk'=>$recom_topic['id']));?>">
                            <div class="info-wrap">
                                <p class="text-more">#<?php echo ($recom_topic['name']); ?>#</p>
                                <p class="text-more"><?php echo ($recom_topic['intro']); ?></p>
                                <p class="text-more read-num"><?php echo ($recom_topic['read_count']); ?> 阅读 </p>
                            </div>
                        </a>
                    </div>
                </div>
            </div><?php endif; ?>
        <input type="hidden" value="<?php echo ($smallnav); ?>" id="smallnav">
        <div class="small-nav" >
            <li class="list-type select" data-role="select-li">
                <a id="all_" href="<?php echo U('Weibo/Index/index',array('select'=>'all_'));?>">
                    <p class="mg-bt0">全部</p>
                </a>
            </li>
            <li class="list-type" data-role="select-li">
                <a id="image" href="<?php echo U('Weibo/Index/index',array('select'=>'image'));?>">
                    <p class="mg-bt0">多图</p>
                </a>
            </li>

            <li class="list-type" data-role="select-li">
                <a id="video" href="<?php echo U('Weibo/Index/index',array('select'=>'video'));?>">
                    <p class="mg-bt0">视频</p>
                </a>
            </li>
            <li class="list-type" data-role="select-li">
                <a id="musics" href="<?php echo U('Weibo/Index/index',array('select'=>'musics'));?>">
                    <p class="mg-bt0">音乐</p>
                </a>
            </li>
            <li class="list-type" data-role="select-li">
                <a id="longWeibo" href="<?php echo U('Weibo/Index/index',array('select'=>'longWeibo'));?>">
                    <p class="mg-bt0">文章</p>
                </a>
            </li>
            <li class='small-nav-search'>
                <div class="search-wrap">
                    <form style="margin-right: -24px;display: none;" id="search-form" action="<?php echo U('Weibo/Index/search');?>" method="post" role="search">
                        <input class="wb-search" id="search-text" type="text" placeholder="输入关键字" name="keywords" value="">
                        <i class="icon-search" style="left: -25px;cursor: pointer;" data-role="do-search" ></i>
                    </form>
                </div>
                <div class="animate-wrap" data-role="search-btn">
                    <i class="icon-search" ></i>
                </div>
            </li>
        </div>
        <!--筛选部分结束-->
        <?php G('a'); ?>
        <div id="top_list">
            <?php if(is_array($top_list)): $i = 0; $__LIST__ = $top_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i; echo W('WeiboDetail/detail',array('weibo_id'=>$top,'can_hide'=>1)); endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <?php G('b'); ?>
        <div style="display: none;"><?php echo(G('a','b')); ?></div>
        <?php if(!empty($crowd_detail)): ?><div id="crowd_top_list">
                <?php if(is_array($crowd_top_list)): $i = 0; $__LIST__ = $crowd_top_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i; echo W('WeiboDetail/detail',array('weibo_id'=>$top,'can_hide'=>1)); endforeach; endif; else: echo "" ;endif; ?>
            </div><?php endif; ?>
        <div id="weibo_list">
            <?php if($page != 1){ ?>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$weibo): $mod = ($i % 2 );++$i; echo W('Weibo/WeiboDetail/detail',array('weibo_id'=>$weibo));?>
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


            <?php } ?>

        </div>
        <div id="load_more" class="text-center text-muted"
        <?php if($page != 1): ?>style="display:none"<?php endif; ?>
        >
        <div id="load_more_text">
            <div class="sk-cube-grid">
                <div class="sk-cube sk-cube1"></div>
                <div class="sk-cube sk-cube2"></div>
                <div class="sk-cube sk-cube3"></div>
                <div class="sk-cube sk-cube4"></div>
                <div class="sk-cube sk-cube5"></div>
                <div class="sk-cube sk-cube6"></div>
                <div class="sk-cube sk-cube7"></div>
                <div class="sk-cube sk-cube8"></div>
                <div class="sk-cube sk-cube9"></div>
            </div>
        </div>
        <?php if($invisible == 1): ?><p class="private-crowd-toast">该圈子已设为私密,您需加入后才能浏览</p><?php endif; ?>
    </div>

    <!--分页-->
    <div id="index_weibo_page" style=" <?php if($page == 1): ?>display:none<?php endif; ?>">
        <div class="text-right">
            <?php echo getPagination($total_count,10);?>
        </div>
    </div>
    </div>


    <!--微博内容列表部分结束-->
    <!--首页右侧部分-->
    <div class="weibo_right">
    <!--当前圈子stat-->
    <?php if(!empty($crowd_detail)): ?><div class="now-circle-wrap">
    <div class="circle-manage row">
        <div class="c-c-wrap">
            <a href="<?php echo U('Weibo/Crowd/Member',array('id'=>$crowd_detail['id']));?>"><img
                    src="<?php echo (getthumbimagebyid($crowd_detail["logo"],80,80)); ?>" alt=""></a>
        </div>
        <div class="c-i-wrap">
            <a href="<?php echo U('Weibo/Crowd/Member',array('id'=>$crowd_detail['id']));?>"><p class="c-name">
                <?php echo ($crowd_detail["title"]); ?></p></a>
            <p class="c-intro"><?php echo ($crowd_detail["intro"]); ?></p>
            <p class="c-num">
                <span class="pull-left">成员<a href="<?php echo U('Weibo/Crowd/Member',array('id'=>$crowd_detail['id']));?>"><strong><?php echo ($crowd_detail["member_count"]); ?></strong></a></span>
                <span class="pull-right">讨论<strong><?php echo ($crowd_detail["post_count"]); ?></strong></span>
            </p>
            <!--判断，管理员才能看到-->
            <?php if(($crowd_detail["is_admin"]) == "true"): if(($crowd_detail["check_num"]) != "0"): ?><p class="c-wait"><a
                            href="<?php echo U('Weibo/Crowd/crowd',array('id'=>$crowd_detail['id'],'type'=>check));?>"
                            style="color: #ff0000;">有<span><?php echo ($crowd_detail["check_num"]); ?></span>位成员等待审核加入</a></p><?php endif; endif; ?>
        </div>
        <div class="c-e-wrap">
            <!--如果是普通用户则看到的是加入按钮-->
            <a href="javascript:" title="申请加入" class="want-join"><i class="icon-plus"></i></a>
            <!--如果管理员则看到的是管理按钮-->
            <?php if(($crowd_detail["is_admin"]) == "true"): ?><div class="down-wrap">
                    <a href="javascript:" title="管理圈子" class="manage-circle">管理</a>
                    <ul class="edit-icon">
                        <a href="<?php echo U('Weibo/Crowd/crowd',array('id'=>$crowd_detail['id']));?>">
                            <li>成员管理</li>
                        </a>
                        <a href="<?php echo U('Weibo/Crowd/crowd',array('id'=>$crowd_detail['id']));?>#change">
                            <li>修改圈子</li>
                        </a>
                        <a href="javascript:" data-toggle="modal" data-target="#mySmModal" data-moveable="true">
                            <li class="dismiss">解散圈子</li>
                        </a>
                    </ul>
                </div><?php endif; ?>
        </div>
    </div>
    <!--输入立即解散来解散圈子-->
    <div class="modal fade" id="mySmModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">您确定要解散<span>"圈子名称"</span>？</h4>
                </div>
                <div class="modal-body">
                    <p>请在框中输入“立即解散”来确认解散</p>
                    <input type="text" data-role="confirm_del_crowd">
                    <div class="c-btn-wrap row">
                        <a href="javascript:" class="sure" data-crowd-id="<?php echo ($crowd_detail["id"]); ?>"
                           data-role="del_crowd">确定</a>
                        <a href="javascript:" data-dismiss="modal">取消</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="circle-notice row">
        <p><?php echo ((isset($crowd_detail["notice"]) && ($crowd_detail["notice"] !== ""))?($crowd_detail["notice"]):'未设置公告'); ?></p>
        <p>
            <!--todo-->
            <!--<a href="" class="pull-left">查看详情</a>-->
            <!--判断，管理员才能看到-->
        </p>
    </div>
</div>
<!--当前圈子end-->
<!--圈主展示start-->
<div class="wb-topic">
    <p class="crowd-head">圈主</p>
    <div class="now-circle-wrap">
        <div class="circle-manage row">
            <div class="c-c-wrap">
                <a href="<?php echo ($crowd_detail["crowd_admin"]["space_url"]); ?>"><img src="<?php echo ($crowd_detail["crowd_admin"]["avatar128"]); ?>"
                                                                     alt=""></a>
            </div>
            <div class="c-i-wrap c-i-wrap-width">
                <a href="<?php echo ($crowd_detail["crowd_admin"]["space_url"]); ?>"><p class="c-name">
                    <?php echo ($crowd_detail["crowd_admin"]["nickname"]); ?></p></a>
                <p class="c-intro c-crowd-admin-fans">
                            <span class="pull-left">粉丝:<a href="<?php echo ($crowd_detail["crowd_admin"]["space_url"]); ?>"><span
                                    style="color: #999;"> <?php echo ($crowd_detail["crowd_admin"]["fans"]); ?></span></a></span>
                </p>
            </div>
            <div class="c-follow">
                <?php if($crowd_detail["uid"] != is_login()): echo W('Common/Follow/follow',array('follow_who'=>$vo['uid'])); endif; ?>
            </div>
        </div>
    </div>
</div>
<!--圈主展示end-->
<!--圈子排行start-->
<div class="wb-topic">
    <p class="crowd-head">圈子排行</p>
    <div class="topic-content" style="height: 210px !important;">
        <ul>
            <?php if(is_array($crowd_rank)): $one_crowd_key = 0; $__LIST__ = $crowd_rank;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$crowd): $mod = ($one_crowd_key % 2 );++$one_crowd_key;?><li data-role="crowd_rank" style="clear: both;">
                    <a href="<?php echo U('Index/index',array('crowd'=>$crowd['id']));?>" title="<?php echo ($one_topic['name']); ?>">
                        <div class="num"><?php echo ($one_crowd_key); ?></div>
                        <div><?php echo ($crowd['title']); ?></div>
                        <div><?php echo ($crowd['member_count']); ?></div>
                    </a>
                    <div style="position: relative;display: none">
                        <div class="crowd-rank-num"><?php echo ($one_crowd_key); ?></div>
                        <div style="" class="crowd-rank-detail">
                            <a href="<?php echo U('Index/index',array('crowd'=>$crowd['id']));?>"><img
                                    src="<?php echo (getthumbimagebyid($crowd["logo"], 80,80)); ?>"></a>
                            <a href="<?php echo U('Index/index',array('crowd'=>$crowd['id']));?>"><p><?php echo ($crowd['title']); ?></p>
                            </a>
                            <span class="pull-left detail-bottom">成员 <?php echo ($crowd["member_count"]); ?></span>
                            <span class="pull-right detail-bottom">讨论 <?php echo ($crowd["post_count"]); ?></span>
                        </div>
                        <?php switch($crowd["is_follow"]): case "0": ?><a href="javascript:" class="crowd-a crowd-attend" data-role="follow_crowd"
                                   data-id="<?php echo ($crowd["id"]); ?>">
                                    +加入
                                </a><?php break;?>
                            <?php case "1": ?><a href="javascript:" class="crowd-a crowd-attend" data-role="unfollow_crowd"
                                   data-id="<?php echo ($crowd["id"]); ?>">
                                    已加入
                                </a><?php break;?>
                            <?php case "2": ?><a href="javascript:" class="crowd-a crowd-attend" data-id="<?php echo ($crowd["id"]); ?>">
                                    待审核
                                </a><?php break;?>
                            <?php default: ?>
                            <a href="javascript:" class="crowd-a crowd-attend" data-role="follow_crowd"
                               data-id="<?php echo ($crowd["id"]); ?>">
                                +加入
                            </a><?php endswitch;?>
                    </div>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
</div>
<!--圈子排行end-->
        <?php else: ?>
        <!--我的社群stat-->
<?php if(is_login()): ?><div class="my-community">
        <div class="ck-sign">
            <?php if(!$check){ ?>
            <img src="/yoyo/Application/Weibo/Static/images/checking.png" alt="">
            <?php }else{ ?>
            <img src="/yoyo/Application/Weibo/Static/images/checked.png" alt="">
            <?php } ?>
        </div>
        <p class="notice-head">我的社群</p>
        <div class="mc-grade row">
            <div class="col-xs-3 mc-box left-box">
                <div class="mc-icon-wrap">
                    <i class="i-heart"></i>
                </div>
            </div>
            <div class="col-xs-4 mc-box center-box">
                <span><?php echo ($today_score); ?></span>
                <p>今日经验</p>
            </div>
            <div class="col-xs-5 mc-box text-ellipsis">
                <span style="color: #666"><?php echo (intval($title["left"])); ?></span>
                <p style="margin: 0 25px">剩余经验</p>
            </div>
        </div>
        <div class="grade-box">
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
            <div class="row">
                <div class="col-xs-3 l-box"><span>当前等级</span></div>
                <div class="col-xs-9 r-box">
                    <div id="upgrade" class="upgrade" data-toggle="tooltip" data-placement="bottom" title="">
                        <div class="grade-bottom"></div>
                        <div class="grade-top" style="width:<?php echo ($title["percent"]); ?>%;"></div>
                    </div>
                </div>
            </div>

            <div class="row gg-wrap">
                <p class="pull-left"><?php echo ($self["title"]); ?></p>
                <p class="pull-right" style="color: #999"><?php echo ($title["next"]); ?></p>
            </div>
        </div>
        <div class="gg-check row">
            <div class="col-xs-3 c-box" data-role="open_checkBox" style="cursor: pointer">
                <?php if(!$check){ ?>
                <i class="iconfont icon-qd" style="color:red;"></i>
                <p style="color:red;">签到</p>
                <?php }else{ ?>
                <i class="iconfont icon-qd"></i>
                <p>签到</p>
                <?php } ?>
            </div>
            <a href="<?php echo U('Ucenter/index/ranking');?>">
                <div class="col-xs-3 c-box">
                    <i class="iconfont icon-px"></i>
                    <p class="pc6">排行</p>
                </div>
            </a>
            <?php if($ping || $Charge): ?><a href="<?php echo ($ping?U('Pingxx/index/index'):U('Recharge/index/index')); ?>">
                    <div class="col-xs-3 c-box">
                        <i class="iconfont icon-chongzhi"></i>
                        <p class="pc6">充值</p>
                    </div>
                </a><?php endif; ?>

            <?php if($shop): ?><a href="<?php echo U('Shop/index/index');?>">
                    <div class="col-xs-3 c-box">
                        <i class="iconfont icon-jifenshangcheng"></i>
                        <p class="pc6">兑换</p>
                    </div>
                </a><?php endif; ?>
            <?php if($Tcenter): ?><a href="<?php echo U('Tcenter/task/task');?>">
                    <div class="col-xs-3 c-box">
                        <i class="iconfont icon-renwu1"></i>
                        <p class="pc6">任务</p>
                    </div>
                </a><?php endif; ?>
            <?php if($Tcenter): ?><a href="<?php echo U('Tcenter/prop/my');?>">
                    <div class="col-xs-3 c-box">
                        <i class="iconfont icon-daoju"></i>
                        <p class="pc6">道具</p>
                    </div>
                </a><?php endif; ?>
            <?php if(check_all_role_authorized('Bank')==1): ?><a href="<?php echo U('Bank/index/index');?>">
                    <div class="col-xs-3 c-box">
                        <i class="iconfont icon-yinxing"></i>
                        <p class="pc6">银行</p>
                    </div>
                </a><?php endif; ?>
        </div>
        <div class="show-more" data-role="show_more_link">
            <i class="icon-angle-down" style="font-size: 20px"></i>
        </div>
        <div class="close-more" data-role="close_more_link">
            <i class="icon-angle-up" style="font-size: 20px"></i>
        </div>
    </div><?php endif; ?>
<!--我的社群end-->
<!--签到日历stat-->
<div class="hide-check-box" style="display: none">
    <h5>
        <a href="javascript:">我的签到日历</a>
        <a href="javascript:" class="pull-right" data-role="close_checkBox">X</a>
    </h5>
    <?php echo W('Ucenter/signCalendar/render');?>
    <div class="checkin">
        <?php echo hook('checkIn');?>
    </div>
</div>
<!--签到日历end-->
<!--热门话题排行start-->
<div class="wb-topic">
    <p class="topic-head">话题排行</p>
    <div class="topic-content">
        <ul>
            <?php if(is_array($hot_topic_list)): $one_topic_key = 0; $__LIST__ = $hot_topic_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$one_topic): $mod = ($one_topic_key % 2 );++$one_topic_key; if($one_topic_key<=3){ $one_topic_class='num-top'; }else{ $one_topic_class=''; } ?>
                <li><a href="<?php echo U('Topic/index',array('topk'=>$one_topic['id']));?>" title="<?php echo ($one_topic['name']); ?>">
                    <div class="num <?php echo ($one_topic_class); ?>"><?php echo ($one_topic_key); ?></div>
                    <div><?php echo ($one_topic['name']); ?></div>
                    <div><?php echo ($one_topic['weibo_num']); ?></div>
                </a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
    </div>
</div>
<!--热门话题排行end--><?php endif; ?>
    <?php echo W('Common/Adv/render',array(array('name'=>'below_self_info','type'=>1,'width'=>'280px','height'=>'100px','margin'=>'0
    0 10px 0','title'=>'个人资料下方')));?>
    <div style="position: relative">
        <?php echo hook('weiboSide');?>
        <!--广告位-->
        <?php echo W('Common/Adv/render',array(array('name'=>'below_checkrank','type'=>1,'width'=>'280px','height'=>'100px','title'=>'签到下方广告')));?>
        <!--广告位end-->
    </div>
</div>
    <!--首页右侧部分结束-->

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


    <script src="/yoyo/Application/Weibo/Static/js/weibo.js"></script>
    <script src="/yoyo/Public/js/ext/touchslide/js/jquery.touchSlider.js"></script>
    <link href="/yoyo/Public/js/ext/touchslide/css/touchslider.css" rel="stylesheet" type="text/css"/>
    <script>
        var tag_id="<?php echo ($smallnav); ?>";
        $('.list-type').removeClass('select');
        $('#'+tag_id).parents('li').addClass('select');

        var SUPPORT_URL = "<?php echo addons_url('Support://Support/doSupport');?>";
        weibo.page = '<?php echo ($page); ?>';
        weibo.loadCount = 0;
        weibo.lastId = parseInt('<?php echo (reset($list)); ?>') + 1;
        weibo.type = "<?php echo ($type); ?>";
        weibo.crowd = "<?php echo ($crowd_type); ?>";
        weibo.url = "<?php echo ($loadMoreUrl); ?>";
        $(function () {
            weibo_bind();
            //当屏幕滚动到底部时
            if (weibo.page == 1) {
                $(window).on('scroll', function () {
                    if (weibo.noMoreNextPage) {
                        return;
                    }
                    if (weibo.isLoadingWeibo) {
                        return;
                    }
                    if (weibo.isLoadMoreVisible()) {
                        weibo.loadNextPage();
                    }
                });
                $(window).trigger('scroll');
            }
        });
        $(document).ready(function () {
            $('#weibo_filter li:last-child .a-wrap-a').css('border','none');
            $('[data-role="switch_sendBox"]').click(function () {
                if (is_login()) {
                    var wb_cookie = $.cookie("wb_type");
                    $(".add-weibo").hide();
                    if (wb_cookie == 'tp_value'){
                        $("#send_box").show();
                    }
                    else if(wb_cookie == 'tp_long_weibo') {
                        $('[data-role="show-long-sendBox"]').click();
                    }else{
                        $(".black-filter").slideToggle();
                    }
                } else {
                    toast.error('请先登录！');
                }
            });
            $('[data-role="show-sendBox"]').click(function () {
                $("#send_box").show();
                $("#weibo_content").focus();
                $(".black-filter").hide();
                $.cookie('wb_type', 'tp_value',{ expires: 7 });
            });
            $('[data-role="show-long-sendBox"]').click(function () {
                if($('#send_long_box').length>0){
                    $(".black-filter").hide();
                    $('#send_long_box').show();
                }else{
                    $(".black-filter").hide();
                    $(".black-filter").after('<div class="long_weibo_post_box" id="send_long_box" style="width: 100%;min-height: 70px;background: #FFFFFF;"></div>');
                    var $tag=$('#send_long_box');
                    OS_Loading.loading($tag,'loading1');
                    $.post(U('Weibo/Index/quickLongWeibo'),null,function (res) {
                        OS_Loading.remove($tag);
                        $tag.append(res);
                        $.cookie("wb_type", "tp_long_weibo",{ expires: 7 });
                    })
                }
            });
            $('[data-role="open_checkBox"]').click(function () {
                $(".hide-check-box").fadeToggle("slow");
            });
            $('[data-role="close_checkBox"]').click(function () {
                $(".hide-check-box").fadeToggle("slow");
            });
            $('[data-role="del_crowd"]').click(function () {
                var text = $('[data-role="confirm_del_crowd"]').val();
                if(text == '立即解散'){
                    var obj = $(this);
                    var crowd_id = obj.attr('data-crowd-id');
                    $.post(U('Weibo/Manage/delCrowd'),{crowd_id:crowd_id},function(res){
                        handleAjax(res);
                    })
                } else {
                    toast.error('输入错误');
                }
            });
            var divNum = $(".c-box").size();
            if(divNum>4){
                $('.show-more').css('display','block');
            }
            $('[data-role="show_more_link"]').click(function () {
                $('.gg-check').addClass('c-class');
                $('.show-more').hide();
                $('.close-more').show()
            });
            $('[data-role="close_more_link"]').click(function () {
                $('.gg-check').removeClass('c-class');
                $('.show-more').show();
                $('.close-more').hide()
            });
            $('[data-role="crowd_rank"]').children().eq(0).css('display','none');
            $('[data-role="crowd_rank"]').children().eq(1).css('display','');
            $('[data-role="crowd_rank"]').mouseover(function(){
                var $this = $(this);
                $this.children().eq(0).css('display','none');
                $this.children().eq(1).css('display','');
            });
            $('[data-role="crowd_rank"]').mouseout(function(){
                $('[data-role="crowd_rank"]').each(function(){
                    var $this = $(this);
                    $this.children().eq(0).css('display','');
                    $this.children().eq(1).css('display','none');
                })
            });
            $('[data-role="sync_sina_weibo"]').click(function(){
                var $sync = $("#sync_sina_weibo");
                var url = $('[data-role="sync_sina_weibo"]').attr('data-url');
                var flag = $sync.val();
                var $this = $(this);
                if (flag == 0) {
                    $.post(U('Weibo/Index/checkBindSinaWeibo'),'',function(res){
                        if (res.status == 1) {
                            $this.children().css('color','red');
                            $sync.val(1);
                        } else {
//                            window.location.href = url;
                            toast.error('未绑定新浪微博');
                        }
                    });
                } else {
                    $this.children().css('color','');
                    $sync.val(0);
                }
            })
        });
    </script>
    <link rel="stylesheet" href="/yoyo/Application/Weibo/Static/css/photoswipe.css">
    <link rel="stylesheet" href="/yoyo/Application/Weibo/Static/css/default-skin/default-skin.css">
    <link rel="stylesheet" href="/yoyo/Application/Weibo/Static/css/circle.css">
    <script src="/yoyo/Application/Weibo/Static/js/photoswipe.min.js"></script>
    <script src="/yoyo/Application/Weibo/Static/js/photoswipe-ui-default.min.js"></script>

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