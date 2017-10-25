<?php if (!defined('THINK_PATH')) exit();?><div class="long-weibo-list-content">
    <a class="article-link" href="<?php echo U('Weibo/Index/weiboDetail',array('id'=>$weibo['id']));?>">
        <div class="long-block">
            <h1 style="width: 100%;" class="text-more"><?php echo ($weibo["long_weibo"]["title"]); ?></h1>
            <p class="word-wrap">
                <?php echo ($weibo["content"]); ?>
            </p>
            <div class="position-block"><div class="read-long-weibo-logo"><i class="logo"></i></div>阅读全文</div>
        </div>
    </a>
</div>