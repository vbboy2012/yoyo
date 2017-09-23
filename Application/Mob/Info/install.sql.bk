-- -----------------------------
-- 表结构 `ocenter_mob_channel`
-- -----------------------------
CREATE TABLE IF NOT EXISTS `ocenter_mob_channel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '频道ID',
  `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级频道ID',
  `title` char(30) NOT NULL COMMENT '频道标题',
  `url` char(100) NOT NULL COMMENT '频道连接',
  `sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '导航排序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `target` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '新窗口打开',
  `color` varchar(30) NOT NULL,
  `band_color` varchar(30) NOT NULL,
  `band_text` varchar(30) NOT NULL,
  `icon` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- -----------------------------
-- 表内记录 `ocenter_mob_channel`
-- -----------------------------
INSERT INTO `ocenter_mob_channel` VALUES ('1', '0', '活动', 'mob/event/index', '0', '0', '0', '1', '0', '', '', '', 'am-icon-');
INSERT INTO `ocenter_mob_channel` VALUES ('2', '0', '论坛', 'mob/forum/index', '0', '0', '0', '1', '0', '', '', '', 'am-icon-');
INSERT INTO `ocenter_mob_channel` VALUES ('3', '0', '群组', 'mob/group/index', '0', '0', '0', '1', '0', '', '', '', 'am-icon-');
INSERT INTO `ocenter_mob_channel` VALUES ('4', '0', '专辑', 'mob/issue/index', '0', '0', '0', '1', '0', '', '', '', 'am-icon-');
INSERT INTO `ocenter_mob_channel` VALUES ('5', '0', '资讯', 'mob/news/index', '0', '0', '0', '1', '0', '', '', '', 'am-icon-');
INSERT INTO `ocenter_mob_channel` VALUES ('6', '0', '找人', 'mob/people/index', '0', '0', '0', '1', '0', '', '', '', 'am-icon-');
INSERT INTO `ocenter_mob_channel` VALUES ('7', '0', '问答', 'mob/question/index', '0', '0', '0', '1', '0', '', '', '', 'am-icon-');
INSERT INTO `ocenter_mob_channel` VALUES ('8', '0', '积分商城', 'mob/shop/index', '0', '0', '0', '1', '0', '', '', '', 'am-icon-');
INSERT INTO `ocenter_mob_channel` VALUES ('9', '0', '微店', 'mob/store/index', '0', '0', '0', '1', '0', '', '', '', 'am-icon-');
INSERT INTO `ocenter_mob_channel` VALUES ('10', '0', '用户中心', 'mob/user/index', '0', '0', '0', '1', '0', '', '', '', 'am-icon-');
INSERT INTO `ocenter_mob_channel` VALUES ('11', '0', '动态Pro', 'mob/weibo/index', '0', '0', '0', '1', '0', '', '', '', 'am-icon-');
