<?php if (!defined('THINK_PATH')) exit();?><li>
    <a <?php if($data['web_url'] != ''): ?>href="<?php echo ($data['web_url']); ?>"<?php endif; ?> title="">
        <div class="avatar-box">
            <img src="<?php echo ($data['user']['avatar64']); ?>" alt="">
        </div>
        <div class="info-box">
            <div class="name"><?php echo ($data['user']['nickname']); ?><span><?php echo (friendlydate($data["create_time"])); ?></span></div>
            <div class="info"><?php echo ($data["title"]); ?>-<?php echo ($data["content"]); ?></div>
        </div>
    </a>
</li>