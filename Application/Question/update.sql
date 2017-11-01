--
-- 表的结构 `ocenter_question_rank`
--

CREATE TABLE IF NOT EXISTS `ocenter_question_rank` (
  `uid` int(11) NOT NULL,
  `support_count` int(11) NOT NULL COMMENT '回答总被点赞数',
  `answer_count` int(11) NOT NULL COMMENT '回答数',
  `best_answer_count` int(11) NOT NULL COMMENT '总最佳回答数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='问答达人表';

ALTER TABLE  `ocenter_question` ADD  `topic_id` VARCHAR( 200 ) NOT NULL AFTER  `category` ,
ADD INDEX (  `topic_id` );

--
-- 表的结构 `ocenter_question_topic`
--

CREATE TABLE IF NOT EXISTS `ocenter_question_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL COMMENT '话题名称',
  `logo` int(11) NOT NULL COMMENT '话题图标',
  `num` int(11) NOT NULL COMMENT '话题问答数',
  `create_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE  `ocenter_question_answer` ADD  `reply_id` INT( 11 ) NOT NULL AFTER  `question_id`;

--
-- 表的结构 `ocenter_question_reward_record`
--

CREATE TABLE IF NOT EXISTS `ocenter_question_reward_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `to_uid` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '打赏类型',
  `num` decimal(10,2) NOT NULL DEFAULT '0.00',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='问答打赏记录表' AUTO_INCREMENT=1 ;

ALTER TABLE  `ocenter_question_answer` ADD  `topic_id` varchar(200) NOT NULL AFTER  `reply_id`;



CREATE TABLE IF NOT EXISTS `ocenter_question_search` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `historical` varchar(50) NOT NULL COMMENT '历史记录',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='问答';

ALTER TABLE  `ocenter_question` ADD  `img_id` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE  `ocenter_question` CHANGE  `leixing`  `leixing` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT  '类型';