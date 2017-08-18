<?php

/**
 * ADMIN INIT FILE.
 * @author Attila
 * @copyright 2012
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
 * Include admin core file.
 */
require_once ACORE."core.php";

/**
 * Include the functions file.
 */
require_once INCLUDES."functions.php";

/**
 * Include admin functions file.
 */
require_once AINCLUDES."functions.php";

/**
 * Include the settings file.
 */
require_once INCLUDES."settings.php";


//---------------------------------ADMIN CORE INITIALIZATION---------------------------------\\
$db   = new MySQL(DBHost, DBUser, DBPass, DBName, DBLogFile);
$sys  = new adminSystem($db, LogFile, BANNIP, false);
$info = new Info($db, $sys, TBOptions);
$com  = new adminCommunity($db, $info, ComLogFile, TBUsers, TBUserWall);
$cm   = new adminContentManager($db, $info, $com, array(TBCMCat, TBCMPost, TBCMComment), CMLogFile);

?>