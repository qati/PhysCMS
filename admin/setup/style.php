<?php

/**
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
header("Content-type: text/css; charset=UTF-8");
header("Cache-Control: must-revalidate, maxage=3600");
header("Expires: ".gmdate('D, d M Y H:i:s', time()+3600)."GMT");
  
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
 * Screen css file.
 */
$style[] = "css/screen.css";

/**
 * Ie css file.
 */
if (strpos($_SERVER["HTTP_USER_AGENT"], "MSIE")!==false){
    $style[] = "css/ie.css";
}

/**
 * Style css file.
 */
$style[] = "style.css";

/**
 * UserWall style css file.
 */
$style[] = "userwall.css";

/**
 * Print css files.
 */
foreach($style as $file){
    echo compress(@file_get_contents($file));
}

/**
 * Finish output buffering and send datas to browser.
 */
if (!ini_get('output_buffering')){
    ob_flush();
    ob_end_clean();
}

?>