-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2017-10-22 02:11:02
-- 服务器版本： 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yoyobit`
--

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_action`
--

CREATE TABLE `ocenter_action` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '主键',
  `name` char(30) NOT NULL DEFAULT '' COMMENT '行为唯一标识',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '行为说明',
  `remark` char(140) NOT NULL DEFAULT '' COMMENT '行为描述',
  `rule` text NOT NULL COMMENT '行为规则',
  `log` text NOT NULL COMMENT '日志规则',
  `type` tinyint(2) UNSIGNED NOT NULL DEFAULT '1' COMMENT '类型',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '修改时间',
  `module` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统行为表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `ocenter_action`
--

INSERT INTO `ocenter_action` (`id`, `name`, `title`, `remark`, `rule`, `log`, `type`, `status`, `update_time`, `module`) VALUES
(1, 'reg', '用户注册', '用户注册', '', '', 1, 1, 1426070545, ''),
(2, 'input_password', '输入密码', '记录输入密码的次数。', '', '', 1, 1, 1426122119, ''),
(3, 'user_login', '用户登录', '积分+10，每天一次', 'a:1:{i:0;a:5:{s:5:\"table\";s:6:\"member\";s:5:\"field\";s:1:\"1\";s:4:\"rule\";s:2:\"10\";s:5:\"cycle\";s:2:\"24\";s:3:\"max\";s:1:\"1\";}}', '[user|get_nickname]在[time|time_format]登录了账号', 1, 1, 1428397656, ''),
(4, 'update_config', '更新配置', '新增或修改或删除配置', '', '', 1, 1, 1383294988, ''),
(7, 'update_channel', '更新导航', '新增或修改或删除导航', '', '', 1, 1, 1383296301, ''),
(8, 'update_menu', '更新菜单', '新增或修改或删除菜单', '', '', 1, 1, 1383296392, ''),
(10001, 'add_weibo', '发布微博', '新增微博', 'a:1:{i:0;a:5:{s:5:\"table\";s:6:\"member\";s:5:\"field\";s:1:\"1\";s:4:\"rule\";s:2:\"+1\";s:5:\"cycle\";s:2:\"24\";s:3:\"max\";s:2:\"10\";}}', '[user|get_nickname]在[time|time_format]发布了新微博：[record|intval]', 1, 1, 1437116716, 'Weibo'),
(10002, 'add_weibo_comment', '添加微博评论', '添加微博评论', 'a:1:{i:0;a:5:{s:5:\"table\";s:6:\"member\";s:5:\"field\";s:1:\"1\";s:4:\"rule\";s:2:\"+1\";s:5:\"cycle\";s:2:\"24\";s:3:\"max\";s:2:\"10\";}}', '[user|get_nickname]在[time|time_format]添加了微博评论：[record|intval]', 1, 1, 1437116734, 'Weibo'),
(10003, 'del_weibo_comment', '删除微博评论', '删除微博评论', '', '[user|get_nickname]在[time|time_format]删除了微博评论：[record|intval]', 1, 1, 1428399164, 'Weibo'),
(10004, 'del_weibo', '删除微博', '删除微博', '', '[user|get_nickname]在[time|time_format]删除了微博：[record|intval]', 1, 1, 1428461334, 'Weibo'),
(10005, 'set_weibo_top', '置顶微博', '置顶微博', '', '[user|get_nickname]在[time|time_format]置顶了微博：[record|intval]', 1, 1, 1428399164, 'Weibo'),
(10006, 'set_weibo_down', '取消置顶微博', '取消置顶微博', '', '[user|get_nickname]在[time|time_format]取消置顶了微博：[record|intval]', 1, 1, 1428462983, 'Weibo'),
(11004, 'news_post_reply', '资讯回复', '资讯回复', '', '', 2, 1, 1428479582, 'News'),
(11003, 'add_news', '资讯投稿', '用户发布资讯', 'N;', '', 2, 1, 1428479582, 'News');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_action_limit`
--

CREATE TABLE `ocenter_action_limit` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `frequency` int(11) NOT NULL,
  `time_number` int(11) NOT NULL,
  `time_unit` varchar(50) NOT NULL,
  `punish` text NOT NULL,
  `if_message` tinyint(4) NOT NULL,
  `message_content` text NOT NULL,
  `action_list` text NOT NULL,
  `status` tinyint(4) NOT NULL,
  `create_time` int(11) NOT NULL,
  `module` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_action_limit`
--

INSERT INTO `ocenter_action_limit` (`id`, `title`, `name`, `frequency`, `time_number`, `time_unit`, `punish`, `if_message`, `message_content`, `action_list`, `status`, `create_time`, `module`) VALUES
(1, 'reg', '注册限制', 1, 1, 'minute', 'warning', 0, '', '[reg]', 1, 0, ''),
(2, 'input_password', '输密码', 3, 1, 'minute', 'warning', 0, '', '[input_password]', 1, 0, ''),
(3, 'add_weibo', '新增微博', 1, 10, 'second', 'warning', 0, '', '[add_weibo]', 1, 0, 'Weibo'),
(4, 'add_weibo_comment', '添加微博评论', 1, 10, 'second', 'warning', 0, '', '[add_weibo_comment]', 1, 0, 'Weibo');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_action_log`
--

CREATE TABLE `ocenter_action_log` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键',
  `action_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '行为id',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '执行用户id',
  `action_ip` bigint(20) NOT NULL COMMENT '执行行为者ip',
  `model` varchar(50) NOT NULL DEFAULT '' COMMENT '触发行为的表',
  `record_id` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '触发行为的数据id',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '日志备注',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '执行行为的时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='行为日志表' ROW_FORMAT=FIXED;

--
-- 转存表中的数据 `ocenter_action_log`
--

INSERT INTO `ocenter_action_log` (`id`, `action_id`, `user_id`, `action_ip`, `model`, `record_id`, `remark`, `status`, `create_time`) VALUES
(1, 3, 1, 2130706433, 'member', 1, 'yoyoadmin在2017-09-22 11:04登录了账号【积分：+10分】', 1, 1506049461),
(2, 3, 1, 2130706433, 'member', 1, 'yoyoadmin在2017-09-22 14:53登录了账号', 1, 1506063235),
(3, 3, 1, 2130706433, 'member', 1, 'yoyoadmin在2017-09-24 09:42登录了账号【积分：+10分】', 1, 1506217377),
(4, 3, 1, 2130706433, 'member', 1, 'yoyoadmin在2017-09-25 08:13登录了账号', 1, 1506298402),
(5, 3, 1, 2130706433, 'member', 1, 'yoyoadmin在2017-09-26 09:24登录了账号【积分：+10分】', 1, 1506389058),
(6, 3, 1, 2130706433, 'member', 1, 'yoyoadmin在2017-09-27 09:07登录了账号', 1, 1506474432),
(7, 10001, 1, 2130706433, 'weibo', 1, 'yoyoadmin在2017-09-27 14:47发布了新微博：1【积分：+1分】', 1, 1506494855),
(8, 10005, 1, 2130706433, 'weibo', 1, 'yoyoadmin在2017-09-27 14:50置顶了微博：1', 1, 1506495032),
(9, 10006, 1, 2130706433, 'weibo', 1, 'yoyoadmin在2017-09-27 14:50取消置顶了微博：1', 1, 1506495040),
(10, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1506497016),
(11, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1506497016),
(12, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-09-27 15:23登录了账号【积分：+10分】', 1, 1506497016),
(13, 2, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1506498027),
(14, 3, 1, 2130706433, 'member', 1, 'yoyoadmin在2017-09-27 15:40登录了账号', 1, 1506498031),
(15, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-09-27 15:41登录了账号', 1, 1506498085),
(16, 10001, 100, 2130706433, 'weibo', 2, 'vbboy在2017-09-27 15:42发布了新微博：2【积分：+1分】', 1, 1506498138),
(17, 3, 1, 2130706433, 'member', 1, 'yoyoadmin在2017-09-27 15:42登录了账号', 1, 1506498174),
(18, 3, 1, 2130706433, 'member', 1, 'yoyoadmin在2017-09-28 08:58登录了账号', 1, 1506560302),
(19, 3, 1, 2130706433, 'member', 1, 'yoyoadmin在2017-09-28 09:45登录了账号', 1, 1506563109),
(20, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1506564812),
(21, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-09-28 10:13登录了账号', 1, 1506564816),
(22, 3, 1, 2130706433, 'member', 1, 'yoyoadmin在2017-09-28 11:04登录了账号', 1, 1506567860),
(23, 3, 1, 2130706433, 'member', 1, 'yoyoadmin在2017-09-28 11:19登录了账号', 1, 1506568774),
(24, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1506569303),
(25, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-09-28 11:28登录了账号', 1, 1506569309),
(26, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-09-29 08:46登录了账号', 1, 1506646015),
(27, 3, 1, 2130706433, 'member', 1, 'yoyoadmin在2017-09-29 09:10登录了账号', 1, 1506647405),
(28, 3, 1, 2130706433, 'member', 1, 'yoyoadmin在2017-09-29 09:18登录了账号', 1, 1506647929),
(29, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-09-29 09:18登录了账号', 1, 1506647938),
(30, 3, 1, 2130706433, 'member', 1, 'yoyoadmin在2017-09-29 09:31登录了账号', 1, 1506648666),
(31, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-09-29 09:31登录了账号', 1, 1506648711),
(32, 3, 1, 2130706433, 'member', 1, 'yoyoadmin在2017-09-29 12:02登录了账号', 1, 1506657723),
(33, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-09-29 15:01登录了账号', 1, 1506668491),
(34, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-09-29 15:07登录了账号', 1, 1506668833),
(35, 3, 1, 2130706433, 'member', 1, 'yoyoadmin在2017-09-30 07:56登录了账号', 1, 1506729375),
(36, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-09-30 09:31登录了账号', 1, 1506735062),
(37, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-09-30 11:02登录了账号', 1, 1506740540),
(38, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1506740738),
(39, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-09-30 11:31登录了账号', 1, 1506742273),
(40, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-09-30 11:38登录了账号', 1, 1506742714),
(41, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1506743207),
(42, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1506743436),
(43, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1506748519),
(44, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-09-30 13:17登录了账号', 1, 1506748635),
(45, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1506748720),
(46, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1506748720),
(47, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-09-30 14:38登录了账号', 1, 1506753508),
(48, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1506753648),
(49, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1506753733),
(50, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1506753733),
(51, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-09-30 15:58登录了账号', 1, 1506758280),
(52, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-09-30 16:02登录了账号', 1, 1506758565),
(53, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-09-30 16:16登录了账号', 1, 1506759394),
(54, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-09-30 16:46登录了账号', 1, 1506761212),
(55, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1506761903),
(56, 3, 111, 2130706433, 'member', 111, 'dsdfsdf在2017-09-30 16:58登录了账号【积分：+10分】', 1, 1506761904),
(57, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-09-30 17:05登录了账号【积分：+10分】', 1, 1506762306),
(58, 2, 111, 2130706433, 'ucenter_member', 111, '操作url：/yoyo/index.php?s=/ucenter/member/quicklogin.html', 1, 1506775496),
(59, 2, 111, 2130706433, 'ucenter_member', 111, '操作url：/yoyo/index.php?s=/ucenter/member/quicklogin.html', 1, 1506775499),
(60, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-09-30 20:45登录了账号', 1, 1506775507),
(61, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-09-30 20:47登录了账号', 1, 1506775654),
(62, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-09-30 20:53登录了账号', 1, 1506776017),
(63, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-01 06:05登录了账号', 1, 1506809152),
(64, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-01 06:25登录了账号', 1, 1506810302),
(65, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-01 06:29登录了账号', 1, 1506810555),
(66, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-01 06:35登录了账号', 1, 1506810949),
(67, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1506811225),
(68, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1506811267),
(69, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-01 08:32登录了账号', 1, 1506817964),
(70, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/admin/public/login.html', 1, 1506818818),
(71, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/admin/public/login.html', 1, 1506818835),
(72, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-01 10:35登录了账号', 1, 1506825301),
(73, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-01 10:36登录了账号', 1, 1506825375),
(74, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-01 10:36登录了账号', 1, 1506825392),
(75, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-01 10:37登录了账号', 1, 1506825433),
(76, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-01 10:37登录了账号', 1, 1506825474),
(77, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-04 07:50登录了账号【积分：+10分】', 1, 1507074648),
(78, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-04 08:01登录了账号', 1, 1507075319),
(79, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1507103623),
(80, 3, 112, 2130706433, 'member', 112, 'bozai2017在2017-10-04 15:53登录了账号【积分：+10分】', 1, 1507103624),
(81, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1507105096),
(82, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1507105096),
(83, 3, 114, 2130706433, 'member', 114, 'bobozaa1在2017-10-04 16:18登录了账号【积分：+10分】', 1, 1507105096),
(84, 3, 114, 2130706433, 'member', 114, 'bobozaa1在2017-10-04 16:18登录了账号', 1, 1507105096),
(85, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 08:15登录了账号【积分：+10分】', 1, 1507335339),
(86, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1507336118),
(87, 3, 115, 2130706433, 'member', 115, 'bboie在2017-10-07 08:28登录了账号【积分：+10分】', 1, 1507336118),
(88, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 08:55登录了账号', 1, 1507337757),
(89, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 11:15登录了账号', 1, 1507346156),
(90, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 11:59登录了账号', 1, 1507348796),
(91, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:00登录了账号', 1, 1507348842),
(92, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:07登录了账号', 1, 1507349236),
(93, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:08登录了账号', 1, 1507349309),
(94, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:09登录了账号', 1, 1507349362),
(95, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:10登录了账号', 1, 1507349442),
(96, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:11登录了账号', 1, 1507349475),
(97, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:12登录了账号', 1, 1507349524),
(98, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:12登录了账号', 1, 1507349552),
(99, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:13登录了账号', 1, 1507349601),
(100, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:14登录了账号', 1, 1507349647),
(101, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:14登录了账号', 1, 1507349679),
(102, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:15登录了账号', 1, 1507349728),
(103, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:17登录了账号', 1, 1507349844),
(104, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:18登录了账号', 1, 1507349885),
(105, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:20登录了账号', 1, 1507350010),
(106, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:21登录了账号', 1, 1507350074),
(107, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:22登录了账号', 1, 1507350136),
(108, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:22登录了账号', 1, 1507350164),
(109, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:23登录了账号', 1, 1507350186),
(110, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:24登录了账号', 1, 1507350287),
(111, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:25登录了账号', 1, 1507350351),
(112, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:26登录了账号', 1, 1507350393),
(113, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:28登录了账号', 1, 1507350521),
(114, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 12:29登录了账号', 1, 1507350557),
(115, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 13:11登录了账号', 1, 1507353091),
(116, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 13:13登录了账号', 1, 1507353205),
(117, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 13:15登录了账号', 1, 1507353303),
(118, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 13:16登录了账号', 1, 1507353361),
(119, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 13:17登录了账号', 1, 1507353478),
(120, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 13:19登录了账号', 1, 1507353553),
(121, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 13:20登录了账号', 1, 1507353624),
(122, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 13:59登录了账号', 1, 1507355977),
(123, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 14:00登录了账号', 1, 1507356014),
(124, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 14:37登录了账号', 1, 1507358254),
(125, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 14:38登录了账号', 1, 1507358282),
(126, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 14:38登录了账号', 1, 1507358305),
(127, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 14:38登录了账号', 1, 1507358330),
(128, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 14:39登录了账号', 1, 1507358356),
(129, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 14:44登录了账号', 1, 1507358695),
(130, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 15:44登录了账号', 1, 1507362292),
(131, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 15:45登录了账号', 1, 1507362336),
(132, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 15:52登录了账号', 1, 1507362762),
(133, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 15:53登录了账号', 1, 1507362811),
(134, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 16:06登录了账号', 1, 1507363572),
(135, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 16:53登录了账号', 1, 1507366432),
(136, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 17:03登录了账号', 1, 1507367020),
(137, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 17:17登录了账号', 1, 1507367842),
(138, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 17:18登录了账号', 1, 1507367939),
(139, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 17:19登录了账号', 1, 1507367952),
(140, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 17:19登录了账号', 1, 1507367988),
(141, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 17:19登录了账号', 1, 1507367989),
(142, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507367991),
(143, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 17:20登录了账号', 1, 1507368002),
(144, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 17:20登录了账号', 1, 1507368026),
(145, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 17:31登录了账号', 1, 1507368665),
(146, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-07 18:26登录了账号', 1, 1507371973),
(147, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-08 07:49登录了账号', 1, 1507420152),
(148, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-08 07:49登录了账号', 1, 1507420192),
(149, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-08 10:02登录了账号', 1, 1507428133),
(150, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-08 17:53登录了账号', 1, 1507456437),
(151, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-08 20:17登录了账号【积分：+10分】', 1, 1507465065),
(152, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-08 21:20登录了账号', 1, 1507468851),
(153, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-09 12:37登录了账号', 1, 1507523845),
(154, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-09 12:37登录了账号', 1, 1507523851),
(155, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-09 15:07登录了账号', 1, 1507532845),
(156, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-09 15:27登录了账号', 1, 1507534058),
(157, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-10 06:59登录了账号', 1, 1507589974),
(158, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-10 09:03登录了账号', 1, 1507597399),
(159, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-10 09:08登录了账号', 1, 1507597690),
(160, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-10 09:23登录了账号', 1, 1507598634),
(161, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-10 10:29登录了账号', 1, 1507602598),
(162, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-10 10:44登录了账号', 1, 1507603463),
(163, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-10 13:14登录了账号', 1, 1507612452),
(164, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-10 13:14登录了账号', 1, 1507612456),
(165, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-10 16:55登录了账号', 1, 1507625728),
(166, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-10 16:55登录了账号', 1, 1507625749),
(167, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-10 17:19登录了账号', 1, 1507627149),
(168, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-10 18:36登录了账号', 1, 1507631802),
(169, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507677398),
(170, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507677406),
(171, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507677410),
(172, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507677468),
(173, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507677503),
(174, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507677507),
(175, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-11 07:18登录了账号', 1, 1507677530),
(176, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-11 07:44登录了账号', 1, 1507679052),
(177, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507684980),
(178, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-11 09:23登录了账号', 1, 1507684985),
(179, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507704129),
(180, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507704134),
(181, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507704137),
(182, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507704479),
(183, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-11 14:48登录了账号', 1, 1507704482),
(184, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-11 14:50登录了账号', 1, 1507704605),
(185, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507705453),
(186, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-11 16:03登录了账号', 1, 1507709024),
(187, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-11 16:13登录了账号', 1, 1507709622),
(188, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-11 16:29登录了账号', 1, 1507710599),
(189, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507710817),
(190, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507710837),
(191, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507710875),
(192, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-11 16:34登录了账号', 1, 1507710878),
(193, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507711770),
(194, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-11 16:49登录了账号', 1, 1507711772),
(195, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507711867),
(196, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-11 16:51登录了账号', 1, 1507711878),
(197, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-12 10:11登录了账号【积分：+10分】', 1, 1507774292),
(198, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507774340),
(199, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-12 10:12登录了账号', 1, 1507774343),
(200, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1507777470),
(201, 3, 116, 2130706433, 'member', 116, 'soni在2017-10-12 11:04登录了账号【积分：+10分】', 1, 1507777470),
(202, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507777580),
(203, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-12 11:06登录了账号', 1, 1507777582),
(204, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-12 11:30登录了账号', 1, 1507779010),
(205, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-12 11:48登录了账号', 1, 1507780122),
(206, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507787575),
(207, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-12 13:52登录了账号', 1, 1507787578),
(208, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507796922),
(209, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-12 16:28登录了账号', 1, 1507796925),
(210, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-12 16:30登录了账号', 1, 1507797002),
(211, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-13 06:18登录了账号', 1, 1507846731),
(212, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507847272),
(213, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-13 06:27登录了账号', 1, 1507847275),
(214, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-13 09:12登录了账号', 1, 1507857153),
(215, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507857177),
(216, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-13 09:13登录了账号', 1, 1507857191),
(217, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-13 09:33登录了账号', 1, 1507858407),
(218, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-13 09:34登录了账号', 1, 1507858491),
(219, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-13 10:15登录了账号', 1, 1507860912),
(220, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-13 11:04登录了账号', 1, 1507863888),
(221, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-13 11:52登录了账号', 1, 1507866727),
(222, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-13 12:02登录了账号', 1, 1507867328),
(223, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-13 15:29登录了账号', 1, 1507879782),
(224, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-13 15:29登录了账号', 1, 1507879786),
(225, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-13 15:35登录了账号', 1, 1507880128),
(226, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-13 15:35登录了账号', 1, 1507880141),
(227, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-13 18:48登录了账号', 1, 1507891722),
(228, 2, 100, 2130706433, 'ucenter_member', 100, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1507895932),
(229, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-13 19:58登录了账号', 1, 1507895934),
(230, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-13 20:31登录了账号', 1, 1507897872),
(231, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-13 21:13登录了账号', 1, 1507900400),
(232, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-13 21:26登录了账号', 1, 1507901165),
(233, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-13 21:26登录了账号', 1, 1507901199),
(234, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-13 21:36登录了账号', 1, 1507901767),
(235, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-13 21:49登录了账号', 1, 1507902597),
(236, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-13 21:51登录了账号', 1, 1507902696),
(237, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-13 22:10登录了账号', 1, 1507903843),
(238, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-14 08:04登录了账号', 1, 1507939497),
(239, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-14 08:08登录了账号', 1, 1507939729),
(240, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-14 08:09登录了账号', 1, 1507939764),
(241, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-14 17:30登录了账号', 1, 1507973438),
(242, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-14 17:43登录了账号', 1, 1507974183),
(243, 3, 1, 2130706433, 'member', 1, 'yoyoadmin在2017-10-15 09:33登录了账号', 1, 1508031223),
(244, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-15 09:34登录了账号', 1, 1508031278),
(245, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-15 09:41登录了账号', 1, 1508031697),
(246, 2, 116, 2130706433, 'ucenter_member', 116, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1508031866),
(247, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-15 09:44登录了账号', 1, 1508031872),
(248, 3, 116, 2130706433, 'member', 116, 'soni在2017-10-15 10:15登录了账号【积分：+10分】', 1, 1508033718),
(249, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-15 10:59登录了账号', 1, 1508036378),
(250, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508037247),
(251, 3, 117, 2130706433, 'member', 117, 'fffeer在2017-10-15 11:14登录了账号【积分：+10分】', 1, 1508037247),
(252, 3, 100, 2130706433, 'member', 100, 'vbboy在2017-10-15 11:22登录了账号', 1, 1508037773),
(253, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508047428),
(254, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508047661),
(255, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508048702),
(256, 3, 120, 2130706433, 'member', 120, 'bozai112在2017-10-15 14:37登录了账号【积分：+10分】', 1, 1508049471),
(257, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508049573),
(258, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508049952),
(259, 3, 122, 2130706433, 'member', 122, 'njghg在2017-10-15 14:47登录了账号【积分：+10分】', 1, 1508050020),
(260, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508050693),
(261, 3, 123, 2130706433, 'member', 123, 'xcvxcv在2017-10-15 14:58登录了账号【积分：+10分】', 1, 1508050730),
(262, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508051424),
(263, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508051492),
(264, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508051643),
(265, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508052720),
(266, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508052832),
(267, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508053520),
(268, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508053655),
(269, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508053960),
(270, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508054061),
(271, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508054238),
(272, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508054439),
(273, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508054668),
(274, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-16 09:02登录了账号', 1, 1508115776),
(275, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-16 09:08登录了账号', 1, 1508116083),
(276, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-16 09:11登录了账号', 1, 1508116319),
(277, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-16 09:14登录了账号', 1, 1508116475),
(278, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508119586),
(279, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508119655),
(280, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508119952),
(281, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508120621),
(282, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508121027),
(283, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508122971),
(284, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508124662),
(285, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508124873),
(286, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508127248),
(287, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508127436),
(288, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508127913),
(289, 1, 1, 2130706433, 'ucenter_member', 1, '操作url：/yoyo/index.php?s=/ucenter/member/register.html', 1, 1508127984),
(290, 3, 147, 2130706433, 'member', 147, 'vbboy2015在2017-10-16 12:28登录了账号【积分：+10分】', 1, 1508128102),
(291, 2, 146, 2130706433, 'ucenter_member', 146, '操作url：/yoyo/index.php?s=/ucenter/member/login.html', 1, 1508142178),
(292, 3, 146, 2130706433, 'member', 146, 'sonia在2017-10-16 16:23登录了账号【积分：+10分】', 1, 1508142182),
(293, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-16 16:58登录了账号', 1, 1508144327),
(294, 3, 146, 2130706433, 'member', 146, 'sonia在2017-10-16 18:43登录了账号', 1, 1508150595),
(295, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-17 06:22登录了账号', 1, 1508192526),
(296, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-17 06:35登录了账号', 1, 1508193326),
(297, 3, 146, 2130706433, 'member', 146, 'sonia在2017-10-17 07:21登录了账号', 1, 1508196109),
(298, 3, 146, 2130706433, 'member', 146, 'sonia在2017-10-17 08:17登录了账号', 1, 1508199439),
(299, 3, 146, 2130706433, 'member', 146, 'sonia在2017-10-17 10:04登录了账号', 1, 1508205869),
(300, 3, 146, 2130706433, 'member', 146, 'sonia在2017-10-17 21:23登录了账号', 1, 1508246626),
(301, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-17 22:37登录了账号', 1, 1508251049),
(302, 3, 146, 2130706433, 'member', 146, 'sonia在2017-10-18 09:02登录了账号', 1, 1508288542),
(303, 3, 146, 2130706433, 'member', 146, 'sonia在2017-10-18 10:56登录了账号', 1, 1508295366),
(304, 3, 146, 2130706433, 'member', 146, 'sonia在2017-10-20 16:00登录了账号【积分：+10分】', 1, 1508486411),
(305, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-20 18:24登录了账号【积分：+10分】', 1, 1508495042),
(306, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-20 20:46登录了账号', 1, 1508503583),
(307, 3, 146, 2130706433, 'member', 146, 'sonia在2017-10-20 21:09登录了账号', 1, 1508504956),
(308, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-20 21:35登录了账号', 1, 1508506500),
(309, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-21 10:28登录了账号', 1, 1508552887),
(310, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-21 10:29登录了账号', 1, 1508552949),
(311, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-21 10:47登录了账号', 1, 1508554059),
(312, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-21 15:35登录了账号', 1, 1508571312),
(313, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-21 16:18登录了账号', 1, 1508573887),
(314, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-21 16:33登录了账号', 1, 1508574787),
(315, 3, 1, 2130706433, 'member', 1, 'yoyocoinsdd在2017-10-21 16:54登录了账号', 1, 1508576069);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_addons`
--

CREATE TABLE `ocenter_addons` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text COMMENT '插件描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `config` text COMMENT '配置',
  `author` varchar(40) DEFAULT '' COMMENT '作者',
  `version` varchar(20) DEFAULT '' COMMENT '版本号',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '安装时间',
  `has_adminlist` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否有后台列表'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='插件表';

--
-- 转存表中的数据 `ocenter_addons`
--

INSERT INTO `ocenter_addons` (`id`, `name`, `title`, `description`, `status`, `config`, `author`, `version`, `create_time`, `has_adminlist`) VALUES
(7, 'CheckIn', '签到', '签到插件', 1, '{\"random\":\"1\"}', 'xjw129xjt(肖骏涛)', '0.1', 1432791968, 0),
(8, 'Support', '赞', '赞的功能', 1, 'null', '嘉兴想天信息科技有限公司', '0.1', 1432792013, 0),
(9, 'SuperLinks', '合作单位', '合作单位', 1, '{\"random\":\"1\"}', '苏南 newsn.net', '0.1', 1432792019, 1),
(10, 'Report', '举报后台', '可举报不法数据', 1, '{\"meta\":\"\"}', '想天科技xuminwei', '0.1', 1432792026, 1),
(11, 'LocalComment', '本地评论', '本地评论插件，不依赖社会化评论平台', 1, '{\"can_guest_comment\":\"0\"}', 'caipeichao', '0.1', 1432792035, 0),
(12, 'ChinaCity', '中国省市区三级联动', '每个系统都需要的一个中国省市区三级联动插件。想天-駿濤修改，将镇级地区移除', 1, 'null', 'i友街', '2.0', 1432792040, 0),
(13, 'Recommend', '推荐关注', '可选择多种方法推荐用户', 1, '{\"howToRecommend\":\"1\",\"howManyRecommend\":\"1\",\"recommendUser\":\"1\"}', '嘉兴想天信息科技有限公司', '0.1', 1432792055, 1),
(14, 'SyncLogin', '同步登陆', '同步登陆', 1, '{\"type\":null,\"meta\":\"\",\"bind\":\"0\",\"QqKEY\":\"\",\"QqSecret\":\"\",\"SinaKEY\":\"\",\"SinaSecret\":\"\",\"WeixinKEY\":\"\",\"WeixinSecret\":\"\"}', 'xjw129xjt', '0.1', 1432792112, 0),
(15, 'SmsBao', '短信宝', '短信宝短信插件 http://www.smsbao.com/ ', 1, '{\"switch\":\"1\"}', '駿濤', '5.0.0', 1507679257, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_adv`
--

CREATE TABLE `ocenter_adv` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '主键',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '广告名称',
  `pos_id` int(11) NOT NULL COMMENT '广告位置',
  `data` text NOT NULL COMMENT '图片地址',
  `click_count` int(11) NOT NULL COMMENT '点击量',
  `url` varchar(500) NOT NULL COMMENT '链接地址',
  `sort` int(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态（0：禁用，1：正常）',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '开始时间',
  `start_time` int(11) DEFAULT NULL,
  `end_time` int(11) UNSIGNED DEFAULT '0' COMMENT '结束时间',
  `target` varchar(20) DEFAULT '_blank'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_advertisement`
--

CREATE TABLE `ocenter_advertisement` (
  `id` int(11) NOT NULL,
  `location` text NOT NULL COMMENT '广告的位置，表示在什么地方显示',
  `status` tinyint(3) NOT NULL COMMENT '状态：1表示启用，0表示禁用，-1表示删除',
  `imgid` text NOT NULL COMMENT '图片',
  `link` text NOT NULL COMMENT '链接地址',
  `create_time` int(11) NOT NULL,
  `name` text NOT NULL COMMENT '广告名'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_adv_pos`
--

CREATE TABLE `ocenter_adv_pos` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '主键',
  `name` varchar(50) NOT NULL,
  `title` char(80) NOT NULL DEFAULT '' COMMENT '广告位置名称',
  `path` varchar(100) NOT NULL COMMENT '所在路径 模块/控制器/方法',
  `type` int(11) UNSIGNED NOT NULL DEFAULT '1' COMMENT '广告位类型 \r\n1.单图\r\n2.多图\r\n3.文字链接\r\n4.代码',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态（0：禁用，1：正常）',
  `data` varchar(500) NOT NULL COMMENT '额外的数据',
  `width` char(20) NOT NULL DEFAULT '' COMMENT '广告位置宽度',
  `height` char(20) NOT NULL DEFAULT '' COMMENT '广告位置高度',
  `margin` varchar(50) NOT NULL COMMENT '边缘',
  `padding` varchar(50) NOT NULL COMMENT '留白',
  `theme` varchar(50) NOT NULL DEFAULT 'all' COMMENT '适用主题，默认为all，通用'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告位置表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `ocenter_adv_pos`
--

INSERT INTO `ocenter_adv_pos` (`id`, `name`, `title`, `path`, `type`, `status`, `data`, `width`, `height`, `margin`, `padding`, `theme`) VALUES
(1, 'right_below_all', '右侧底部广告', 'Weibo/Index/index', 1, 1, '0', '280px', '100px', '', '', 'all'),
(2, 'below_checkrank', '签到下方广告', 'Weibo/Index/index', 1, 1, '0', '280px', '100px', '0 0 10px 0', '', 'all'),
(3, 'filter_right', '过滤右方', 'Weibo/Index/index', 3, 1, '0', '300px', '30px', '', '', 'all'),
(10, 'below_post_content', '帖子主题下方广告1', 'Forum/Index/index', 1, 1, '0', '680px', '100px', '', '', 'all'),
(11, 'below_post_content', '论坛帖子主题下方广告', 'Forum/Index/detail', 1, 1, '', '680px', '100px', '', '', 'all'),
(4, 'below_self_info', '个人资料下方', 'Weibo/Index/index', 1, 1, '', '280px', '100px', '0 0 10px 0', '', 'all'),
(5, 'index_top', '资讯首页顶部广告', 'News/Index/index', 2, 1, '', '738px', '240px', '', '', 'all'),
(6, 'index_bottom_top', '资讯首页右侧最底部广告', 'News/Index/index', 1, 1, '', '360px', '120px', '10px 0 0 0', '', 'all'),
(9, 'slider', '首页轮播图', 'Home/Index/index', 2, 1, '{\"style\":1}', '1120px', '300px', '0 0 15px 0', '', 'all'),
(7, 'index_right_top', '资讯首页右侧最顶部广告', 'News/Index/index', 1, 1, '[]', '360px', '120px', '0 0 10px 0', '', 'all'),
(8, 'below_article_content', '资讯文章内容下方广告', 'News/Index/detail', 1, 1, '', '690px', '100px', '', '', 'all');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_announce`
--

CREATE TABLE `ocenter_announce` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `is_force` tinyint(4) NOT NULL COMMENT '是否强制推送',
  `content` text NOT NULL,
  `link` varchar(200) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `create_time` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `arrive` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公告表';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_announce_arrive`
--

CREATE TABLE `ocenter_announce_arrive` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `announce_id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公告确认记录';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_attachment`
--

CREATE TABLE `ocenter_attachment` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '用户ID',
  `title` char(30) NOT NULL DEFAULT '' COMMENT '附件显示名',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '附件类型',
  `source` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '资源ID',
  `record_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '关联记录ID',
  `download` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '下载次数',
  `size` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '附件大小',
  `dir` int(12) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级目录ID',
  `sort` int(8) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='附件表';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_attest`
--

CREATE TABLE `ocenter_attest` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `attest_type_id` int(11) NOT NULL COMMENT '认证类型id',
  `child_type` varchar(25) NOT NULL COMMENT '认证称号',
  `company_name` varchar(250) NOT NULL COMMENT '公司名',
  `name` varchar(50) NOT NULL COMMENT '真实姓名',
  `id_num` varchar(25) NOT NULL COMMENT '身份证号码',
  `phone` varchar(25) NOT NULL COMMENT '联系方式',
  `image_type` varchar(50) NOT NULL COMMENT '证件类型',
  `prove_image` varchar(100) NOT NULL COMMENT '企业证明、组织证明材料图片ids',
  `image` varchar(100) NOT NULL COMMENT '身份证正反面照ids',
  `other_image` varchar(100) NOT NULL COMMENT '手持身份证或其他证明材料',
  `info` text NOT NULL COMMENT '认证补充',
  `reason` varchar(200) NOT NULL COMMENT '认证失败原因',
  `status` tinyint(4) NOT NULL COMMENT '认证状态',
  `create_time` int(11) NOT NULL COMMENT '发起认证时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='认证表';

--
-- 转存表中的数据 `ocenter_attest`
--

INSERT INTO `ocenter_attest` (`id`, `uid`, `attest_type_id`, `child_type`, `company_name`, `name`, `id_num`, `phone`, `image_type`, `prove_image`, `image`, `other_image`, `info`, `reason`, `status`, `create_time`, `update_time`) VALUES
(1, 1, 1, '优秀二次开发者', '', '蒋志波', '431002198610120019', '18507358828', '身份证', '', '12,5,6,7,8,9,13,14,3', '11', '', '', -1, 1508572608, 1508574501),
(2, 1, 1, '优秀二次开发者', '', '蒋志波', '431002198610120019', '18507358828', '身份证', '', '12,5,6', '3', '', '', -1, 1508574709, 1508574709),
(3, 1, 1, '优秀二次开发者', '', '蒋志波', '431002198610120019', '18507358828', '身份证', '', '15,16,17,18,19,20,21', '14', '', '', -1, 1508574964, 1508574964),
(4, 1, 1, '优秀二次开发者', '', '蒋志波', '431002198610120019', '18507358828', '身份证', '', '22', '20', '', '', -1, 1508575368, 1508575368),
(5, 1, 1, '优秀二次开发者', '', '蒋志波', '431002198610120019', '18507358828', '身份证', '', '23', '11', '', '', 2, 1508575748, 1508575748);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_attest_type`
--

CREATE TABLE `ocenter_attest_type` (
  `id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL COMMENT '认证类型标识',
  `title` varchar(25) NOT NULL COMMENT '标题',
  `logo` int(11) NOT NULL COMMENT '图标',
  `privilege` varchar(25) NOT NULL COMMENT '特权标识，1：专属认证图标；2：优先推荐；3：各类特权',
  `conditions` varchar(200) NOT NULL COMMENT '条件，avatar：1；phone：1；follow：30；fans：30；friends：2',
  `fields` text NOT NULL COMMENT '字段配置',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态',
  `description` text NOT NULL COMMENT '认证说明',
  `tips` varchar(200) NOT NULL COMMENT '认证小提示'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='认证类型';

--
-- 转存表中的数据 `ocenter_attest_type`
--

INSERT INTO `ocenter_attest_type` (`id`, `name`, `title`, `logo`, `privilege`, `conditions`, `fields`, `status`, `description`, `tips`) VALUES
(1, 'personal', '个人认证', 0, '1,2,3', 'avatar:1|phone:0|follow:0|fans:0|friends:0', 'child_type:1|child_type_option:优秀二次开发者,网红，优秀站长，主播|company_name:0|name:1|id_num:1|phone:1|image_type:1|image_type_option:身份证，驾驶证|prove_image:0|image:1|other_image:2|other_image_tip:请上传手持身份证高清正面照|info:2', 1, '<p><span style=\"color: rgb(255, 0, 0);line-height:30px;\">请详细阅读本说明</span></p><p>1.信息必须真实</p><p>2.证件必须清晰可见</p><p>3.信息必须真实</p><p>4.信息必须真实</p><p>5.信息必须真实</p>', '真实身份，易于被辨识 更加权威，提高可信度'),
(2, 'organization', '组织机构认证', 0, '1,2,3', 'avatar:1|phone:1|follow:30|fans:30|friends:2', 'child_type:1|child_type_option:盈利机构，公益机构|company_name:1|name:2|id_num:2|phone:1|image_type:1|image_type_option:组织机构代码证|prove_image:1|image:0|other_image:2|other_image_tip:请上传代表人的个人身份证、手持身份证高清正面照和其他证明材料|info:2', 0, '<p style=\"white-space: normal;\"><span style=\"color: rgb(255, 0, 0); line-height: 30px;\">请详细阅读本说明</span></p><p style=\"white-space: normal;\">1.信息必须真实</p><p style=\"white-space: normal;\">2.证件必须清晰可见</p><p style=\"white-space: normal;\">3.信息必须真实</p><p style=\"white-space: normal;\">4.信息必须真实</p><p style=\"white-space: normal;\">5.信息必须真实</p><p><br/></p>', '真实身份，易于被辨识 更加权威，提高可信度'),
(3, 'company', '企业认证', 0, '1,2,3', 'avatar:1|phone:1|follow:50|fans:30|friends:5', 'child_type:1|child_type_option:大型企业，中型企业，小型企业，微型企业|company_name:1|name:2|id_num:2|phone:1|image_type:1|image_type_option:组织机构代码证|prove_image:1|image:0|other_image:2|other_image_tip:请上传其他证明材料|info:2', 1, '<p style=\"white-space: normal;\"><span style=\"color: rgb(255, 0, 0); line-height: 30px;\">请详细阅读本说明</span></p><p style=\"white-space: normal;\">1.信息必须真实</p><p style=\"white-space: normal;\">2.证件必须清晰可见</p><p style=\"white-space: normal;\">3.信息必须真实</p><p style=\"white-space: normal;\">4.信息必须真实</p><p style=\"white-space: normal;\">5.信息必须真实</p><p><br/></p>', '真实身份，易于被辨识 更加权威，提高可信度');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_auth_extend`
--

CREATE TABLE `ocenter_auth_extend` (
  `group_id` mediumint(10) UNSIGNED NOT NULL COMMENT '用户id',
  `extend_id` mediumint(8) UNSIGNED NOT NULL COMMENT '扩展表中数据的id',
  `type` tinyint(1) UNSIGNED NOT NULL COMMENT '扩展类型标识 1:栏目分类权限;2:模型权限'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限组与分类的对应关系表';

--
-- 转存表中的数据 `ocenter_auth_extend`
--

INSERT INTO `ocenter_auth_extend` (`group_id`, `extend_id`, `type`) VALUES
(1, 1, 1),
(1, 1, 2),
(1, 2, 1),
(1, 2, 2),
(1, 3, 1),
(1, 3, 2),
(1, 4, 1),
(1, 37, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_auth_group`
--

CREATE TABLE `ocenter_auth_group` (
  `id` mediumint(8) UNSIGNED NOT NULL COMMENT '权限组id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '权限组所属模块',
  `type` tinyint(4) NOT NULL COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '权限组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '权限组状态：为1正常，为0禁用,-1为删除',
  `rules` text NOT NULL COMMENT '权限组拥有的规则id，多个规则 , 隔开',
  `end_time` int(11) NOT NULL DEFAULT '2000000000' COMMENT '有效期'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_auth_group`
--

INSERT INTO `ocenter_auth_group` (`id`, `module`, `type`, `title`, `description`, `status`, `rules`, `end_time`) VALUES
(1, 'admin', 1, '普通用户', '', 1, ',338,340,341,344,10004', 2000000000),
(2, 'admin', 1, 'VIP', '', 1, ',338,340,341,344,10004', 2000000000);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_auth_group_access`
--

CREATE TABLE `ocenter_auth_group_access` (
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) UNSIGNED NOT NULL COMMENT '权限组id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_auth_group_access`
--

INSERT INTO `ocenter_auth_group_access` (`uid`, `group_id`) VALUES
(1, 1),
(100, 1),
(101, 1),
(102, 1),
(103, 1),
(104, 1),
(105, 1),
(106, 1),
(107, 1),
(108, 1),
(109, 1),
(110, 1),
(111, 1),
(112, 1),
(113, 1),
(114, 1),
(115, 1),
(116, 1),
(117, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_auth_rule`
--

CREATE TABLE `ocenter_auth_rule` (
  `id` mediumint(8) UNSIGNED NOT NULL COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_auth_rule`
--

INSERT INTO `ocenter_auth_rule` (`id`, `module`, `type`, `name`, `title`, `status`, `condition`) VALUES
(1, 'admin', 2, 'Admin/Index/index', '首页', 1, ''),
(2, 'admin', 2, 'Admin/Article/mydocument', '资讯', -1, ''),
(3, 'admin', 2, 'Admin/User/index', '用户', 1, ''),
(4, 'admin', 2, 'Admin/Addons/index', '插件', -1, ''),
(5, 'admin', 2, 'Admin/Config/group', '系统', 1, ''),
(7, 'admin', 1, 'Admin/article/add', '新增', -1, ''),
(8, 'admin', 1, 'Admin/article/edit', '编辑', -1, ''),
(9, 'admin', 1, 'Admin/article/setStatus', '改变状态', -1, ''),
(10, 'admin', 1, 'Admin/article/update', '保存', -1, ''),
(11, 'admin', 1, 'Admin/article/autoSave', '保存草稿', -1, ''),
(12, 'admin', 1, 'Admin/article/move', '移动', -1, ''),
(13, 'admin', 1, 'Admin/article/copy', '复制', -1, ''),
(14, 'admin', 1, 'Admin/article/paste', '粘贴', -1, ''),
(15, 'admin', 1, 'Admin/article/permit', '还原', -1, ''),
(16, 'admin', 1, 'Admin/article/clear', '清空', -1, ''),
(17, 'admin', 1, 'Admin/article/index', '文档列表', -1, ''),
(18, 'admin', 1, 'Admin/article/recycle', '回收站', -1, ''),
(19, 'admin', 1, 'Admin/User/addaction', '新增用户行为', 1, ''),
(20, 'admin', 1, 'Admin/User/editaction', '编辑用户行为', 1, ''),
(21, 'admin', 1, 'Admin/User/saveAction', '保存用户行为', 1, ''),
(22, 'admin', 1, 'Admin/User/setStatus', '变更行为状态', 1, ''),
(23, 'admin', 1, 'Admin/User/changeStatus?method=forbidUser', '禁用会员', 1, ''),
(24, 'admin', 1, 'Admin/User/changeStatus?method=resumeUser', '启用会员', 1, ''),
(25, 'admin', 1, 'Admin/User/changeStatus?method=deleteUser', '删除会员', 1, ''),
(26, 'admin', 1, 'Admin/User/index', '用户信息', 1, ''),
(27, 'admin', 1, 'Admin/User/action', '用户行为', 1, ''),
(28, 'admin', 1, 'Admin/AuthManager/changeStatus?method=deleteGroup', '删除', 1, ''),
(29, 'admin', 1, 'Admin/AuthManager/changeStatus?method=forbidGroup', '禁用', 1, ''),
(30, 'admin', 1, 'Admin/AuthManager/changeStatus?method=resumeGroup', '恢复', 1, ''),
(31, 'admin', 1, 'Admin/AuthManager/createGroup', '新增', 1, ''),
(32, 'admin', 1, 'Admin/AuthManager/editGroup', '编辑', 1, ''),
(33, 'admin', 1, 'Admin/AuthManager/writeGroup', '保存权限组', 1, ''),
(34, 'admin', 1, 'Admin/AuthManager/group', '授权', 1, ''),
(35, 'admin', 1, 'Admin/AuthManager/access', '访问授权', 1, ''),
(36, 'admin', 1, 'Admin/AuthManager/user', '成员授权', 1, ''),
(37, 'admin', 1, 'Admin/AuthManager/removeFromGroup', '解除授权', 1, ''),
(38, 'admin', 1, 'Admin/AuthManager/addToGroup', '保存成员授权', 1, ''),
(39, 'admin', 1, 'Admin/AuthManager/category', '分类授权', 1, ''),
(40, 'admin', 1, 'Admin/AuthManager/addToCategory', '保存分类授权', 1, ''),
(41, 'admin', 1, 'Admin/AuthManager/index', '权限管理', 1, ''),
(42, 'admin', 1, 'Admin/Addons/create', '创建', 1, ''),
(43, 'admin', 1, 'Admin/Addons/checkForm', '检测创建', 1, ''),
(44, 'admin', 1, 'Admin/Addons/preview', '预览', 1, ''),
(45, 'admin', 1, 'Admin/Addons/build', '快速生成插件', 1, ''),
(46, 'admin', 1, 'Admin/Addons/config', '设置', 1, ''),
(47, 'admin', 1, 'Admin/Addons/disable', '禁用', 1, ''),
(48, 'admin', 1, 'Admin/Addons/enable', '启用', 1, ''),
(49, 'admin', 1, 'Admin/Addons/install', '安装', 1, ''),
(50, 'admin', 1, 'Admin/Addons/uninstall', '卸载', 1, ''),
(51, 'admin', 1, 'Admin/Addons/saveconfig', '更新配置', 1, ''),
(52, 'admin', 1, 'Admin/Addons/adminList', '插件后台列表', 1, ''),
(53, 'admin', 1, 'Admin/Addons/execute', 'URL方式访问插件', 1, ''),
(54, 'admin', 1, 'Admin/Addons/index', '插件管理', 1, ''),
(55, 'admin', 1, 'Admin/Addons/hooks', '钩子管理', 1, ''),
(56, 'admin', 1, 'Admin/model/add', '新增', -1, ''),
(57, 'admin', 1, 'Admin/model/edit', '编辑', -1, ''),
(58, 'admin', 1, 'Admin/model/setStatus', '改变状态', -1, ''),
(59, 'admin', 1, 'Admin/model/update', '保存数据', -1, ''),
(60, 'admin', 1, 'Admin/Model/index', '模型管理', -1, ''),
(61, 'admin', 1, 'Admin/Config/edit', '编辑', 1, ''),
(62, 'admin', 1, 'Admin/Config/del', '删除', 1, ''),
(63, 'admin', 1, 'Admin/Config/add', '新增', 1, ''),
(64, 'admin', 1, 'Admin/Config/save', '保存', 1, ''),
(65, 'admin', 1, 'Admin/Config/group', '网站设置', 1, ''),
(66, 'admin', 1, 'Admin/Config/index', '配置管理', 1, ''),
(67, 'admin', 1, 'Admin/Channel/add', '新增', 1, ''),
(68, 'admin', 1, 'Admin/Channel/edit', '编辑', 1, ''),
(69, 'admin', 1, 'Admin/Channel/del', '删除', 1, ''),
(70, 'admin', 1, 'Admin/Channel/index', '导航管理', 1, ''),
(71, 'admin', 1, 'Admin/Category/edit', '编辑', -1, ''),
(72, 'admin', 1, 'Admin/Category/add', '新增', -1, ''),
(73, 'admin', 1, 'Admin/Category/remove', '删除', -1, ''),
(74, 'admin', 1, 'Admin/Category/index', '分类管理', -1, ''),
(75, 'admin', 1, 'Admin/file/upload', '上传控件', -1, ''),
(76, 'admin', 1, 'Admin/file/uploadPicture', '上传图片', -1, ''),
(77, 'admin', 1, 'Admin/file/download', '下载', -1, ''),
(94, 'admin', 1, 'Admin/AuthManager/modelauth', '模型授权', 1, ''),
(79, 'admin', 1, 'Admin/article/batchOperate', '导入', -1, ''),
(80, 'admin', 1, 'Admin/Database/index?type=export', '备份数据库', 1, ''),
(81, 'admin', 1, 'Admin/Database/index?type=import', '还原数据库', 1, ''),
(82, 'admin', 1, 'Admin/Database/export', '备份', 1, ''),
(83, 'admin', 1, 'Admin/Database/optimize', '优化表', 1, ''),
(84, 'admin', 1, 'Admin/Database/repair', '修复表', 1, ''),
(86, 'admin', 1, 'Admin/Database/import', '恢复', 1, ''),
(87, 'admin', 1, 'Admin/Database/del', '删除', 1, ''),
(88, 'admin', 1, 'Admin/User/add', '新增用户', -1, ''),
(89, 'admin', 1, 'Admin/Attribute/index', '属性管理', -1, ''),
(90, 'admin', 1, 'Admin/Attribute/add', '新增', -1, ''),
(91, 'admin', 1, 'Admin/Attribute/edit', '编辑', -1, ''),
(92, 'admin', 1, 'Admin/Attribute/setStatus', '改变状态', -1, ''),
(93, 'admin', 1, 'Admin/Attribute/update', '保存数据', -1, ''),
(95, 'admin', 1, 'Admin/AuthManager/addToModel', '保存模型授权', 1, ''),
(96, 'admin', 1, 'Admin/Category/move', '移动', -1, ''),
(97, 'admin', 1, 'Admin/Category/merge', '合并', -1, ''),
(98, 'admin', 1, 'Admin/Config/menu', '后台菜单管理', -1, ''),
(99, 'admin', 1, 'Admin/Article/mydocument', '内容', -1, ''),
(100, 'admin', 1, 'Admin/Menu/index', '菜单管理', 1, ''),
(101, 'admin', 1, 'Admin/other', '其他', -1, ''),
(102, 'admin', 1, 'Admin/Menu/add', '新增', 1, ''),
(103, 'admin', 1, 'Admin/Menu/edit', '编辑', 1, ''),
(104, 'admin', 1, 'Admin/Think/lists?model=article', '文章管理', -1, ''),
(105, 'admin', 1, 'Admin/Think/lists?model=download', '下载管理', -1, ''),
(106, 'admin', 1, 'Admin/Think/lists?model=config', '配置管理', -1, ''),
(107, 'admin', 1, 'Admin/Action/actionlog', '行为日志', 1, ''),
(108, 'admin', 1, 'Admin/User/updatePassword', '修改密码', 1, ''),
(109, 'admin', 1, 'Admin/User/updateNickname', '修改昵称', 1, ''),
(110, 'admin', 1, 'Admin/action/edit', '查看行为日志', 1, ''),
(205, 'admin', 1, 'Admin/think/add', '新增数据', -1, ''),
(111, 'admin', 2, 'Admin/article/index', '文档列表', -1, ''),
(112, 'admin', 2, 'Admin/article/add', '新增', -1, ''),
(113, 'admin', 2, 'Admin/article/edit', '编辑', -1, ''),
(114, 'admin', 2, 'Admin/article/setStatus', '改变状态', -1, ''),
(115, 'admin', 2, 'Admin/article/update', '保存', -1, ''),
(116, 'admin', 2, 'Admin/article/autoSave', '保存草稿', -1, ''),
(117, 'admin', 2, 'Admin/article/move', '移动', -1, ''),
(118, 'admin', 2, 'Admin/article/copy', '复制', -1, ''),
(119, 'admin', 2, 'Admin/article/paste', '粘贴', -1, ''),
(120, 'admin', 2, 'Admin/article/batchOperate', '导入', -1, ''),
(121, 'admin', 2, 'Admin/article/recycle', '回收站', -1, ''),
(122, 'admin', 2, 'Admin/article/permit', '还原', -1, ''),
(123, 'admin', 2, 'Admin/article/clear', '清空', -1, ''),
(124, 'admin', 2, 'Admin/User/add', '新增用户', -1, ''),
(125, 'admin', 2, 'Admin/User/action', '用户行为', -1, ''),
(126, 'admin', 2, 'Admin/User/addAction', '新增用户行为', -1, ''),
(127, 'admin', 2, 'Admin/User/editAction', '编辑用户行为', -1, ''),
(128, 'admin', 2, 'Admin/User/saveAction', '保存用户行为', -1, ''),
(129, 'admin', 2, 'Admin/User/setStatus', '变更行为状态', -1, ''),
(130, 'admin', 2, 'Admin/User/changeStatus?method=forbidUser', '禁用会员', -1, ''),
(131, 'admin', 2, 'Admin/User/changeStatus?method=resumeUser', '启用会员', -1, ''),
(132, 'admin', 2, 'Admin/User/changeStatus?method=deleteUser', '删除会员', -1, ''),
(133, 'admin', 2, 'Admin/AuthManager/index', '权限管理', -1, ''),
(134, 'admin', 2, 'Admin/AuthManager/changeStatus?method=deleteGroup', '删除', -1, ''),
(135, 'admin', 2, 'Admin/AuthManager/changeStatus?method=forbidGroup', '禁用', -1, ''),
(136, 'admin', 2, 'Admin/AuthManager/changeStatus?method=resumeGroup', '恢复', -1, ''),
(137, 'admin', 2, 'Admin/AuthManager/createGroup', '新增', -1, ''),
(138, 'admin', 2, 'Admin/AuthManager/editGroup', '编辑', -1, ''),
(139, 'admin', 2, 'Admin/AuthManager/writeGroup', '保存权限组', -1, ''),
(140, 'admin', 2, 'Admin/AuthManager/group', '授权', -1, ''),
(141, 'admin', 2, 'Admin/AuthManager/access', '访问授权', -1, ''),
(142, 'admin', 2, 'Admin/AuthManager/user', '成员授权', -1, ''),
(143, 'admin', 2, 'Admin/AuthManager/removeFromGroup', '解除授权', -1, ''),
(144, 'admin', 2, 'Admin/AuthManager/addToGroup', '保存成员授权', -1, ''),
(145, 'admin', 2, 'Admin/AuthManager/category', '分类授权', -1, ''),
(146, 'admin', 2, 'Admin/AuthManager/addToCategory', '保存分类授权', -1, ''),
(147, 'admin', 2, 'Admin/AuthManager/modelauth', '模型授权', -1, ''),
(148, 'admin', 2, 'Admin/AuthManager/addToModel', '保存模型授权', -1, ''),
(149, 'admin', 2, 'Admin/Addons/create', '创建', -1, ''),
(150, 'admin', 2, 'Admin/Addons/checkForm', '检测创建', -1, ''),
(151, 'admin', 2, 'Admin/Addons/preview', '预览', -1, ''),
(152, 'admin', 2, 'Admin/Addons/build', '快速生成插件', -1, ''),
(153, 'admin', 2, 'Admin/Addons/config', '设置', -1, ''),
(154, 'admin', 2, 'Admin/Addons/disable', '禁用', -1, ''),
(155, 'admin', 2, 'Admin/Addons/enable', '启用', -1, ''),
(156, 'admin', 2, 'Admin/Addons/install', '安装', -1, ''),
(157, 'admin', 2, 'Admin/Addons/uninstall', '卸载', -1, ''),
(158, 'admin', 2, 'Admin/Addons/saveconfig', '更新配置', -1, ''),
(159, 'admin', 2, 'Admin/Addons/adminList', '插件后台列表', -1, ''),
(160, 'admin', 2, 'Admin/Addons/execute', 'URL方式访问插件', -1, ''),
(161, 'admin', 2, 'Admin/Addons/hooks', '钩子管理', -1, ''),
(162, 'admin', 2, 'Admin/Model/index', '模型管理', -1, ''),
(163, 'admin', 2, 'Admin/model/add', '新增', -1, ''),
(164, 'admin', 2, 'Admin/model/edit', '编辑', -1, ''),
(165, 'admin', 2, 'Admin/model/setStatus', '改变状态', -1, ''),
(166, 'admin', 2, 'Admin/model/update', '保存数据', -1, ''),
(167, 'admin', 2, 'Admin/Attribute/index', '属性管理', -1, ''),
(168, 'admin', 2, 'Admin/Attribute/add', '新增', -1, ''),
(169, 'admin', 2, 'Admin/Attribute/edit', '编辑', -1, ''),
(170, 'admin', 2, 'Admin/Attribute/setStatus', '改变状态', -1, ''),
(171, 'admin', 2, 'Admin/Attribute/update', '保存数据', -1, ''),
(172, 'admin', 2, 'Admin/Config/index', '配置管理', -1, ''),
(173, 'admin', 2, 'Admin/Config/edit', '编辑', -1, ''),
(174, 'admin', 2, 'Admin/Config/del', '删除', -1, ''),
(175, 'admin', 2, 'Admin/Config/add', '新增', -1, ''),
(176, 'admin', 2, 'Admin/Config/save', '保存', -1, ''),
(177, 'admin', 2, 'Admin/Menu/index', '菜单管理', -1, ''),
(178, 'admin', 2, 'Admin/Channel/index', '导航管理', -1, ''),
(179, 'admin', 2, 'Admin/Channel/add', '新增', -1, ''),
(180, 'admin', 2, 'Admin/Channel/edit', '编辑', -1, ''),
(181, 'admin', 2, 'Admin/Channel/del', '删除', -1, ''),
(182, 'admin', 2, 'Admin/Category/index', '分类管理', -1, ''),
(183, 'admin', 2, 'Admin/Category/edit', '编辑', -1, ''),
(184, 'admin', 2, 'Admin/Category/add', '新增', -1, ''),
(185, 'admin', 2, 'Admin/Category/remove', '删除', -1, ''),
(186, 'admin', 2, 'Admin/Category/move', '移动', -1, ''),
(187, 'admin', 2, 'Admin/Category/merge', '合并', -1, ''),
(188, 'admin', 2, 'Admin/Database/index?type=export', '备份数据库', -1, ''),
(189, 'admin', 2, 'Admin/Database/export', '备份', -1, ''),
(190, 'admin', 2, 'Admin/Database/optimize', '优化表', -1, ''),
(191, 'admin', 2, 'Admin/Database/repair', '修复表', -1, ''),
(192, 'admin', 2, 'Admin/Database/index?type=import', '还原数据库', -1, ''),
(193, 'admin', 2, 'Admin/Database/import', '恢复', -1, ''),
(194, 'admin', 2, 'Admin/Database/del', '删除', -1, ''),
(195, 'admin', 2, 'Admin/other', '其他', -1, ''),
(196, 'admin', 2, 'Admin/Menu/add', '新增', -1, ''),
(197, 'admin', 2, 'Admin/Menu/edit', '编辑', -1, ''),
(198, 'admin', 2, 'Admin/Think/lists?model=article', '应用', -1, ''),
(199, 'admin', 2, 'Admin/Think/lists?model=download', '下载管理', -1, ''),
(200, 'admin', 2, 'Admin/Think/lists?model=config', '应用', -1, ''),
(201, 'admin', 2, 'Admin/Action/actionlog', '行为日志', -1, ''),
(202, 'admin', 2, 'Admin/User/updatePassword', '修改密码', -1, ''),
(203, 'admin', 2, 'Admin/User/updateNickname', '修改昵称', -1, ''),
(204, 'admin', 2, 'Admin/action/edit', '查看行为日志', -1, ''),
(206, 'admin', 1, 'Admin/think/edit', '编辑数据', -1, ''),
(207, 'admin', 1, 'Admin/Menu/import', '导入', 1, ''),
(208, 'admin', 1, 'Admin/Model/generate', '生成', -1, ''),
(209, 'admin', 1, 'Admin/Addons/addHook', '新增钩子', 1, ''),
(210, 'admin', 1, 'Admin/Addons/edithook', '编辑钩子', 1, ''),
(211, 'admin', 1, 'Admin/Article/sort', '文档排序', -1, ''),
(212, 'admin', 1, 'Admin/Config/sort', '排序', 1, ''),
(213, 'admin', 1, 'Admin/Menu/sort', '排序', 1, ''),
(214, 'admin', 1, 'Admin/Channel/sort', '排序', 1, ''),
(215, 'admin', 1, 'Admin/Category/operate/type/move', '移动', -1, ''),
(216, 'admin', 1, 'Admin/Category/operate/type/merge', '合并', -1, ''),
(217, 'admin', 1, 'Admin/Forum/forum', '板块管理', -1, ''),
(218, 'admin', 1, 'Admin/Forum/post', '帖子管理', -1, ''),
(219, 'admin', 1, 'Admin/Forum/editForum', '编辑／发表帖子', -1, ''),
(220, 'admin', 1, 'Admin/Forum/editPost', 'edit pots', -1, ''),
(221, 'admin', 2, 'Admin//Admin/Forum/index', '讨论区', -1, ''),
(222, 'admin', 2, 'Admin//Admin/Weibo/index', '微博', -1, ''),
(223, 'admin', 1, 'Admin/Forum/sortForum', '排序', -1, ''),
(224, 'admin', 1, 'Admin/SEO/editRule', '新增、编辑', 1, ''),
(225, 'admin', 1, 'Admin/SEO/sortRule', '排序', 1, ''),
(226, 'admin', 1, 'Admin/SEO/index', 'SEO规则管理', 1, ''),
(227, 'admin', 1, 'Admin/Forum/editReply', '新增 编辑', -1, ''),
(228, 'admin', 1, 'Admin/Weibo/editComment', '编辑回复', 1, ''),
(229, 'admin', 1, 'Admin/Weibo/editWeibo', '编辑微博', 1, ''),
(230, 'admin', 1, 'Admin/SEO/ruleTrash', 'SEO规则回收站', 1, ''),
(231, 'admin', 1, 'Admin/Rank/userList', '查看用户', 1, ''),
(232, 'admin', 1, 'Admin/Rank/userRankList', '用户头衔列表', 1, ''),
(233, 'admin', 1, 'Admin/Rank/userAddRank', '关联新头衔', 1, ''),
(234, 'admin', 1, 'Admin/Rank/userChangeRank', '编辑头衔关联', 1, ''),
(235, 'admin', 1, 'Admin/Issue/add', '编辑专辑', -1, ''),
(236, 'admin', 1, 'Admin/Issue/issue', '专辑管理', -1, ''),
(237, 'admin', 1, 'Admin/Issue/operate', '专辑操作', -1, ''),
(238, 'admin', 1, 'Admin/Weibo/weibo', '微博管理', 1, ''),
(239, 'admin', 1, 'Admin/Rank/index', '头衔列表', 1, ''),
(240, 'admin', 1, 'Admin/Forum/forumTrash', '板块回收站', -1, ''),
(241, 'admin', 1, 'Admin/Weibo/weiboTrash', '微博回收站', 1, ''),
(242, 'admin', 1, 'Admin/Rank/editRank', '添加头衔', 1, ''),
(243, 'admin', 1, 'Admin/Weibo/comment', '回复管理', 1, ''),
(244, 'admin', 1, 'Admin/Forum/postTrash', '帖子回收站', -1, ''),
(245, 'admin', 1, 'Admin/Weibo/commentTrash', '回复回收站', 1, ''),
(246, 'admin', 1, 'Admin/Issue/issueTrash', '专辑回收站', -1, ''),
(247, 'admin', 1, 'Admin//Admin/Forum/reply', '回复管理', -1, ''),
(248, 'admin', 1, 'Admin/Forum/replyTrash', '回复回收站', -1, ''),
(249, 'admin', 2, 'Admin/Forum/index', '贴吧', -1, ''),
(250, 'admin', 2, 'Admin/Weibo/weibo', '微博', 1, ''),
(251, 'admin', 2, 'Admin/SEO/index', 'SEO', -1, ''),
(252, 'admin', 2, 'Admin/Rank/index', '头衔', -1, ''),
(253, 'admin', 2, 'Admin/Issue/issue', '专辑', -1, ''),
(254, 'admin', 1, 'Admin/Issue/contents', '内容管理', -1, ''),
(255, 'admin', 1, 'Admin/User/profile', '扩展资料', 1, ''),
(256, 'admin', 1, 'Admin/User/editProfile', '添加、编辑分组', 1, ''),
(257, 'admin', 1, 'Admin/User/sortProfile', '分组排序', 1, ''),
(258, 'admin', 1, 'Admin/User/field', '字段列表', 1, ''),
(259, 'admin', 1, 'Admin/User/editFieldSetting', '添加、编辑字段', 1, ''),
(260, 'admin', 1, 'Admin/User/sortField', '字段排序', 1, ''),
(261, 'admin', 1, 'Admin/Update/quick', '全部补丁', 1, ''),
(262, 'admin', 1, 'Admin/Update/addpack', '新增补丁', 1, ''),
(263, 'admin', 1, 'Admin/User/expandinfo_select', '用户扩展资料列表', 1, ''),
(264, 'admin', 1, 'Admin/User/expandinfo_details', '扩展资料详情', 1, ''),
(265, 'admin', 1, 'Admin/Shop/shopLog', '商城信息记录', -1, ''),
(266, 'admin', 1, 'Admin/Shop/setStatus', '商品分类状态设置', -1, ''),
(267, 'admin', 1, 'Admin/Shop/setGoodsStatus', '商品状态设置', -1, ''),
(268, 'admin', 1, 'Admin/Shop/operate', '商品分类操作', -1, ''),
(269, 'admin', 1, 'Admin/Shop/add', '商品分类添加', -1, ''),
(270, 'admin', 1, 'Admin/Shop/goodsEdit', '添加、编辑商品', -1, ''),
(271, 'admin', 1, 'Admin/Shop/hotSellConfig', '热销商品阀值配置', -1, ''),
(272, 'admin', 1, 'Admin/Shop/setNew', '设置新品', -1, ''),
(273, 'admin', 1, 'Admin/EventType/index', '活动分类管理', -1, ''),
(274, 'admin', 1, 'Admin/Event/event', '内容管理', -1, ''),
(275, 'admin', 1, 'Admin/EventType/eventTypeTrash', '活动分类回收站', -1, ''),
(276, 'admin', 1, 'Admin/Event/verify', '内容审核', -1, ''),
(277, 'admin', 1, 'Admin/Event/contentTrash', '内容回收站', -1, ''),
(278, 'admin', 1, 'Admin/Rank/rankVerify', '待审核用户头衔', 1, ''),
(279, 'admin', 1, 'Admin/Rank/rankVerifyFailure', '被驳回的头衔申请', 1, ''),
(280, 'admin', 1, 'Admin/Weibo/config', '微博设置', 1, ''),
(281, 'admin', 1, 'Admin/Issue/verify', '内容审核', -1, ''),
(282, 'admin', 1, 'Admin/Shop/goodsList', '商品列表', -1, ''),
(283, 'admin', 1, 'Admin/Shop/shopCategory', '商品分类配置', -1, ''),
(284, 'admin', 1, 'Admin/Shop/categoryTrash', '商品分类回收站', -1, ''),
(285, 'admin', 1, 'Admin/Shop/verify', '待发货交易', -1, ''),
(286, 'admin', 1, 'Admin/Issue/contentTrash', '内容回收站', -1, ''),
(287, 'admin', 1, 'Admin/Shop/goodsBuySuccess', '交易成功记录', -1, ''),
(288, 'admin', 1, 'Admin/Shop/goodsTrash', '商品回收站', -1, ''),
(289, 'admin', 1, 'Admin/Shop/toxMoneyConfig', '货币配置', -1, ''),
(290, 'admin', 2, 'Admin/Shop/shopCategory', '商城', -1, ''),
(291, 'admin', 2, 'Admin/EventType/index', '活动', -1, ''),
(340, 'Weibo', 1, 'Weibo/Index/doSend', '发微博', 1, ''),
(297, 'Home', 1, 'deleteLocalComment', '删除本地评论', 1, ''),
(306, 'Issue', 1, 'addIssueContent', '专辑投稿权限', 1, ''),
(307, 'Issue', 1, 'editIssueContent', '编辑专辑内容（管理）', 1, ''),
(339, 'Weibo', 1, 'Weibo/Index/doDelWeibo', '删除微博(管理)', 1, ''),
(338, 'Weibo', 1, 'Weibo/Index/doSendRepost', '转发微博', 1, ''),
(313, 'admin', 1, 'Admin/module/install', '模块安装', 1, ''),
(315, 'admin', 1, 'Admin/module/lists', '模块管理', 1, ''),
(316, 'admin', 1, 'Admin/module/uninstall', '卸载模块', 1, ''),
(317, 'admin', 1, 'Admin/AuthManager/addNode', '新增权限节点', 1, ''),
(318, 'admin', 1, 'Admin/AuthManager/accessUser', '前台权限管理', 1, ''),
(319, 'admin', 1, 'Admin/User/changeGroup', '转移权限组', 1, ''),
(320, 'admin', 1, 'Admin/AuthManager/deleteNode', '删除权限节点', 1, ''),
(321, 'admin', 1, 'Admin/Issue/config', '专辑设置', -1, ''),
(322, 'admin', 2, 'Admin/module/lists', '云平台', -1, ''),
(341, 'Weibo', 1, 'Weibo/Index/doComment', '评论微博', 1, ''),
(342, 'Weibo', 1, 'Weibo/Index/doDelComment', '删除评论微博(管理)', 1, ''),
(343, 'Weibo', 1, 'Weibo/Index/setTop', '微博置顶(管理)', 1, ''),
(344, 'Weibo', 1, 'Weibo/Topic/beAdmin', '抢先成为话题主持人', 1, ''),
(345, 'Weibo', 1, 'Weibo/Topic/editTopic', '管理话题(管理)', 1, ''),
(346, 'admin', 1, 'Admin/UserConfig/index', '用户注册配置', 1, ''),
(347, 'admin', 1, 'Admin/User/scoreList', '积分类型列表', 1, ''),
(348, 'admin', 1, 'Admin/user/editScoreType', '新增/编辑类型', 1, ''),
(349, 'admin', 1, 'Admin/user/recharge', '充值积分', 1, ''),
(350, 'admin', 1, 'Admin/Authorize/ssoSetting', '单点登录配置', 1, ''),
(351, 'admin', 1, 'Admin/Authorize/ssolist', '应用列表', 1, ''),
(352, 'admin', 1, 'Admin/authorize/editssoapp', '新增/编辑应用', 1, ''),
(353, 'admin', 1, 'Admin/ActionLimit/limitList', '行为限制列表', 1, ''),
(354, 'admin', 1, 'Admin/ActionLimit/editLimit', '新增/编辑行为限制', 1, ''),
(355, 'admin', 1, 'Admin/Role/index', '角色列表', 1, ''),
(356, 'admin', 1, 'Admin/Role/editRole', '编辑角色', 1, ''),
(357, 'admin', 1, 'Admin/Role/setStatus', '启用、禁用、删除角色', 1, ''),
(358, 'admin', 1, 'Admin/Role/sort', '角色排序', 1, ''),
(359, 'admin', 1, 'Admin/Role/configScore', '默认积分配置', 1, ''),
(360, 'admin', 1, 'Admin/Role/configAuth', '默认权限配置', 1, ''),
(361, 'admin', 1, 'Admin/Role/configAvatar', '默认头像配置', 1, ''),
(362, 'admin', 1, 'Admin/Role/configRank', '默认头衔配置', 1, ''),
(363, 'admin', 1, 'Admin/Role/configField', '默认字段管理', 1, ''),
(364, 'admin', 1, 'Admin/Role/group', '角色分组', 1, ''),
(365, 'admin', 1, 'Admin/Role/editGroup', '编辑分组', 1, ''),
(366, 'admin', 1, 'Admin/Role/deleteGroup', '删除分组', 1, ''),
(367, 'admin', 1, 'Admin/Role/config', '角色基本信息配置', 1, ''),
(368, 'admin', 1, 'Admin/Role/userList', '用户列表', 1, ''),
(369, 'admin', 1, 'Admin/Role/setUserStatus', '设置用户状态', 1, ''),
(370, 'admin', 1, 'Admin/Role/setUserAudit', '审核用户', 1, ''),
(371, 'admin', 1, 'Admin/Role/changeRole', '迁移用户', 1, ''),
(372, 'admin', 1, 'Admin/Role/uploadPicture', '上传默认头像', 1, ''),
(373, 'admin', 1, 'Admin/Invite/index', '类型管理', 1, ''),
(374, 'admin', 1, 'Admin/Invite/invite', '邀请码管理', 1, ''),
(375, 'admin', 1, 'Admin/Invite/config', '基础配置', 1, ''),
(376, 'admin', 1, 'Admin/Invite/buyLog', '兑换记录', 1, ''),
(377, 'admin', 1, 'Admin/Invite/inviteLog', '邀请记录', 1, ''),
(378, 'admin', 1, 'Admin/Invite/userInfo', '用户信息', 1, ''),
(379, 'admin', 1, 'Admin/Invite/edit', '编辑邀请注册类型', 1, ''),
(380, 'admin', 1, 'Admin/Invite/setStatus', '删除邀请', 1, ''),
(381, 'admin', 1, 'Admin/Invite/delete', '删除邀请码', 1, ''),
(382, 'admin', 1, 'Admin/Invite/createCode', '生成邀请码', 1, ''),
(383, 'admin', 1, 'Admin/Invite/deleteTrue', '删除无用邀请码', 1, ''),
(384, 'admin', 1, 'Admin/Invite/cvs', '导出cvs', 1, ''),
(385, 'admin', 1, 'Admin/Invite/editUserInfo', '用户信息编辑', 1, ''),
(386, 'admin', 1, 'Admin/Action/remove', '删除日志', 1, ''),
(387, 'admin', 1, 'Admin/Action/clear', '清空日志', 1, ''),
(388, 'admin', 1, 'Admin/User/setTypeStatus', '设置积分状态', 1, ''),
(389, 'admin', 1, 'Admin/User/delType', '删除积分类型', 1, ''),
(390, 'admin', 1, 'Admin/User/getNickname', '充值积分', 1, ''),
(391, 'admin', 1, 'Admin/Menu/del', '删除菜单', 1, ''),
(392, 'admin', 1, 'Admin/Menu/toogleDev', '设置开发者模式可见', 1, ''),
(393, 'admin', 1, 'Admin/Menu/toogleHide', '设置显示隐藏', 1, ''),
(394, 'admin', 1, 'Admin/ActionLimit/setLimitStatus', '行为限制启用、禁用、删除', 1, ''),
(395, 'admin', 1, 'Admin/SEO/setRuleStatus', '启用、禁用、删除、回收站还原', 1, ''),
(396, 'admin', 1, 'Admin/SEO/doClear', '回收站彻底删除', 1, ''),
(397, 'admin', 1, 'Admin/Role/initUnhaveUser', '初始化无角色用户', 1, ''),
(398, 'admin', 1, 'Admin/Addons/delHook', '删除钩子', 1, ''),
(399, 'admin', 1, 'Admin/Update/usePack', '使用补丁', 1, ''),
(400, 'admin', 1, 'Admin/Update/view', '查看补丁', 1, ''),
(401, 'admin', 1, 'Admin/Update/delPack', '删除补丁', 1, ''),
(402, 'admin', 1, 'Admin/UserTag/userTag', '标签列表', 1, ''),
(403, 'admin', 1, 'Admin/UserTag/add', '添加分类、标签', 1, ''),
(404, 'admin', 1, 'Admin/UserTag/setStatus', '设置分类、标签状态', 1, ''),
(405, 'admin', 1, 'Admin/UserTag/tagTrash', '分类、标签回收站', 1, ''),
(406, 'admin', 1, 'Admin/UserTag/userTagClear', '测底删除回收站内容', 1, ''),
(407, 'admin', 1, 'Admin/role/configusertag', '可拥有标签配置', 1, ''),
(408, 'admin', 1, 'Admin/Module/edit', '编辑模块', 1, ''),
(409, 'admin', 1, 'Admin/Config/website', '网站信息', 1, ''),
(410, 'admin', 1, 'Admin/Theme/setTheme', '使用主题', 1, ''),
(411, 'admin', 1, 'Admin/Theme/lookTheme', '查看主题', 1, ''),
(412, 'admin', 1, 'Admin/Theme/packageDownload', '主题打包下载', 1, ''),
(413, 'admin', 1, 'Admin/Theme/delete', '卸载删除主题', 1, ''),
(414, 'admin', 1, 'Admin/Theme/add', '上传安装主题', 1, ''),
(415, 'admin', 2, 'Admin/Home/config', '网站主页', 1, ''),
(416, 'admin', 1, 'Admin/Home/config', '基本设置', 1, ''),
(417, 'admin', 1, 'Admin/Weibo/topic', '话题管理', 1, ''),
(418, 'admin', 1, 'Admin/Weibo/setWeiboTop', '置顶微博', 1, ''),
(419, 'admin', 1, 'Admin/Weibo/setWeiboStatus', '设置微博状态', 1, ''),
(420, 'admin', 1, 'Admin/Weibo/setCommentStatus', '设置微博评论状态', 1, ''),
(421, 'admin', 1, 'Admin/Weibo/setTopicTop', '设置置顶话题', 1, ''),
(422, 'admin', 1, 'Admin/Weibo/delTopic', '删除话题', 1, ''),
(423, 'admin', 1, 'Admin/People/config', '基本设置', 1, ''),
(424, 'admin', 1, 'Admin/Cloud/index', '云市场', 1, ''),
(425, 'admin', 2, 'Admin/authorize/ssoSetting', '授权', 1, ''),
(426, 'admin', 2, 'Admin/Role/index', '角色', 1, ''),
(427, 'admin', 1, 'Admin/Theme/tpls', '主题管理', 1, ''),
(428, 'admin', 2, 'Admin/ActionLimit/limitList', '安全', 1, ''),
(429, 'admin', 2, 'Admin/Cloud/index', '云市场', 1, ''),
(430, 'admin', 2, 'Admin/People/config', '会员展示', 1, ''),
(431, 'admin', 1, 'Admin/Index/index', '后台入口', 1, ''),
(432, 'Ucenter', 1, 'Ucenter/Attest/apply', '申请认证', 1, ''),
(10005, 'News', 1, 'News/Index/edit', '编辑资讯（管理）', 1, ''),
(10004, 'News', 1, 'News/Index/add', '资讯投稿', 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_avatar`
--

CREATE TABLE `ocenter_avatar` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `path` varchar(200) NOT NULL,
  `driver` varchar(50) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `is_temp` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_avatar`
--

INSERT INTO `ocenter_avatar` (`id`, `uid`, `path`, `driver`, `create_time`, `status`, `is_temp`) VALUES
(1, 1, '/1/59c9fe662f4d9.jpg', 'local', 1506410089, 1, 0),
(4, 100, '/100/59e2d49d800f3.jpg', 'local', 1508037790, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_channel`
--

CREATE TABLE `ocenter_channel` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '频道ID',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级频道ID',
  `title` char(30) NOT NULL COMMENT '频道标题',
  `url` char(100) NOT NULL COMMENT '频道连接',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '导航排序',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `target` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '新窗口打开',
  `color` varchar(30) NOT NULL,
  `band_color` varchar(30) NOT NULL,
  `band_text` varchar(30) NOT NULL,
  `icon` varchar(20) NOT NULL,
  `image` int(11) NOT NULL,
  `remark` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_channel`
--

INSERT INTO `ocenter_channel` (`id`, `pid`, `title`, `url`, `sort`, `create_time`, `update_time`, `status`, `target`, `color`, `band_color`, `band_text`, `icon`, `image`, `remark`) VALUES
(1, 0, '首页', 'Home/Index/index', 0, 0, 0, 1, 0, '#000000', '#000000', '', '-', 0, ''),
(2, 0, '买卖中心', 'Coin/index/index', 1, 0, 0, 1, 0, '#000000', '#000000', '', '-', 0, ''),
(3, 0, '发布广告', '/ad/advertise', 2, 0, 0, 1, 0, '#000000', '#000000', '', '-', 0, ''),
(4, 0, '资讯', 'News/index/index', 4, 0, 0, 1, 0, '#000000', '#000000', '', '-', 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_checkin`
--

CREATE TABLE `ocenter_checkin` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_checkin`
--

INSERT INTO `ocenter_checkin` (`id`, `uid`, `create_time`) VALUES
(1, 1, 1506410210),
(2, 1, 1506495009);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_coin_addr`
--

CREATE TABLE `ocenter_coin_addr` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `addr_type` tinyint(2) NOT NULL COMMENT '类型：1充值,2提款',
  `coin_type` tinyint(2) NOT NULL COMMENT '类型：1BTC,2ETH',
  `addr` varchar(50) NOT NULL COMMENT '钱包地址',
  `status` tinyint(2) NOT NULL COMMENT '状态：1正常，2冻结'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='钱包地址表';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_collect`
--

CREATE TABLE `ocenter_collect` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `module` varchar(32) NOT NULL COMMENT '模块名',
  `table` varchar(32) NOT NULL COMMENT '表名',
  `row` int(11) NOT NULL,
  `create_time` int(11) NOT NULL COMMENT '收藏时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_config`
--

CREATE TABLE `ocenter_config` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '配置ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(500) NOT NULL COMMENT '配置说明',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `value` text NOT NULL COMMENT '配置值',
  `sort` smallint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_config`
--

INSERT INTO `ocenter_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES
(100, 'WEB_SITE_CLOSE', 4, '关闭站点', 1, '0:关闭,1:开启', '站点关闭后其他用户不能访问，管理员可以正常访问', 1378898976, 1379235296, 1, '1', 11),
(101, 'CONFIG_TYPE_LIST', 3, '配置类型列表', 4, '', '主要用于数据解析和页面表单的生成', 1378898976, 1379235348, 1, '0:数字\r\n1:字符\r\n2:文本\r\n3:数组\r\n4:枚举\r\n8:多选框', 8),
(102, 'CONFIG_GROUP_LIST', 3, '配置分组', 4, '', '配置分组', 1379228036, 1384418383, 1, '1:基本\r\n2:内容\r\n3:用户\r\n4:系统\r\n5:邮件', 15),
(103, 'HOOKS_TYPE', 3, '钩子的类型', 4, '', '类型 1-用于扩展显示内容，2-用于扩展业务处理', 1379313397, 1379313407, 1, '1:视图\r\n2:控制器', 17),
(104, 'AUTH_CONFIG', 3, 'Auth配置', 4, '', '自定义Auth.class.php类配置', 1379409310, 1379409564, 1, 'AUTH_ON:1\r\nAUTH_TYPE:2', 20),
(105, 'LIST_ROWS', 0, '后台每页记录数', 2, '', '后台数据每页显示记录数', 1379503896, 1380427745, 1, '10', 24),
(107, 'CODEMIRROR_THEME', 4, '预览插件的CodeMirror主题', 4, '3024-day:3024 day\r\n3024-night:3024 night\r\nambiance:ambiance\r\nbase16-dark:base16 dark\r\nbase16-light:base16 light\r\nblackboard:blackboard\r\ncobalt:cobalt\r\neclipse:eclipse\r\nelegant:elegant\r\nerlang-dark:erlang-dark\r\nlesser-dark:lesser-dark\r\nmidnight:midnight', '详情见CodeMirror官网', 1379814385, 1384740813, 1, 'ambiance', 13),
(108, 'DATA_BACKUP_PATH', 1, '数据库备份根路径', 4, '', '路径必须以 / 结尾', 1381482411, 1381482411, 1, './Data/Backup', 16),
(109, 'DATA_BACKUP_PART_SIZE', 0, '数据库备份卷大小', 4, '', '该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M', 1381482488, 1381729564, 1, '20971520', 18),
(110, 'DATA_BACKUP_COMPRESS', 4, '数据库备份文件是否启用压缩', 4, '0:不压缩\r\n1:启用压缩', '压缩备份文件需要PHP环境支持gzopen,gzwrite函数', 1381713345, 1381729544, 1, '1', 22),
(111, 'DATA_BACKUP_COMPRESS_LEVEL', 4, '数据库备份文件压缩级别', 4, '1:普通\r\n4:一般\r\n9:最高', '数据库备份文件的压缩级别，该配置在开启压缩时生效', 1381713408, 1381713408, 1, '9', 25),
(112, 'DEVELOP_MODE', 4, '开启开发者模式', 4, '0:关闭\r\n1:开启', '是否开启开发者模式，部分后台功能必须在开发者模式下才生效，重新设置后清空缓存生效', 1383105995, 1383291877, 1, '1', 26),
(113, 'ALLOW_VISIT', 3, '不受限控制器方法', 0, '', '', 1386644047, 1386644741, 1, '0:article/draftbox\r\n1:article/mydocument\r\n2:Category/tree\r\n3:Index/verify\r\n4:file/upload\r\n5:file/download\r\n6:user/updatePassword\r\n7:user/updateNickname\r\n8:user/submitPassword\r\n9:user/submitNickname\r\n10:file/uploadpicture', 2),
(114, 'DENY_VISIT', 3, '超管专限控制器方法', 0, '', '仅超级管理员可访问的控制器方法', 1386644141, 1386644659, 1, '0:Addons/addhook\r\n1:Addons/edithook\r\n2:Addons/delhook\r\n3:Addons/updateHook\r\n4:Admin/getMenus\r\n5:Admin/recordList\r\n6:AuthManager/updateRules\r\n7:AuthManager/tree', 3),
(115, 'ADMIN_ALLOW_IP', 2, '后台允许访问IP', 4, '', '多个用逗号分隔，如果不配置表示不限制IP访问', 1387165454, 1387165553, 1, '', 27),
(116, 'SHOW_PAGE_TRACE', 4, '是否显示页面Trace', 4, '0:关闭\r\n1:开启', '是否显示页面Trace信息', 1387165685, 1387165685, 1, '0', 7),
(117, 'MAIL_TYPE', 4, '邮件类型', 5, '1:SMTP 模块发送\r\n2:mail() 函数发送', '如果您选择了采用服务器内置的 Mail 服务，您不需要填写下面的内容', 1388332882, 1388931416, 1, '1', 0),
(118, 'MAIL_SMTP_HOST', 1, 'SMTP 服务器', 5, '', 'SMTP服务器', 1388332932, 1388332932, 1, 'smtp.exmail.qq.com', 0),
(119, 'MAIL_SMTP_PORT', 0, 'SMTP服务器端口', 5, '', '默认25', 1388332975, 1388332975, 1, '465', 0),
(120, 'MAIL_SMTP_USER', 1, 'SMTP服务器用户名', 5, '', '填写完整用户名', 1388333010, 1388333010, 1, 'support@yoyocoins.com', 0),
(121, 'MAIL_SMTP_PASS', 6, 'SMTP服务器密码', 5, '', '填写您的密码', 1388333057, 1389187088, 1, 'Zz35580113', 0),
(122, 'MAIL_USER_PASS', 5, '密码找回模板', 0, '', '支持HTML代码', 1388583989, 1388672614, 1, '密码找回111223333555111', 0),
(123, 'PIC_FILE_PATH', 1, '图片文件保存根目录', 4, '', '图片文件保存根目录./目录/', 1388673255, 1388673255, 1, './Uploads/', 0),
(124, 'COUNT_DAY', 0, '后台首页统计用户增长天数', 0, '', '默认统计最近半个月的用户数增长情况', 1420791945, 1420876261, 1, '7', 0),
(125, 'MAIL_USER_REG', 5, '注册邮件模板', 3, '', '支持HTML代码', 1388337307, 1389532335, 1, '<a href=\"http://www.opensns.cn\" target=\"_blank\">点击进入</a><span style=\"color:#E53333;\">当您收到这封邮件，表明您已注册成功，以上为您的用户名和密码。。。。祝您生活愉快····</span>', 55),
(126, 'USER_NAME_BAOLIU', 1, '保留用户名和昵称', 3, '', '禁止注册用户名和昵称，包含这些即无法注册,用\" , \"号隔开，用户只能是英文，下划线_，数字等', 1388845937, 1388845937, 1, '管理员,测试,admin,垃圾', 0),
(128, 'VERIFY_OPEN', 8, '验证码配置', 4, 'reg:注册显示\r\nlogin:登陆显示\r\nreset:密码重置', '验证码配置', 1388500332, 1405561711, 1, '', 0),
(129, 'VERIFY_TYPE', 4, '验证码类型', 4, '1:中文\r\n2:英文\r\n3:数字\r\n4:英文+数字', '验证码类型', 1388500873, 1405561731, 1, '4', 0),
(130, 'NO_BODY_TLE', 2, '空白说明', 2, '', '空白说明', 1392216444, 1392981305, 1, '呵呵，暂时没有内容哦！！', 0),
(131, 'USER_RESPASS', 5, '密码找回模板', 3, '', '密码找回文本', 1396191234, 1396191234, 1, '<span style=\"color:#009900;\">请点击以下链接找回密码，如无反应，请将链接地址复制到浏览器中打开(下次登录前有效)</span>', 0),
(132, 'COUNT_CODE', 2, '统计代码', 1, '', '用于统计网站访问量的第三方代码，推荐CNZZ统计', 1403058890, 1403058890, 1, '', 12),
(134, 'URL_MODEL', 4, 'URL模式', 4, '2:REWRITE模式(开启伪静态)\r\n3:兼容模式', '选择Rewrite模式则开启伪静态，在开启伪静态之前需要先<a href=\"http://v2.opensns.cn/index.php?s=/news/index/detail/id/128.html\" target=\"_blank\">设置伪静态</a>或者阅读/Rewrite/readme.txt中的说明，默认建议开启兼容模式', 1421027546, 1421027676, 1, '3', 0),
(135, 'DEFUALT_HOME_URL', 1, '登录前首页Url', 1, '', '支持形如weibo/index/index的ThinkPhp路由写法，支持普通的url写法，不填则显示默认聚合首页', 1417509438, 1427340006, 1, '', 1),
(136, 'AUTO_UPDATE', 4, '自动更新提示', 1, '0:关闭,1:开启', '关闭后，后台将不显示更新提示', 1433731153, 1433731348, 1, '1', 2),
(137, 'WEB_SITE_CLOSE_HINT', 2, '关站提示文字', 1, '', '站点关闭后的提示文字。', 1433731248, 1433731287, 1, '网站正在更新维护，请稍候再试。', 4),
(138, 'SESSION_PREFIX', 1, '网站前台session前缀', 1, '', '当多个网站在同一个根域名下请保证每个网站的前缀不同', 1436923664, 1436923664, 1, 'opensns', 20),
(139, 'COOKIE_PREFIX', 1, '网站前台cookie前缀', 1, '', '当多个网站在同一个根域名下请保证每个网站的前缀不同', 1436923664, 1436923664, 1, 'opensns_', 21),
(140, 'MAIL_SMTP_CE', 1, '邮件发送测试', 5, '', '填写测试邮件地址', 1388334529, 1388584028, 1, 'soni@vip.qq.com', 11),
(141, 'LOST_LONG', 0, '用户流失标准（天）', 0, '', '', 1469414315, 1469414315, 1, '30', 0),
(142, 'MAIL_VERIFY_TYPE', 4, 'SMTP验证方式', 5, '1:无\r\n2:ssl\r\n3:tls', 'SMTP验证方式', 1388332882, 1388931416, 1, '2', 0),
(11165, '_USERCONFIG_REG_SWITCH', 0, '', 0, '', '', 1507680018, 1507680018, 1, 'email,mobile', 0),
(11219, '_CONFIG_WEB_SITE_NAME', 0, '', 0, '', '', 1507898783, 1507898783, 1, 'OpenSNS v5开源社群系统', 0),
(11220, '_CONFIG_ICP', 0, '', 0, '', '', 1507898783, 1507898783, 1, '浙ICP备12042711号-5', 0),
(11221, '_CONFIG_LOGO', 0, '', 0, '', '', 1507898783, 1507898783, 1, '', 0),
(11223, '_CONFIG_QRCODE', 0, '', 0, '', '', 1507898783, 1507898783, 1, '', 0),
(11237, '_CONFIG_ABOUT_US', 0, '', 0, '', '', 1507898783, 1507898783, 1, '<p>&nbsp; 嘉兴想天信息科技有限公司专注于为客户提供专业的社群方案。公司秉持简洁、高效、创新，不断为客户创造奇迹。旗下产品有OpenSNS开源社群系统和OpenCenter开源用户和后台管理系统。</p>', 0),
(11235, '_CONFIG_SUBSCRIB_US', 0, '', 0, '', '', 1507898783, 1507898783, 1, '<p>业务QQ：276905621</p><p>联系地址：浙江省桐乡市环城南路1号电子商务中心</p><p>联系电话：0573-88037510</p>', 0),
(11234, '_CONFIG_COPY_RIGHT', 0, '', 0, '', '', 1507898783, 1507898783, 1, '<p>Copyright ©2014-2017 <a href=\"http://www.ourstu.com\" target=\"_blank\">嘉兴想天信息科技有限公司</a></p>', 0),
(1008, '_HOME_LOGO', 0, '', 0, '', '', 1432791820, 1432791820, 1, '', 0),
(1009, '_HOME_ENTER_URL', 0, '', 0, '', '', 1432791820, 1432791820, 1, '', 0),
(10002, '_HOME_BLOCK', 0, '', 0, '', '', 1506049592, 1506049592, 1, '[{\"data-id\":\"disable\",\"title\":\"\\u7981\\u7528\",\"items\":[{\"data-id\":\"Coin\",\"title\":\"\\u4e70\\u5356\\u4e2d\\u5fc3\"}]},{\"data-id\":\"enable\",\"title\":\"\\u542f\\u7528\",\"items\":[{\"data-id\":\"slider\",\"title\":\"\\u8f6e\\u64ad\"},{\"data-id\":\"Weibo\",\"title\":\"\\u52a8\\u6001\"},{\"data-id\":\"People\",\"title\":\"\\u627e\\u4eba\"}]}]', 0),
(1011, '_HOME_PIC1', 0, '', 0, '', '', 1432791820, 1432791820, 1, '', 0),
(1012, '_HOME_URL1', 0, '', 0, '', '', 1432791820, 1432791820, 1, '', 0),
(1013, '_HOME_TITLE1', 0, '', 0, '', '', 1432791820, 1432791820, 1, '', 0),
(1014, '_HOME_PIC2', 0, '', 0, '', '', 1432791820, 1432791820, 1, '', 0),
(1015, '_HOME_URL2', 0, '', 0, '', '', 1432791820, 1432791820, 1, '', 0),
(1016, '_HOME_TITLE2', 0, '', 0, '', '', 1432791820, 1432791820, 1, '', 0),
(1017, '_HOME_PIC3', 0, '', 0, '', '', 1432791820, 1432791820, 1, '', 0),
(1018, '_HOME_URL3', 0, '', 0, '', '', 1432791820, 1432791820, 1, '', 0),
(1019, '_HOME_TITLE3', 0, '', 0, '', '', 1432791820, 1432791820, 1, '', 0),
(11236, '_CONFIG_COMPANY', 0, '', 0, '', '', 1507898783, 1507898783, 1, '<p><a href=\"http://www.ourstu.com/joinus.html\" target=\"_blank\"></a></p><p><a href=\"http://www.ourstu.com/joinus.html\" target=\"_blank\"><em class=\"icon icon-user\"></em>加入我们</a></p><p><br/></p><p><em class=\"icon icon-phone-sign\"></em>400-0573-080</p><p><em class=\"icon icon-envelope-alt\"></em>co@ourstu.com</p>', 0),
(11222, '_CONFIG_QRCODE_BOTTOM', 0, '', 0, '', '', 1507898783, 1507898783, 1, '', 0),
(11187, '_USERCONFIG_UCENTER_KANBAN', 0, '', 0, '', '', 1507680018, 1507680018, 1, '[{\"data-id\":\"disable\",\"title\":\"\\u7981\\u7528\",\"items\":[{\"data-id\":\"Weibo\",\"title\":\"\\u52a8\\u6001Pro\"},{\"data-id\":\"News\",\"title\":\"\\u8d44\\u8baf\"}]},{\"data-id\":\"enable\",\"title\":\"\\u542f\\u7528\",\"items\":[{\"data-id\":\"follow\",\"title\":\"TA\\u7684\\u5173\\u6ce8\\/\\u7c89\\u4e1d\"},{\"data-id\":\"info\",\"title\":\"\\u8d44\\u6599\"},{\"data-id\":\"rank_title\",\"title\":\"\\u5934\\u8854\"},{\"data-id\":\"topic_list\",\"title\":\"\\u5173\\u6ce8\\u7684\\u8bdd\\u9898\"}]}]', 0),
(11740, '_CONFIG_FIRST_USER_RUN', 0, '', 0, '', '', 1508630988, 1508630988, 1, '2017-10-22', 0),
(10001, '_HOME_HOME_INDEX_TYPE', 0, '', 0, '', '', 1506049592, 1506049592, 1, 'index', 0),
(10003, '_HOME_SEARCH', 0, '', 0, '', '', 1506049592, 1506049592, 1, '[{\"data-id\":\"disable\",\"title\":\"\\u7981\\u7528\",\"items\":[]},{\"data-id\":\"enable\",\"title\":\"\\u542f\\u7528\",\"items\":[{\"data-id\":\"Coin\",\"title\":\"\\u4e70\\u5356\\u4e2d\\u5fc3\"},{\"data-id\":\"People\",\"title\":\"\\u627e\\u4eba\"},{\"data-id\":\"Weibo\",\"title\":\"\\u52a8\\u6001\"}]}]', 0),
(11192, '_BCLIENT_OS_OFFICIAL_ACCOUNT', 0, '', 0, '', '', 1507846886, 1507846886, 1, 'soni@vip.qq.com', 0),
(11193, '_BCLIENT_OS_OFFICIAL_PASSWORD', 0, '', 0, '', '', 1507846886, 1507846886, 1, 'z35580113', 0),
(11194, '_BCLIENT_SERVER_DOMAIN', 0, '', 0, '', '', 1507846892, 1507846892, 1, '127.0.0.1', 0),
(11195, '_BCLIENT_SERVER_IP', 0, '', 0, '', '', 1507846892, 1507846892, 1, '127.0.0.1', 0),
(11196, '_BCLIENT_SERVER_USES', 0, '', 0, '', '', 1507846892, 1507846892, 1, 'test', 0),
(11197, '_BCLIENT_SERVER_CONTEXT', 0, '', 0, '', '', 1507846892, 1507846892, 1, '0', 0),
(11198, '_BCLIENT_SERVER_CERTIFICATE', 0, '', 0, '', '', 1507846892, 1507846892, 1, 'ef0f864bdb16f324b81fe6978eec58fc', 0),
(11199, '_BCLIENT_SERVER_UNIQUE', 0, '', 0, '', '', 1507846892, 1507846892, 1, 'd0:a6:37:ed:7b:73', 0),
(11224, '_CONFIG_JUMP_BACKGROUND', 0, '', 0, '', '', 1507898783, 1507898783, 1, '', 0),
(11225, '_CONFIG_SUCCESS_WAIT_TIME', 0, '', 0, '', '', 1507898783, 1507898783, 1, '2', 0),
(11226, '_CONFIG_ERROR_WAIT_TIME', 0, '', 0, '', '', 1507898783, 1507898783, 1, '5', 0),
(11227, '_CONFIG_OPEN_IM', 0, '', 0, '', '', 1507898783, 1507898783, 1, '1', 0),
(11228, '_CONFIG_GET_INFORMATION', 0, '', 0, '', '', 1507898783, 1507898783, 1, '1', 0),
(11229, '_CONFIG_GET_INFORMATION_INTERNAL', 0, '', 0, '', '', 1507898783, 1507898783, 1, '10', 0),
(11230, '_CONFIG_PICTURE_UPLOAD_DRIVER', 0, '', 0, '', '', 1507898783, 1507898783, 1, 'local', 0),
(11231, '_CONFIG_DOWNLOAD_UPLOAD_DRIVER', 0, '', 0, '', '', 1507898783, 1507898783, 1, 'local', 0),
(11232, '_CONFIG_WEBSOCKET_ADDRESS', 0, '', 0, '', '', 1507898783, 1507898783, 1, '127.0.0.1', 0),
(11233, '_CONFIG_WEBSOCKET_PORT', 0, '', 0, '', '', 1507898783, 1507898783, 1, '8000', 0),
(11166, '_USERCONFIG_EMAIL_VERIFY_TYPE', 0, '', 0, '', '', 1507680018, 1507680018, 1, '2', 0),
(11167, '_USERCONFIG_MOBILE_VERIFY_TYPE', 0, '', 0, '', '', 1507680018, 1507680018, 1, '1', 0),
(11168, '_USERCONFIG_NEW_USER_FOLLOW', 0, '', 0, '', '', 1507680018, 1507680018, 1, '', 0),
(11169, '_USERCONFIG_NEW_USER_FANS', 0, '', 0, '', '', 1507680018, 1507680018, 1, '', 0),
(11170, '_USERCONFIG_NEW_USER_FRIENDS', 0, '', 0, '', '', 1507680018, 1507680018, 1, '', 0),
(11171, '_USERCONFIG_REG_STEP', 0, '', 0, '', '', 1507680018, 1507680018, 1, '[{\"data-id\":\"disable\",\"title\":\"\\u7981\\u7528\",\"items\":[{\"data-id\":\"change_avatar\",\"title\":\"\\u4fee\\u6539\\u5934\\u50cf\"},{\"data-id\":\"expand_info\",\"title\":\"\\u586b\\u5199\\u6269\\u5c55\\u8d44\\u6599\"},{\"data-id\":\"set_tag\",\"title\":\"\\u9009\\u62e9\\u4e2a\\u4eba\\u6807\\u7b7e\"}]},{\"data-id\":\"enable\",\"title\":\"\\u542f\\u7528\",\"items\":[]}]', 0),
(11172, '_USERCONFIG_REG_CAN_SKIP', 0, '', 0, '', '', 1507680018, 1507680018, 1, '', 0),
(11173, '_USERCONFIG_OPEN_QUICK_LOGIN', 0, '', 0, '', '', 1507680018, 1507680018, 1, '0', 0),
(11174, '_USERCONFIG_LOGIN_SWITCH', 0, '', 0, '', '', 1507680018, 1507680018, 1, 'email,mobile', 0),
(11175, '_USERCONFIG_SYNC_LOGIN_EMAIL_SUFFIX', 0, '', 0, '', '', 1507680018, 1507680018, 1, '@ocenter.com', 0),
(11176, '_USERCONFIG_SMS_HOOK', 0, '', 0, '', '', 1507680018, 1507680018, 1, 'SmsBao', 0),
(11177, '_USERCONFIG_SMS_RESEND', 0, '', 0, '', '', 1507680018, 1507680018, 1, '60', 0),
(11178, '_USERCONFIG_SMS_UID', 0, '', 0, '', '', 1507680018, 1507680018, 1, 'vbboy2012', 0),
(11179, '_USERCONFIG_SMS_PWD', 0, '', 0, '', '', 1507680018, 1507680018, 1, 'z35580113', 0),
(11180, '_USERCONFIG_SMS_CONTENT', 0, '', 0, '', '', 1507680018, 1507680018, 1, '您的验证码为{$verify}验证码', 0),
(11181, '_USERCONFIG_RANK_LIST', 0, '', 0, '', '', 1507680018, 1507680018, 1, 'fans,con_check,total_check,score', 0),
(11182, '_USERCONFIG_LEVEL', 0, '', 0, '', '', 1507680018, 1507680018, 1, '0:Lv1 实习\r\n50:Lv2 试用\r\n100:Lv3 转正\r\n200:Lv4 助理\r\n400:Lv5 经理\r\n800:Lv6 董事\r\n1600:Lv7 董事长', 0),
(11183, '_USERCONFIG_NICKNAME_MIN_LENGTH', 0, '', 0, '', '', 1507680018, 1507680018, 1, '2', 0),
(11184, '_USERCONFIG_NICKNAME_MAX_LENGTH', 0, '', 0, '', '', 1507680018, 1507680018, 1, '32', 0),
(11185, '_USERCONFIG_USERNAME_MIN_LENGTH', 0, '', 0, '', '', 1507680018, 1507680018, 1, '2', 0),
(11186, '_USERCONFIG_USERNAME_MAX_LENGTH', 0, '', 0, '', '', 1507680018, 1507680018, 1, '32', 0),
(11188, '_USERCONFIG_REG_EMAIL_ACTIVATE', 0, '', 0, '', '', 1507680018, 1507680018, 1, '<p>您在{$title}的激活链接为<a href=\"{$url}\" target=\"_blank\">激活</a>，或者请复制链接：{$url}到浏览器打开。</p>', 0),
(11189, '_USERCONFIG_REG_EMAIL_VERIFY', 0, '', 0, '', '', 1507680018, 1507680018, 1, '<p>您的验证码为{$verify}验证码，账号为{$account}。</p>', 0);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_consumption_log`
--

CREATE TABLE `ocenter_consumption_log` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '自增id',
  `uid` varchar(255) NOT NULL DEFAULT '' COMMENT '用户id',
  `behavior` text NOT NULL COMMENT '行为',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `amount` decimal(10,2) NOT NULL COMMENT '金额'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户消费记录日志';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_country`
--

CREATE TABLE `ocenter_country` (
  `id` smallint(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `en_name` varchar(50) NOT NULL,
  `code` varchar(5) NOT NULL DEFAULT '',
  `code2` varchar(5) NOT NULL DEFAULT '',
  `code3` varchar(5) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_country`
--

INSERT INTO `ocenter_country` (`id`, `name`, `en_name`, `code`, `code2`, `code3`) VALUES
(1, '阿富汗', 'Afghanistan', 'AF', 'AFG', '004'),
(2, '奥兰群岛', 'Aland Islands', 'AX', 'ALA', '248'),
(3, '阿尔巴尼亚', 'Albania', 'AL', 'ALB', '008'),
(4, '阿尔及利亚', 'Algeria', 'DZ', 'DZA', '012'),
(5, '美属萨摩亚', 'American Samoa', 'AS', 'ASM', '016'),
(6, '安道尔', 'Andorra', 'AD', 'AND', '020'),
(7, '安哥拉', 'Angola', 'AO', 'AGO', '024'),
(8, '安圭拉', 'Anguilla', 'AI', 'AIA', '660'),
(9, '南极洲', 'Antarctica', 'AQ', 'ATA', '010'),
(10, '安提瓜和巴布达', 'Antigua and Barbuda', 'AG', 'ATG', '028'),
(11, '阿根廷', 'Argentina', 'AR', 'ARG', '032'),
(12, '亚美尼亚', 'Armenia', 'AM', 'ARM', '051'),
(13, '阿鲁巴', 'Aruba', 'AW', 'ABW', '533'),
(14, '澳大利亚', 'Australia', 'AU', 'AUS', '036'),
(15, '奥地利', 'Austria', 'AT', 'AUT', '040'),
(16, '阿塞拜疆', 'Azerbaijan', 'AZ', 'AZE', '031'),
(17, '巴哈马', 'Bahamas (The)', 'BS', 'BHS', '044'),
(18, '巴林', 'Bahrain', 'BH', 'BHR', '048'),
(19, '孟加拉国', 'Bangladesh', 'BD', 'BGD', '050'),
(20, '巴巴多斯', 'Barbados', 'BB', 'BRB', '052'),
(21, '白俄罗斯', 'Belarus', 'BY', 'BLR', '112'),
(22, '比利时', 'Belgium', 'BE', 'BEL', '056'),
(23, '伯利兹', 'Belize', 'BZ', 'BLZ', '084'),
(24, '贝宁', 'Benin', 'BJ', 'BEN', '204'),
(25, '百慕大', 'Bermuda', 'BM', 'BMU', '060'),
(26, '不丹', 'Bhutan', 'BT', 'BTN', '064'),
(27, '玻利维亚', 'Bolivia', 'BO', 'BOL', '068'),
(28, '波黑', 'Bosnia and Herzegovina', 'BA', 'BIH', '070'),
(29, '博茨瓦纳', 'Botswana', 'BW', 'BWA', '072'),
(30, '布维岛', 'Bouvet Island', 'BV', 'BVT', '074'),
(31, '巴西', 'Brazil', 'BR', 'BRA', '076'),
(32, '英属印度洋领地', 'British Indian Ocean Territory (the)', 'IO', 'IOT', '086'),
(33, '文莱', 'Brunei Darussalam', 'BN', 'BRN', '096'),
(34, '保加利亚', 'Bulgaria', 'BG', 'BGR', '100'),
(35, '布基纳法索', 'Burkina Faso', 'BF', 'BFA', '854'),
(36, '布隆迪', 'Burundi', 'BI', 'BDI', '108'),
(37, '柬埔寨', 'Cambodia', 'KH', 'KHM', '116'),
(38, '喀麦隆', 'Cameroon', 'CM', 'CMR', '120'),
(39, '加拿大', 'Canada', 'CA', 'CAN', '124'),
(40, '佛得角', 'Cape Verde', 'CV', 'CPV', '132'),
(41, '开曼群岛', 'Cayman Islands (the)', 'KY', 'CYM', '136'),
(42, '中非', 'Central African Republic (the)', 'CF', 'CAF', '140'),
(43, '乍得', 'Chad', 'TD', 'TCD', '148'),
(44, '智利', 'Chile', 'CL', 'CHL', '152'),
(45, '中国', 'China', 'CN', 'CHN', '156'),
(46, '圣诞岛', 'Christmas Island', 'CX', 'CXR', '162'),
(47, '科科斯（基林）群岛', 'Cocos (Keeling) Islands (the)', 'CC', 'CCK', '166'),
(48, '哥伦比亚', 'Colombia', 'CO', 'COL', '170'),
(49, '科摩罗', 'Comoros', 'KM', 'COM', '174'),
(50, '刚果（布）', 'Congo', 'CG', 'COG', '178'),
(51, '刚果（金）', 'Congo (the Democratic Republic of the)', 'CD', 'COD', '180'),
(52, '库克群岛', 'Cook Islands (the)', 'CK', 'COK', '184'),
(53, '哥斯达黎加', 'Costa Rica', 'CR', 'CRI', '188'),
(54, '科特迪瓦', 'Côte d\'Ivoire', 'CI', 'CIV', '384'),
(55, '克罗地亚', 'Croatia', 'HR', 'HRV', '191'),
(56, '古巴', 'Cuba', 'CU', 'CUB', '192'),
(57, '塞浦路斯', 'Cyprus', 'CY', 'CYP', '196'),
(58, '捷克', 'Czech Republic (the)', 'CZ', 'CZE', '203'),
(59, '丹麦', 'Denmark', 'DK', 'DNK', '208'),
(60, '吉布提', 'Djibouti', 'DJ', 'DJI', '262'),
(61, '多米尼克', 'Dominica', 'DM', 'DMA', '212'),
(62, '多米尼加', 'Dominican Republic (the)', 'DO', 'DOM', '214'),
(63, '厄瓜多尔', 'Ecuador', 'EC', 'ECU', '218'),
(64, '埃及', 'Egypt', 'EG', 'EGY', '818'),
(65, '萨尔瓦多', 'El Salvador', 'SV', 'SLV', '222'),
(66, '赤道几内亚', 'Equatorial Guinea', 'GQ', 'GNQ', '226'),
(67, '厄立特里亚', 'Eritrea', 'ER', 'ERI', '232'),
(68, '爱沙尼亚', 'Estonia', 'EE', 'EST', '233'),
(69, '埃塞俄比亚', 'Ethiopia', 'ET', 'ETH', '231'),
(70, '福克兰群岛（马尔维纳斯）', 'Falkland Islands (the) [Malvinas]', 'FK', 'FLK', '238'),
(71, '法罗群岛', 'Faroe Islands (the)', 'FO', 'FRO', '234'),
(72, '斐济', 'Fiji', 'FJ', 'FJI', '242'),
(73, '芬兰', 'Finland', 'FI', 'FIN', '246'),
(74, '法国', 'France', 'FR', 'FRA', '250'),
(75, '法属圭亚那', 'French Guiana', 'GF', 'GUF', '254'),
(76, '法属波利尼西亚', 'French Polynesia', 'PF', 'PYF', '258'),
(77, '法属南部领地', 'French Southern Territories (the)', 'TF', 'ATF', '260'),
(78, '加蓬', 'Gabon', 'GA', 'GAB', '266'),
(79, '冈比亚', 'Gambia (The)', 'GM', 'GMB', '270'),
(80, '格鲁吉亚', 'Georgia', 'GE', 'GEO', '268'),
(81, '德国', 'Germany', 'DE', 'DEU', '276'),
(82, '加纳', 'Ghana', 'GH', 'GHA', '288'),
(83, '直布罗陀', 'Gibraltar', 'GI', 'GIB', '292'),
(84, '希腊', 'Greece', 'GR', 'GRC', '300'),
(85, '格陵兰', 'Greenland', 'GL', 'GRL', '304'),
(86, '格林纳达', 'Grenada', 'GD', 'GRD', '308'),
(87, '瓜德罗普', 'Guadeloupe', 'GP', 'GLP', '312'),
(88, '关岛', 'Guam', 'GU', 'GUM', '316'),
(89, '危地马拉', 'Guatemala', 'GT', 'GTM', '320'),
(90, '格恩西岛', 'Guernsey', 'GG', 'GGY', '831'),
(91, '几内亚', 'Guinea', 'GN', 'GIN', '324'),
(92, '几内亚比绍', 'Guinea-Bissau', 'GW', 'GNB', '624'),
(93, '圭亚那', 'Guyana', 'GY', 'GUY', '328'),
(94, '海地', 'Haiti', 'HT', 'HTI', '332'),
(95, '赫德岛和麦克唐纳岛', 'Heard Island and McDonald Islands', 'HM', 'HMD', '334'),
(96, '梵蒂冈', 'Holy See (the) [Vatican City State]', 'VA', 'VAT', '336'),
(97, '洪都拉斯', 'Honduras', 'HN', 'HND', '340'),
(98, '香港', 'Hong Kong', 'HK', 'HKG', '344'),
(99, '匈牙利', 'Hungary', 'HU', 'HUN', '348'),
(100, '冰岛', 'Iceland', 'IS', 'ISL', '352'),
(101, '印度', 'India', 'IN', 'IND', '356'),
(102, '印度尼西亚', 'Indonesia', 'ID', 'IDN', '360'),
(103, '伊朗', 'Iran (the Islamic Republic of)', 'IR', 'IRN', '364'),
(104, '伊拉克', 'Iraq', 'IQ', 'IRQ', '368'),
(105, '爱尔兰', 'Ireland', 'IE', 'IRL', '372'),
(106, '英国属地曼岛', 'Isle of Man', 'IM', 'IMN', '833'),
(107, '以色列', 'Israel', 'IL', 'ISR', '376'),
(108, '意大利', 'Italy', 'IT', 'ITA', '380'),
(109, '牙买加', 'Jamaica', 'JM', 'JAM', '388'),
(110, '日本', 'Japan', 'JP', 'JPN', '392'),
(111, '泽西岛', 'Jersey', 'JE', 'JEY', '832'),
(112, '约旦', 'Jordan', 'JO', 'JOR', '400'),
(113, '哈萨克斯坦', 'Kazakhstan', 'KZ', 'KAZ', '398'),
(114, '肯尼亚', 'Kenya', 'KE', 'KEN', '404'),
(115, '基里巴斯', 'Kiribati', 'KI', 'KIR', '296'),
(116, '朝鲜', 'Korea (the Democratic People\'s Republic of)', 'KP', 'PRK', '408'),
(117, '韩国', 'Korea (the Republic of)', 'KR', 'KOR', '410'),
(118, '科威特', 'Kuwait', 'KW', 'KWT', '414'),
(119, '吉尔吉斯斯坦', 'Kyrgyzstan', 'KG', 'KGZ', '417'),
(120, '老挝', 'Lao People\'s Democratic Republic (the)', 'LA', 'LAO', '418'),
(121, '拉脱维亚', 'Latvia', 'LV', 'LVA', '428'),
(122, '黎巴嫩', 'Lebanon', 'LB', 'LBN', '422'),
(123, '莱索托', 'Lesotho', 'LS', 'LSO', '426'),
(124, '利比里亚', 'Liberia', 'LR', 'LBR', '430'),
(125, '利比亚', 'Libyan Arab Jamahiriya (the)', 'LY', 'LBY', '434'),
(126, '列支敦士登', 'Liechtenstein', 'LI', 'LIE', '438'),
(127, '立陶宛', 'Lithuania', 'LT', 'LTU', '440'),
(128, '卢森堡', 'Luxembourg', 'LU', 'LUX', '442'),
(129, '澳门', 'Macao', 'MO', 'MAC', '446'),
(130, '前南马其顿', 'Macedonia (the former Yugoslav Republic of)', 'MK', 'MKD', '807'),
(131, '马达加斯加', 'Madagascar', 'MG', 'MDG', '450'),
(132, '马拉维', 'Malawi', 'MW', 'MWI', '454'),
(133, '马来西亚', 'Malaysia', 'MY', 'MYS', '458'),
(134, '马尔代夫', 'Maldives', 'MV', 'MDV', '462'),
(135, '马里', 'Mali', 'ML', 'MLI', '466'),
(136, '马耳他', 'Malta', 'MT', 'MLT', '470'),
(137, '马绍尔群岛', 'Marshall Islands (the)', 'MH', 'MHL', '584'),
(138, '马提尼克', 'Martinique', 'MQ', 'MTQ', '474'),
(139, '毛利塔尼亚', 'Mauritania', 'MR', 'MRT', '478'),
(140, '毛里求斯', 'Mauritius', 'MU', 'MUS', '480'),
(141, '马约特', 'Mayotte', 'YT', 'MYT', '175'),
(142, '墨西哥', 'Mexico', 'MX', 'MEX', '484'),
(143, '密克罗尼西亚联邦', 'Micronesia (the Federated States of)', 'FM', 'FSM', '583'),
(144, '摩尔多瓦', 'Moldova (the Republic of)', 'MD', 'MDA', '498'),
(145, '摩纳哥', 'Monaco', 'MC', 'MCO', '492'),
(146, '蒙古', 'Mongolia', 'MN', 'MNG', '496'),
(147, '黑山', 'Montenegro', 'ME', 'MNE', '499'),
(148, '蒙特塞拉特', 'Montserrat', 'MS', 'MSR', '500'),
(149, '摩洛哥', 'Morocco', 'MA', 'MAR', '504'),
(150, '莫桑比克', 'Mozambique', 'MZ', 'MOZ', '508'),
(151, '缅甸', 'Myanmar', 'MM', 'MMR', '104'),
(152, '纳米比亚', 'Namibia', 'NA', 'NAM', '516'),
(153, '瑙鲁', 'Nauru', 'NR', 'NRU', '520'),
(154, '尼泊尔', 'Nepal', 'NP', 'NPL', '524'),
(155, '荷兰', 'Netherlands (the)', 'NL', 'NLD', '528'),
(156, '荷属安的列斯', 'Netherlands Antilles (the)', 'AN', 'ANT', '530'),
(157, '新喀里多尼亚', 'New Caledonia', 'NC', 'NCL', '540'),
(158, '新西兰', 'New Zealand', 'NZ', 'NZL', '554'),
(159, '尼加拉瓜', 'Nicaragua', 'NI', 'NIC', '558'),
(160, '尼日尔', 'Niger (the)', 'NE', 'NER', '562'),
(161, '尼日利亚', 'Nigeria', 'NG', 'NGA', '566'),
(162, '纽埃', 'Niue', 'NU', 'NIU', '570'),
(163, '诺福克岛', 'Norfolk Island', 'NF', 'NFK', '574'),
(164, '北马里亚纳', 'Northern Mariana Islands (the)', 'MP', 'MNP', '580'),
(165, '挪威', 'Norway', 'NO', 'NOR', '578'),
(166, '阿曼', 'Oman', 'OM', 'OMN', '512'),
(167, '巴基斯坦', 'Pakistan', 'PK', 'PAK', '586'),
(168, '帕劳', 'Palau', 'PW', 'PLW', '585'),
(169, '巴勒斯坦', 'Palestinian Territory (the Occupied)', 'PS', 'PSE', '275'),
(170, '巴拿马', 'Panama', 'PA', 'PAN', '591'),
(171, '巴布亚新几内亚', 'Papua New Guinea', 'PG', 'PNG', '598'),
(172, '巴拉圭', 'Paraguay', 'PY', 'PRY', '600'),
(173, '秘鲁', 'Peru', 'PE', 'PER', '604'),
(174, '菲律宾', 'Philippines (the)', 'PH', 'PHL', '608'),
(175, '皮特凯恩', 'Pitcairn', 'PN', 'PCN', '612'),
(176, '波兰', 'Poland', 'PL', 'POL', '616'),
(177, '葡萄牙', 'Portugal', 'PT', 'PRT', '620'),
(178, '波多黎各', 'Puerto Rico', 'PR', 'PRI', '630'),
(179, '卡塔尔', 'Qatar', 'QA', 'QAT', '634'),
(180, '留尼汪', 'Réunion', 'RE', 'REU', '638'),
(181, '罗马尼亚', 'Romania', 'RO', 'ROU', '642'),
(182, '俄罗斯联邦', 'Russian Federation (the)', 'RU', 'RUS', '643'),
(183, '卢旺达', 'Rwanda', 'RW', 'RWA', '646'),
(184, '圣赫勒拿', 'Saint Helena', 'SH', 'SHN', '654'),
(185, '圣基茨和尼维斯', 'Saint Kitts and Nevis', 'KN', 'KNA', '659'),
(186, '圣卢西亚', 'Saint Lucia', 'LC', 'LCA', '662'),
(187, '圣皮埃尔和密克隆', 'Saint Pierre and Miquelon', 'PM', 'SPM', '666'),
(188, '圣文森特和格林纳丁斯', 'Saint Vincent and the Grenadines', 'VC', 'VCT', '670'),
(189, '萨摩亚', 'Samoa', 'WS', 'WSM', '882'),
(190, '圣马力诺', 'San Marino', 'SM', 'SMR', '674'),
(191, '圣多美和普林西比', 'Sao Tome and Principe', 'ST', 'STP', '678'),
(192, '沙特阿拉伯', 'Saudi Arabia', 'SA', 'SAU', '682'),
(193, '塞内加尔', 'Senegal', 'SN', 'SEN', '686'),
(194, '塞尔维亚', 'Serbia', 'RS', 'SRB', '688'),
(195, '塞舌尔', 'Seychelles', 'SC', 'SYC', '690'),
(196, '塞拉利昂', 'Sierra Leone', 'SL', 'SLE', '694'),
(197, '新加坡', 'Singapore', 'SG', 'SGP', '702'),
(198, '斯洛伐克', 'Slovakia', 'SK', 'SVK', '703'),
(199, '斯洛文尼亚', 'Slovenia', 'SI', 'SVN', '705'),
(200, '所罗门群岛', 'Solomon Islands (the)', 'SB', 'SLB', '090'),
(201, '索马里', 'Somalia', 'SO', 'SOM', '706'),
(202, '南非', 'South Africa', 'ZA', 'ZAF', '710'),
(203, '南乔治亚岛和南桑德韦奇岛', 'South Georgia and the South Sandwich Islands', 'GS', 'SGS', '239'),
(204, '西班牙', 'Spain', 'ES', 'ESP', '724'),
(205, '斯里兰卡', 'Sri Lanka', 'LK', 'LKA', '144'),
(206, '苏丹', 'Sudan (the)', 'SD', 'SDN', '736'),
(207, '苏里南', 'Suriname', 'SR', 'SUR', '740'),
(208, '斯瓦尔巴岛和扬马延岛', 'Svalbard and Jan Mayen', 'SJ', 'SJM', '744'),
(209, '斯威士兰', 'Swaziland', 'SZ', 'SWZ', '748'),
(210, '瑞典', 'Sweden', 'SE', 'SWE', '752'),
(211, '瑞士', 'Switzerland', 'CH', 'CHE', '756'),
(212, '叙利亚', 'Syrian Arab Republic (the)', 'SY', 'SYR', '760'),
(213, '台湾', 'Taiwan (Province of China)', 'TW', 'TWN', '158'),
(214, '塔吉克斯坦', 'Tajikistan', 'TJ', 'TJK', '762'),
(215, '坦桑尼亚', 'Tanzania,United Republic of', 'TZ', 'TZA', '834'),
(216, '泰国', 'Thailand', 'TH', 'THA', '764'),
(217, '东帝汶', 'Timor-Leste', 'TL', 'TLS', '626'),
(218, '多哥', 'Togo', 'TG', 'TGO', '768'),
(219, '托克劳', 'Tokelau', 'TK', 'TKL', '772'),
(220, '汤加', 'Tonga', 'TO', 'TON', '776'),
(221, '特立尼达和多巴哥', 'Trinidad and Tobago', 'TT', 'TTO', '780'),
(222, '突尼斯', 'Tunisia', 'TN', 'TUN', '788'),
(223, '土耳其', 'Turkey', 'TR', 'TUR', '792'),
(224, '土库曼斯坦', 'Turkmenistan', 'TM', 'TKM', '795'),
(225, '特克斯和凯科斯群岛', 'Turks and Caicos Islands (the)', 'TC', 'TCA', '796'),
(226, '图瓦卢', 'Tuvalu', 'TV', 'TUV', '798'),
(227, '乌干达', 'Uganda', 'UG', 'UGA', '800'),
(228, '乌克兰', 'Ukraine', 'UA', 'UKR', '804'),
(229, '阿联酋', 'United Arab Emirates (the)', 'AE', 'ARE', '784'),
(230, '英国', 'United Kingdom (the)', 'GB', 'GBR', '826'),
(231, '美国', 'United States (the)', 'US', 'USA', '840'),
(232, '美国本土外小岛屿', 'United States Minor Outlying Islands (the)', 'UM', 'UMI', '581'),
(233, '乌拉圭', 'Uruguay', 'UY', 'URY', '858'),
(234, '乌兹别克斯坦', 'Uzbekistan', 'UZ', 'UZB', '860'),
(235, '瓦努阿图', 'Vanuatu', 'VU', 'VUT', '548'),
(236, '委内瑞拉', 'Venezuela', 'VE', 'VEN', '862'),
(237, '越南', 'Viet Nam', 'VN', 'VNM', '704'),
(238, '英属维尔京群岛', 'Virgin Islands (British)', 'VG', 'VGB', '092'),
(239, '美属维尔京群岛', 'Virgin Islands (U.S.)', 'VI', 'VIR', '850'),
(240, '瓦利斯和富图纳', 'Wallis and Futuna', 'WF', 'WLF', '876'),
(241, '西撒哈拉', 'Western Sahara', 'EH', 'ESH', '732'),
(242, '也门', 'Yemen', 'YE', 'YEM', '887'),
(243, '赞比亚', 'Zambia', 'ZM', 'ZMB', '894'),
(244, '津巴布韦', 'Zimbabwe', 'ZW', 'ZWE', '716');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_count_active`
--

CREATE TABLE `ocenter_count_active` (
  `id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL COMMENT '类型:''day'',''week'',''month''',
  `date` int(11) NOT NULL,
  `num` int(11) NOT NULL COMMENT '活跃人数',
  `total` int(11) NOT NULL COMMENT '总人数'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='活跃统计表';

--
-- 转存表中的数据 `ocenter_count_active`
--

INSERT INTO `ocenter_count_active` (`id`, `type`, `date`, `num`, `total`) VALUES
(1, 'day', 1505923200, 0, 0),
(2, 'day', 1506096000, 0, 0),
(3, 'week', 1505577607, 1, 0),
(4, 'day', 1506182400, 1, 0),
(5, 'day', 1506268800, 1, 0),
(6, 'day', 1506355200, 1, 0),
(7, 'day', 1506441600, 2, 0),
(8, 'day', 1506528000, 2, 0),
(9, 'day', 1506614400, 2, 0),
(10, 'day', 1506700800, 3, 0),
(11, 'week', 1506182407, 3, 0),
(12, 'month', 1504195230, 3, 0),
(13, 'day', 1506960000, 0, 0),
(14, 'day', 1507219200, 0, 0),
(15, 'day', 1507305600, 2, 0),
(16, 'week', 1506787207, 4, 0),
(17, 'day', 1507392000, 2, 0),
(18, 'day', 1507478400, 2, 0),
(19, 'day', 1507564800, 2, 0),
(20, 'day', 1507651200, 2, 0),
(21, 'day', 1507737600, 3, 0),
(22, 'day', 1507824000, 2, 0),
(23, 'day', 1507910400, 2, 0),
(24, 'week', 1507392007, 3, 0),
(25, 'day', 1507996800, 7, 0),
(26, 'day', 1508083200, 3, 0),
(27, 'day', 1508169600, 2, 0),
(28, 'day', 1508256000, 1, 0),
(29, 'day', 1508342400, 0, 0),
(30, 'day', 1508428800, 2, 0),
(31, 'day', 1508515200, 1, 0),
(32, 'week', 1507996807, 9, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_count_lost`
--

CREATE TABLE `ocenter_count_lost` (
  `id` int(11) NOT NULL,
  `user_num` int(11) NOT NULL COMMENT '用户总数',
  `new_lost` int(11) NOT NULL COMMENT '新增流失用户数',
  `date` int(11) NOT NULL COMMENT '日期，时间戳形式',
  `lost_num` int(11) NOT NULL COMMENT '流失用户数',
  `rate` decimal(6,4) NOT NULL COMMENT '比率0~1之间，小数点四位的小数',
  `create_time` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户流失率统计';

--
-- 转存表中的数据 `ocenter_count_lost`
--

INSERT INTO `ocenter_count_lost` (`id`, `user_num`, `new_lost`, `date`, `lost_num`, `rate`, `create_time`) VALUES
(1, 1, 0, 1505923200, 0, '0.0000', 1506049156),
(2, 1, 0, 1506096000, 0, '0.0000', 1506217377),
(3, 1, 0, 1506182400, 0, '0.0000', 1506298403),
(4, 1, 0, 1506268800, 0, '0.0000', 1506389059),
(5, 1, 0, 1506355200, 0, '0.0000', 1506474434),
(6, 3, 1, 1506441600, 1, '0.3333', 1506560304),
(7, 3, 0, 1506528000, 1, '0.3333', 1506646017),
(8, 3, 0, 1506614400, 1, '0.3333', 1506729302),
(9, 3, -1, 1506700800, 0, '0.0000', 1506809151),
(10, 3, 0, 1506960000, 0, '0.0000', 1507074123),
(11, 4, 0, 1507219200, 1, '0.2500', 1507334848),
(12, 2, -1, 1507305600, 0, '0.0000', 1507420100),
(13, 2, 0, 1507392000, 0, '0.0000', 1507478620),
(14, 2, 0, 1507478400, 0, '0.0000', 1507589833),
(15, 2, 0, 1507564800, 0, '0.0000', 1507677371),
(16, 2, 0, 1507651200, 0, '0.0000', 1507774288),
(17, 3, 0, 1507737600, 0, '0.0000', 1507846795),
(18, 3, 0, 1507824000, 0, '0.0000', 1507939309),
(19, 3, 0, 1507910400, 0, '0.0000', 1508025598),
(20, 2, 0, 1507996800, 0, '0.0000', 1508112556),
(21, 4, 0, 1508083200, 0, '0.0000', 1508192523),
(22, 4, 0, 1508169600, 0, '0.0000', 1508288244),
(23, 4, 0, 1508256000, 0, '0.0000', 1508380056),
(24, 4, 0, 1508342400, 0, '0.0000', 1508457145),
(25, 4, 0, 1508428800, 0, '0.0000', 1508545852),
(26, 4, 0, 1508515200, 0, '0.0000', 1508630988);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_count_remain`
--

CREATE TABLE `ocenter_count_remain` (
  `id` int(11) NOT NULL,
  `date` int(11) NOT NULL COMMENT '日期',
  `day1_num` int(11) NOT NULL COMMENT '第一天登录人数',
  `day2_num` int(11) NOT NULL COMMENT '第二天登录人数',
  `day3_num` int(11) NOT NULL,
  `day4_num` int(11) NOT NULL,
  `day5_num` int(11) NOT NULL,
  `day6_num` int(11) NOT NULL,
  `day7_num` int(11) NOT NULL,
  `day8_num` int(11) NOT NULL,
  `reg_num` int(11) NOT NULL COMMENT '当天注册人数'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='留存率统计表';

--
-- 转存表中的数据 `ocenter_count_remain`
--

INSERT INTO `ocenter_count_remain` (`id`, `date`, `day1_num`, `day2_num`, `day3_num`, `day4_num`, `day5_num`, `day6_num`, `day7_num`, `day8_num`, `reg_num`) VALUES
(1, 1505836800, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(2, 1505750400, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(3, 1505664000, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 1505577600, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(5, 1505491200, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(6, 1505404800, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(7, 1505318400, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(8, 1505232000, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(9, 1506009600, 0, 1, 1, 1, 1, 1, 1, 1, 1),
(10, 1505923200, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(11, 1506096000, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(12, 1506182400, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(13, 1506268800, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(14, 1506355200, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(15, 1506441600, 1, 1, 1, 0, 0, 0, 0, 0, 2),
(16, 1506528000, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(17, 1506614400, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(18, 1506873600, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(19, 1506787200, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(20, 1506700800, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(21, 1507132800, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(22, 1507046400, 0, 0, 0, 0, 0, 0, 0, 0, 2),
(23, 1506960000, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(24, 1507219200, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(25, 1507305600, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(26, 1507392000, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(27, 1507478400, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(28, 1507564800, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(29, 1507651200, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(30, 1507737600, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(31, 1507824000, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(32, 1507910400, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(33, 1507996800, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(34, 1508083200, 1, 1, 0, 1, 0, 0, 0, 0, 2),
(35, 1508169600, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(36, 1508256000, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(37, 1508342400, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(38, 1508428800, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_currency`
--

CREATE TABLE `ocenter_currency` (
  `id` smallint(4) NOT NULL,
  `code` varchar(5) NOT NULL,
  `rate` decimal(10,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_currency`
--

INSERT INTO `ocenter_currency` (`id`, `code`, `rate`) VALUES
(1, 'AFN', '68.0000'),
(2, 'ALL', '112.4000'),
(3, 'AMD', '481.2300'),
(4, 'ANG', '1.7825'),
(5, 'AOA', '165.0970'),
(6, 'ARS', '17.4390'),
(7, 'AUD', '1.2735'),
(8, 'AWG', '1.7800'),
(9, 'AZN', '1.6996'),
(10, 'BAM', '1.6595'),
(11, 'BBD', '2.0000'),
(12, 'BDT', '82.3900'),
(13, 'BGN', '1.6527'),
(14, 'BHD', '0.3771'),
(15, 'BIF', '1738.9700'),
(16, 'BMD', '1.0000'),
(17, 'BND', '1.3595'),
(18, 'BOB', '6.8500'),
(19, 'BRL', '3.1663'),
(20, 'BSD', '1.0000'),
(21, 'BTN', '65.0250'),
(22, 'BWP', '10.3256'),
(23, 'BYR', '20020.0000'),
(24, 'BZD', '1.9977'),
(25, 'CAD', '1.2508'),
(26, 'CDF', '1565.5000'),
(27, 'CHF', '0.9813'),
(28, 'CLF', '0.0232'),
(29, 'CLP', '624.6500'),
(30, 'CNH', '6.6189'),
(31, 'CNY', '6.6169'),
(32, 'COP', '2917.8000'),
(33, 'CRC', '563.0000'),
(34, 'CUP', '1.0000'),
(35, 'CVE', '93.4400'),
(36, 'CYP', '0.0000'),
(37, 'CZK', '21.7954'),
(38, 'DEM', '1.7175'),
(39, 'DJF', '177.7400'),
(40, 'DKK', '6.3080'),
(41, 'DOP', '47.6000'),
(42, 'DZD', '113.9090'),
(43, 'ECS', '25000.0000'),
(44, 'EGP', '17.6000'),
(45, 'ERN', '14.9900'),
(46, 'ETB', '26.9100'),
(47, 'EUR', '0.8470'),
(48, 'FJD', '2.0525'),
(49, 'FKP', '0.7623'),
(50, 'FRF', '1700.2722'),
(51, 'GBP', '0.7617'),
(52, 'GEL', '2.4763'),
(53, 'GHS', '4.3885'),
(54, 'GIP', '0.7625'),
(55, 'GMD', '47.1000'),
(56, 'GNF', '8995.0000'),
(57, 'GTQ', '7.3435'),
(58, 'GYD', '204.5000'),
(59, 'HKD', '7.8024'),
(60, 'HNL', '23.3800'),
(61, 'HRK', '6.3555'),
(62, 'HTG', '61.3100'),
(63, 'HUF', '260.6900'),
(64, 'IDR', '13522.0000'),
(65, 'IEP', '0.6992'),
(66, 'ILS', '3.4901'),
(67, 'INR', '65.0200'),
(68, 'IQD', '1167.0000'),
(69, 'IRR', '34299.0000'),
(70, 'ISK', '104.9500'),
(71, 'ITL', '1700.2722'),
(72, 'JMD', '126.9900'),
(73, 'JOD', '0.7075'),
(74, 'JPY', '113.3130'),
(75, 'KES', '103.0500'),
(76, 'KGS', '68.6200'),
(77, 'KHR', '4032.0000'),
(78, 'KMF', '417.8700'),
(79, 'KPW', '900.0000'),
(80, 'KRW', '1130.5900'),
(81, 'KWD', '0.3019'),
(82, 'KYD', '0.8200'),
(83, 'KZT', '335.2700'),
(84, 'LAK', '8300.0000'),
(85, 'LBP', '1511.0000'),
(86, 'LKR', '153.6000'),
(87, 'LRD', '118.0700'),
(88, 'LSL', '13.5900'),
(89, 'LTL', '3.0487'),
(90, 'LVL', '0.6205'),
(91, 'LYD', '1.3663'),
(92, 'MAD', '9.4130'),
(93, 'MDL', '17.2650'),
(94, 'MGA', '3050.0000'),
(95, 'MKD', '51.8900'),
(96, 'MMK', '1359.0000'),
(97, 'MNT', '2446.0000'),
(98, 'MOP', '8.0361'),
(99, 'MRO', '351.3200'),
(100, 'MUR', '33.8500'),
(101, 'MVR', '15.5700'),
(102, 'MWK', '713.5000'),
(103, 'MXN', '18.8668'),
(104, 'MXV', '3.2293'),
(105, 'MYR', '4.2260'),
(106, 'MZN', '60.2000'),
(107, 'NAD', '13.6200'),
(108, 'NGN', '352.0000'),
(109, 'NIO', '30.4955'),
(110, 'NOK', '7.9794'),
(111, 'NPR', '103.5500'),
(112, 'NZD', '1.4298'),
(113, 'OMR', '0.3842'),
(114, 'PAB', '1.0000'),
(115, 'PEN', '3.2293'),
(116, 'PGK', '3.1975'),
(117, 'PHP', '51.4800'),
(118, 'PKR', '105.3000'),
(119, 'PLN', '3.5966'),
(120, 'PYG', '5639.0000'),
(121, 'QAR', '3.7798'),
(122, 'RON', '3.8912'),
(123, 'RSD', '100.6417'),
(124, 'RUB', '57.5080'),
(125, 'RWF', '830.4800'),
(126, 'SAR', '3.7500'),
(127, 'SBD', '7.7735'),
(128, 'SCR', '13.2800'),
(129, 'SDG', '6.6599'),
(130, 'SEK', '8.1599'),
(131, 'SGD', '1.3603'),
(132, 'SHP', '0.7625'),
(133, 'SIT', '216.4868'),
(134, 'SLL', '7620.0000'),
(135, 'SOS', '557.0000'),
(136, 'SRD', '7.3700'),
(137, 'STD', '20762.6992'),
(138, 'SVC', '8.7500'),
(139, 'SYP', '514.9800'),
(140, 'SZL', '13.6270'),
(141, 'THB', '33.1500'),
(142, 'TJS', '8.8075'),
(143, 'TMT', '3.4000'),
(144, 'TND', '2.4800'),
(145, 'TOP', '2.2351'),
(146, 'TRY', '3.6659'),
(147, 'TTD', '6.6295'),
(148, 'TWD', '30.2490'),
(149, 'TZS', '2239.0000'),
(150, 'UAH', '26.4900'),
(151, 'UGX', '3647.0000'),
(152, 'USD', '1.0000'),
(153, 'UYU', '29.4800'),
(154, 'UZS', '8035.0000'),
(155, 'VEF', '9.9750'),
(156, 'VND', '22717.0000'),
(157, 'VUV', '104.2900'),
(158, 'WST', '2.5356'),
(159, 'XAF', '555.3100'),
(160, 'XAG', '0.0584'),
(161, 'XAU', '0.0008'),
(162, 'XCD', '2.7000'),
(163, 'XCP', '0.3133'),
(164, 'XDR', '0.7080'),
(165, 'XOF', '555.8000'),
(166, 'XPD', '0.0010'),
(167, 'XPF', '100.5500'),
(168, 'XPT', '0.0011'),
(169, 'YER', '249.9500'),
(170, 'ZAR', '13.6288'),
(171, 'ZMW', '9.7300'),
(172, 'ZWL', '322.3550');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_district`
--

CREATE TABLE `ocenter_district` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `level` tinyint(4) UNSIGNED NOT NULL DEFAULT '0',
  `upid` mediumint(8) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='中国省市区乡镇数据表';

--
-- 转存表中的数据 `ocenter_district`
--

INSERT INTO `ocenter_district` (`id`, `name`, `level`, `upid`) VALUES
(110000, '北京市', 1, 0),
(120000, '天津市', 1, 0),
(130000, '河北省', 1, 0),
(140000, '山西省', 1, 0),
(150000, '内蒙古', 1, 0),
(210000, '辽宁省', 1, 0),
(220000, '吉林省', 1, 0),
(230000, '黑龙江', 1, 0),
(310000, '上海市', 1, 0),
(320000, '江苏省', 1, 0),
(330000, '浙江省', 1, 0),
(340000, '安徽省', 1, 0),
(350000, '福建省', 1, 0),
(360000, '江西省', 1, 0),
(370000, '山东省', 1, 0),
(410000, '河南省', 1, 0),
(420000, '湖北省', 1, 0),
(430000, '湖南省', 1, 0),
(440000, '广东省', 1, 0),
(450000, '广西省', 1, 0),
(460000, '海南省', 1, 0),
(500000, '重庆市', 1, 0),
(510000, '四川省', 1, 0),
(520000, '贵州省', 1, 0),
(530000, '云南省', 1, 0),
(540000, '西　藏', 1, 0),
(610000, '陕西省', 1, 0),
(620000, '甘肃省', 1, 0),
(630000, '青海省', 1, 0),
(640000, '宁　夏', 1, 0),
(650000, '新　疆', 1, 0),
(710000, '台湾省', 1, 0),
(810000, '香　港', 1, 0),
(820000, '澳　门', 1, 0),
(110100, '北京市', 2, 110000),
(110200, '县', 2, 110000),
(120100, '市辖区', 2, 120000),
(120200, '县', 2, 120000),
(130100, '石家庄市', 2, 130000),
(130200, '唐山市', 2, 130000),
(130300, '秦皇岛市', 2, 130000),
(130400, '邯郸市', 2, 130000),
(130500, '邢台市', 2, 130000),
(130600, '保定市', 2, 130000),
(130700, '张家口市', 2, 130000),
(130800, '承德市', 2, 130000),
(130900, '沧州市', 2, 130000),
(131000, '廊坊市', 2, 130000),
(131100, '衡水市', 2, 130000),
(140100, '太原市', 2, 140000),
(140200, '大同市', 2, 140000),
(140300, '阳泉市', 2, 140000),
(140400, '长治市', 2, 140000),
(140500, '晋城市', 2, 140000),
(140600, '朔州市', 2, 140000),
(140700, '晋中市', 2, 140000),
(140800, '运城市', 2, 140000),
(140900, '忻州市', 2, 140000),
(141000, '临汾市', 2, 140000),
(141100, '吕梁市', 2, 140000),
(150100, '呼和浩特市', 2, 150000),
(150200, '包头市', 2, 150000),
(150300, '乌海市', 2, 150000),
(150400, '赤峰市', 2, 150000),
(150500, '通辽市', 2, 150000),
(150600, '鄂尔多斯市', 2, 150000),
(150700, '呼伦贝尔市', 2, 150000),
(150800, '巴彦淖尔市', 2, 150000),
(150900, '乌兰察布市', 2, 150000),
(152200, '兴安盟', 2, 150000),
(152500, '锡林郭勒盟', 2, 150000),
(152900, '阿拉善盟', 2, 150000),
(210100, '沈阳市', 2, 210000),
(210200, '大连市', 2, 210000),
(210300, '鞍山市', 2, 210000),
(210400, '抚顺市', 2, 210000),
(210500, '本溪市', 2, 210000),
(210600, '丹东市', 2, 210000),
(210700, '锦州市', 2, 210000),
(210800, '营口市', 2, 210000),
(210900, '阜新市', 2, 210000),
(211000, '辽阳市', 2, 210000),
(211100, '盘锦市', 2, 210000),
(211200, '铁岭市', 2, 210000),
(211300, '朝阳市', 2, 210000),
(211400, '葫芦岛市', 2, 210000),
(220100, '长春市', 2, 220000),
(220200, '吉林市', 2, 220000),
(220300, '四平市', 2, 220000),
(220400, '辽源市', 2, 220000),
(220500, '通化市', 2, 220000),
(220600, '白山市', 2, 220000),
(220700, '松原市', 2, 220000),
(220800, '白城市', 2, 220000),
(222400, '延边朝鲜族自治州', 2, 220000),
(230100, '哈尔滨市', 2, 230000),
(230200, '齐齐哈尔市', 2, 230000),
(230300, '鸡西市', 2, 230000),
(230400, '鹤岗市', 2, 230000),
(230500, '双鸭山市', 2, 230000),
(230600, '大庆市', 2, 230000),
(230700, '伊春市', 2, 230000),
(230800, '佳木斯市', 2, 230000),
(230900, '七台河市', 2, 230000),
(231000, '牡丹江市', 2, 230000),
(231100, '黑河市', 2, 230000),
(231200, '绥化市', 2, 230000),
(232700, '大兴安岭地区', 2, 230000),
(310100, '市辖区', 2, 310000),
(310200, '县', 2, 310000),
(320100, '南京市', 2, 320000),
(320200, '无锡市', 2, 320000),
(320300, '徐州市', 2, 320000),
(320400, '常州市', 2, 320000),
(320500, '苏州市', 2, 320000),
(320600, '南通市', 2, 320000),
(320700, '连云港市', 2, 320000),
(320800, '淮安市', 2, 320000),
(320900, '盐城市', 2, 320000),
(321000, '扬州市', 2, 320000),
(321100, '镇江市', 2, 320000),
(321200, '泰州市', 2, 320000),
(321300, '宿迁市', 2, 320000),
(330100, '杭州市', 2, 330000),
(330200, '宁波市', 2, 330000),
(330300, '温州市', 2, 330000),
(330400, '嘉兴市', 2, 330000),
(330500, '湖州市', 2, 330000),
(330600, '绍兴市', 2, 330000),
(330700, '金华市', 2, 330000),
(330800, '衢州市', 2, 330000),
(330900, '舟山市', 2, 330000),
(331000, '台州市', 2, 330000),
(331100, '丽水市', 2, 330000),
(340100, '合肥市', 2, 340000),
(340200, '芜湖市', 2, 340000),
(340300, '蚌埠市', 2, 340000),
(340400, '淮南市', 2, 340000),
(340500, '马鞍山市', 2, 340000),
(340600, '淮北市', 2, 340000),
(340700, '铜陵市', 2, 340000),
(340800, '安庆市', 2, 340000),
(341000, '黄山市', 2, 340000),
(341100, '滁州市', 2, 340000),
(341200, '阜阳市', 2, 340000),
(341300, '宿州市', 2, 340000),
(341500, '六安市', 2, 340000),
(341600, '亳州市', 2, 340000),
(341700, '池州市', 2, 340000),
(341800, '宣城市', 2, 340000),
(350100, '福州市', 2, 350000),
(350200, '厦门市', 2, 350000),
(350300, '莆田市', 2, 350000),
(350400, '三明市', 2, 350000),
(350500, '泉州市', 2, 350000),
(350600, '漳州市', 2, 350000),
(350700, '南平市', 2, 350000),
(350800, '龙岩市', 2, 350000),
(350900, '宁德市', 2, 350000),
(360100, '南昌市', 2, 360000),
(360200, '景德镇市', 2, 360000),
(360300, '萍乡市', 2, 360000),
(360400, '九江市', 2, 360000),
(360500, '新余市', 2, 360000),
(360600, '鹰潭市', 2, 360000),
(360700, '赣州市', 2, 360000),
(360800, '吉安市', 2, 360000),
(360900, '宜春市', 2, 360000),
(361000, '抚州市', 2, 360000),
(361100, '上饶市', 2, 360000),
(370100, '济南市', 2, 370000),
(370200, '青岛市', 2, 370000),
(370300, '淄博市', 2, 370000),
(370400, '枣庄市', 2, 370000),
(370500, '东营市', 2, 370000),
(370600, '烟台市', 2, 370000),
(370700, '潍坊市', 2, 370000),
(370800, '济宁市', 2, 370000),
(370900, '泰安市', 2, 370000),
(371000, '威海市', 2, 370000),
(371100, '日照市', 2, 370000),
(371200, '莱芜市', 2, 370000),
(371300, '临沂市', 2, 370000),
(371400, '德州市', 2, 370000),
(371500, '聊城市', 2, 370000),
(371600, '滨州市', 2, 370000),
(371700, '菏泽市', 2, 370000),
(410100, '郑州市', 2, 410000),
(410200, '开封市', 2, 410000),
(410300, '洛阳市', 2, 410000),
(410400, '平顶山市', 2, 410000),
(410500, '安阳市', 2, 410000),
(410600, '鹤壁市', 2, 410000),
(410700, '新乡市', 2, 410000),
(410800, '焦作市', 2, 410000),
(410900, '濮阳市', 2, 410000),
(411000, '许昌市', 2, 410000),
(411100, '漯河市', 2, 410000),
(411200, '三门峡市', 2, 410000),
(411300, '南阳市', 2, 410000),
(411400, '商丘市', 2, 410000),
(411500, '信阳市', 2, 410000),
(411600, '周口市', 2, 410000),
(411700, '驻马店市', 2, 410000),
(420100, '武汉市', 2, 420000),
(420200, '黄石市', 2, 420000),
(420300, '十堰市', 2, 420000),
(420500, '宜昌市', 2, 420000),
(420600, '襄樊市', 2, 420000),
(420700, '鄂州市', 2, 420000),
(420800, '荆门市', 2, 420000),
(420900, '孝感市', 2, 420000),
(421000, '荆州市', 2, 420000),
(421100, '黄冈市', 2, 420000),
(421200, '咸宁市', 2, 420000),
(421300, '随州市', 2, 420000),
(422800, '恩施土家族苗族自治州', 2, 420000),
(429000, '省直辖行政单位', 2, 420000),
(430100, '长沙市', 2, 430000),
(430200, '株洲市', 2, 430000),
(430300, '湘潭市', 2, 430000),
(430400, '衡阳市', 2, 430000),
(430500, '邵阳市', 2, 430000),
(430600, '岳阳市', 2, 430000),
(430700, '常德市', 2, 430000),
(430800, '张家界市', 2, 430000),
(430900, '益阳市', 2, 430000),
(431000, '郴州市', 2, 430000),
(431100, '永州市', 2, 430000),
(431200, '怀化市', 2, 430000),
(431300, '娄底市', 2, 430000),
(433100, '湘西土家族苗族自治州', 2, 430000),
(440100, '广州市', 2, 440000),
(440200, '韶关市', 2, 440000),
(440300, '深圳市', 2, 440000),
(440400, '珠海市', 2, 440000),
(440500, '汕头市', 2, 440000),
(440600, '佛山市', 2, 440000),
(440700, '江门市', 2, 440000),
(440800, '湛江市', 2, 440000),
(440900, '茂名市', 2, 440000),
(441200, '肇庆市', 2, 440000),
(441300, '惠州市', 2, 440000),
(441400, '梅州市', 2, 440000),
(441500, '汕尾市', 2, 440000),
(441600, '河源市', 2, 440000),
(441700, '阳江市', 2, 440000),
(441800, '清远市', 2, 440000),
(441900, '东莞市', 2, 440000),
(442000, '中山市', 2, 440000),
(445100, '潮州市', 2, 440000),
(445200, '揭阳市', 2, 440000),
(445300, '云浮市', 2, 440000),
(450100, '南宁市', 2, 450000),
(450200, '柳州市', 2, 450000),
(450300, '桂林市', 2, 450000),
(450400, '梧州市', 2, 450000),
(450500, '北海市', 2, 450000),
(450600, '防城港市', 2, 450000),
(450700, '钦州市', 2, 450000),
(450800, '贵港市', 2, 450000),
(450900, '玉林市', 2, 450000),
(451000, '百色市', 2, 450000),
(451100, '贺州市', 2, 450000),
(451200, '河池市', 2, 450000),
(451300, '来宾市', 2, 450000),
(451400, '崇左市', 2, 450000),
(460100, '海口市', 2, 460000),
(460200, '三亚市', 2, 460000),
(469000, '省直辖县级行政单位', 2, 460000),
(500100, '市辖区', 2, 500000),
(500200, '县', 2, 500000),
(500300, '市', 2, 500000),
(510100, '成都市', 2, 510000),
(510300, '自贡市', 2, 510000),
(510400, '攀枝花市', 2, 510000),
(510500, '泸州市', 2, 510000),
(510600, '德阳市', 2, 510000),
(510700, '绵阳市', 2, 510000),
(510800, '广元市', 2, 510000),
(510900, '遂宁市', 2, 510000),
(511000, '内江市', 2, 510000),
(511100, '乐山市', 2, 510000),
(511300, '南充市', 2, 510000),
(511400, '眉山市', 2, 510000),
(511500, '宜宾市', 2, 510000),
(511600, '广安市', 2, 510000),
(511700, '达州市', 2, 510000),
(511800, '雅安市', 2, 510000),
(511900, '巴中市', 2, 510000),
(512000, '资阳市', 2, 510000),
(513200, '阿坝藏族羌族自治州', 2, 510000),
(513300, '甘孜藏族自治州', 2, 510000),
(513400, '凉山彝族自治州', 2, 510000),
(520100, '贵阳市', 2, 520000),
(520200, '六盘水市', 2, 520000),
(520300, '遵义市', 2, 520000),
(520400, '安顺市', 2, 520000),
(522200, '铜仁地区', 2, 520000),
(522300, '黔西南布依族苗族自治州', 2, 520000),
(522400, '毕节地区', 2, 520000),
(522600, '黔东南苗族侗族自治州', 2, 520000),
(522700, '黔南布依族苗族自治州', 2, 520000),
(530100, '昆明市', 2, 530000),
(530300, '曲靖市', 2, 530000),
(530400, '玉溪市', 2, 530000),
(530500, '保山市', 2, 530000),
(530600, '昭通市', 2, 530000),
(530700, '丽江市', 2, 530000),
(530800, '思茅市', 2, 530000),
(530900, '临沧市', 2, 530000),
(532300, '楚雄彝族自治州', 2, 530000),
(532500, '红河哈尼族彝族自治州', 2, 530000),
(532600, '文山壮族苗族自治州', 2, 530000),
(532800, '西双版纳傣族自治州', 2, 530000),
(532900, '大理白族自治州', 2, 530000),
(533100, '德宏傣族景颇族自治州', 2, 530000),
(533300, '怒江傈僳族自治州', 2, 530000),
(533400, '迪庆藏族自治州', 2, 530000),
(540100, '拉萨市', 2, 540000),
(542100, '昌都地区', 2, 540000),
(542200, '山南地区', 2, 540000),
(542300, '日喀则地区', 2, 540000),
(542400, '那曲地区', 2, 540000),
(542500, '阿里地区', 2, 540000),
(542600, '林芝地区', 2, 540000),
(610100, '西安市', 2, 610000),
(610200, '铜川市', 2, 610000),
(610300, '宝鸡市', 2, 610000),
(610400, '咸阳市', 2, 610000),
(610500, '渭南市', 2, 610000),
(610600, '延安市', 2, 610000),
(610700, '汉中市', 2, 610000),
(610800, '榆林市', 2, 610000),
(610900, '安康市', 2, 610000),
(611000, '商洛市', 2, 610000),
(620100, '兰州市', 2, 620000),
(620200, '嘉峪关市', 2, 620000),
(620300, '金昌市', 2, 620000),
(620400, '白银市', 2, 620000),
(620500, '天水市', 2, 620000),
(620600, '武威市', 2, 620000),
(620700, '张掖市', 2, 620000),
(620800, '平凉市', 2, 620000),
(620900, '酒泉市', 2, 620000),
(621000, '庆阳市', 2, 620000),
(621100, '定西市', 2, 620000),
(621200, '陇南市', 2, 620000),
(622900, '临夏回族自治州', 2, 620000),
(623000, '甘南藏族自治州', 2, 620000),
(630100, '西宁市', 2, 630000),
(632100, '海东地区', 2, 630000),
(632200, '海北藏族自治州', 2, 630000),
(632300, '黄南藏族自治州', 2, 630000),
(632500, '海南藏族自治州', 2, 630000),
(632600, '果洛藏族自治州', 2, 630000),
(632700, '玉树藏族自治州', 2, 630000),
(632800, '海西蒙古族藏族自治州', 2, 630000),
(640100, '银川市', 2, 640000),
(640200, '石嘴山市', 2, 640000),
(640300, '吴忠市', 2, 640000),
(640400, '固原市', 2, 640000),
(640500, '中卫市', 2, 640000),
(650100, '乌鲁木齐市', 2, 650000),
(650200, '克拉玛依市', 2, 650000),
(652100, '吐鲁番地区', 2, 650000),
(652200, '哈密地区', 2, 650000),
(652300, '昌吉回族自治州', 2, 650000),
(652700, '博尔塔拉蒙古自治州', 2, 650000),
(652800, '巴音郭楞蒙古自治州', 2, 650000),
(652900, '阿克苏地区', 2, 650000),
(653000, '克孜勒苏柯尔克孜自治州', 2, 650000),
(653100, '喀什地区', 2, 650000),
(653200, '和田地区', 2, 650000),
(654000, '伊犁哈萨克自治州', 2, 650000),
(654200, '塔城地区', 2, 650000),
(654300, '阿勒泰地区', 2, 650000),
(659000, '省直辖行政单位', 2, 650000),
(110101, '东城区', 3, 110100),
(110102, '西城区', 3, 110100),
(110103, '崇文区', 3, 110100),
(110104, '宣武区', 3, 110100),
(110105, '朝阳区', 3, 110100),
(110106, '丰台区', 3, 110100),
(110107, '石景山区', 3, 110100),
(110108, '海淀区', 3, 110100),
(110109, '门头沟区', 3, 110100),
(110111, '房山区', 3, 110100),
(110112, '通州区', 3, 110100),
(110113, '顺义区', 3, 110100),
(110114, '昌平区', 3, 110100),
(110115, '大兴区', 3, 110100),
(110116, '怀柔区', 3, 110100),
(110117, '平谷区', 3, 110100),
(110228, '密云县', 3, 110200),
(110229, '延庆县', 3, 110200),
(120101, '和平区', 3, 120100),
(120102, '河东区', 3, 120100),
(120103, '河西区', 3, 120100),
(120104, '南开区', 3, 120100),
(120105, '河北区', 3, 120100),
(120106, '红桥区', 3, 120100),
(120107, '塘沽区', 3, 120100),
(120108, '汉沽区', 3, 120100),
(120109, '大港区', 3, 120100),
(120110, '东丽区', 3, 120100),
(120111, '西青区', 3, 120100),
(120112, '津南区', 3, 120100),
(120113, '北辰区', 3, 120100),
(120114, '武清区', 3, 120100),
(120115, '宝坻区', 3, 120100),
(120221, '宁河县', 3, 120200),
(120223, '静海县', 3, 120200),
(120225, '蓟　县', 3, 120200),
(130101, '市辖区', 3, 130100),
(130102, '长安区', 3, 130100),
(130103, '桥东区', 3, 130100),
(130104, '桥西区', 3, 130100),
(130105, '新华区', 3, 130100),
(130107, '井陉矿区', 3, 130100),
(130108, '裕华区', 3, 130100),
(130121, '井陉县', 3, 130100),
(130123, '正定县', 3, 130100),
(130124, '栾城县', 3, 130100),
(130125, '行唐县', 3, 130100),
(130126, '灵寿县', 3, 130100),
(130127, '高邑县', 3, 130100),
(130128, '深泽县', 3, 130100),
(130129, '赞皇县', 3, 130100),
(130130, '无极县', 3, 130100),
(130131, '平山县', 3, 130100),
(130132, '元氏县', 3, 130100),
(130133, '赵　县', 3, 130100),
(130181, '辛集市', 3, 130100),
(130182, '藁城市', 3, 130100),
(130183, '晋州市', 3, 130100),
(130184, '新乐市', 3, 130100),
(130185, '鹿泉市', 3, 130100),
(130201, '市辖区', 3, 130200),
(130202, '路南区', 3, 130200),
(130203, '路北区', 3, 130200),
(130204, '古冶区', 3, 130200),
(130205, '开平区', 3, 130200),
(130207, '丰南区', 3, 130200),
(130208, '丰润区', 3, 130200),
(130223, '滦　县', 3, 130200),
(130224, '滦南县', 3, 130200),
(130225, '乐亭县', 3, 130200),
(130227, '迁西县', 3, 130200),
(130229, '玉田县', 3, 130200),
(130230, '唐海县', 3, 130200),
(130281, '遵化市', 3, 130200),
(130283, '迁安市', 3, 130200),
(130301, '市辖区', 3, 130300),
(130302, '海港区', 3, 130300),
(130303, '山海关区', 3, 130300),
(130304, '北戴河区', 3, 130300),
(130321, '青龙满族自治县', 3, 130300),
(130322, '昌黎县', 3, 130300),
(130323, '抚宁县', 3, 130300),
(130324, '卢龙县', 3, 130300),
(130401, '市辖区', 3, 130400),
(130402, '邯山区', 3, 130400),
(130403, '丛台区', 3, 130400),
(130404, '复兴区', 3, 130400),
(130406, '峰峰矿区', 3, 130400),
(130421, '邯郸县', 3, 130400),
(130423, '临漳县', 3, 130400),
(130424, '成安县', 3, 130400),
(130425, '大名县', 3, 130400),
(130426, '涉　县', 3, 130400),
(130427, '磁　县', 3, 130400),
(130428, '肥乡县', 3, 130400),
(130429, '永年县', 3, 130400),
(130430, '邱　县', 3, 130400),
(130431, '鸡泽县', 3, 130400),
(130432, '广平县', 3, 130400),
(130433, '馆陶县', 3, 130400),
(130434, '魏　县', 3, 130400),
(130435, '曲周县', 3, 130400),
(130481, '武安市', 3, 130400),
(130501, '市辖区', 3, 130500),
(130502, '桥东区', 3, 130500),
(130503, '桥西区', 3, 130500),
(130521, '邢台县', 3, 130500),
(130522, '临城县', 3, 130500),
(130523, '内丘县', 3, 130500),
(130524, '柏乡县', 3, 130500),
(130525, '隆尧县', 3, 130500),
(130526, '任　县', 3, 130500),
(130527, '南和县', 3, 130500),
(130528, '宁晋县', 3, 130500),
(130529, '巨鹿县', 3, 130500),
(130530, '新河县', 3, 130500),
(130531, '广宗县', 3, 130500),
(130532, '平乡县', 3, 130500),
(130533, '威　县', 3, 130500),
(130534, '清河县', 3, 130500),
(130535, '临西县', 3, 130500),
(130581, '南宫市', 3, 130500),
(130582, '沙河市', 3, 130500),
(130601, '市辖区', 3, 130600),
(130602, '新市区', 3, 130600),
(130603, '北市区', 3, 130600),
(130604, '南市区', 3, 130600),
(130621, '满城县', 3, 130600),
(130622, '清苑县', 3, 130600),
(130623, '涞水县', 3, 130600),
(130624, '阜平县', 3, 130600),
(130625, '徐水县', 3, 130600),
(130626, '定兴县', 3, 130600),
(130627, '唐　县', 3, 130600),
(130628, '高阳县', 3, 130600),
(130629, '容城县', 3, 130600),
(130630, '涞源县', 3, 130600),
(130631, '望都县', 3, 130600),
(130632, '安新县', 3, 130600),
(130633, '易　县', 3, 130600),
(130634, '曲阳县', 3, 130600),
(130635, '蠡　县', 3, 130600),
(130636, '顺平县', 3, 130600),
(130637, '博野县', 3, 130600),
(130638, '雄　县', 3, 130600),
(130681, '涿州市', 3, 130600),
(130682, '定州市', 3, 130600),
(130683, '安国市', 3, 130600),
(130684, '高碑店市', 3, 130600),
(130701, '市辖区', 3, 130700),
(130702, '桥东区', 3, 130700),
(130703, '桥西区', 3, 130700),
(130705, '宣化区', 3, 130700),
(130706, '下花园区', 3, 130700),
(130721, '宣化县', 3, 130700),
(130722, '张北县', 3, 130700),
(130723, '康保县', 3, 130700),
(130724, '沽源县', 3, 130700),
(130725, '尚义县', 3, 130700),
(130726, '蔚　县', 3, 130700),
(130727, '阳原县', 3, 130700),
(130728, '怀安县', 3, 130700),
(130729, '万全县', 3, 130700),
(130730, '怀来县', 3, 130700),
(130731, '涿鹿县', 3, 130700),
(130732, '赤城县', 3, 130700),
(130733, '崇礼县', 3, 130700),
(130801, '市辖区', 3, 130800),
(130802, '双桥区', 3, 130800),
(130803, '双滦区', 3, 130800),
(130804, '鹰手营子矿区', 3, 130800),
(130821, '承德县', 3, 130800),
(130822, '兴隆县', 3, 130800),
(130823, '平泉县', 3, 130800),
(130824, '滦平县', 3, 130800),
(130825, '隆化县', 3, 130800),
(130826, '丰宁满族自治县', 3, 130800),
(130827, '宽城满族自治县', 3, 130800),
(130828, '围场满族蒙古族自治县', 3, 130800),
(130901, '市辖区', 3, 130900),
(130902, '新华区', 3, 130900),
(130903, '运河区', 3, 130900),
(130921, '沧　县', 3, 130900),
(130922, '青　县', 3, 130900),
(130923, '东光县', 3, 130900),
(130924, '海兴县', 3, 130900),
(130925, '盐山县', 3, 130900),
(130926, '肃宁县', 3, 130900),
(130927, '南皮县', 3, 130900),
(130928, '吴桥县', 3, 130900),
(130929, '献　县', 3, 130900),
(130930, '孟村回族自治县', 3, 130900),
(130981, '泊头市', 3, 130900),
(130982, '任丘市', 3, 130900),
(130983, '黄骅市', 3, 130900),
(130984, '河间市', 3, 130900),
(131001, '市辖区', 3, 131000),
(131002, '安次区', 3, 131000),
(131003, '广阳区', 3, 131000),
(131022, '固安县', 3, 131000),
(131023, '永清县', 3, 131000),
(131024, '香河县', 3, 131000),
(131025, '大城县', 3, 131000),
(131026, '文安县', 3, 131000),
(131028, '大厂回族自治县', 3, 131000),
(131081, '霸州市', 3, 131000),
(131082, '三河市', 3, 131000),
(131101, '市辖区', 3, 131100),
(131102, '桃城区', 3, 131100),
(131121, '枣强县', 3, 131100),
(131122, '武邑县', 3, 131100),
(131123, '武强县', 3, 131100),
(131124, '饶阳县', 3, 131100),
(131125, '安平县', 3, 131100),
(131126, '故城县', 3, 131100),
(131127, '景　县', 3, 131100),
(131128, '阜城县', 3, 131100),
(131181, '冀州市', 3, 131100),
(131182, '深州市', 3, 131100),
(140101, '市辖区', 3, 140100),
(140105, '小店区', 3, 140100),
(140106, '迎泽区', 3, 140100),
(140107, '杏花岭区', 3, 140100),
(140108, '尖草坪区', 3, 140100),
(140109, '万柏林区', 3, 140100),
(140110, '晋源区', 3, 140100),
(140121, '清徐县', 3, 140100),
(140122, '阳曲县', 3, 140100),
(140123, '娄烦县', 3, 140100),
(140181, '古交市', 3, 140100),
(140201, '市辖区', 3, 140200),
(140202, '城　区', 3, 140200),
(140203, '矿　区', 3, 140200),
(140211, '南郊区', 3, 140200),
(140212, '新荣区', 3, 140200),
(140221, '阳高县', 3, 140200),
(140222, '天镇县', 3, 140200),
(140223, '广灵县', 3, 140200),
(140224, '灵丘县', 3, 140200),
(140225, '浑源县', 3, 140200),
(140226, '左云县', 3, 140200),
(140227, '大同县', 3, 140200),
(140301, '市辖区', 3, 140300),
(140302, '城　区', 3, 140300),
(140303, '矿　区', 3, 140300),
(140311, '郊　区', 3, 140300),
(140321, '平定县', 3, 140300),
(140322, '盂　县', 3, 140300),
(140401, '市辖区', 3, 140400),
(140402, '城　区', 3, 140400),
(140411, '郊　区', 3, 140400),
(140421, '长治县', 3, 140400),
(140423, '襄垣县', 3, 140400),
(140424, '屯留县', 3, 140400),
(140425, '平顺县', 3, 140400),
(140426, '黎城县', 3, 140400),
(140427, '壶关县', 3, 140400),
(140428, '长子县', 3, 140400),
(140429, '武乡县', 3, 140400),
(140430, '沁　县', 3, 140400),
(140431, '沁源县', 3, 140400),
(140481, '潞城市', 3, 140400),
(140501, '市辖区', 3, 140500),
(140502, '城　区', 3, 140500),
(140521, '沁水县', 3, 140500),
(140522, '阳城县', 3, 140500),
(140524, '陵川县', 3, 140500),
(140525, '泽州县', 3, 140500),
(140581, '高平市', 3, 140500),
(140601, '市辖区', 3, 140600),
(140602, '朔城区', 3, 140600),
(140603, '平鲁区', 3, 140600),
(140621, '山阴县', 3, 140600),
(140622, '应　县', 3, 140600),
(140623, '右玉县', 3, 140600),
(140624, '怀仁县', 3, 140600),
(140701, '市辖区', 3, 140700),
(140702, '榆次区', 3, 140700),
(140721, '榆社县', 3, 140700),
(140722, '左权县', 3, 140700),
(140723, '和顺县', 3, 140700),
(140724, '昔阳县', 3, 140700),
(140725, '寿阳县', 3, 140700),
(140726, '太谷县', 3, 140700),
(140727, '祁　县', 3, 140700),
(140728, '平遥县', 3, 140700),
(140729, '灵石县', 3, 140700),
(140781, '介休市', 3, 140700),
(140801, '市辖区', 3, 140800),
(140802, '盐湖区', 3, 140800),
(140821, '临猗县', 3, 140800),
(140822, '万荣县', 3, 140800),
(140823, '闻喜县', 3, 140800),
(140824, '稷山县', 3, 140800),
(140825, '新绛县', 3, 140800),
(140826, '绛　县', 3, 140800),
(140827, '垣曲县', 3, 140800),
(140828, '夏　县', 3, 140800),
(140829, '平陆县', 3, 140800),
(140830, '芮城县', 3, 140800),
(140881, '永济市', 3, 140800),
(140882, '河津市', 3, 140800),
(140901, '市辖区', 3, 140900),
(140902, '忻府区', 3, 140900),
(140921, '定襄县', 3, 140900),
(140922, '五台县', 3, 140900),
(140923, '代　县', 3, 140900),
(140924, '繁峙县', 3, 140900),
(140925, '宁武县', 3, 140900),
(140926, '静乐县', 3, 140900),
(140927, '神池县', 3, 140900),
(140928, '五寨县', 3, 140900),
(140929, '岢岚县', 3, 140900),
(140930, '河曲县', 3, 140900),
(140931, '保德县', 3, 140900),
(140932, '偏关县', 3, 140900),
(140981, '原平市', 3, 140900),
(141001, '市辖区', 3, 141000),
(141002, '尧都区', 3, 141000),
(141021, '曲沃县', 3, 141000),
(141022, '翼城县', 3, 141000),
(141023, '襄汾县', 3, 141000),
(141024, '洪洞县', 3, 141000),
(141025, '古　县', 3, 141000),
(141026, '安泽县', 3, 141000),
(141027, '浮山县', 3, 141000),
(141028, '吉　县', 3, 141000),
(141029, '乡宁县', 3, 141000),
(141030, '大宁县', 3, 141000),
(141031, '隰　县', 3, 141000),
(141032, '永和县', 3, 141000),
(141033, '蒲　县', 3, 141000),
(141034, '汾西县', 3, 141000),
(141081, '侯马市', 3, 141000),
(141082, '霍州市', 3, 141000),
(141101, '市辖区', 3, 141100),
(141102, '离石区', 3, 141100),
(141121, '文水县', 3, 141100),
(141122, '交城县', 3, 141100),
(141123, '兴　县', 3, 141100),
(141124, '临　县', 3, 141100),
(141125, '柳林县', 3, 141100),
(141126, '石楼县', 3, 141100),
(141127, '岚　县', 3, 141100),
(141128, '方山县', 3, 141100),
(141129, '中阳县', 3, 141100),
(141130, '交口县', 3, 141100),
(141181, '孝义市', 3, 141100),
(141182, '汾阳市', 3, 141100),
(150101, '市辖区', 3, 150100),
(150102, '新城区', 3, 150100),
(150103, '回民区', 3, 150100),
(150104, '玉泉区', 3, 150100),
(150105, '赛罕区', 3, 150100),
(150121, '土默特左旗', 3, 150100),
(150122, '托克托县', 3, 150100),
(150123, '和林格尔县', 3, 150100),
(150124, '清水河县', 3, 150100),
(150125, '武川县', 3, 150100),
(150201, '市辖区', 3, 150200),
(150202, '东河区', 3, 150200),
(150203, '昆都仑区', 3, 150200),
(150204, '青山区', 3, 150200),
(150205, '石拐区', 3, 150200),
(150206, '白云矿区', 3, 150200),
(150207, '九原区', 3, 150200),
(150221, '土默特右旗', 3, 150200),
(150222, '固阳县', 3, 150200),
(150223, '达尔罕茂明安联合旗', 3, 150200),
(150301, '市辖区', 3, 150300),
(150302, '海勃湾区', 3, 150300),
(150303, '海南区', 3, 150300),
(150304, '乌达区', 3, 150300),
(150401, '市辖区', 3, 150400),
(150402, '红山区', 3, 150400),
(150403, '元宝山区', 3, 150400),
(150404, '松山区', 3, 150400),
(150421, '阿鲁科尔沁旗', 3, 150400),
(150422, '巴林左旗', 3, 150400),
(150423, '巴林右旗', 3, 150400),
(150424, '林西县', 3, 150400),
(150425, '克什克腾旗', 3, 150400),
(150426, '翁牛特旗', 3, 150400),
(150428, '喀喇沁旗', 3, 150400),
(150429, '宁城县', 3, 150400),
(150430, '敖汉旗', 3, 150400),
(150501, '市辖区', 3, 150500),
(150502, '科尔沁区', 3, 150500),
(150521, '科尔沁左翼中旗', 3, 150500),
(150522, '科尔沁左翼后旗', 3, 150500),
(150523, '开鲁县', 3, 150500),
(150524, '库伦旗', 3, 150500),
(150525, '奈曼旗', 3, 150500),
(150526, '扎鲁特旗', 3, 150500),
(150581, '霍林郭勒市', 3, 150500),
(150602, '东胜区', 3, 150600),
(150621, '达拉特旗', 3, 150600),
(150622, '准格尔旗', 3, 150600),
(150623, '鄂托克前旗', 3, 150600),
(150624, '鄂托克旗', 3, 150600),
(150625, '杭锦旗', 3, 150600),
(150626, '乌审旗', 3, 150600),
(150627, '伊金霍洛旗', 3, 150600),
(150701, '市辖区', 3, 150700),
(150702, '海拉尔区', 3, 150700),
(150721, '阿荣旗', 3, 150700),
(150722, '莫力达瓦达斡尔族自治旗', 3, 150700),
(150723, '鄂伦春自治旗', 3, 150700),
(150724, '鄂温克族自治旗', 3, 150700),
(150725, '陈巴尔虎旗', 3, 150700),
(150726, '新巴尔虎左旗', 3, 150700),
(150727, '新巴尔虎右旗', 3, 150700),
(150781, '满洲里市', 3, 150700),
(150782, '牙克石市', 3, 150700),
(150783, '扎兰屯市', 3, 150700),
(150784, '额尔古纳市', 3, 150700),
(150785, '根河市', 3, 150700),
(150801, '市辖区', 3, 150800),
(150802, '临河区', 3, 150800),
(150821, '五原县', 3, 150800),
(150822, '磴口县', 3, 150800),
(150823, '乌拉特前旗', 3, 150800),
(150824, '乌拉特中旗', 3, 150800),
(150825, '乌拉特后旗', 3, 150800),
(150826, '杭锦后旗', 3, 150800),
(150901, '市辖区', 3, 150900),
(150902, '集宁区', 3, 150900),
(150921, '卓资县', 3, 150900),
(150922, '化德县', 3, 150900),
(150923, '商都县', 3, 150900),
(150924, '兴和县', 3, 150900),
(150925, '凉城县', 3, 150900),
(150926, '察哈尔右翼前旗', 3, 150900),
(150927, '察哈尔右翼中旗', 3, 150900),
(150928, '察哈尔右翼后旗', 3, 150900),
(150929, '四子王旗', 3, 150900),
(150981, '丰镇市', 3, 150900),
(152201, '乌兰浩特市', 3, 152200),
(152202, '阿尔山市', 3, 152200),
(152221, '科尔沁右翼前旗', 3, 152200),
(152222, '科尔沁右翼中旗', 3, 152200),
(152223, '扎赉特旗', 3, 152200),
(152224, '突泉县', 3, 152200),
(152501, '二连浩特市', 3, 152500),
(152502, '锡林浩特市', 3, 152500),
(152522, '阿巴嘎旗', 3, 152500),
(152523, '苏尼特左旗', 3, 152500),
(152524, '苏尼特右旗', 3, 152500),
(152525, '东乌珠穆沁旗', 3, 152500),
(152526, '西乌珠穆沁旗', 3, 152500),
(152527, '太仆寺旗', 3, 152500),
(152528, '镶黄旗', 3, 152500),
(152529, '正镶白旗', 3, 152500),
(152530, '正蓝旗', 3, 152500),
(152531, '多伦县', 3, 152500),
(152921, '阿拉善左旗', 3, 152900),
(152922, '阿拉善右旗', 3, 152900),
(152923, '额济纳旗', 3, 152900),
(210101, '市辖区', 3, 210100),
(210102, '和平区', 3, 210100),
(210103, '沈河区', 3, 210100),
(210104, '大东区', 3, 210100),
(210105, '皇姑区', 3, 210100),
(210106, '铁西区', 3, 210100),
(210111, '苏家屯区', 3, 210100),
(210112, '东陵区', 3, 210100),
(210113, '新城子区', 3, 210100),
(210114, '于洪区', 3, 210100),
(210122, '辽中县', 3, 210100),
(210123, '康平县', 3, 210100),
(210124, '法库县', 3, 210100),
(210181, '新民市', 3, 210100),
(210201, '市辖区', 3, 210200),
(210202, '中山区', 3, 210200),
(210203, '西岗区', 3, 210200),
(210204, '沙河口区', 3, 210200),
(210211, '甘井子区', 3, 210200),
(210212, '旅顺口区', 3, 210200),
(210213, '金州区', 3, 210200),
(210224, '长海县', 3, 210200),
(210281, '瓦房店市', 3, 210200),
(210282, '普兰店市', 3, 210200),
(210283, '庄河市', 3, 210200),
(210301, '市辖区', 3, 210300),
(210302, '铁东区', 3, 210300),
(210303, '铁西区', 3, 210300),
(210304, '立山区', 3, 210300),
(210311, '千山区', 3, 210300),
(210321, '台安县', 3, 210300),
(210323, '岫岩满族自治县', 3, 210300),
(210381, '海城市', 3, 210300),
(210401, '市辖区', 3, 210400),
(210402, '新抚区', 3, 210400),
(210403, '东洲区', 3, 210400),
(210404, '望花区', 3, 210400),
(210411, '顺城区', 3, 210400),
(210421, '抚顺县', 3, 210400),
(210422, '新宾满族自治县', 3, 210400),
(210423, '清原满族自治县', 3, 210400),
(210501, '市辖区', 3, 210500),
(210502, '平山区', 3, 210500),
(210503, '溪湖区', 3, 210500),
(210504, '明山区', 3, 210500),
(210505, '南芬区', 3, 210500),
(210521, '本溪满族自治县', 3, 210500),
(210522, '桓仁满族自治县', 3, 210500),
(210601, '市辖区', 3, 210600),
(210602, '元宝区', 3, 210600),
(210603, '振兴区', 3, 210600),
(210604, '振安区', 3, 210600),
(210624, '宽甸满族自治县', 3, 210600),
(210681, '东港市', 3, 210600),
(210682, '凤城市', 3, 210600),
(210701, '市辖区', 3, 210700),
(210702, '古塔区', 3, 210700),
(210703, '凌河区', 3, 210700),
(210711, '太和区', 3, 210700),
(210726, '黑山县', 3, 210700),
(210727, '义　县', 3, 210700),
(210781, '凌海市', 3, 210700),
(210782, '北宁市', 3, 210700),
(210801, '市辖区', 3, 210800),
(210802, '站前区', 3, 210800),
(210803, '西市区', 3, 210800),
(210804, '鲅鱼圈区', 3, 210800),
(210811, '老边区', 3, 210800),
(210881, '盖州市', 3, 210800),
(210882, '大石桥市', 3, 210800),
(210901, '市辖区', 3, 210900),
(210902, '海州区', 3, 210900),
(210903, '新邱区', 3, 210900),
(210904, '太平区', 3, 210900),
(210905, '清河门区', 3, 210900),
(210911, '细河区', 3, 210900),
(210921, '阜新蒙古族自治县', 3, 210900),
(210922, '彰武县', 3, 210900),
(211001, '市辖区', 3, 211000),
(211002, '白塔区', 3, 211000),
(211003, '文圣区', 3, 211000),
(211004, '宏伟区', 3, 211000),
(211005, '弓长岭区', 3, 211000),
(211011, '太子河区', 3, 211000),
(211021, '辽阳县', 3, 211000),
(211081, '灯塔市', 3, 211000),
(211101, '市辖区', 3, 211100),
(211102, '双台子区', 3, 211100),
(211103, '兴隆台区', 3, 211100),
(211121, '大洼县', 3, 211100),
(211122, '盘山县', 3, 211100),
(211201, '市辖区', 3, 211200),
(211202, '银州区', 3, 211200),
(211204, '清河区', 3, 211200),
(211221, '铁岭县', 3, 211200),
(211223, '西丰县', 3, 211200),
(211224, '昌图县', 3, 211200),
(211281, '调兵山市', 3, 211200),
(211282, '开原市', 3, 211200),
(211301, '市辖区', 3, 211300),
(211302, '双塔区', 3, 211300),
(211303, '龙城区', 3, 211300),
(211321, '朝阳县', 3, 211300),
(211322, '建平县', 3, 211300),
(211324, '喀喇沁左翼蒙古族自治县', 3, 211300),
(211381, '北票市', 3, 211300),
(211382, '凌源市', 3, 211300),
(211401, '市辖区', 3, 211400),
(211402, '连山区', 3, 211400),
(211403, '龙港区', 3, 211400),
(211404, '南票区', 3, 211400),
(211421, '绥中县', 3, 211400),
(211422, '建昌县', 3, 211400),
(211481, '兴城市', 3, 211400),
(220101, '市辖区', 3, 220100),
(220102, '南关区', 3, 220100),
(220103, '宽城区', 3, 220100),
(220104, '朝阳区', 3, 220100),
(220105, '二道区', 3, 220100),
(220106, '绿园区', 3, 220100),
(220112, '双阳区', 3, 220100),
(220122, '农安县', 3, 220100),
(220181, '九台市', 3, 220100),
(220182, '榆树市', 3, 220100),
(220183, '德惠市', 3, 220100),
(220201, '市辖区', 3, 220200),
(220202, '昌邑区', 3, 220200),
(220203, '龙潭区', 3, 220200),
(220204, '船营区', 3, 220200),
(220211, '丰满区', 3, 220200),
(220221, '永吉县', 3, 220200),
(220281, '蛟河市', 3, 220200),
(220282, '桦甸市', 3, 220200),
(220283, '舒兰市', 3, 220200),
(220284, '磐石市', 3, 220200),
(220301, '市辖区', 3, 220300),
(220302, '铁西区', 3, 220300),
(220303, '铁东区', 3, 220300),
(220322, '梨树县', 3, 220300),
(220323, '伊通满族自治县', 3, 220300),
(220381, '公主岭市', 3, 220300),
(220382, '双辽市', 3, 220300),
(220401, '市辖区', 3, 220400),
(220402, '龙山区', 3, 220400),
(220403, '西安区', 3, 220400),
(220421, '东丰县', 3, 220400),
(220422, '东辽县', 3, 220400),
(220501, '市辖区', 3, 220500),
(220502, '东昌区', 3, 220500),
(220503, '二道江区', 3, 220500),
(220521, '通化县', 3, 220500),
(220523, '辉南县', 3, 220500),
(220524, '柳河县', 3, 220500),
(220581, '梅河口市', 3, 220500),
(220582, '集安市', 3, 220500),
(220601, '市辖区', 3, 220600),
(220602, '八道江区', 3, 220600),
(220621, '抚松县', 3, 220600),
(220622, '靖宇县', 3, 220600),
(220623, '长白朝鲜族自治县', 3, 220600),
(220625, '江源县', 3, 220600),
(220681, '临江市', 3, 220600),
(220701, '市辖区', 3, 220700),
(220702, '宁江区', 3, 220700),
(220721, '前郭尔罗斯蒙古族自治县', 3, 220700),
(220722, '长岭县', 3, 220700),
(220723, '乾安县', 3, 220700),
(220724, '扶余县', 3, 220700),
(220801, '市辖区', 3, 220800),
(220802, '洮北区', 3, 220800),
(220821, '镇赉县', 3, 220800),
(220822, '通榆县', 3, 220800),
(220881, '洮南市', 3, 220800),
(220882, '大安市', 3, 220800),
(222401, '延吉市', 3, 222400),
(222402, '图们市', 3, 222400),
(222403, '敦化市', 3, 222400),
(222404, '珲春市', 3, 222400),
(222405, '龙井市', 3, 222400),
(222406, '和龙市', 3, 222400),
(222424, '汪清县', 3, 222400),
(222426, '安图县', 3, 222400),
(230101, '市辖区', 3, 230100),
(230102, '道里区', 3, 230100),
(230103, '南岗区', 3, 230100),
(230104, '道外区', 3, 230100),
(230106, '香坊区', 3, 230100),
(230107, '动力区', 3, 230100),
(230108, '平房区', 3, 230100),
(230109, '松北区', 3, 230100),
(230111, '呼兰区', 3, 230100),
(230123, '依兰县', 3, 230100),
(230124, '方正县', 3, 230100),
(230125, '宾　县', 3, 230100),
(230126, '巴彦县', 3, 230100),
(230127, '木兰县', 3, 230100),
(230128, '通河县', 3, 230100),
(230129, '延寿县', 3, 230100),
(230181, '阿城市', 3, 230100),
(230182, '双城市', 3, 230100),
(230183, '尚志市', 3, 230100),
(230184, '五常市', 3, 230100),
(230201, '市辖区', 3, 230200),
(230202, '龙沙区', 3, 230200),
(230203, '建华区', 3, 230200),
(230204, '铁锋区', 3, 230200),
(230205, '昂昂溪区', 3, 230200),
(230206, '富拉尔基区', 3, 230200),
(230207, '碾子山区', 3, 230200),
(230208, '梅里斯达斡尔族区', 3, 230200),
(230221, '龙江县', 3, 230200),
(230223, '依安县', 3, 230200),
(230224, '泰来县', 3, 230200),
(230225, '甘南县', 3, 230200),
(230227, '富裕县', 3, 230200),
(230229, '克山县', 3, 230200),
(230230, '克东县', 3, 230200),
(230231, '拜泉县', 3, 230200),
(230281, '讷河市', 3, 230200),
(230301, '市辖区', 3, 230300),
(230302, '鸡冠区', 3, 230300),
(230303, '恒山区', 3, 230300),
(230304, '滴道区', 3, 230300),
(230305, '梨树区', 3, 230300),
(230306, '城子河区', 3, 230300),
(230307, '麻山区', 3, 230300),
(230321, '鸡东县', 3, 230300),
(230381, '虎林市', 3, 230300),
(230382, '密山市', 3, 230300),
(230401, '市辖区', 3, 230400),
(230402, '向阳区', 3, 230400),
(230403, '工农区', 3, 230400),
(230404, '南山区', 3, 230400),
(230405, '兴安区', 3, 230400),
(230406, '东山区', 3, 230400),
(230407, '兴山区', 3, 230400),
(230421, '萝北县', 3, 230400),
(230422, '绥滨县', 3, 230400),
(230501, '市辖区', 3, 230500),
(230502, '尖山区', 3, 230500),
(230503, '岭东区', 3, 230500),
(230505, '四方台区', 3, 230500),
(230506, '宝山区', 3, 230500),
(230521, '集贤县', 3, 230500),
(230522, '友谊县', 3, 230500),
(230523, '宝清县', 3, 230500),
(230524, '饶河县', 3, 230500),
(230601, '市辖区', 3, 230600),
(230602, '萨尔图区', 3, 230600),
(230603, '龙凤区', 3, 230600),
(230604, '让胡路区', 3, 230600),
(230605, '红岗区', 3, 230600),
(230606, '大同区', 3, 230600),
(230621, '肇州县', 3, 230600),
(230622, '肇源县', 3, 230600),
(230623, '林甸县', 3, 230600),
(230624, '杜尔伯特蒙古族自治县', 3, 230600),
(230701, '市辖区', 3, 230700),
(230702, '伊春区', 3, 230700),
(230703, '南岔区', 3, 230700),
(230704, '友好区', 3, 230700),
(230705, '西林区', 3, 230700),
(230706, '翠峦区', 3, 230700),
(230707, '新青区', 3, 230700),
(230708, '美溪区', 3, 230700),
(230709, '金山屯区', 3, 230700),
(230710, '五营区', 3, 230700),
(230711, '乌马河区', 3, 230700),
(230712, '汤旺河区', 3, 230700),
(230713, '带岭区', 3, 230700),
(230714, '乌伊岭区', 3, 230700),
(230715, '红星区', 3, 230700),
(230716, '上甘岭区', 3, 230700),
(230722, '嘉荫县', 3, 230700),
(230781, '铁力市', 3, 230700),
(230801, '市辖区', 3, 230800),
(230802, '永红区', 3, 230800),
(230803, '向阳区', 3, 230800),
(230804, '前进区', 3, 230800),
(230805, '东风区', 3, 230800),
(230811, '郊　区', 3, 230800),
(230822, '桦南县', 3, 230800),
(230826, '桦川县', 3, 230800),
(230828, '汤原县', 3, 230800),
(230833, '抚远县', 3, 230800),
(230881, '同江市', 3, 230800),
(230882, '富锦市', 3, 230800),
(230901, '市辖区', 3, 230900),
(230902, '新兴区', 3, 230900),
(230903, '桃山区', 3, 230900),
(230904, '茄子河区', 3, 230900),
(230921, '勃利县', 3, 230900),
(231001, '市辖区', 3, 231000),
(231002, '东安区', 3, 231000),
(231003, '阳明区', 3, 231000),
(231004, '爱民区', 3, 231000),
(231005, '西安区', 3, 231000),
(231024, '东宁县', 3, 231000),
(231025, '林口县', 3, 231000),
(231081, '绥芬河市', 3, 231000),
(231083, '海林市', 3, 231000),
(231084, '宁安市', 3, 231000),
(231085, '穆棱市', 3, 231000),
(231101, '市辖区', 3, 231100),
(231102, '爱辉区', 3, 231100),
(231121, '嫩江县', 3, 231100),
(231123, '逊克县', 3, 231100),
(231124, '孙吴县', 3, 231100),
(231181, '北安市', 3, 231100),
(231182, '五大连池市', 3, 231100),
(231201, '市辖区', 3, 231200),
(231202, '北林区', 3, 231200),
(231221, '望奎县', 3, 231200),
(231222, '兰西县', 3, 231200),
(231223, '青冈县', 3, 231200),
(231224, '庆安县', 3, 231200),
(231225, '明水县', 3, 231200),
(231226, '绥棱县', 3, 231200),
(231281, '安达市', 3, 231200),
(231282, '肇东市', 3, 231200),
(231283, '海伦市', 3, 231200),
(232721, '呼玛县', 3, 232700),
(232722, '塔河县', 3, 232700),
(232723, '漠河县', 3, 232700),
(310101, '黄浦区', 3, 310100),
(310103, '卢湾区', 3, 310100),
(310104, '徐汇区', 3, 310100),
(310105, '长宁区', 3, 310100),
(310106, '静安区', 3, 310100),
(310107, '普陀区', 3, 310100),
(310108, '闸北区', 3, 310100),
(310109, '虹口区', 3, 310100),
(310110, '杨浦区', 3, 310100),
(310112, '闵行区', 3, 310100),
(310113, '宝山区', 3, 310100),
(310114, '嘉定区', 3, 310100),
(310115, '浦东新区', 3, 310100),
(310116, '金山区', 3, 310100),
(310117, '松江区', 3, 310100),
(310118, '青浦区', 3, 310100),
(310119, '南汇区', 3, 310100),
(310120, '奉贤区', 3, 310100),
(310230, '崇明县', 3, 310200),
(320101, '市辖区', 3, 320100),
(320102, '玄武区', 3, 320100),
(320103, '白下区', 3, 320100),
(320104, '秦淮区', 3, 320100),
(320105, '建邺区', 3, 320100),
(320106, '鼓楼区', 3, 320100),
(320107, '下关区', 3, 320100),
(320111, '浦口区', 3, 320100),
(320113, '栖霞区', 3, 320100),
(320114, '雨花台区', 3, 320100),
(320115, '江宁区', 3, 320100),
(320116, '六合区', 3, 320100),
(320124, '溧水县', 3, 320100),
(320125, '高淳县', 3, 320100),
(320201, '市辖区', 3, 320200),
(320202, '崇安区', 3, 320200),
(320203, '南长区', 3, 320200),
(320204, '北塘区', 3, 320200),
(320205, '锡山区', 3, 320200),
(320206, '惠山区', 3, 320200),
(320211, '滨湖区', 3, 320200),
(320281, '江阴市', 3, 320200),
(320282, '宜兴市', 3, 320200),
(320301, '市辖区', 3, 320300),
(320302, '鼓楼区', 3, 320300),
(320303, '云龙区', 3, 320300),
(320304, '九里区', 3, 320300),
(320305, '贾汪区', 3, 320300),
(320311, '泉山区', 3, 320300),
(320321, '丰　县', 3, 320300),
(320322, '沛　县', 3, 320300),
(320323, '铜山县', 3, 320300),
(320324, '睢宁县', 3, 320300),
(320381, '新沂市', 3, 320300),
(320382, '邳州市', 3, 320300),
(320401, '市辖区', 3, 320400),
(320402, '天宁区', 3, 320400),
(320404, '钟楼区', 3, 320400),
(320405, '戚墅堰区', 3, 320400),
(320411, '新北区', 3, 320400),
(320412, '武进区', 3, 320400),
(320481, '溧阳市', 3, 320400),
(320482, '金坛市', 3, 320400),
(320501, '市辖区', 3, 320500),
(320502, '沧浪区', 3, 320500),
(320503, '平江区', 3, 320500),
(320504, '金阊区', 3, 320500),
(320505, '虎丘区', 3, 320500),
(320506, '吴中区', 3, 320500),
(320507, '相城区', 3, 320500),
(320581, '常熟市', 3, 320500),
(320582, '张家港市', 3, 320500),
(320583, '昆山市', 3, 320500),
(320584, '吴江市', 3, 320500),
(320585, '太仓市', 3, 320500),
(320601, '市辖区', 3, 320600),
(320602, '崇川区', 3, 320600),
(320611, '港闸区', 3, 320600),
(320621, '海安县', 3, 320600),
(320623, '如东县', 3, 320600),
(320681, '启东市', 3, 320600),
(320682, '如皋市', 3, 320600),
(320683, '通州市', 3, 320600),
(320684, '海门市', 3, 320600),
(320701, '市辖区', 3, 320700),
(320703, '连云区', 3, 320700),
(320705, '新浦区', 3, 320700),
(320706, '海州区', 3, 320700),
(320721, '赣榆县', 3, 320700),
(320722, '东海县', 3, 320700),
(320723, '灌云县', 3, 320700),
(320724, '灌南县', 3, 320700),
(320801, '市辖区', 3, 320800),
(320802, '清河区', 3, 320800),
(320803, '楚州区', 3, 320800),
(320804, '淮阴区', 3, 320800),
(320811, '清浦区', 3, 320800),
(320826, '涟水县', 3, 320800),
(320829, '洪泽县', 3, 320800),
(320830, '盱眙县', 3, 320800),
(320831, '金湖县', 3, 320800),
(320901, '市辖区', 3, 320900),
(320902, '亭湖区', 3, 320900),
(320903, '盐都区', 3, 320900),
(320921, '响水县', 3, 320900),
(320922, '滨海县', 3, 320900),
(320923, '阜宁县', 3, 320900),
(320924, '射阳县', 3, 320900),
(320925, '建湖县', 3, 320900),
(320981, '东台市', 3, 320900),
(320982, '大丰市', 3, 320900),
(321001, '市辖区', 3, 321000),
(321002, '广陵区', 3, 321000),
(321003, '邗江区', 3, 321000),
(321011, '郊　区', 3, 321000),
(321023, '宝应县', 3, 321000),
(321081, '仪征市', 3, 321000),
(321084, '高邮市', 3, 321000),
(321088, '江都市', 3, 321000),
(321101, '市辖区', 3, 321100),
(321102, '京口区', 3, 321100),
(321111, '润州区', 3, 321100),
(321112, '丹徒区', 3, 321100),
(321181, '丹阳市', 3, 321100),
(321182, '扬中市', 3, 321100),
(321183, '句容市', 3, 321100),
(321201, '市辖区', 3, 321200),
(321202, '海陵区', 3, 321200),
(321203, '高港区', 3, 321200),
(321281, '兴化市', 3, 321200),
(321282, '靖江市', 3, 321200),
(321283, '泰兴市', 3, 321200),
(321284, '姜堰市', 3, 321200),
(321301, '市辖区', 3, 321300),
(321302, '宿城区', 3, 321300),
(321311, '宿豫区', 3, 321300),
(321322, '沭阳县', 3, 321300),
(321323, '泗阳县', 3, 321300),
(321324, '泗洪县', 3, 321300),
(330101, '市辖区', 3, 330100),
(330102, '上城区', 3, 330100),
(330103, '下城区', 3, 330100),
(330104, '江干区', 3, 330100),
(330105, '拱墅区', 3, 330100),
(330106, '西湖区', 3, 330100),
(330108, '滨江区', 3, 330100),
(330109, '萧山区', 3, 330100),
(330110, '余杭区', 3, 330100),
(330122, '桐庐县', 3, 330100),
(330127, '淳安县', 3, 330100),
(330182, '建德市', 3, 330100),
(330183, '富阳市', 3, 330100),
(330185, '临安市', 3, 330100),
(330201, '市辖区', 3, 330200),
(330203, '海曙区', 3, 330200),
(330204, '江东区', 3, 330200),
(330205, '江北区', 3, 330200),
(330206, '北仑区', 3, 330200),
(330211, '镇海区', 3, 330200),
(330212, '鄞州区', 3, 330200),
(330225, '象山县', 3, 330200),
(330226, '宁海县', 3, 330200),
(330281, '余姚市', 3, 330200),
(330282, '慈溪市', 3, 330200),
(330283, '奉化市', 3, 330200),
(330301, '市辖区', 3, 330300),
(330302, '鹿城区', 3, 330300),
(330303, '龙湾区', 3, 330300),
(330304, '瓯海区', 3, 330300),
(330322, '洞头县', 3, 330300),
(330324, '永嘉县', 3, 330300),
(330326, '平阳县', 3, 330300),
(330327, '苍南县', 3, 330300),
(330328, '文成县', 3, 330300),
(330329, '泰顺县', 3, 330300),
(330381, '瑞安市', 3, 330300),
(330382, '乐清市', 3, 330300),
(330401, '市辖区', 3, 330400),
(330402, '秀城区', 3, 330400),
(330411, '秀洲区', 3, 330400),
(330421, '嘉善县', 3, 330400),
(330424, '海盐县', 3, 330400),
(330481, '海宁市', 3, 330400),
(330482, '平湖市', 3, 330400),
(330483, '桐乡市', 3, 330400),
(330501, '市辖区', 3, 330500),
(330502, '吴兴区', 3, 330500),
(330503, '南浔区', 3, 330500),
(330521, '德清县', 3, 330500),
(330522, '长兴县', 3, 330500),
(330523, '安吉县', 3, 330500),
(330601, '市辖区', 3, 330600),
(330602, '越城区', 3, 330600),
(330621, '绍兴县', 3, 330600),
(330624, '新昌县', 3, 330600),
(330681, '诸暨市', 3, 330600),
(330682, '上虞市', 3, 330600),
(330683, '嵊州市', 3, 330600),
(330701, '市辖区', 3, 330700),
(330702, '婺城区', 3, 330700),
(330703, '金东区', 3, 330700),
(330723, '武义县', 3, 330700),
(330726, '浦江县', 3, 330700),
(330727, '磐安县', 3, 330700),
(330781, '兰溪市', 3, 330700),
(330782, '义乌市', 3, 330700),
(330783, '东阳市', 3, 330700),
(330784, '永康市', 3, 330700),
(330801, '市辖区', 3, 330800),
(330802, '柯城区', 3, 330800),
(330803, '衢江区', 3, 330800),
(330822, '常山县', 3, 330800),
(330824, '开化县', 3, 330800),
(330825, '龙游县', 3, 330800),
(330881, '江山市', 3, 330800),
(330901, '市辖区', 3, 330900),
(330902, '定海区', 3, 330900),
(330903, '普陀区', 3, 330900),
(330921, '岱山县', 3, 330900),
(330922, '嵊泗县', 3, 330900),
(331001, '市辖区', 3, 331000),
(331002, '椒江区', 3, 331000),
(331003, '黄岩区', 3, 331000),
(331004, '路桥区', 3, 331000),
(331021, '玉环县', 3, 331000),
(331022, '三门县', 3, 331000),
(331023, '天台县', 3, 331000),
(331024, '仙居县', 3, 331000),
(331081, '温岭市', 3, 331000),
(331082, '临海市', 3, 331000),
(331101, '市辖区', 3, 331100),
(331102, '莲都区', 3, 331100),
(331121, '青田县', 3, 331100),
(331122, '缙云县', 3, 331100),
(331123, '遂昌县', 3, 331100),
(331124, '松阳县', 3, 331100),
(331125, '云和县', 3, 331100),
(331126, '庆元县', 3, 331100),
(331127, '景宁畲族自治县', 3, 331100),
(331181, '龙泉市', 3, 331100),
(340101, '市辖区', 3, 340100),
(340102, '瑶海区', 3, 340100),
(340103, '庐阳区', 3, 340100),
(340104, '蜀山区', 3, 340100),
(340111, '包河区', 3, 340100),
(340121, '长丰县', 3, 340100),
(340122, '肥东县', 3, 340100),
(340123, '肥西县', 3, 340100),
(340201, '市辖区', 3, 340200),
(340202, '镜湖区', 3, 340200),
(340203, '马塘区', 3, 340200),
(340204, '新芜区', 3, 340200),
(340207, '鸠江区', 3, 340200),
(340221, '芜湖县', 3, 340200),
(340222, '繁昌县', 3, 340200),
(340223, '南陵县', 3, 340200),
(340301, '市辖区', 3, 340300),
(340302, '龙子湖区', 3, 340300),
(340303, '蚌山区', 3, 340300),
(340304, '禹会区', 3, 340300),
(340311, '淮上区', 3, 340300),
(340321, '怀远县', 3, 340300),
(340322, '五河县', 3, 340300),
(340323, '固镇县', 3, 340300),
(340401, '市辖区', 3, 340400),
(340402, '大通区', 3, 340400),
(340403, '田家庵区', 3, 340400),
(340404, '谢家集区', 3, 340400),
(340405, '八公山区', 3, 340400),
(340406, '潘集区', 3, 340400),
(340421, '凤台县', 3, 340400),
(340501, '市辖区', 3, 340500),
(340502, '金家庄区', 3, 340500),
(340503, '花山区', 3, 340500),
(340504, '雨山区', 3, 340500),
(340521, '当涂县', 3, 340500),
(340601, '市辖区', 3, 340600),
(340602, '杜集区', 3, 340600),
(340603, '相山区', 3, 340600),
(340604, '烈山区', 3, 340600),
(340621, '濉溪县', 3, 340600),
(340701, '市辖区', 3, 340700),
(340702, '铜官山区', 3, 340700),
(340703, '狮子山区', 3, 340700),
(340711, '郊　区', 3, 340700),
(340721, '铜陵县', 3, 340700),
(340801, '市辖区', 3, 340800),
(340802, '迎江区', 3, 340800),
(340803, '大观区', 3, 340800),
(340811, '郊　区', 3, 340800),
(340822, '怀宁县', 3, 340800),
(340823, '枞阳县', 3, 340800),
(340824, '潜山县', 3, 340800),
(340825, '太湖县', 3, 340800),
(340826, '宿松县', 3, 340800),
(340827, '望江县', 3, 340800),
(340828, '岳西县', 3, 340800),
(340881, '桐城市', 3, 340800),
(341001, '市辖区', 3, 341000),
(341002, '屯溪区', 3, 341000),
(341003, '黄山区', 3, 341000),
(341004, '徽州区', 3, 341000),
(341021, '歙　县', 3, 341000),
(341022, '休宁县', 3, 341000),
(341023, '黟　县', 3, 341000),
(341024, '祁门县', 3, 341000),
(341101, '市辖区', 3, 341100),
(341102, '琅琊区', 3, 341100),
(341103, '南谯区', 3, 341100),
(341122, '来安县', 3, 341100),
(341124, '全椒县', 3, 341100),
(341125, '定远县', 3, 341100),
(341126, '凤阳县', 3, 341100),
(341181, '天长市', 3, 341100),
(341182, '明光市', 3, 341100),
(341201, '市辖区', 3, 341200),
(341202, '颍州区', 3, 341200),
(341203, '颍东区', 3, 341200),
(341204, '颍泉区', 3, 341200),
(341221, '临泉县', 3, 341200),
(341222, '太和县', 3, 341200),
(341225, '阜南县', 3, 341200),
(341226, '颍上县', 3, 341200),
(341282, '界首市', 3, 341200),
(341301, '市辖区', 3, 341300),
(341302, '墉桥区', 3, 341300),
(341321, '砀山县', 3, 341300),
(341322, '萧　县', 3, 341300),
(341323, '灵璧县', 3, 341300),
(341324, '泗　县', 3, 341300),
(341401, '庐江县', 3, 340100),
(341402, '巢湖市', 3, 340100),
(341422, '无为县', 3, 340200),
(341423, '含山县', 3, 340500),
(341424, '和　县', 3, 340500),
(341501, '市辖区', 3, 341500),
(341502, '金安区', 3, 341500),
(341503, '裕安区', 3, 341500),
(341521, '寿　县', 3, 341500),
(341522, '霍邱县', 3, 341500),
(341523, '舒城县', 3, 341500),
(341524, '金寨县', 3, 341500),
(341525, '霍山县', 3, 341500),
(341601, '市辖区', 3, 341600),
(341602, '谯城区', 3, 341600),
(341621, '涡阳县', 3, 341600),
(341622, '蒙城县', 3, 341600),
(341623, '利辛县', 3, 341600),
(341701, '市辖区', 3, 341700),
(341702, '贵池区', 3, 341700),
(341721, '东至县', 3, 341700),
(341722, '石台县', 3, 341700),
(341723, '青阳县', 3, 341700),
(341801, '市辖区', 3, 341800),
(341802, '宣州区', 3, 341800),
(341821, '郎溪县', 3, 341800),
(341822, '广德县', 3, 341800),
(341823, '泾　县', 3, 341800),
(341824, '绩溪县', 3, 341800),
(341825, '旌德县', 3, 341800),
(341881, '宁国市', 3, 341800),
(350101, '市辖区', 3, 350100),
(350102, '鼓楼区', 3, 350100),
(350103, '台江区', 3, 350100),
(350104, '仓山区', 3, 350100),
(350105, '马尾区', 3, 350100),
(350111, '晋安区', 3, 350100),
(350121, '闽侯县', 3, 350100),
(350122, '连江县', 3, 350100),
(350123, '罗源县', 3, 350100),
(350124, '闽清县', 3, 350100),
(350125, '永泰县', 3, 350100),
(350128, '平潭县', 3, 350100),
(350181, '福清市', 3, 350100),
(350182, '长乐市', 3, 350100),
(350201, '市辖区', 3, 350200),
(350203, '思明区', 3, 350200),
(350205, '海沧区', 3, 350200),
(350206, '湖里区', 3, 350200),
(350211, '集美区', 3, 350200),
(350212, '同安区', 3, 350200),
(350213, '翔安区', 3, 350200),
(350301, '市辖区', 3, 350300),
(350302, '城厢区', 3, 350300),
(350303, '涵江区', 3, 350300),
(350304, '荔城区', 3, 350300),
(350305, '秀屿区', 3, 350300),
(350322, '仙游县', 3, 350300),
(350401, '市辖区', 3, 350400),
(350402, '梅列区', 3, 350400),
(350403, '三元区', 3, 350400),
(350421, '明溪县', 3, 350400),
(350423, '清流县', 3, 350400),
(350424, '宁化县', 3, 350400),
(350425, '大田县', 3, 350400),
(350426, '尤溪县', 3, 350400),
(350427, '沙　县', 3, 350400),
(350428, '将乐县', 3, 350400),
(350429, '泰宁县', 3, 350400),
(350430, '建宁县', 3, 350400),
(350481, '永安市', 3, 350400),
(350501, '市辖区', 3, 350500),
(350502, '鲤城区', 3, 350500),
(350503, '丰泽区', 3, 350500),
(350504, '洛江区', 3, 350500),
(350505, '泉港区', 3, 350500),
(350521, '惠安县', 3, 350500),
(350524, '安溪县', 3, 350500),
(350525, '永春县', 3, 350500),
(350526, '德化县', 3, 350500),
(350527, '金门县', 3, 350500),
(350581, '石狮市', 3, 350500),
(350582, '晋江市', 3, 350500),
(350583, '南安市', 3, 350500),
(350601, '市辖区', 3, 350600),
(350602, '芗城区', 3, 350600),
(350603, '龙文区', 3, 350600),
(350622, '云霄县', 3, 350600),
(350623, '漳浦县', 3, 350600),
(350624, '诏安县', 3, 350600),
(350625, '长泰县', 3, 350600),
(350626, '东山县', 3, 350600),
(350627, '南靖县', 3, 350600),
(350628, '平和县', 3, 350600),
(350629, '华安县', 3, 350600),
(350681, '龙海市', 3, 350600),
(350701, '市辖区', 3, 350700),
(350702, '延平区', 3, 350700),
(350721, '顺昌县', 3, 350700),
(350722, '浦城县', 3, 350700),
(350723, '光泽县', 3, 350700),
(350724, '松溪县', 3, 350700),
(350725, '政和县', 3, 350700),
(350781, '邵武市', 3, 350700),
(350782, '武夷山市', 3, 350700),
(350783, '建瓯市', 3, 350700),
(350784, '建阳市', 3, 350700),
(350801, '市辖区', 3, 350800),
(350802, '新罗区', 3, 350800),
(350821, '长汀县', 3, 350800),
(350822, '永定县', 3, 350800),
(350823, '上杭县', 3, 350800),
(350824, '武平县', 3, 350800),
(350825, '连城县', 3, 350800),
(350881, '漳平市', 3, 350800),
(350901, '市辖区', 3, 350900),
(350902, '蕉城区', 3, 350900),
(350921, '霞浦县', 3, 350900),
(350922, '古田县', 3, 350900),
(350923, '屏南县', 3, 350900),
(350924, '寿宁县', 3, 350900),
(350925, '周宁县', 3, 350900),
(350926, '柘荣县', 3, 350900),
(350981, '福安市', 3, 350900),
(350982, '福鼎市', 3, 350900),
(360101, '市辖区', 3, 360100),
(360102, '东湖区', 3, 360100),
(360103, '西湖区', 3, 360100),
(360104, '青云谱区', 3, 360100),
(360105, '湾里区', 3, 360100),
(360111, '青山湖区', 3, 360100),
(360121, '南昌县', 3, 360100),
(360122, '新建县', 3, 360100),
(360123, '安义县', 3, 360100),
(360124, '进贤县', 3, 360100),
(360201, '市辖区', 3, 360200),
(360202, '昌江区', 3, 360200),
(360203, '珠山区', 3, 360200),
(360222, '浮梁县', 3, 360200),
(360281, '乐平市', 3, 360200),
(360301, '市辖区', 3, 360300),
(360302, '安源区', 3, 360300),
(360313, '湘东区', 3, 360300),
(360321, '莲花县', 3, 360300),
(360322, '上栗县', 3, 360300),
(360323, '芦溪县', 3, 360300),
(360401, '市辖区', 3, 360400),
(360402, '庐山区', 3, 360400),
(360403, '浔阳区', 3, 360400),
(360421, '九江县', 3, 360400),
(360423, '武宁县', 3, 360400),
(360424, '修水县', 3, 360400),
(360425, '永修县', 3, 360400),
(360426, '德安县', 3, 360400),
(360427, '星子县', 3, 360400),
(360428, '都昌县', 3, 360400),
(360429, '湖口县', 3, 360400),
(360430, '彭泽县', 3, 360400),
(360481, '瑞昌市', 3, 360400),
(360501, '市辖区', 3, 360500),
(360502, '渝水区', 3, 360500),
(360521, '分宜县', 3, 360500),
(360601, '市辖区', 3, 360600),
(360602, '月湖区', 3, 360600),
(360622, '余江县', 3, 360600),
(360681, '贵溪市', 3, 360600),
(360701, '市辖区', 3, 360700),
(360702, '章贡区', 3, 360700),
(360721, '赣　县', 3, 360700),
(360722, '信丰县', 3, 360700),
(360723, '大余县', 3, 360700),
(360724, '上犹县', 3, 360700),
(360725, '崇义县', 3, 360700),
(360726, '安远县', 3, 360700),
(360727, '龙南县', 3, 360700),
(360728, '定南县', 3, 360700),
(360729, '全南县', 3, 360700),
(360730, '宁都县', 3, 360700),
(360731, '于都县', 3, 360700),
(360732, '兴国县', 3, 360700),
(360733, '会昌县', 3, 360700),
(360734, '寻乌县', 3, 360700),
(360735, '石城县', 3, 360700),
(360781, '瑞金市', 3, 360700),
(360782, '南康市', 3, 360700),
(360801, '市辖区', 3, 360800),
(360802, '吉州区', 3, 360800),
(360803, '青原区', 3, 360800),
(360821, '吉安县', 3, 360800),
(360822, '吉水县', 3, 360800),
(360823, '峡江县', 3, 360800),
(360824, '新干县', 3, 360800),
(360825, '永丰县', 3, 360800),
(360826, '泰和县', 3, 360800),
(360827, '遂川县', 3, 360800),
(360828, '万安县', 3, 360800),
(360829, '安福县', 3, 360800),
(360830, '永新县', 3, 360800),
(360881, '井冈山市', 3, 360800),
(360901, '市辖区', 3, 360900),
(360902, '袁州区', 3, 360900),
(360921, '奉新县', 3, 360900),
(360922, '万载县', 3, 360900),
(360923, '上高县', 3, 360900),
(360924, '宜丰县', 3, 360900),
(360925, '靖安县', 3, 360900),
(360926, '铜鼓县', 3, 360900),
(360981, '丰城市', 3, 360900),
(360982, '樟树市', 3, 360900),
(360983, '高安市', 3, 360900),
(361001, '市辖区', 3, 361000),
(361002, '临川区', 3, 361000),
(361021, '南城县', 3, 361000),
(361022, '黎川县', 3, 361000),
(361023, '南丰县', 3, 361000),
(361024, '崇仁县', 3, 361000),
(361025, '乐安县', 3, 361000),
(361026, '宜黄县', 3, 361000),
(361027, '金溪县', 3, 361000),
(361028, '资溪县', 3, 361000),
(361029, '东乡县', 3, 361000),
(361030, '广昌县', 3, 361000),
(361101, '市辖区', 3, 361100),
(361102, '信州区', 3, 361100),
(361121, '上饶县', 3, 361100),
(361122, '广丰县', 3, 361100),
(361123, '玉山县', 3, 361100),
(361124, '铅山县', 3, 361100),
(361125, '横峰县', 3, 361100),
(361126, '弋阳县', 3, 361100),
(361127, '余干县', 3, 361100),
(361128, '鄱阳县', 3, 361100),
(361129, '万年县', 3, 361100),
(361130, '婺源县', 3, 361100),
(361181, '德兴市', 3, 361100),
(370101, '市辖区', 3, 370100),
(370102, '历下区', 3, 370100),
(370103, '市中区', 3, 370100),
(370104, '槐荫区', 3, 370100),
(370105, '天桥区', 3, 370100),
(370112, '历城区', 3, 370100),
(370113, '长清区', 3, 370100),
(370124, '平阴县', 3, 370100),
(370125, '济阳县', 3, 370100),
(370126, '商河县', 3, 370100),
(370181, '章丘市', 3, 370100),
(370201, '市辖区', 3, 370200),
(370202, '市南区', 3, 370200),
(370203, '市北区', 3, 370200),
(370205, '四方区', 3, 370200),
(370211, '黄岛区', 3, 370200),
(370212, '崂山区', 3, 370200),
(370213, '李沧区', 3, 370200),
(370214, '城阳区', 3, 370200),
(370281, '胶州市', 3, 370200),
(370282, '即墨市', 3, 370200),
(370283, '平度市', 3, 370200),
(370284, '胶南市', 3, 370200),
(370285, '莱西市', 3, 370200),
(370301, '市辖区', 3, 370300),
(370302, '淄川区', 3, 370300),
(370303, '张店区', 3, 370300),
(370304, '博山区', 3, 370300),
(370305, '临淄区', 3, 370300),
(370306, '周村区', 3, 370300),
(370321, '桓台县', 3, 370300),
(370322, '高青县', 3, 370300),
(370323, '沂源县', 3, 370300),
(370401, '市辖区', 3, 370400),
(370402, '市中区', 3, 370400),
(370403, '薛城区', 3, 370400),
(370404, '峄城区', 3, 370400),
(370405, '台儿庄区', 3, 370400),
(370406, '山亭区', 3, 370400),
(370481, '滕州市', 3, 370400),
(370501, '市辖区', 3, 370500),
(370502, '东营区', 3, 370500),
(370503, '河口区', 3, 370500),
(370521, '垦利县', 3, 370500),
(370522, '利津县', 3, 370500),
(370523, '广饶县', 3, 370500),
(370601, '市辖区', 3, 370600),
(370602, '芝罘区', 3, 370600),
(370611, '福山区', 3, 370600),
(370612, '牟平区', 3, 370600),
(370613, '莱山区', 3, 370600),
(370634, '长岛县', 3, 370600),
(370681, '龙口市', 3, 370600),
(370682, '莱阳市', 3, 370600),
(370683, '莱州市', 3, 370600),
(370684, '蓬莱市', 3, 370600),
(370685, '招远市', 3, 370600),
(370686, '栖霞市', 3, 370600),
(370687, '海阳市', 3, 370600),
(370701, '市辖区', 3, 370700),
(370702, '潍城区', 3, 370700),
(370703, '寒亭区', 3, 370700),
(370704, '坊子区', 3, 370700),
(370705, '奎文区', 3, 370700),
(370724, '临朐县', 3, 370700),
(370725, '昌乐县', 3, 370700),
(370781, '青州市', 3, 370700),
(370782, '诸城市', 3, 370700),
(370783, '寿光市', 3, 370700),
(370784, '安丘市', 3, 370700),
(370785, '高密市', 3, 370700),
(370786, '昌邑市', 3, 370700),
(370801, '市辖区', 3, 370800),
(370802, '市中区', 3, 370800),
(370811, '任城区', 3, 370800),
(370826, '微山县', 3, 370800),
(370827, '鱼台县', 3, 370800),
(370828, '金乡县', 3, 370800),
(370829, '嘉祥县', 3, 370800),
(370830, '汶上县', 3, 370800),
(370831, '泗水县', 3, 370800),
(370832, '梁山县', 3, 370800),
(370881, '曲阜市', 3, 370800),
(370882, '兖州市', 3, 370800),
(370883, '邹城市', 3, 370800),
(370901, '市辖区', 3, 370900),
(370902, '泰山区', 3, 370900),
(370903, '岱岳区', 3, 370900),
(370921, '宁阳县', 3, 370900),
(370923, '东平县', 3, 370900),
(370982, '新泰市', 3, 370900),
(370983, '肥城市', 3, 370900),
(371001, '市辖区', 3, 371000),
(371002, '环翠区', 3, 371000),
(371081, '文登市', 3, 371000),
(371082, '荣成市', 3, 371000),
(371083, '乳山市', 3, 371000),
(371101, '市辖区', 3, 371100),
(371102, '东港区', 3, 371100),
(371103, '岚山区', 3, 371100),
(371121, '五莲县', 3, 371100),
(371122, '莒　县', 3, 371100),
(371201, '市辖区', 3, 371200),
(371202, '莱城区', 3, 371200),
(371203, '钢城区', 3, 371200),
(371301, '市辖区', 3, 371300),
(371302, '兰山区', 3, 371300),
(371311, '罗庄区', 3, 371300),
(371312, '河东区', 3, 371300),
(371321, '沂南县', 3, 371300),
(371322, '郯城县', 3, 371300),
(371323, '沂水县', 3, 371300),
(371324, '苍山县', 3, 371300),
(371325, '费　县', 3, 371300),
(371326, '平邑县', 3, 371300),
(371327, '莒南县', 3, 371300),
(371328, '蒙阴县', 3, 371300),
(371329, '临沭县', 3, 371300),
(371401, '市辖区', 3, 371400),
(371402, '德城区', 3, 371400),
(371421, '陵　县', 3, 371400),
(371422, '宁津县', 3, 371400),
(371423, '庆云县', 3, 371400),
(371424, '临邑县', 3, 371400),
(371425, '齐河县', 3, 371400),
(371426, '平原县', 3, 371400),
(371427, '夏津县', 3, 371400),
(371428, '武城县', 3, 371400),
(371481, '乐陵市', 3, 371400),
(371482, '禹城市', 3, 371400),
(371501, '市辖区', 3, 371500),
(371502, '东昌府区', 3, 371500),
(371521, '阳谷县', 3, 371500),
(371522, '莘　县', 3, 371500),
(371523, '茌平县', 3, 371500),
(371524, '东阿县', 3, 371500),
(371525, '冠　县', 3, 371500),
(371526, '高唐县', 3, 371500),
(371581, '临清市', 3, 371500),
(371601, '市辖区', 3, 371600),
(371602, '滨城区', 3, 371600),
(371621, '惠民县', 3, 371600),
(371622, '阳信县', 3, 371600),
(371623, '无棣县', 3, 371600),
(371624, '沾化县', 3, 371600),
(371625, '博兴县', 3, 371600),
(371626, '邹平县', 3, 371600),
(371701, '市辖区', 3, 371700),
(371702, '牡丹区', 3, 371700),
(371721, '曹　县', 3, 371700),
(371722, '单　县', 3, 371700),
(371723, '成武县', 3, 371700),
(371724, '巨野县', 3, 371700),
(371725, '郓城县', 3, 371700),
(371726, '鄄城县', 3, 371700),
(371727, '定陶县', 3, 371700),
(371728, '东明县', 3, 371700),
(410101, '市辖区', 3, 410100),
(410102, '中原区', 3, 410100),
(410103, '二七区', 3, 410100),
(410104, '管城回族区', 3, 410100),
(410105, '金水区', 3, 410100),
(410106, '上街区', 3, 410100),
(410108, '邙山区', 3, 410100),
(410122, '中牟县', 3, 410100),
(410181, '巩义市', 3, 410100),
(410182, '荥阳市', 3, 410100),
(410183, '新密市', 3, 410100),
(410184, '新郑市', 3, 410100),
(410185, '登封市', 3, 410100),
(410201, '市辖区', 3, 410200),
(410202, '龙亭区', 3, 410200),
(410203, '顺河回族区', 3, 410200),
(410204, '鼓楼区', 3, 410200),
(410205, '南关区', 3, 410200),
(410211, '郊　区', 3, 410200),
(410221, '杞　县', 3, 410200),
(410222, '通许县', 3, 410200),
(410223, '尉氏县', 3, 410200),
(410224, '开封县', 3, 410200),
(410225, '兰考县', 3, 410200),
(410301, '市辖区', 3, 410300);
INSERT INTO `ocenter_district` (`id`, `name`, `level`, `upid`) VALUES
(410302, '老城区', 3, 410300),
(410303, '西工区', 3, 410300),
(410304, '廛河回族区', 3, 410300),
(410305, '涧西区', 3, 410300),
(410306, '吉利区', 3, 410300),
(410307, '洛龙区', 3, 410300),
(410322, '孟津县', 3, 410300),
(410323, '新安县', 3, 410300),
(410324, '栾川县', 3, 410300),
(410325, '嵩　县', 3, 410300),
(410326, '汝阳县', 3, 410300),
(410327, '宜阳县', 3, 410300),
(410328, '洛宁县', 3, 410300),
(410329, '伊川县', 3, 410300),
(410381, '偃师市', 3, 410300),
(410401, '市辖区', 3, 410400),
(410402, '新华区', 3, 410400),
(410403, '卫东区', 3, 410400),
(410404, '石龙区', 3, 410400),
(410411, '湛河区', 3, 410400),
(410421, '宝丰县', 3, 410400),
(410422, '叶　县', 3, 410400),
(410423, '鲁山县', 3, 410400),
(410425, '郏　县', 3, 410400),
(410481, '舞钢市', 3, 410400),
(410482, '汝州市', 3, 410400),
(410501, '市辖区', 3, 410500),
(410502, '文峰区', 3, 410500),
(410503, '北关区', 3, 410500),
(410505, '殷都区', 3, 410500),
(410506, '龙安区', 3, 410500),
(410522, '安阳县', 3, 410500),
(410523, '汤阴县', 3, 410500),
(410526, '滑　县', 3, 410500),
(410527, '内黄县', 3, 410500),
(410581, '林州市', 3, 410500),
(410601, '市辖区', 3, 410600),
(410602, '鹤山区', 3, 410600),
(410603, '山城区', 3, 410600),
(410611, '淇滨区', 3, 410600),
(410621, '浚　县', 3, 410600),
(410622, '淇　县', 3, 410600),
(410701, '市辖区', 3, 410700),
(410702, '红旗区', 3, 410700),
(410703, '卫滨区', 3, 410700),
(410704, '凤泉区', 3, 410700),
(410711, '牧野区', 3, 410700),
(410721, '新乡县', 3, 410700),
(410724, '获嘉县', 3, 410700),
(410725, '原阳县', 3, 410700),
(410726, '延津县', 3, 410700),
(410727, '封丘县', 3, 410700),
(410728, '长垣县', 3, 410700),
(410781, '卫辉市', 3, 410700),
(410782, '辉县市', 3, 410700),
(410801, '市辖区', 3, 410800),
(410802, '解放区', 3, 410800),
(410803, '中站区', 3, 410800),
(410804, '马村区', 3, 410800),
(410811, '山阳区', 3, 410800),
(410821, '修武县', 3, 410800),
(410822, '博爱县', 3, 410800),
(410823, '武陟县', 3, 410800),
(410825, '温　县', 3, 410800),
(410881, '济源市', 3, 410800),
(410882, '沁阳市', 3, 410800),
(410883, '孟州市', 3, 410800),
(410901, '市辖区', 3, 410900),
(410902, '华龙区', 3, 410900),
(410922, '清丰县', 3, 410900),
(410923, '南乐县', 3, 410900),
(410926, '范　县', 3, 410900),
(410927, '台前县', 3, 410900),
(410928, '濮阳县', 3, 410900),
(411001, '市辖区', 3, 411000),
(411002, '魏都区', 3, 411000),
(411023, '许昌县', 3, 411000),
(411024, '鄢陵县', 3, 411000),
(411025, '襄城县', 3, 411000),
(411081, '禹州市', 3, 411000),
(411082, '长葛市', 3, 411000),
(411101, '市辖区', 3, 411100),
(411102, '源汇区', 3, 411100),
(411103, '郾城区', 3, 411100),
(411104, '召陵区', 3, 411100),
(411121, '舞阳县', 3, 411100),
(411122, '临颍县', 3, 411100),
(411201, '市辖区', 3, 411200),
(411202, '湖滨区', 3, 411200),
(411221, '渑池县', 3, 411200),
(411222, '陕　县', 3, 411200),
(411224, '卢氏县', 3, 411200),
(411281, '义马市', 3, 411200),
(411282, '灵宝市', 3, 411200),
(411301, '市辖区', 3, 411300),
(411302, '宛城区', 3, 411300),
(411303, '卧龙区', 3, 411300),
(411321, '南召县', 3, 411300),
(411322, '方城县', 3, 411300),
(411323, '西峡县', 3, 411300),
(411324, '镇平县', 3, 411300),
(411325, '内乡县', 3, 411300),
(411326, '淅川县', 3, 411300),
(411327, '社旗县', 3, 411300),
(411328, '唐河县', 3, 411300),
(411329, '新野县', 3, 411300),
(411330, '桐柏县', 3, 411300),
(411381, '邓州市', 3, 411300),
(411401, '市辖区', 3, 411400),
(411402, '梁园区', 3, 411400),
(411403, '睢阳区', 3, 411400),
(411421, '民权县', 3, 411400),
(411422, '睢　县', 3, 411400),
(411423, '宁陵县', 3, 411400),
(411424, '柘城县', 3, 411400),
(411425, '虞城县', 3, 411400),
(411426, '夏邑县', 3, 411400),
(411481, '永城市', 3, 411400),
(411501, '市辖区', 3, 411500),
(411502, '师河区', 3, 411500),
(411503, '平桥区', 3, 411500),
(411521, '罗山县', 3, 411500),
(411522, '光山县', 3, 411500),
(411523, '新　县', 3, 411500),
(411524, '商城县', 3, 411500),
(411525, '固始县', 3, 411500),
(411526, '潢川县', 3, 411500),
(411527, '淮滨县', 3, 411500),
(411528, '息　县', 3, 411500),
(411601, '市辖区', 3, 411600),
(411602, '川汇区', 3, 411600),
(411621, '扶沟县', 3, 411600),
(411622, '西华县', 3, 411600),
(411623, '商水县', 3, 411600),
(411624, '沈丘县', 3, 411600),
(411625, '郸城县', 3, 411600),
(411626, '淮阳县', 3, 411600),
(411627, '太康县', 3, 411600),
(411628, '鹿邑县', 3, 411600),
(411681, '项城市', 3, 411600),
(411701, '市辖区', 3, 411700),
(411702, '驿城区', 3, 411700),
(411721, '西平县', 3, 411700),
(411722, '上蔡县', 3, 411700),
(411723, '平舆县', 3, 411700),
(411724, '正阳县', 3, 411700),
(411725, '确山县', 3, 411700),
(411726, '泌阳县', 3, 411700),
(411727, '汝南县', 3, 411700),
(411728, '遂平县', 3, 411700),
(411729, '新蔡县', 3, 411700),
(420101, '市辖区', 3, 420100),
(420102, '江岸区', 3, 420100),
(420103, '江汉区', 3, 420100),
(420104, '乔口区', 3, 420100),
(420105, '汉阳区', 3, 420100),
(420106, '武昌区', 3, 420100),
(420107, '青山区', 3, 420100),
(420111, '洪山区', 3, 420100),
(420112, '东西湖区', 3, 420100),
(420113, '汉南区', 3, 420100),
(420114, '蔡甸区', 3, 420100),
(420115, '江夏区', 3, 420100),
(420116, '黄陂区', 3, 420100),
(420117, '新洲区', 3, 420100),
(420201, '市辖区', 3, 420200),
(420202, '黄石港区', 3, 420200),
(420203, '西塞山区', 3, 420200),
(420204, '下陆区', 3, 420200),
(420205, '铁山区', 3, 420200),
(420222, '阳新县', 3, 420200),
(420281, '大冶市', 3, 420200),
(420301, '市辖区', 3, 420300),
(420302, '茅箭区', 3, 420300),
(420303, '张湾区', 3, 420300),
(420321, '郧　县', 3, 420300),
(420322, '郧西县', 3, 420300),
(420323, '竹山县', 3, 420300),
(420324, '竹溪县', 3, 420300),
(420325, '房　县', 3, 420300),
(420381, '丹江口市', 3, 420300),
(420501, '市辖区', 3, 420500),
(420502, '西陵区', 3, 420500),
(420503, '伍家岗区', 3, 420500),
(420504, '点军区', 3, 420500),
(420505, '猇亭区', 3, 420500),
(420506, '夷陵区', 3, 420500),
(420525, '远安县', 3, 420500),
(420526, '兴山县', 3, 420500),
(420527, '秭归县', 3, 420500),
(420528, '长阳土家族自治县', 3, 420500),
(420529, '五峰土家族自治县', 3, 420500),
(420581, '宜都市', 3, 420500),
(420582, '当阳市', 3, 420500),
(420583, '枝江市', 3, 420500),
(420601, '市辖区', 3, 420600),
(420602, '襄城区', 3, 420600),
(420606, '樊城区', 3, 420600),
(420607, '襄阳区', 3, 420600),
(420624, '南漳县', 3, 420600),
(420625, '谷城县', 3, 420600),
(420626, '保康县', 3, 420600),
(420682, '老河口市', 3, 420600),
(420683, '枣阳市', 3, 420600),
(420684, '宜城市', 3, 420600),
(420701, '市辖区', 3, 420700),
(420702, '梁子湖区', 3, 420700),
(420703, '华容区', 3, 420700),
(420704, '鄂城区', 3, 420700),
(420801, '市辖区', 3, 420800),
(420802, '东宝区', 3, 420800),
(420804, '掇刀区', 3, 420800),
(420821, '京山县', 3, 420800),
(420822, '沙洋县', 3, 420800),
(420881, '钟祥市', 3, 420800),
(420901, '市辖区', 3, 420900),
(420902, '孝南区', 3, 420900),
(420921, '孝昌县', 3, 420900),
(420922, '大悟县', 3, 420900),
(420923, '云梦县', 3, 420900),
(420981, '应城市', 3, 420900),
(420982, '安陆市', 3, 420900),
(420984, '汉川市', 3, 420900),
(421001, '市辖区', 3, 421000),
(421002, '沙市区', 3, 421000),
(421003, '荆州区', 3, 421000),
(421022, '公安县', 3, 421000),
(421023, '监利县', 3, 421000),
(421024, '江陵县', 3, 421000),
(421081, '石首市', 3, 421000),
(421083, '洪湖市', 3, 421000),
(421087, '松滋市', 3, 421000),
(421101, '市辖区', 3, 421100),
(421102, '黄州区', 3, 421100),
(421121, '团风县', 3, 421100),
(421122, '红安县', 3, 421100),
(421123, '罗田县', 3, 421100),
(421124, '英山县', 3, 421100),
(421125, '浠水县', 3, 421100),
(421126, '蕲春县', 3, 421100),
(421127, '黄梅县', 3, 421100),
(421181, '麻城市', 3, 421100),
(421182, '武穴市', 3, 421100),
(421201, '市辖区', 3, 421200),
(421202, '咸安区', 3, 421200),
(421221, '嘉鱼县', 3, 421200),
(421222, '通城县', 3, 421200),
(421223, '崇阳县', 3, 421200),
(421224, '通山县', 3, 421200),
(421281, '赤壁市', 3, 421200),
(421301, '市辖区', 3, 421300),
(421302, '曾都区', 3, 421300),
(421381, '广水市', 3, 421300),
(422801, '恩施市', 3, 422800),
(422802, '利川市', 3, 422800),
(422822, '建始县', 3, 422800),
(422823, '巴东县', 3, 422800),
(422825, '宣恩县', 3, 422800),
(422826, '咸丰县', 3, 422800),
(422827, '来凤县', 3, 422800),
(422828, '鹤峰县', 3, 422800),
(429004, '仙桃市', 3, 429000),
(429005, '潜江市', 3, 429000),
(429006, '天门市', 3, 429000),
(429021, '神农架林区', 3, 429000),
(430101, '市辖区', 3, 430100),
(430102, '芙蓉区', 3, 430100),
(430103, '天心区', 3, 430100),
(430104, '岳麓区', 3, 430100),
(430105, '开福区', 3, 430100),
(430111, '雨花区', 3, 430100),
(430121, '长沙县', 3, 430100),
(430122, '望城县', 3, 430100),
(430124, '宁乡县', 3, 430100),
(430181, '浏阳市', 3, 430100),
(430201, '市辖区', 3, 430200),
(430202, '荷塘区', 3, 430200),
(430203, '芦淞区', 3, 430200),
(430204, '石峰区', 3, 430200),
(430211, '天元区', 3, 430200),
(430221, '株洲县', 3, 430200),
(430223, '攸　县', 3, 430200),
(430224, '茶陵县', 3, 430200),
(430225, '炎陵县', 3, 430200),
(430281, '醴陵市', 3, 430200),
(430301, '市辖区', 3, 430300),
(430302, '雨湖区', 3, 430300),
(430304, '岳塘区', 3, 430300),
(430321, '湘潭县', 3, 430300),
(430381, '湘乡市', 3, 430300),
(430382, '韶山市', 3, 430300),
(430401, '市辖区', 3, 430400),
(430405, '珠晖区', 3, 430400),
(430406, '雁峰区', 3, 430400),
(430407, '石鼓区', 3, 430400),
(430408, '蒸湘区', 3, 430400),
(430412, '南岳区', 3, 430400),
(430421, '衡阳县', 3, 430400),
(430422, '衡南县', 3, 430400),
(430423, '衡山县', 3, 430400),
(430424, '衡东县', 3, 430400),
(430426, '祁东县', 3, 430400),
(430481, '耒阳市', 3, 430400),
(430482, '常宁市', 3, 430400),
(430501, '市辖区', 3, 430500),
(430502, '双清区', 3, 430500),
(430503, '大祥区', 3, 430500),
(430511, '北塔区', 3, 430500),
(430521, '邵东县', 3, 430500),
(430522, '新邵县', 3, 430500),
(430523, '邵阳县', 3, 430500),
(430524, '隆回县', 3, 430500),
(430525, '洞口县', 3, 430500),
(430527, '绥宁县', 3, 430500),
(430528, '新宁县', 3, 430500),
(430529, '城步苗族自治县', 3, 430500),
(430581, '武冈市', 3, 430500),
(430601, '市辖区', 3, 430600),
(430602, '岳阳楼区', 3, 430600),
(430603, '云溪区', 3, 430600),
(430611, '君山区', 3, 430600),
(430621, '岳阳县', 3, 430600),
(430623, '华容县', 3, 430600),
(430624, '湘阴县', 3, 430600),
(430626, '平江县', 3, 430600),
(430681, '汨罗市', 3, 430600),
(430682, '临湘市', 3, 430600),
(430701, '市辖区', 3, 430700),
(430702, '武陵区', 3, 430700),
(430703, '鼎城区', 3, 430700),
(430721, '安乡县', 3, 430700),
(430722, '汉寿县', 3, 430700),
(430723, '澧　县', 3, 430700),
(430724, '临澧县', 3, 430700),
(430725, '桃源县', 3, 430700),
(430726, '石门县', 3, 430700),
(430781, '津市市', 3, 430700),
(430801, '市辖区', 3, 430800),
(430802, '永定区', 3, 430800),
(430811, '武陵源区', 3, 430800),
(430821, '慈利县', 3, 430800),
(430822, '桑植县', 3, 430800),
(430901, '市辖区', 3, 430900),
(430902, '资阳区', 3, 430900),
(430903, '赫山区', 3, 430900),
(430921, '南　县', 3, 430900),
(430922, '桃江县', 3, 430900),
(430923, '安化县', 3, 430900),
(430981, '沅江市', 3, 430900),
(431001, '市辖区', 3, 431000),
(431002, '北湖区', 3, 431000),
(431003, '苏仙区', 3, 431000),
(431021, '桂阳县', 3, 431000),
(431022, '宜章县', 3, 431000),
(431023, '永兴县', 3, 431000),
(431024, '嘉禾县', 3, 431000),
(431025, '临武县', 3, 431000),
(431026, '汝城县', 3, 431000),
(431027, '桂东县', 3, 431000),
(431028, '安仁县', 3, 431000),
(431081, '资兴市', 3, 431000),
(431101, '市辖区', 3, 431100),
(431102, '芝山区', 3, 431100),
(431103, '冷水滩区', 3, 431100),
(431121, '祁阳县', 3, 431100),
(431122, '东安县', 3, 431100),
(431123, '双牌县', 3, 431100),
(431124, '道　县', 3, 431100),
(431125, '江永县', 3, 431100),
(431126, '宁远县', 3, 431100),
(431127, '蓝山县', 3, 431100),
(431128, '新田县', 3, 431100),
(431129, '江华瑶族自治县', 3, 431100),
(431201, '市辖区', 3, 431200),
(431202, '鹤城区', 3, 431200),
(431221, '中方县', 3, 431200),
(431222, '沅陵县', 3, 431200),
(431223, '辰溪县', 3, 431200),
(431224, '溆浦县', 3, 431200),
(431225, '会同县', 3, 431200),
(431226, '麻阳苗族自治县', 3, 431200),
(431227, '新晃侗族自治县', 3, 431200),
(431228, '芷江侗族自治县', 3, 431200),
(431229, '靖州苗族侗族自治县', 3, 431200),
(431230, '通道侗族自治县', 3, 431200),
(431281, '洪江市', 3, 431200),
(431301, '市辖区', 3, 431300),
(431302, '娄星区', 3, 431300),
(431321, '双峰县', 3, 431300),
(431322, '新化县', 3, 431300),
(431381, '冷水江市', 3, 431300),
(431382, '涟源市', 3, 431300),
(433101, '吉首市', 3, 433100),
(433122, '泸溪县', 3, 433100),
(433123, '凤凰县', 3, 433100),
(433124, '花垣县', 3, 433100),
(433125, '保靖县', 3, 433100),
(433126, '古丈县', 3, 433100),
(433127, '永顺县', 3, 433100),
(433130, '龙山县', 3, 433100),
(440101, '市辖区', 3, 440100),
(440102, '东山区', 3, 440100),
(440103, '荔湾区', 3, 440100),
(440104, '越秀区', 3, 440100),
(440105, '海珠区', 3, 440100),
(440106, '天河区', 3, 440100),
(440107, '芳村区', 3, 440100),
(440111, '白云区', 3, 440100),
(440112, '黄埔区', 3, 440100),
(440113, '番禺区', 3, 440100),
(440114, '花都区', 3, 440100),
(440183, '增城市', 3, 440100),
(440184, '从化市', 3, 440100),
(440201, '市辖区', 3, 440200),
(440203, '武江区', 3, 440200),
(440204, '浈江区', 3, 440200),
(440205, '曲江区', 3, 440200),
(440222, '始兴县', 3, 440200),
(440224, '仁化县', 3, 440200),
(440229, '翁源县', 3, 440200),
(440232, '乳源瑶族自治县', 3, 440200),
(440233, '新丰县', 3, 440200),
(440281, '乐昌市', 3, 440200),
(440282, '南雄市', 3, 440200),
(440301, '市辖区', 3, 440300),
(440303, '罗湖区', 3, 440300),
(440304, '福田区', 3, 440300),
(440305, '南山区', 3, 440300),
(440306, '宝安区', 3, 440300),
(440307, '龙岗区', 3, 440300),
(440308, '盐田区', 3, 440300),
(440401, '市辖区', 3, 440400),
(440402, '香洲区', 3, 440400),
(440403, '斗门区', 3, 440400),
(440404, '金湾区', 3, 440400),
(440501, '市辖区', 3, 440500),
(440507, '龙湖区', 3, 440500),
(440511, '金平区', 3, 440500),
(440512, '濠江区', 3, 440500),
(440513, '潮阳区', 3, 440500),
(440514, '潮南区', 3, 440500),
(440515, '澄海区', 3, 440500),
(440523, '南澳县', 3, 440500),
(440601, '市辖区', 3, 440600),
(440604, '禅城区', 3, 440600),
(440605, '南海区', 3, 440600),
(440606, '顺德区', 3, 440600),
(440607, '三水区', 3, 440600),
(440608, '高明区', 3, 440600),
(440701, '市辖区', 3, 440700),
(440703, '蓬江区', 3, 440700),
(440704, '江海区', 3, 440700),
(440705, '新会区', 3, 440700),
(440781, '台山市', 3, 440700),
(440783, '开平市', 3, 440700),
(440784, '鹤山市', 3, 440700),
(440785, '恩平市', 3, 440700),
(440801, '市辖区', 3, 440800),
(440802, '赤坎区', 3, 440800),
(440803, '霞山区', 3, 440800),
(440804, '坡头区', 3, 440800),
(440811, '麻章区', 3, 440800),
(440823, '遂溪县', 3, 440800),
(440825, '徐闻县', 3, 440800),
(440881, '廉江市', 3, 440800),
(440882, '雷州市', 3, 440800),
(440883, '吴川市', 3, 440800),
(440901, '市辖区', 3, 440900),
(440902, '茂南区', 3, 440900),
(440903, '茂港区', 3, 440900),
(440923, '电白县', 3, 440900),
(440981, '高州市', 3, 440900),
(440982, '化州市', 3, 440900),
(440983, '信宜市', 3, 440900),
(441201, '市辖区', 3, 441200),
(441202, '端州区', 3, 441200),
(441203, '鼎湖区', 3, 441200),
(441223, '广宁县', 3, 441200),
(441224, '怀集县', 3, 441200),
(441225, '封开县', 3, 441200),
(441226, '德庆县', 3, 441200),
(441283, '高要市', 3, 441200),
(441284, '四会市', 3, 441200),
(441301, '市辖区', 3, 441300),
(441302, '惠城区', 3, 441300),
(441303, '惠阳区', 3, 441300),
(441322, '博罗县', 3, 441300),
(441323, '惠东县', 3, 441300),
(441324, '龙门县', 3, 441300),
(441401, '市辖区', 3, 441400),
(441402, '梅江区', 3, 441400),
(441421, '梅　县', 3, 441400),
(441422, '大埔县', 3, 441400),
(441423, '丰顺县', 3, 441400),
(441424, '五华县', 3, 441400),
(441426, '平远县', 3, 441400),
(441427, '蕉岭县', 3, 441400),
(441481, '兴宁市', 3, 441400),
(441501, '市辖区', 3, 441500),
(441502, '城　区', 3, 441500),
(441521, '海丰县', 3, 441500),
(441523, '陆河县', 3, 441500),
(441581, '陆丰市', 3, 441500),
(441601, '市辖区', 3, 441600),
(441602, '源城区', 3, 441600),
(441621, '紫金县', 3, 441600),
(441622, '龙川县', 3, 441600),
(441623, '连平县', 3, 441600),
(441624, '和平县', 3, 441600),
(441625, '东源县', 3, 441600),
(441701, '市辖区', 3, 441700),
(441702, '江城区', 3, 441700),
(441721, '阳西县', 3, 441700),
(441723, '阳东县', 3, 441700),
(441781, '阳春市', 3, 441700),
(441801, '市辖区', 3, 441800),
(441802, '清城区', 3, 441800),
(441821, '佛冈县', 3, 441800),
(441823, '阳山县', 3, 441800),
(441825, '连山壮族瑶族自治县', 3, 441800),
(441826, '连南瑶族自治县', 3, 441800),
(441827, '清新县', 3, 441800),
(441881, '英德市', 3, 441800),
(441882, '连州市', 3, 441800),
(445101, '市辖区', 3, 445100),
(445102, '湘桥区', 3, 445100),
(445121, '潮安县', 3, 445100),
(445122, '饶平县', 3, 445100),
(445201, '市辖区', 3, 445200),
(445202, '榕城区', 3, 445200),
(445221, '揭东县', 3, 445200),
(445222, '揭西县', 3, 445200),
(445224, '惠来县', 3, 445200),
(445281, '普宁市', 3, 445200),
(445301, '市辖区', 3, 445300),
(445302, '云城区', 3, 445300),
(445321, '新兴县', 3, 445300),
(445322, '郁南县', 3, 445300),
(445323, '云安县', 3, 445300),
(445381, '罗定市', 3, 445300),
(450101, '市辖区', 3, 450100),
(450102, '兴宁区', 3, 450100),
(450103, '青秀区', 3, 450100),
(450105, '江南区', 3, 450100),
(450107, '西乡塘区', 3, 450100),
(450108, '良庆区', 3, 450100),
(450109, '邕宁区', 3, 450100),
(450122, '武鸣县', 3, 450100),
(450123, '隆安县', 3, 450100),
(450124, '马山县', 3, 450100),
(450125, '上林县', 3, 450100),
(450126, '宾阳县', 3, 450100),
(450127, '横　县', 3, 450100),
(450201, '市辖区', 3, 450200),
(450202, '城中区', 3, 450200),
(450203, '鱼峰区', 3, 450200),
(450204, '柳南区', 3, 450200),
(450205, '柳北区', 3, 450200),
(450221, '柳江县', 3, 450200),
(450222, '柳城县', 3, 450200),
(450223, '鹿寨县', 3, 450200),
(450224, '融安县', 3, 450200),
(450225, '融水苗族自治县', 3, 450200),
(450226, '三江侗族自治县', 3, 450200),
(450301, '市辖区', 3, 450300),
(450302, '秀峰区', 3, 450300),
(450303, '叠彩区', 3, 450300),
(450304, '象山区', 3, 450300),
(450305, '七星区', 3, 450300),
(450311, '雁山区', 3, 450300),
(450321, '阳朔县', 3, 450300),
(450322, '临桂县', 3, 450300),
(450323, '灵川县', 3, 450300),
(450324, '全州县', 3, 450300),
(450325, '兴安县', 3, 450300),
(450326, '永福县', 3, 450300),
(450327, '灌阳县', 3, 450300),
(450328, '龙胜各族自治县', 3, 450300),
(450329, '资源县', 3, 450300),
(450330, '平乐县', 3, 450300),
(450331, '荔蒲县', 3, 450300),
(450332, '恭城瑶族自治县', 3, 450300),
(450401, '市辖区', 3, 450400),
(450403, '万秀区', 3, 450400),
(450404, '蝶山区', 3, 450400),
(450405, '长洲区', 3, 450400),
(450421, '苍梧县', 3, 450400),
(450422, '藤　县', 3, 450400),
(450423, '蒙山县', 3, 450400),
(450481, '岑溪市', 3, 450400),
(450501, '市辖区', 3, 450500),
(450502, '海城区', 3, 450500),
(450503, '银海区', 3, 450500),
(450512, '铁山港区', 3, 450500),
(450521, '合浦县', 3, 450500),
(450601, '市辖区', 3, 450600),
(450602, '港口区', 3, 450600),
(450603, '防城区', 3, 450600),
(450621, '上思县', 3, 450600),
(450681, '东兴市', 3, 450600),
(450701, '市辖区', 3, 450700),
(450702, '钦南区', 3, 450700),
(450703, '钦北区', 3, 450700),
(450721, '灵山县', 3, 450700),
(450722, '浦北县', 3, 450700),
(450801, '市辖区', 3, 450800),
(450802, '港北区', 3, 450800),
(450803, '港南区', 3, 450800),
(450804, '覃塘区', 3, 450800),
(450821, '平南县', 3, 450800),
(450881, '桂平市', 3, 450800),
(450901, '市辖区', 3, 450900),
(450902, '玉州区', 3, 450900),
(450921, '容　县', 3, 450900),
(450922, '陆川县', 3, 450900),
(450923, '博白县', 3, 450900),
(450924, '兴业县', 3, 450900),
(450981, '北流市', 3, 450900),
(451001, '市辖区', 3, 451000),
(451002, '右江区', 3, 451000),
(451021, '田阳县', 3, 451000),
(451022, '田东县', 3, 451000),
(451023, '平果县', 3, 451000),
(451024, '德保县', 3, 451000),
(451025, '靖西县', 3, 451000),
(451026, '那坡县', 3, 451000),
(451027, '凌云县', 3, 451000),
(451028, '乐业县', 3, 451000),
(451029, '田林县', 3, 451000),
(451030, '西林县', 3, 451000),
(451031, '隆林各族自治县', 3, 451000),
(451101, '市辖区', 3, 451100),
(451102, '八步区', 3, 451100),
(451121, '昭平县', 3, 451100),
(451122, '钟山县', 3, 451100),
(451123, '富川瑶族自治县', 3, 451100),
(451201, '市辖区', 3, 451200),
(451202, '金城江区', 3, 451200),
(451221, '南丹县', 3, 451200),
(451222, '天峨县', 3, 451200),
(451223, '凤山县', 3, 451200),
(451224, '东兰县', 3, 451200),
(451225, '罗城仫佬族自治县', 3, 451200),
(451226, '环江毛南族自治县', 3, 451200),
(451227, '巴马瑶族自治县', 3, 451200),
(451228, '都安瑶族自治县', 3, 451200),
(451229, '大化瑶族自治县', 3, 451200),
(451281, '宜州市', 3, 451200),
(451301, '市辖区', 3, 451300),
(451302, '兴宾区', 3, 451300),
(451321, '忻城县', 3, 451300),
(451322, '象州县', 3, 451300),
(451323, '武宣县', 3, 451300),
(451324, '金秀瑶族自治县', 3, 451300),
(451381, '合山市', 3, 451300),
(451401, '市辖区', 3, 451400),
(451402, '江洲区', 3, 451400),
(451421, '扶绥县', 3, 451400),
(451422, '宁明县', 3, 451400),
(451423, '龙州县', 3, 451400),
(451424, '大新县', 3, 451400),
(451425, '天等县', 3, 451400),
(451481, '凭祥市', 3, 451400),
(460101, '市辖区', 3, 460100),
(460105, '秀英区', 3, 460100),
(460106, '龙华区', 3, 460100),
(460107, '琼山区', 3, 460100),
(460108, '美兰区', 3, 460100),
(460201, '市辖区', 3, 460200),
(469001, '五指山市', 3, 469000),
(469002, '琼海市', 3, 469000),
(469003, '儋州市', 3, 469000),
(469005, '文昌市', 3, 469000),
(469006, '万宁市', 3, 469000),
(469007, '东方市', 3, 469000),
(469025, '定安县', 3, 469000),
(469026, '屯昌县', 3, 469000),
(469027, '澄迈县', 3, 469000),
(469028, '临高县', 3, 469000),
(469030, '白沙黎族自治县', 3, 469000),
(469031, '昌江黎族自治县', 3, 469000),
(469033, '乐东黎族自治县', 3, 469000),
(469034, '陵水黎族自治县', 3, 469000),
(469035, '保亭黎族苗族自治县', 3, 469000),
(469036, '琼中黎族苗族自治县', 3, 469000),
(469037, '西沙群岛', 3, 469000),
(469038, '南沙群岛', 3, 469000),
(469039, '中沙群岛的岛礁及其海域', 3, 469000),
(500101, '万州区', 3, 500100),
(500102, '涪陵区', 3, 500100),
(500103, '渝中区', 3, 500100),
(500104, '大渡口区', 3, 500100),
(500105, '江北区', 3, 500100),
(500106, '沙坪坝区', 3, 500100),
(500107, '九龙坡区', 3, 500100),
(500108, '南岸区', 3, 500100),
(500109, '北碚区', 3, 500100),
(500110, '万盛区', 3, 500100),
(500111, '双桥区', 3, 500100),
(500112, '渝北区', 3, 500100),
(500113, '巴南区', 3, 500100),
(500114, '黔江区', 3, 500100),
(500115, '长寿区', 3, 500100),
(500222, '綦江县', 3, 500200),
(500223, '潼南县', 3, 500200),
(500224, '铜梁县', 3, 500200),
(500225, '大足县', 3, 500200),
(500226, '荣昌县', 3, 500200),
(500227, '璧山县', 3, 500200),
(500228, '梁平县', 3, 500200),
(500229, '城口县', 3, 500200),
(500230, '丰都县', 3, 500200),
(500231, '垫江县', 3, 500200),
(500232, '武隆县', 3, 500200),
(500233, '忠　县', 3, 500200),
(500234, '开　县', 3, 500200),
(500235, '云阳县', 3, 500200),
(500236, '奉节县', 3, 500200),
(500237, '巫山县', 3, 500200),
(500238, '巫溪县', 3, 500200),
(500240, '石柱土家族自治县', 3, 500200),
(500241, '秀山土家族苗族自治县', 3, 500200),
(500242, '酉阳土家族苗族自治县', 3, 500200),
(500243, '彭水苗族土家族自治县', 3, 500200),
(500381, '江津市', 3, 500300),
(500382, '合川市', 3, 500300),
(500383, '永川市', 3, 500300),
(500384, '南川市', 3, 500300),
(510101, '市辖区', 3, 510100),
(510104, '锦江区', 3, 510100),
(510105, '青羊区', 3, 510100),
(510106, '金牛区', 3, 510100),
(510107, '武侯区', 3, 510100),
(510108, '成华区', 3, 510100),
(510112, '龙泉驿区', 3, 510100),
(510113, '青白江区', 3, 510100),
(510114, '新都区', 3, 510100),
(510115, '温江区', 3, 510100),
(510121, '金堂县', 3, 510100),
(510122, '双流县', 3, 510100),
(510124, '郫　县', 3, 510100),
(510129, '大邑县', 3, 510100),
(510131, '蒲江县', 3, 510100),
(510132, '新津县', 3, 510100),
(510181, '都江堰市', 3, 510100),
(510182, '彭州市', 3, 510100),
(510183, '邛崃市', 3, 510100),
(510184, '崇州市', 3, 510100),
(510301, '市辖区', 3, 510300),
(510302, '自流井区', 3, 510300),
(510303, '贡井区', 3, 510300),
(510304, '大安区', 3, 510300),
(510311, '沿滩区', 3, 510300),
(510321, '荣　县', 3, 510300),
(510322, '富顺县', 3, 510300),
(510401, '市辖区', 3, 510400),
(510402, '东　区', 3, 510400),
(510403, '西　区', 3, 510400),
(510411, '仁和区', 3, 510400),
(510421, '米易县', 3, 510400),
(510422, '盐边县', 3, 510400),
(510501, '市辖区', 3, 510500),
(510502, '江阳区', 3, 510500),
(510503, '纳溪区', 3, 510500),
(510504, '龙马潭区', 3, 510500),
(510521, '泸　县', 3, 510500),
(510522, '合江县', 3, 510500),
(510524, '叙永县', 3, 510500),
(510525, '古蔺县', 3, 510500),
(510601, '市辖区', 3, 510600),
(510603, '旌阳区', 3, 510600),
(510623, '中江县', 3, 510600),
(510626, '罗江县', 3, 510600),
(510681, '广汉市', 3, 510600),
(510682, '什邡市', 3, 510600),
(510683, '绵竹市', 3, 510600),
(510701, '市辖区', 3, 510700),
(510703, '涪城区', 3, 510700),
(510704, '游仙区', 3, 510700),
(510722, '三台县', 3, 510700),
(510723, '盐亭县', 3, 510700),
(510724, '安　县', 3, 510700),
(510725, '梓潼县', 3, 510700),
(510726, '北川羌族自治县', 3, 510700),
(510727, '平武县', 3, 510700),
(510781, '江油市', 3, 510700),
(510801, '市辖区', 3, 510800),
(510802, '市中区', 3, 510800),
(510811, '元坝区', 3, 510800),
(510812, '朝天区', 3, 510800),
(510821, '旺苍县', 3, 510800),
(510822, '青川县', 3, 510800),
(510823, '剑阁县', 3, 510800),
(510824, '苍溪县', 3, 510800),
(510901, '市辖区', 3, 510900),
(510903, '船山区', 3, 510900),
(510904, '安居区', 3, 510900),
(510921, '蓬溪县', 3, 510900),
(510922, '射洪县', 3, 510900),
(510923, '大英县', 3, 510900),
(511001, '市辖区', 3, 511000),
(511002, '市中区', 3, 511000),
(511011, '东兴区', 3, 511000),
(511024, '威远县', 3, 511000),
(511025, '资中县', 3, 511000),
(511028, '隆昌县', 3, 511000),
(511101, '市辖区', 3, 511100),
(511102, '市中区', 3, 511100),
(511111, '沙湾区', 3, 511100),
(511112, '五通桥区', 3, 511100),
(511113, '金口河区', 3, 511100),
(511123, '犍为县', 3, 511100),
(511124, '井研县', 3, 511100),
(511126, '夹江县', 3, 511100),
(511129, '沐川县', 3, 511100),
(511132, '峨边彝族自治县', 3, 511100),
(511133, '马边彝族自治县', 3, 511100),
(511181, '峨眉山市', 3, 511100),
(511301, '市辖区', 3, 511300),
(511302, '顺庆区', 3, 511300),
(511303, '高坪区', 3, 511300),
(511304, '嘉陵区', 3, 511300),
(511321, '南部县', 3, 511300),
(511322, '营山县', 3, 511300),
(511323, '蓬安县', 3, 511300),
(511324, '仪陇县', 3, 511300),
(511325, '西充县', 3, 511300),
(511381, '阆中市', 3, 511300),
(511401, '市辖区', 3, 511400),
(511402, '东坡区', 3, 511400),
(511421, '仁寿县', 3, 511400),
(511422, '彭山县', 3, 511400),
(511423, '洪雅县', 3, 511400),
(511424, '丹棱县', 3, 511400),
(511425, '青神县', 3, 511400),
(511501, '市辖区', 3, 511500),
(511502, '翠屏区', 3, 511500),
(511521, '宜宾县', 3, 511500),
(511522, '南溪县', 3, 511500),
(511523, '江安县', 3, 511500),
(511524, '长宁县', 3, 511500),
(511525, '高　县', 3, 511500),
(511526, '珙　县', 3, 511500),
(511527, '筠连县', 3, 511500),
(511528, '兴文县', 3, 511500),
(511529, '屏山县', 3, 511500),
(511601, '市辖区', 3, 511600),
(511602, '广安区', 3, 511600),
(511621, '岳池县', 3, 511600),
(511622, '武胜县', 3, 511600),
(511623, '邻水县', 3, 511600),
(511681, '华莹市', 3, 511600),
(511701, '市辖区', 3, 511700),
(511702, '通川区', 3, 511700),
(511721, '达　县', 3, 511700),
(511722, '宣汉县', 3, 511700),
(511723, '开江县', 3, 511700),
(511724, '大竹县', 3, 511700),
(511725, '渠　县', 3, 511700),
(511781, '万源市', 3, 511700),
(511801, '市辖区', 3, 511800),
(511802, '雨城区', 3, 511800),
(511821, '名山县', 3, 511800),
(511822, '荥经县', 3, 511800),
(511823, '汉源县', 3, 511800),
(511824, '石棉县', 3, 511800),
(511825, '天全县', 3, 511800),
(511826, '芦山县', 3, 511800),
(511827, '宝兴县', 3, 511800),
(511901, '市辖区', 3, 511900),
(511902, '巴州区', 3, 511900),
(511921, '通江县', 3, 511900),
(511922, '南江县', 3, 511900),
(511923, '平昌县', 3, 511900),
(512001, '市辖区', 3, 512000),
(512002, '雁江区', 3, 512000),
(512021, '安岳县', 3, 512000),
(512022, '乐至县', 3, 512000),
(512081, '简阳市', 3, 512000),
(513221, '汶川县', 3, 513200),
(513222, '理　县', 3, 513200),
(513223, '茂　县', 3, 513200),
(513224, '松潘县', 3, 513200),
(513225, '九寨沟县', 3, 513200),
(513226, '金川县', 3, 513200),
(513227, '小金县', 3, 513200),
(513228, '黑水县', 3, 513200),
(513229, '马尔康县', 3, 513200),
(513230, '壤塘县', 3, 513200),
(513231, '阿坝县', 3, 513200),
(513232, '若尔盖县', 3, 513200),
(513233, '红原县', 3, 513200),
(513321, '康定县', 3, 513300),
(513322, '泸定县', 3, 513300),
(513323, '丹巴县', 3, 513300),
(513324, '九龙县', 3, 513300),
(513325, '雅江县', 3, 513300),
(513326, '道孚县', 3, 513300),
(513327, '炉霍县', 3, 513300),
(513328, '甘孜县', 3, 513300),
(513329, '新龙县', 3, 513300),
(513330, '德格县', 3, 513300),
(513331, '白玉县', 3, 513300),
(513332, '石渠县', 3, 513300),
(513333, '色达县', 3, 513300),
(513334, '理塘县', 3, 513300),
(513335, '巴塘县', 3, 513300),
(513336, '乡城县', 3, 513300),
(513337, '稻城县', 3, 513300),
(513338, '得荣县', 3, 513300),
(513401, '西昌市', 3, 513400),
(513422, '木里藏族自治县', 3, 513400),
(513423, '盐源县', 3, 513400),
(513424, '德昌县', 3, 513400),
(513425, '会理县', 3, 513400),
(513426, '会东县', 3, 513400),
(513427, '宁南县', 3, 513400),
(513428, '普格县', 3, 513400),
(513429, '布拖县', 3, 513400),
(513430, '金阳县', 3, 513400),
(513431, '昭觉县', 3, 513400),
(513432, '喜德县', 3, 513400),
(513433, '冕宁县', 3, 513400),
(513434, '越西县', 3, 513400),
(513435, '甘洛县', 3, 513400),
(513436, '美姑县', 3, 513400),
(513437, '雷波县', 3, 513400),
(520101, '市辖区', 3, 520100),
(520102, '南明区', 3, 520100),
(520103, '云岩区', 3, 520100),
(520111, '花溪区', 3, 520100),
(520112, '乌当区', 3, 520100),
(520113, '白云区', 3, 520100),
(520114, '小河区', 3, 520100),
(520121, '开阳县', 3, 520100),
(520122, '息烽县', 3, 520100),
(520123, '修文县', 3, 520100),
(520181, '清镇市', 3, 520100),
(520201, '钟山区', 3, 520200),
(520203, '六枝特区', 3, 520200),
(520221, '水城县', 3, 520200),
(520222, '盘　县', 3, 520200),
(520301, '市辖区', 3, 520300),
(520302, '红花岗区', 3, 520300),
(520303, '汇川区', 3, 520300),
(520321, '遵义县', 3, 520300),
(520322, '桐梓县', 3, 520300),
(520323, '绥阳县', 3, 520300),
(520324, '正安县', 3, 520300),
(520325, '道真仡佬族苗族自治县', 3, 520300),
(520326, '务川仡佬族苗族自治县', 3, 520300),
(520327, '凤冈县', 3, 520300),
(520328, '湄潭县', 3, 520300),
(520329, '余庆县', 3, 520300),
(520330, '习水县', 3, 520300),
(520381, '赤水市', 3, 520300),
(520382, '仁怀市', 3, 520300),
(520401, '市辖区', 3, 520400),
(520402, '西秀区', 3, 520400),
(520421, '平坝县', 3, 520400),
(520422, '普定县', 3, 520400),
(520423, '镇宁布依族苗族自治县', 3, 520400),
(520424, '关岭布依族苗族自治县', 3, 520400),
(520425, '紫云苗族布依族自治县', 3, 520400),
(522201, '铜仁市', 3, 522200),
(522222, '江口县', 3, 522200),
(522223, '玉屏侗族自治县', 3, 522200),
(522224, '石阡县', 3, 522200),
(522225, '思南县', 3, 522200),
(522226, '印江土家族苗族自治县', 3, 522200),
(522227, '德江县', 3, 522200),
(522228, '沿河土家族自治县', 3, 522200),
(522229, '松桃苗族自治县', 3, 522200),
(522230, '万山特区', 3, 522200),
(522301, '兴义市', 3, 522300),
(522322, '兴仁县', 3, 522300),
(522323, '普安县', 3, 522300),
(522324, '晴隆县', 3, 522300),
(522325, '贞丰县', 3, 522300),
(522326, '望谟县', 3, 522300),
(522327, '册亨县', 3, 522300),
(522328, '安龙县', 3, 522300),
(522401, '毕节市', 3, 522400),
(522422, '大方县', 3, 522400),
(522423, '黔西县', 3, 522400),
(522424, '金沙县', 3, 522400),
(522425, '织金县', 3, 522400),
(522426, '纳雍县', 3, 522400),
(522427, '威宁彝族回族苗族自治县', 3, 522400),
(522428, '赫章县', 3, 522400),
(522601, '凯里市', 3, 522600),
(522622, '黄平县', 3, 522600),
(522623, '施秉县', 3, 522600),
(522624, '三穗县', 3, 522600),
(522625, '镇远县', 3, 522600),
(522626, '岑巩县', 3, 522600),
(522627, '天柱县', 3, 522600),
(522628, '锦屏县', 3, 522600),
(522629, '剑河县', 3, 522600),
(522630, '台江县', 3, 522600),
(522631, '黎平县', 3, 522600),
(522632, '榕江县', 3, 522600),
(522633, '从江县', 3, 522600),
(522634, '雷山县', 3, 522600),
(522635, '麻江县', 3, 522600),
(522636, '丹寨县', 3, 522600),
(522701, '都匀市', 3, 522700),
(522702, '福泉市', 3, 522700),
(522722, '荔波县', 3, 522700),
(522723, '贵定县', 3, 522700),
(522725, '瓮安县', 3, 522700),
(522726, '独山县', 3, 522700),
(522727, '平塘县', 3, 522700),
(522728, '罗甸县', 3, 522700),
(522729, '长顺县', 3, 522700),
(522730, '龙里县', 3, 522700),
(522731, '惠水县', 3, 522700),
(522732, '三都水族自治县', 3, 522700),
(530101, '市辖区', 3, 530100),
(530102, '五华区', 3, 530100),
(530103, '盘龙区', 3, 530100),
(530111, '官渡区', 3, 530100),
(530112, '西山区', 3, 530100),
(530113, '东川区', 3, 530100),
(530121, '呈贡县', 3, 530100),
(530122, '晋宁县', 3, 530100),
(530124, '富民县', 3, 530100),
(530125, '宜良县', 3, 530100),
(530126, '石林彝族自治县', 3, 530100),
(530127, '嵩明县', 3, 530100),
(530128, '禄劝彝族苗族自治县', 3, 530100),
(530129, '寻甸回族彝族自治县', 3, 530100),
(530181, '安宁市', 3, 530100),
(530301, '市辖区', 3, 530300),
(530302, '麒麟区', 3, 530300),
(530321, '马龙县', 3, 530300),
(530322, '陆良县', 3, 530300),
(530323, '师宗县', 3, 530300),
(530324, '罗平县', 3, 530300),
(530325, '富源县', 3, 530300),
(530326, '会泽县', 3, 530300),
(530328, '沾益县', 3, 530300),
(530381, '宣威市', 3, 530300),
(530401, '市辖区', 3, 530400),
(530402, '红塔区', 3, 530400),
(530421, '江川县', 3, 530400),
(530422, '澄江县', 3, 530400),
(530423, '通海县', 3, 530400),
(530424, '华宁县', 3, 530400),
(530425, '易门县', 3, 530400),
(530426, '峨山彝族自治县', 3, 530400),
(530427, '新平彝族傣族自治县', 3, 530400),
(530428, '元江哈尼族彝族傣族自治县', 3, 530400),
(530501, '市辖区', 3, 530500),
(530502, '隆阳区', 3, 530500),
(530521, '施甸县', 3, 530500),
(530522, '腾冲县', 3, 530500),
(530523, '龙陵县', 3, 530500),
(530524, '昌宁县', 3, 530500),
(530601, '市辖区', 3, 530600),
(530602, '昭阳区', 3, 530600),
(530621, '鲁甸县', 3, 530600),
(530622, '巧家县', 3, 530600),
(530623, '盐津县', 3, 530600),
(530624, '大关县', 3, 530600),
(530625, '永善县', 3, 530600),
(530626, '绥江县', 3, 530600),
(530627, '镇雄县', 3, 530600),
(530628, '彝良县', 3, 530600),
(530629, '威信县', 3, 530600),
(530630, '水富县', 3, 530600),
(530701, '市辖区', 3, 530700),
(530702, '古城区', 3, 530700),
(530721, '玉龙纳西族自治县', 3, 530700),
(530722, '永胜县', 3, 530700),
(530723, '华坪县', 3, 530700),
(530724, '宁蒗彝族自治县', 3, 530700),
(530801, '市辖区', 3, 530800),
(530802, '翠云区', 3, 530800),
(530821, '普洱哈尼族彝族自治县', 3, 530800),
(530822, '墨江哈尼族自治县', 3, 530800),
(530823, '景东彝族自治县', 3, 530800),
(530824, '景谷傣族彝族自治县', 3, 530800),
(530825, '镇沅彝族哈尼族拉祜族自治县', 3, 530800),
(530826, '江城哈尼族彝族自治县', 3, 530800),
(530827, '孟连傣族拉祜族佤族自治县', 3, 530800),
(530828, '澜沧拉祜族自治县', 3, 530800),
(530829, '西盟佤族自治县', 3, 530800),
(530901, '市辖区', 3, 530900),
(530902, '临翔区', 3, 530900),
(530921, '凤庆县', 3, 530900),
(530922, '云　县', 3, 530900),
(530923, '永德县', 3, 530900),
(530924, '镇康县', 3, 530900),
(530925, '双江拉祜族佤族布朗族傣族自治县', 3, 530900),
(530926, '耿马傣族佤族自治县', 3, 530900),
(530927, '沧源佤族自治县', 3, 530900),
(532301, '楚雄市', 3, 532300),
(532322, '双柏县', 3, 532300),
(532323, '牟定县', 3, 532300),
(532324, '南华县', 3, 532300),
(532325, '姚安县', 3, 532300),
(532326, '大姚县', 3, 532300),
(532327, '永仁县', 3, 532300),
(532328, '元谋县', 3, 532300),
(532329, '武定县', 3, 532300),
(532331, '禄丰县', 3, 532300),
(532501, '个旧市', 3, 532500),
(532502, '开远市', 3, 532500),
(532522, '蒙自县', 3, 532500),
(532523, '屏边苗族自治县', 3, 532500),
(532524, '建水县', 3, 532500),
(532525, '石屏县', 3, 532500),
(532526, '弥勒县', 3, 532500),
(532527, '泸西县', 3, 532500),
(532528, '元阳县', 3, 532500),
(532529, '红河县', 3, 532500),
(532530, '金平苗族瑶族傣族自治县', 3, 532500),
(532531, '绿春县', 3, 532500),
(532532, '河口瑶族自治县', 3, 532500),
(532621, '文山县', 3, 532600),
(532622, '砚山县', 3, 532600),
(532623, '西畴县', 3, 532600),
(532624, '麻栗坡县', 3, 532600),
(532625, '马关县', 3, 532600),
(532626, '丘北县', 3, 532600),
(532627, '广南县', 3, 532600),
(532628, '富宁县', 3, 532600),
(532801, '景洪市', 3, 532800),
(532822, '勐海县', 3, 532800),
(532823, '勐腊县', 3, 532800),
(532901, '大理市', 3, 532900),
(532922, '漾濞彝族自治县', 3, 532900),
(532923, '祥云县', 3, 532900),
(532924, '宾川县', 3, 532900),
(532925, '弥渡县', 3, 532900),
(532926, '南涧彝族自治县', 3, 532900),
(532927, '巍山彝族回族自治县', 3, 532900),
(532928, '永平县', 3, 532900),
(532929, '云龙县', 3, 532900),
(532930, '洱源县', 3, 532900),
(532931, '剑川县', 3, 532900),
(532932, '鹤庆县', 3, 532900),
(533102, '瑞丽市', 3, 533100),
(533103, '潞西市', 3, 533100),
(533122, '梁河县', 3, 533100),
(533123, '盈江县', 3, 533100),
(533124, '陇川县', 3, 533100),
(533321, '泸水县', 3, 533300),
(533323, '福贡县', 3, 533300),
(533324, '贡山独龙族怒族自治县', 3, 533300),
(533325, '兰坪白族普米族自治县', 3, 533300),
(533421, '香格里拉县', 3, 533400),
(533422, '德钦县', 3, 533400),
(533423, '维西傈僳族自治县', 3, 533400),
(540101, '市辖区', 3, 540100),
(540102, '城关区', 3, 540100),
(540121, '林周县', 3, 540100),
(540122, '当雄县', 3, 540100),
(540123, '尼木县', 3, 540100),
(540124, '曲水县', 3, 540100),
(540125, '堆龙德庆县', 3, 540100),
(540126, '达孜县', 3, 540100),
(540127, '墨竹工卡县', 3, 540100),
(542121, '昌都县', 3, 542100),
(542122, '江达县', 3, 542100),
(542123, '贡觉县', 3, 542100),
(542124, '类乌齐县', 3, 542100),
(542125, '丁青县', 3, 542100),
(542126, '察雅县', 3, 542100),
(542127, '八宿县', 3, 542100),
(542128, '左贡县', 3, 542100),
(542129, '芒康县', 3, 542100),
(542132, '洛隆县', 3, 542100),
(542133, '边坝县', 3, 542100),
(542221, '乃东县', 3, 542200),
(542222, '扎囊县', 3, 542200),
(542223, '贡嘎县', 3, 542200),
(542224, '桑日县', 3, 542200),
(542225, '琼结县', 3, 542200),
(542226, '曲松县', 3, 542200),
(542227, '措美县', 3, 542200),
(542228, '洛扎县', 3, 542200),
(542229, '加查县', 3, 542200),
(542231, '隆子县', 3, 542200),
(542232, '错那县', 3, 542200),
(542233, '浪卡子县', 3, 542200),
(542301, '日喀则市', 3, 542300),
(542322, '南木林县', 3, 542300),
(542323, '江孜县', 3, 542300),
(542324, '定日县', 3, 542300),
(542325, '萨迦县', 3, 542300),
(542326, '拉孜县', 3, 542300),
(542327, '昂仁县', 3, 542300),
(542328, '谢通门县', 3, 542300),
(542329, '白朗县', 3, 542300),
(542330, '仁布县', 3, 542300),
(542331, '康马县', 3, 542300),
(542332, '定结县', 3, 542300),
(542333, '仲巴县', 3, 542300),
(542334, '亚东县', 3, 542300),
(542335, '吉隆县', 3, 542300),
(542336, '聂拉木县', 3, 542300),
(542337, '萨嘎县', 3, 542300),
(542338, '岗巴县', 3, 542300),
(542421, '那曲县', 3, 542400),
(542422, '嘉黎县', 3, 542400),
(542423, '比如县', 3, 542400),
(542424, '聂荣县', 3, 542400),
(542425, '安多县', 3, 542400),
(542426, '申扎县', 3, 542400),
(542427, '索　县', 3, 542400),
(542428, '班戈县', 3, 542400),
(542429, '巴青县', 3, 542400),
(542430, '尼玛县', 3, 542400),
(542521, '普兰县', 3, 542500),
(542522, '札达县', 3, 542500),
(542523, '噶尔县', 3, 542500),
(542524, '日土县', 3, 542500),
(542525, '革吉县', 3, 542500),
(542526, '改则县', 3, 542500),
(542527, '措勤县', 3, 542500),
(542621, '林芝县', 3, 542600),
(542622, '工布江达县', 3, 542600),
(542623, '米林县', 3, 542600),
(542624, '墨脱县', 3, 542600),
(542625, '波密县', 3, 542600),
(542626, '察隅县', 3, 542600),
(542627, '朗　县', 3, 542600),
(610101, '市辖区', 3, 610100),
(610102, '新城区', 3, 610100),
(610103, '碑林区', 3, 610100),
(610104, '莲湖区', 3, 610100),
(610111, '灞桥区', 3, 610100),
(610112, '未央区', 3, 610100),
(610113, '雁塔区', 3, 610100),
(610114, '阎良区', 3, 610100),
(610115, '临潼区', 3, 610100),
(610116, '长安区', 3, 610100),
(610122, '蓝田县', 3, 610100),
(610124, '周至县', 3, 610100),
(610125, '户　县', 3, 610100),
(610126, '高陵县', 3, 610100),
(610201, '市辖区', 3, 610200),
(610202, '王益区', 3, 610200),
(610203, '印台区', 3, 610200),
(610204, '耀州区', 3, 610200),
(610222, '宜君县', 3, 610200),
(610301, '市辖区', 3, 610300),
(610302, '渭滨区', 3, 610300),
(610303, '金台区', 3, 610300),
(610304, '陈仓区', 3, 610300),
(610322, '凤翔县', 3, 610300),
(610323, '岐山县', 3, 610300),
(610324, '扶风县', 3, 610300),
(610326, '眉　县', 3, 610300),
(610327, '陇　县', 3, 610300),
(610328, '千阳县', 3, 610300),
(610329, '麟游县', 3, 610300),
(610330, '凤　县', 3, 610300),
(610331, '太白县', 3, 610300),
(610401, '市辖区', 3, 610400),
(610402, '秦都区', 3, 610400),
(610403, '杨凌区', 3, 610400),
(610404, '渭城区', 3, 610400),
(610422, '三原县', 3, 610400),
(610423, '泾阳县', 3, 610400),
(610424, '乾　县', 3, 610400),
(610425, '礼泉县', 3, 610400),
(610426, '永寿县', 3, 610400),
(610427, '彬　县', 3, 610400),
(610428, '长武县', 3, 610400),
(610429, '旬邑县', 3, 610400),
(610430, '淳化县', 3, 610400),
(610431, '武功县', 3, 610400),
(610481, '兴平市', 3, 610400),
(610501, '市辖区', 3, 610500),
(610502, '临渭区', 3, 610500),
(610521, '华　县', 3, 610500),
(610522, '潼关县', 3, 610500),
(610523, '大荔县', 3, 610500),
(610524, '合阳县', 3, 610500),
(610525, '澄城县', 3, 610500),
(610526, '蒲城县', 3, 610500),
(610527, '白水县', 3, 610500),
(610528, '富平县', 3, 610500),
(610581, '韩城市', 3, 610500),
(610582, '华阴市', 3, 610500),
(610601, '市辖区', 3, 610600),
(610602, '宝塔区', 3, 610600),
(610621, '延长县', 3, 610600),
(610622, '延川县', 3, 610600),
(610623, '子长县', 3, 610600),
(610624, '安塞县', 3, 610600),
(610625, '志丹县', 3, 610600),
(610626, '吴旗县', 3, 610600),
(610627, '甘泉县', 3, 610600),
(610628, '富　县', 3, 610600),
(610629, '洛川县', 3, 610600),
(610630, '宜川县', 3, 610600),
(610631, '黄龙县', 3, 610600),
(610632, '黄陵县', 3, 610600),
(610701, '市辖区', 3, 610700),
(610702, '汉台区', 3, 610700),
(610721, '南郑县', 3, 610700),
(610722, '城固县', 3, 610700),
(610723, '洋　县', 3, 610700),
(610724, '西乡县', 3, 610700),
(610725, '勉　县', 3, 610700),
(610726, '宁强县', 3, 610700),
(610727, '略阳县', 3, 610700),
(610728, '镇巴县', 3, 610700),
(610729, '留坝县', 3, 610700),
(610730, '佛坪县', 3, 610700),
(610801, '市辖区', 3, 610800),
(610802, '榆阳区', 3, 610800),
(610821, '神木县', 3, 610800),
(610822, '府谷县', 3, 610800),
(610823, '横山县', 3, 610800),
(610824, '靖边县', 3, 610800),
(610825, '定边县', 3, 610800),
(610826, '绥德县', 3, 610800),
(610827, '米脂县', 3, 610800),
(610828, '佳　县', 3, 610800),
(610829, '吴堡县', 3, 610800),
(610830, '清涧县', 3, 610800),
(610831, '子洲县', 3, 610800),
(610901, '市辖区', 3, 610900),
(610902, '汉滨区', 3, 610900),
(610921, '汉阴县', 3, 610900),
(610922, '石泉县', 3, 610900),
(610923, '宁陕县', 3, 610900),
(610924, '紫阳县', 3, 610900),
(610925, '岚皋县', 3, 610900),
(610926, '平利县', 3, 610900),
(610927, '镇坪县', 3, 610900),
(610928, '旬阳县', 3, 610900),
(610929, '白河县', 3, 610900),
(611001, '市辖区', 3, 611000),
(611002, '商州区', 3, 611000),
(611021, '洛南县', 3, 611000),
(611022, '丹凤县', 3, 611000),
(611023, '商南县', 3, 611000),
(611024, '山阳县', 3, 611000),
(611025, '镇安县', 3, 611000),
(611026, '柞水县', 3, 611000),
(620101, '市辖区', 3, 620100),
(620102, '城关区', 3, 620100),
(620103, '七里河区', 3, 620100),
(620104, '西固区', 3, 620100),
(620105, '安宁区', 3, 620100),
(620111, '红古区', 3, 620100),
(620121, '永登县', 3, 620100),
(620122, '皋兰县', 3, 620100),
(620123, '榆中县', 3, 620100),
(620201, '市辖区', 3, 620200),
(620301, '市辖区', 3, 620300),
(620302, '金川区', 3, 620300),
(620321, '永昌县', 3, 620300),
(620401, '市辖区', 3, 620400),
(620402, '白银区', 3, 620400),
(620403, '平川区', 3, 620400),
(620421, '靖远县', 3, 620400),
(620422, '会宁县', 3, 620400),
(620423, '景泰县', 3, 620400),
(620501, '市辖区', 3, 620500),
(620502, '秦城区', 3, 620500),
(620503, '北道区', 3, 620500),
(620521, '清水县', 3, 620500),
(620522, '秦安县', 3, 620500),
(620523, '甘谷县', 3, 620500),
(620524, '武山县', 3, 620500),
(620525, '张家川回族自治县', 3, 620500),
(620601, '市辖区', 3, 620600),
(620602, '凉州区', 3, 620600),
(620621, '民勤县', 3, 620600),
(620622, '古浪县', 3, 620600),
(620623, '天祝藏族自治县', 3, 620600),
(620701, '市辖区', 3, 620700),
(620702, '甘州区', 3, 620700),
(620721, '肃南裕固族自治县', 3, 620700),
(620722, '民乐县', 3, 620700),
(620723, '临泽县', 3, 620700),
(620724, '高台县', 3, 620700),
(620725, '山丹县', 3, 620700),
(620801, '市辖区', 3, 620800),
(620802, '崆峒区', 3, 620800),
(620821, '泾川县', 3, 620800),
(620822, '灵台县', 3, 620800),
(620823, '崇信县', 3, 620800),
(620824, '华亭县', 3, 620800),
(620825, '庄浪县', 3, 620800),
(620826, '静宁县', 3, 620800),
(620901, '市辖区', 3, 620900),
(620902, '肃州区', 3, 620900),
(620921, '金塔县', 3, 620900),
(620922, '安西县', 3, 620900),
(620923, '肃北蒙古族自治县', 3, 620900),
(620924, '阿克塞哈萨克族自治县', 3, 620900),
(620981, '玉门市', 3, 620900),
(620982, '敦煌市', 3, 620900),
(621001, '市辖区', 3, 621000),
(621002, '西峰区', 3, 621000),
(621021, '庆城县', 3, 621000),
(621022, '环　县', 3, 621000),
(621023, '华池县', 3, 621000),
(621024, '合水县', 3, 621000),
(621025, '正宁县', 3, 621000),
(621026, '宁　县', 3, 621000),
(621027, '镇原县', 3, 621000),
(621101, '市辖区', 3, 621100),
(621102, '安定区', 3, 621100),
(621121, '通渭县', 3, 621100),
(621122, '陇西县', 3, 621100),
(621123, '渭源县', 3, 621100),
(621124, '临洮县', 3, 621100),
(621125, '漳　县', 3, 621100),
(621126, '岷　县', 3, 621100),
(621201, '市辖区', 3, 621200),
(621202, '武都区', 3, 621200),
(621221, '成　县', 3, 621200),
(621222, '文　县', 3, 621200),
(621223, '宕昌县', 3, 621200),
(621224, '康　县', 3, 621200),
(621225, '西和县', 3, 621200),
(621226, '礼　县', 3, 621200),
(621227, '徽　县', 3, 621200),
(621228, '两当县', 3, 621200),
(622901, '临夏市', 3, 622900),
(622921, '临夏县', 3, 622900),
(622922, '康乐县', 3, 622900),
(622923, '永靖县', 3, 622900),
(622924, '广河县', 3, 622900),
(622925, '和政县', 3, 622900),
(622926, '东乡族自治县', 3, 622900),
(622927, '积石山保安族东乡族撒拉族自治县', 3, 622900),
(623001, '合作市', 3, 623000),
(623021, '临潭县', 3, 623000),
(623022, '卓尼县', 3, 623000),
(623023, '舟曲县', 3, 623000),
(623024, '迭部县', 3, 623000),
(623025, '玛曲县', 3, 623000),
(623026, '碌曲县', 3, 623000),
(623027, '夏河县', 3, 623000),
(630101, '市辖区', 3, 630100),
(630102, '城东区', 3, 630100),
(630103, '城中区', 3, 630100),
(630104, '城西区', 3, 630100),
(630105, '城北区', 3, 630100),
(630121, '大通回族土族自治县', 3, 630100),
(630122, '湟中县', 3, 630100),
(630123, '湟源县', 3, 630100),
(632121, '平安县', 3, 632100),
(632122, '民和回族土族自治县', 3, 632100),
(632123, '乐都县', 3, 632100),
(632126, '互助土族自治县', 3, 632100),
(632127, '化隆回族自治县', 3, 632100),
(632128, '循化撒拉族自治县', 3, 632100),
(632221, '门源回族自治县', 3, 632200),
(632222, '祁连县', 3, 632200),
(632223, '海晏县', 3, 632200),
(632224, '刚察县', 3, 632200),
(632321, '同仁县', 3, 632300),
(632322, '尖扎县', 3, 632300),
(632323, '泽库县', 3, 632300),
(632324, '河南蒙古族自治县', 3, 632300),
(632521, '共和县', 3, 632500),
(632522, '同德县', 3, 632500),
(632523, '贵德县', 3, 632500),
(632524, '兴海县', 3, 632500),
(632525, '贵南县', 3, 632500),
(632621, '玛沁县', 3, 632600),
(632622, '班玛县', 3, 632600),
(632623, '甘德县', 3, 632600),
(632624, '达日县', 3, 632600),
(632625, '久治县', 3, 632600),
(632626, '玛多县', 3, 632600),
(632721, '玉树县', 3, 632700),
(632722, '杂多县', 3, 632700),
(632723, '称多县', 3, 632700),
(632724, '治多县', 3, 632700),
(632725, '囊谦县', 3, 632700),
(632726, '曲麻莱县', 3, 632700),
(632801, '格尔木市', 3, 632800),
(632802, '德令哈市', 3, 632800),
(632821, '乌兰县', 3, 632800),
(632822, '都兰县', 3, 632800),
(632823, '天峻县', 3, 632800),
(640101, '市辖区', 3, 640100),
(640104, '兴庆区', 3, 640100),
(640105, '西夏区', 3, 640100),
(640106, '金凤区', 3, 640100),
(640121, '永宁县', 3, 640100),
(640122, '贺兰县', 3, 640100),
(640181, '灵武市', 3, 640100),
(640201, '市辖区', 3, 640200),
(640202, '大武口区', 3, 640200),
(640205, '惠农区', 3, 640200),
(640221, '平罗县', 3, 640200),
(640301, '市辖区', 3, 640300),
(640302, '利通区', 3, 640300),
(640323, '盐池县', 3, 640300),
(640324, '同心县', 3, 640300),
(640381, '青铜峡市', 3, 640300),
(640401, '市辖区', 3, 640400),
(640402, '原州区', 3, 640400),
(640422, '西吉县', 3, 640400),
(640423, '隆德县', 3, 640400),
(640424, '泾源县', 3, 640400),
(640425, '彭阳县', 3, 640400),
(640501, '市辖区', 3, 640500),
(640502, '沙坡头区', 3, 640500),
(640521, '中宁县', 3, 640500),
(640522, '海原县', 3, 640500),
(650101, '市辖区', 3, 650100),
(650102, '天山区', 3, 650100),
(650103, '沙依巴克区', 3, 650100),
(650104, '新市区', 3, 650100),
(650105, '水磨沟区', 3, 650100),
(650106, '头屯河区', 3, 650100),
(650107, '达坂城区', 3, 650100),
(650108, '东山区', 3, 650100),
(650121, '乌鲁木齐县', 3, 650100),
(650201, '市辖区', 3, 650200),
(650202, '独山子区', 3, 650200),
(650203, '克拉玛依区', 3, 650200),
(650204, '白碱滩区', 3, 650200),
(650205, '乌尔禾区', 3, 650200),
(652101, '吐鲁番市', 3, 652100),
(652122, '鄯善县', 3, 652100),
(652123, '托克逊县', 3, 652100),
(652201, '哈密市', 3, 652200),
(652222, '巴里坤哈萨克自治县', 3, 652200),
(652223, '伊吾县', 3, 652200),
(652301, '昌吉市', 3, 652300),
(652302, '阜康市', 3, 652300),
(652303, '米泉市', 3, 652300),
(652323, '呼图壁县', 3, 652300),
(652324, '玛纳斯县', 3, 652300),
(652325, '奇台县', 3, 652300),
(652327, '吉木萨尔县', 3, 652300),
(652328, '木垒哈萨克自治县', 3, 652300),
(652701, '博乐市', 3, 652700),
(652722, '精河县', 3, 652700),
(652723, '温泉县', 3, 652700),
(652801, '库尔勒市', 3, 652800),
(652822, '轮台县', 3, 652800),
(652823, '尉犁县', 3, 652800),
(652824, '若羌县', 3, 652800),
(652825, '且末县', 3, 652800),
(652826, '焉耆回族自治县', 3, 652800),
(652827, '和静县', 3, 652800),
(652828, '和硕县', 3, 652800),
(652829, '博湖县', 3, 652800),
(652901, '阿克苏市', 3, 652900),
(652922, '温宿县', 3, 652900),
(652923, '库车县', 3, 652900),
(652924, '沙雅县', 3, 652900),
(652925, '新和县', 3, 652900),
(652926, '拜城县', 3, 652900),
(652927, '乌什县', 3, 652900),
(652928, '阿瓦提县', 3, 652900),
(652929, '柯坪县', 3, 652900),
(653001, '阿图什市', 3, 653000),
(653022, '阿克陶县', 3, 653000),
(653023, '阿合奇县', 3, 653000),
(653024, '乌恰县', 3, 653000),
(653101, '喀什市', 3, 653100),
(653121, '疏附县', 3, 653100),
(653122, '疏勒县', 3, 653100),
(653123, '英吉沙县', 3, 653100),
(653124, '泽普县', 3, 653100),
(653125, '莎车县', 3, 653100),
(653126, '叶城县', 3, 653100),
(653127, '麦盖提县', 3, 653100),
(653128, '岳普湖县', 3, 653100),
(653129, '伽师县', 3, 653100),
(653130, '巴楚县', 3, 653100),
(653131, '塔什库尔干塔吉克自治县', 3, 653100),
(653201, '和田市', 3, 653200),
(653221, '和田县', 3, 653200),
(653222, '墨玉县', 3, 653200),
(653223, '皮山县', 3, 653200),
(653224, '洛浦县', 3, 653200),
(653225, '策勒县', 3, 653200),
(653226, '于田县', 3, 653200),
(653227, '民丰县', 3, 653200),
(654002, '伊宁市', 3, 654000),
(654003, '奎屯市', 3, 654000),
(654021, '伊宁县', 3, 654000),
(654022, '察布查尔锡伯自治县', 3, 654000),
(654023, '霍城县', 3, 654000),
(654024, '巩留县', 3, 654000),
(654025, '新源县', 3, 654000),
(654026, '昭苏县', 3, 654000),
(654027, '特克斯县', 3, 654000),
(654028, '尼勒克县', 3, 654000),
(654201, '塔城市', 3, 654200),
(654202, '乌苏市', 3, 654200),
(654221, '额敏县', 3, 654200),
(654223, '沙湾县', 3, 654200),
(654224, '托里县', 3, 654200),
(654225, '裕民县', 3, 654200),
(654226, '和布克赛尔蒙古自治县', 3, 654200),
(654301, '阿勒泰市', 3, 654300),
(654321, '布尔津县', 3, 654300),
(654322, '富蕴县', 3, 654300),
(654323, '福海县', 3, 654300),
(654324, '哈巴河县', 3, 654300),
(654325, '青河县', 3, 654300),
(654326, '吉木乃县', 3, 654300),
(659001, '石河子市', 3, 659000),
(659002, '阿拉尔市', 3, 659000),
(659003, '图木舒克市', 3, 659000),
(659004, '五家渠市', 3, 659000),
(810001, '香港', 2, 810000),
(810002, '中西区', 3, 810001),
(810003, '九龙城区', 3, 810001),
(810004, '南区', 3, 810001),
(810005, '黄大仙区', 3, 810001),
(810006, '油尖旺区', 3, 810001),
(810007, '葵青区', 3, 810001),
(810008, '西贡区', 3, 810001),
(810009, '屯门区', 3, 810001),
(810010, '荃湾区', 3, 810001),
(810011, '东区', 3, 810001),
(810012, '观塘区', 3, 810001),
(810013, '深水步区', 3, 810001),
(810014, '湾仔区', 3, 810001),
(810015, '离岛区', 3, 810001),
(810016, '北区', 3, 810001),
(810017, '沙田区', 3, 810001),
(810018, '大埔区', 3, 810001),
(810019, '元朗区', 3, 810001),
(820001, '澳门', 2, 820000),
(820002, '澳门', 3, 820001),
(710001, '台北市', 2, 710000),
(710002, '台北县', 3, 710001),
(710003, '基隆市', 2, 710000),
(910005, '中山市', 3, 442000),
(710004, '花莲县', 3, 710003),
(910006, '东莞市', 3, 441900);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_expression`
--

CREATE TABLE `ocenter_expression` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `path` varchar(200) NOT NULL,
  `driver` varchar(50) NOT NULL,
  `create_time` int(11) NOT NULL,
  `expression_pkg_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_expression_pkg`
--

CREATE TABLE `ocenter_expression_pkg` (
  `id` int(11) NOT NULL,
  `pkg_title` varchar(50) NOT NULL,
  `pkg_name` varchar(50) NOT NULL,
  `path` varchar(200) NOT NULL,
  `driver` varchar(50) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_field`
--

CREATE TABLE `ocenter_field` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `field_data` varchar(1000) NOT NULL,
  `createTime` int(11) NOT NULL,
  `changeTime` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_field_group`
--

CREATE TABLE `ocenter_field_group` (
  `id` int(11) NOT NULL,
  `profile_name` varchar(25) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `createTime` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `visiable` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_field_group`
--

INSERT INTO `ocenter_field_group` (`id`, `profile_name`, `status`, `createTime`, `sort`, `visiable`) VALUES
(1, '身份认证', -1, 1403847366, 0, 0),
(2, '开发者资料', -1, 1423537648, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_field_setting`
--

CREATE TABLE `ocenter_field_setting` (
  `id` int(11) NOT NULL,
  `field_name` varchar(25) NOT NULL,
  `profile_group_id` int(11) NOT NULL,
  `visiable` tinyint(4) NOT NULL DEFAULT '1',
  `required` tinyint(4) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL,
  `form_type` varchar(25) NOT NULL,
  `form_default_value` varchar(200) NOT NULL,
  `validation` varchar(25) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `createTime` int(11) NOT NULL,
  `child_form_type` varchar(25) NOT NULL,
  `input_tips` varchar(100) NOT NULL COMMENT '输入提示'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_field_setting`
--

INSERT INTO `ocenter_field_setting` (`id`, `field_name`, `profile_group_id`, `visiable`, `required`, `sort`, `form_type`, `form_default_value`, `validation`, `status`, `createTime`, `child_form_type`, `input_tips`) VALUES
(1, 'qq', 1, 1, 1, 0, 'input', '', '', 1, 1409045825, 'string', ''),
(2, '生日', 1, 1, 1, 0, 'time', '', '', 1, 1423537409, '', ''),
(3, '擅长语言', 2, 1, 1, 0, 'select', 'Java|C++|Python|php|object c|ruby', '', 1, 1423537693, '', ''),
(4, '承接项目', 2, 1, 1, 0, 'radio', '是|否', '', 1, 1423537733, '', ''),
(5, '简介', 2, 1, 1, 0, 'textarea', '', '', 1, 1423537770, '', '简单介绍入行以来的工作经验，项目经验'),
(6, '其他技能', 2, 1, 1, 0, 'checkbox', 'PhotoShop|Flash', '', 1, 1423537834, '', '');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_file`
--

CREATE TABLE `ocenter_file` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '文件ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '原始文件名',
  `savename` varchar(100) NOT NULL DEFAULT '' COMMENT '保存名称',
  `savepath` varchar(255) NOT NULL DEFAULT '' COMMENT '文件保存路径',
  `ext` char(5) NOT NULL DEFAULT '' COMMENT '文件后缀',
  `mime` char(40) NOT NULL DEFAULT '' COMMENT '文件mime类型',
  `size` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文件大小',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `location` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '文件保存位置',
  `create_time` int(10) UNSIGNED NOT NULL COMMENT '上传时间',
  `driver` varchar(50) NOT NULL,
  `download_num` int(11) NOT NULL DEFAULT '0' COMMENT '下载数'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文件表';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_follow`
--

CREATE TABLE `ocenter_follow` (
  `id` int(11) NOT NULL COMMENT 'id',
  `follow_who` int(11) NOT NULL COMMENT '关注谁',
  `who_follow` int(11) NOT NULL COMMENT '谁关注',
  `create_time` int(11) NOT NULL,
  `alias` varchar(40) NOT NULL COMMENT '备注',
  `group_id` int(11) NOT NULL COMMENT '分组ID',
  `trust` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='关注表';

--
-- 转存表中的数据 `ocenter_follow`
--

INSERT INTO `ocenter_follow` (`id`, `follow_who`, `who_follow`, `create_time`, `alias`, `group_id`, `trust`) VALUES
(43, 100, 1, 1507780145, '', 0, 1),
(4, 100, 116, 1507777482, '', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_hooks`
--

CREATE TABLE `ocenter_hooks` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text NOT NULL COMMENT '描述',
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '类型',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 ''，''分割'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_hooks`
--

INSERT INTO `ocenter_hooks` (`id`, `name`, `description`, `type`, `update_time`, `addons`) VALUES
(38, 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', 1, 0, ''),
(39, 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', 1, 0, 'SuperLinks'),
(40, 'adminEditor', '后台内容编辑页编辑器', 1, 1378982734, 'EditorForAdmin'),
(41, 'AdminIndex', '首页小格子个性化显示', 1, 1382596073, 'SiteStat,SyncLogin,DevTeam,SystemInfo,LocalComment'),
(42, 'topicComment', '评论提交方式扩展钩子。', 1, 1380163518, ''),
(43, 'app_begin', '应用开始', 2, 1384481614, 'Iswaf'),
(44, 'checkIn', '签到', 1, 1395371353, 'CheckIn'),
(46, 'support', '赞', 1, 1398264759, 'Support'),
(47, 'localComment', '本地评论插件', 1, 1399440321, 'LocalComment'),
(48, 'weiboType', '微博类型', 1, 1409121894, ''),
(49, 'repost', '转发钩子', 1, 1403668286, ''),
(50, 'syncLogin', '第三方登陆位置', 1, 1403700579, 'SyncLogin'),
(51, 'syncMeta', '第三方登陆meta接口', 1, 1403700633, 'SyncLogin'),
(52, 'J_China_City', '每个系统都需要的一个中国省市区三级联动插件。', 1, 1403841931, 'ChinaCity'),
(54, 'imageSlider', '图片轮播钩子', 1, 1407144022, ''),
(55, 'friendLink', '友情链接插件', 1, 1407156413, 'SuperLinks'),
(56, 'beforeSendWeibo', '在发微博之前预处理微博', 2, 1408084504, 'InsertFile'),
(57, 'beforeSendRepost', '转发微博前的预处理钩子', 2, 1408085689, ''),
(58, 'parseWeiboContent', '解析微博内容钩子', 2, 1409121261, ''),
(59, 'userConfig', '用户配置页面钩子', 1, 1417137557, 'SyncLogin'),
(60, 'weiboSide', '微博侧边钩子', 1, 1417063425, 'Retopic,Recommend'),
(61, 'personalMenus', '顶部导航栏个人下拉菜单', 1, 1417146501, ''),
(62, 'dealPicture', '上传图片处理', 2, 1417139975, ''),
(63, 'ucenterSideMenu', '用户中心左侧菜单', 1, 1417161205, ''),
(64, 'afterTop', '顶部导航之后的钩子，调用公告等', 1, 1429671392, ''),
(65, 'report', '举报钩子', 1, 1429511732, 'Report'),
(66, 'handleAction', '行为的额外操作', 2, 1433300260, 'CheckIn'),
(67, 'uploadDriver', '附件图片上传引擎', 2, 1435306269, ''),
(68, 'sms', '短信插件钩子', 2, 1437382105, 'SmsBao'),
(69, 'filterHtmlContent', '渲染富文本', 2, 1441951420, ''),
(70, 'parseContent', '解析内容', 2, 1445828128, 'Sensitive'),
(71, 'tool', '返回顶部，右下角工具栏', 1, 1445828128, ''),
(72, 'homeIndex', '网站首页', 2, 1445828128, '');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_iexpression`
--

CREATE TABLE `ocenter_iexpression` (
  `id` int(11) NOT NULL,
  `path` varchar(200) NOT NULL,
  `driver` varchar(50) NOT NULL,
  `md5` varchar(32) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_iexpression_link`
--

CREATE TABLE `ocenter_iexpression_link` (
  `id` int(11) NOT NULL,
  `iexpression_id` int(11) NOT NULL,
  `uid` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_invite`
--

CREATE TABLE `ocenter_invite` (
  `id` int(11) NOT NULL COMMENT 'PRIMARY_KEY',
  `invite_type` int(11) NOT NULL COMMENT '邀请类型id',
  `code` varchar(25) NOT NULL COMMENT '邀请码',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `can_num` int(10) NOT NULL COMMENT '可以注册用户（含升级）',
  `already_num` int(10) NOT NULL COMMENT '已经注册用户（含升级）',
  `end_time` int(11) NOT NULL COMMENT '有效期至',
  `status` tinyint(2) NOT NULL COMMENT '0：已用完，1：还可注册，2：用户取消邀请，-1：管理员删除',
  `create_time` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邀请码表';

--
-- 转存表中的数据 `ocenter_invite`
--

INSERT INTO `ocenter_invite` (`id`, `invite_type`, `code`, `uid`, `can_num`, `already_num`, `end_time`, `status`, `create_time`) VALUES
(1, 1, 'M9TR2PPaHWY', 1, 100000, 0, 1818494575, 1, 1502961774),
(2, 1, 'tRHJpsLdZ4D', 100, 100000, 0, 1822029816, 1, 1506497016),
(3, 1, '5inDkb6eO3J', 111, 100000, 0, 1822294704, 1, 1506761904),
(4, 1, '6jX592wC4to', 112, 100000, 0, 1822636424, 1, 1507103624),
(5, 1, 'KrUClYtXs9A', 114, 100000, 0, 1822637896, 1, 1507105096),
(6, 1, 'eJUc3hjAu7D', 114, 100000, 0, 1822637896, 1, 1507105096),
(7, 1, 'qdsYAyhNoBb', 115, 100000, 0, 1822868919, 1, 1507336118),
(8, 1, 'qPnZreyfEOX', 116, 100000, 0, 1823310270, 1, 1507777470),
(9, 1, 'P2k5awcJRDF', 117, 100000, 0, 1823570048, 1, 1508037247),
(10, 1, '6JCQFzxBBdg', 120, 100000, 0, 1823582271, 1, 1508049471),
(11, 1, 'VSLptxyXHGP', 120, 100000, 0, 1823582271, 1, 1508049471),
(12, 1, 'Ys7B9IWWj45', 122, 100000, 0, 1823582820, 1, 1508050020),
(13, 1, 'q0bzWF3vVYt', 123, 100000, 0, 1823583530, 1, 1508050730),
(14, 1, 'NDIY1SJR7dn', 123, 100000, 0, 1823583530, 1, 1508050730),
(15, 1, 'zq8sZMrEywA', 147, 100000, 0, 1823660902, 1, 1508128102),
(16, 1, 'YkWEsSinYqD', 146, 100000, 0, 1823674982, 1, 1508142182);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_invite_buy_log`
--

CREATE TABLE `ocenter_invite_buy_log` (
  `id` int(11) NOT NULL COMMENT 'PRIMARY_KEY',
  `invite_type` int(11) NOT NULL COMMENT '邀请类型id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `num` int(10) NOT NULL COMMENT '可邀请名额',
  `content` varchar(200) NOT NULL COMMENT '记录信息',
  `create_time` int(11) NOT NULL COMMENT '创建时间（做频率用）'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户购买邀请名额记录';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_invite_log`
--

CREATE TABLE `ocenter_invite_log` (
  `id` int(11) NOT NULL COMMENT 'PRIMARY_KEY',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `inviter_id` int(11) NOT NULL COMMENT '邀请人id',
  `invite_id` int(11) NOT NULL COMMENT '邀请码id',
  `content` varchar(200) NOT NULL COMMENT '记录内容',
  `create_time` int(11) NOT NULL COMMENT '创建时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邀请注册成功记录表';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_invite_type`
--

CREATE TABLE `ocenter_invite_type` (
  `id` int(11) NOT NULL COMMENT 'PRIMARY_KEY',
  `title` varchar(25) NOT NULL COMMENT '标题',
  `length` int(10) NOT NULL DEFAULT '11' COMMENT '验证码长度',
  `time` varchar(50) NOT NULL COMMENT '有效时长，带单位的时间',
  `cycle_num` int(10) NOT NULL COMMENT '周期内可购买个数',
  `cycle_time` varchar(50) NOT NULL COMMENT '周期时长，带单位的时间',
  `roles` varchar(50) NOT NULL COMMENT '绑定角色ids',
  `auth_groups` varchar(50) NOT NULL COMMENT '允许购买的权限组ids',
  `pay_score` int(10) NOT NULL COMMENT '购买消耗积分',
  `pay_score_type` int(11) NOT NULL COMMENT '购买消耗积分类型',
  `income_score` int(10) NOT NULL COMMENT '每邀请成功一个用户，邀请者增加积分',
  `income_score_type` int(11) NOT NULL COMMENT '邀请成功后增加积分类型id',
  `is_follow` tinyint(2) NOT NULL COMMENT '邀请成功后是否互相关注',
  `status` tinyint(2) NOT NULL,
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邀请注册码类型表';

--
-- 转存表中的数据 `ocenter_invite_type`
--

INSERT INTO `ocenter_invite_type` (`id`, `title`, `length`, `time`, `cycle_num`, `cycle_time`, `roles`, `auth_groups`, `pay_score`, `pay_score_type`, `income_score`, `income_score_type`, `is_follow`, `status`, `create_time`, `update_time`) VALUES
(1, '系统默认邀请码', 11, '10 year', 1, '1 second', '[1]', '[1],[2]', 0, 1, 0, 1, 1, 1, 1466749163, 1471247871);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_invite_user_info`
--

CREATE TABLE `ocenter_invite_user_info` (
  `id` int(11) NOT NULL COMMENT 'PRIMARY_KEY',
  `invite_type` int(11) NOT NULL COMMENT '邀请类型id',
  `uid` int(11) NOT NULL COMMENT '用户id',
  `num` int(11) NOT NULL COMMENT '可邀请名额',
  `already_num` int(11) NOT NULL COMMENT '已邀请名额',
  `success_num` int(11) NOT NULL COMMENT '成功邀请名额'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邀请注册用户信息';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_letter`
--

CREATE TABLE `ocenter_letter` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '发送人',
  `puid` int(11) NOT NULL DEFAULT '0' COMMENT '接收人',
  `content` text NOT NULL COMMENT '内容',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(2) NOT NULL,
  `is_read` tinyint(2) NOT NULL COMMENT '是否查看：0未查看，1已查看'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='私信表';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_local_comment`
--

CREATE TABLE `ocenter_local_comment` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `app` text NOT NULL,
  `mod` text NOT NULL,
  `row_id` int(11) NOT NULL,
  `parse` int(11) NOT NULL,
  `content` varchar(1000) NOT NULL,
  `create_time` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `area` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_market`
--

CREATE TABLE `ocenter_market` (
  `id` int(11) NOT NULL,
  `market` char(20) NOT NULL,
  `high` decimal(10,2) NOT NULL,
  `low` decimal(10,2) NOT NULL,
  `bid` decimal(10,2) NOT NULL,
  `ask` decimal(10,2) NOT NULL,
  `close` decimal(10,2) NOT NULL,
  `avg` decimal(10,2) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='BTC市场价格表';

--
-- 转存表中的数据 `ocenter_market`
--

INSERT INTO `ocenter_market` (`id`, `market`, `high`, `low`, `bid`, `ask`, `close`, `avg`, `create_time`) VALUES
(1, 'BitfinexUSD', '5744.00', '5580.00', '5654.00', '5654.70', '5654.50', '5662.00', 1508485421),
(2, 'BitstampUSD', '5735.14', '5589.80', '5640.51', '5649.08', '5648.98', '5674.14', 1508485419),
(3, 'CoincheckJPY', '640888.00', '626666.00', '633483.00', '633693.00', '633478.00', '633777.00', 1508485418),
(4, 'BtcboxJPY', '640000.00', '627960.00', '632629.00', '634251.00', '634251.00', '633980.00', 1508485417),
(5, 'KorbitKRW', '6529500.00', '6402500.00', '6455500.00', '6460000.00', '6460000.00', '6466000.00', 1508485414),
(6, 'HitbtcUSD', '5736.70', '5579.77', '5656.64', '5658.05', '5657.35', '5658.24', 1508485413),
(7, 'KrakenEUR', '4838.00', '4748.80', '4803.60', '4803.70', '4803.70', '4801.37', 1508485412),
(8, 'OkcoinUSD', '5730.98', '5450.01', '5536.21', '5672.27', '5600.00', '5590.50', 1508485410);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_member`
--

CREATE TABLE `ocenter_member` (
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户ID',
  `nickname` char(32) NOT NULL DEFAULT '' COMMENT '昵称',
  `sex` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '性别',
  `trade_count` int(10) DEFAULT '0' COMMENT '交易次数',
  `trade_score` tinyint(1) NOT NULL COMMENT '评价得分',
  `login` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '登录次数',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `reg_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '注册时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `last_login_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '会员状态',
  `last_login_role` int(11) NOT NULL,
  `show_role` int(11) NOT NULL COMMENT '个人主页显示角色',
  `signature` text NOT NULL,
  `pos_province` int(11) NOT NULL,
  `pos_city` int(11) NOT NULL,
  `pos_district` int(11) NOT NULL,
  `pos_community` int(11) NOT NULL,
  `score1` double DEFAULT '0' COMMENT '用户积分',
  `score2` double DEFAULT '0' COMMENT 'score2',
  `score3` double DEFAULT '0' COMMENT 'score3',
  `score4` double DEFAULT '0' COMMENT 'score4',
  `btc_count` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `eth_count` decimal(10,4) NOT NULL DEFAULT '0.0000',
  `btc` decimal(10,8) NOT NULL DEFAULT '0.00000000',
  `eth` decimal(10,8) NOT NULL DEFAULT '0.00000000',
  `con_check` int(11) NOT NULL DEFAULT '0',
  `total_check` int(11) NOT NULL DEFAULT '0',
  `fans` int(11) NOT NULL DEFAULT '0' COMMENT '粉丝数',
  `trust` int(11) NOT NULL,
  `session_id` varchar(225) NOT NULL,
  `lang` char(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员表';

--
-- 转存表中的数据 `ocenter_member`
--

INSERT INTO `ocenter_member` (`uid`, `nickname`, `sex`, `trade_count`, `trade_score`, `login`, `reg_ip`, `reg_time`, `last_login_ip`, `last_login_time`, `status`, `last_login_role`, `show_role`, `signature`, `pos_province`, `pos_city`, `pos_district`, `pos_community`, `score1`, `score2`, `score3`, `score4`, `btc_count`, `eth_count`, `btc`, `eth`, `con_check`, `total_check`, `fans`, `trust`, `session_id`, `lang`) VALUES
(1, 'yoyocoinsdd', 0, 100, 100, 162, 0, 1506049131, 2130706433, 1508576069, 1, 1, 1, '', 0, 0, 0, 0, 21, 0, 0, 0, '0.0000', '0.0000', '0.00000000', '0.00000000', 0, 2, 1, 0, 'kvmbfpuu2k0kphjp7tedjafd55', 'zh-cn'),
(100, 'vbboy', 0, 11, 90, 44, 2130706433, 1506497016, 2130706433, 1508037773, 1, 1, 1, '', 0, 0, 0, 0, 31, 0, 0, 0, '0.0000', '0.0000', '0.00000000', '0.00000000', 0, 0, 1, 0, '3gg6o22cp54uicff8f8a0qoht1', 'zh-cn'),
(147, 'vbboy2015', 0, 0, 0, 1, 2130706433, 1508127984, 2130706433, 1508128102, 1, 0, 0, '', 0, 0, 0, 0, 10, 0, 0, 0, '0.0000', '0.0000', '0.00000000', '0.00000000', 0, 0, 0, 0, 'p1peklh4au69n8cdmeor0sbt76', ''),
(146, 'sonia', 0, 0, 0, 10, 2130706433, 1508127913, 2130706433, 1508504956, 1, 0, 0, '', 0, 0, 0, 0, 20, 0, 0, 0, '0.0000', '0.0000', '0.00000000', '0.00000000', 0, 0, 0, 0, 'dn4uab1qmbh2db25j49bndkt06', ''),
(145, 'bobo', 0, 0, 0, 0, 2130706433, 1508127436, 0, 0, 3, 0, 0, '', 0, 0, 0, 0, 0, 0, 0, 0, '0.0000', '0.0000', '0.00000000', '0.00000000', 0, 0, 0, 0, '', '');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_menu`
--

CREATE TABLE `ocenter_menu` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '文档ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  `group` varchar(50) DEFAULT '' COMMENT '分组',
  `is_dev` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否仅开发者模式可见',
  `icon` varchar(20) NOT NULL COMMENT '导航图标',
  `module` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_menu`
--

INSERT INTO `ocenter_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`, `icon`, `module`) VALUES
(1, '首页', 0, 1, 'Index/index', 0, '', '', 0, 'home', ''),
(2, '用户与身份', 0, 2, 'User/index', 0, '', '', 0, 'user', ''),
(3, '用户信息', 2, 2, 'User/index', 0, '', '用户配置', 0, '', ''),
(4, '积分规则', 197, 3, 'User/action', 0, '', '行为与积分', 0, '', ''),
(5, '新增用户行为', 4, 0, 'User/addaction', 0, '', '', 0, '', ''),
(6, '编辑用户行为', 4, 0, 'User/editaction', 0, '', '', 0, '', ''),
(7, '保存用户行为', 4, 0, 'User/saveAction', 0, '\"用户->用户行为\"保存编辑和新增的用户行为', '', 0, '', ''),
(8, '变更行为状态', 4, 0, 'User/setStatus', 0, '\"用户->用户行为\"中的启用,禁用和删除权限', '', 0, '', ''),
(9, '禁用会员', 4, 0, 'User/changeStatus?method=forbidUser', 0, '\"用户->用户信息\"中的禁用', '', 0, '', ''),
(10, '启用会员', 4, 0, 'User/changeStatus?method=resumeUser', 0, '\"用户->用户信息\"中的启用', '', 0, '', ''),
(11, '删除会员', 4, 0, 'User/changeStatus?method=deleteUser', 0, '\"用户->用户信息\"中的删除', '', 0, '', ''),
(12, '权限组管理', 2, 5, 'AuthManager/index', 0, '', '用户配置', 0, '', ''),
(13, '删除', 12, 0, 'AuthManager/changeStatus?method=deleteGroup', 0, '删除权限组', '', 1, '', ''),
(14, '禁用', 12, 0, 'AuthManager/changeStatus?method=forbidGroup', 0, '禁用权限组', '', 1, '', ''),
(15, '恢复', 12, 0, 'AuthManager/changeStatus?method=resumeGroup', 0, '恢复已禁用的权限组', '', 1, '', ''),
(16, '新增', 12, 0, 'AuthManager/createGroup', 0, '创建新的权限组', '', 1, '', ''),
(17, '编辑', 12, 0, 'AuthManager/editGroup', 0, '编辑权限组名称和描述', '', 1, '', ''),
(18, '保存权限组', 12, 0, 'AuthManager/writeGroup', 0, '新增和编辑权限组的\"保存\"按钮', '', 0, '', ''),
(19, '授权', 12, 0, 'AuthManager/group', 0, '\"后台 \\ 用户 \\ 用户信息\"列表页的\"授权\"操作按钮,用于设置用户所属权限组', '', 0, '', ''),
(20, '访问授权', 12, 0, 'AuthManager/access', 0, '\"后台 \\ 用户 \\ 权限管理\"列表页的\"访问授权\"操作按钮', '', 0, '', ''),
(21, '成员授权', 12, 0, 'AuthManager/user', 0, '\"后台 \\ 用户 \\ 权限管理\"列表页的\"成员授权\"操作按钮', '', 0, '', ''),
(22, '解除授权', 12, 0, 'AuthManager/removeFromGroup', 0, '\"成员授权\"列表页内的解除授权操作按钮', '', 0, '', ''),
(23, '保存成员授权', 12, 0, 'AuthManager/addToGroup', 0, '\"用户信息\"列表页\"授权\"时的\"保存\"按钮和\"成员授权\"里右上角的\"添加\"按钮)', '', 0, '', ''),
(24, '分类授权', 12, 0, 'AuthManager/category', 0, '\"后台 \\ 用户 \\ 权限管理\"列表页的\"分类授权\"操作按钮', '', 0, '', ''),
(25, '保存分类授权', 12, 0, 'AuthManager/addToCategory', 0, '\"分类授权\"页面的\"保存\"按钮', '', 0, '', ''),
(26, '模型授权', 12, 0, 'AuthManager/modelauth', 0, '\"后台 \\ 用户 \\ 权限管理\"列表页的\"模型授权\"操作按钮', '', 0, '', ''),
(27, '保存模型授权', 12, 0, 'AuthManager/addToModel', 0, '\"分类授权\"页面的\"保存\"按钮', '', 0, '', ''),
(28, '新增权限节点', 12, 0, 'AuthManager/addNode', 1, '', '', 1, '', ''),
(29, '前台权限管理', 12, 0, 'AuthManager/accessUser', 1, '', '权限管理', 0, '', ''),
(30, '删除权限节点', 12, 0, 'AuthManager/deleteNode', 1, '', '', 1, '', ''),
(31, '行为日志', 197, 4, 'Action/actionlog', 0, '', '行为与积分', 0, '', ''),
(32, '查看行为日志', 31, 0, 'action/edit', 1, '', '', 0, '', ''),
(33, '修改密码', 2, 3, 'User/updatePassword', 1, '', '', 0, '', ''),
(34, '修改昵称', 2, 4, 'User/updateNickname', 1, '', '', 0, '', ''),
(35, '查看用户', 2, 1, 'Rank/userList', 0, '', '头衔管理', 0, '', ''),
(36, '用户头衔列表', 35, 0, 'Rank/userRankList', 1, '', '', 0, '', ''),
(37, '关联新头衔', 35, 0, 'Rank/userAddRank', 1, '', '', 0, '', ''),
(38, '编辑头衔关联', 35, 0, 'Rank/userChangeRank', 1, '', '', 0, '', ''),
(39, '扩展资料', 2, 3, 'User/profile', 0, '', '标签与资料', 0, '', ''),
(40, '添加、编辑分组', 39, 0, 'Admin/User/editProfile', 0, '', '', 0, '', ''),
(41, '分组排序', 39, 0, 'Admin/User/sortProfile', 0, '', '', 0, '', ''),
(42, '字段列表', 39, 0, 'Admin/User/field', 0, '', '', 0, '', ''),
(43, '添加、编辑字段', 39, 0, 'Admin/User/editFieldSetting', 0, '', '', 0, '', ''),
(44, '字段排序', 39, 0, 'Admin/User/sortField', 0, '', '', 0, '', ''),
(45, '用户扩展资料列表', 2, 7, 'User/expandinfo_select', 0, '', '标签与资料', 0, '', ''),
(46, '扩展资料详情', 45, 0, 'User/expandinfo_details', 0, '', '', 0, '', ''),
(47, '待审核用户头衔', 2, 2, 'Rank/rankVerify', 0, '', '头衔管理', 0, '', ''),
(48, '被驳回的头衔申请', 2, 3, 'Rank/rankVerifyFailure', 0, '', '头衔管理', 0, '', ''),
(49, '转移权限组', 2, 7, 'User/changeGroup', 1, '批量转移权限组', '', 0, '', ''),
(50, '基础配置', 2, 1, 'UserConfig/index', 0, '', '用户配置', 0, '', ''),
(51, '积分类型', 197, 1, 'User/scoreList', 0, '', '行为与积分', 0, '', ''),
(52, '新增/编辑类型', 197, 2, 'User/editScoreType', 1, '', '行为与积分', 0, '', ''),
(53, '充值积分', 197, 5, 'User/recharge', 1, '', '', 0, '用户管理', ''),
(54, '头衔列表', 2, 6, 'Rank/index', 0, '', '头衔管理', 0, '', ''),
(55, '添加头衔', 54, 2, 'Rank/editRank', 1, '', '头衔管理', 0, '', ''),
(57, '插件管理', 105, 4, 'Addons/index', 0, '', '本地', 0, '', ''),
(58, '钩子管理', 57, 2, 'Addons/hooks', 0, '', '本地', 0, '', ''),
(59, '创建', 57, 0, 'Addons/create', 0, '服务器上创建插件结构向导', '', 0, '', ''),
(60, '检测创建', 57, 0, 'Addons/checkForm', 0, '检测插件是否可以创建', '', 0, '', ''),
(61, '预览', 57, 0, 'Addons/preview', 0, '预览插件定义类文件', '', 0, '', ''),
(62, '快速生成插件', 57, 0, 'Addons/build', 0, '开始生成插件结构', '', 0, '', ''),
(64, '设置', 57, 0, 'Addons/config', 0, '设置插件配置', '', 0, '', ''),
(65, '禁用', 57, 0, 'Addons/disable', 0, '禁用插件', '', 0, '', ''),
(66, '启用', 57, 0, 'Addons/enable', 0, '启用插件', '', 0, '', ''),
(67, '安装', 57, 0, 'Addons/install', 0, '安装插件', '', 0, '', ''),
(68, '卸载', 57, 0, 'Addons/uninstall', 0, '卸载插件', '', 0, '', ''),
(69, '更新配置', 57, 0, 'Addons/saveconfig', 0, '更新插件配置处理', '', 0, '', ''),
(70, '插件后台列表', 57, 0, 'Addons/adminList', 0, '', '', 0, '', ''),
(71, 'URL方式访问插件', 57, 0, 'Addons/execute', 0, '控制是否有权限通过url访问插件控制器方法', '', 0, '', ''),
(72, '新增钩子', 58, 0, 'Addons/addHook', 0, '', '', 0, '', ''),
(73, '编辑钩子', 58, 0, 'Addons/edithook', 0, '', '', 0, '', ''),
(74, '系统', 0, 7, 'Config/group', 0, '', '', 0, 'windows', ''),
(75, '网站设置', 74, 1, 'Config/group', 0, '', '网站设置', 0, '', ''),
(76, '配置管理', 74, 7, 'Config/index', 0, '', '网站设置', 0, '', ''),
(77, '编辑', 76, 0, 'Config/edit', 0, '新增编辑和保存配置', '', 0, '', ''),
(78, '删除', 76, 0, 'Config/del', 0, '删除配置', '', 0, '', ''),
(79, '新增', 76, 0, 'Config/add', 0, '新增配置', '', 0, '', ''),
(80, '保存', 76, 0, 'Config/save', 0, '保存配置', '', 0, '', ''),
(81, '排序', 76, 0, 'Config/sort', 1, '', '', 0, '', ''),
(82, '后台菜单管理', 74, 6, 'Menu/index', 0, '', '导航管理', 1, '', ''),
(83, '新增', 82, 0, 'Menu/add', 0, '', '系统设置', 0, '', ''),
(84, '编辑', 82, 0, 'Menu/edit', 0, '', '', 0, '', ''),
(85, '导入', 82, 0, 'Menu/import', 0, '', '', 0, '', ''),
(86, '排序', 82, 0, 'Menu/sort', 1, '', '', 0, '', ''),
(87, '顶部导航', 74, 3, 'Channel/index', 0, '', '导航管理', 0, '', ''),
(88, '新增', 87, 0, 'Channel/add', 0, '', '', 0, '', ''),
(89, '编辑', 87, 0, 'Channel/edit', 0, '', '', 0, '', ''),
(90, '删除', 87, 0, 'Channel/del', 0, '', '', 0, '', ''),
(91, '排序', 87, 0, 'Channel/sort', 1, '', '', 0, '', ''),
(92, '备份数据库', 74, 8, 'Database/index?type=export', 0, '', '网站数据管理', 0, '', ''),
(93, '备份', 92, 0, 'Database/export', 0, '备份数据库', '', 0, '', ''),
(94, '优化表', 92, 0, 'Database/optimize', 0, '优化数据表', '', 0, '', ''),
(95, '修复表', 92, 0, 'Database/repair', 0, '修复数据表', '', 0, '', ''),
(96, '还原数据库', 74, 9, 'Database/index?type=import', 0, '', '网站数据管理', 0, '', ''),
(97, '恢复', 96, 0, 'Database/import', 0, '数据库恢复', '', 0, '', ''),
(98, '删除', 96, 0, 'Database/del', 0, '删除备份文件', '', 0, '', ''),
(99, 'SEO规则管理', 197, 8, 'SEO/index', 0, '', 'SEO管理', 0, '', ''),
(100, '新增、编辑', 99, 0, 'SEO/editRule', 0, '', '', 0, '', ''),
(101, '排序', 99, 0, 'SEO/sortRule', 1, '', '', 0, '', ''),
(102, 'SEO规则回收站', 197, 9, 'SEO/ruleTrash', 0, '', 'SEO管理', 0, '', ''),
(103, '全部补丁', 74, 16, 'Update/quick', 1, '', '升级补丁', 0, '', ''),
(104, '新增补丁', 74, 15, 'Update/addpack', 1, '', '升级补丁', 0, '', ''),
(105, '扩展', 0, 11, 'Cloud/index', 0, '', '', 0, 'cloud', ''),
(106, '模块安装', 105, 3, 'module/install', 1, '', '本地', 0, '', ''),
(107, '模块管理', 105, 5, 'module/lists', 0, '', '本地', 0, '', ''),
(108, '卸载模块', 105, 7, 'module/uninstall', 1, '', '本地', 0, '', ''),
(109, '授权', 0, 6, 'authorize/ssoSetting', 0, '', '', 1, 'lock', ''),
(110, '单点登录配置', 109, 0, 'Authorize/ssoSetting', 0, '', '单点登录', 1, '', ''),
(111, '应用列表', 109, 0, 'Authorize/ssolist', 0, '', '单点登录', 1, '', ''),
(112, '新增/编辑应用', 109, 0, 'authorize/editssoapp', 1, '', '单点登录', 1, '', ''),
(114, '用户行为', 197, 6, 'ActionLimit/limitList', 0, '', '行为与积分', 0, '', ''),
(115, '新增/编辑行为限制', 197, 7, 'ActionLimit/editLimit', 1, '', '行为与积分', 0, '', ''),
(117, '身份列表', 2, 1, 'Role/index', 0, '', '身份管理', 0, '', ''),
(118, '编辑身份', 2, 2, 'Role/editRole', 1, '', '身份管理', 0, '', ''),
(119, '启用、禁用、删除身份', 2, 3, 'Role/setStatus', 1, '', '身份管理', 0, '', ''),
(120, '身份排序', 2, 4, 'Role/sort', 1, '', '身份管理', 0, '', ''),
(121, '默认积分配置', 117, 0, 'Role/configScore', 1, '', '', 0, '', ''),
(122, '默认权限配置', 117, 0, 'Role/configAuth', 1, '', '', 0, '', ''),
(123, '默认头像配置', 117, 0, 'Role/configAvatar', 1, '', '', 0, '', ''),
(124, '默认头衔配置', 117, 0, 'Role/configRank', 1, '', '', 0, '', ''),
(125, '默认字段管理', 117, 0, 'Role/configField', 1, '', '', 0, '', ''),
(126, '身份分组', 2, 5, 'Role/group', 0, '', '身份管理', 0, '', ''),
(127, '编辑分组', 126, 0, 'Role/editGroup', 1, '', '', 0, '', ''),
(128, '删除分组', 126, 0, 'Role/deleteGroup', 1, '', '', 0, '', ''),
(129, '身份基本信息配置', 2, 6, 'Role/config', 1, '', '身份管理', 0, '', ''),
(130, '用户身份归属列表', 2, 7, 'Role/userList', 0, '', '身份管理', 0, '', ''),
(131, '设置用户状态', 130, 0, 'Role/setUserStatus', 1, '', '', 0, '', ''),
(132, '审核用户', 130, 0, 'Role/setUserAudit', 1, '', '', 0, '', ''),
(133, '迁移用户', 130, 0, 'Role/changeRole', 1, '', '', 0, '', ''),
(134, '上传默认头像', 123, 0, 'Role/uploadPicture', 1, '', '', 0, '', ''),
(135, '邀请码类型', 197, 8, 'Invite/index', 0, '', '邀请注册管理', 0, '', ''),
(136, '邀请码管理', 197, 9, 'Invite/invite', 0, '', '邀请注册管理', 0, '', ''),
(137, '是否开启邀请注册', 2, 10, 'Invite/config', 0, '', '用户配置', 0, '', ''),
(138, '兑换记录', 197, 11, 'Invite/buyLog', 0, '', '邀请注册管理', 0, '', ''),
(139, '用户邀请记录', 197, 12, 'Invite/inviteLog', 0, '', '邀请注册管理', 0, '', ''),
(140, '邀请码列表', 197, 13, 'Invite/userInfo', 0, '', '邀请注册管理', 0, '', ''),
(141, '编辑邀请注册类型', 135, 0, 'Invite/edit', 1, '', '', 0, '', ''),
(142, '删除邀请', 135, 0, 'Invite/setStatus', 1, '', '', 0, '', ''),
(143, '删除邀请码', 136, 0, 'Invite/delete', 1, '', '', 0, '', ''),
(144, '生成邀请码', 136, 0, 'Invite/createCode', 1, '', '', 0, '', ''),
(145, '删除无用邀请码', 136, 0, 'Invite/deleteTrue', 1, '', '', 0, '', ''),
(146, '导出cvs', 136, 0, 'Invite/cvs', 1, '', '', 0, '', ''),
(147, '用户信息编辑', 140, 0, 'Invite/editUserInfo', 1, '', '', 0, '', ''),
(148, '删除日志', 31, 0, 'Action/remove', 1, '', '', 0, '', ''),
(149, '清空日志', 31, 0, 'Action/clear', 1, '', '', 0, '', ''),
(150, '设置积分状态', 51, 0, 'User/setTypeStatus', 1, '', '', 0, '', ''),
(151, '删除积分类型', 51, 0, 'User/delType', 1, '', '', 0, '', ''),
(152, '充值积分-获取用户昵称', 53, 0, 'User/getNickname', 1, '', '', 0, '', ''),
(153, '删除菜单', 82, 0, 'Menu/del', 1, '', '', 0, '', ''),
(154, '设置开发者模式可见', 82, 0, 'Menu/toogleDev', 1, '', '', 0, '', ''),
(155, '设置显示隐藏', 82, 0, 'Menu/toogleHide', 1, '', '', 0, '', ''),
(156, '行为限制启用、禁用、删除', 114, 0, 'ActionLimit/setLimitStatus', 1, '', '', 0, '', ''),
(157, '启用、禁用、删除、回收站还原', 99, 0, 'SEO/setRuleStatus', 1, '', '', 0, '', ''),
(158, '回收站彻底删除', 102, 0, 'SEO/doClear', 1, '', '', 0, '', ''),
(159, '初始化无角色用户', 130, 0, 'Role/initUnhaveUser', 1, '', '', 0, '', ''),
(160, '删除钩子', 58, 0, 'Addons/delHook', 0, '', '', 0, '', ''),
(161, '使用补丁', 103, 0, 'Update/usePack', 1, '', '', 0, '', ''),
(162, '查看补丁', 103, 0, 'Update/view', 1, '', '', 0, '', ''),
(163, '删除补丁', 103, 0, 'Update/delPack', 1, '', '', 0, '', ''),
(164, '用户标签列表', 2, 4, 'UserTag/userTag', 0, '', '标签与资料', 0, '', ''),
(165, '添加分类、标签', 164, 0, 'UserTag/add', 1, '', '', 0, '', ''),
(166, '设置分类、标签状态', 164, 0, 'UserTag/setStatus', 1, '', '', 0, '', ''),
(167, '分类、标签回收站', 164, 0, 'UserTag/tagTrash', 1, '', '', 0, '', ''),
(168, '测底删除回收站内容', 164, 0, 'UserTag/userTagClear', 1, '', '', 0, '', ''),
(169, '可拥有标签配置', 2, 14, 'role/configusertag', 1, '', '标签与资料', 0, '', ''),
(170, '编辑模块', 107, 0, 'Module/edit', 1, '', '模块管理', 0, '', ''),
(171, '网站信息', 74, 2, 'Config/website', 0, '', '网站设置', 0, '', ''),
(172, '主题管理', 105, 6, 'Theme/tpls', 0, '', '本地', 0, '', ''),
(173, '使用主题', 105, 8, 'Theme/setTheme', 1, '', '本地', 0, '', ''),
(174, '查看主题', 105, 9, 'Theme/lookTheme', 1, '', '本地', 0, '', ''),
(175, '主题打包下载', 105, 10, 'Theme/packageDownload', 1, '', '本地', 0, '', ''),
(176, '卸载删除主题', 105, 11, 'Theme/delete', 1, '', '本地', 0, '', ''),
(177, '上传安装主题', 105, 12, 'Theme/add', 1, '', '本地', 0, '', ''),
(178, '云市场', 105, 1, 'Cloud/index', 0, '', '云市场', 0, '', ''),
(197, '运营', 0, 4, 'Super_links/index', 0, '', '', 0, 'laptop', ''),
(198, '群发消息用户列表', 74, 99, 'message/userList', 0, '', '群发消息', 0, '', ''),
(199, '群发消息', 197, 5, 'message/sendMessage', 1, '', '群发消息', 0, '', ''),
(200, '在线安装', 178, 0, 'Cloud/install', 1, '', '', 0, '', ''),
(201, '重置用户密码', 3, 0, 'User/initpass', 1, '', '', 0, '', ''),
(202, '自动升级', 105, 2, 'Cloud/update', 0, '', '云市场', 0, '', ''),
(203, '获取版本信息', 202, 0, 'Cloud/version', 1, '', '', 0, '', ''),
(204, '获取文件列表', 202, 0, 'Cloud/getFileList', 1, '', '', 0, '', ''),
(205, '比较本地文件', 202, 0, 'Cloud/compare', 1, '', '', 0, '', ''),
(206, '覆盖文件', 202, 0, 'Cloud/cover', 1, '', '', 0, '', ''),
(207, '更新数据库', 202, 0, 'Cloud/updb', 1, '', '', 0, '', ''),
(208, '更新完成', 202, 0, 'Cloud/finish', 1, '', '', 0, '', ''),
(209, '表情设置', 197, 4, 'Expression/index', 0, '', '表情管理', 0, '', ''),
(210, '添加表情包', 197, 5, 'Expression/add', 1, '', '表情管理', 0, '', ''),
(211, '表情包列表', 197, 6, 'Expression/package', 0, '', '表情管理', 0, '', ''),
(212, '表情列表', 197, 7, 'Expression/expressionList', 1, '', '表情管理', 0, '', ''),
(213, '删除表情包', 197, 8, 'Expression/delPackage', 1, '', '表情管理', 0, '', ''),
(214, '编辑表情包', 197, 9, 'Expression/editPackage', 1, '', '表情管理', 0, '', ''),
(215, '删除表情', 197, 10, 'Expression/delExpression', 1, '', '表情管理', 0, '', ''),
(216, '上传表情包', 197, 11, 'Expression/upload', 1, '', '表情管理', 0, '', ''),
(217, '用户头衔审核', 47, 2, 'Rank/setVerifyStatus', 1, '', '头衔管理', 0, '', ''),
(218, '获取扩展升级列表', 106, 0, 'Cloud/getVersionList', 1, '', '', 0, '', ''),
(219, '自动升级', 178, 0, 'Cloud/updateGoods', 1, '', '', 0, '', ''),
(220, '自动升级1-获取文件列表', 178, 0, 'Cloud/Updating1', 1, '', '', 0, '', ''),
(221, '自动升级2-比较文件', 178, 0, 'Cloud/Updating2', 1, '', '', 0, '', ''),
(222, '自动升级3-升级代码', 178, 0, 'Cloud/updating3', 1, '', '', 0, '', ''),
(223, '自动升级4-导入数据库', 178, 0, 'Cloud/updating4', 1, '', '', 0, '', ''),
(224, '自动升级5-重置版本号', 178, 0, 'Cloud/updating5', 1, '', '', 0, '', ''),
(225, '广告位', 197, 0, 'Adv/pos', 0, '', '广告配置', 0, '', ''),
(226, '广告管理', 197, 0, 'Adv/adv', 0, '', '广告配置', 0, '', ''),
(227, '新增广告', 226, 0, 'Adv/editAdv', 1, '', '', 0, '', ''),
(228, '编辑广告位', 225, 0, 'Adv/editPos', 1, '', '', 0, '', ''),
(229, '设置广告位状态', 225, 0, 'Adv/setPosStatus', 1, '', '', 0, '', ''),
(230, '广告排期', 226, 0, 'Adv/schedule', 1, '', '', 0, '', ''),
(231, '用户导航', 74, 0, 'Channel/user', 0, '', '导航管理', 0, '', 'Core'),
(232, '积分日志', 197, 0, 'Action/scoreLog', 0, '', '行为与积分', 0, '', ''),
(233, '仪表盘', 1, 1, 'Index/index', 0, '', '系统首页', 0, '', ''),
(234, '数据概览', 1, 2, 'Index/stats', 0, '', '系统首页', 0, '', ''),
(235, '公告列表', 197, 0, 'Announce/announceList', 0, '', '友链与公告', 0, '', ''),
(236, '发布公告', 197, 0, 'Announce/add', 0, '', '友链与公告', 0, '', ''),
(237, '设置公告状态', 235, 0, 'Announce/setStatus', 1, '', '公告管理', 0, '', ''),
(238, '公告送达情况', 235, 0, 'Announce/arrive', 1, '', '公告管理', 0, '', ''),
(239, '消息列表', 74, 0, 'Message/messageSessionList', 0, '', '消息管理', 0, '', ''),
(240, '消息模板', 74, 0, 'Message/messageTplList', 0, '', '消息管理', 0, '', ''),
(241, '刷新消息列表', 74, 0, 'Message/sessionRefresh', 1, '', '消息管理', 0, '', ''),
(242, '刷新消息模板列表', 74, 0, 'Message/tplRefresh', 1, '', '消息管理', 0, '', ''),
(243, '计划任务列表', 74, 99, 'admin/schedule/scheduleList', 0, '', '计划任务', 0, '', ''),
(244, '新增/编辑计划任务', 243, 0, 'Schedule/editSchedule', 1, '', '计划任务', 0, '', ''),
(245, '运行/停止计划任务', 243, 0, 'admin/schedule/run', 1, '', '计划任务', 0, '', ''),
(246, '查看日志', 243, 0, 'Schedule/showLog', 1, '', '计划任务', 0, '', ''),
(247, '清空日志', 243, 0, 'admin/schedule/clearLog', 1, '', '计划任务', 0, '', ''),
(248, '重启计划任务', 243, 0, 'Schedule/reRun', 1, '', '计划任务', 0, '', ''),
(249, '设置计划任务状态', 243, 0, 'Schedule/setScheduleStatus', 1, '', '计划任务', 0, '', ''),
(250, '网站统计', 1, 3, 'Count/index', 0, '', '数据统计', 0, '', ''),
(251, '流失率统计', 1, 4, 'Count/lost', 0, '', '数据统计', 0, '', ''),
(252, '留存率统计', 1, 5, 'Count/remain', 0, '', '数据统计', 0, '', ''),
(253, '充值用户统计', 1, 6, 'Count/consumption', 0, '', '数据统计', 0, '', ''),
(254, '活跃用户统计', 1, 7, 'Count/active', 0, '', '数据统计', 0, '', ''),
(255, '设置活跃度绑定的行为', 1, 0, 'Count/setActiveAction', 1, '', '数据统计', 0, '', ''),
(256, '在线用户列表', 1, 8, 'Count/nowUserList', 0, '', '数据统计', 0, '', ''),
(257, '下线在线用户', 1, 0, 'Count/downUser', 1, '', '数据统计', 0, '', ''),
(258, '消息设置', 74, 0, 'Message/config', 0, '', '消息管理', 0, '', ''),
(259, '频道编辑', 87, 0, 'Channel/customEdit', 0, '', '', 0, '', ''),
(260, '添加', 233, 0, 'Index/addTo', 0, '', '', 0, '', ''),
(261, '删除', 233, 0, 'Index/delTile', 0, '', '', 0, '', ''),
(262, '修改', 233, 0, 'Index/setTile', 0, '', '', 0, '', ''),
(263, '友情链接', 197, 0, 'SuperLinks/index', 0, '', '友链与公告', 0, '', ''),
(264, '图片水印设置', 197, 0, 'Picture/config', 0, '', '图片管理', 0, '', ''),
(265, '上传水印图片', 264, 0, 'Picture/uploadWater', 1, '', '图片管理', 0, '', ''),
(266, '图片列表', 197, 0, 'Picture/pictureList', 0, '', '图片管理', 0, '', ''),
(267, '设置图片状态、删除图片', 266, 0, 'Picture/setStatus', 1, '', '图片管理', 0, '', ''),
(268, '敏感词列表', 197, 0, 'Sensitive/index', 0, '', '敏感词', 0, '', ''),
(269, '敏感词设置', 197, 0, 'Sensitive/config', 0, '', '敏感词', 0, '', ''),
(270, '批量添加', 197, 0, 'Sensitive/addMore', 0, '', '敏感词', 0, '', ''),
(271, '新增、编辑敏感词', 268, 0, 'Sensitive/edit', 1, '', '', 0, '', ''),
(272, '设置敏感词状态', 268, 0, 'Sensitive/setStatus', 1, '', '', 0, '', ''),
(273, '自定义表情列表', 197, 0, 'Expression/iexpressionList', 0, '', '表情管理', 0, '', ''),
(274, '允许身份访问', 1, 0, 'Role/moduleRole', 0, '', '模块权限', 0, '', ''),
(275, '认证类型', 2, 0, 'Attest/attestTypes', 0, '', '认证管理', 0, '', 'Ucenter'),
(276, '认证条件设置', 275, 0, 'Attest/setCondition', 1, '', '认证管理', 0, '', 'Ucenter'),
(277, '认证字段管理', 275, 0, 'Attest/setFields', 1, '', '认证管理', 0, '', 'Ucenter'),
(278, '编辑认证类型', 275, 0, 'Attest/editType', 1, '', '认证管理', 0, '', 'Ucenter'),
(279, '认证用户列表', 2, 0, 'Attest/attestList', 0, '', '认证管理', 0, '', ''),
(280, '设置认证状态', 279, 0, 'Attest/setAttestStatus', 1, '', '认证管理', 0, '', ''),
(281, '微信绑定', 2, 0, 'User/bindwechat', 0, '', '用户配置', 0, '', ''),
(10000, '网站主页', 0, 0, 'Home/config', 1, '', '', 0, 'home', 'Home'),
(10001, '基本设置', 10000, 0, 'Home/config', 0, '', '设置', 0, '', 'Home'),
(10002, '微博', 0, 8, 'Weibo/weibo', 1, '', '', 0, '', 'Weibo'),
(10003, '微博管理', 10002, 1, 'Weibo/weibo', 0, '', '微博', 0, '', 'Weibo'),
(10004, '回复管理', 10002, 3, 'Weibo/comment', 0, '', '回复', 0, '', 'Weibo'),
(10005, '编辑微博', 10002, 0, 'Weibo/editWeibo', 1, '', '', 0, '', 'Weibo'),
(10006, '编辑回复', 10002, 0, 'Weibo/editComment', 1, '', '', 0, '', 'Weibo'),
(10007, '微博回收站', 10002, 2, 'Weibo/weiboTrash', 0, '', '微博', 0, '', 'Weibo'),
(10008, '回复回收站', 10002, 4, 'Weibo/commentTrash', 0, '', '回复', 0, '', 'Weibo'),
(10009, '微博设置', 10002, 0, 'Weibo/config', 0, '微博的基本配置', '设置', 0, '', 'Weibo'),
(10010, '话题管理', 10002, 0, 'Weibo/topic', 0, '微博的话题', '话题管理', 0, '', 'Weibo'),
(10011, '置顶微博', 10002, 0, 'Weibo/setWeiboTop', 1, '', '', 0, '', 'Weibo'),
(10012, '设置微博状态', 10002, 0, 'Weibo/setWeiboStatus', 1, '', '', 0, '', 'Weibo'),
(10013, '设置微博评论状态', 10002, 0, 'Weibo/setCommentStatus', 1, '', '', 0, '', 'Weibo'),
(10014, '设置置顶话题', 10002, 0, 'Weibo/setTopicTop', 1, '', '', 0, '', 'Weibo'),
(10015, '删除话题', 10002, 0, 'Weibo/delTopic', 1, '', '', 0, '', 'Weibo'),
(10016, '会员展示', 0, 22, 'People/config', 1, '', '', 0, '', 'People'),
(10017, '基本设置', 10016, 0, 'People/config', 0, '', '配置', 0, '', 'People'),
(10018, '移动版', 0, 0, 'Mob/config', 1, '', '', 0, '', 'Mob'),
(10019, '基本设置', 10018, 0, 'Mob/config', 0, '', '设置', 0, '', 'Mob'),
(10020, '导航管理', 10018, 0, 'mob/navigation', 0, '', '设置', 0, '', 'Mob'),
(13041, '资讯回收站', 13030, 0, 'News/newsTrash', 0, '', '资讯管理', 0, '', 'News'),
(13040, '基础配置', 13030, 0, 'News/config', 0, '', '资讯配置', 0, '', 'News'),
(13039, '编辑、添加资讯', 13037, 0, 'News/editNews', 1, '', '', 0, '', 'News'),
(13000, '商业版客户端', 0, 0, 'admin/bclient/index', 1, '', '', 0, '', 'Bclient'),
(13001, '授权登录', 13000, 0, 'admin/bclient/index', 1, '', '授权登录', 0, '', 'Bclient'),
(13002, '激活过程', 13000, 0, 'admin/bclient/activate', 1, '', '激活过程', 0, '', 'Bclient'),
(13003, '升级扩展', 13000, 0, 'admin/bclient/updateList', 0, '', '客户中心', 0, '', 'Bclient'),
(13004, '升级', 13000, 0, 'admin/bclient/update', 1, '', '客户中心', 0, '', 'Bclient'),
(13005, '执行升级', 13000, 0, 'admin/bclient/doUpdate', 1, '', '客户中心', 0, '', 'Bclient'),
(13006, '已安装扩展', 13000, 0, 'admin/bclient/installed', 0, '', '客户中心', 0, '', 'Bclient'),
(13007, '客户中心', 13000, 0, 'admin/bclient/center', 0, '', '客户中心', 0, '', 'Bclient'),
(13008, '兑换历史', 13000, 0, 'admin/bclient/history', 0, '', '客户中心', 0, '', 'Bclient'),
(13009, '圈子配置', 10002, 0, 'Weibo/crowdConfig', 0, '', '圈子管理', 0, '', ''),
(13010, '圈子类型', 10002, 0, 'Weibo/crowdType', 0, '', '圈子管理', 0, '', ''),
(13011, '圈子列表', 10002, 0, 'Weibo/crowd', 0, '', '圈子管理', 0, '', ''),
(13012, '编辑圈子', 10002, 0, 'Weibo/editCrowd', 0, '', '圈子管理', 0, '', ''),
(13013, '设置圈子类型状态', 10002, 0, 'Weibo/setcrowdtypestatus', 1, '', '圈子管理', 0, '', ''),
(13014, '设置圈子状态', 10002, 0, 'Weibo/setcrowdstatus', 1, '', '圈子管理', 0, '', ''),
(13015, '圈子是否可发送微博', 10002, 0, 'Weibo/doCrowdAllowPost', 1, '', '圈子管理', 0, '', ''),
(13016, '执行默认关注', 10002, 0, 'Weibo/followCrowd', 0, '', '圈子管理', 0, '', ''),
(13017, '修复关注数脚本', 10002, 0, 'Weibo/repaircrowdfollow', 1, '', '圈子管理', 0, '', ''),
(13038, '设为到期', 13037, 0, 'News/setDead', 1, '', '', 0, '', 'News'),
(13037, '资讯列表', 13030, 0, 'News/index', 0, '', '资讯管理', 0, 'rss-sign', 'News'),
(13036, '设置分类状态', 13034, 0, 'News/setStatus', 1, '', '', 0, '', 'News'),
(13035, '编辑、添加分类', 13034, 0, 'News/add', 1, '', '', 0, '', 'News'),
(13034, '分类管理', 13030, 0, 'News/newsCategory', 0, '', '资讯配置', 0, '', 'News'),
(13033, '资讯审核失败操作', 13031, 0, 'News/doAudit', 1, '', '', 0, '', 'News'),
(13032, '审核通过', 13031, 0, 'News/setNewsStatus', 1, '', '', 0, '', 'News'),
(13031, '审核列表', 13030, 0, 'News/audit', 0, '', '资讯管理', 0, '', 'News'),
(13030, '资讯', 0, 22, 'News/index', 1, '', '', 0, '', 'News');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_message`
--

CREATE TABLE `ocenter_message` (
  `id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `from_uid` int(11) NOT NULL,
  `to_uid` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `is_read` tinyint(4) NOT NULL,
  `last_toast` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `type` varchar(25) NOT NULL COMMENT '消息类型',
  `tpl` varchar(100) NOT NULL COMMENT '消息模板'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='消息表';

--
-- 转存表中的数据 `ocenter_message`
--

INSERT INTO `ocenter_message` (`id`, `content_id`, `from_uid`, `to_uid`, `create_time`, `is_read`, `last_toast`, `status`, `type`, `tpl`) VALUES
(1, 1, 100, 1, 1506669549, 1, 1506669549, 1, 'Ucenter', ''),
(2, 2, 1, 100, 1506669615, 1, 1506669619, 1, 'Ucenter', ''),
(22, 21, 116, 100, 1507777508, 1, 1507777585, 1, 'Ucenter', ''),
(21, 20, 116, 100, 1507777482, 1, 1507777585, 1, 'Ucenter', ''),
(20, 19, 100, 1, 1507776152, 1, 1507780124, 1, 'Ucenter', ''),
(19, 18, 1, 100, 1507602836, 1, 1507602843, 1, 'Common_system', ''),
(18, 18, 1, 1, 1507602836, 1, 1507603672, 1, 'Common_system', ''),
(13, 13, 100, 1, 1507534970, 1, 1507534973, 1, 'Common_system', ''),
(14, 14, 100, 1, 1507535010, 1, 1507535017, 1, 'Common_system', ''),
(15, 15, 100, 1, 1507536193, 1, 1507536199, 1, 'Ucenter', ''),
(16, 16, 100, 1, 1507536197, 1, 1507536199, 1, 'Ucenter', ''),
(17, 17, 100, 1, 1507536526, 1, 1507536529, 1, 'Common_system', ''),
(23, 22, 100, 1, 1507777703, 1, 1507780124, 1, 'Ucenter', ''),
(24, 23, 100, 1, 1507777716, 1, 1507780124, 1, 'Ucenter', ''),
(25, 24, 100, 1, 1507778126, 1, 1507780124, 1, 'Ucenter', ''),
(26, 25, 100, 1, 1507778130, 1, 1507780124, 1, 'Ucenter', ''),
(27, 26, 100, 1, 1507778134, 1, 1507780124, 1, 'Ucenter', ''),
(28, 27, 100, 1, 1507778135, 1, 1507780124, 1, 'Ucenter', ''),
(29, 28, 100, 1, 1507778139, 1, 1507780124, 1, 'Ucenter', ''),
(30, 29, 100, 1, 1507778182, 1, 1507780124, 1, 'Ucenter', ''),
(31, 30, 100, 1, 1507778183, 1, 1507780124, 1, 'Ucenter', ''),
(32, 31, 100, 1, 1507778184, 1, 1507780124, 1, 'Ucenter', ''),
(33, 32, 100, 1, 1507778187, 1, 1507780124, 1, 'Ucenter', ''),
(34, 33, 100, 1, 1507778189, 1, 1507780124, 1, 'Ucenter', ''),
(35, 34, 100, 1, 1507778190, 1, 1507780124, 1, 'Ucenter', ''),
(36, 35, 100, 1, 1507778190, 1, 1507780124, 1, 'Ucenter', ''),
(37, 36, 100, 1, 1507778194, 1, 1507780124, 1, 'Ucenter', ''),
(38, 37, 100, 1, 1507778194, 1, 1507780124, 1, 'Ucenter', ''),
(39, 38, 100, 1, 1507778195, 1, 1507780124, 1, 'Ucenter', ''),
(40, 39, 100, 1, 1507778195, 1, 1507780124, 1, 'Ucenter', ''),
(41, 40, 100, 1, 1507778195, 1, 1507780124, 1, 'Ucenter', ''),
(42, 41, 100, 1, 1507778195, 1, 1507780124, 1, 'Ucenter', ''),
(43, 42, 100, 1, 1507778196, 1, 1507780124, 1, 'Ucenter', ''),
(44, 43, 100, 1, 1507778196, 1, 1507780124, 1, 'Ucenter', ''),
(45, 44, 100, 1, 1507778198, 1, 1507780124, 1, 'Ucenter', ''),
(46, 45, 100, 1, 1507778200, 1, 1507780124, 1, 'Ucenter', ''),
(47, 46, 100, 1, 1507778201, 1, 1507780124, 1, 'Ucenter', ''),
(48, 47, 100, 1, 1507778202, 1, 1507780124, 1, 'Ucenter', ''),
(49, 48, 100, 1, 1507778202, 1, 1507780124, 1, 'Ucenter', ''),
(50, 49, 100, 1, 1507778203, 1, 1507780124, 1, 'Ucenter', ''),
(51, 50, 100, 1, 1507778203, 1, 1507780124, 1, 'Ucenter', ''),
(52, 51, 100, 1, 1507778204, 1, 1507780124, 1, 'Ucenter', ''),
(53, 52, 100, 1, 1507778205, 1, 1507780124, 1, 'Ucenter', ''),
(54, 53, 100, 1, 1507778205, 1, 1507780124, 1, 'Ucenter', ''),
(55, 54, 100, 1, 1507778206, 1, 1507780124, 1, 'Ucenter', ''),
(56, 55, 100, 1, 1507778287, 1, 1507780124, 1, 'Ucenter', ''),
(57, 56, 100, 1, 1507778289, 1, 1507780124, 1, 'Ucenter', ''),
(58, 57, 100, 1, 1507778290, 1, 1507780124, 1, 'Ucenter', ''),
(59, 58, 100, 1, 1507778291, 1, 1507780124, 1, 'Ucenter', ''),
(60, 59, 100, 1, 1507778291, 1, 1507780124, 1, 'Ucenter', ''),
(61, 60, 100, 1, 1507778292, 1, 1507780124, 1, 'Ucenter', ''),
(62, 61, 100, 1, 1507778292, 1, 1507780124, 1, 'Ucenter', ''),
(63, 62, 100, 1, 1507778292, 1, 1507780124, 1, 'Ucenter', ''),
(64, 63, 100, 1, 1507778292, 1, 1507780124, 1, 'Ucenter', ''),
(65, 64, 100, 1, 1507778292, 1, 1507780124, 1, 'Ucenter', ''),
(66, 65, 100, 1, 1507778293, 1, 1507780124, 1, 'Ucenter', ''),
(67, 66, 100, 1, 1507778293, 1, 1507780124, 1, 'Ucenter', ''),
(68, 67, 100, 1, 1507778293, 1, 1507780124, 1, 'Ucenter', ''),
(69, 68, 100, 1, 1507778293, 1, 1507780124, 1, 'Ucenter', ''),
(70, 69, 100, 1, 1507778293, 1, 1507780124, 1, 'Ucenter', ''),
(71, 70, 100, 1, 1507778332, 1, 1507780124, 1, 'Ucenter', ''),
(72, 71, 100, 1, 1507778357, 1, 1507780124, 1, 'Ucenter', ''),
(73, 72, 100, 1, 1507778359, 1, 1507780124, 1, 'Ucenter', ''),
(74, 73, 100, 1, 1507778363, 1, 1507780124, 1, 'Ucenter', ''),
(75, 74, 100, 1, 1507778435, 1, 1507780124, 1, 'Ucenter', ''),
(76, 75, 100, 1, 1507778436, 1, 1507780124, 1, 'Ucenter', ''),
(77, 76, 100, 1, 1507778587, 1, 1507780124, 1, 'Ucenter', ''),
(78, 77, 100, 1, 1507778588, 1, 1507780124, 1, 'Ucenter', ''),
(79, 78, 100, 1, 1507778590, 1, 1507780124, 1, 'Ucenter', ''),
(80, 79, 100, 1, 1507778591, 1, 1507780124, 1, 'Ucenter', ''),
(81, 80, 100, 1, 1507778607, 1, 1507780124, 1, 'Ucenter', ''),
(82, 81, 100, 1, 1507778608, 1, 1507780124, 1, 'Ucenter', ''),
(83, 82, 100, 1, 1507778613, 1, 1507780124, 1, 'Ucenter', ''),
(84, 83, 100, 1, 1507778614, 1, 1507780124, 1, 'Ucenter', ''),
(85, 84, 100, 1, 1507778615, 1, 1507780124, 1, 'Ucenter', ''),
(86, 85, 100, 1, 1507778616, 1, 1507780124, 1, 'Ucenter', ''),
(87, 86, 100, 1, 1507778653, 1, 1507780124, 1, 'Ucenter', ''),
(88, 87, 100, 1, 1507778654, 1, 1507780124, 1, 'Ucenter', ''),
(89, 88, 100, 1, 1507778656, 1, 1507780124, 1, 'Ucenter', ''),
(90, 89, 100, 1, 1507778663, 1, 1507780124, 1, 'Ucenter', ''),
(91, 90, 100, 1, 1507779086, 1, 1507780124, 1, 'Ucenter', ''),
(92, 91, 100, 1, 1507779089, 1, 1507780124, 1, 'Ucenter', ''),
(93, 92, 100, 1, 1507779090, 1, 1507780124, 1, 'Ucenter', ''),
(94, 93, 100, 1, 1507779131, 1, 1507780124, 1, 'Ucenter', ''),
(95, 94, 100, 1, 1507779132, 1, 1507780124, 1, 'Ucenter', ''),
(96, 95, 100, 1, 1507779134, 1, 1507780124, 1, 'Ucenter', ''),
(97, 96, 100, 1, 1507779135, 1, 1507780124, 1, 'Ucenter', ''),
(98, 97, 100, 1, 1507779137, 1, 1507780124, 1, 'Ucenter', ''),
(99, 98, 100, 1, 1507779884, 1, 1507780124, 1, 'Ucenter', ''),
(100, 99, 100, 1, 1507779886, 1, 1507780124, 1, 'Ucenter', ''),
(101, 100, 1, 100, 1507780132, 1, 1507780134, 1, 'Ucenter', ''),
(102, 101, 1, 100, 1507780145, 1, 1507780147, 1, 'Ucenter', ''),
(103, 102, 1, 100, 1507780179, 1, 1507780185, 1, 'Ucenter', ''),
(104, 103, 100, 1, 1507780408, 1, 1507780408, 1, 'Ucenter', ''),
(105, 104, 100, 1, 1507780424, 1, 1507780428, 1, 'Ucenter', ''),
(106, 105, 100, 1, 1507780433, 1, 1507780438, 1, 'Ucenter', ''),
(107, 106, 100, 1, 1507780559, 1, 1507780562, 1, 'Ucenter', ''),
(108, 107, 100, 1, 1507780565, 1, 1507780572, 1, 'Ucenter', ''),
(109, 108, 100, 1, 1507780988, 1, 1507781052, 1, 'Ucenter', ''),
(110, 109, 100, 1, 1507781139, 1, 1507781149, 1, 'Ucenter', ''),
(111, 110, 100, 1, 1507781212, 1, 1507781249, 1, 'Ucenter', ''),
(112, 111, 100, 1, 1507787595, 1, 1507846764, 1, 'Ucenter', ''),
(113, 112, 100, 1, 1507787599, 1, 1507846764, 1, 'Ucenter', ''),
(114, 113, 100, 1, 1507787601, 1, 1507846764, 1, 'Ucenter', ''),
(115, 114, 100, 1, 1507787602, 1, 1507846764, 1, 'Ucenter', ''),
(116, 115, 100, 1, 1507787608, 1, 1507846764, 1, 'Ucenter', ''),
(117, 116, 100, 1, 1507787916, 1, 1507846764, 1, 'Ucenter', ''),
(118, 117, 100, 1, 1507787918, 1, 1507846764, 1, 'Ucenter', ''),
(119, 118, 100, 1, 1507787923, 1, 1507846764, 1, 'Ucenter', ''),
(120, 119, 100, 1, 1507787923, 1, 1507846764, 1, 'Ucenter', ''),
(121, 120, 100, 1, 1507788247, 1, 1507846764, 1, 'Ucenter', ''),
(122, 121, 100, 1, 1507788306, 1, 1507846764, 1, 'Ucenter', ''),
(123, 122, 100, 1, 1507788349, 1, 1507846764, 1, 'Ucenter', ''),
(124, 123, 100, 1, 1507788545, 1, 1507846764, 1, 'Ucenter', ''),
(125, 124, 100, 1, 1507788591, 1, 1507846764, 1, 'Ucenter', ''),
(126, 125, 100, 1, 1507788593, 1, 1507846764, 1, 'Ucenter', ''),
(127, 126, 100, 1, 1507788595, 1, 1507846764, 1, 'Ucenter', ''),
(128, 127, 100, 1, 1507788596, 1, 1507846764, 1, 'Ucenter', ''),
(129, 128, 100, 1, 1507788622, 1, 1507846764, 1, 'Ucenter', ''),
(130, 129, 100, 1, 1507788645, 1, 1507846764, 1, 'Ucenter', ''),
(131, 130, 100, 1, 1507788646, 1, 1507846764, 1, 'Ucenter', ''),
(132, 131, 100, 1, 1507788724, 1, 1507846764, 1, 'Ucenter', ''),
(133, 132, 100, 1, 1507788725, 1, 1507846764, 1, 'Ucenter', ''),
(134, 133, 100, 1, 1507788726, 1, 1507846764, 1, 'Ucenter', ''),
(135, 134, 100, 1, 1507788727, 1, 1507846764, 1, 'Ucenter', ''),
(136, 135, 100, 1, 1507788749, 1, 1507846764, 1, 'Ucenter', ''),
(137, 136, 100, 1, 1507788750, 1, 1507846764, 1, 'Ucenter', ''),
(138, 137, 100, 1, 1507788751, 1, 1507846764, 1, 'Ucenter', ''),
(139, 138, 100, 1, 1507788752, 1, 1507846764, 1, 'Ucenter', ''),
(140, 139, 100, 1, 1507788866, 1, 1507846764, 1, 'Ucenter', ''),
(141, 140, 100, 1, 1507788867, 1, 1507846764, 1, 'Ucenter', ''),
(142, 141, 100, 1, 1507788882, 1, 1507846764, 1, 'Ucenter', ''),
(143, 142, 100, 1, 1507789636, 1, 1507846764, 1, 'Ucenter', ''),
(144, 143, 100, 1, 1507789637, 1, 1507846764, 1, 'Ucenter', ''),
(145, 144, 100, 1, 1507789649, 1, 1507846764, 1, 'Ucenter', ''),
(146, 145, 100, 1, 1507789677, 1, 1507846764, 1, 'Ucenter', ''),
(147, 146, 100, 1, 1507789678, 1, 1507846764, 1, 'Ucenter', ''),
(148, 147, 100, 1, 1507789723, 1, 1507846764, 1, 'Ucenter', ''),
(149, 148, 100, 1, 1507789724, 1, 1507846764, 1, 'Ucenter', ''),
(150, 149, 100, 1, 1507796933, 1, 1507846764, 1, 'Ucenter', ''),
(151, 150, 100, 1, 1507796973, 1, 1507846764, 1, 'Ucenter', ''),
(152, 151, 100, 1, 1507796983, 1, 1507846764, 1, 'Ucenter', ''),
(153, 152, 100, 1, 1507796986, 1, 1507846764, 1, 'Ucenter', ''),
(154, 153, 100, 1, 1507796987, 1, 1507846764, 1, 'Ucenter', ''),
(155, 154, 100, 1, 1507796988, 1, 1507846764, 1, 'Ucenter', ''),
(156, 155, 100, 1, 1507796989, 1, 1507846764, 1, 'Ucenter', ''),
(157, 156, 100, 1, 1507796989, 1, 1507846764, 1, 'Ucenter', ''),
(158, 157, 100, 1, 1507796989, 1, 1507846764, 1, 'Ucenter', ''),
(159, 158, 100, 1, 1507796989, 1, 1507846764, 1, 'Ucenter', ''),
(160, 159, 100, 1, 1507797011, 1, 1507846764, 1, 'Ucenter', ''),
(161, 160, 100, 1, 1507797040, 1, 1507846764, 1, 'Ucenter', ''),
(162, 161, 100, 1, 1507797143, 1, 1507846764, 1, 'Ucenter', ''),
(163, 162, 100, 1, 1507798365, 1, 1507846764, 1, 'Ucenter', ''),
(164, 163, 100, 1, 1508031840, 1, 1508031875, 1, 'Common_system', ''),
(165, 164, 100, 1, 1508031945, 1, 1508031946, 1, 'Common_system', '');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_message_content`
--

CREATE TABLE `ocenter_message_content` (
  `id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL,
  `title` varchar(500) NOT NULL,
  `content` text NOT NULL,
  `url` varchar(100) NOT NULL,
  `args` text NOT NULL,
  `type` varchar(25) NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_message_content`
--

INSERT INTO `ocenter_message_content` (`id`, `from_id`, `title`, `content`, `url`, `args`, `type`, `create_time`, `status`) VALUES
(1, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1506669549, 1),
(2, 1, '粉丝数增加', 'yoyoadmin 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"1\"}', 'Ucenter', 1506669615, 1),
(3, 100, '您收到了一条新消息！', '', '', '', 'Common_system', 1506762338, 1),
(4, 100, '您收到了一条新消息！', '', '', '', 'Common_system', 1506762359, 1),
(5, 100, '您收到了一条新消息！', '', '', '', 'Common_system', 1507465616, 1),
(6, 100, '您收到了一条新消息！', '', '', '', 'Common_system', 1507467227, 1),
(7, 100, '您收到了一条新消息！', '', '', '', 'Common_system', 1507468884, 1),
(8, 100, '您收到了一条新消息！', '', '', '', 'Common_system', 1507468915, 1),
(9, 100, '您收到了一条新消息！', '', '', '', 'Common_system', 1507532462, 1),
(10, 100, '您收到了一条新消息！', '', '', '', 'Common_system', 1507532490, 1),
(11, 100, '您收到了一条新消息！', '', '', '', 'Common_system', 1507532590, 1),
(12, 100, '您收到了一条新消息！', '', '', '', 'Common_system', 1507534076, 1),
(13, 100, '您收到了一条新消息！', '', '', '', 'Common_system', 1507534970, 1),
(14, 100, '您收到了一条新消息！', '', '', '', 'Common_system', 1507535010, 1),
(15, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507536193, 1),
(16, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507536197, 1),
(17, 100, '您收到了一条新消息！', '', '', '', 'Common_system', 1507536526, 1),
(18, 1, '你好', '<p>nihao k<br/></p>', 'www.baidu.com', '', 'Common_system', 1507602836, 1),
(19, 100, 'vbboy赞了您', '快去看看吧^……^！', 'weibo/index/weibodetail', '{\"id\":\"1\"}', 'Ucenter', 1507776151, 1),
(20, 116, '粉丝数增加', 'soni 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"116\"}', 'Ucenter', 1507777482, 1),
(21, 116, 'soni赞了您', '快去看看吧^……^！', 'weibo/index/weibodetail', '{\"id\":\"2\"}', 'Ucenter', 1507777508, 1),
(22, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507777703, 1),
(23, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507777716, 1),
(24, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778126, 1),
(25, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778130, 1),
(26, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778134, 1),
(27, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778135, 1),
(28, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778139, 1),
(29, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778182, 1),
(30, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778183, 1),
(31, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778184, 1),
(32, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778187, 1),
(33, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778189, 1),
(34, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778190, 1),
(35, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778190, 1),
(36, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778194, 1),
(37, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778194, 1),
(38, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778195, 1),
(39, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778195, 1),
(40, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778195, 1),
(41, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778195, 1),
(42, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778196, 1),
(43, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778196, 1),
(44, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778198, 1),
(45, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778200, 1),
(46, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778201, 1),
(47, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778202, 1),
(48, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778202, 1),
(49, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778203, 1),
(50, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778203, 1),
(51, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778204, 1),
(52, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778205, 1),
(53, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778205, 1),
(54, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778206, 1),
(55, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778287, 1),
(56, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778289, 1),
(57, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778290, 1),
(58, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778291, 1),
(59, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778291, 1),
(60, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778292, 1),
(61, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778292, 1),
(62, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778292, 1),
(63, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778292, 1),
(64, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778292, 1),
(65, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778293, 1),
(66, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778293, 1),
(67, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778293, 1),
(68, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778293, 1),
(69, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778293, 1),
(70, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778332, 1),
(71, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778357, 1),
(72, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778359, 1),
(73, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778363, 1),
(74, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778435, 1),
(75, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778436, 1),
(76, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778587, 1),
(77, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778588, 1),
(78, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778590, 1),
(79, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778591, 1),
(80, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778607, 1),
(81, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778608, 1),
(82, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778613, 1),
(83, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778614, 1),
(84, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778615, 1),
(85, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778616, 1),
(86, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778653, 1),
(87, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778654, 1),
(88, 100, '粉丝数增加', 'vbboy 关注了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778656, 1),
(89, 100, '粉丝数减少', 'vbboy 取消了对你的关注。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507778663, 1),
(90, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507779086, 1),
(91, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507779089, 1),
(92, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507779090, 1),
(93, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507779131, 1),
(94, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507779132, 1),
(95, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507779134, 1),
(96, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507779135, 1),
(97, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507779137, 1),
(98, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507779884, 1),
(99, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507779886, 1),
(100, 1, '粉丝数减少', 'yoyocoinsdd 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"1\"}', 'Ucenter', 1507780132, 1),
(101, 1, '粉丝数增加', 'yoyocoinsdd 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"1\"}', 'Ucenter', 1507780145, 1),
(102, 1, 'yoyocoinsdd赞了您', '快去看看吧^……^！', 'weibo/index/weibodetail', '{\"id\":\"2\"}', 'Ucenter', 1507780179, 1),
(103, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507780408, 1),
(104, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507780424, 1),
(105, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507780433, 1),
(106, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507780559, 1),
(107, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507780565, 1),
(108, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507780988, 1),
(109, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507781139, 1),
(110, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507781212, 1),
(111, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507787595, 1),
(112, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507787599, 1),
(113, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507787601, 1),
(114, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507787602, 1),
(115, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507787608, 1),
(116, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507787916, 1),
(117, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507787918, 1),
(118, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507787923, 1),
(119, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507787923, 1),
(120, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507788247, 1),
(121, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507788306, 1),
(122, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507788349, 1),
(123, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507788545, 1),
(124, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507788591, 1),
(125, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507788593, 1),
(126, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507788595, 1),
(127, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507788596, 1),
(128, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507788622, 1),
(129, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507788645, 1),
(130, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507788646, 1),
(131, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507788724, 1),
(132, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507788725, 1),
(133, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507788726, 1),
(134, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507788727, 1),
(135, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507788749, 1),
(136, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507788750, 1),
(137, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507788751, 1),
(138, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507788752, 1),
(139, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507788866, 1),
(140, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507788867, 1),
(141, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507788882, 1),
(142, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507789636, 1),
(143, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507789637, 1),
(144, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507789649, 1),
(145, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507789677, 1),
(146, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507789678, 1),
(147, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507789723, 1),
(148, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507789724, 1),
(149, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507796933, 1),
(150, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507796973, 1),
(151, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507796983, 1),
(152, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507796986, 1),
(153, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507796987, 1),
(154, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507796988, 1),
(155, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507796989, 1),
(156, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507796989, 1),
(157, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507796989, 1),
(158, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507796989, 1),
(159, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507797011, 1),
(160, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507797040, 1),
(161, 100, '粉丝数减少', 'vbboy 取消了对你的信任。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507797143, 1),
(162, 100, '粉丝数增加', 'vbboy 信任了你。', 'Ucenter/Index/index', '{\"uid\":\"100\"}', 'Ucenter', 1507798365, 1),
(163, 100, '您收到了一条新消息！', '', '', '', 'Common_system', 1508031840, 1),
(164, 100, '您收到了一条新消息！', '', '', '', 'Common_system', 1508031945, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_message_old`
--

CREATE TABLE `ocenter_message_old` (
  `id` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `from_uid` int(11) NOT NULL,
  `to_uid` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `is_read` tinyint(4) NOT NULL,
  `last_toast` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `type` varchar(25) NOT NULL COMMENT '消息类型',
  `tpl` varchar(100) NOT NULL COMMENT '消息模板'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='消息表';

--
-- 转存表中的数据 `ocenter_message_old`
--

INSERT INTO `ocenter_message_old` (`id`, `content_id`, `from_uid`, `to_uid`, `create_time`, `is_read`, `last_toast`, `status`, `type`, `tpl`) VALUES
(3, 3, 100, 1, 1506762338, 1, 1506762349, 1, 'Common_system', ''),
(4, 4, 100, 1, 1506762359, 1, 1506762369, 1, 'Common_system', ''),
(9, 9, 100, 1, 1507532462, 1, 1507532466, 1, 'Common_system', ''),
(8, 8, 100, 1, 1507468915, 1, 1507468917, 1, 'Common_system', ''),
(7, 7, 100, 1, 1507468884, 1, 1507468887, 1, 'Common_system', ''),
(6, 6, 100, 1, 1507467227, 1, 1507468853, 1, 'Common_system', ''),
(5, 5, 100, 1, 1507465616, 1, 1507468853, 1, 'Common_system', ''),
(10, 10, 100, 1, 1507532490, 1, 1507532499, 1, 'Common_system', ''),
(11, 11, 100, 1, 1507532590, 1, 1507532592, 1, 'Common_system', ''),
(12, 12, 100, 1, 1507534076, 1, 1507534081, 1, 'Common_system', '');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_message_type`
--

CREATE TABLE `ocenter_message_type` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `type` varchar(25) NOT NULL COMMENT '消息类型',
  `status` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户消息类型表';

--
-- 转存表中的数据 `ocenter_message_type`
--

INSERT INTO `ocenter_message_type` (`id`, `uid`, `type`, `status`) VALUES
(1, 1, 'Ucenter', 1),
(2, 100, 'Ucenter', 1),
(3, 1, 'Common_system', 1),
(4, 100, 'Common_system', 1);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_mob_channel`
--

CREATE TABLE `ocenter_mob_channel` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '频道ID',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级频道ID',
  `title` char(30) NOT NULL COMMENT '频道标题',
  `url` char(100) NOT NULL COMMENT '频道连接',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '导航排序',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `target` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '新窗口打开',
  `color` varchar(30) NOT NULL,
  `band_color` varchar(30) NOT NULL,
  `band_text` varchar(30) NOT NULL,
  `icon` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_mob_channel`
--

INSERT INTO `ocenter_mob_channel` (`id`, `pid`, `title`, `url`, `sort`, `create_time`, `update_time`, `status`, `target`, `color`, `band_color`, `band_text`, `icon`) VALUES
(1, 0, '活动', 'mob/event/index', 0, 0, 0, 1, 0, '', '', '', 'am-icon-'),
(2, 0, '论坛', 'mob/forum/index', 0, 0, 0, 1, 0, '', '', '', 'am-icon-'),
(3, 0, '群组', 'mob/group/index', 0, 0, 0, 1, 0, '', '', '', 'am-icon-'),
(4, 0, '专辑', 'mob/issue/index', 0, 0, 0, 1, 0, '', '', '', 'am-icon-'),
(5, 0, '资讯', 'mob/news/index', 0, 0, 0, 1, 0, '', '', '', 'am-icon-'),
(6, 0, '找人', 'mob/people/index', 0, 0, 0, 1, 0, '', '', '', 'am-icon-'),
(7, 0, '问答', 'mob/question/index', 0, 0, 0, 1, 0, '', '', '', 'am-icon-'),
(8, 0, '积分商城', 'mob/shop/index', 0, 0, 0, 1, 0, '', '', '', 'am-icon-'),
(9, 0, '微店', 'mob/store/index', 0, 0, 0, 1, 0, '', '', '', 'am-icon-'),
(10, 0, '用户中心', 'mob/user/index', 0, 0, 0, 1, 0, '', '', '', 'am-icon-'),
(11, 0, '动态Pro', 'mob/weibo/index', 0, 0, 0, 1, 0, '', '', '', 'am-icon-');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_module`
--

CREATE TABLE `ocenter_module` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL COMMENT '模块名',
  `alias` varchar(30) NOT NULL COMMENT '中文名',
  `version` varchar(20) NOT NULL COMMENT '版本号',
  `is_com` tinyint(4) NOT NULL COMMENT '是否商业版',
  `show_nav` tinyint(4) NOT NULL COMMENT '是否显示在导航栏中',
  `summary` varchar(200) NOT NULL COMMENT '简介',
  `developer` varchar(50) NOT NULL COMMENT '开发者',
  `website` varchar(200) NOT NULL COMMENT '网址',
  `entry` varchar(50) NOT NULL COMMENT '前台入口',
  `is_setup` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否已安装',
  `sort` int(11) NOT NULL COMMENT '模块排序',
  `icon` varchar(20) NOT NULL,
  `can_uninstall` tinyint(4) NOT NULL,
  `admin_entry` varchar(50) NOT NULL,
  `menu_hide` tinyint(4) NOT NULL DEFAULT '0' COMMENT '后台入口隐藏',
  `auth_role` varchar(200) NOT NULL COMMENT '允许访问角色，为空表示非登录也能访问',
  `is_pro` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否增强版'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模块管理表';

--
-- 转存表中的数据 `ocenter_module`
--

INSERT INTO `ocenter_module` (`id`, `name`, `alias`, `version`, `is_com`, `show_nav`, `summary`, `developer`, `website`, `entry`, `is_setup`, `sort`, `icon`, `can_uninstall`, `admin_entry`, `menu_hide`, `auth_role`, `is_pro`) VALUES
(1, 'Home', '网站主页', '2.0.0', 0, 1, '首页模块，主要用于展示网站内容', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'Home/index/index', 1, 0, 'home', 1, 'Admin/Home/config', 0, '', 0),
(2, 'Ucenter', '用户中心', '2.0.0', 0, 1, '用户中心模块，系统核心模块', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'Ucenter/index/index', 1, 0, 'user', 0, 'Admin/User/index', 0, '', 0),
(3, 'People', '找人', '2.0.0', 0, 1, '会员展示模块，可以用于会员的查找', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'People/index/index', 1, 0, 'group', 1, 'People/config', 0, '', 0),
(4, 'Weibo', '动态Pro', '3.1.0', 1, 1, '动态Pro模块，在动态模块基础上新增圈子', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'Weibo/index/index', 1, 0, 'quote-left', 1, 'Admin/Weibo/weibo', 0, '', 1),
(12, 'Core', '系统公共模块', '2.1.0', 0, 0, '系统核心模块，必不可少，负责核心的处理。', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', '', 1, 0, 'globe', 0, '', 0, '', 0),
(13, 'Mob', '移动版', '2.8.8', 1, 1, 'OpenSNS移动版', '嘉兴想天信息科技有限公司', 'http://www.opensns.cn', 'Mob/Member/index', 1, 0, 'phone', 1, 'Admin/Mob/config', 0, '', 0),
(14, 'Coin', '买卖中心', '1.0.0', 0, 1, '买卖模块，比特币交易中心', 'sobit', 'http://www.sobit123.com', 'Coin/index/index', 1, 0, 'rss', 1, 'Admin/Coin/index', 0, '', 0),
(15, 'Bclient', '商业版客户端', '1.0.0', 1, 0, '商业版客户端，商业客户使用', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'Bclient/index/index', 1, 0, 'map-marker', 1, 'Admin/Bclient/index', 0, '', 0),
(16, 'Ad', '发布广告', '1.0.0', 0, 1, '买卖模块，比特币交易中心', 'sobit', 'http://www.sobit123.com', 'Coin/index/index', 1, 0, 'rss', 1, 'Admin/Coin/index', 0, '', 0),
(17, 'News', '资讯', '2.4.1', 0, 1, '资讯模块，用户可前台投稿的CMS模块', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'News/index/index', 1, 0, 'rss', 1, 'Admin/News/index', 0, '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_m_addons`
--

CREATE TABLE `ocenter_m_addons` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text COMMENT '插件描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `config` text COMMENT '配置',
  `author` varchar(40) DEFAULT '' COMMENT '作者',
  `version` varchar(20) DEFAULT '' COMMENT '版本号',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '安装时间',
  `has_adminlist` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否有后台列表'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='插件表';

--
-- 转存表中的数据 `ocenter_m_addons`
--

INSERT INTO `ocenter_m_addons` (`id`, `name`, `title`, `description`, `status`, `config`, `author`, `version`, `create_time`, `has_adminlist`) VALUES
(1, 'ChinaCity', '中国省市区三级联动', '每个系统都需要的一个中国省市区三级联动插件。想天-駿濤修改，将镇级地区移除', 1, 'null', 'i友街', '2.0', 1432792040, 0),
(2, 'SyncLogin', '同步登陆', '同步登陆', 1, '{\"type\":null,\"meta\":\"\",\"bind\":\"0\",\"QqKEY\":\"\",\"QqSecret\":\"\",\"SinaKEY\":\"\",\"SinaSecret\":\"\",\"WeixinKEY\":\"\",\"WeixinSecret\":\"\"}', 'xjw129xjt', '0.1', 1432792112, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_m_adv`
--

CREATE TABLE `ocenter_m_adv` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '主键',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '广告名称',
  `pos_id` int(11) NOT NULL COMMENT '广告位置',
  `data` text NOT NULL COMMENT '图片地址',
  `click_count` int(11) NOT NULL COMMENT '点击量',
  `url` varchar(500) NOT NULL COMMENT '链接地址',
  `sort` int(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态（0：禁用，1：正常）',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '开始时间',
  `start_time` int(11) DEFAULT NULL,
  `end_time` int(11) UNSIGNED DEFAULT '0' COMMENT '结束时间',
  `target` varchar(20) DEFAULT '_blank'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_m_advertisement`
--

CREATE TABLE `ocenter_m_advertisement` (
  `id` int(11) NOT NULL,
  `location` text NOT NULL COMMENT '广告的位置，表示在什么地方显示',
  `status` tinyint(3) NOT NULL COMMENT '状态：1表示启用，0表示禁用，-1表示删除',
  `imgid` text NOT NULL COMMENT '图片',
  `link` text NOT NULL COMMENT '链接地址',
  `create_time` int(11) NOT NULL,
  `name` text NOT NULL COMMENT '广告名'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_m_adv_pos`
--

CREATE TABLE `ocenter_m_adv_pos` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '主键',
  `name` varchar(50) NOT NULL,
  `title` char(80) NOT NULL DEFAULT '' COMMENT '广告位置名称',
  `path` varchar(100) NOT NULL COMMENT '所在路径 模块/控制器/方法',
  `type` int(11) UNSIGNED NOT NULL DEFAULT '1' COMMENT '广告位类型 \r\n1.单图\r\n2.多图\r\n3.文字链接\r\n4.代码',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态（0：禁用，1：正常）',
  `data` varchar(500) NOT NULL COMMENT '额外的数据',
  `width` char(20) NOT NULL DEFAULT '' COMMENT '广告位置宽度',
  `height` char(20) NOT NULL DEFAULT '' COMMENT '广告位置高度',
  `margin` varchar(50) NOT NULL COMMENT '边缘',
  `padding` varchar(50) NOT NULL COMMENT '留白',
  `theme` varchar(50) NOT NULL DEFAULT 'all' COMMENT '适用主题，默认为all，通用'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告位置表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `ocenter_m_adv_pos`
--

INSERT INTO `ocenter_m_adv_pos` (`id`, `name`, `title`, `path`, `type`, `status`, `data`, `width`, `height`, `margin`, `padding`, `theme`) VALUES
(10003, 'square_adv', '广场', 'Ucenter/Index/square', 2, 1, '[]', '370px', '200px', '', '', 'all'),
(10004, 'information_adv', '资讯', 'News/Index/index', 2, 1, '[]', '370px', '200px', '', '', 'all');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_m_auth_group`
--

CREATE TABLE `ocenter_m_auth_group` (
  `id` mediumint(8) UNSIGNED NOT NULL COMMENT '权限组id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '权限组所属模块',
  `type` tinyint(4) NOT NULL COMMENT '组类型',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '权限组中文名称',
  `description` varchar(80) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '权限组状态：为1正常，为0禁用,-1为删除',
  `rules` text NOT NULL COMMENT '权限组拥有的规则id，多个规则 , 隔开',
  `end_time` int(11) NOT NULL DEFAULT '2000000000' COMMENT '有效期'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_m_auth_group`
--

INSERT INTO `ocenter_m_auth_group` (`id`, `module`, `type`, `title`, `description`, `status`, `rules`, `end_time`) VALUES
(1, 'admin', 1, '普通用户', '', 1, ',338,340,341,344,10000', 2000000000),
(2, 'admin', 1, 'VIP', '', 1, ',338,340,341,344,10000', 2000000000);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_m_auth_group_access`
--

CREATE TABLE `ocenter_m_auth_group_access` (
  `uid` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `group_id` mediumint(8) UNSIGNED NOT NULL COMMENT '权限组id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_m_auth_group_access`
--

INSERT INTO `ocenter_m_auth_group_access` (`uid`, `group_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_m_auth_rule`
--

CREATE TABLE `ocenter_m_auth_rule` (
  `id` mediumint(8) UNSIGNED NOT NULL COMMENT '规则id,自增主键',
  `module` varchar(20) NOT NULL COMMENT '规则所属module',
  `type` tinyint(2) NOT NULL DEFAULT '1' COMMENT '1-url;2-主菜单',
  `name` char(80) NOT NULL DEFAULT '' COMMENT '规则唯一英文标识',
  `title` char(20) NOT NULL DEFAULT '' COMMENT '规则中文描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效(0:无效,1:有效)',
  `condition` varchar(300) NOT NULL DEFAULT '' COMMENT '规则附加条件'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_m_auth_rule`
--

INSERT INTO `ocenter_m_auth_rule` (`id`, `module`, `type`, `name`, `title`, `status`, `condition`) VALUES
(1, 'admin', 2, 'Admin/Index/index', '首页', 1, ''),
(2, 'admin', 2, 'Admin/Article/mydocument', '资讯', -1, ''),
(3, 'admin', 2, 'Admin/User/index', '用户', 1, ''),
(4, 'admin', 2, 'Admin/Addons/index', '插件', -1, ''),
(5, 'admin', 2, 'Admin/Config/group', '系统', 1, ''),
(7, 'admin', 1, 'Admin/article/add', '新增', -1, ''),
(8, 'admin', 1, 'Admin/article/edit', '编辑', -1, ''),
(9, 'admin', 1, 'Admin/article/setStatus', '改变状态', -1, ''),
(10, 'admin', 1, 'Admin/article/update', '保存', -1, ''),
(11, 'admin', 1, 'Admin/article/autoSave', '保存草稿', -1, ''),
(12, 'admin', 1, 'Admin/article/move', '移动', -1, ''),
(13, 'admin', 1, 'Admin/article/copy', '复制', -1, ''),
(14, 'admin', 1, 'Admin/article/paste', '粘贴', -1, ''),
(15, 'admin', 1, 'Admin/article/permit', '还原', -1, ''),
(16, 'admin', 1, 'Admin/article/clear', '清空', -1, ''),
(17, 'admin', 1, 'Admin/article/index', '文档列表', -1, ''),
(18, 'admin', 1, 'Admin/article/recycle', '回收站', -1, ''),
(19, 'admin', 1, 'Admin/User/addaction', '新增用户行为', 1, ''),
(20, 'admin', 1, 'Admin/User/editaction', '编辑用户行为', 1, ''),
(21, 'admin', 1, 'Admin/User/saveAction', '保存用户行为', 1, ''),
(22, 'admin', 1, 'Admin/User/setStatus', '变更行为状态', 1, ''),
(23, 'admin', 1, 'Admin/User/changeStatus?method=forbidUser', '禁用会员', 1, ''),
(24, 'admin', 1, 'Admin/User/changeStatus?method=resumeUser', '启用会员', 1, ''),
(25, 'admin', 1, 'Admin/User/changeStatus?method=deleteUser', '删除会员', 1, ''),
(26, 'admin', 1, 'Admin/User/index', '用户信息', 1, ''),
(27, 'admin', 1, 'Admin/User/action', '用户行为', 1, ''),
(28, 'admin', 1, 'Admin/AuthManager/changeStatus?method=deleteGroup', '删除', 1, ''),
(29, 'admin', 1, 'Admin/AuthManager/changeStatus?method=forbidGroup', '禁用', 1, ''),
(30, 'admin', 1, 'Admin/AuthManager/changeStatus?method=resumeGroup', '恢复', 1, ''),
(31, 'admin', 1, 'Admin/AuthManager/createGroup', '新增', 1, ''),
(32, 'admin', 1, 'Admin/AuthManager/editGroup', '编辑', 1, ''),
(33, 'admin', 1, 'Admin/AuthManager/writeGroup', '保存权限组', 1, ''),
(34, 'admin', 1, 'Admin/AuthManager/group', '授权', 1, ''),
(35, 'admin', 1, 'Admin/AuthManager/access', '访问授权', 1, ''),
(36, 'admin', 1, 'Admin/AuthManager/user', '成员授权', 1, ''),
(37, 'admin', 1, 'Admin/AuthManager/removeFromGroup', '解除授权', 1, ''),
(38, 'admin', 1, 'Admin/AuthManager/addToGroup', '保存成员授权', 1, ''),
(39, 'admin', 1, 'Admin/AuthManager/category', '分类授权', 1, ''),
(40, 'admin', 1, 'Admin/AuthManager/addToCategory', '保存分类授权', 1, ''),
(41, 'admin', 1, 'Admin/AuthManager/index', '权限管理', 1, ''),
(42, 'admin', 1, 'Admin/Addons/create', '创建', 1, ''),
(43, 'admin', 1, 'Admin/Addons/checkForm', '检测创建', 1, ''),
(44, 'admin', 1, 'Admin/Addons/preview', '预览', 1, ''),
(45, 'admin', 1, 'Admin/Addons/build', '快速生成插件', 1, ''),
(46, 'admin', 1, 'Admin/Addons/config', '设置', 1, ''),
(47, 'admin', 1, 'Admin/Addons/disable', '禁用', 1, ''),
(48, 'admin', 1, 'Admin/Addons/enable', '启用', 1, ''),
(49, 'admin', 1, 'Admin/Addons/install', '安装', 1, ''),
(50, 'admin', 1, 'Admin/Addons/uninstall', '卸载', 1, ''),
(51, 'admin', 1, 'Admin/Addons/saveconfig', '更新配置', 1, ''),
(52, 'admin', 1, 'Admin/Addons/adminList', '插件后台列表', 1, ''),
(53, 'admin', 1, 'Admin/Addons/execute', 'URL方式访问插件', 1, ''),
(54, 'admin', 1, 'Admin/Addons/index', '插件管理', 1, ''),
(55, 'admin', 1, 'Admin/Addons/hooks', '钩子管理', 1, ''),
(56, 'admin', 1, 'Admin/model/add', '新增', -1, ''),
(57, 'admin', 1, 'Admin/model/edit', '编辑', -1, ''),
(58, 'admin', 1, 'Admin/model/setStatus', '改变状态', -1, ''),
(59, 'admin', 1, 'Admin/model/update', '保存数据', -1, ''),
(60, 'admin', 1, 'Admin/Model/index', '模型管理', -1, ''),
(61, 'admin', 1, 'Admin/Config/edit', '编辑', 1, ''),
(62, 'admin', 1, 'Admin/Config/del', '删除', 1, ''),
(63, 'admin', 1, 'Admin/Config/add', '新增', 1, ''),
(64, 'admin', 1, 'Admin/Config/save', '保存', 1, ''),
(65, 'admin', 1, 'Admin/Config/group', '网站设置', 1, ''),
(67, 'admin', 1, 'Admin/Channel/add', '新增', 1, ''),
(68, 'admin', 1, 'Admin/Channel/edit', '编辑', 1, ''),
(69, 'admin', 1, 'Admin/Channel/del', '删除', 1, ''),
(70, 'admin', 1, 'Admin/Channel/index', '导航管理', 1, ''),
(71, 'admin', 1, 'Admin/Category/edit', '编辑', -1, ''),
(72, 'admin', 1, 'Admin/Category/add', '新增', -1, ''),
(73, 'admin', 1, 'Admin/Category/remove', '删除', -1, ''),
(74, 'admin', 1, 'Admin/Category/index', '分类管理', -1, ''),
(75, 'admin', 1, 'Admin/file/upload', '上传控件', -1, ''),
(76, 'admin', 1, 'Admin/file/uploadPicture', '上传图片', -1, ''),
(77, 'admin', 1, 'Admin/file/download', '下载', -1, ''),
(94, 'admin', 1, 'Admin/AuthManager/modelauth', '模型授权', 1, ''),
(79, 'admin', 1, 'Admin/article/batchOperate', '导入', -1, ''),
(82, 'admin', 1, 'Admin/Database/export', '备份', 1, ''),
(83, 'admin', 1, 'Admin/Database/optimize', '优化表', 1, ''),
(84, 'admin', 1, 'Admin/Database/repair', '修复表', 1, ''),
(86, 'admin', 1, 'Admin/Database/import', '恢复', 1, ''),
(87, 'admin', 1, 'Admin/Database/del', '删除', 1, ''),
(88, 'admin', 1, 'Admin/User/add', '新增用户', -1, ''),
(89, 'admin', 1, 'Admin/Attribute/index', '属性管理', -1, ''),
(90, 'admin', 1, 'Admin/Attribute/add', '新增', -1, ''),
(91, 'admin', 1, 'Admin/Attribute/edit', '编辑', -1, ''),
(92, 'admin', 1, 'Admin/Attribute/setStatus', '改变状态', -1, ''),
(93, 'admin', 1, 'Admin/Attribute/update', '保存数据', -1, ''),
(95, 'admin', 1, 'Admin/AuthManager/addToModel', '保存模型授权', 1, ''),
(96, 'admin', 1, 'Admin/Category/move', '移动', -1, ''),
(97, 'admin', 1, 'Admin/Category/merge', '合并', -1, ''),
(98, 'admin', 1, 'Admin/Config/menu', '后台菜单管理', -1, ''),
(99, 'admin', 1, 'Admin/Article/mydocument', '内容', -1, ''),
(100, 'admin', 1, 'Admin/Menu/index', '菜单管理', 1, ''),
(101, 'admin', 1, 'Admin/other', '其他', -1, ''),
(102, 'admin', 1, 'Admin/Menu/add', '新增', 1, ''),
(103, 'admin', 1, 'Admin/Menu/edit', '编辑', 1, ''),
(104, 'admin', 1, 'Admin/Think/lists?model=article', '文章管理', -1, ''),
(105, 'admin', 1, 'Admin/Think/lists?model=download', '下载管理', -1, ''),
(106, 'admin', 1, 'Admin/Think/lists?model=config', '配置管理', -1, ''),
(107, 'admin', 1, 'Admin/Action/actionlog', '行为日志', 1, ''),
(108, 'admin', 1, 'Admin/User/updatePassword', '修改密码', 1, ''),
(109, 'admin', 1, 'Admin/User/updateNickname', '修改昵称', 1, ''),
(110, 'admin', 1, 'Admin/action/edit', '查看行为日志', 1, ''),
(205, 'admin', 1, 'Admin/think/add', '新增数据', -1, ''),
(111, 'admin', 2, 'Admin/article/index', '文档列表', -1, ''),
(112, 'admin', 2, 'Admin/article/add', '新增', -1, ''),
(113, 'admin', 2, 'Admin/article/edit', '编辑', -1, ''),
(114, 'admin', 2, 'Admin/article/setStatus', '改变状态', -1, ''),
(115, 'admin', 2, 'Admin/article/update', '保存', -1, ''),
(116, 'admin', 2, 'Admin/article/autoSave', '保存草稿', -1, ''),
(117, 'admin', 2, 'Admin/article/move', '移动', -1, ''),
(118, 'admin', 2, 'Admin/article/copy', '复制', -1, ''),
(119, 'admin', 2, 'Admin/article/paste', '粘贴', -1, ''),
(120, 'admin', 2, 'Admin/article/batchOperate', '导入', -1, ''),
(121, 'admin', 2, 'Admin/article/recycle', '回收站', -1, ''),
(122, 'admin', 2, 'Admin/article/permit', '还原', -1, ''),
(123, 'admin', 2, 'Admin/article/clear', '清空', -1, ''),
(124, 'admin', 2, 'Admin/User/add', '新增用户', -1, ''),
(125, 'admin', 2, 'Admin/User/action', '用户行为', -1, ''),
(126, 'admin', 2, 'Admin/User/addAction', '新增用户行为', -1, ''),
(127, 'admin', 2, 'Admin/User/editAction', '编辑用户行为', -1, ''),
(128, 'admin', 2, 'Admin/User/saveAction', '保存用户行为', -1, ''),
(129, 'admin', 2, 'Admin/User/setStatus', '变更行为状态', -1, ''),
(130, 'admin', 2, 'Admin/User/changeStatus?method=forbidUser', '禁用会员', -1, ''),
(131, 'admin', 2, 'Admin/User/changeStatus?method=resumeUser', '启用会员', -1, ''),
(132, 'admin', 2, 'Admin/User/changeStatus?method=deleteUser', '删除会员', -1, ''),
(133, 'admin', 2, 'Admin/AuthManager/index', '权限管理', -1, ''),
(134, 'admin', 2, 'Admin/AuthManager/changeStatus?method=deleteGroup', '删除', -1, ''),
(135, 'admin', 2, 'Admin/AuthManager/changeStatus?method=forbidGroup', '禁用', -1, ''),
(136, 'admin', 2, 'Admin/AuthManager/changeStatus?method=resumeGroup', '恢复', -1, ''),
(137, 'admin', 2, 'Admin/AuthManager/createGroup', '新增', -1, ''),
(138, 'admin', 2, 'Admin/AuthManager/editGroup', '编辑', -1, ''),
(139, 'admin', 2, 'Admin/AuthManager/writeGroup', '保存权限组', -1, ''),
(140, 'admin', 2, 'Admin/AuthManager/group', '授权', -1, ''),
(141, 'admin', 2, 'Admin/AuthManager/access', '访问授权', -1, ''),
(142, 'admin', 2, 'Admin/AuthManager/user', '成员授权', -1, ''),
(143, 'admin', 2, 'Admin/AuthManager/removeFromGroup', '解除授权', -1, ''),
(144, 'admin', 2, 'Admin/AuthManager/addToGroup', '保存成员授权', -1, ''),
(145, 'admin', 2, 'Admin/AuthManager/category', '分类授权', -1, ''),
(146, 'admin', 2, 'Admin/AuthManager/addToCategory', '保存分类授权', -1, ''),
(147, 'admin', 2, 'Admin/AuthManager/modelauth', '模型授权', -1, ''),
(148, 'admin', 2, 'Admin/AuthManager/addToModel', '保存模型授权', -1, ''),
(149, 'admin', 2, 'Admin/Addons/create', '创建', -1, ''),
(150, 'admin', 2, 'Admin/Addons/checkForm', '检测创建', -1, ''),
(151, 'admin', 2, 'Admin/Addons/preview', '预览', -1, ''),
(152, 'admin', 2, 'Admin/Addons/build', '快速生成插件', -1, ''),
(153, 'admin', 2, 'Admin/Addons/config', '设置', -1, ''),
(154, 'admin', 2, 'Admin/Addons/disable', '禁用', -1, ''),
(155, 'admin', 2, 'Admin/Addons/enable', '启用', -1, ''),
(156, 'admin', 2, 'Admin/Addons/install', '安装', -1, ''),
(157, 'admin', 2, 'Admin/Addons/uninstall', '卸载', -1, ''),
(158, 'admin', 2, 'Admin/Addons/saveconfig', '更新配置', -1, ''),
(159, 'admin', 2, 'Admin/Addons/adminList', '插件后台列表', -1, ''),
(160, 'admin', 2, 'Admin/Addons/execute', 'URL方式访问插件', -1, ''),
(161, 'admin', 2, 'Admin/Addons/hooks', '钩子管理', -1, ''),
(162, 'admin', 2, 'Admin/Model/index', '模型管理', -1, ''),
(163, 'admin', 2, 'Admin/model/add', '新增', -1, ''),
(164, 'admin', 2, 'Admin/model/edit', '编辑', -1, ''),
(165, 'admin', 2, 'Admin/model/setStatus', '改变状态', -1, ''),
(166, 'admin', 2, 'Admin/model/update', '保存数据', -1, ''),
(167, 'admin', 2, 'Admin/Attribute/index', '属性管理', -1, ''),
(168, 'admin', 2, 'Admin/Attribute/add', '新增', -1, ''),
(169, 'admin', 2, 'Admin/Attribute/edit', '编辑', -1, ''),
(170, 'admin', 2, 'Admin/Attribute/setStatus', '改变状态', -1, ''),
(171, 'admin', 2, 'Admin/Attribute/update', '保存数据', -1, ''),
(173, 'admin', 2, 'Admin/Config/edit', '编辑', -1, ''),
(174, 'admin', 2, 'Admin/Config/del', '删除', -1, ''),
(175, 'admin', 2, 'Admin/Config/add', '新增', -1, ''),
(176, 'admin', 2, 'Admin/Config/save', '保存', -1, ''),
(177, 'admin', 2, 'Admin/Menu/index', '菜单管理', -1, ''),
(178, 'admin', 2, 'Admin/Channel/index', '导航管理', -1, ''),
(179, 'admin', 2, 'Admin/Channel/add', '新增', -1, ''),
(180, 'admin', 2, 'Admin/Channel/edit', '编辑', -1, ''),
(181, 'admin', 2, 'Admin/Channel/del', '删除', -1, ''),
(182, 'admin', 2, 'Admin/Category/index', '分类管理', -1, ''),
(183, 'admin', 2, 'Admin/Category/edit', '编辑', -1, ''),
(184, 'admin', 2, 'Admin/Category/add', '新增', -1, ''),
(185, 'admin', 2, 'Admin/Category/remove', '删除', -1, ''),
(186, 'admin', 2, 'Admin/Category/move', '移动', -1, ''),
(187, 'admin', 2, 'Admin/Category/merge', '合并', -1, ''),
(195, 'admin', 2, 'Admin/other', '其他', -1, ''),
(196, 'admin', 2, 'Admin/Menu/add', '新增', -1, ''),
(197, 'admin', 2, 'Admin/Menu/edit', '编辑', -1, ''),
(198, 'admin', 2, 'Admin/Think/lists?model=article', '应用', -1, ''),
(199, 'admin', 2, 'Admin/Think/lists?model=download', '下载管理', -1, ''),
(200, 'admin', 2, 'Admin/Think/lists?model=config', '应用', -1, ''),
(201, 'admin', 2, 'Admin/Action/actionlog', '行为日志', -1, ''),
(202, 'admin', 2, 'Admin/User/updatePassword', '修改密码', -1, ''),
(203, 'admin', 2, 'Admin/User/updateNickname', '修改昵称', -1, ''),
(204, 'admin', 2, 'Admin/action/edit', '查看行为日志', -1, ''),
(206, 'admin', 1, 'Admin/think/edit', '编辑数据', -1, ''),
(207, 'admin', 1, 'Admin/Menu/import', '导入', 1, ''),
(208, 'admin', 1, 'Admin/Model/generate', '生成', -1, ''),
(209, 'admin', 1, 'Admin/Addons/addHook', '新增钩子', 1, ''),
(210, 'admin', 1, 'Admin/Addons/edithook', '编辑钩子', 1, ''),
(211, 'admin', 1, 'Admin/Article/sort', '文档排序', -1, ''),
(212, 'admin', 1, 'Admin/Config/sort', '排序', 1, ''),
(213, 'admin', 1, 'Admin/Menu/sort', '排序', 1, ''),
(214, 'admin', 1, 'Admin/Channel/sort', '排序', 1, ''),
(215, 'admin', 1, 'Admin/Category/operate/type/move', '移动', -1, ''),
(216, 'admin', 1, 'Admin/Category/operate/type/merge', '合并', -1, ''),
(217, 'admin', 1, 'Admin/Forum/forum', '板块管理', -1, ''),
(218, 'admin', 1, 'Admin/Forum/post', '帖子管理', -1, ''),
(219, 'admin', 1, 'Admin/Forum/editForum', '编辑／发表帖子', -1, ''),
(220, 'admin', 1, 'Admin/Forum/editPost', 'edit pots', -1, ''),
(221, 'admin', 2, 'Admin//Admin/Forum/index', '讨论区', -1, ''),
(222, 'admin', 2, 'Admin//Admin/Weibo/index', '动态', -1, ''),
(223, 'admin', 1, 'Admin/Forum/sortForum', '排序', -1, ''),
(224, 'admin', 1, 'Admin/SEO/editRule', '新增、编辑', 1, ''),
(225, 'admin', 1, 'Admin/SEO/sortRule', '排序', 1, ''),
(226, 'admin', 1, 'Admin/SEO/index', 'SEO规则管理', 1, ''),
(227, 'admin', 1, 'Admin/Forum/editReply', '新增 编辑', -1, ''),
(228, 'admin', 1, 'Admin/Weibo/editComment', '编辑回复', 1, ''),
(229, 'admin', 1, 'Admin/Weibo/editWeibo', '编辑动态', 1, ''),
(230, 'admin', 1, 'Admin/SEO/ruleTrash', 'SEO规则回收站', 1, ''),
(231, 'admin', 1, 'Admin/Rank/userList', '查看用户', 1, ''),
(232, 'admin', 1, 'Admin/Rank/userRankList', '用户头衔列表', 1, ''),
(233, 'admin', 1, 'Admin/Rank/userAddRank', '关联新头衔', 1, ''),
(234, 'admin', 1, 'Admin/Rank/userChangeRank', '编辑头衔关联', 1, ''),
(235, 'admin', 1, 'Admin/Issue/add', '编辑专辑', -1, ''),
(236, 'admin', 1, 'Admin/Issue/issue', '专辑管理', -1, ''),
(237, 'admin', 1, 'Admin/Issue/operate', '专辑操作', -1, ''),
(238, 'admin', 1, 'Admin/Weibo/weibo', '动态管理', 1, ''),
(239, 'admin', 1, 'Admin/Rank/index', '头衔列表', 1, ''),
(240, 'admin', 1, 'Admin/Forum/forumTrash', '板块回收站', -1, ''),
(241, 'admin', 1, 'Admin/Weibo/weiboTrash', '动态回收站', 1, ''),
(242, 'admin', 1, 'Admin/Rank/editRank', '添加头衔', 1, ''),
(243, 'admin', 1, 'Admin/Weibo/comment', '回复管理', 1, ''),
(244, 'admin', 1, 'Admin/Forum/postTrash', '帖子回收站', -1, ''),
(245, 'admin', 1, 'Admin/Weibo/commentTrash', '回复回收站', 1, ''),
(246, 'admin', 1, 'Admin/Issue/issueTrash', '专辑回收站', -1, ''),
(247, 'admin', 1, 'Admin//Admin/Forum/reply', '回复管理', -1, ''),
(248, 'admin', 1, 'Admin/Forum/replyTrash', '回复回收站', -1, ''),
(249, 'admin', 2, 'Admin/Forum/index', '贴吧', -1, ''),
(250, 'admin', 2, 'Admin/Weibo/weibo', '动态', 1, ''),
(251, 'admin', 2, 'Admin/SEO/index', 'SEO', -1, ''),
(252, 'admin', 2, 'Admin/Rank/index', '头衔', -1, ''),
(253, 'admin', 2, 'Admin/Issue/issue', '专辑', -1, ''),
(254, 'admin', 1, 'Admin/Issue/contents', '内容管理', -1, ''),
(255, 'admin', 1, 'Admin/User/profile', '扩展资料', 1, ''),
(256, 'admin', 1, 'Admin/User/editProfile', '添加、编辑分组', 1, ''),
(257, 'admin', 1, 'Admin/User/sortProfile', '分组排序', 1, ''),
(258, 'admin', 1, 'Admin/User/field', '字段列表', 1, ''),
(259, 'admin', 1, 'Admin/User/editFieldSetting', '添加、编辑字段', 1, ''),
(260, 'admin', 1, 'Admin/User/sortField', '字段排序', 1, ''),
(261, 'admin', 1, 'Admin/Update/quick', '全部补丁', 1, ''),
(262, 'admin', 1, 'Admin/Update/addpack', '新增补丁', 1, ''),
(263, 'admin', 1, 'Admin/User/expandinfo_select', '用户扩展资料列表', 1, ''),
(264, 'admin', 1, 'Admin/User/expandinfo_details', '扩展资料详情', 1, ''),
(265, 'admin', 1, 'Admin/Shop/shopLog', '商城信息记录', -1, ''),
(266, 'admin', 1, 'Admin/Shop/setStatus', '商品分类状态设置', -1, ''),
(267, 'admin', 1, 'Admin/Shop/setGoodsStatus', '商品状态设置', -1, ''),
(268, 'admin', 1, 'Admin/Shop/operate', '商品分类操作', -1, ''),
(269, 'admin', 1, 'Admin/Shop/add', '商品分类添加', -1, ''),
(270, 'admin', 1, 'Admin/Shop/goodsEdit', '添加、编辑商品', -1, ''),
(271, 'admin', 1, 'Admin/Shop/hotSellConfig', '热销商品阀值配置', -1, ''),
(272, 'admin', 1, 'Admin/Shop/setNew', '设置新品', -1, ''),
(273, 'admin', 1, 'Admin/EventType/index', '活动分类管理', -1, ''),
(274, 'admin', 1, 'Admin/Event/event', '内容管理', -1, ''),
(275, 'admin', 1, 'Admin/EventType/eventTypeTrash', '活动分类回收站', -1, ''),
(276, 'admin', 1, 'Admin/Event/verify', '内容审核', -1, ''),
(277, 'admin', 1, 'Admin/Event/contentTrash', '内容回收站', -1, ''),
(278, 'admin', 1, 'Admin/Rank/rankVerify', '待审核用户头衔', 1, ''),
(279, 'admin', 1, 'Admin/Rank/rankVerifyFailure', '被驳回的头衔申请', 1, ''),
(280, 'admin', 1, 'Admin/Weibo/config', '动态设置', 1, ''),
(281, 'admin', 1, 'Admin/Issue/verify', '内容审核', -1, ''),
(282, 'admin', 1, 'Admin/Shop/goodsList', '商品列表', -1, ''),
(283, 'admin', 1, 'Admin/Shop/shopCategory', '商品分类配置', -1, ''),
(284, 'admin', 1, 'Admin/Shop/categoryTrash', '商品分类回收站', -1, ''),
(285, 'admin', 1, 'Admin/Shop/verify', '待发货交易', -1, ''),
(286, 'admin', 1, 'Admin/Issue/contentTrash', '内容回收站', -1, ''),
(287, 'admin', 1, 'Admin/Shop/goodsBuySuccess', '交易成功记录', -1, ''),
(288, 'admin', 1, 'Admin/Shop/goodsTrash', '商品回收站', -1, ''),
(289, 'admin', 1, 'Admin/Shop/toxMoneyConfig', '货币配置', -1, ''),
(290, 'admin', 2, 'Admin/Shop/shopCategory', '商城', -1, ''),
(291, 'admin', 2, 'Admin/EventType/index', '活动', -1, ''),
(340, 'Weibo', 1, 'Weibo/Index/doSend', '发动态', 1, ''),
(297, 'Home', 1, 'deleteLocalComment', '删除本地评论', 1, ''),
(306, 'Issue', 1, 'addIssueContent', '专辑投稿权限', 1, ''),
(307, 'Issue', 1, 'editIssueContent', '编辑专辑内容（管理）', 1, ''),
(339, 'Weibo', 1, 'Weibo/Index/doDelWeibo', '删除动态(管理)', 1, ''),
(338, 'Weibo', 1, 'Weibo/Index/doSendRepost', '转发动态', 1, ''),
(313, 'admin', 1, 'Admin/module/install', '模块安装', 1, ''),
(315, 'admin', 1, 'Admin/module/lists', '模块管理', 1, ''),
(316, 'admin', 1, 'Admin/module/uninstall', '卸载模块', 1, ''),
(317, 'admin', 1, 'Admin/AuthManager/addNode', '新增权限节点', 1, ''),
(318, 'admin', 1, 'Admin/AuthManager/accessUser', '前台权限管理', 1, ''),
(319, 'admin', 1, 'Admin/User/changeGroup', '转移权限组', 1, ''),
(320, 'admin', 1, 'Admin/AuthManager/deleteNode', '删除权限节点', 1, ''),
(321, 'admin', 1, 'Admin/Issue/config', '专辑设置', -1, ''),
(322, 'admin', 2, 'Admin/module/lists', '云平台', -1, ''),
(341, 'Weibo', 1, 'Weibo/Index/doComment', '评论动态', 1, ''),
(342, 'Weibo', 1, 'Weibo/Index/doDelComment', '删除评论动态(管理)', 1, ''),
(343, 'Weibo', 1, 'Weibo/Index/setTop', '动态置顶(管理)', 1, ''),
(346, 'admin', 1, 'Admin/UserConfig/index', '用户注册配置', 1, ''),
(347, 'admin', 1, 'Admin/User/scoreList', '积分类型列表', 1, ''),
(348, 'admin', 1, 'Admin/user/editScoreType', '新增/编辑类型', 1, ''),
(349, 'admin', 1, 'Admin/user/recharge', '充值积分', 1, ''),
(350, 'admin', 1, 'Admin/Authorize/ssoSetting', '单点登录配置', 1, ''),
(351, 'admin', 1, 'Admin/Authorize/ssolist', '应用列表', 1, ''),
(352, 'admin', 1, 'Admin/authorize/editssoapp', '新增/编辑应用', 1, ''),
(353, 'admin', 1, 'Admin/ActionLimit/limitList', '行为限制列表', 1, ''),
(354, 'admin', 1, 'Admin/ActionLimit/editLimit', '新增/编辑行为限制', 1, ''),
(355, 'admin', 1, 'Admin/Role/index', '角色列表', 1, ''),
(356, 'admin', 1, 'Admin/Role/editRole', '编辑角色', 1, ''),
(357, 'admin', 1, 'Admin/Role/setStatus', '启用、禁用、删除角色', 1, ''),
(358, 'admin', 1, 'Admin/Role/sort', '角色排序', 1, ''),
(359, 'admin', 1, 'Admin/Role/configScore', '默认积分配置', 1, ''),
(360, 'admin', 1, 'Admin/Role/configAuth', '默认权限配置', 1, ''),
(361, 'admin', 1, 'Admin/Role/configAvatar', '默认头像配置', 1, ''),
(362, 'admin', 1, 'Admin/Role/configRank', '默认头衔配置', 1, ''),
(363, 'admin', 1, 'Admin/Role/configField', '默认字段管理', 1, ''),
(364, 'admin', 1, 'Admin/Role/group', '角色分组', 1, ''),
(365, 'admin', 1, 'Admin/Role/editGroup', '编辑分组', 1, ''),
(366, 'admin', 1, 'Admin/Role/deleteGroup', '删除分组', 1, ''),
(367, 'admin', 1, 'Admin/Role/config', '角色基本信息配置', 1, ''),
(368, 'admin', 1, 'Admin/Role/userList', '用户列表', 1, ''),
(369, 'admin', 1, 'Admin/Role/setUserStatus', '设置用户状态', 1, ''),
(370, 'admin', 1, 'Admin/Role/setUserAudit', '审核用户', 1, ''),
(371, 'admin', 1, 'Admin/Role/changeRole', '迁移用户', 1, ''),
(372, 'admin', 1, 'Admin/Role/uploadPicture', '上传默认头像', 1, ''),
(373, 'admin', 1, 'Admin/Invite/index', '类型管理', 1, ''),
(374, 'admin', 1, 'Admin/Invite/invite', '邀请码管理', 1, ''),
(375, 'admin', 1, 'Admin/Invite/config', '基础配置', 1, ''),
(376, 'admin', 1, 'Admin/Invite/buyLog', '兑换记录', 1, ''),
(377, 'admin', 1, 'Admin/Invite/inviteLog', '邀请记录', 1, ''),
(378, 'admin', 1, 'Admin/Invite/userInfo', '用户信息', 1, ''),
(379, 'admin', 1, 'Admin/Invite/edit', '编辑邀请注册类型', 1, ''),
(380, 'admin', 1, 'Admin/Invite/setStatus', '删除邀请', 1, ''),
(381, 'admin', 1, 'Admin/Invite/delete', '删除邀请码', 1, ''),
(382, 'admin', 1, 'Admin/Invite/createCode', '生成邀请码', 1, ''),
(383, 'admin', 1, 'Admin/Invite/deleteTrue', '删除无用邀请码', 1, ''),
(384, 'admin', 1, 'Admin/Invite/cvs', '导出cvs', 1, ''),
(385, 'admin', 1, 'Admin/Invite/editUserInfo', '用户信息编辑', 1, ''),
(386, 'admin', 1, 'Admin/Action/remove', '删除日志', 1, ''),
(387, 'admin', 1, 'Admin/Action/clear', '清空日志', 1, ''),
(388, 'admin', 1, 'Admin/User/setTypeStatus', '设置积分状态', 1, ''),
(389, 'admin', 1, 'Admin/User/delType', '删除积分类型', 1, ''),
(390, 'admin', 1, 'Admin/User/getNickname', '充值积分', 1, ''),
(391, 'admin', 1, 'Admin/Menu/del', '删除菜单', 1, ''),
(392, 'admin', 1, 'Admin/Menu/toogleDev', '设置开发者模式可见', 1, ''),
(393, 'admin', 1, 'Admin/Menu/toogleHide', '设置显示隐藏', 1, ''),
(394, 'admin', 1, 'Admin/ActionLimit/setLimitStatus', '行为限制启用、禁用、删除', 1, ''),
(395, 'admin', 1, 'Admin/SEO/setRuleStatus', '启用、禁用、删除、回收站还原', 1, ''),
(396, 'admin', 1, 'Admin/SEO/doClear', '回收站彻底删除', 1, ''),
(397, 'admin', 1, 'Admin/Role/initUnhaveUser', '初始化无角色用户', 1, ''),
(398, 'admin', 1, 'Admin/Addons/delHook', '删除钩子', 1, ''),
(399, 'admin', 1, 'Admin/Update/usePack', '使用补丁', 1, ''),
(400, 'admin', 1, 'Admin/Update/view', '查看补丁', 1, ''),
(401, 'admin', 1, 'Admin/Update/delPack', '删除补丁', 1, ''),
(402, 'admin', 1, 'Admin/UserTag/userTag', '标签列表', 1, ''),
(403, 'admin', 1, 'Admin/UserTag/add', '添加分类、标签', 1, ''),
(404, 'admin', 1, 'Admin/UserTag/setStatus', '设置分类、标签状态', 1, ''),
(405, 'admin', 1, 'Admin/UserTag/tagTrash', '分类、标签回收站', 1, ''),
(406, 'admin', 1, 'Admin/UserTag/userTagClear', '测底删除回收站内容', 1, ''),
(407, 'admin', 1, 'Admin/role/configusertag', '可拥有标签配置', 1, ''),
(408, 'admin', 1, 'Admin/Module/edit', '编辑模块', 1, ''),
(409, 'admin', 1, 'Admin/Config/website', '网站信息', 1, ''),
(410, 'admin', 1, 'Admin/Theme/setTheme', '使用主题', 1, ''),
(411, 'admin', 1, 'Admin/Theme/lookTheme', '查看主题', 1, ''),
(412, 'admin', 1, 'Admin/Theme/packageDownload', '主题打包下载', 1, ''),
(413, 'admin', 1, 'Admin/Theme/delete', '卸载删除主题', 1, ''),
(414, 'admin', 1, 'Admin/Theme/add', '上传安装主题', 1, ''),
(415, 'admin', 2, 'Admin/Home/config', '网站主页', 1, ''),
(416, 'admin', 1, 'Admin/Home/config', '基本设置', 1, ''),
(418, 'admin', 1, 'Admin/Weibo/setWeiboTop', '置顶动态', 1, ''),
(419, 'admin', 1, 'Admin/Weibo/setWeiboStatus', '设置动态状态', 1, ''),
(420, 'admin', 1, 'Admin/Weibo/setCommentStatus', '设置动态评论状态', 1, ''),
(423, 'admin', 1, 'Admin/People/config', '基本设置', 1, ''),
(424, 'admin', 1, 'Admin/Cloud/index', '云市场', 1, ''),
(425, 'admin', 2, 'Admin/authorize/ssoSetting', '授权', 1, ''),
(426, 'admin', 2, 'Admin/Role/index', '角色', 1, ''),
(428, 'admin', 2, 'Admin/ActionLimit/limitList', '安全', 1, ''),
(429, 'admin', 2, 'Admin/Cloud/index', '云市场', 1, ''),
(430, 'admin', 2, 'Admin/People/config', '会员展示', 1, ''),
(431, 'admin', 1, 'Admin/Index/index', '后台入口', 1, ''),
(432, 'Weibo', 1, 'Weibo/Index/editWeibo', '编辑动态(管理)', 1, ''),
(433, 'Ucenter', 1, 'Ucenter/Attest/apply', '申请认证', 1, ''),
(10000, 'News', 1, 'News/Index/add', '资讯投稿', 1, ''),
(10001, 'News', 1, 'News/Index/edit', '编辑资讯（管理）', 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_m_channel`
--

CREATE TABLE `ocenter_m_channel` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '频道ID',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级频道ID',
  `title` char(30) NOT NULL COMMENT '频道标题',
  `url` char(100) NOT NULL COMMENT '频道连接',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '导航排序',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `target` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '新窗口打开',
  `out_site` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否为站外链接',
  `color` varchar(30) NOT NULL,
  `band_color` varchar(30) NOT NULL,
  `band_text` varchar(30) NOT NULL,
  `icon` varchar(20) NOT NULL,
  `image` int(11) NOT NULL,
  `remark` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_m_channel`
--

INSERT INTO `ocenter_m_channel` (`id`, `pid`, `title`, `url`, `sort`, `create_time`, `update_time`, `status`, `target`, `out_site`, `color`, `band_color`, `band_text`, `icon`, `image`, `remark`) VALUES
(1, 0, '动态', 'Weibo/Index/index', 0, 0, 0, 1, 0, 0, '', '', '', 'icon-iconfontdongtai', 0, ''),
(2, 0, '圈子', 'Weibo/Crowd/crowd', 1, 0, 0, 1, 0, 0, '', '', '', 'icon-quanzi', 0, ''),
(5, 0, '广场', 'ucenter/index/square', 1, 0, 0, 1, 0, 0, '', '', '', 'icon-remen', 0, ''),
(6, 0, '资讯', 'News/index/index', 100, 0, 0, 1, 0, 0, '', '', '', 'rss', 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_m_config`
--

CREATE TABLE `ocenter_m_config` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '配置ID',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '配置名称',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '配置类型',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '配置说明',
  `group` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '配置分组',
  `extra` varchar(255) NOT NULL DEFAULT '' COMMENT '配置值',
  `remark` varchar(500) NOT NULL COMMENT '配置说明',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `value` text NOT NULL COMMENT '配置值',
  `sort` smallint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_m_config`
--

INSERT INTO `ocenter_m_config` (`id`, `name`, `type`, `title`, `group`, `extra`, `remark`, `create_time`, `update_time`, `status`, `value`, `sort`) VALUES
(100, 'WEB_SITE_CLOSE', 4, '关闭站点', 1, '0:关闭,1:开启', '站点关闭后其他用户不能访问，管理员可以正常访问', 1378898976, 1379235296, 1, '1', 11),
(108, 'DATA_BACKUP_PATH', 1, '数据库备份根路径', 4, '', '路径必须以 / 结尾', 1381482411, 1381482411, 1, './Data/Backup', 16),
(109, 'DATA_BACKUP_PART_SIZE', 0, '数据库备份卷大小', 4, '', '该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M', 1381482488, 1381729564, 1, '20971520', 18),
(124, 'COUNT_DAY', 0, '后台首页统计用户增长天数', 0, '', '默认统计最近半个月的用户数增长情况', 1420791945, 1420876261, 1, '7', 0),
(126, 'USER_NAME_BAOLIU', 1, '保留用户名和昵称', 3, '', '禁止注册用户名和昵称，包含这些即无法注册,用\" , \"号隔开，用户只能是英文，下划线_，数字等', 1388845937, 1388845937, 1, '管理员,测试,admin,垃圾', 0),
(129, 'VERIFY_TYPE', 4, '验证码类型', 4, '1:中文\r\n2:英文\r\n3:数字\r\n4:英文+数字', '验证码类型', 1388500873, 1405561731, 1, '4', 0),
(134, 'URL_MODEL', 4, 'URL模式', 4, '2:REWRITE模式(开启伪静态)\r\n3:兼容模式', '选择Rewrite模式则开启伪静态，在开启伪静态之前需要先<a href=\"http://v2.opensns.cn/index.php?s=/news/index/detail/id/128.html\" target=\"_blank\">设置伪静态</a>或者阅读/Rewrite/readme.txt中的说明，默认建议开启兼容模式', 1421027546, 1421027676, 1, '3', 0),
(137, 'WEB_SITE_CLOSE_HINT', 2, '关站提示文字', 1, '', '站点关闭后的提示文字。', 1433731248, 1433731287, 1, '网站正在更新维护，请稍候再试。', 4),
(141, 'LOST_LONG', 0, '用户流失标准（天）', 0, '', '', 1469414315, 1469414315, 1, '30', 0),
(142, '_USERCONFIG_REG_SWITCH', 0, '', 0, '', '', 1427094903, 1427094903, 1, 'mobile', 0),
(143, '_CONFIG_PICTURE_UPLOAD_DRIVER', 0, '', 0, '', '', 1488937154, 1488937154, 1, 'qiniu', 0);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_m_hooks`
--

CREATE TABLE `ocenter_m_hooks` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text NOT NULL COMMENT '描述',
  `type` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT '类型',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 ''，''分割'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_m_hooks`
--

INSERT INTO `ocenter_m_hooks` (`id`, `name`, `description`, `type`, `update_time`, `addons`) VALUES
(38, 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', 1, 0, ''),
(39, 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', 1, 0, 'SuperLinks'),
(40, 'adminEditor', '后台内容编辑页编辑器', 1, 1378982734, 'EditorForAdmin'),
(41, 'AdminIndex', '首页小格子个性化显示', 1, 1382596073, 'SiteStat,SyncLogin,DevTeam,SystemInfo,LocalComment'),
(42, 'topicComment', '评论提交方式扩展钩子。', 1, 1380163518, ''),
(43, 'app_begin', '应用开始', 2, 1384481614, 'Iswaf'),
(48, 'weiboType', '动态类型', 1, 1409121894, ''),
(49, 'repost', '转发钩子', 1, 1403668286, ''),
(50, 'syncLogin', '第三方登陆位置', 1, 1403700579, 'SyncLogin'),
(51, 'syncMeta', '第三方登陆meta接口', 1, 1403700633, 'SyncLogin'),
(52, 'J_China_City', '每个系统都需要的一个中国省市区三级联动插件。', 1, 1403841931, 'ChinaCity'),
(54, 'imageSlider', '图片轮播钩子', 1, 1407144022, ''),
(56, 'beforeSendWeibo', '在发动态之前预处理动态', 2, 1408084504, 'InsertFile'),
(57, 'beforeSendRepost', '转发动态前的预处理钩子', 2, 1408085689, ''),
(58, 'parseWeiboContent', '解析动态内容钩子', 2, 1409121261, ''),
(59, 'userConfig', '用户配置页面钩子', 1, 1417137557, 'SyncLogin'),
(60, 'weiboSide', '动态侧边钩子', 1, 1417063425, 'Retopic,Recommend'),
(61, 'personalMenus', '顶部导航栏个人下拉菜单', 1, 1417146501, ''),
(62, 'dealPicture', '上传图片处理', 2, 1417139975, ''),
(63, 'ucenterSideMenu', '用户中心左侧菜单', 1, 1417161205, ''),
(64, 'afterTop', '顶部导航之后的钩子，调用公告等', 1, 1429671392, ''),
(65, 'report', '举报钩子', 1, 1429511732, 'Report'),
(66, 'handleAction', '行为的额外操作', 2, 1433300260, 'CheckIn'),
(67, 'uploadDriver', '附件图片上传引擎', 2, 1435306269, ''),
(68, 'sms', '短信插件钩子', 2, 1437382105, ''),
(69, 'filterHtmlContent', '渲染富文本', 2, 1441951420, ''),
(70, 'parseContent', '解析内容', 2, 1445828128, 'Sensitive'),
(71, 'tool', '返回顶部，右下角工具栏', 1, 1445828128, ''),
(72, 'homeIndex', '网站首页', 2, 1445828128, '');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_m_menu`
--

CREATE TABLE `ocenter_m_menu` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '文档ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `pid` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级分类ID',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '排序（同级有效）',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `hide` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `tip` varchar(255) NOT NULL DEFAULT '' COMMENT '提示',
  `group` varchar(50) DEFAULT '' COMMENT '分组',
  `is_dev` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否仅开发者模式可见',
  `icon` varchar(20) NOT NULL COMMENT '导航图标',
  `module` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_m_menu`
--

INSERT INTO `ocenter_m_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`, `icon`, `module`) VALUES
(1, '首页', 0, 1, 'Index/index', 0, '', '', 0, 'home', ''),
(2, '用户', 0, 2, 'User/index', 0, '', '', 0, 'user', ''),
(3, '用户管理', 2, 2, 'User/index', 0, '', '用户管理', 0, '', ''),
(4, '积分规则', 113, 3, 'User/action', 0, '', '行为管理', 0, '', ''),
(5, '新增用户行为', 4, 0, 'User/addaction', 0, '', '', 0, '', ''),
(6, '编辑用户行为', 4, 0, 'User/editaction', 0, '', '', 0, '', ''),
(7, '保存用户行为', 4, 0, 'User/saveAction', 0, '\"用户->用户行为\"保存编辑和新增的用户行为', '', 0, '', ''),
(8, '变更行为状态', 4, 0, 'User/setStatus', 0, '\"用户->用户行为\"中的启用,禁用和删除权限', '', 0, '', ''),
(9, '禁用会员', 4, 0, 'User/changeStatus?method=forbidUser', 0, '\"用户->用户信息\"中的禁用', '', 0, '', ''),
(10, '启用会员', 4, 0, 'User/changeStatus?method=resumeUser', 0, '\"用户->用户信息\"中的启用', '', 0, '', ''),
(11, '删除会员', 4, 0, 'User/changeStatus?method=deleteUser', 0, '\"用户->用户信息\"中的删除', '', 0, '', ''),
(12, '权限组管理', 2, 5, 'AuthManager/index', 0, '', '权限管理', 0, '', ''),
(13, '删除', 12, 0, 'AuthManager/changeStatus?method=deleteGroup', 0, '删除权限组', '', 0, '', ''),
(14, '禁用', 12, 0, 'AuthManager/changeStatus?method=forbidGroup', 0, '禁用权限组', '', 0, '', ''),
(15, '恢复', 12, 0, 'AuthManager/changeStatus?method=resumeGroup', 0, '恢复已禁用的权限组', '', 0, '', ''),
(16, '新增', 12, 0, 'AuthManager/createGroup', 0, '创建新的权限组', '', 0, '', ''),
(17, '编辑', 12, 0, 'AuthManager/editGroup', 0, '编辑权限组名称和描述', '', 0, '', ''),
(18, '保存权限组', 12, 0, 'AuthManager/writeGroup', 0, '新增和编辑权限组的\"保存\"按钮', '', 0, '', ''),
(19, '授权', 12, 0, 'AuthManager/group', 0, '\"后台 \\ 用户 \\ 用户信息\"列表页的\"授权\"操作按钮,用于设置用户所属权限组', '', 0, '', ''),
(20, '访问授权', 12, 0, 'AuthManager/access', 0, '\"后台 \\ 用户 \\ 权限管理\"列表页的\"访问授权\"操作按钮', '', 0, '', ''),
(21, '成员授权', 12, 0, 'AuthManager/user', 0, '\"后台 \\ 用户 \\ 权限管理\"列表页的\"成员授权\"操作按钮', '', 0, '', ''),
(22, '解除授权', 12, 0, 'AuthManager/removeFromGroup', 0, '\"成员授权\"列表页内的解除授权操作按钮', '', 0, '', ''),
(23, '保存成员授权', 12, 0, 'AuthManager/addToGroup', 0, '\"用户信息\"列表页\"授权\"时的\"保存\"按钮和\"成员授权\"里右上角的\"添加\"按钮)', '', 0, '', ''),
(24, '分类授权', 12, 0, 'AuthManager/category', 0, '\"后台 \\ 用户 \\ 权限管理\"列表页的\"分类授权\"操作按钮', '', 0, '', ''),
(25, '保存分类授权', 12, 0, 'AuthManager/addToCategory', 0, '\"分类授权\"页面的\"保存\"按钮', '', 0, '', ''),
(26, '模型授权', 12, 0, 'AuthManager/modelauth', 0, '\"后台 \\ 用户 \\ 权限管理\"列表页的\"模型授权\"操作按钮', '', 0, '', ''),
(27, '保存模型授权', 12, 0, 'AuthManager/addToModel', 0, '\"分类授权\"页面的\"保存\"按钮', '', 0, '', ''),
(28, '新增权限节点', 12, 0, 'AuthManager/addNode', 1, '', '', 1, '', ''),
(29, '前台权限管理', 12, 0, 'AuthManager/accessUser', 1, '', '权限管理', 0, '', ''),
(30, '删除权限节点', 12, 0, 'AuthManager/deleteNode', 1, '', '', 0, '', ''),
(31, '行为日志', 113, 4, 'Action/actionlog', 0, '', '行为管理', 0, '', ''),
(32, '查看行为日志', 31, 0, 'action/edit', 1, '', '', 0, '', ''),
(33, '修改密码', 2, 3, 'User/updatePassword', 1, '', '', 0, '', ''),
(34, '修改昵称', 2, 4, 'User/updateNickname', 1, '', '', 0, '', ''),
(36, '用户头衔列表', 35, 0, 'Rank/userRankList', 1, '', '', 0, '', ''),
(37, '关联新头衔', 35, 0, 'Rank/userAddRank', 1, '', '', 0, '', ''),
(38, '编辑头衔关联', 35, 0, 'Rank/userChangeRank', 1, '', '', 0, '', ''),
(40, '添加、编辑分组', 39, 0, 'Admin/User/editProfile', 0, '', '', 0, '', ''),
(41, '分组排序', 39, 0, 'Admin/User/sortProfile', 0, '', '', 0, '', ''),
(42, '字段列表', 39, 0, 'Admin/User/field', 0, '', '', 0, '', ''),
(43, '添加、编辑字段', 39, 0, 'Admin/User/editFieldSetting', 0, '', '', 0, '', ''),
(44, '字段排序', 39, 0, 'Admin/User/sortField', 0, '', '', 0, '', ''),
(46, '扩展资料详情', 45, 0, 'User/expandinfo_details', 0, '', '', 0, '', ''),
(49, '转移权限组', 2, 7, 'User/changeGroup', 1, '批量转移权限组', '', 0, '', ''),
(50, '基础配置', 2, 1, 'UserConfig/index', 0, '', '用户配置', 0, '', ''),
(51, '积分类型列表', 113, 1, 'User/scoreList', 0, '', '行为管理', 0, '', ''),
(52, '新增/编辑类型', 113, 2, 'User/editScoreType', 1, '', '行为管理', 0, '', ''),
(57, '插件管理', 105, 4, 'Addons/index', 0, '', '本地', 0, '', ''),
(58, '钩子管理', 57, 2, 'Addons/hooks', 0, '', '本地', 0, '', ''),
(59, '创建', 57, 0, 'Addons/create', 0, '服务器上创建插件结构向导', '', 0, '', ''),
(60, '检测创建', 57, 0, 'Addons/checkForm', 0, '检测插件是否可以创建', '', 0, '', ''),
(61, '预览', 57, 0, 'Addons/preview', 0, '预览插件定义类文件', '', 0, '', ''),
(62, '快速生成插件', 57, 0, 'Addons/build', 0, '开始生成插件结构', '', 0, '', ''),
(64, '设置', 57, 0, 'Addons/config', 0, '设置插件配置', '', 0, '', ''),
(65, '禁用', 57, 0, 'Addons/disable', 0, '禁用插件', '', 0, '', ''),
(66, '启用', 57, 0, 'Addons/enable', 0, '启用插件', '', 0, '', ''),
(67, '安装', 57, 0, 'Addons/install', 0, '安装插件', '', 0, '', ''),
(68, '卸载', 57, 0, 'Addons/uninstall', 0, '卸载插件', '', 0, '', ''),
(69, '更新配置', 57, 0, 'Addons/saveconfig', 0, '更新插件配置处理', '', 0, '', ''),
(70, '插件后台列表', 57, 0, 'Addons/adminList', 0, '', '', 0, '', ''),
(71, 'URL方式访问插件', 57, 0, 'Addons/execute', 0, '控制是否有权限通过url访问插件控制器方法', '', 0, '', ''),
(72, '新增钩子', 58, 0, 'Addons/addHook', 0, '', '', 0, '', ''),
(73, '编辑钩子', 58, 0, 'Addons/edithook', 0, '', '', 0, '', ''),
(74, '系统', 0, 7, 'Config/website', 0, '', '', 0, 'windows', ''),
(75, '网站设置', 74, 1, 'Config/group', 0, '', '系统设置', 0, '', ''),
(77, '编辑', 76, 0, 'Config/edit', 0, '新增编辑和保存配置', '', 0, '', ''),
(78, '删除', 76, 0, 'Config/del', 0, '删除配置', '', 0, '', ''),
(79, '新增', 76, 0, 'Config/add', 0, '新增配置', '', 0, '', ''),
(80, '保存', 76, 0, 'Config/save', 0, '保存配置', '', 0, '', ''),
(81, '排序', 76, 0, 'Config/sort', 1, '', '', 0, '', ''),
(83, '新增', 82, 0, 'Menu/add', 0, '', '系统设置', 0, '', ''),
(84, '编辑', 82, 0, 'Menu/edit', 0, '', '', 0, '', ''),
(85, '导入', 82, 0, 'Menu/import', 0, '', '', 0, '', ''),
(86, '排序', 82, 0, 'Menu/sort', 1, '', '', 0, '', ''),
(87, '底部导航', 74, 3, 'Channel/index', 0, '', '导航管理', 0, '', ''),
(88, '新增', 87, 0, 'Channel/add', 0, '', '', 0, '', ''),
(89, '编辑', 87, 0, 'Channel/edit', 0, '', '', 0, '', ''),
(90, '删除', 87, 0, 'Channel/del', 0, '', '', 0, '', ''),
(91, '排序', 87, 0, 'Channel/sort', 1, '', '', 0, '', ''),
(103, '全部补丁', 74, 16, 'Update/quick', 1, '', '升级补丁', 0, '', ''),
(104, '新增补丁', 74, 15, 'Update/addpack', 1, '', '升级补丁', 0, '', ''),
(105, '扩展', 0, 11, 'module/lists', 0, '', '', 0, 'cloud', ''),
(106, '模块安装', 105, 3, 'module/install', 1, '', '本地', 0, '', ''),
(107, '模块管理', 105, 5, 'module/lists', 0, '', '本地', 0, '', ''),
(108, '卸载模块', 105, 7, 'module/uninstall', 1, '', '本地', 0, '', ''),
(112, '新增/编辑应用', 109, 0, 'authorize/editssoapp', 1, '', '单点登录', 0, '', ''),
(113, '安全', 0, 5, 'ActionLimit/limitList', 0, '', '', 0, 'shield', ''),
(114, '行为限制列表', 113, 6, 'ActionLimit/limitList', 0, '', '行为限制', 0, '', ''),
(115, '新增/编辑行为限制', 113, 7, 'ActionLimit/editLimit', 1, '', '行为限制', 0, '', ''),
(121, '默认积分配置', 117, 0, 'Role/configScore', 1, '', '', 0, '', ''),
(122, '默认权限配置', 117, 0, 'Role/configAuth', 1, '', '', 0, '', ''),
(123, '默认头像配置', 117, 0, 'Role/configAvatar', 1, '', '', 0, '', ''),
(124, '默认头衔配置', 117, 0, 'Role/configRank', 1, '', '', 0, '', ''),
(125, '默认字段管理', 117, 0, 'Role/configField', 1, '', '', 0, '', ''),
(134, '上传默认头像', 123, 0, 'Role/uploadPicture', 1, '', '', 0, '', ''),
(147, '用户信息编辑', 140, 0, 'Invite/editUserInfo', 1, '', '', 0, '', ''),
(148, '删除日志', 31, 0, 'Action/remove', 1, '', '', 0, '', ''),
(149, '清空日志', 31, 0, 'Action/clear', 1, '', '', 0, '', ''),
(150, '设置积分状态', 51, 0, 'User/setTypeStatus', 1, '', '', 0, '', ''),
(151, '删除积分类型', 51, 0, 'User/delType', 1, '', '', 0, '', ''),
(152, '充值积分-获取用户昵称', 53, 0, 'User/getNickname', 1, '', '', 0, '', ''),
(153, '删除菜单', 82, 0, 'Menu/del', 1, '', '', 0, '', ''),
(154, '设置开发者模式可见', 82, 0, 'Menu/toogleDev', 1, '', '', 0, '', ''),
(155, '设置显示隐藏', 82, 0, 'Menu/toogleHide', 1, '', '', 0, '', ''),
(156, '行为限制启用、禁用、删除', 114, 0, 'ActionLimit/setLimitStatus', 1, '', '', 0, '', ''),
(157, '启用、禁用、删除、回收站还原', 99, 0, 'SEO/setRuleStatus', 1, '', '', 0, '', ''),
(158, '回收站彻底删除', 102, 0, 'SEO/doClear', 1, '', '', 0, '', ''),
(159, '初始化无角色用户', 130, 0, 'Role/initUnhaveUser', 1, '', '', 0, '', ''),
(160, '删除钩子', 58, 0, 'Addons/delHook', 0, '', '', 0, '', ''),
(161, '使用补丁', 103, 0, 'Update/usePack', 1, '', '', 0, '', ''),
(162, '查看补丁', 103, 0, 'Update/view', 1, '', '', 0, '', ''),
(163, '删除补丁', 103, 0, 'Update/delPack', 1, '', '', 0, '', ''),
(165, '添加分类、标签', 164, 0, 'UserTag/add', 1, '', '', 0, '', ''),
(166, '设置分类、标签状态', 164, 0, 'UserTag/setStatus', 1, '', '', 0, '', ''),
(167, '分类、标签回收站', 164, 0, 'UserTag/tagTrash', 1, '', '', 0, '', ''),
(168, '测底删除回收站内容', 164, 0, 'UserTag/userTagClear', 1, '', '', 0, '', ''),
(169, '可拥有标签配置', 116, 14, 'role/configusertag', 1, '', '', 0, '', ''),
(170, '编辑模块', 107, 0, 'Module/edit', 1, '', '模块管理', 0, '', ''),
(171, '社区信息', 74, 2, 'Config/website', 0, '', '系统设置', 0, '', ''),
(173, '使用主题', 105, 8, 'Theme/setTheme', 1, '', '本地', 0, '', ''),
(174, '查看主题', 105, 9, 'Theme/lookTheme', 1, '', '本地', 0, '', ''),
(175, '主题打包下载', 105, 10, 'Theme/packageDownload', 1, '', '本地', 0, '', ''),
(176, '卸载删除主题', 105, 11, 'Theme/delete', 1, '', '本地', 0, '', ''),
(177, '上传安装主题', 105, 12, 'Theme/add', 1, '', '本地', 0, '', ''),
(197, '运营', 0, 4, 'Count/index', 0, '', '', 0, 'laptop', ''),
(200, '在线安装', 178, 0, 'Cloud/install', 1, '', '', 0, '', ''),
(201, '重置用户密码', 3, 0, 'User/initpass', 1, '', '', 0, '', ''),
(203, '获取版本信息', 202, 0, 'Cloud/version', 1, '', '', 0, '', ''),
(204, '获取文件列表', 202, 0, 'Cloud/getFileList', 1, '', '', 0, '', ''),
(205, '比较本地文件', 202, 0, 'Cloud/compare', 1, '', '', 0, '', ''),
(206, '覆盖文件', 202, 0, 'Cloud/cover', 1, '', '', 0, '', ''),
(207, '更新数据库', 202, 0, 'Cloud/updb', 1, '', '', 0, '', ''),
(208, '更新完成', 202, 0, 'Cloud/finish', 1, '', '', 0, '', ''),
(218, '获取扩展升级列表', 106, 0, 'Cloud/getVersionList', 1, '', '', 0, '', ''),
(232, '积分日志', 113, 0, 'Action/scoreLog', 0, '', '积分管理', 0, '', ''),
(233, '仪表盘', 1, 0, 'Index/index', 0, '', '系统首页', 0, '', ''),
(234, '数据统计', 1, 1, 'Index/stats', 0, '', '统计管理', 0, '', ''),
(241, '刷新会话列表', 74, 0, 'Message/sessionRefresh', 1, '', '', 1, '', ''),
(242, '刷新消息模板列表', 74, 0, 'Message/tplRefresh', 1, '', '', 1, '', ''),
(250, '网站统计', 197, 0, 'Count/index', 0, '', '数据统计', 0, '', ''),
(251, '流失率统计', 197, 0, 'Count/lost', 0, '', '数据统计', 0, '', ''),
(252, '留存率统计', 197, 0, 'Count/remain', 0, '', '数据统计', 0, '', ''),
(254, '活跃用户统计', 197, 0, 'Count/active', 0, '', '数据统计', 0, '', ''),
(256, '在线用户列表', 197, 0, 'Count/nowUserList', 0, '', '数据统计', 0, '', ''),
(257, '下线在线用户', 197, 0, 'Count/downUser', 1, '', '数据统计', 0, '', ''),
(260, '添加', 233, 0, 'Index/addTo', 0, '', '', 0, '', ''),
(261, '删除', 233, 0, 'Index/delTile', 0, '', '', 0, '', ''),
(262, '修改', 233, 0, 'Index/setTile', 0, '', '', 0, '', ''),
(266, '图片列表', 74, 0, 'Picture/pictureList', 0, '', '图片管理', 1, '', ''),
(267, '设置图片状态、删除图片', 266, 0, 'Picture/setStatus', 1, '', '图片管理', 1, '', ''),
(268, '支付系统', 0, 0, 'Order/index', 1, '', '', 0, '', 'Order'),
(269, '配置', 268, 0, 'Order/config', 0, '', '配置管理', 0, '', 'Order'),
(270, '商品订单列表', 268, 0, 'Order/index', 0, '', '订单列表', 0, '', 'Order'),
(271, '充值订单列表', 268, 0, 'Order/rechargeList', 0, '', '订单列表', 0, '', 'Order'),
(272, '提现记录表', 268, 0, 'Order/withdrawList', 0, '', '订单列表', 0, '', 'Order'),
(273, '短信日志', 113, 4, 'Action/smslog', 0, '', '行为管理', 0, '', ''),
(10000, '网站主页', 0, 0, 'Home/config', 1, '', '', 0, 'home', 'Home'),
(10001, '基本设置', 10000, 0, 'Home/config', 0, '', '设置', 0, '', 'Home'),
(10002, '动态', 0, 8, 'Weibo/weibo', 1, '', '', 0, '', 'Weibo'),
(10003, '动态管理', 10002, 1, 'Weibo/weibo', 0, '', '动态', 0, '', 'Weibo'),
(10004, '回复管理', 10002, 3, 'Weibo/comment', 0, '', '回复', 0, '', 'Weibo'),
(10005, '编辑动态', 10002, 0, 'Weibo/editWeibo', 1, '', '', 0, '', 'Weibo'),
(10006, '编辑回复', 10002, 0, 'Weibo/editComment', 1, '', '', 0, '', 'Weibo'),
(10007, '动态回收站', 10002, 2, 'Weibo/weiboTrash', 0, '', '动态', 0, '', 'Weibo'),
(10008, '回复回收站', 10002, 4, 'Weibo/commentTrash', 0, '', '回复', 0, '', 'Weibo'),
(10009, '基本设置', 10002, 0, 'Weibo/config', 0, '动态的基本配置', '动态', 0, '', 'Weibo'),
(10011, '置顶动态', 10002, 0, 'Weibo/setWeiboTop', 1, '', '', 0, '', 'Weibo'),
(10012, '设置动态状态', 10002, 0, 'Weibo/setWeiboStatus', 1, '', '', 0, '', 'Weibo'),
(10013, '设置动态评论状态', 10002, 0, 'Weibo/setCommentStatus', 1, '', '', 0, '', 'Weibo'),
(10016, '会员展示', 0, 22, 'People/config', 1, '', '', 0, '', 'People'),
(10017, '基本设置', 10016, 0, 'People/config', 0, '', '配置', 0, '', 'People'),
(10018, '圈子配置', 10002, 0, 'Weibo/crowdConfig', 0, '', '圈子管理', 0, '', ''),
(10019, '圈子类型', 10002, 0, 'Weibo/crowdType', 0, '', '圈子管理', 0, '', ''),
(10020, '圈子管理', 10002, 0, 'Weibo/crowd', 0, '', '圈子管理', 0, '', ''),
(10021, '编辑圈子', 10002, 0, 'Weibo/editCrowd', 1, '', '圈子管理', 0, '', ''),
(10022, '设置圈子类型状态', 10019, 0, 'Weibo/setcrowdtypestatus', 1, '', '圈子管理', 0, '', ''),
(10023, '设置圈子状态', 10021, 0, 'Weibo/setcrowdstatus', 1, '', '圈子管理', 0, '', ''),
(10024, '圈子是否可发送动态', 10002, 0, 'Weibo/doCrowdAllowPost', 1, '', '圈子管理', 0, '', ''),
(10025, '执行默认关注', 10002, 0, 'Weibo/followCrowd', 1, '', '圈子管理', 0, '', ''),
(10026, '修复关注数脚本', 10002, 0, 'Weibo/repaircrowdfollow', 1, '', '圈子管理', 0, '', ''),
(10038, '商城', 0, 0, 'Mall/index', 1, '', '', 0, '', 'Mall'),
(10039, '商品列表', 10038, 0, 'Mall/index', 0, '', '商城管理', 0, '', 'Mall'),
(10040, '设置热门商品', 10038, 0, 'Mall/setHotGoods', 1, '', '', 0, '', 'Mall'),
(10041, '添加商品', 10038, 0, 'Mall/addGoods', 0, '', '商品管理', 0, '', 'Mall'),
(10042, '设置商品状态', 10038, 0, 'Mall/setGoodsStatus', 1, '', '', 0, '', 'Mall'),
(10043, '商品分类', 10038, 0, 'Mall/goodsCategory', 0, '', '商品管理', 0, '', 'Mall'),
(10044, '新增分类', 10043, 0, 'Mall/addCate', 1, '', '', 0, '', 'Mall'),
(10045, '设置分类状态', 10043, 0, 'Mall/setCateStatus', 1, '', '', 0, '', 'Mall'),
(10046, '规格', 10038, 0, 'Mall/spec', 0, '', '规格管理', 0, '', 'Mall'),
(10047, '编辑规格', 10046, 0, 'Mall/editSpec', 0, '', '规格管理', 0, '', 'Mall'),
(10048, '编辑规格详情', 10046, 0, 'Mall/editSpecValue', 1, '', '规格管理', 0, '', 'Mall'),
(10049, '规格详情状态', 10046, 0, 'Mall/setSpecValueStatus', 1, '', '规格管理', 0, '', 'Mall'),
(10050, '规格详情', 10046, 0, 'Mall/specValue', 1, '', '规格管理', 0, '', 'Mall'),
(10051, '设置规格状态', 10046, 0, 'Mall/setSpecStatus', 1, '', '规格管理', 0, '', 'Mall'),
(10052, '商城设置', 10038, 0, 'Mall/config', 0, '', '商城管理', 0, '', 'Mall'),
(10053, '广场设置', 74, 2, 'Ucenter/setsquareshow', 0, '', '系统设置', 0, '', ''),
(10054, '资讯', 0, 22, 'News/index', 1, '', '', 0, '', 'News'),
(10055, '审核列表', 10054, 0, 'News/audit', 0, '', '资讯管理', 0, '', 'News'),
(10056, '审核通过', 10055, 0, 'News/setNewsStatus', 1, '', '', 0, '', 'News'),
(10057, '资讯审核失败操作', 10055, 0, 'News/doAudit', 1, '', '', 0, '', 'News'),
(10058, '分类管理', 10054, 0, 'News/newsCategory', 0, '', '资讯配置', 0, '', 'News'),
(10059, '编辑、添加分类', 10058, 0, 'News/add', 1, '', '', 0, '', 'News'),
(10060, '设置分类状态', 10058, 0, 'News/setStatus', 1, '', '', 0, '', 'News'),
(10061, '资讯列表', 10054, 0, 'News/index', 0, '', '资讯管理', 0, 'rss-sign', 'News'),
(10062, '设为到期', 10061, 0, 'News/setDead', 1, '', '', 0, '', 'News'),
(10063, '编辑、添加资讯', 10061, 0, 'News/editNews', 1, '', '', 0, '', 'News'),
(10064, '基础配置', 10054, 0, 'News/config', 0, '', '资讯配置', 0, '', 'News'),
(10065, '资讯回收站', 10054, 0, 'News/newsTrash', 0, '', '资讯管理', 0, '', 'News');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_m_module`
--

CREATE TABLE `ocenter_m_module` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL COMMENT '模块名',
  `alias` varchar(30) NOT NULL COMMENT '中文名',
  `version` varchar(20) NOT NULL COMMENT '版本号',
  `is_com` tinyint(4) NOT NULL COMMENT '是否商业版',
  `show_nav` tinyint(4) NOT NULL COMMENT '是否显示在导航栏中',
  `summary` varchar(200) NOT NULL COMMENT '简介',
  `developer` varchar(50) NOT NULL COMMENT '开发者',
  `website` varchar(200) NOT NULL COMMENT '网址',
  `entry` varchar(50) NOT NULL COMMENT '前台入口',
  `is_setup` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否已安装',
  `sort` int(11) NOT NULL COMMENT '模块排序',
  `icon` varchar(20) NOT NULL,
  `can_uninstall` tinyint(4) NOT NULL,
  `admin_entry` varchar(50) NOT NULL,
  `menu_hide` tinyint(4) NOT NULL DEFAULT '0' COMMENT '后台入口隐藏',
  `auth_role` varchar(200) NOT NULL COMMENT '允许访问角色，为空表示非登录也能访问'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模块管理表';

--
-- 转存表中的数据 `ocenter_m_module`
--

INSERT INTO `ocenter_m_module` (`id`, `name`, `alias`, `version`, `is_com`, `show_nav`, `summary`, `developer`, `website`, `entry`, `is_setup`, `sort`, `icon`, `can_uninstall`, `admin_entry`, `menu_hide`, `auth_role`) VALUES
(2, 'Ucenter', '用户中心', '2.0.0', 0, 1, '用户中心模块，系统核心模块', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'Ucenter/index/index', 1, 0, 'user', 0, '', 0, ''),
(4, 'Weibo', '动态', '2.0.0', 0, 1, '动态模块，用户可以发布动态', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'Weibo/index/index', 1, 0, 'quote-left', 1, 'Admin/Weibo/weibo', 0, ''),
(5, 'Crowd', '圈子', '2.0.0', 0, 1, '圈子模块', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'Weibo/Crowd/crowd', 1, 0, 'quote-left', 0, 'Admin/Weibo/weibo', 0, ''),
(13, 'Core', '系统公共模块', '2.1.0', 0, 0, '系统核心模块，必不可少，负责核心的处理。', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', '', 1, 0, 'globe', 0, '', 0, ''),
(15, 'Message', '消息', '0.0.1', 1, 1, '消息', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'Message/index/index', 1, 0, 'quote-left', 0, '', 0, ''),
(16, 'Mall', '商城', '0.0.1', 1, 1, '商城', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'Mall/index/index', 1, 0, 'quote-left', 1, 'Admin/Mall/index', 0, ''),
(17, 'Event', '活动', '2.8.0', 0, 1, '活动模块，用户可以发起活动', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'Event/index/index', 0, 0, 'map-marker', 1, 'Admin/Event/index', 0, ''),
(18, 'Forum', '论坛', '1.0.0', 0, 1, '论坛模块，轻便强大的论坛模块', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'Forum/index/index', 0, 0, 'comments', 1, 'Admin/Forum/post', 0, ''),
(19, 'News', '资讯', '1.0.0', 0, 1, '资讯模块，用户可前台投稿的CMS模块', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'News/index/index', 1, 0, 'rss', 1, 'Admin/News/index', 0, ''),
(20, 'Order', '支付系统', '1.0.0', 0, 1, '支付模块', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'Order/index/index', 1, 0, '', 0, 'Admin/Order/index', 0, ''),
(21, 'Project', '项目管理', '0.0.1', 1, 1, '项目管理模块', '嘉兴想天信息科技有限公司', 'http://www.opensns.cn', 'Project/index/index', 0, 0, 'comments', 1, 'Admin/Project/index', 0, ''),
(22, 'Question', '问答', '2.4.0', 1, 1, '问答模块，用户可前台发布、回答问题', '嘉兴想天信息科技有限公司', 'http://www.ourstu.com', 'Question/index/index', 0, 0, 'question', 1, 'Admin/Question/index', 0, ''),
(23, 'Weixin', '微信公众平台', '2.1.0', 1, 1, '微信公众平台', '钱枪枪科技有限公司', 'http://www.zacms.com', 'Weixin/index/index', 0, 0, 'comments', 1, 'Admin/Weixin/index', 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_m_reward`
--

CREATE TABLE `ocenter_m_reward` (
  `id` int(11) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL,
  `message` text NOT NULL,
  `authorid` int(11) NOT NULL,
  `Articleid` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `table_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_m_session`
--

CREATE TABLE `ocenter_m_session` (
  `session_id` varchar(225) NOT NULL,
  `session_expire` int(11) NOT NULL,
  `session_data` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_m_session`
--

INSERT INTO `ocenter_m_session` (`session_id`, `session_expire`, `session_data`) VALUES
('2uj5isg4lj1j7g6n20app5ar11', 1507880910, 'opensns_m|a:3:{s:33:\"d2d977c58444271d9c780187e93f80e51\";a:2:{s:11:\"verify_code\";s:32:\"4d2f454a66affdc3b0c27d1a2669ee42\";s:11:\"verify_time\";i:1507880087;}s:9:\"user_auth\";a:5:{s:3:\"uid\";s:1:\"1\";s:8:\"username\";s:11:\"yoyocoinsdd\";s:15:\"last_login_time\";s:10:\"1507880128\";s:7:\"role_id\";s:1:\"1\";s:5:\"audit\";s:1:\"1\";}s:14:\"user_auth_sign\";s:40:\"f335cafd53634bd87a20db1ed6a9fd15312a3957\";}'),
('rn2617rlgkofj33l9n5veqmtq1', 1507881296, 'opensns_m|a:1:{s:33:\"d2d977c58444271d9c780187e93f80e51\";a:2:{s:11:\"verify_code\";s:32:\"2a6a916e7225831be79a20ef92d26709\";s:11:\"verify_time\";i:1507880652;}}');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_news`
--

CREATE TABLE `ocenter_news` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL COMMENT '标题',
  `description` varchar(200) NOT NULL COMMENT '描述',
  `category` int(11) NOT NULL COMMENT '分类',
  `status` tinyint(2) NOT NULL COMMENT '状态',
  `reason` varchar(100) NOT NULL COMMENT '审核失败原因',
  `sort` int(5) NOT NULL COMMENT '排序',
  `position` int(4) NOT NULL COMMENT '定位，展示位',
  `cover` int(11) NOT NULL COMMENT '封面',
  `view` int(10) NOT NULL COMMENT '阅读量',
  `comment` int(10) NOT NULL COMMENT '评论量',
  `collection` int(10) NOT NULL COMMENT '收藏量',
  `dead_line` int(11) NOT NULL COMMENT '有效期',
  `source` varchar(200) NOT NULL COMMENT '来源url',
  `create_time` int(11) NOT NULL,
  `post_time` int(11) NOT NULL COMMENT '预发布日期',
  `update_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资讯';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_news_category`
--

CREATE TABLE `ocenter_news_category` (
  `id` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `pid` int(11) NOT NULL,
  `can_post` tinyint(4) NOT NULL COMMENT '前台可投稿',
  `need_audit` tinyint(4) NOT NULL COMMENT '前台投稿是否需要审核',
  `sort` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资讯分类';

--
-- 转存表中的数据 `ocenter_news_category`
--

INSERT INTO `ocenter_news_category` (`id`, `title`, `pid`, `can_post`, `need_audit`, `sort`, `status`) VALUES
(1, '默认分类', 0, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_news_detail`
--

CREATE TABLE `ocenter_news_detail` (
  `news_id` int(11) NOT NULL,
  `content` text NOT NULL COMMENT '内容',
  `template` varchar(50) NOT NULL COMMENT '模板'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资讯详情';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_news_reply`
--

CREATE TABLE `ocenter_news_reply` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `to_f_reply_id` int(11) NOT NULL,
  `to_reply_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `uid` int(11) NOT NULL,
  `to_uid` int(11) NOT NULL,
  `ctime` int(11) NOT NULL,
  `is_del` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_news_search`
--

CREATE TABLE `ocenter_news_search` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `historical` varchar(50) NOT NULL COMMENT '历史记录',
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资讯';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_order_goods`
--

CREATE TABLE `ocenter_order_goods` (
  `id` bigint(20) NOT NULL COMMENT '订单号',
  `goods_id` varchar(50) NOT NULL COMMENT '商品id',
  `goods_type` int(11) NOT NULL COMMENT '商品类型',
  `uid` int(11) NOT NULL COMMENT '下单用户',
  `amount` decimal(10,2) NOT NULL COMMENT '交易金额',
  `field` int(11) NOT NULL COMMENT '交易字段',
  `method` varchar(50) NOT NULL COMMENT '交易方式',
  `status` tinyint(4) NOT NULL COMMENT '订单状态',
  `is_pay` tinyint(4) NOT NULL COMMENT '支付状态0未支付1已支付',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `pay_time` int(11) NOT NULL COMMENT '支付时间',
  `process` int(11) NOT NULL COMMENT '订单流程',
  `wechat_order` varchar(50) NOT NULL COMMENT '微信订单号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_order_recharge`
--

CREATE TABLE `ocenter_order_recharge` (
  `id` bigint(20) NOT NULL COMMENT '订单号',
  `field` int(11) NOT NULL COMMENT '充值字段',
  `amount` decimal(10,2) NOT NULL COMMENT '充值数额',
  `method` varchar(50) NOT NULL COMMENT '支付方式',
  `uid` int(11) NOT NULL COMMENT '充值用户',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `is_pay` tinyint(4) NOT NULL COMMENT '支付状态',
  `pay_time` int(11) NOT NULL COMMENT '支付时间',
  `process` int(11) NOT NULL COMMENT '订单流程',
  `wechat_order` varchar(50) NOT NULL COMMENT '微信订单号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_order_withdraw`
--

CREATE TABLE `ocenter_order_withdraw` (
  `id` int(11) NOT NULL,
  `field` int(11) NOT NULL COMMENT '提现字段',
  `amount` decimal(10,2) NOT NULL COMMENT '提现金额',
  `method` varchar(50) NOT NULL COMMENT '提现方式',
  `uid` int(11) NOT NULL COMMENT '提现用户',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(4) NOT NULL COMMENT '状态',
  `is_pay` tinyint(4) NOT NULL COMMENT '支付状态',
  `pay_uid` int(11) NOT NULL COMMENT '支付者',
  `pay_time` int(11) NOT NULL COMMENT '支付时间',
  `freeze_amount` decimal(10,2) NOT NULL COMMENT '冻结积分',
  `account_info` varchar(200) NOT NULL COMMENT '账户信息',
  `process` int(11) NOT NULL COMMENT '订单流程'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_pay`
--

CREATE TABLE `ocenter_pay` (
  `id` smallint(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `en_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_pay`
--

INSERT INTO `ocenter_pay` (`id`, `name`, `en_name`) VALUES
(1, '现金支付', 'cash'),
(2, '支付宝', 'alipay'),
(3, '微信支付', 'wechat'),
(4, '国内银行转账', 'national_bank'),
(5, '其他在线付款方式', 'other_online'),
(6, '国际电汇(SWIFT)', 'international_wire_swift'),
(7, 'Paypal', 'paypal'),
(8, 'Payza', 'payza'),
(9, 'OKPay', 'okpay'),
(10, 'Paytm', 'paytm');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_picture`
--

CREATE TABLE `ocenter_picture` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键id自增',
  `type` varchar(50) NOT NULL,
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '图片链接',
  `md5` char(32) NOT NULL DEFAULT '' COMMENT '文件md5',
  `sha1` char(40) NOT NULL DEFAULT '' COMMENT '文件 sha1编码',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `width` int(11) NOT NULL DEFAULT '0',
  `height` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_rank`
--

CREATE TABLE `ocenter_rank` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '上传者id',
  `title` varchar(50) NOT NULL,
  `logo` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `types` tinyint(2) NOT NULL DEFAULT '1' COMMENT '前台是否可申请',
  `label_content` varchar(10) NOT NULL COMMENT '文字头衔内容',
  `label_color` varchar(10) NOT NULL COMMENT '文字颜色',
  `label_bg` varchar(10) NOT NULL COMMENT 'label背景色'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_rank_user`
--

CREATE TABLE `ocenter_rank_user` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `rank_id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `is_show` tinyint(4) NOT NULL COMMENT '是否显示在昵称右侧（必须有图片才可）',
  `create_time` int(11) NOT NULL,
  `status` tinyint(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_register`
--

CREATE TABLE `ocenter_register` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `from` char(50) NOT NULL COMMENT '注册终端',
  `type` char(50) NOT NULL COMMENT '注册方式',
  `status` int(4) NOT NULL COMMENT '注册状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='注册终端和方式统计';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_report`
--

CREATE TABLE `ocenter_report` (
  `id` int(11) NOT NULL,
  `url` varchar(500) NOT NULL,
  `uid` int(11) NOT NULL,
  `reason` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `data` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `create_time` int(11) NOT NULL,
  `updata_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `handle_status` tinyint(4) NOT NULL,
  `handle_result` text NOT NULL,
  `handle_uid` int(11) NOT NULL,
  `handle_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_reward`
--

CREATE TABLE `ocenter_reward` (
  `id` int(11) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL,
  `message` text NOT NULL,
  `authorid` int(11) NOT NULL,
  `Articleid` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `table_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_role`
--

CREATE TABLE `ocenter_role` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL COMMENT '角色组id',
  `name` varchar(25) NOT NULL COMMENT '英文标识',
  `title` varchar(25) NOT NULL COMMENT '中文标题',
  `description` varchar(500) NOT NULL COMMENT '描述',
  `user_groups` varchar(200) NOT NULL COMMENT '默认权限组ids',
  `invite` tinyint(4) NOT NULL COMMENT '预留字段(类型：是否需要邀请注册等)',
  `audit` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否需要审核',
  `sort` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(2) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色表';

--
-- 转存表中的数据 `ocenter_role`
--

INSERT INTO `ocenter_role` (`id`, `group_id`, `name`, `title`, `description`, `user_groups`, `invite`, `audit`, `sort`, `status`, `create_time`, `update_time`) VALUES
(1, 0, 'default', '普通用户', '普通用户', '1', 0, 0, 0, 1, 1506049131, 1506049131);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_role_config`
--

CREATE TABLE `ocenter_role_config` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `name` varchar(25) NOT NULL COMMENT '标识',
  `category` varchar(25) NOT NULL COMMENT '归类标识',
  `value` text NOT NULL COMMENT '配置值',
  `data` text NOT NULL COMMENT '该配置的其它值',
  `update_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色配置表';

--
-- 转存表中的数据 `ocenter_role_config`
--

INSERT INTO `ocenter_role_config` (`id`, `role_id`, `name`, `category`, `value`, `data`, `update_time`) VALUES
(1, 1, 'user_tag', '', '2,3', '', 1476755028),
(2, 1, 'expend_field', 'expend_field', '1,2,3,4,5,6,7', '', 1476755034),
(3, 1, 'register_expend_field', 'expend_field', '1,2,3,4,5,6,7', '', 1476755110);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_role_group`
--

CREATE TABLE `ocenter_role_group` (
  `id` int(11) NOT NULL,
  `title` varchar(25) NOT NULL,
  `update_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色分组';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_schedule`
--

CREATE TABLE `ocenter_schedule` (
  `id` int(11) NOT NULL,
  `method` varchar(100) NOT NULL,
  `args` varchar(500) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `type_value` varchar(200) NOT NULL,
  `start_time` int(11) NOT NULL,
  `end_time` int(11) NOT NULL,
  `intro` varchar(500) NOT NULL,
  `lever` int(11) NOT NULL COMMENT '优先级',
  `status` tinyint(4) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_schedule`
--

INSERT INTO `ocenter_schedule` (`id`, `method`, `args`, `type`, `type_value`, `start_time`, `end_time`, `intro`, `lever`, `status`, `create_time`) VALUES
(1, 'Admin/Count->dayCount', '', 3, 'Daily=01:00', 1469167215, 2147483647, '执行了数据统计', 0, 0, 1469167281);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_score_log`
--

CREATE TABLE `ocenter_score_log` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `ip` bigint(20) NOT NULL,
  `type` int(11) NOT NULL,
  `action` varchar(20) NOT NULL,
  `value` double NOT NULL,
  `finally_value` double NOT NULL,
  `create_time` int(11) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `model` varchar(20) NOT NULL,
  `record_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_score_log`
--

INSERT INTO `ocenter_score_log` (`id`, `uid`, `ip`, `type`, `action`, `value`, `finally_value`, `create_time`, `remark`, `model`, `record_id`) VALUES
(1, 1, 2130706433, 1, 'inc', 10, 10, 1506217379, 'yoyoadmin在2017-09-24 09:42登录了账号【积分：+10分】', 'member', 1),
(2, 1, 2130706433, 1, 'inc', 10, 20, 1506389062, 'yoyoadmin在2017-09-26 09:24登录了账号【积分：+10分】', 'member', 1),
(3, 1, 2130706433, 1, 'inc', 1, 21, 1506494855, 'yoyoadmin在2017-09-27 14:47发布了新微博：1【积分：+1分】', 'weibo', 1),
(4, 100, 2130706433, 1, 'inc', 10, 10, 1506497016, 'vbboy在2017-09-27 15:23登录了账号【积分：+10分】', 'member', 100),
(5, 100, 2130706433, 1, 'inc', 1, 11, 1506498139, 'vbboy在2017-09-27 15:42发布了新微博：2【积分：+1分】', 'weibo', 2),
(6, 111, 2130706433, 1, 'inc', 10, 10, 1506761904, 'dsdfsdf在2017-09-30 16:58登录了账号【积分：+10分】', 'member', 111),
(7, 100, 2130706433, 1, 'inc', 10, 21, 1506762306, 'vbboy在2017-09-30 17:05登录了账号【积分：+10分】', 'member', 100),
(8, 112, 2130706433, 1, 'inc', 10, 10, 1507103624, 'bozai2017在2017-10-04 15:53登录了账号【积分：+10分】', 'member', 112),
(9, 114, 2130706433, 1, 'inc', 10, 10, 1507105096, 'bobozaa1在2017-10-04 16:18登录了账号【积分：+10分】', 'member', 114),
(10, 115, 2130706433, 1, 'inc', 10, 10, 1507336119, 'bboie在2017-10-07 08:28登录了账号【积分：+10分】', 'member', 115),
(11, 100, 2130706433, 1, 'inc', 10, 31, 1507465065, 'vbboy在2017-10-08 20:17登录了账号【积分：+10分】', 'member', 100),
(12, 116, 2130706433, 1, 'inc', 10, 10, 1507777470, 'soni在2017-10-12 11:04登录了账号【积分：+10分】', 'member', 116),
(13, 116, 2130706433, 1, 'inc', 10, 20, 1508033718, 'soni在2017-10-15 10:15登录了账号【积分：+10分】', 'member', 116),
(14, 117, 2130706433, 1, 'inc', 10, 10, 1508037248, 'fffeer在2017-10-15 11:14登录了账号【积分：+10分】', 'member', 117),
(15, 120, 2130706433, 1, 'inc', 10, 10, 1508049471, 'bozai112在2017-10-15 14:37登录了账号【积分：+10分】', 'member', 120),
(16, 122, 2130706433, 1, 'inc', 10, 10, 1508050020, 'njghg在2017-10-15 14:47登录了账号【积分：+10分】', 'member', 122),
(17, 123, 2130706433, 1, 'inc', 10, 10, 1508050730, 'xcvxcv在2017-10-15 14:58登录了账号【积分：+10分】', 'member', 123),
(18, 147, 2130706433, 1, 'inc', 10, 10, 1508128102, 'vbboy2015在2017-10-16 12:28登录了账号【积分：+10分】', 'member', 147),
(19, 146, 2130706433, 1, 'inc', 10, 10, 1508142183, 'sonia在2017-10-16 16:23登录了账号【积分：+10分】', 'member', 146),
(20, 146, 2130706433, 1, 'inc', 10, 20, 1508486412, 'sonia在2017-10-20 16:00登录了账号【积分：+10分】', 'member', 146);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_send_sms_log`
--

CREATE TABLE `ocenter_send_sms_log` (
  `id` int(11) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `content` text NOT NULL COMMENT '发送信息',
  `return` text NOT NULL COMMENT '接口返回数据',
  `type` varchar(32) NOT NULL COMMENT '判别发送客户端类型',
  `status` tinyint(3) NOT NULL DEFAULT '1',
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_sensitive`
--

CREATE TABLE `ocenter_sensitive` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_seo_rule`
--

CREATE TABLE `ocenter_seo_rule` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `app` varchar(40) NOT NULL,
  `controller` varchar(40) NOT NULL,
  `action` varchar(40) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `seo_keywords` text NOT NULL,
  `seo_description` text NOT NULL,
  `seo_title` text NOT NULL,
  `sort` int(11) NOT NULL,
  `summary` varchar(500) NOT NULL COMMENT 'seo变量介绍'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_seo_rule`
--

INSERT INTO `ocenter_seo_rule` (`id`, `title`, `app`, `controller`, `action`, `status`, `seo_keywords`, `seo_description`, `seo_title`, `sort`, `summary`) VALUES
(1000, '整站标题', '', '', '', 1, '', '', '', 7, '-'),
(1001, '用户中心', 'Ucenter', 'index', 'index', 1, '{$user_info.username|text}的个人主页', '{$user_info.username|text}的个人主页', '{$user_info.nickname|op_t}的个人主页', 3, '-'),
(1002, '网站首页', 'Home', 'Index', 'index', 1, '', '', '', 0, '-'),
(1003, '积分商城首页', 'Shop', 'Index', 'index', 1, '', '', '', 0, '-'),
(1004, '商品列表', 'Shop', 'Index', 'goods', 1, '', '', '', 0, 'category_name：当前分类名\nchild_category_name：子分类名'),
(1005, '商品详情', 'Shop', 'Index', 'goodsdetail', 1, '', '', '', 0, 'content：商品变量集\n   content.goods_name 商品名\n   content.goods_introduct 商品简介\n   content.goods_detail 商品详情'),
(1006, '活动主页', 'Event', 'Index', 'index', 1, '', '', '', 0, '-'),
(1007, '活动详情', 'Event', 'Index', 'detail', 1, '', '', '', 0, 'content：活动变量集\n   content.title 活动名称\n   content.type.title 活动分类名\n   content.user.nickname 发起人昵称\n   content.address 活动地点\n   content.limitCount 人数限制'),
(1008, '活动成员', 'Event', 'Index', 'member', 1, '', '', '', 0, '-'),
(1009, '专辑首页', 'Issue', 'Index', 'index', 1, '', '', '', 0, '-'),
(1010, '专辑详情', 'Issue', 'Index', 'issuecontentdetail', 1, '', '', '', 0, 'content：专辑内容变量集\n   content.user.nickname 内容发布者昵称\n   content.user.signature 内容发布者签名\n   content.url 内容的Url\n   content.title 内容标题\n   content.create_time|friendlyDate 发布时间\n   content.update_time|friendlyDate 更新时间'),
(1011, '论坛主页', 'Forum', 'Index', 'index', 1, '', '', '', 0, '-'),
(1012, '某个版块的帖子列表', 'Forum', 'Index', 'forum', 1, '', '', '', 0, 'forum：版块变量集\n   forum.description 版块描述\n   forum.title 版块名称\n   forum.topic_count 主题数\n   forum.total_count 帖子数'),
(1013, '帖子详情', 'Forum', 'Index', 'detail', 1, '', '', '', 0, 'post：帖子变量集\n   post.title 帖子标题\n   post.content 帖子详情\n   post.forum.title 帖子所在版块标题\n   '),
(1014, '搜索帖子', 'Forum', 'Index', 'search', 1, '', '', '', 0, 'keywords：搜索的关键词，2.4.0以后版本提供'),
(1015, '随便看看', 'Forum', 'Index', 'look', 1, '', '', '', 0, '-'),
(1016, '全部版块', 'Forum', 'Index', 'lists', 1, '', '', '', 0, '-'),
(1017, '资讯首页/某个分类下的文章列表', 'News', 'Index', 'index', 1, '', '', '', 0, 'now_category.title 当前分类的名称'),
(1018, '某篇文章的内容页', 'News', 'Index', 'detail', 1, '', '', '', 0, 'now_category.title 当前分类的名称\ninfo：文章变量集\n   info.title 文章标题\n   info.description 文章摘要\n   info.source 文章来源\n   info.detail.content 文章内容\nauthor.nickname 作者昵称\nauthor.signature 作者签名\n   '),
(1019, '微博首页', 'Weibo', 'Index', 'index', 1, '{$MODULE_ALIAS}', '{$MODULE_ALIAS}首页', '{$MODULE_ALIAS}-{$website_name}', 0, 'title：我关注的、热门微博、全站关注'),
(1020, '某条微博的详情页', 'Weibo', 'Index', 'weibodetail', 1, '{$weibo.title|text},{$website_name},{$MODULE_ALIAS}', '{$weibo.content|text}', '{$weibo.content|text}——{$MODULE_ALIAS}', 0, 'weibo:微博变量集\n   weibo.user.nickname 发布者昵称\n   weibo.content 微博内容'),
(1021, '微博搜索页面', 'Weibo', 'Index', 'search', 1, '', '', '', 0, 'search_keywords：搜索关键词'),
(1022, '热门话题列表', 'Weibo', 'Topic', 'topic', 1, '', '', '', 0, '-'),
(1023, '某个话题的话题页', 'Weibo', 'Topic', 'index', 1, '', '', '', 0, 'topic：话题变量集\n   topic.name 话题名称\nhost：话题主持人变量集\n   host.nickname 主持人昵称'),
(1024, '自动跳转到我的群组', 'Group', 'Index', 'index', 1, '', '', '', 0, '-'),
(1025, '全部群组', 'Group', 'Index', 'groups', 1, '', '', '', 0, '-'),
(1026, '我的群组-帖子列表', 'Group', 'Index', 'my', 1, '', '', '', 0, '-'),
(1027, '我的群组-全部关注的群组列表', 'Group', 'Index', 'mygroup', 1, '', '', '', 0, '-'),
(1028, '某个群组的帖子列表页面', 'Group', 'Index', 'group', 1, '', '', '', 0, 'search_key：如果查找帖子，则是关键词\ngroup：群组变量集\n   group.title 群组标题\n   group.user.nickname 创始人昵称\n   group.member_count 群组人数'),
(1029, '某篇帖子的内容页', 'Group', 'Index', 'detail', 1, '', '', '', 0, 'group：群组变量集\n   group.title 群组标题\n   group.user.nickname 创始人昵称\n   group.member_count 群组人数\npost：帖子变量集\n   post.title 帖子标题\n   post.content 帖子内容'),
(1030, '创建群组', 'Group', 'Index', 'create', 1, '', '', '', 0, '-'),
(1031, '发现', 'Group', 'Index', 'discover', 1, '', '', '', 0, '-'),
(1032, '精选', 'Group', 'Index', 'select', 1, '', '', '', 0, '-'),
(1033, '找人首页', 'People', 'Index', 'index', 1, '{$MODULE_ALIAS}', '{$MODULE_ALIAS}', '{$MODULE_ALIAS}', 0, '-'),
(1034, '微店首页', 'Store', 'Index', 'index', 1, '', '', '', 0, '-'),
(1035, '某个分类下的商品列表页', 'Store', 'Index', 'li', 1, '', '', '', 0, 'type：当前分类变量集\n   type.title 分类名称'),
(1036, '搜索商品列表页', 'Store', 'Index', 'search', 1, '', '', '', 0, 'searchKey：搜索关键词'),
(1037, '单个商品的详情页', 'Store', 'Index', 'info', 1, '', '', '', 0, 'info：商品变量集\n   info.title 商品标题\n   info.des 商品描述\n   info.shop：店铺变量集\n       info.shop.title 店铺名称\n       info.shop.summary 店铺简介\n       info.shop.position 店铺所在地\n       info.shop.user.nickname 店主昵称'),
(1038, '店铺街', 'Store', 'Index', 'lists', 1, '', '', '', 0, '-'),
(1039, '某个店铺的首页', 'Store', 'Shop', 'detail', 1, '', '', '', 0, 'shop：店铺变量集\n   shop.title 店铺名称\n   shop.summary 店铺简介\n   shop.position 店铺所在地\n   shop.user.nickname 店主昵称'),
(1040, '某个店铺下的商品列表页', 'Store', 'Shop', 'goods', 1, '', '', '', 0, 'shop：店铺变量集\n   shop.title 店铺名称\n   shop.summary 店铺简介\n   shop.position 店铺所在地\n   shop.user.nickname 店主昵称'),
(1041, '分类信息首页', 'Cat', 'Index', 'index', 1, '', '', '', 0, '-'),
(1042, '某个模型下的列表页', 'Cat', 'Index', 'li', 1, '', '', '', 0, 'entity：当前模型变量集\n   entity.alias 模型名'),
(1043, '某条信息的详情页', 'Cat', 'Index', 'info', 1, '', '', '', 0, 'entity：当前模型变量集\n   entity.alias 模型名\ninfo：当前信息\n   info.title 信息名称\nuser.nickname 发布者昵称'),
(1044, '待回答页面', 'Question', 'Index', 'waitanswer', 1, '', '', '', 0, '-'),
(1045, '热门问题', 'Question', 'Index', 'goodquestion', 1, '', '', '', 0, '-'),
(1046, '我的回答', 'Question', 'Index', 'myquestion', 1, '', '', '', 0, '-'),
(1047, '全部问题', 'Question', 'Index', 'questions', 1, '', '', '', 0, '-'),
(1048, '详情', 'Question', 'Index', 'detail', 1, '', '', '', 0, 'question：问题变量集\n   question.title 问题标题\n   question.description 问题描述\n   question.answer_num 回答数\nbest_answer：最佳回答\n   best_answer.content 最佳答案内容');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_session`
--

CREATE TABLE `ocenter_session` (
  `session_id` varchar(225) NOT NULL,
  `session_expire` int(11) NOT NULL,
  `session_data` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_session`
--

INSERT INTO `ocenter_session` (`session_id`, `session_expire`, `session_data`) VALUES
('r2n3v6tvfbmeb5am3s54crt8b5', 1508631589, ''),
('ho09jclbgubtin28vk8siofvt0', 1508631589, ''),
('kvmbfpuu2k0kphjp7tedjafd55', 1508631651, '');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_sso_app`
--

CREATE TABLE `ocenter_sso_app` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `create_time` int(11) NOT NULL,
  `config` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_super_links`
--

CREATE TABLE `ocenter_super_links` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '主键',
  `type` int(1) NOT NULL DEFAULT '1' COMMENT '类别（1：图片，2：普通）',
  `title` char(80) NOT NULL DEFAULT '' COMMENT '站点名称',
  `cover_id` int(10) NOT NULL COMMENT '图片ID',
  `link` char(140) NOT NULL DEFAULT '' COMMENT '链接地址',
  `level` int(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '优先级',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态（0：禁用，1：正常）',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '添加时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='友情连接表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_support`
--

CREATE TABLE `ocenter_support` (
  `id` int(11) NOT NULL,
  `appname` varchar(20) NOT NULL COMMENT '应用名',
  `row` int(11) NOT NULL COMMENT '应用标识',
  `uid` int(11) NOT NULL COMMENT '用户',
  `create_time` int(11) NOT NULL COMMENT '发布时间',
  `table` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支持的表';

--
-- 转存表中的数据 `ocenter_support`
--

INSERT INTO `ocenter_support` (`id`, `appname`, `row`, `uid`, `create_time`, `table`) VALUES
(1, 'Weibo', 1, 1, 1506494893, 'weibo'),
(2, 'Weibo', 2, 100, 1507776145, 'weibo'),
(3, 'Weibo', 1, 100, 1507776151, 'weibo'),
(4, 'Weibo', 2, 116, 1507777508, 'weibo'),
(5, 'Weibo', 2, 1, 1507780179, 'weibo');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_sync_login`
--

CREATE TABLE `ocenter_sync_login` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `type_uid` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `oauth_token` varchar(255) NOT NULL,
  `oauth_token_secret` varchar(255) NOT NULL,
  `is_sync` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_talk`
--

CREATE TABLE `ocenter_talk` (
  `id` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `uids` varchar(100) NOT NULL,
  `update_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `title` varchar(50) NOT NULL,
  `order_id` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会话表';

--
-- 转存表中的数据 `ocenter_talk`
--

INSERT INTO `ocenter_talk` (`id`, `create_time`, `uids`, `update_time`, `status`, `title`, `order_id`) VALUES
(1, 1507534966, '[1],[100]', 1507536526, 1, 'vbboy 和 yoyocoinsdd的聊天', '2017100954574853'),
(2, 1508031829, '[1],[100]', 1508031945, 1, 'vbboy 和 yoyocoinsdd的聊天', '2017101553975555');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_talk_message`
--

CREATE TABLE `ocenter_talk_message` (
  `id` int(11) NOT NULL,
  `content` varchar(500) NOT NULL,
  `uid` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `talk_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='聊天消息表';

--
-- 转存表中的数据 `ocenter_talk_message`
--

INSERT INTO `ocenter_talk_message` (`id`, `content`, `uid`, `create_time`, `talk_id`) VALUES
(1, '121', 100, 1507534970, 1),
(2, '我在', 1, 1507534992, 1),
(3, '你在哪个位置', 1, 1507535002, 1),
(4, 'OK', 100, 1507535010, 1),
(5, '好了', 100, 1507536526, 1),
(6, '111', 100, 1508031840, 2),
(7, '11', 1, 1508031921, 2),
(8, 'dasf', 1, 1508031939, 2),
(9, 'asd', 100, 1508031945, 2);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_talk_message_push`
--

CREATE TABLE `ocenter_talk_message_push` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `source_id` int(11) NOT NULL COMMENT '来源消息id',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` tinyint(4) NOT NULL,
  `talk_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='状态，0为未提示，1为未点击，-1为已点击';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_talk_push`
--

CREATE TABLE `ocenter_talk_push` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '接收推送的用户id',
  `source_id` int(11) NOT NULL COMMENT '来源id',
  `create_time` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '状态，0为未提示，1为未点击，-1为已点击'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='对话推送表';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_ticket`
--

CREATE TABLE `ocenter_ticket` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `type` varchar(10) DEFAULT NULL COMMENT '类型',
  `child_type` varchar(20) DEFAULT NULL COMMENT '子类型',
  `order_id` int(11) NOT NULL,
  `content` varchar(200) DEFAULT NULL COMMENT '内容',
  `images` varchar(200) NOT NULL COMMENT '图片',
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支持工单';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_tile`
--

CREATE TABLE `ocenter_tile` (
  `id` int(11) NOT NULL,
  `aid` int(11) NOT NULL COMMENT '父页面id',
  `icon` varchar(20) NOT NULL COMMENT '图标',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` int(11) NOT NULL,
  `title` varchar(50) NOT NULL COMMENT '标题',
  `title_vo` varchar(50) NOT NULL COMMENT '父页面标题',
  `url` varchar(255) NOT NULL COMMENT '路径',
  `url_vo` varchar(255) NOT NULL COMMENT '父页面路径',
  `tile_bg` varchar(10) NOT NULL COMMENT '块颜色(背景)'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='常用操作';

--
-- 转存表中的数据 `ocenter_tile`
--

INSERT INTO `ocenter_tile` (`id`, `aid`, `icon`, `sort`, `status`, `title`, `title_vo`, `url`, `url_vo`, `tile_bg`) VALUES
(1, 3, 'direction', 1, 1, '用户管理', '用户', 'User/index', 'User/index', '#1ba1e2');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_tradead`
--

CREATE TABLE `ocenter_tradead` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `type` tinyint(2) NOT NULL COMMENT '广告类型：1在线sell，2在线buy，3本地sell，4本地buy',
  `coin_type` tinyint(2) NOT NULL COMMENT '币种',
  `country` smallint(4) NOT NULL COMMENT '国家地区',
  `currency` varchar(5) NOT NULL COMMENT '货币类型',
  `market` char(20) DEFAULT NULL COMMENT '市场价格源',
  `formula` char(50) DEFAULT NULL COMMENT '计价公式',
  `price` decimal(10,2) NOT NULL COMMENT '价格',
  `pre_price` tinyint(2) NOT NULL COMMENT '溢价',
  `low_price` decimal(10,2) NOT NULL COMMENT '最低价',
  `max_price` int(11) DEFAULT NULL COMMENT 'max',
  `min_price` int(11) DEFAULT NULL COMMENT 'min',
  `pay_type` char(50) NOT NULL COMMENT '付款方式',
  `pay_time` int(11) DEFAULT NULL,
  `pay_addr` varchar(50) DEFAULT NULL COMMENT '见面地点',
  `pay_text` text COMMENT '交易条款',
  `auto_message` varchar(100) DEFAULT NULL COMMENT '自动回复消息',
  `is_safe` tinyint(2) NOT NULL DEFAULT '0' COMMENT '安全验证',
  `is_trust` tinyint(2) NOT NULL DEFAULT '0' COMMENT '信任验证',
  `is_price` tinyint(2) NOT NULL DEFAULT '0' COMMENT '固定价格',
  `start0` tinyint(1) DEFAULT NULL,
  `end0` tinyint(1) DEFAULT NULL,
  `start1` tinyint(1) DEFAULT NULL,
  `end1` tinyint(1) DEFAULT NULL,
  `start2` tinyint(1) DEFAULT NULL,
  `end2` tinyint(1) DEFAULT NULL,
  `start3` tinyint(1) DEFAULT NULL,
  `end3` tinyint(1) DEFAULT NULL,
  `start4` tinyint(1) DEFAULT NULL,
  `end4` tinyint(1) DEFAULT NULL,
  `start5` tinyint(1) DEFAULT NULL,
  `end5` tinyint(1) DEFAULT NULL,
  `start6` tinyint(1) DEFAULT NULL,
  `end6` tinyint(1) DEFAULT NULL,
  `status` tinyint(2) NOT NULL COMMENT '状态：1开放，0关闭',
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='交易广告表';

--
-- 转存表中的数据 `ocenter_tradead`
--

INSERT INTO `ocenter_tradead` (`id`, `uid`, `type`, `coin_type`, `country`, `currency`, `market`, `formula`, `price`, `pre_price`, `low_price`, `max_price`, `min_price`, `pay_type`, `pay_time`, `pay_addr`, `pay_text`, `auto_message`, `is_safe`, `is_trust`, `is_price`, `start0`, `end0`, `start1`, `end1`, `start2`, `end2`, `start3`, `end3`, `start4`, `end4`, `start5`, `end5`, `start6`, `end6`, `status`, `create_time`) VALUES
(4003, 146, 3, 1, 45, 'CNY', 'BitfinexUSD', 'BitfinexUSD*USD_in_CNY*1.05', '39338.13', 5, '0.00', 50000, 5000, '2,3', 180, 'sdfsdf', '请先确认您能够在半小时内完成现金付款再发出交易。\r\n一旦上线核实支付后会马上释放，所以请尽管安心下单', '', 0, 0, 0, -2, 25, -2, 25, -2, 25, -2, 25, -2, 25, -2, 25, -2, 25, 0, 1508486627),
(4004, 146, 4, 1, 45, 'CNY', 'BitfinexUSD', 'BitfinexUSD*USD_in_CNY*1.04', '38963.48', 4, '0.00', 50000, 5000, '2,4', 180, '123123', '请先确认您能够在半小时内完成现金付款再发出交易。\r\n一旦上线核实支付后会马上释放，所以请尽管安心下单', '', 0, 0, 0, -2, 25, -2, 25, -2, 25, -2, 25, -2, 25, -2, 25, -2, 25, 0, 1508487203),
(4005, 146, 1, 1, 45, 'CNY', 'BitfinexUSD', 'BitfinexUSD*USD_in_CNY*1.04', '38963.48', 4, '0.00', 50000, 5000, '6,8', 180, '卑微', '能看到此广告，就表示在线。\r<br>\r<br>3年老帐号，100%能信任。\r<br>\r<br>English OK! \r<br>中文 \r<br>', '', 0, 0, 0, -2, 25, -2, 25, -2, 25, -2, 25, -2, 25, -2, 25, -2, 25, 1, 1508487246),
(4006, 146, 2, 1, 45, 'CNY', 'BitfinexUSD', 'BitfinexUSD*USD_in_CNY*1.03', '38588.83', 3, '0.00', 100000, 6000, '3', 180, '', '请先确认您能够在半小时内完成现金付款再发出交易。\r\n一旦上线核实支付后会马上释放，所以请尽管安心下单并支付。', '', 0, 0, 0, -2, 25, -2, 25, -2, 25, -2, 25, -2, 25, -2, 25, -2, 25, 0, 1508488723);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_trade_order`
--

CREATE TABLE `ocenter_trade_order` (
  `id` int(11) NOT NULL,
  `ad_id` int(11) NOT NULL,
  `order_id` varchar(20) NOT NULL,
  `ad_uid` int(11) NOT NULL,
  `get_uid` int(11) NOT NULL,
  `type` tinyint(2) NOT NULL COMMENT '广告类型：1在线sell，2在线buy，3本地sell，4本地buy',
  `coin_type` tinyint(2) NOT NULL COMMENT '币种',
  `coin_num` decimal(10,8) NOT NULL COMMENT '交易数量',
  `price` decimal(10,4) NOT NULL COMMENT '交易价格',
  `fee` decimal(10,8) NOT NULL COMMENT '手续费',
  `currency` varchar(5) NOT NULL COMMENT '货币类型',
  `pay_text` varchar(50) DEFAULT NULL COMMENT '交易条款',
  `status` tinyint(2) NOT NULL COMMENT '状态：1等待付款，2付款完毕，3确认完成，4，申诉，0取消',
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='交易表';

--
-- 转存表中的数据 `ocenter_trade_order`
--

INSERT INTO `ocenter_trade_order` (`id`, `ad_id`, `order_id`, `ad_uid`, `get_uid`, `type`, `coin_type`, `coin_num`, `price`, `fee`, `currency`, `pay_text`, `status`, `create_time`) VALUES
(1, 4014, '2017100954574853', 1, 100, 4, 1, '0.44444000', '11111.0000', '0.00222220', 'CNY', 'sdfsadfasdsad', 1, 1507534966),
(2, 4013, '2017101553975555', 1, 100, 3, 2, '0.44444000', '11111.0000', '0.00222220', 'CNY', 'sdfsdf', 1, 1508031829);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_ucenter_admin`
--

CREATE TABLE `ocenter_ucenter_admin` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '管理员ID',
  `member_id` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '管理员用户ID',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '管理员状态'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_ucenter_member`
--

CREATE TABLE `ocenter_ucenter_member` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '用户ID',
  `username` char(32) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `safe_pw` char(32) NOT NULL,
  `email` char(32) NOT NULL COMMENT '用户邮箱',
  `mobile` char(15) NOT NULL COMMENT '用户手机',
  `reg_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '注册IP',
  `last_login_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后登录IP',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) DEFAULT '0' COMMENT '用户状态',
  `type` tinyint(4) NOT NULL COMMENT '1为用户名注册，2为邮箱注册，3为手机注册',
  `google_ver` char(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户表';

--
-- 转存表中的数据 `ocenter_ucenter_member`
--

INSERT INTO `ocenter_ucenter_member` (`id`, `username`, `password`, `safe_pw`, `email`, `mobile`, `reg_time`, `reg_ip`, `last_login_time`, `last_login_ip`, `update_time`, `status`, `type`, `google_ver`) VALUES
(1, 'yoyoadmin', '1ad5cc9a96fcb8a42881f0b875dbbe69', '', 'admin@admin.com', '', 1506049131, 2130706433, 1508576069, 2130706433, 1506049131, 1, 1, '0'),
(100, '', '1ad5cc9a96fcb8a42881f0b875dbbe69', 'qqwwee', 'vbboy2015@163.com', '18507358828', 1507711542, 2130706433, 1508037773, 2130706433, 1507711542, 1, 2, 'F2WXMDXO5NBMMHA7'),
(147, 'vbboy2015', '1ad5cc9a96fcb8a42881f0b875dbbe69', '', 'vbboy2012@163.com', '', 1508127984, 2130706433, 1508128099, 2130706433, 1508127984, 1, 1, NULL),
(146, 'sonia', '1ad5cc9a96fcb8a42881f0b875dbbe69', '', 'soni@vip.qq.com', '', 1508127913, 2130706433, 1508504956, 2130706433, 1508127913, 1, 1, NULL),
(145, 'bobo', '1ad5cc9a96fcb8a42881f0b875dbbe69', '', '362395084@qq.com', '', 1508127436, 2130706433, 1508127863, 2130706433, 1508127436, 3, 1, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_ucenter_score_type`
--

CREATE TABLE `ocenter_ucenter_score_type` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `unit` varchar(20) NOT NULL,
  `type_value` int(11) NOT NULL,
  `type_key` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_ucenter_score_type`
--

INSERT INTO `ocenter_ucenter_score_type` (`id`, `title`, `status`, `unit`, `type_value`, `type_key`) VALUES
(1, '积分', 1, '分', 0, 0),
(2, '威望', 1, '点', 0, 0),
(3, '贡献', 1, '元', 0, 0),
(4, '余额', 1, '点', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_ucenter_setting`
--

CREATE TABLE `ocenter_ucenter_setting` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '设置ID',
  `type` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '配置类型（1-用户配置）',
  `value` text NOT NULL COMMENT '配置数据'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='设置表';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_ucenter_short`
--

CREATE TABLE `ocenter_ucenter_short` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `short` varchar(25) NOT NULL COMMENT '个性域名',
  `can_change_num` int(11) NOT NULL COMMENT '可修改次数，备用字段，用户商业或任务获得',
  `status` tinyint(4) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户个性域名';

--
-- 转存表中的数据 `ocenter_ucenter_short`
--

INSERT INTO `ocenter_ucenter_short` (`id`, `uid`, `short`, `can_change_num`, `status`, `create_time`, `update_time`) VALUES
(1, 1, 'jzbk', 0, 1, 1506670437, 1506670437);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_ucenter_user_link`
--

CREATE TABLE `ocenter_ucenter_user_link` (
  `id` int(10) UNSIGNED NOT NULL,
  `uid` int(11) NOT NULL,
  `uc_uid` int(11) NOT NULL,
  `uc_username` varchar(50) NOT NULL,
  `uc_email` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_url`
--

CREATE TABLE `ocenter_url` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '链接唯一标识',
  `url` char(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `short` char(100) NOT NULL DEFAULT '' COMMENT '短网址',
  `status` tinyint(2) NOT NULL DEFAULT '2' COMMENT '状态',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='链接表';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_user_config`
--

CREATE TABLE `ocenter_user_config` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT '0',
  `model` varchar(30) NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户配置信息表';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_user_nav`
--

CREATE TABLE `ocenter_user_nav` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '频道ID',
  `title` char(30) NOT NULL COMMENT '频道标题',
  `url` char(100) NOT NULL COMMENT '频道连接',
  `sort` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '导航排序',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `target` tinyint(2) UNSIGNED NOT NULL DEFAULT '0' COMMENT '新窗口打开',
  `color` varchar(30) NOT NULL,
  `band_color` varchar(30) NOT NULL,
  `band_text` varchar(30) NOT NULL,
  `icon` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_user_nav`
--

INSERT INTO `ocenter_user_nav` (`id`, `title`, `url`, `sort`, `create_time`, `update_time`, `status`, `target`, `color`, `band_color`, `band_text`, `icon`) VALUES
(1, '我的广告', '/ucenter/myad', 0, 0, 0, 1, 0, '#000000', '#000000', '', '-'),
(2, '我的订单', '/ucenter/myorder', 1, 0, 0, 1, 0, '#000000', '#000000', '', '-'),
(3, '消息中心', 'ucenter/message/message', 2, 0, 0, 1, 0, '#000000', '#000000', '', '-'),
(4, '我的收藏', 'ucenter/Collection/index', 3, 0, 0, 1, 0, '#000000', '#000000', '', '-'),
(5, '申请认证', 'ucenter/attest/process/go_index/1', 4, 0, 0, 1, 0, '#000000', '#000000', '', '-');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_user_role`
--

CREATE TABLE `ocenter_user_role` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '2：未审核，1:启用，0：禁用，-1：删除',
  `step` varchar(50) NOT NULL COMMENT '记录当前执行步骤',
  `init` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否初始化'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户角色关联';

--
-- 转存表中的数据 `ocenter_user_role`
--

INSERT INTO `ocenter_user_role` (`id`, `uid`, `role_id`, `status`, `step`, `init`) VALUES
(1, 1, 1, 1, 'finish', 1),
(2, 100, 1, 1, 'finish', 1),
(3, 101, 1, 1, 'start', 1),
(4, 102, 1, 1, 'start', 1),
(5, 103, 1, 1, 'start', 1),
(6, 104, 1, 1, 'start', 1),
(7, 105, 1, 1, 'start', 1),
(8, 107, 1, 1, 'start', 1),
(9, 106, 1, 1, 'start', 1),
(10, 108, 1, 1, 'start', 1),
(11, 109, 1, 1, 'start', 1),
(12, 110, 1, 1, 'start', 1),
(13, 111, 1, 1, 'finish', 1),
(14, 112, 1, 1, 'finish', 1),
(15, 114, 1, 1, 'finish', 1),
(16, 113, 1, 1, 'start', 1),
(17, 115, 1, 1, 'finish', 1),
(18, 116, 1, 1, 'finish', 1),
(19, 117, 1, 1, 'finish', 1),
(20, 118, 0, 1, 'start', 1),
(21, 119, 0, 1, 'start', 1),
(22, 120, 0, 1, 'finish', 1),
(23, 121, 0, 1, 'start', 1),
(24, 122, 0, 1, 'finish', 1),
(25, 123, 0, 1, 'finish', 1),
(26, 124, 0, 1, 'start', 1),
(27, 125, 0, 1, 'start', 1),
(28, 126, 0, 1, 'start', 1),
(29, 127, 0, 1, 'start', 1),
(30, 128, 0, 1, 'start', 1),
(31, 129, 0, 1, 'start', 1),
(32, 130, 0, 1, 'start', 1),
(33, 131, 0, 1, 'start', 1),
(34, 132, 0, 1, 'start', 1),
(35, 133, 0, 1, 'start', 1),
(36, 134, 0, 1, 'start', 1),
(37, 135, 0, 1, 'start', 1),
(38, 136, 0, 1, 'start', 1),
(39, 137, 0, 1, 'start', 1),
(40, 138, 0, 1, 'start', 1),
(41, 139, 0, 1, 'start', 1),
(42, 140, 0, 1, 'start', 1),
(43, 141, 0, 1, 'start', 1),
(44, 142, 0, 1, 'start', 1),
(45, 143, 0, 1, 'start', 1),
(46, 144, 0, 1, 'start', 1),
(47, 145, 0, 1, 'start', 1),
(48, 146, 0, 1, 'finish', 1),
(49, 147, 0, 1, 'finish', 1);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_user_tag`
--

CREATE TABLE `ocenter_user_tag` (
  `id` int(11) NOT NULL,
  `title` varchar(25) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `pid` int(11) NOT NULL,
  `sort` tinyint(6) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='标签分类表';

--
-- 转存表中的数据 `ocenter_user_tag`
--

INSERT INTO `ocenter_user_tag` (`id`, `title`, `status`, `pid`, `sort`) VALUES
(1, '默认', 1, 0, 0),
(2, '开发者', 1, 1, 0),
(3, '站长', 1, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_user_tag_link`
--

CREATE TABLE `ocenter_user_tag_link` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `tags` varchar(200) NOT NULL COMMENT '标签ids'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户标签关联表';

--
-- 转存表中的数据 `ocenter_user_tag_link`
--

INSERT INTO `ocenter_user_tag_link` (`id`, `uid`, `tags`) VALUES
(1, 1, '[2],[3]');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_user_token`
--

CREATE TABLE `ocenter_user_token` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_user_token`
--

INSERT INTO `ocenter_user_token` (`id`, `uid`, `token`, `time`) VALUES
(4, 1, 'vmzQ2tEjBXplaNCVYnUwJL1gh7c36RWOoA5sGuZI', 1507346157);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_verify`
--

CREATE TABLE `ocenter_verify` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `account` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `verify` varchar(50) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_verify`
--

INSERT INTO `ocenter_verify` (`id`, `uid`, `account`, `type`, `verify`, `create_time`) VALUES
(79, 125, 'qweqwe@qq.com', 'email', '809714', 1508051492),
(80, 126, 'swr@11.com', 'email', '757243', 1508051643),
(81, 127, 'qweq@qq.com', 'email', '050154', 1508052720),
(82, 128, 'sdfsdf1@qq.com', 'email', '609547', 1508052832),
(83, 129, 'qw2e2qwe@qq.com', 'email', '014444', 1508053520),
(84, 130, 's123oni@vip.qq.com', 'email', '714193', 1508053655),
(85, 131, 'sdfsdfw@qq.com', 'email', '056206', 1508053960),
(86, 132, 'dfgwer@qq.com', 'email', '457517', 1508054061),
(87, 133, 'sdfsdfwe@qq.com', 'email', '495363', 1508054238),
(88, 134, 'dsfsdfw23@qq.com', 'email', '344580', 1508054439),
(89, 135, 'sdgf23w54@qq.com', 'email', '509614', 1508054668),
(91, 137, 's22oni@vip.qq.com', 'email', '039621', 1508119655),
(92, 138, 'so123ni@vip.qq.com', 'email', '580562', 1508119952),
(93, 139, 'so22ni@vip.qq.com', 'email', '917577', 1508120621),
(94, 140, 'soni211@vip.qq.com', 'email', '214550', 1508121027),
(95, 141, 'admin@qq.com', 'email', '734962', 1508122971),
(96, 142, 'so12ni@vip.qq.com', 'email', '792821', 1508124662),
(97, 143, '12soni@vip.qq.com', 'email', '654040', 1508124873),
(98, 144, 'son2i@vip.qq.com', 'email', '754465', 1508127248),
(104, 145, '362395084@qq.com', 'email', '285774', 1508127873);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_version`
--

CREATE TABLE `ocenter_version` (
  `title` varchar(50) NOT NULL COMMENT '版本名',
  `create_time` int(11) NOT NULL COMMENT '发布时间',
  `update_time` int(11) NOT NULL COMMENT '更新的时间',
  `log` text NOT NULL COMMENT '更新日志',
  `url` varchar(150) NOT NULL COMMENT '链接到的远程文章',
  `number` int(11) NOT NULL COMMENT '序列号，一般用日期数字标示',
  `name` varchar(50) NOT NULL COMMENT '版本号',
  `is_current` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='自动更新表';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_weibo`
--

CREATE TABLE `ocenter_weibo` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `content` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `comment_count` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `is_top` tinyint(4) NOT NULL,
  `type` varchar(255) NOT NULL,
  `data` text NOT NULL,
  `repost_count` int(11) NOT NULL,
  `from` varchar(40) NOT NULL,
  `crowd_id` int(11) NOT NULL COMMENT '圈子类型',
  `pos` varchar(20) NOT NULL,
  `reply_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后一次评论时间',
  `is_crowd_top` tinyint(4) NOT NULL,
  `geolocation_id` int(11) NOT NULL COMMENT '地理信息id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `ocenter_weibo`
--

INSERT INTO `ocenter_weibo` (`id`, `uid`, `content`, `create_time`, `comment_count`, `status`, `is_top`, `type`, `data`, `repost_count`, `from`, `crowd_id`, `pos`, `reply_time`, `is_crowd_top`, `geolocation_id`) VALUES
(1, 1, '/br \n/br \n美国证券交易委员会（SEC）今天发布了一项有关新建专门的“网络单位（Cyber/nbUnit）”的新闻公告。值得注意的是，这一新机构将执行一些对象为数字货币的监管。/br \n/br \n广阔的监管范围/br \n/br \nSEC指出：/br \n/br \n“网络单位”将聚焦网络违规操作，如：/br \n/br \n重磅：SEC宣布创建新的机构/nb对ICO和分布式账本违规进行监管/br \n/br \n如果上述行为全部被纳入这个新的“网络', 1506494855, 0, 1, 0, 'long_weibo', '', 0, '', 0, '', 0, 0, 0),
(2, 100, '1、登陆https://ebtcbank.com/，未注册用户请点击蓝色注册按钮。/br \n/br \n/br \n2、按要求填写注册信息，设置交易密码，进行实名认证，注册成功即可点击去交易。/br \n/br \n/br \n/br \n/br \n3、在财务中心，点击转入虚拟币，下拉菜单选择并点击钛值（TV）。/br \n/br \n/br \n4、出现钛值（TV）地址，进行复制。/br \n/br \n5、登陆www.btc123.com,在资产管理中选择TV资产，点击提现TV。/br \n/br \n/br \n6、发送至', 1506498138, 0, 1, 0, 'long_weibo', '', 0, '', 0, '', 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_weibo_cache`
--

CREATE TABLE `ocenter_weibo_cache` (
  `id` int(11) NOT NULL,
  `weibo_id` int(11) NOT NULL,
  `groups` varchar(100) NOT NULL,
  `cache_html` text NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微博html缓存表';

--
-- 转存表中的数据 `ocenter_weibo_cache`
--

INSERT INTO `ocenter_weibo_cache` (`id`, `weibo_id`, `groups`, `cache_html`, `create_time`) VALUES
(75, 2, '1', '<div style=\"max-width: 680px;\" data-role=\"id_weibo\" id=\"weibo_2\" class=\"\">\n    <div class=\"all-wrap\">\n                    <div class=\"new-user-first-weibo\"></div>                <div class=\"weibo-content\">\n            <div class=\"content-head\">\n                <div class=\"avat-box pull-left\">\n                    <a href=\"/yoyo/index.php?s=/ucenter/index/index/uid/100.html\" ucard=\"100\">\n                        <span style=\"position: relative;display: inline-block;\">\n                                    <img src=\"/yoyo/Uploads/Avatar/100/59e2d49d800f3_128_128.jpg\" class=\"avatar-img\">\n                                  </span>                    </a>\n                    <div class=\"show-follow pull-right\">\n                        <div class=\"follow-btn\" style=\"display: none;\">\n                            [follow:100]\n                        </div>\n                    </div>\n                </div>\n                <div class=\"op-box pull-right\">\n                    <div class=\"op-tb op-top\">\n                        <a ucard=\"100\" href=\"/yoyo/index.php?s=/ucenter/index/index/uid/100.html\" class=\"user_name\">\n                            [nickname:100]\n                        </a>\n                        <small class=\"font_grey\">Lv1 实习</small>                        &nbsp;\n                        <!--隐藏操作列表-->\n                        <div class=\"pull-right show-operate-wrap\">\n                            <a href=\"javascript:\" class=\"show-operate pull-right icon-angle-down\"></a>\n                            <div class=\"operate-box\" >\n                                <li data-weibo-id=\"2\" title=\"设置置顶\" data-role=\"weibo_set_top\">\n                                            置顶\n                                        </li>\n                                                                                                        <li data-weibo-id=\"2\" title=\"删除\" data-role=\"del_weibo\">\n                                        删除\n                                    </li>                                                                <li><a data-type=\"ajax\" data-url=\"/yoyo/index.php?s=/home/addons/_addons/report/_controller/report/_action/eject/param/type%3D%25E5%258A%25A8%25E6%2580%2581Pro%252F%25E5%258A%25A8%25E6%2580%2581Pro%26url%3DWeibo%252FIndex%252FweiboDetail%253Fid%253D2%26data%255Bweibo-id%255D%3D2.html\" data-title=\"举报\"  data-toggle=\"modal\" >举报</a></li>\n                            </div>\n                        </div>\n                    </div>\n                    <div class=\"op-tb op-bottom\">\n                        <a data-hover=\"查看详情\" class=\"wb-time\" href=\"/yoyo/index.php?s=/weibo/index/weibodetail/id/2.html\">\n                            [time:1506498138]\n                                                    </a>\n                    </div>\n                </div>\n            </div>\n            <div class=\"content-info row\">\n                <div class=\"long-weibo-list-content\">\n    <a class=\"article-link\" href=\"/yoyo/index.php?s=/weibo/index/weibodetail/id/2.html\">\n        <div class=\"long-block\">\n            <h1 style=\"width: 100%;\" class=\"text-more\">123教程| 如何从btc123提TV到ebtcbank</h1>\n            <p class=\"word-wrap\">\n                1、登陆https://ebtcbank.com/，未注册用户请点击蓝色注册按钮。<br/> \n<br/> \n<br/> \n2、按要求填写注册信息，设置交易密码，进行实名认证，注册成功即可点击去交易。<br/> \n<br/> \n<br/> \n<br/> \n<br/> \n3、在财务中心，点击转入虚拟币，下拉菜单选择并点击钛值（TV）。<br/> \n<br/> \n<br/> \n4、出现钛值（TV）地址，进行复制。<br/> \n<br/> \n5、登陆www.btc123.com,在资产管理中选择TV资产，点击提现TV。<br/> \n<br/> \n<br/> \n6、发送至            </p>\n            <div class=\"position-block\"><div class=\"read-long-weibo-logo\"><i class=\"logo\"></i></div>阅读全文</div>\n        </div>\n    </a>\n</div>                 <div class=\"form-where\">\n                    <div class=\"where w-left\">\n                        <!--<span>来自 <b> 全站动态</b></span>-->\n                                                    <span></span>\n                    </div>\n                    <div class=\"where w-right  bottom-operate\" data-weibo-id=\"2\">\n                                                <div class=\"col-xs-3 operate-color\">\n     <a title=\"喜欢\"\n     class=\"support_btn\" table=\"weibo\" row=\"2\" uid=\"100\" jump=\"weibo/index/weibodetail\">\n\n    <i id=\"ico_like\" class=\"icon-heart\"></i>\n        \n\n    <span id=\"support_Weibo_weibo_2_pos\"><span id=\"support_Weibo_weibo_2\">3</span> </span>\n</a>\n<script>\n    bind_support();\n</script></div>\n<div class=\" col-xs-3 operate-color\" data-role=\"weibo_comment_btn\"  data-weibo-id=\"2\">\n   <i class=\"os-icon-bubbles\"></i> 0</div>\n<div class=\"col-xs-3 operate-color\">\n        <a title=\"转发\"  data-role=\"send_repost\"  href=\"/yoyo/index.php?s=/weibo/index/sendrepost/sourceId/2/weiboId/2.html\"><i class=\"os-icon-share-alt\"></i> 0</a>\n</div>\n<div class=\"share_button col-xs-3  operate-color\" style=\"padding: 0px;position: relative;\">\n    <span class=\"cpointer weibo_share_btn_2\" data-weibo-id=\"2\">\n        <a data-role=\"weibo_share_btn\" class=\"share-btn\" title=\"分享\"><i class=\"os-icon-share\"></i><!--<span class=\"share_count\" title=\"累计分享0次\" style=\"margin-left: 5px;\">0</span>--></a>\n    </span>\n    <div class=\"share_block\" data-url=\"http://127.0.0.1/yoyo/index.php?s=/weibo/index/weibodetail/id/2.html\" data-text=\"1、登陆https://ebtcbank.com/，未注册用户请点击蓝色注册按钮。 \n \n \n2、按要求填写注册信息，设置交易密码，进行实名认证，注册成功即可点击去交易。 \n \n \n \n \n3、在财务中心，点击转入虚拟币，下拉菜单选择并点击钛值（TV）。 \n \n \n4、出现钛值（TV）地址，进行复制。 \n \n5、登陆www.btc123.com,在资产管理中选择TV资产，点击提现TV。 \n \n \n6、发送至\" data-dec=\"分享微博\" style=\"display: none;\">\n        <div class=\"bdsharebuttonbox\" data-tag=\"share_feedlist\">\n            <a class=\"bds_qzone\" data-cmd=\"qzone\" title=\"分享到QQ空间\" data-id=\"2\">QQ空间</a>\n            <a class=\"bds_tsina\" data-cmd=\"tsina\" title=\"分享到新浪微博\" data-id=\"2\">新浪微博</a>\n            <a class=\"bds_tqq\" data-cmd=\"tqq\" title=\"分享到腾讯微博\" data-id=\"2\">腾讯微博</a>\n            <a class=\"bds_weixin\" data-cmd=\"weixin\" title=\"分享到微信\" data-id=\"2\">微信</a>\n            <a class=\"bds_sqq\" data-cmd=\"sqq\" title=\"分享到QQ好友\" data-id=\"2\">QQ好友</a>\n            <!--<a class=\"bds_count\" data-cmd=\"count\" style=\"display: none;\"></a>-->\n        </div>\n        <div style=\"position: relative;\">\n            <div class=\"tip\"></div>\n            <div class=\"tip-xs\"></div>\n        </div>\n    </div>\n    <script src=\"/yoyo/Application/Weibo/Static/js/bdshare.js\"></script>\n</div>\n                    </div>\n                </div>\n            </div>\n        </div>\n\n        </div>\n    <div class=\"weibo-comment-list row\" style=\"display: block;margin:0;\"  data-weibo-id=\"2\">\n    <div class=\" weibo-comment-block\">\n            <div class=\"weibo-comment-container\">\n                <div class=\"weibo_post_box\">\n            <p class=\"comment-area\">\n                <input type=\"hidden\"  name=\"reply_id\" value=\"0\"/>\n\n                <input type=\"text\" placeholder=\"评论（Ctrl+Enter）\" id=\"text_2\" rows=\"2\" data-weibo-id=\"2\"\n                       class=\"comment-input  weibo-comment-content comment_text_inputor\">\n                <a style=\"margin-right: 10px\"  class=\"\"                  onclick=\"insertFace($(this))\"><i style=\"color: #999;\" class=\"os-icon-emoticon-smile\"></i> </a>\n                <a  data-role=\"do_comment\" id=\"btn_2\" data-weibo-id=\"2\">\n                    <i class=\"os-icon-paper-plane\"></i> </a>\n            </p>\n\n    <div id=\"emot_content\" class=\"emot_content\" style=\"position: absolute;    right: 425px;\n    top: 45px;\"></div>\n    <!--评论列表-->\n</div>\n<div id=\"show_comment_2\" class=\"weibo_comment_list\" data-comment-count=\"0\">\n    <div class=\"pager\" style=\"display: none!important;\">\n    </div>\n</div>\n\n\n<script>\n    $(function () {\n        var weiboid = \'2\';\n        $(\'#text_\' + weiboid + \'\').keypress(function (e) {\n            if (e.ctrlKey && e.which == 13 || e.which == 10) {\n                $(\'#btn_\' + weiboid + \'\').click();\n            }\n        });\n    });\n</script>            </div>\n        </div>    </div>\n    </div>\n</if>\n<style>\n    .suofang {MARGIN: auto;WIDTH: 200px;}\n    .suofang img{MAX-WIDTH: 100%!important;HEIGHT: auto!important;width:expression(this.width > 300 ? \"300px\" :this.width)!important;}\n</style>', 1508552980),
(76, 1, '1', '<div style=\"max-width: 680px;\" data-role=\"id_weibo\" id=\"weibo_1\" class=\"\">\n    <div class=\"all-wrap\">\n                    <div class=\"new-user-first-weibo\"></div>                <div class=\"weibo-content\">\n            <div class=\"content-head\">\n                <div class=\"avat-box pull-left\">\n                    <a href=\"http://127.0.0.1/yoyo/u/jzbk\" ucard=\"1\">\n                        <span style=\"position: relative;display: inline-block;\">\n                                    <img src=\"/yoyo/Uploads/Avatar/1/59c9fe662f4d9_128_128.jpg\" class=\"avatar-img\">\n                                  </span>                    </a>\n                    <div class=\"show-follow pull-right\">\n                        <div class=\"follow-btn\" style=\"display: none;\">\n                            [follow:1]\n                        </div>\n                    </div>\n                </div>\n                <div class=\"op-box pull-right\">\n                    <div class=\"op-tb op-top\">\n                        <a ucard=\"1\" href=\"http://127.0.0.1/yoyo/u/jzbk\" class=\"user_name\">\n                            [nickname:1]\n                        </a>\n                        <small class=\"font_grey\">Lv1 实习</small>                        &nbsp;\n                        <!--隐藏操作列表-->\n                        <div class=\"pull-right show-operate-wrap\">\n                            <a href=\"javascript:\" class=\"show-operate pull-right icon-angle-down\"></a>\n                            <div class=\"operate-box\" >\n                                <li data-weibo-id=\"1\" title=\"设置置顶\" data-role=\"weibo_set_top\">\n                                            置顶\n                                        </li>\n                                                                                                        <li data-weibo-id=\"1\" title=\"删除\" data-role=\"del_weibo\">\n                                        删除\n                                    </li>                                                                <li><a data-type=\"ajax\" data-url=\"/yoyo/index.php?s=/home/addons/_addons/report/_controller/report/_action/eject/param/type%3D%25E5%258A%25A8%25E6%2580%2581Pro%252F%25E5%258A%25A8%25E6%2580%2581Pro%26url%3DWeibo%252FIndex%252FweiboDetail%253Fid%253D1%26data%255Bweibo-id%255D%3D1.html\" data-title=\"举报\"  data-toggle=\"modal\" >举报</a></li>\n                            </div>\n                        </div>\n                    </div>\n                    <div class=\"op-tb op-bottom\">\n                        <a data-hover=\"查看详情\" class=\"wb-time\" href=\"/yoyo/index.php?s=/weibo/index/weibodetail/id/1.html\">\n                            [time:1506494855]\n                                                    </a>\n                    </div>\n                </div>\n            </div>\n            <div class=\"content-info row\">\n                <div class=\"long-weibo-list-content\">\n    <a class=\"article-link\" href=\"/yoyo/index.php?s=/weibo/index/weibodetail/id/1.html\">\n        <div class=\"long-block\">\n            <h1 style=\"width: 100%;\" class=\"text-more\">SEC创建新的机构对ICO违规进行监管</h1>\n            <p class=\"word-wrap\">\n                <br/> \n<br/> \n美国证券交易委员会（SEC）今天发布了一项有关新建专门的“网络单位（Cyber&nbsp;Unit）”的新闻公告。值得注意的是，这一新机构将执行一些对象为数字货币的监管。<br/> \n<br/> \n广阔的监管范围<br/> \n<br/> \nSEC指出：<br/> \n<br/> \n“网络单位”将聚焦网络违规操作，如：<br/> \n<br/> \n重磅：SEC宣布创建新的机构&nbsp;对ICO和分布式账本违规进行监管<br/> \n<br/> \n如果上述行为全部被纳入这个新的“网络            </p>\n            <div class=\"position-block\"><div class=\"read-long-weibo-logo\"><i class=\"logo\"></i></div>阅读全文</div>\n        </div>\n    </a>\n</div>                 <div class=\"form-where\">\n                    <div class=\"where w-left\">\n                        <!--<span>来自 <b> 全站动态</b></span>-->\n                                                    <span></span>\n                    </div>\n                    <div class=\"where w-right  bottom-operate\" data-weibo-id=\"1\">\n                                                <div class=\"col-xs-3 operate-color\">\n     <a title=\"喜欢\"\n     class=\"support_btn\" table=\"weibo\" row=\"1\" uid=\"1\" jump=\"weibo/index/weibodetail\">\n\n    <i id=\"ico_like\" class=\"icon-heart\"></i>\n        \n\n    <span id=\"support_Weibo_weibo_1_pos\"><span id=\"support_Weibo_weibo_1\">2</span> </span>\n</a>\n<script>\n    bind_support();\n</script></div>\n<div class=\" col-xs-3 operate-color\" data-role=\"weibo_comment_btn\"  data-weibo-id=\"1\">\n   <i class=\"os-icon-bubbles\"></i> 0</div>\n<div class=\"col-xs-3 operate-color\">\n        <a title=\"转发\"  data-role=\"send_repost\"  href=\"/yoyo/index.php?s=/weibo/index/sendrepost/sourceId/1/weiboId/1.html\"><i class=\"os-icon-share-alt\"></i> 0</a>\n</div>\n<div class=\"share_button col-xs-3  operate-color\" style=\"padding: 0px;position: relative;\">\n    <span class=\"cpointer weibo_share_btn_1\" data-weibo-id=\"1\">\n        <a data-role=\"weibo_share_btn\" class=\"share-btn\" title=\"分享\"><i class=\"os-icon-share\"></i><!--<span class=\"share_count\" title=\"累计分享0次\" style=\"margin-left: 5px;\">0</span>--></a>\n    </span>\n    <div class=\"share_block\" data-url=\"http://127.0.0.1/yoyo/index.php?s=/weibo/index/weibodetail/id/1.html\" data-text=\"美国证券交易委员会（SEC）今天发布了一项有关新建专门的“网络单位（Cyber&nbsp;Unit）”的新闻公告。值得注意的是，这一新机构将执行一些对象为数字货币的监管。 \n \n广阔的监管范围 \n \nSEC指出： \n \n“网络单位”将聚焦网络违规操作，如： \n \n重磅：SEC宣布创建新的机构&nbsp;对ICO和分布式账本违规进行监管 \n \n如果上述行为全部被纳入这个新的“网络\" data-dec=\"分享微博\" style=\"display: none;\">\n        <div class=\"bdsharebuttonbox\" data-tag=\"share_feedlist\">\n            <a class=\"bds_qzone\" data-cmd=\"qzone\" title=\"分享到QQ空间\" data-id=\"1\">QQ空间</a>\n            <a class=\"bds_tsina\" data-cmd=\"tsina\" title=\"分享到新浪微博\" data-id=\"1\">新浪微博</a>\n            <a class=\"bds_tqq\" data-cmd=\"tqq\" title=\"分享到腾讯微博\" data-id=\"1\">腾讯微博</a>\n            <a class=\"bds_weixin\" data-cmd=\"weixin\" title=\"分享到微信\" data-id=\"1\">微信</a>\n            <a class=\"bds_sqq\" data-cmd=\"sqq\" title=\"分享到QQ好友\" data-id=\"1\">QQ好友</a>\n            <!--<a class=\"bds_count\" data-cmd=\"count\" style=\"display: none;\"></a>-->\n        </div>\n        <div style=\"position: relative;\">\n            <div class=\"tip\"></div>\n            <div class=\"tip-xs\"></div>\n        </div>\n    </div>\n    <script src=\"/yoyo/Application/Weibo/Static/js/bdshare.js\"></script>\n</div>\n                    </div>\n                </div>\n            </div>\n        </div>\n\n        </div>\n    <div class=\"weibo-comment-list row\" style=\"display: block;margin:0;\"  data-weibo-id=\"1\">\n    <div class=\" weibo-comment-block\">\n            <div class=\"weibo-comment-container\">\n                <div class=\"weibo_post_box\">\n            <p class=\"comment-area\">\n                <input type=\"hidden\"  name=\"reply_id\" value=\"0\"/>\n\n                <input type=\"text\" placeholder=\"评论（Ctrl+Enter）\" id=\"text_1\" rows=\"2\" data-weibo-id=\"1\"\n                       class=\"comment-input  weibo-comment-content comment_text_inputor\">\n                <a style=\"margin-right: 10px\"  class=\"\"                  onclick=\"insertFace($(this))\"><i style=\"color: #999;\" class=\"os-icon-emoticon-smile\"></i> </a>\n                <a  data-role=\"do_comment\" id=\"btn_1\" data-weibo-id=\"1\">\n                    <i class=\"os-icon-paper-plane\"></i> </a>\n            </p>\n\n    <div id=\"emot_content\" class=\"emot_content\" style=\"position: absolute;    right: 425px;\n    top: 45px;\"></div>\n    <!--评论列表-->\n</div>\n<div id=\"show_comment_1\" class=\"weibo_comment_list\" data-comment-count=\"0\">\n    <div class=\"pager\" style=\"display: none!important;\">\n    </div>\n</div>\n\n\n<script>\n    $(function () {\n        var weiboid = \'1\';\n        $(\'#text_\' + weiboid + \'\').keypress(function (e) {\n            if (e.ctrlKey && e.which == 13 || e.which == 10) {\n                $(\'#btn_\' + weiboid + \'\').click();\n            }\n        });\n    });\n</script>            </div>\n        </div>    </div>\n    </div>\n</if>\n<style>\n    .suofang {MARGIN: auto;WIDTH: 200px;}\n    .suofang img{MAX-WIDTH: 100%!important;HEIGHT: auto!important;width:expression(this.width > 300 ? \"300px\" :this.width)!important;}\n</style>', 1508552980);

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_weibo_comment`
--

CREATE TABLE `ocenter_weibo_comment` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `weibo_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `to_comment_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_weibo_crowd`
--

CREATE TABLE `ocenter_weibo_crowd` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `title` text NOT NULL,
  `create_time` int(11) NOT NULL,
  `post_count` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `allow_user_post` int(11) NOT NULL,
  `logo` int(11) NOT NULL,
  `background` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `intro` text NOT NULL,
  `notice` text NOT NULL COMMENT '圈子公告',
  `type` tinyint(4) NOT NULL COMMENT '圈子类型，0为公共的，1为私有的',
  `need_pay` float NOT NULL DEFAULT '0' COMMENT '私有圈子是否付费入圈',
  `pay_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '支付类型',
  `activity` int(11) NOT NULL,
  `member_count` int(11) NOT NULL,
  `member_alias` varchar(10) NOT NULL,
  `order_type` int(11) NOT NULL COMMENT '圈内动态排序方式 0:最新发表  1:最新回复',
  `invisible` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否私密（1:私密，0:公开）'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_weibo_crowd_member`
--

CREATE TABLE `ocenter_weibo_crowd_member` (
  `id` int(11) NOT NULL,
  `crowd_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL,
  `activity` int(11) NOT NULL,
  `last_view` int(11) NOT NULL,
  `position` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1为普通成员，2为管理员，3为创建者',
  `contribution` int(11) NOT NULL COMMENT '贡献'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_weibo_crowd_score`
--

CREATE TABLE `ocenter_weibo_crowd_score` (
  `crowd_id` int(11) NOT NULL COMMENT '圈子id',
  `score1` float NOT NULL DEFAULT '0' COMMENT '代码量',
  `score2` float NOT NULL DEFAULT '0' COMMENT '威望',
  `score3` float NOT NULL DEFAULT '0' COMMENT '贡献',
  `score4` float NOT NULL DEFAULT '0' COMMENT '人民币',
  `score6` float NOT NULL DEFAULT '0' COMMENT '云市场代金券',
  `score8` float NOT NULL DEFAULT '0' COMMENT '搬砖'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='圈子账户' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_weibo_crowd_type`
--

CREATE TABLE `ocenter_weibo_crowd_type` (
  `id` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `status` tinyint(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='圈子的分类表' ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_weibo_long`
--

CREATE TABLE `ocenter_weibo_long` (
  `id` int(11) NOT NULL,
  `weibo_id` int(11) NOT NULL,
  `long_content` text NOT NULL COMMENT '长微博内容',
  `title` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='长微博内容' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `ocenter_weibo_long`
--

INSERT INTO `ocenter_weibo_long` (`id`, `weibo_id`, `long_content`, `title`) VALUES
(1, 1, '<p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\"><img src=\"http://www.btc38.com/ueditor/php/upload/image/20170927/1506481571382849.png\" title=\"1506481571382849.png\" alt=\"SEC_看图王.png\" style=\"border: none; max-width: 850px;\"/></p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\"><br/></p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">美国证券交易委员会（SEC）今天发布了一项有关新建专门的“网络单位（Cyber Unit）”的新闻公告。值得注意的是，这一新机构将执行一些对象为数字货币的监管。</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">&nbsp;</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\"><span style=\"font-size: 18px; color: rgb(54, 96, 146);\"><strong style=\"font-weight: bold;\">广阔的监管范围</strong></span></p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">&nbsp;</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">SEC指出：</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">&nbsp;</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">“网络单位”将聚焦网络违规操作，如：</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">&nbsp;</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">重磅：SEC宣布创建新的机构 对ICO和分布式账本违规进行监管</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">&nbsp;</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">如果上述行为全部被纳入这个新的“网络单位”的监管范围，那它无疑将变得非常重要。</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">&nbsp;</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">第三个要点和加密货币市场的关联性最大。SEC官方正在对ICO和“分布式账本技术”行业进行管制。</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">&nbsp;</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">单把“分布式账本技术”的执法权力给自己似乎范围过大。根据SEC（或者和法院）定义这个术语的方式，其可能有广泛的调查加密货币用户的权利。</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">&nbsp;</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\"><span style=\"font-size: 18px; color: rgb(54, 96, 146);\"><strong style=\"font-weight: bold;\">ICO被纳入监管并不令人意外</strong></span></p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">&nbsp;</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">在今年夏季将以太坊的“The DAO”项目判定为一种证券发行后，我们并不意外SEC会采用这样一个相当主动的方式来监管ICO。</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">&nbsp;</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">ICO之潮是史无前例的，仅在2017年就筹集了15亿美元。而许多的ICO项目要么完全是诈骗，要么是一群未来企业家脑中的一些模糊的概念。</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">&nbsp;</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">有网友在红迪网的/r/BitcoinMarkets上写道， SEC明显在计划对ICO采取强硬措施，对此我表示高兴。</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">&nbsp;</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\"><span style=\"font-size: 18px; color: rgb(54, 96, 146);\"><strong style=\"font-weight: bold;\">其他领域</strong></span></p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">&nbsp;</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">第1个要点“通过网络和社交媒体传播虚假信息的市场操控行为”也相当值得关注，SEC将会对通过“网络和社交媒体”进行的哄抬股价 高时卖出市场操控手段进行处理。此外，对于那些在暗网中干着非法勾当的数字货币用户，要点4应该是给你们的。</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">&nbsp;</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\"><span style=\"font-size: 18px; color: rgb(54, 96, 146);\"><strong style=\"font-weight: bold;\">平衡方式</strong></span></p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">&nbsp;</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">总而言之，SEC似乎对数字货币采取了一个平衡的方式。鉴于许多ICO承诺的代币听起来十分像“股份”，我们一点也不意外，SEC会对这一领域可能的证券违规感兴趣。</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">&nbsp;</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">认真的监管将使散户投资者免于被割的风险，同时又不完全扼杀这个刚长毛（羽翼未丰）的ICO市场。同样地，对于“分布式账本技术”违规的监管可能会给SEC带来更多的权利以追踪和指控像Josh Garza这样的诈骗者。</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">&nbsp;</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">文章来源：bitcoin86.com</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">编译：Chase</p><p style=\"margin: 0px; padding: 0px; cursor: default; color: rgb(51, 51, 51); font-family: &quot;Microsoft YaHei&quot;, Arial, Verdana, sans-serif, &quot;Segoe UI&quot;, Tahoma; font-size: 15px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: start; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px;\">免责声明：本文为转载，仅为传播消息之用，不代表比特时代观点，不构成比特时代投资建议！</p><p><br/></p>', 'SEC创建新的机构对ICO违规进行监管'),
(2, 2, '<p style=\"margin: 0px 0px 20px; padding: 0px; box-sizing: content-box; word-break: normal; color: rgb(92, 95, 97); font-family: &quot;Microsoft YaHei&quot;, 微软雅黑, SimSun, 宋体; font-size: 18px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);\">1、登陆https://ebtcbank.com/，未注册用户请点击蓝色注册按钮。</p><p style=\"margin: 0px 0px 20px; padding: 0px; box-sizing: content-box; word-break: normal; color: rgb(92, 95, 97); font-family: &quot;Microsoft YaHei&quot;, 微软雅黑, SimSun, 宋体; font-size: 18px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);\"><br style=\"margin: 0px; padding: 0px; box-sizing: content-box;\"/></p><p style=\"margin: 0px 0px 20px; padding: 0px; box-sizing: content-box; word-break: normal; color: rgb(92, 95, 97); font-family: &quot;Microsoft YaHei&quot;, 微软雅黑, SimSun, 宋体; font-size: 18px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);\"><img src=\"https://img1.btc123.com/up/29/313A313A3239_7222.png\" alt=\"\" style=\"margin: 0px auto 20px; padding: 0px; box-sizing: content-box; border: 0px; vertical-align: top; display: block; max-width: 780px; height: auto; text-align: center;\"/><br style=\"margin: 0px; padding: 0px; box-sizing: content-box;\"/></p><p style=\"margin: 0px 0px 20px; padding: 0px; box-sizing: content-box; word-break: normal; color: rgb(92, 95, 97); font-family: &quot;Microsoft YaHei&quot;, 微软雅黑, SimSun, 宋体; font-size: 18px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);\">2、按要求填写注册信息，设置交易密码，进行实名认证，注册成功即可点击去交易。</p><p style=\"margin: 0px 0px 20px; padding: 0px; box-sizing: content-box; word-break: normal; color: rgb(92, 95, 97); font-family: &quot;Microsoft YaHei&quot;, 微软雅黑, SimSun, 宋体; font-size: 18px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);\"><br style=\"margin: 0px; padding: 0px; box-sizing: content-box;\"/></p><p style=\"margin: 0px 0px 20px; padding: 0px; box-sizing: content-box; word-break: normal; color: rgb(92, 95, 97); font-family: &quot;Microsoft YaHei&quot;, 微软雅黑, SimSun, 宋体; font-size: 18px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);\"><img src=\"https://img1.btc123.com/up/29/313A313A3239_7223.png\" alt=\"\" style=\"margin: 0px auto 20px; padding: 0px; box-sizing: content-box; border: 0px; vertical-align: top; display: block; max-width: 780px; height: auto; text-align: center;\"/><br style=\"margin: 0px; padding: 0px; box-sizing: content-box;\"/></p><p style=\"margin: 0px 0px 20px; padding: 0px; box-sizing: content-box; word-break: normal; color: rgb(92, 95, 97); font-family: &quot;Microsoft YaHei&quot;, 微软雅黑, SimSun, 宋体; font-size: 18px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);\"><img src=\"https://img1.btc123.com/up/29/313A313A3239_7224.png\" alt=\"\" style=\"margin: 0px auto 20px; padding: 0px; box-sizing: content-box; border: 0px; vertical-align: top; display: block; max-width: 780px; height: auto; text-align: center;\"/><br style=\"margin: 0px; padding: 0px; box-sizing: content-box;\"/></p><p style=\"margin: 0px 0px 20px; padding: 0px; box-sizing: content-box; word-break: normal; color: rgb(92, 95, 97); font-family: &quot;Microsoft YaHei&quot;, 微软雅黑, SimSun, 宋体; font-size: 18px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);\"><img src=\"https://img1.btc123.com/up/29/313A313A3239_7225.png\" alt=\"\" style=\"margin: 0px auto 20px; padding: 0px; box-sizing: content-box; border: 0px; vertical-align: top; display: block; max-width: 780px; height: auto; text-align: center;\"/><br style=\"margin: 0px; padding: 0px; box-sizing: content-box;\"/></p><p style=\"margin: 0px 0px 20px; padding: 0px; box-sizing: content-box; word-break: normal; color: rgb(92, 95, 97); font-family: &quot;Microsoft YaHei&quot;, 微软雅黑, SimSun, 宋体; font-size: 18px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);\">3、在财务中心，点击转入虚拟币，下拉菜单选择并点击钛值（TV）。</p><p style=\"margin: 0px 0px 20px; padding: 0px; box-sizing: content-box; word-break: normal; color: rgb(92, 95, 97); font-family: &quot;Microsoft YaHei&quot;, 微软雅黑, SimSun, 宋体; font-size: 18px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);\"><br style=\"margin: 0px; padding: 0px; box-sizing: content-box;\"/></p><p style=\"margin: 0px 0px 20px; padding: 0px; box-sizing: content-box; word-break: normal; color: rgb(92, 95, 97); font-family: &quot;Microsoft YaHei&quot;, 微软雅黑, SimSun, 宋体; font-size: 18px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);\"><img src=\"https://img1.btc123.com/up/29/313A313A3239_7226.png\" alt=\"\" style=\"margin: 0px auto 20px; padding: 0px; box-sizing: content-box; border: 0px; vertical-align: top; display: block; max-width: 780px; height: auto; text-align: center;\"/><br style=\"margin: 0px; padding: 0px; box-sizing: content-box;\"/></p><p style=\"margin: 0px 0px 20px; padding: 0px; box-sizing: content-box; word-break: normal; color: rgb(92, 95, 97); font-family: &quot;Microsoft YaHei&quot;, 微软雅黑, SimSun, 宋体; font-size: 18px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);\">4、出现钛值（TV）地址，进行复制。</p><p style=\"margin: 0px 0px 20px; padding: 0px; box-sizing: content-box; word-break: normal; color: rgb(92, 95, 97); font-family: &quot;Microsoft YaHei&quot;, 微软雅黑, SimSun, 宋体; font-size: 18px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);\"><img src=\"https://img1.btc123.com/up/29/313A313A3239_7227.png\" alt=\"\" style=\"margin: 0px auto 20px; padding: 0px; box-sizing: content-box; border: 0px; vertical-align: top; display: block; max-width: 780px; height: auto; text-align: center;\"/><br style=\"margin: 0px; padding: 0px; box-sizing: content-box;\"/></p><p style=\"margin: 0px 0px 20px; padding: 0px; box-sizing: content-box; word-break: normal; color: rgb(92, 95, 97); font-family: &quot;Microsoft YaHei&quot;, 微软雅黑, SimSun, 宋体; font-size: 18px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);\">5、登陆www.btc123.com,在资产管理中选择TV资产，点击提现TV。</p><p style=\"margin: 0px 0px 20px; padding: 0px; box-sizing: content-box; word-break: normal; color: rgb(92, 95, 97); font-family: &quot;Microsoft YaHei&quot;, 微软雅黑, SimSun, 宋体; font-size: 18px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);\"><br style=\"margin: 0px; padding: 0px; box-sizing: content-box;\"/></p><p style=\"margin: 0px 0px 20px; padding: 0px; box-sizing: content-box; word-break: normal; color: rgb(92, 95, 97); font-family: &quot;Microsoft YaHei&quot;, 微软雅黑, SimSun, 宋体; font-size: 18px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);\"><img src=\"https://img1.btc123.com/up/29/313A313A3239_7228.png\" alt=\"\" style=\"margin: 0px auto 20px; padding: 0px; box-sizing: content-box; border: 0px; vertical-align: top; display: block; max-width: 780px; height: auto; text-align: center;\"/><br style=\"margin: 0px; padding: 0px; box-sizing: content-box;\"/></p><p style=\"margin: 0px 0px 20px; padding: 0px; box-sizing: content-box; word-break: normal; color: rgb(92, 95, 97); font-family: &quot;Microsoft YaHei&quot;, 微软雅黑, SimSun, 宋体; font-size: 18px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);\">6、发送至第4步复制的TV地址，输入转账金额和安全密码，点击发送。即可等待TV到账。</p><p style=\"margin: 0px 0px 20px; padding: 0px; box-sizing: content-box; word-break: normal; color: rgb(92, 95, 97); font-family: &quot;Microsoft YaHei&quot;, 微软雅黑, SimSun, 宋体; font-size: 18px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);\"><br style=\"margin: 0px; padding: 0px; box-sizing: content-box;\"/></p><p style=\"margin: 0px 0px 20px; padding: 0px; box-sizing: content-box; word-break: normal; color: rgb(92, 95, 97); font-family: &quot;Microsoft YaHei&quot;, 微软雅黑, SimSun, 宋体; font-size: 18px; font-style: normal; font-variant-ligatures: normal; font-variant-caps: normal; font-weight: normal; letter-spacing: normal; orphans: 2; text-align: left; text-indent: 0px; text-transform: none; white-space: normal; widows: 2; word-spacing: 0px; -webkit-text-stroke-width: 0px; background-color: rgb(255, 255, 255);\"><img src=\"https://img1.btc123.com/up/29/313A313A3239_7229.png\" alt=\"\" style=\"margin: 0px auto 20px; padding: 0px; box-sizing: content-box; border: 0px; vertical-align: top; display: block; max-width: 780px; height: auto; text-align: center;\"/></p><p><br class=\"Apple-interchange-newline\"/></p><p><br/></p>', '123教程| 如何从btc123提TV到ebtcbank');

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_weibo_top`
--

CREATE TABLE `ocenter_weibo_top` (
  `weibo_id` int(11) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='置顶微博表';

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_weibo_topic`
--

CREATE TABLE `ocenter_weibo_topic` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT '话题名',
  `logo` varchar(255) NOT NULL DEFAULT '/topicavatar.jpg' COMMENT '话题logo',
  `intro` varchar(255) NOT NULL COMMENT '导语',
  `qrcode` varchar(255) NOT NULL COMMENT '二维码',
  `uadmin` int(11) NOT NULL DEFAULT '0' COMMENT '话题管理   默认无',
  `read_count` int(11) NOT NULL DEFAULT '0' COMMENT '阅读',
  `is_top` tinyint(4) NOT NULL,
  `weibo_num` int(11) NOT NULL DEFAULT '0' COMMENT '微博数',
  `status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_weibo_topic_follow`
--

CREATE TABLE `ocenter_weibo_topic_follow` (
  `id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `create_time` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ocenter_weibo_topic_link`
--

CREATE TABLE `ocenter_weibo_topic_link` (
  `id` int(11) NOT NULL,
  `weibo_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `create_time` int(11) NOT NULL,
  `is_top` tinyint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='话题、微博关联表';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ocenter_action`
--
ALTER TABLE `ocenter_action`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_action_limit`
--
ALTER TABLE `ocenter_action_limit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_action_log`
--
ALTER TABLE `ocenter_action_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `action_ip_ix` (`action_ip`),
  ADD KEY `action_id_ix` (`action_id`),
  ADD KEY `user_id_ix` (`user_id`);

--
-- Indexes for table `ocenter_addons`
--
ALTER TABLE `ocenter_addons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_adv`
--
ALTER TABLE `ocenter_adv`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_advertisement`
--
ALTER TABLE `ocenter_advertisement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_adv_pos`
--
ALTER TABLE `ocenter_adv_pos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_announce`
--
ALTER TABLE `ocenter_announce`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_announce_arrive`
--
ALTER TABLE `ocenter_announce_arrive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_attachment`
--
ALTER TABLE `ocenter_attachment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_record_status` (`record_id`,`status`);

--
-- Indexes for table `ocenter_attest`
--
ALTER TABLE `ocenter_attest`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_attest_type`
--
ALTER TABLE `ocenter_attest_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_auth_extend`
--
ALTER TABLE `ocenter_auth_extend`
  ADD UNIQUE KEY `group_extend_type` (`group_id`,`extend_id`,`type`),
  ADD KEY `uid` (`group_id`),
  ADD KEY `group_id` (`extend_id`);

--
-- Indexes for table `ocenter_auth_group`
--
ALTER TABLE `ocenter_auth_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_auth_group_access`
--
ALTER TABLE `ocenter_auth_group_access`
  ADD UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `ocenter_auth_rule`
--
ALTER TABLE `ocenter_auth_rule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module` (`module`,`status`,`type`);

--
-- Indexes for table `ocenter_avatar`
--
ALTER TABLE `ocenter_avatar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_channel`
--
ALTER TABLE `ocenter_channel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `ocenter_checkin`
--
ALTER TABLE `ocenter_checkin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_coin_addr`
--
ALTER TABLE `ocenter_coin_addr`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_config`
--
ALTER TABLE `ocenter_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_name` (`name`),
  ADD KEY `type` (`type`),
  ADD KEY `group` (`group`);

--
-- Indexes for table `ocenter_consumption_log`
--
ALTER TABLE `ocenter_consumption_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_country`
--
ALTER TABLE `ocenter_country`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `ocenter_count_active`
--
ALTER TABLE `ocenter_count_active`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `date` (`date`);

--
-- Indexes for table `ocenter_count_lost`
--
ALTER TABLE `ocenter_count_lost`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `date` (`date`);

--
-- Indexes for table `ocenter_count_remain`
--
ALTER TABLE `ocenter_count_remain`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `date` (`date`);

--
-- Indexes for table `ocenter_currency`
--
ALTER TABLE `ocenter_currency`
  ADD PRIMARY KEY (`id`),
  ADD KEY `code` (`code`);

--
-- Indexes for table `ocenter_district`
--
ALTER TABLE `ocenter_district`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_expression`
--
ALTER TABLE `ocenter_expression`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_expression_pkg`
--
ALTER TABLE `ocenter_expression_pkg`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_field`
--
ALTER TABLE `ocenter_field`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_field_group`
--
ALTER TABLE `ocenter_field_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_field_setting`
--
ALTER TABLE `ocenter_field_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_file`
--
ALTER TABLE `ocenter_file`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_md5` (`md5`);

--
-- Indexes for table `ocenter_follow`
--
ALTER TABLE `ocenter_follow`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_hooks`
--
ALTER TABLE `ocenter_hooks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `ocenter_iexpression`
--
ALTER TABLE `ocenter_iexpression`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_iexpression_link`
--
ALTER TABLE `ocenter_iexpression_link`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_invite`
--
ALTER TABLE `ocenter_invite`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_invite_buy_log`
--
ALTER TABLE `ocenter_invite_buy_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_invite_log`
--
ALTER TABLE `ocenter_invite_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_invite_type`
--
ALTER TABLE `ocenter_invite_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_invite_user_info`
--
ALTER TABLE `ocenter_invite_user_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_letter`
--
ALTER TABLE `ocenter_letter`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `uid_2` (`uid`);

--
-- Indexes for table `ocenter_local_comment`
--
ALTER TABLE `ocenter_local_comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_market`
--
ALTER TABLE `ocenter_market`
  ADD PRIMARY KEY (`id`),
  ADD KEY `market` (`market`);

--
-- Indexes for table `ocenter_member`
--
ALTER TABLE `ocenter_member`
  ADD PRIMARY KEY (`uid`),
  ADD KEY `status` (`status`),
  ADD KEY `name` (`nickname`);

--
-- Indexes for table `ocenter_menu`
--
ALTER TABLE `ocenter_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `ocenter_message`
--
ALTER TABLE `ocenter_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_message_content`
--
ALTER TABLE `ocenter_message_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_message_old`
--
ALTER TABLE `ocenter_message_old`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_message_type`
--
ALTER TABLE `ocenter_message_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_mob_channel`
--
ALTER TABLE `ocenter_mob_channel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `ocenter_module`
--
ALTER TABLE `ocenter_module`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `name_2` (`name`);

--
-- Indexes for table `ocenter_m_addons`
--
ALTER TABLE `ocenter_m_addons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_m_adv`
--
ALTER TABLE `ocenter_m_adv`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_m_advertisement`
--
ALTER TABLE `ocenter_m_advertisement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_m_adv_pos`
--
ALTER TABLE `ocenter_m_adv_pos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_m_auth_group`
--
ALTER TABLE `ocenter_m_auth_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_m_auth_group_access`
--
ALTER TABLE `ocenter_m_auth_group_access`
  ADD UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  ADD KEY `uid` (`uid`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `ocenter_m_auth_rule`
--
ALTER TABLE `ocenter_m_auth_rule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `module` (`module`,`status`,`type`);

--
-- Indexes for table `ocenter_m_channel`
--
ALTER TABLE `ocenter_m_channel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `ocenter_m_config`
--
ALTER TABLE `ocenter_m_config`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_name` (`name`),
  ADD KEY `type` (`type`),
  ADD KEY `group` (`group`);

--
-- Indexes for table `ocenter_m_hooks`
--
ALTER TABLE `ocenter_m_hooks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `ocenter_m_menu`
--
ALTER TABLE `ocenter_m_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `ocenter_m_module`
--
ALTER TABLE `ocenter_m_module`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `name_2` (`name`);

--
-- Indexes for table `ocenter_m_reward`
--
ALTER TABLE `ocenter_m_reward`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_m_session`
--
ALTER TABLE `ocenter_m_session`
  ADD UNIQUE KEY `session_id` (`session_id`);

--
-- Indexes for table `ocenter_news`
--
ALTER TABLE `ocenter_news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_news_category`
--
ALTER TABLE `ocenter_news_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_news_detail`
--
ALTER TABLE `ocenter_news_detail`
  ADD PRIMARY KEY (`news_id`);

--
-- Indexes for table `ocenter_news_reply`
--
ALTER TABLE `ocenter_news_reply`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_news_search`
--
ALTER TABLE `ocenter_news_search`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_order_goods`
--
ALTER TABLE `ocenter_order_goods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_order_recharge`
--
ALTER TABLE `ocenter_order_recharge`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_order_withdraw`
--
ALTER TABLE `ocenter_order_withdraw`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_pay`
--
ALTER TABLE `ocenter_pay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `ocenter_picture`
--
ALTER TABLE `ocenter_picture`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_rank`
--
ALTER TABLE `ocenter_rank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_rank_user`
--
ALTER TABLE `ocenter_rank_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_register`
--
ALTER TABLE `ocenter_register`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_report`
--
ALTER TABLE `ocenter_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_reward`
--
ALTER TABLE `ocenter_reward`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_role`
--
ALTER TABLE `ocenter_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_role_config`
--
ALTER TABLE `ocenter_role_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_role_group`
--
ALTER TABLE `ocenter_role_group`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_schedule`
--
ALTER TABLE `ocenter_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_score_log`
--
ALTER TABLE `ocenter_score_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_send_sms_log`
--
ALTER TABLE `ocenter_send_sms_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_sensitive`
--
ALTER TABLE `ocenter_sensitive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_seo_rule`
--
ALTER TABLE `ocenter_seo_rule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_session`
--
ALTER TABLE `ocenter_session`
  ADD UNIQUE KEY `session_id` (`session_id`);

--
-- Indexes for table `ocenter_sso_app`
--
ALTER TABLE `ocenter_sso_app`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_super_links`
--
ALTER TABLE `ocenter_super_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_support`
--
ALTER TABLE `ocenter_support`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_sync_login`
--
ALTER TABLE `ocenter_sync_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_talk`
--
ALTER TABLE `ocenter_talk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_talk_message`
--
ALTER TABLE `ocenter_talk_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_talk_message_push`
--
ALTER TABLE `ocenter_talk_message_push`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_talk_push`
--
ALTER TABLE `ocenter_talk_push`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_ticket`
--
ALTER TABLE `ocenter_ticket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_tile`
--
ALTER TABLE `ocenter_tile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_tradead`
--
ALTER TABLE `ocenter_tradead`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_trade_order`
--
ALTER TABLE `ocenter_trade_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_ucenter_admin`
--
ALTER TABLE `ocenter_ucenter_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_ucenter_member`
--
ALTER TABLE `ocenter_ucenter_member`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `ocenter_ucenter_score_type`
--
ALTER TABLE `ocenter_ucenter_score_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_ucenter_setting`
--
ALTER TABLE `ocenter_ucenter_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_ucenter_short`
--
ALTER TABLE `ocenter_ucenter_short`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_ucenter_user_link`
--
ALTER TABLE `ocenter_ucenter_user_link`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_url`
--
ALTER TABLE `ocenter_url`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_url` (`url`);

--
-- Indexes for table `ocenter_user_config`
--
ALTER TABLE `ocenter_user_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_user_nav`
--
ALTER TABLE `ocenter_user_nav`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_user_role`
--
ALTER TABLE `ocenter_user_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_user_tag`
--
ALTER TABLE `ocenter_user_tag`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_user_tag_link`
--
ALTER TABLE `ocenter_user_tag_link`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_user_token`
--
ALTER TABLE `ocenter_user_token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_verify`
--
ALTER TABLE `ocenter_verify`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_version`
--
ALTER TABLE `ocenter_version`
  ADD PRIMARY KEY (`name`),
  ADD KEY `id` (`number`);

--
-- Indexes for table `ocenter_weibo`
--
ALTER TABLE `ocenter_weibo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `crowd_id` (`crowd_id`);

--
-- Indexes for table `ocenter_weibo_cache`
--
ALTER TABLE `ocenter_weibo_cache`
  ADD PRIMARY KEY (`id`),
  ADD KEY `weibo_id` (`weibo_id`),
  ADD KEY `groups` (`groups`);

--
-- Indexes for table `ocenter_weibo_comment`
--
ALTER TABLE `ocenter_weibo_comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_weibo_crowd`
--
ALTER TABLE `ocenter_weibo_crowd`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_weibo_crowd_member`
--
ALTER TABLE `ocenter_weibo_crowd_member`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_weibo_crowd_score`
--
ALTER TABLE `ocenter_weibo_crowd_score`
  ADD PRIMARY KEY (`crowd_id`);

--
-- Indexes for table `ocenter_weibo_crowd_type`
--
ALTER TABLE `ocenter_weibo_crowd_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_weibo_long`
--
ALTER TABLE `ocenter_weibo_long`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_weibo_top`
--
ALTER TABLE `ocenter_weibo_top`
  ADD PRIMARY KEY (`weibo_id`);

--
-- Indexes for table `ocenter_weibo_topic`
--
ALTER TABLE `ocenter_weibo_topic`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_weibo_topic_follow`
--
ALTER TABLE `ocenter_weibo_topic_follow`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ocenter_weibo_topic_link`
--
ALTER TABLE `ocenter_weibo_topic_link`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `ocenter_action`
--
ALTER TABLE `ocenter_action`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=11005;
--
-- 使用表AUTO_INCREMENT `ocenter_action_limit`
--
ALTER TABLE `ocenter_action_limit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- 使用表AUTO_INCREMENT `ocenter_action_log`
--
ALTER TABLE `ocenter_action_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=316;
--
-- 使用表AUTO_INCREMENT `ocenter_addons`
--
ALTER TABLE `ocenter_addons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=16;
--
-- 使用表AUTO_INCREMENT `ocenter_adv`
--
ALTER TABLE `ocenter_adv`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=10000;
--
-- 使用表AUTO_INCREMENT `ocenter_advertisement`
--
ALTER TABLE `ocenter_advertisement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_adv_pos`
--
ALTER TABLE `ocenter_adv_pos`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=10000;
--
-- 使用表AUTO_INCREMENT `ocenter_announce`
--
ALTER TABLE `ocenter_announce`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_announce_arrive`
--
ALTER TABLE `ocenter_announce_arrive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_attachment`
--
ALTER TABLE `ocenter_attachment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_attest`
--
ALTER TABLE `ocenter_attest`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `ocenter_attest_type`
--
ALTER TABLE `ocenter_attest_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `ocenter_auth_group`
--
ALTER TABLE `ocenter_auth_group`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '权限组id,自增主键', AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `ocenter_auth_rule`
--
ALTER TABLE `ocenter_auth_rule`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键', AUTO_INCREMENT=10006;
--
-- 使用表AUTO_INCREMENT `ocenter_avatar`
--
ALTER TABLE `ocenter_avatar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `ocenter_channel`
--
ALTER TABLE `ocenter_channel`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '频道ID', AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `ocenter_checkin`
--
ALTER TABLE `ocenter_checkin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `ocenter_coin_addr`
--
ALTER TABLE `ocenter_coin_addr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_config`
--
ALTER TABLE `ocenter_config`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '配置ID', AUTO_INCREMENT=11741;
--
-- 使用表AUTO_INCREMENT `ocenter_consumption_log`
--
ALTER TABLE `ocenter_consumption_log`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '自增id', AUTO_INCREMENT=75;
--
-- 使用表AUTO_INCREMENT `ocenter_country`
--
ALTER TABLE `ocenter_country`
  MODIFY `id` smallint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;
--
-- 使用表AUTO_INCREMENT `ocenter_count_active`
--
ALTER TABLE `ocenter_count_active`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
--
-- 使用表AUTO_INCREMENT `ocenter_count_lost`
--
ALTER TABLE `ocenter_count_lost`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- 使用表AUTO_INCREMENT `ocenter_count_remain`
--
ALTER TABLE `ocenter_count_remain`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- 使用表AUTO_INCREMENT `ocenter_currency`
--
ALTER TABLE `ocenter_currency`
  MODIFY `id` smallint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;
--
-- 使用表AUTO_INCREMENT `ocenter_district`
--
ALTER TABLE `ocenter_district`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=910007;
--
-- 使用表AUTO_INCREMENT `ocenter_expression`
--
ALTER TABLE `ocenter_expression`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_expression_pkg`
--
ALTER TABLE `ocenter_expression_pkg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_field`
--
ALTER TABLE `ocenter_field`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_field_group`
--
ALTER TABLE `ocenter_field_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `ocenter_field_setting`
--
ALTER TABLE `ocenter_field_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- 使用表AUTO_INCREMENT `ocenter_file`
--
ALTER TABLE `ocenter_file`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文件ID';
--
-- 使用表AUTO_INCREMENT `ocenter_follow`
--
ALTER TABLE `ocenter_follow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id', AUTO_INCREMENT=207;
--
-- 使用表AUTO_INCREMENT `ocenter_hooks`
--
ALTER TABLE `ocenter_hooks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=10000;
--
-- 使用表AUTO_INCREMENT `ocenter_iexpression`
--
ALTER TABLE `ocenter_iexpression`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_iexpression_link`
--
ALTER TABLE `ocenter_iexpression_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_invite`
--
ALTER TABLE `ocenter_invite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PRIMARY_KEY', AUTO_INCREMENT=17;
--
-- 使用表AUTO_INCREMENT `ocenter_invite_buy_log`
--
ALTER TABLE `ocenter_invite_buy_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PRIMARY_KEY';
--
-- 使用表AUTO_INCREMENT `ocenter_invite_log`
--
ALTER TABLE `ocenter_invite_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PRIMARY_KEY';
--
-- 使用表AUTO_INCREMENT `ocenter_invite_type`
--
ALTER TABLE `ocenter_invite_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PRIMARY_KEY', AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `ocenter_invite_user_info`
--
ALTER TABLE `ocenter_invite_user_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PRIMARY_KEY';
--
-- 使用表AUTO_INCREMENT `ocenter_letter`
--
ALTER TABLE `ocenter_letter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_local_comment`
--
ALTER TABLE `ocenter_local_comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_market`
--
ALTER TABLE `ocenter_market`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- 使用表AUTO_INCREMENT `ocenter_member`
--
ALTER TABLE `ocenter_member`
  MODIFY `uid` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户ID', AUTO_INCREMENT=148;
--
-- 使用表AUTO_INCREMENT `ocenter_menu`
--
ALTER TABLE `ocenter_menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文档ID', AUTO_INCREMENT=13042;
--
-- 使用表AUTO_INCREMENT `ocenter_message`
--
ALTER TABLE `ocenter_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;
--
-- 使用表AUTO_INCREMENT `ocenter_message_content`
--
ALTER TABLE `ocenter_message_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;
--
-- 使用表AUTO_INCREMENT `ocenter_message_type`
--
ALTER TABLE `ocenter_message_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `ocenter_mob_channel`
--
ALTER TABLE `ocenter_mob_channel`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '频道ID', AUTO_INCREMENT=12;
--
-- 使用表AUTO_INCREMENT `ocenter_module`
--
ALTER TABLE `ocenter_module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- 使用表AUTO_INCREMENT `ocenter_m_addons`
--
ALTER TABLE `ocenter_m_addons`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=15;
--
-- 使用表AUTO_INCREMENT `ocenter_m_adv`
--
ALTER TABLE `ocenter_m_adv`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=10024;
--
-- 使用表AUTO_INCREMENT `ocenter_m_advertisement`
--
ALTER TABLE `ocenter_m_advertisement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `ocenter_m_adv_pos`
--
ALTER TABLE `ocenter_m_adv_pos`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=10005;
--
-- 使用表AUTO_INCREMENT `ocenter_m_auth_group`
--
ALTER TABLE `ocenter_m_auth_group`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '权限组id,自增主键', AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `ocenter_m_auth_rule`
--
ALTER TABLE `ocenter_m_auth_rule`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '规则id,自增主键', AUTO_INCREMENT=10002;
--
-- 使用表AUTO_INCREMENT `ocenter_m_channel`
--
ALTER TABLE `ocenter_m_channel`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '频道ID', AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `ocenter_m_config`
--
ALTER TABLE `ocenter_m_config`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '配置ID', AUTO_INCREMENT=10000;
--
-- 使用表AUTO_INCREMENT `ocenter_m_hooks`
--
ALTER TABLE `ocenter_m_hooks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=10000;
--
-- 使用表AUTO_INCREMENT `ocenter_m_menu`
--
ALTER TABLE `ocenter_m_menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文档ID', AUTO_INCREMENT=10066;
--
-- 使用表AUTO_INCREMENT `ocenter_m_module`
--
ALTER TABLE `ocenter_m_module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
--
-- 使用表AUTO_INCREMENT `ocenter_m_reward`
--
ALTER TABLE `ocenter_m_reward`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_news`
--
ALTER TABLE `ocenter_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_news_category`
--
ALTER TABLE `ocenter_news_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `ocenter_news_reply`
--
ALTER TABLE `ocenter_news_reply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- 使用表AUTO_INCREMENT `ocenter_news_search`
--
ALTER TABLE `ocenter_news_search`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_order_goods`
--
ALTER TABLE `ocenter_order_goods`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '订单号', AUTO_INCREMENT=2147483647;
--
-- 使用表AUTO_INCREMENT `ocenter_order_recharge`
--
ALTER TABLE `ocenter_order_recharge`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '订单号', AUTO_INCREMENT=2147483647;
--
-- 使用表AUTO_INCREMENT `ocenter_order_withdraw`
--
ALTER TABLE `ocenter_order_withdraw`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_pay`
--
ALTER TABLE `ocenter_pay`
  MODIFY `id` smallint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- 使用表AUTO_INCREMENT `ocenter_picture`
--
ALTER TABLE `ocenter_picture`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id自增', AUTO_INCREMENT=24;
--
-- 使用表AUTO_INCREMENT `ocenter_rank`
--
ALTER TABLE `ocenter_rank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_rank_user`
--
ALTER TABLE `ocenter_rank_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_register`
--
ALTER TABLE `ocenter_register`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_report`
--
ALTER TABLE `ocenter_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_reward`
--
ALTER TABLE `ocenter_reward`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_role`
--
ALTER TABLE `ocenter_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `ocenter_role_config`
--
ALTER TABLE `ocenter_role_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `ocenter_role_group`
--
ALTER TABLE `ocenter_role_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_schedule`
--
ALTER TABLE `ocenter_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `ocenter_score_log`
--
ALTER TABLE `ocenter_score_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- 使用表AUTO_INCREMENT `ocenter_send_sms_log`
--
ALTER TABLE `ocenter_send_sms_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `ocenter_sensitive`
--
ALTER TABLE `ocenter_sensitive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_seo_rule`
--
ALTER TABLE `ocenter_seo_rule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10000;
--
-- 使用表AUTO_INCREMENT `ocenter_sso_app`
--
ALTER TABLE `ocenter_sso_app`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_super_links`
--
ALTER TABLE `ocenter_super_links`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `ocenter_support`
--
ALTER TABLE `ocenter_support`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `ocenter_sync_login`
--
ALTER TABLE `ocenter_sync_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_talk`
--
ALTER TABLE `ocenter_talk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `ocenter_talk_message`
--
ALTER TABLE `ocenter_talk_message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- 使用表AUTO_INCREMENT `ocenter_talk_message_push`
--
ALTER TABLE `ocenter_talk_message_push`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=310;
--
-- 使用表AUTO_INCREMENT `ocenter_talk_push`
--
ALTER TABLE `ocenter_talk_push`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- 使用表AUTO_INCREMENT `ocenter_ticket`
--
ALTER TABLE `ocenter_ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_tile`
--
ALTER TABLE `ocenter_tile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `ocenter_tradead`
--
ALTER TABLE `ocenter_tradead`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4007;
--
-- 使用表AUTO_INCREMENT `ocenter_trade_order`
--
ALTER TABLE `ocenter_trade_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `ocenter_ucenter_admin`
--
ALTER TABLE `ocenter_ucenter_admin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '管理员ID';
--
-- 使用表AUTO_INCREMENT `ocenter_ucenter_member`
--
ALTER TABLE `ocenter_ucenter_member`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户ID', AUTO_INCREMENT=148;
--
-- 使用表AUTO_INCREMENT `ocenter_ucenter_score_type`
--
ALTER TABLE `ocenter_ucenter_score_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `ocenter_ucenter_setting`
--
ALTER TABLE `ocenter_ucenter_setting`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '设置ID';
--
-- 使用表AUTO_INCREMENT `ocenter_ucenter_short`
--
ALTER TABLE `ocenter_ucenter_short`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `ocenter_ucenter_user_link`
--
ALTER TABLE `ocenter_ucenter_user_link`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_url`
--
ALTER TABLE `ocenter_url`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '链接唯一标识';
--
-- 使用表AUTO_INCREMENT `ocenter_user_config`
--
ALTER TABLE `ocenter_user_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_user_nav`
--
ALTER TABLE `ocenter_user_nav`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '频道ID', AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `ocenter_user_role`
--
ALTER TABLE `ocenter_user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- 使用表AUTO_INCREMENT `ocenter_user_tag`
--
ALTER TABLE `ocenter_user_tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `ocenter_user_tag_link`
--
ALTER TABLE `ocenter_user_tag_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `ocenter_user_token`
--
ALTER TABLE `ocenter_user_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- 使用表AUTO_INCREMENT `ocenter_verify`
--
ALTER TABLE `ocenter_verify`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;
--
-- 使用表AUTO_INCREMENT `ocenter_weibo`
--
ALTER TABLE `ocenter_weibo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `ocenter_weibo_cache`
--
ALTER TABLE `ocenter_weibo_cache`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;
--
-- 使用表AUTO_INCREMENT `ocenter_weibo_comment`
--
ALTER TABLE `ocenter_weibo_comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_weibo_crowd`
--
ALTER TABLE `ocenter_weibo_crowd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_weibo_crowd_member`
--
ALTER TABLE `ocenter_weibo_crowd_member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_weibo_crowd_score`
--
ALTER TABLE `ocenter_weibo_crowd_score`
  MODIFY `crowd_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '圈子id';
--
-- 使用表AUTO_INCREMENT `ocenter_weibo_crowd_type`
--
ALTER TABLE `ocenter_weibo_crowd_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_weibo_long`
--
ALTER TABLE `ocenter_weibo_long`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `ocenter_weibo_topic`
--
ALTER TABLE `ocenter_weibo_topic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_weibo_topic_follow`
--
ALTER TABLE `ocenter_weibo_topic_follow`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ocenter_weibo_topic_link`
--
ALTER TABLE `ocenter_weibo_topic_link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
