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

 Date: 12/12/2018 14:33:57
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='广告表';

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='广告类型表';

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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='总题库';

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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COMMENT='店铺下题库';

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='调研问卷表';

SET FOREIGN_KEY_CHECKS = 1;
