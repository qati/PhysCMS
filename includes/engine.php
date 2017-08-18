<?php

/**
 * ENGINE FILE
 * @author Attila
 * @copyright 2011
 * @package engine
 */


/**
 * Include the engine functions file.
 */
require_once INCLUDES."engine-functions.php";

/**
 * Include engine constans.
 */
require_once INCLUDES."engine-consts.php";

/**
 * Include engine messages.
 */
require_once INCLUDES."engine-msgs.php";

/**
 * $content variable.
 * @var string
 */
$content = "";

/**
 * ENGINE MAIN
 */
switch(isset($_GET["engine"]) ? $_GET["engine"] : false)
{
    default:
        $content .= module_home(makeRequest("home"));
        break;
    case "systemmsg":
        $content .= module_systemMSG(isset($_GET["code"]) ? $_GET["code"] : false);
        break;
    case "error":
        $content .= module_error(isset($_GET["code"]) ? $_GET["code"] : false);
        break;
    case "content":
        $content .= module_article(makeRequest("content"));
        break;
    case "community":
        $args     = makeRequest("community");
        $content .= module_community(isset($args[0]) ? $args[0] : false, $args);
        break;
}

?>