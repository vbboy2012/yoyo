ALTER TABLE  `ocenter_module` ADD  `is_pro` TINYINT NOT NULL DEFAULT  '0' COMMENT  '是否增强版';


INSERT INTO `ocenter_menu` (`id`, `title`, `pid`, `sort`, `url`, `hide`, `tip`, `group`, `is_dev`, `icon`, `module`) VALUES
(13000, '商业版客户端', 0, 0, 'admin/bclient/index', 1, '', '', 0, '', 'Bclient'),
(13001, '授权登录', 13000, 0, 'admin/bclient/index', 1, '', '授权登录', 0, '', 'Bclient'),
(13002, '激活过程', 13000, 0, 'admin/bclient/activate', 1, '', '激活过程', 0, '', 'Bclient'),
(13003, '升级扩展', 13000, 0, 'admin/bclient/updateList', 0, '', '客户中心', 0, '', 'Bclient'),
(13004, '升级', 13000, 0, 'admin/bclient/update', 1, '', '客户中心', 0, '', 'Bclient'),
(13005, '执行升级', 13000, 0, 'admin/bclient/doUpdate', 1, '', '客户中心', 0, '', 'Bclient'),
(13006, '已安装扩展', 13000, 0, 'admin/bclient/installed', 0, '', '客户中心', 0, '', 'Bclient'),
(13007, '客户中心', 13000, 0, 'admin/bclient/center', 0, '', '客户中心', 0, '', 'Bclient'),
(13008, '兑换历史', 13000, 0, 'admin/bclient/history', 0, '', '客户中心', 0, '', 'Bclient');