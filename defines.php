<?php

/**
 * DEFINES FILE.
 * @author Attila
 * @copyright 2011
 */


/**
 * URL constans.
 */
define("URL", "http://".$_SERVER["SERVER_NAME"]."/");

/**
 * ABSPATH constans.
 */
define("ABSPATH", dirname(__FILE__)."/");

/**
 * INCLUDES constans.
 */
define("INCLUDES", ABSPATH."includes/");

/**
 * AJAX CONSTANS.
 */
define("AJAX", INCLUDES."ajax/");

/**
 * CORE constans.
 */
define("CORE", INCLUDES."core/");

/**
 * STORAGE constans.
 */
define("STORAGE", ABSPATH."storage/");

/**
 * CONTENT constans.
 */
define("CONTENT", ABSPATH."content/");

/**
 * THEME constans.
 */
define("THEME", CONTENT."theme/");

/**
 * IMAGES constans.
 */
define("IMAGES", URL."content/images/");

/**
 * AVATARS constans.
 */
define("AVATARS", IMAGES."avatars/");

/**
 * AVATARSPATH constans.
 */
define("AVATARSPATH", CONTENT."images/avatars/");

/**
 * NEWSIMG constans.
 */
define("NEWSIMG", IMAGES."news/");

/**
 * NEWSIMGPATH constans.
 */
define("NEWSIMGPATH", CONTENT."images/news/");


?>