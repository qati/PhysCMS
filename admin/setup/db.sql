--
--author Attila
--

--------------------------------------------------------------------------------------
--create database
CREATE DATABASE IF NOT EXISTS `wcm` CHARSET=utf8 COLLATE=utf8_unicode_ci;

--------------------------------------------------------------------------------------
--create options table
CREATE TABLE IF NOT EXISTS `options` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `option_name` VARCHAR(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `option_value` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `autoload` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '0'
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

INSERT INTO `options` VALUES(null, 'siteurl', 'http://animecom.tk/', '1');

INSERT INTO `options` VALUES(null, 'siteMail', 'admin@animecom.tk', '0');

INSERT INTO `options` VALUES(null, 'title', 'AnimeCom', '1');

INSERT INTO `options` VALUES(null, 'keywords', 'anime, manga, japán, közösség', '1');

INSERT INTO `options` VALUES(null, 'description', 'Animés közösségi oldal', '1');

INSERT INTO `options` VALUES(null, 'menu', '', '0');

INSERT INTO `options` VALUES(null, 'submenu', '', '0');

INSERT INTO `options` VALUES(null, 'footer', '', '1');

INSERT INTO `options` VALUES(null, 'activationEmail', '[LINK]', '0');

INSERT INTO `options` VALUES(null, 'postPerPage', '10', '0');

INSERT INTO `options` VALUES(null, 'commentsPerPage', '10', '0');

INSERT INTO `options` VALUES(null, 'avatarMaxSize', '1000000', '0');

INSERT INTO `options` VALUES(null, 'userLevel', '', '0');

INSERT INTO `options` VALUES(null, 'defaultUserOptions', '', '0');

INSERT INTO `options` VALUES(null, 'userListMax', '30', '0');

INSERT INTO `options` VALUES(null, 'smtp_server', 'ssl://smtp.gmail.com', '0');

INSERT INTO `options` VALUES(null, 'smtp_port', '465', '0');

INSERT INTO `options` VALUES(null, 'smpt_user', 'bmFydW1pYXl1bXUuOTVAZ21haWwuY29t', '0');

INSERT INTO `options` VALUES(null, 'smpt_pass', 'JlF2UUR3aFMkNzl6RGtOUldPTCY4TWNeWWJwSzViV1Q=', '0');

INSERT INTO `options` VALUES(null, 'time', '25200', '1');

INSERT INTO `options` VALUES(null, 'newPass', '', '0');



--create users tables
/**
 * userLevel:  0->banned, 1->registred but not activated, 2->member
 */
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nick` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `name` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `pass` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `email` VARCHAR(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `bdate` DATE, 
    `regDate` DATETIME,
    `lastVisit` DATETIME DEFAULT NULL,
    `userLevel` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '1',
    `options` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `userwall`(
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT UNSIGNED NOT NULL,
    `add_id` INT UNSIGNED NOT NULL,
    `post` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci,
    `post_date` DATETIME,
    `comments` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;


--create content manager tables
/**
 * Visibility: 0->invisible, 1->visible to everyone, 2->visible only to users
 * Type: News (0-2): 0->category, not subcat, 1->subcat, users can post 2->subcat, users can't post
 * Type: Posts (3-5): 3->category, not subcat, 4->subcat, users can post, 5->subcat, users can't post
 * Cat null: it is category, not subcategory
 */
CREATE TABLE IF NOT EXISTS `post_cat` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `cat` INT UNSIGNED DEFAULT NULL,
    `url` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `visibility` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '1',
    `type` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '0',
    `num` INT UNSIGNED DEFAULT '0',
    `created` DATETIME
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

/**
 * Type: 0->hidden, 1->news, comment; 2->news, no comment; 3->important news; 4->important news, no comment 
 */
CREATE TABLE IF NOT EXISTS `posts` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `subcat` INT UNSIGNED NOT NULL,
    `url` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `keywords` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `summary` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `content` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `opened` INT UNSIGNED DEFAULT '0',
    `type` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '0',
    `created` DATETIME,
    `comments` INT UNSIGNED DEFAULT '0' 
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `post_comment` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `post_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL, 
    `msg` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `created` DATETIME
)CHARACTER SET utf8 COLLATE utf8_unicode_ci;