<?php

/**
 * SETTINGS FILE.
 * @author Attila
 * @copyright 2011
 */


/**
 * DBHost constans.
 */
define("DBHost", "DBHOST");

/**
 * DBUser constans.
 */
define("DBUser", "DBUSER");

/**
 * DBPass constans.
 */
define("DBPass", "DBPASS");

/**
 * DBName constans.
 */
define("DBName", "DBNAME");


//====================================DATABASE TABLES====================================\\

/**
 * Options table.
 */
define("TBOptions", "options");

/**
 * Users table.
 */
define("TBUsers", "users");

/**
 * UserWall table.
 */
define("TBUserWall", "userwall");

/**
 * TBCMCat table.
 */
define("TBCMCat", "post_cat");

/**
 * TBCMPost table.
 */
define("TBCMPost", "posts");

/**
 * TBCMComment table.
 */
define("TBCMComment", "post_comment");


//====================================XML FILES====================================\\

/**
 * DBLogFile constans.
 */
define("DBLogFile", STORAGE."mysql_error_log.xml");

/**
 * LogFile constans.
 */
define("LogFile", STORAGE."error_log.xml");

/**
 * ComLogFile constans.
 */
define("ComLogFile", STORAGE."community_log.xml");

/**
 * CMLogFile constans.
 */
define("CMLogFile", STORAGE."contentmanager_log.xml");


//====================================OTHER FILES====================================\\

/**
 * BANNIP constans.
 */
define("BANNIP", STORAGE."bann.ip");


//==================================reCaptchaLib==================================\\

/**
 * RECAPTCHA_PUBLICKEY const.
 */
define("RECAPTCHA_PUBLICKEY", "PUBLICKEY");

/**
 * RECAPTCHA_PRIVATEKEY const.
 */
define("RECAPTCHA_PRIVATEKEY", "PRIVATEKEY");


//==================================SECURITY KEYS==================================\\

/**
 * AJAX_SECURITYKEY constans.
 */
define("AJAX_SECURITYKEY", "KEY");

?>