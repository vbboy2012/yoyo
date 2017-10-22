ALTER TABLE  `ocenter_forum_post` ADD  `hide` int(11) NOT NULL COMMENT '是否回复可见';
ALTER TABLE  `ocenter_forum_post` ADD  `file_id` int(11) NOT NULL COMMENT '文件id';
ALTER TABLE  `ocenter_forum_post` ADD  `pay_on` int(11) NOT NULL COMMENT '是否付费下载';
ALTER TABLE  `ocenter_forum_post` ADD  `pay_type` int(11) NOT NULL COMMENT '付费类型';
ALTER TABLE  `ocenter_forum_post` ADD  `pay_num` int(11) NOT NULL COMMENT '付费数量';


CREATE TABLE IF NOT EXISTS `ocenter_forum_pay` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `forum_post_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;