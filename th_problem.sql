/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50711
Source Host           : localhost:3306
Source Database       : ceshi

Target Server Type    : MYSQL
Target Server Version : 50711
File Encoding         : 65001

Date: 2018-12-10 22:51:24
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `th_problem`
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='题库';

-- ----------------------------
-- Records of th_problem
-- ----------------------------
INSERT INTO `th_problem` VALUES ('2', '0', '1+3=？', '[\"D\"]', '0', '{\"A\":\"1\",\"B\":\"2\",\"C\":\"3\",\"D\":\"4\"}', '1544453286', '1544453286', '1');
INSERT INTO `th_problem` VALUES ('4', '1', '5+6=？', '{\"0\":\"A\",\"1\":\"B\",\"3\":\"D\"}', '0', '{\"A\":\"10\",\"B\":\"11\",\"C\":\"12\",\"D\":\"11\"}', '1544453330', '1544453330', '1');
INSERT INTO `th_problem` VALUES ('5', '0', '1', '[\"A\"]', '1', '{\"A\":\"2\",\"B\":\"3\",\"C\":\"4\",\"D\":\"5\"}', '1544453378', '1544453378', '1');
INSERT INTO `th_problem` VALUES ('6', '0', '3', '[\"D\"]', '1', '{\"A\":\"3\",\"B\":\"3\",\"C\":\"55\",\"D\":\"555\"}', '1544453389', '1544453389', '1');
