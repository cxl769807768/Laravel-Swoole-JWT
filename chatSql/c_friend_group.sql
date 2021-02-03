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

 Date: 03/02/2021 10:02:22
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for c_friend_group
-- ----------------------------
DROP TABLE IF EXISTS `c_friend_group`;
CREATE TABLE `c_friend_group`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `groupname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
