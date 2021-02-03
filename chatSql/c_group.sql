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

 Date: 03/02/2021 10:02:31
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for c_group
-- ----------------------------
DROP TABLE IF EXISTS `c_group`;
CREATE TABLE `c_group`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL COMMENT '群组所属用户id,群主',
  `groupname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '群名',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10013 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '群组' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
