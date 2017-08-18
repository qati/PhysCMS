<?php

/**
 * DEFINES FILE
 * @author Attila
 * @copyright 2011
 */


/**
 * ABSPATH constans.
 */
define("ABSPATH", str_replace("admin", "", realpath(".")));

/**
 * ADMINPATH constans.
 */
define("ADMINPATH", dirname(__FILE__)."/");

/**
 * AINCLUDES constans.
 */
define("AINCLUDES", ADMINPATH."includes/");

/**
 * ACORE constans.
 */
define("ACORE", AINCLUDES."core/");

/**
 * ACONTENT constans.
 */
define("ACONTENT", ADMINPATH."content/");

/**
 * ATHEME constans.
 */
define("ATHEME", ACONTENT."theme/");


?>