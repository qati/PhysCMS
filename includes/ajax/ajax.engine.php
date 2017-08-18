<?php

/**
 * AJAX Engine file.
 * @author Attila
 * @copyright 2011
 * @package ajax
 * @subpackage engine
 */


/**
 * Include the engine functions file.
 */
require_once AJAX."ajax.engine-functions.php";

/**
 * Engine Main.
 */
switch($ajax->getEngine())
{
    case "community":
        module_community();
        break;
    case "contentmanager":
        module_contentmanager();
        break;
    default:
        $ajax->response = get_msg(ERRAJAX_INVALIDREQUEST);
        break;
}

?>