<?php

/**
 * JavaScript generator file.
 * @author Attila
 * @copyright 2011
 */


/**
 * Function: compressor
 * @param string $buffer
 * @return string
 */
function compress($buffer) 
{
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
    return $buffer;
}


/**
 * Send header.
 */
header("Content-type: text/javascript; charset=UTF-8");

/**
 * Start output buffering and gzipping.
 */
if (!ini_get('output_buffering')){
    ob_start("ob_gzhandler");
}

/**
 * Reset the file's directory.
 */
chdir(dirname($_SERVER['SCRIPT_FILENAME']));

/**
 * SCRIPT constans.
 */
define("SCRIPT", dirname(__FILE__)."/"."includes/js/");

/**
 * STORAGE constans.
 */
define("STORAGE", dirname(__FILE__)."/storage/");

/**
 * Include settings file.
 */
require_once dirname(__FILE__)."/includes/settings.php";


/**
 * JQuery JavaScript lib.
 */
$script[] = "lib/jquery-1.7.1.min.js";

/**
 * ReCaptcha JavaScript lib.
 */
$script[] = "lib/recaptcha_ajax.js";

/**
 * Constans js file.
 */
$script[] = "constans.js";

/**
 * Main functions.
 */
$script[] = "functions.js";

/**
 * Ajax javascript file.
 */
 $script[] = "objects/ajax.js";
 
/**
 * Navigator javascript file.
 */
$script[] = "objects/navigator.js";

/**
 * Community javacript file.
 */
$script[] = "objects/community.js";

/**
 * Content manager javascript file.
 */
$script[] = "objects/contentManager.js";

/**
 * Main JavaScript file.
 */
$script[] = "main.js";


/**
 * Open script.js.
 */
$js = fopen(dirname(__FILE__)."/script.js", "w");
if (!is_resource($js)){
    echo "Nem sikerült megnyitni a script.js filet!";
}

/**
 * Print JavaScript files.
 */
foreach($script as $file){
    if (!fputs($js, @file_get_contents(SCRIPT.$file))){
        echo "Nem sikerült a $file js filet kiírni!";
    }
}

/**
 * Close script.js
 */
if (fclose($js)){
    echo "script.js file létrehozva!";
}


/**
 * Finish output buffering and send datas to browser.
 */
if (!ini_get('output_buffering')){
    ob_flush();
    ob_end_clean();
}
?>