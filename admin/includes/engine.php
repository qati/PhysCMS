<?php

/**
 * ADMIN ENGINE FILE.
 * @author Attila
 * @copyright 2012
 * @package adminEngine
 */


/**
 * Include the engine functions file.
 */
require_once AINCLUDES."engine-functions.php";

/**
 * Include engine constans.
 */
require_once AINCLUDES."engine-consts.php";

/**
 * Include engine messages.
 */
require_once AINCLUDES."engine-msgs.php";

/**
 * $content variable.
 * @var string
 */
$content = "";

/**
 * ENGINE MAIN
 */
switch(isset($_REQUEST["engine"]) ? $_REQUEST["engine"] : false)
{
    default:
        $content .= module_adminHome();
        break;
    case "settings":
        $content .= module_settings();
        break;
    case "cm":
        $content .= module_cmAdmin(makeARequest());
        break;
    case "error":
        $content .= module_error();
        break;
}

?>