<?php

/**
 * INIT FILE.
 * @author Attila
 * @copyright 2011
 */

/**
 * Header.
 */
header("Content-Type: text/html; charset=UTF-8");

/**
 * Include the core file.
 */
require_once CORE."core.php";

/**
 * Include the functions file.
 */
require_once INCLUDES."functions.php";

/**
 * Include the settings file.
 */
require_once INCLUDES."settings.php";

/**
 * Include recaptcha lib.
 */
require_once INCLUDES."recaptchalib.php";


//---------------------------------CORE INITIALIZATION---------------------------------\\
$db   = new MySQL(DBHost, DBUser, DBPass, DBName, DBLogFile);
$sys  = new System($db, LogFile, BANNIP);
$info = new Info($db, $sys, TBOptions);
$com  = new Community($db, $info, ComLogFile, TBUsers, TBUserWall);
$cm   = new ContentManager($db, $info, $com, array(TBCMCat, TBCMPost, TBCMComment), CMLogFile);

?>