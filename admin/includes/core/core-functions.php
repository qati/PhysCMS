<?php

/**
 * @author Attila
 * @copyright 2012
 * @package adminCore
 */


/**
 * FUNCTION makeARequest
 * @return array
 */
function makeARequest()
{
    $args = array();
    if (isset($_POST["engine"])){
        foreach($_POST as $key=>$val){
            if ($key!="engine"){
                $args[$key] = $val;
            }
        }
    } else {
        foreach($_GET as $key=>$val){
            if ($key!="engine"){
                $args[$key] = $val;
            }
        }
    }
    return $args;
}


?>