/*
 Navicat Premium Data Transfer

 Source Server         : homestead
 Source Server Type    : MySQL
 Source Server Version : 50724
 Source Host           : 192.168.10.10:3306
 Source Schema         : ceshi

 Target Server Type    : MySQL
 Target Server Version : 50724
 File Encoding         : 65001

 Date: 17/12/2018 17:44:22
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for th_activity
-- ----------------------------
DROP TABLE IF EXISTS `th_activity`;
CREATE TABLE `th_activity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '活动自增id',
  `activity_name` varchar(255) DEFAULT '' COMMENT '活动名称',
  `activity_url` varchar(255) DEFAULT '' COMMENT '活动图片',
  `activity_start_time` int(10) unsigned DEFAULT '0' COMMENT '活动开始时间',
  `activity_end_time` int(10) unsigned DEFAULT '0' COMMENT '活动结束时间',
  `activity_detail` text COMMENT '活动详情',
  `status` tinyint(3) unsigned DEFAULT '0' COMMENT '0正常1删除',
  `state` tinyint(3) unsigned DEFAULT '0' COMMENT '0开启1关闭',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  `store_id` int(11) unsigned DEFAULT '0' COMMENT '店铺id',
  `store_name` varchar(255) DEFAULT '' COMMENT '店铺名称',
  `link_state` tinyint(1) unsigned DEFAULT '0' COMMENT '0内连接1外连接',
  `number` int(10) unsigned DEFAULT '0' COMMENT '活动可参与总人数',
  `participants_number` int(10) unsigned DEFAULT '0' COMMENT '活动已参与人数',
  `pid` int(11) unsigned DEFAULT '0' COMMENT '活动库id',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动表';

-- ----------------------------
-- Table structure for th_activity_library
-- ----------------------------
DROP TABLE IF EXISTS `th_activity_library`;
CREATE TABLE `th_activity_library` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '活动自增id',
  `activity_name` varchar(255) DEFAULT '' COMMENT '活动名称',
  `activity_url` varchar(255) DEFAULT '' COMMENT '活动图片',
  `activity_start_time` int(10) unsigned DEFAULT '0' COMMENT '活动开始时间',
  `activity_end_time` int(10) unsigned DEFAULT '0' COMMENT '活动结束时间',
  `activity_detail` text COMMENT '活动详情',
  `status` tinyint(3) unsigned DEFAULT '0' COMMENT '0正常1删除',
  `state` tinyint(3) unsigned DEFAULT '0' COMMENT '0开启1关闭',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  `store_id` int(11) unsigned DEFAULT '0' COMMENT '店铺id',
  `store_name` varchar(255) DEFAULT '' COMMENT '店铺名称',
  `link_state` tinyint(1) unsigned DEFAULT '0' COMMENT '0内连接1外连接',
  `number` int(10) unsigned DEFAULT '0' COMMENT '活动可参与总人数',
  `participants_number` int(10) unsigned DEFAULT '0' COMMENT '活动已参与人数',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `store_id` (`store_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动库表';

-- ----------------------------
-- Table structure for th_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `th_admin_log`;
CREATE TABLE `th_admin_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `create_time` int(10) NOT NULL DEFAULT '0',
  `user_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `log_info` varchar(255) NOT NULL DEFAULT '',
  `ip_address` varchar(15) NOT NULL DEFAULT '',
  `user_name` varchar(50) DEFAULT '' COMMENT '账号',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='系统日志表';

-- ----------------------------
-- Table structure for th_advertisement
-- ----------------------------
DROP TABLE IF EXISTS `th_advertisement`;
CREATE TABLE `th_advertisement` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '广告id',
  `type_id` int(11) unsigned DEFAULT '0' COMMENT '广告类型id',
  `image` varchar(255) DEFAULT '' COMMENT '广告图片',
  `name` varchar(255) DEFAULT '' COMMENT '广告名称',
  `url` varchar(255) DEFAULT '' COMMENT '跳转路径',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '0正常 1删除',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  `store_id` int(10) unsigned DEFAULT '0' COMMENT '店铺id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广告表';

-- ----------------------------
-- Table structure for th_advertisement_type
-- ----------------------------
DROP TABLE IF EXISTS `th_advertisement_type`;
CREATE TABLE `th_advertisement_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '广告类型自增id',
  `type_name` varchar(255) DEFAULT '' COMMENT '类型名称',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '0正常1删除',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  `store_id` int(10) unsigned DEFAULT '0' COMMENT '店铺id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='广告类型表';

-- ----------------------------
-- Table structure for th_goods
-- ----------------------------
DROP TABLE IF EXISTS `th_goods`;
CREATE TABLE `th_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品自增id',
  `store_id` int(11) unsigned DEFAULT '0' COMMENT '店铺id',
  `goods_name` varchar(255) DEFAULT '' COMMENT '商品名称',
  `goods_images` varchar(2000) DEFAULT '' COMMENT '商品图片已json形式存储',
  `goods_original_price` decimal(20,2) unsigned DEFAULT NULL COMMENT '商品原价',
  `present_price` decimal(20,2) unsigned DEFAULT NULL COMMENT '商品现价',
  `goods_detail` text COMMENT '商品详情',
  `goods_specifications` varchar(255) DEFAULT '' COMMENT '商品规则已json形式存储',
  `goods_attribute` varchar(255) DEFAULT '' COMMENT '商品属性已json形式存储',
  `goods_stock` int(11) unsigned DEFAULT '0' COMMENT '库存',
  `state` tinyint(1) unsigned DEFAULT '0' COMMENT '0上架1下架',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '0正常1删除2清除',
  `create_time` tinyint(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` tinyint(10) unsigned DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品表';

-- ----------------------------
-- Table structure for th_goods_type
-- ----------------------------
DROP TABLE IF EXISTS `th_goods_type`;
CREATE TABLE `th_goods_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品类型表',
  `goods_type_name` varchar(255) DEFAULT '' COMMENT '商品类型表',
  `store_id` int(11) unsigned DEFAULT '0' COMMENT '店铺id',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品类型表';

-- ----------------------------
-- Table structure for th_information
-- ----------------------------
DROP TABLE IF EXISTS `th_information`;
CREATE TABLE `th_information` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `search_box_content` varchar(255) DEFAULT '' COMMENT '搜索框默认内容',
  `site_name` varchar(255) DEFAULT '' COMMENT '网站名称',
  `site_title` varchar(255) DEFAULT '' COMMENT '网站标题',
  `phone` varchar(20) DEFAULT '' COMMENT '联系电话',
  `email` varchar(30) DEFAULT '' COMMENT '电子邮箱',
  `address` varchar(255) DEFAULT '' COMMENT '联系地址',
  `copyright_information` varchar(255) DEFAULT '' COMMENT '版权信息',
  `record_information` varchar(255) DEFAULT '' COMMENT '备案信息',
  `key_word` varchar(255) DEFAULT '' COMMENT '关键词',
  `site_description` varchar(2000) DEFAULT '' COMMENT '网站描述',
  `friendship_link` varchar(255) DEFAULT '' COMMENT '友情链接',
  `site_switch_state` tinyint(1) unsigned DEFAULT '0' COMMENT '0关1开',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统基本信息';

-- ----------------------------
-- Table structure for th_item_bank
-- ----------------------------
DROP TABLE IF EXISTS `th_item_bank`;
CREATE TABLE `th_item_bank` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '0单选题1多选题',
  `problem` varchar(255) DEFAULT '' COMMENT '问题',
  `answer` varchar(50) DEFAULT '' COMMENT '正确答案',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '0正常1删除',
  `content` varchar(2000) DEFAULT '' COMMENT '所以选项已json形式存储',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `store_id` int(11) unsigned DEFAULT '0' COMMENT '店铺id',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='总题库';

-- ----------------------------
-- Table structure for th_problem
-- ----------------------------
DROP TABLE IF EXISTS `th_problem`;
CREATE TABLE `th_problem` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `type` tinyint(1) unsigned DEFAULT '0' COMMENT '0单选题1多选题',
  `problem` varchar(255) DEFAULT '' COMMENT '问题',
  `answer` varchar(50) DEFAULT '' COMMENT '正确答案',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '0正常1删除',
  `content` varchar(2000) DEFAULT '' COMMENT '所以选项已json形式存储',
  `create_time` int(10) unsigned DEFAULT NULL COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT NULL COMMENT '更新时间',
  `store_id` int(11) unsigned DEFAULT '0' COMMENT '店铺id',
  `pid` int(11) unsigned DEFAULT '0' COMMENT '题库自增id',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='店铺下题库';

-- ----------------------------
-- Table structure for th_questionnaire
-- ----------------------------
DROP TABLE IF EXISTS `th_questionnaire`;
CREATE TABLE `th_questionnaire` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '问卷自增id',
  `title` varchar(255) DEFAULT '' COMMENT '标题',
  `start_time` int(10) unsigned DEFAULT '0' COMMENT '开始时间',
  `end_time` int(10) unsigned DEFAULT '0' COMMENT '结束时间',
  `questionnaire_text` varchar(2000) DEFAULT '' COMMENT '问卷正文',
  `opinion_completion` varchar(2000) DEFAULT '' COMMENT '意见填写',
  `problem_id` varchar(255) DEFAULT '' COMMENT '问题',
  `create_time` int(10) unsigned DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '0正常1删除',
  `store_id` int(11) unsigned DEFAULT '0' COMMENT '店铺id',
  PRIMARY KEY (`id`),
  KEY `status` (`status`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='调研问卷表';

-- ----------------------------
-- Table structure for th_user
-- ----------------------------
DROP TABLE IF EXISTS `th_user`;
CREATE TABLE `th_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `status` tinyint(3) unsigned DEFAULT '1' COMMENT '1有效0冻结',
  `createTime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间',
  `reg_ip` bigint(20) unsigned DEFAULT NULL COMMENT '注册ip',
  `last_login_time` int(10) unsigned DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` bigint(20) unsigned DEFAULT NULL COMMENT '最后登录ip',
  `update_time` int(11) unsigned DEFAULT NULL COMMENT '更新时间',
  `role_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色id',
  `store_id` int(11) unsigned DEFAULT NULL COMMENT '店铺id',
  `state` tinyint(1) unsigned DEFAULT '0' COMMENT '0正常1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO `th_user`(`id`, `user_name`, `password`, `status`, `createTime`, `reg_ip`, `last_login_time`, `last_login_ip`, `update_time`, `role_id`, `store_id`, `state`) VALUES (1, 'admin', '21232F297A57A5A743894A0E4A801FC3', 1, 2147483647, 119119, 2147483647, 0, 2, 0, NULL, 0);
INSERT INTO `th_user`(`id`, `user_name`, `password`, `status`, `createTime`, `reg_ip`, `last_login_time`, `last_login_ip`, `update_time`, `role_id`, `store_id`, `state`) VALUES (2, 'admin1', '4297f44b13955235245b2497399d7a93', 1, 1517565931, NULL, 0, NULL, NULL, 1, NULL, 0);
INSERT INTO `th_user`(`id`, `user_name`, `password`, `status`, `createTime`, `reg_ip`, `last_login_time`, `last_login_ip`, `update_time`, `role_id`, `store_id`, `state`) VALUES (3, '小川小川', '21232F297A57A5A743894A0E4A801FC3', 1, 1544586957, NULL, 0, NULL, 1544586957, 2, 1, 0);

