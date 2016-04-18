SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `thinkcmf`
--

-- --------------------------------------------------------

--
-- 表的结构 `cmf_plugin_vnimy_tpls`
--

CREATE TABLE IF NOT EXISTS `cmf_plugin_vnimy_tpls` (
  `tpl_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tpl_name` varchar(64) NOT NULL,
  `tpl_path` varchar(255) NOT NULL,
  `listorder` int(10) NOT NULL DEFAULT '0',
  `status` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`tpl_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COMMENT='模板列表';

--
-- 表的结构 `cmf_menu`，修改参数长度
--

ALTER TABLE `cmf_menu`
CHANGE COLUMN `data` `data` CHAR(100) NOT NULL COMMENT '额外参数' ;