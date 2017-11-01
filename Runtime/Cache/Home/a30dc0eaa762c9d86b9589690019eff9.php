<?php if (!defined('THINK_PATH')) exit();?><div class="qtSync">
   <div class="qtContent">
       <a href="<?php echo U('Question/Index/detail',array('id'=>$weibo['question']['id']));?>">
            <?php echo ($weibo["question"]["title"]); ?>
       </a>
       <p><span><?php echo (friendlydate($weibo["question"]["create_time"])); ?></span></p>
   </div>
   <a href="<?php echo U('Question/Index/detail',array('id'=>$weibo['question']['id']));?>" class="qtAnswer">回答</a>
</div>