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
 * Include defines file.
 */
require_once "defines.php";

/**
 * Change dir.
 */
chdir(ABSPATH);

/**
 * Include the init file.
 */
require_once INCLUDES."init.php";

/**
 * Include the engine file.
 */
require_once INCLUDES."engine.php";

/**
 * Include the theme file.
 */
require_once THEME."index.php";

?>