<?php

/**
 * @author Attila
 * @copyright 2011
 * @version 1.0
 */


/**
 * Define the system version.
 */
define("WCM", "1.0");
 

/**
 * Define the admin system version.
 */
define("WCM_ADMIN", "1.0");

/**
 * Include admin defines file.
 */
require_once "defines.php";

/**
 * Include defines file.
 */
require_once ABSPATH."defines.php";

/**
 * Change dir.
 */
chdir(ADMINPATH);

/**
 * Include init file.
 */
require_once AINCLUDES."init.php";


/**
 * Include admin engine file.
 */
require_once AINCLUDES."engine.php";

/**
 * Include admin theme file.
 */
require_once THEME."index.php";

?>