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

 Date: 11/12/2018 17:02:34
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='调研问卷表';

SET FOREIGN_KEY_CHECKS = 1;
