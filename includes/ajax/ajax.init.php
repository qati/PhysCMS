<?php

/**
 * AJAX Init file.
 * @author Attila
 * @copyright 2011
 * @package ajax
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

/**
 * Include ajax constans file.
 */
require_once AJAX."ajax.consts.php";

/**
 * Include short messages file.
 */
require_once AJAX."ajax.msgs.php";


//---------------------------------CORE INITIALIZATION---------------------------------\\
$db   = new MySQL(DBHost, DBUser, DBPass, DBName, DBLogFile);
$sys  = new System($db, LogFile, BANNIP);
$info = new Info($db, $sys, TBOptions);
$com  = new Community($db, $info, ComLogFile, TBUsers, TBUserWall);
$cm   = new ContentManager($db, $info, $com, array(TBCMCat, TBCMPost, TBCMComment), CMLogFile);
$ajax = NULL;
try {
    $ajax = new Ajax;    
} catch(Error $e){
    if ($e->getErrno()==ERRAJAX_NOAUTH || $e->getErrno()==ERRAJAX_INVALIDREQUEST){
        $e->log();
        die(get_msg($e->getErrno()));
    } else {
        die(get_msg(ERRAJAX_ERROR));
    }
}

if (!$ajax){
    die(get_msg(ERRAJAX_ERROR));
}

?>