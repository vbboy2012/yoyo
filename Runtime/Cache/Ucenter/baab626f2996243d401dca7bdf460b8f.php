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
<link type="text/css" rel="stylesheet" href="//at.alicdn.com/t/font_m3e1llc4usypsyvi.css"/>
<!--<script src="/yoyo/Public/js/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="/yoyo/Public/js/com/com.functions.js"></script>

<script type="text/javascript" src="/yoyo/Public/js/core.js"></script>-->
<script src="/yoyo/Public/js.php?f=js/jquery-2.0.3.min.js,js/com/com.functions.js,static/os-loading/loading.js,js/core.js,js/com/com.toast.class.js,js/com/com.ucard.js"></script>




    <link href="/yoyo/Application/Ucenter/Static/css/attest.css" rel="stylesheet" type="text/css"/>

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
<link rel="stylesheet" href="http://at.alicdn.com/t/font_iwj71cmtw1dobt9.css">
<script>
    $(document).ready(function () {
        $('[data-role="show_hide"]').click(function () {
            $("#search_box").slideToggle("slow");
        });
        $('[data-role="close"]').click(function () {
            $("#search_box").slideToggle("slow");
        });
    });

</script>
<div class="container-fluid topp-box clearfloat">
    <div class="col-xs-2 box">
        <div class="img-wrap">
            <?php $logo = get_cover(modC('LOGO',0,'Config'),'path'); $logo = $logo?$logo:'/yoyo/Public/images/logo.png'; ?>
            <a class="navbar-brand logo" href="<?php echo U('Home/Index/index');?>"><img src="<?php echo ($logo); ?>"/></a>
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
                <!--<input type="text" class="search" placeholder="搜索">-->
                <a href="javascript:" id="show_box" data-role="show_hide">
                    <!--<i class="iconfont icon-ss"></i>-->
                    <img src="/yoyo/Public/images/search.png">
                </a>
            </li>
            <li class="li-hover">
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
                            <a href="<?php echo ($self["space_url"]); ?>">
                                <div class="col-xs-6 l-p0">
                                    <i class="os-icon-user"></i>
                                    个人主页
                                </div>
                            </a>
                            <a href="<?php echo U('Ucenter/index/ranking');?>">
                                <div class="col-xs-6 r-p0">
                                    <i class="os-icon-bar-chart"></i>
                                    排行榜
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
                <div class=" form-group">
                    <input type="text" class="search " placeholder="搜索">
                    <button type="submit" class="search-btn " data-role="search">
                        <img src="/yoyo/Public/images/search.png">
                    </button>
                </div>
                <div class=" a-div">
                    <a class="top-btn" data-login="do_login"><?php echo L('_LOGIN_');?></a>
                    <a class="top-btn" data-role="do_register" data-url="<?php echo U('Ucenter/Member/register');?>"><?php echo L('_REGISTER_');?></a>
                </div>
            </div><?php endif; ?>
    </div>
    <div class="container-fluid search-box" id="search_box" style="display: none">
        <canvas width="1835" height="374"></canvas>
        <div class="text-wrap">
            <div class="container text-box" style="margin: 0 auto!important;">
                <h1>无处不在,搜你所想</h1>
                <form class="navbar-form " action="<?php echo U('Home/Index/search');?>" method="post"
                      role="search" id="search">
                    <div class="search">
                        <span class="pull-left"><input type="text" name="keywords" class="input" placeholder="全站搜索"></span>
                        <a data-role="search"><i class="icon icon-search pull-right"></i></a>
                    </div>

                    </span>
                </form>

            </div>
            <div class="close-box" data-role="close">X</div>
        </div>
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
    $(function() {
        $('[data-role="search"]').click(function() {
            $("#search").submit();
        })
    })

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
            
    <div class="v-wrap id-wrap apply-wrap">
        <div class="id-head">
            <p>申请<?php echo ($attest_type['title']); ?></p>
        </div>
        <div class="flow-path row">
            <div class="col-xs-8 left-box">
                <form id="attest-form" method="post">
                    <input type="hidden" name="id" value="<?php echo ($attest['id']); ?>">
                    <input type="hidden" name="change" value="<?php echo ($change); ?>">
                    <input type="hidden" name="attest_type_id" value="<?php echo ($attest_type['id']); ?>"/>
                    <table>
                        <?php if(($attest_type["fields"]["child_type"]) != "0"): ?><tr class="apply-form">
                                <td class="title-wrap"><label>认证分类：</label></td>
                                <td class="f-wrap">
                                    <select id="child_type" name="child_type" class="form-control">
                                        <?php if(is_array($attest_type["fields"]["child_type_option"])): $i = 0; $__LIST__ = $attest_type["fields"]["child_type_option"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child_option): $mod = ($i % 2 );++$i;?><option value="<?php echo ($child_option); ?>"><?php echo ($child_option); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </td>
                                <?php if(!empty($attest["child_type"])): ?><script>
                                        $('#child_type').val("<?php echo ($attest['child_type']); ?>");
                                    </script><?php endif; ?>
                                <td class="tip-txt"></td>
                            </tr><?php endif; ?>
                        <?php if(($attest_type["fields"]["company_name"]) != "0"): ?><tr class="apply-form">
                                <td class="title-wrap">
                                    <label>
                                        <?php if(($attest_type["fields"]["company_name"]) == "1"): ?><span>*</span><?php endif; ?>
                                        <?php if(($attest_type["name"]) == "company"): ?>企业<?php else: ?>组织机构<?php endif; ?>名称：
                                    </label>
                                </td>
                                <td class="f-wrap">
                                    <input type="text" name="company_name" value="<?php echo ($attest['company_name']); ?>" class="form_check" check-length="2,100" placeholder="名称">
                                </td>
                                <td class="tip-txt"></td>
                            </tr><?php endif; ?>
                        <?php if(($attest_type["fields"]["name"]) != "0"): ?><tr class="apply-form">
                                <td class="title-wrap">
                                    <label>
                                        <?php if(($attest_type["fields"]["name"]) == "1"): ?><span>*</span><?php endif; ?>
                                        真实姓名：
                                    </label>
                                </td>
                                <td class="f-wrap">
                                    <input type="text" name="name" value="<?php echo ($attest['name']); ?>" class="form_check" check-type="Chinese" placeholder="姓名">
                                </td>
                                <td class="tip-txt"></td>
                            </tr><?php endif; ?>
                        <?php if(($attest_type["fields"]["id_num"]) != "0"): ?><tr class="apply-form">
                                <td class="title-wrap">
                                    <label>
                                        <?php if(($attest_type["fields"]["id_num"]) == "1"): ?><span>*</span><?php endif; ?>
                                        身份证号码：
                                    </label>
                                </td>
                                <td class="f-wrap">
                                    <input type="text" name="id_num" value="<?php echo ($attest['id_num']); ?>" class="form_check" check-type="IDCard" placeholder="身份证号码">
                                </td>
                                <td class="tip-txt"></td>
                            </tr><?php endif; ?>
                        <?php if(($attest_type["fields"]["phone"]) != "0"): ?><tr class="apply-form">
                                <td class="title-wrap">
                                    <label>
                                        <?php if(($attest_type["fields"]["phone"]) == "1"): ?><span>*</span><?php endif; ?>
                                        联系方式：
                                    </label>
                                </td>
                                <td class="f-wrap">
                                    <input type="text" name="phone" value="<?php echo ($attest['phone']); ?>" class="form_check" check-type="PhoneOrTelephone" placeholder="手机或带区号的固话">
                                </td>
                                <td class="tip-txt"></td>
                            </tr><?php endif; ?>
                        <?php if(($attest_type["fields"]["image_type"]) != "0"): ?><tr class="apply-form">
                                <td class="title-wrap"><label>证件类型：</label></td>
                                <td class="f-wrap">
                                    <select id="image_type" name="image_type" class="form-control">
                                        <?php if(is_array($attest_type["fields"]["image_type_option"])): $i = 0; $__LIST__ = $attest_type["fields"]["image_type_option"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$image_option): $mod = ($i % 2 );++$i;?><option value="<?php echo ($image_option); ?>"><?php echo ($image_option); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </td>
                                <?php if(!empty($attest["image_type"])): ?><script>
                                        $('#image_type').val("<?php echo ($attest['image_type']); ?>");
                                    </script><?php endif; ?>
                                <td class="tip-txt"></td>
                            </tr><?php endif; ?>
                        <?php if(($attest_type["fields"]["prove_image"]) != "0"): ?><tr class="apply-form">
                                <td class="title-wrap">
                                    <label>
                                        <?php if(($attest_type["fields"]["prove_image"]) == "1"): ?><span>*</span><?php endif; ?>
                                        <?php if(($attest_type["name"]) == "company"): ?>企业<?php else: ?>组织机构<?php endif; ?>证件：
                                    </label>
                                </td>
                                <td class="f-wrap" style="width: auto;">
                                    <span id="web_uploader_wrapper_gallary_prove_image">上传</span>

                                    <input id="web_uploader_input_gallary_prove_image" type="hidden" value=""  event-node="uploadinput">

                                    <div id="web_uploader_picture_list_gallary_prove_image" class="web_uploader_picture_list">
                                        <?php if(is_array($attest["prove_image"])): $i = 0; $__LIST__ = $attest["prove_image"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$p): $mod = ($i % 2 );++$i;?><img class="gallary_thumb" onclick="remove_file(this,'prove_image')" src="<?php echo (get_cover($p,'path')); ?>">
                                            <input type="hidden" name="prove_image[]" value="<?php echo ($p); ?>"/><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </div>
                                    <span class="help-block">*点击小图删除，删除后不能再上传</span>
                                </td>
                                <td class="tip-txt"></td>
                            </tr><?php endif; ?>
                        <?php if(($attest_type["fields"]["image"]) != "0"): ?><tr class="apply-form">
                                <td class="title-wrap">
                                    <label>
                                        <?php if(($attest_type["fields"]["image"]) == "1"): ?><span>*</span><?php endif; ?>
                                        证件正反面照：
                                    </label>
                                </td>
                                <td class="f-wrap" style="width: auto;">
                                    <span id="web_uploader_wrapper_gallary_image">上传</span>

                                    <input id="web_uploader_input_gallary_image" type="hidden" value=""  event-node="uploadinput">

                                    <div id="web_uploader_picture_list_gallary_image" class="web_uploader_picture_list">
                                        <?php if(is_array($attest["image"])): $i = 0; $__LIST__ = $attest["image"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$i): $mod = ($i % 2 );++$i;?><img class="gallary_thumb" onclick="remove_file(this,'image')" src="<?php echo (get_cover($i,'path')); ?>">
                                            <input type="hidden" name="image[]" value="<?php echo ($i); ?>"/><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </div>
                                    <span class="help-block">*点击小图删除，删除后不能再上传</span>
                                </td>
                                <td class="tip-txt"></td>
                            </tr><?php endif; ?>
                        <?php if(($attest_type["fields"]["other_image"]) != "0"): ?><tr class="apply-form">
                                <td class="title-wrap">
                                    <label>
                                        <?php if(($attest_type["fields"]["other_image"]) == "1"): ?><span>*</span><?php endif; ?>
                                        其他证明材料：
                                    </label>
                                </td>
                                <td class="f-wrap" style="width: auto;">
                                    <span id="web_uploader_wrapper_gallary_other_image">上传</span>

                                    <input id="web_uploader_input_gallary_other_image" type="hidden" value=""  event-node="uploadinput">

                                    <div id="web_uploader_picture_list_gallary_other_image" class="web_uploader_picture_list">
                                        <?php if(is_array($attest["other_image"])): $i = 0; $__LIST__ = $attest["other_image"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$o): $mod = ($i % 2 );++$i;?><img class="gallary_thumb" onclick="remove_file(this,'other_image')" src="<?php echo (get_cover($o,'path')); ?>">
                                            <input type="hidden" name="other_image[]" value="<?php echo ($o); ?>"/><?php endforeach; endif; else: echo "" ;endif; ?>
                                    </div>
                                    <span class="help-block"><?php echo ($attest_type["fields"]["other_image_tip"]); ?></span>
                                </td>
                                <td class="tip-txt"></td>
                            </tr><?php endif; ?>

                        <?php if(($attest_type["fields"]["info"]) != "0"): ?><tr class="apply-form">
                                <td class="title-wrap">
                                    <label>
                                        <?php if(($attest_type["fields"]["info"]) == "1"): ?><span>*</span><?php endif; ?>
                                        认证补充：
                                    </label>
                                </td>
                                <td class="f-wrap">
                                    <textarea type="text" name="info" <?php if(($attest_type["fields"]["info"]) == "1"): ?>class="form_check"<?php endif; ?>><?php echo ($attest['info']); ?></textarea>
                                </td>
                                <td class="tip-txt"></td>
                            </tr><?php endif; ?>
                        <tr class="text-center">
                            <td><a href="javascript:void(0);" data-role="submit" class="app-btn">提交认证</a></td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="col-xs-4 right-box">
                <div class="r-head">认证说明：</div>
                <?php echo ($attest_type['description']); ?>
            </div>
        </div>
    </div>

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


    <link href="/yoyo/Application/Core/Static/css/form_check.css" rel="stylesheet" type="text/css">
    <script src='/yoyo/Application/Core/Static/js/form_check.js'></script>

    <script type="text/javascript" charset="utf-8" src="/yoyo/Public/static/ueditor/third-party/webuploader/webuploader.js"></script>
    <link href="/yoyo/Public/static/ueditor/third-party/webuploader/webuploader.css" type="text/css" rel="stylesheet">
    <script type="text/javascript" src="/yoyo/Public/static/uploadify/jquery.uploadify.min.js"></script>
    <style>
        .webuploader-pick{
            padding: 5px 15px;
        }
        .gallary_thumb {
            border: 1px solid #ddd;
            padding: 2px;
            margin-right: 10px;
            margin-bottom: 10px;
            width: 150px;
            height: 150px;
        }
    </style>
    <script>
        $(function () {
            $('[data-role="submit"]').click(function () {
                var $tag=$(this);
                if($tag.attr('disabled')=='disabled'){
                    return false;
                }
                $tag.attr('disabled','disabled');
                var param=$('#attest-form').serialize();
                var url=U('Ucenter/Attest/apply');
                $.post(url,param,function (msg) {
                    $tag.removeAttr('disabled');
                    handleAjax(msg);
                })
            })
        })


        var gallary_num_prove_image="<?php echo count($info['prove_image']) ?>";
        var gallary_num_image="<?php echo count($info['image']) ?>";
        var gallary_num_other_image = "<?php echo count($info['other_image']) ?>";
        $(function () {
            //prove_image start
            var id_prove_image = "#web_uploader_wrapper_gallary_prove_image";
            if($(id_prove_image).length>0){
                var uploader_gallary_prove_image = WebUploader.create({
                    // swf文件路径
                    swf: 'Uploader.swf',
                    // 文件接收服务端。
                    server: "<?php echo U('Core/File/uploadPicture',array('session_id'=>session_id()));?>",
                    fileNumLimit: 9,
                    // 选择文件的按钮。可选。
                    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                    pick: {'id': id_prove_image, 'multi': true}
                });
                uploader_gallary_prove_image.on('beforeFileQueued', function (file) {
                    if (gallary_num_prove_image >= 8) {
                        toast.error('图片不能超过9张');
                        return false;
                    }
                });
                uploader_gallary_prove_image.on('fileQueued', function (file) {
                    gallary_num_prove_image = parseInt(gallary_num_prove_image) + 1;

                    uploader_gallary_prove_image.upload();
                    $("#web_uploader_file_name_gallary_prove_image").text('正在上传...');
                });

                /*上传成功*/
                uploader_gallary_prove_image.on('uploadSuccess', function (file, ret) {
                    if (ret.status == 0) {
                        $("#web_uploader_file_name_gallary_prove_image").text(ret.info);
                    } else {
                        $('#web_uploader_input_gallary_prove_image').focus();
                        $('#web_uploader_input_gallary_prove_image').val(ret.data.file.id);
                        $('#web_uploader_input_gallary_prove_image').blur();
                        $("#web_uploader_picture_list_gallary_prove_image").append('<img class="gallary_thumb" onclick="remove_file(this,'+"'prove_image'"+')" src="' + ret.data.file.path + '"/><input type="hidden" name="prove_image[]" value="' + ret.data.file.id + '"/>');
                    }
                });
                //prove_image end
            }


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
                        $('#web_uploader_input_gallary_image').focus();
                        $('#web_uploader_input_gallary_image').val(ret.data.file.id);
                        $('#web_uploader_input_gallary_image').blur();
                        $("#web_uploader_picture_list_gallary_image").append('<img class="gallary_thumb" onclick="remove_file(this,' + "'image'" + ')" src="' + ret.data.file.path + '"/><input type="hidden" name="image[]" value="' + ret.data.file.id + '"/>');
                    }
                });
            }
            //image end


            // other_image  start
            var id_other_image = "#web_uploader_wrapper_gallary_other_image";
            if($(id_other_image).length>0) {
                var uploader_gallary_other_image = WebUploader.create({
                    // swf文件路径
                    swf: 'Uploader.swf',
                    // 文件接收服务端。
                    server: "<?php echo U('Core/File/uploadPicture',array('session_id'=>session_id()));?>",
                    fileNumLimit: 9,
                    // 选择文件的按钮。可选。
                    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                    pick: {'id': id_other_image, 'multi': true}
                });
                uploader_gallary_other_image.on('beforeFileQueued', function (file) {
                    if (gallary_num_other_image >= 9) {
                        toast.error('图片不能超过9张');
                        return false;
                    }
                });
                uploader_gallary_other_image.on('fileQueued', function (file) {
                    gallary_num_other_image = parseInt(gallary_num_other_image) + 1;

                    uploader_gallary_other_image.upload();
                    $("#web_uploader_file_name_gallary_other_image").text('正在上传...');
                });

                /*上传成功*/
                uploader_gallary_other_image.on('uploadSuccess', function (file, ret) {
                    if (ret.status == 0) {
                        $("#web_uploader_file_name_gallary_other_image").text(ret.info);
                    } else {
                        $('#web_uploader_input_gallary_other_image').focus();
                        $('#web_uploader_input_gallary_other_image').val(ret.data.file.id);
                        $('#web_uploader_input_gallary_other_image').blur();
                        $("#web_uploader_picture_list_gallary_other_image").append('<img class="gallary_thumb" onclick="remove_file(this,' + "'other_image'" + ')" src="' + ret.data.file.path + '"/><input type="hidden" name="other_image[]" value="' + ret.data.file.id + '"/>');
                    }
                });
            }
            //other_image end
        })
        function remove_file(obj,str) {
            $(obj).next().remove();
            $(obj).remove();
            switch (str){
                case 'prove_image':
                    gallary_num_prove_image = gallary_num_prove_image - 1;
                    break;
                case 'image':
                    gallary_num_image = gallary_num_image - 1;
                    break;
                case 'other_image':
                    gallary_num_other_image = gallary_num_other_image - 1;
                    break;
                default:;
            }
        }
    </script>

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