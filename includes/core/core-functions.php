<?php

/**
 * @author Attila
 * @copyright 2011
 * @package core
 */


/**
 * XMLSpecialChars function encode a string like htmlspecialchars.
 * @param string $str
 * @return string
 */
function xmlspecialchars($str)
{
    $ptn = array("&",       "<",      ">");   
    $rpm = array("__AND__", "__LT__", "__GT__");
    return str_replace($ptn, $rpm, $str);
}

/**
 * XMLSpecialCharsDecode function decode a string what is encoded with XMLSpecialChars function.
 * @parm string $str
 * @return string
 */
function xmlspecialchars_decode($str)
{
    $ptn = array("__AND__", "__LT__", "__GT__");   
    $rpm = array("&",       "<",      ">");
    return str_replace($ptn, $rpm, $str); 
}

/**
 * Get time.
 * @return string
 */
function getTime()
{
    global $info;
    
    return date("Y-m-d H:i:s", time()+$info->time);
}

/**
 * Get engine.
 * @return string
 */
function getEngine()
{
    return (isset($_GET["engine"])) ? $_GET["engine"] : "home";
}

/**
 * in_array for multidimensional arrays
 * @param mixed $needle
 * @param array $arr
 * @return boolean
 */
function in_array_m($needle, $arr)
{
    if (is_array($arr) && !is_array(end($arr))){
        if (in_array($needle, $arr)){
            return true;
        } 
        return false;
    }
    foreach($arr as $val){
        if (is_array($val)){
            return in_array_m($needle, $val);
        }
    }
    return false;
}

/**
 * Make request array
 * @param string $engine
 * @return array
 */
function makeRequest($engine)
{
    if (!isset($_GET["module"])){
        return false;
    }
    $query = $_GET["module"];
    
    if (str_replace($engine."/", "*", $query)==$query){
        $query = str_replace($engine, "", $query);
    } else {
        $query = str_replace($engine."/", "", $query);
    }
    
    if (str_replace("/", "*", $query)==$query){
        return array($query);
    }
    
    $args = explode("/", $query);
    
    if (($last=array_pop($args))!=""){
        array_push($args, $last);
    }
    
    return $args;
}

/**
 * email
 * @param string $to
 * @param string $subject
 * @param string $msg
 * @global info
 * @return boolean
 */
function email($to, $subject, $msg) 
{
    global $info;
    $mail = new Mail($info);
    
    if (!$mail->send($to, $info->siteMail, $subject, $msg, $info->time)){
        Error::fatal_error(0, __FILE__, __LINE__, $mail->getResp(), true);
        return false;
    }
    return true;
}


/**
 * get_msg
 * @param string $errno 
 * @param boolean $redirect redirect if $errno isn't good
 * @return string
 */
function get_msg($errno, $redirect=false)
{
    if (!preg_match("/^[0-9]{1}x[0-9a-zA-Z]{6}$/i", $errno) || !defined("E".$errno)){
        $errno = ERROR_INVALIDPARAM;
        
        if ($redirect===true){
            Error::redirect($errno);
        }
    }
    
    return eval("return E".$errno.";");   
}

/**
 * avatar_type
 * @param string $nick
 * @return string
 */
function avatar_type($nick)
{
    if (file_exists(AVATARSPATH.$nick.".png")){
        return "png";
    } elseif (file_exists(AVATARSPATH.$nick.".gif")){
        return "gif";
    } elseif (file_exists(AVATARSPATH.$nick.".jpg")){
        return "jpg";
    } elseif (file_exists(AVATARSPATH.$nick.".jpeg")){
        return "jpeg";
    }
    return false;
}

/**
 * img_type
 * @param $file
 * @return string
 */
function img_type($file)
{
    if (file_exists($file.".png")){
        return "png";
    } elseif (file_exists($file.".jpg")){
        return "jpg";
    } elseif (file_exists($file.".gif")){
        return "gif";
    } elseif (file_exists($file.".jpeg")){
        return "jpeg";
    }
    return false;
}

?>