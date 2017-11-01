--
-- 表的结构 `ocenter_stamp`
--

CREATE TABLE IF NOT EXISTS `ocenter_stamp` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '图章ID',
  `title` varchar(32) NOT NULL COMMENT '图章名称',
  `pic` int(11) NOT NULL COMMENT '图章图片',
  `type` varchar(32) NOT NULL COMMENT '图章类型 是否为系统自带',
  `create_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='图章表' AUTO_INCREMENT=13 ;

--
-- 转存表中的数据 `ocenter_stamp`
--

INSERT INTO `ocenter_stamp` (`id`, `title`, `pic`, `type`, `create_time`, `status`) VALUES
(1, '爆料', 1, 'system', 1488855181, 1),
(2, '灌水', 2, 'system', 1488855201, 1),
(3, '精华', 3, 'system', 1488855213, 1),
(4, '美图', 4, 'system', 1488855213, 1),
(5, '通过', 5, 'system', 1488855213, 1),
(6, '圈主推荐', 6, 'system', 1488855213, 1),
(7, '推荐', 7, 'system', 1488855213, 1),
(8, '优秀', 8, 'system', 1488855213, 1),
(9, '解决', 9, 'system', 1488855213, 1),
(10, '采纳', 10, 'system', 1488855213, 1),
(11, '已阅', 11, 'system', 1488855213, 1),
(12, '原创', 12, 'system', 1488855213, 1);

--
-- 表的结构 `ocenter_stamp_weibo`
--

CREATE TABLE IF NOT EXISTS `ocenter_stamp_weibo` (
  `weibo_id` int(11) NOT NULL COMMENT '微博ID',
  `stamp_id` int(11) NOT NULL COMMENT '图章ID',
  `create_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`weibo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;