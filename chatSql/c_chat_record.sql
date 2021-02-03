/*
 Navicat Premium Data Transfer

 Source Server         : 192.168.33.13
 Source Server Type    : MySQL
 Source Server Version : 80021
 Source Host           : 192.168.33.13:3306
 Source Schema         : laravel_admin

 Target Server Type    : MySQL
 Target Server Version : 80021
 File Encoding         : 65001

 Date: 03/02/2021 10:02:02
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for c_chat_record
-- ----------------------------
DROP TABLE IF EXISTS `c_chat_record`;
CREATE TABLE `c_chat_record`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `friend_id` int NOT NULL DEFAULT 0 COMMENT '是群聊消息记录的话 此id为0',
  `group_id` int NOT NULL DEFAULT 0 COMMENT '如果不为0说明是群聊',
  `content` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '',
  `time` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 96 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '聊天记录' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
