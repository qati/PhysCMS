<?php

/**
 * EGINE FUNCTIONS FILE.
 * @author Attila
 * @copyright 2011
 * @package engine
 */


/**
 * Error module.
 * @param string $errno
 * @return string
 */
function module_error($errno)
{   
    $str  = "<blockquote class='errormsg'>";
    $str .= get_msg($errno, true);
    $str .= "</blockquote>";
    
    return $str;
}

/**
 * System message module.
 * @param string $code
 * @return string
 */
function module_systemMSG($code)
{
    if (!preg_match("/^[a-zA-Z]{3}([0-9]+)$/i", $code) || !defined("SYSMSG".$code)){
        Error::redirect(ERROR_INVALIDPARAM);
    }
    
    $str  = "<blockquote class='systemmsg'>";

    eval("\$str .= SYSMSG".$code.";");
    
    $str .= "</blockquote>";
    
    return $str;
}

/**
 * Home module
 * @param array $args
 * @return string
 */
function module_home($args)
{
    global $cm, $info;
    $str = "";
    if (!isset($args[0]) || empty($args[0])){
        try {
            $db = $cm->getShortPost();
           
            if (is_array(end($db))){
                foreach($db as $news){
                    $str .= printNews($news);
                }
            } else {
                $str .= printNews($db);
            }
            
        } catch(CMException $e) {
            Error::redirect($e->getErrno());
        }
    } elseif (isset($args[0]) && !empty($args[0]) && (!isset($args[1]) || empty($args[1]))) {
        try {
            $db = $cm->getShortPost(1, $cm->getCatID($args[0]));
            
            if (is_array(end($db))){
                foreach($db as $news){
                    $str .= printNews($news, true);
                }
            } else {
                $str .= printNews($db, true);
            }
            
        } catch(CMException $e) {
            Error::redirect($e->getErrno());
        }
    } elseif (isset($args[0], $args[1]) && !empty($args[0]) && !empty($args[1])){
        try {
            $tmp               = $cm->getPost($args[1], true);
            $info->title       = $tmp["title"];
            $info->keywords    = $tmp["keywords"];
            $info->description = $tmp["summary"];
            $str              .= printFullNews($tmp);
        } catch(CMException $e){
            Error::redirect($e->getErrno());
        }
    }
    return $str;
}

/**
 * Article module.
 * @param array $args
 * @return string
 */
function module_article($args)
{
    global $cm, $info;
    $str = "";
    
    if (!isset($args[1]) || empty($args[1])){
        try {
            $db = $cm->getShortPost(1, false, false);
            
            if (is_array(end($db))){
                foreach($db as $news){
                    $str .= printNews($news);
                }
            } else {
                $str .= printNews($db);
            }
            
        } catch(CMException $e) {
            Error::redirect($e->getErrno());
        }
    } elseif (isset($args[1]) && !empty($args[1]) && (!isset($args[2]) || empty($args[2]))) {
        try {
    
            $db = $cm->getShortPost(1, $cm->getCatID($args[1], false, true), false);
                
            if (is_array(end($db))){
                foreach($db as $news){
                    $str .= printNews($news, true);
                }
            } else {
                $str .= printNews($db, true);
            }
            
        } catch(CMException $e) {
            Error::redirect($e->getErrno());
        }
    } elseif (isset($args[1], $args[2]) && !empty($args[2]) && !empty($args[2])){
        try {
            $tmp               = $cm->getPost($args[2], true);
            $info->title       = $tmp["title"];
            $info->keywords    = $tmp["keywords"];
            $info->description = $tmp["summary"];
            $str              .= printFullNews($tmp);
        } catch(CMException $e){
            Error::redirect($e->getErrno());
        }
    }
    
    return $str;
}


/**
 * Community module.
 * @param string $module
 * @return string
 */
function module_community($module, $args)
{
    global $com;
    $str = "";
    
    switch($module)
    {
        case "login":
            if (isset($_POST["nick"], $_POST["pass"]) && 4<=strlen($_POST["nick"]) && strlen($_POST["nick"])<20
                                                      && 6<=strlen($_POST["pass"]) && strlen($_POST["pass"])<32){
                try {
                    $com->login($_POST["nick"], $_POST["pass"]);
                    if (!$com->isUser()){
                        throw new ComException(COM_NOLOGIN);
                    }
                    System::redirect("community/");
                } catch(ComException $e){
                    Error::redirect($e->getErrno());
                }
            } else {
                Error::redirect(COM_NOLOGIN);
            }
            break;
        case "logout":
            $com->logout();
            System::redirect("");
            break;
        case "chat":
            if ($com->isUser()){
                $str .= "<script type='text/javascript'>com.chat.getPosts(true);</script>";
            } else {
                System::redirect("rendszeruzenet/".COM_CHAT_ACCESSDENNIED);
            }
            break;
        case "user":
            if (isset($args[1]) && $args[1]=="profile"){
                if (isset($args[2]) && preg_match("/^[0-9]+$/i", $args[2])){
                    try {
                        $str .= userProfile($com->userProfile($args[2]));
                    } catch(ComException $e){
                        Error::redirect($e->getErrno());
                    }
                } else {
                    if ($com->isUser()){
                        try {
                            $str .= userProfile($com->userProfile());
                        } catch(ComException $e){
                            Error::redirect($e->getErrno());
                        }
                    } else {
                        System::redirect("rendszeruzenet/".COM_ACCESS_DENNIED);
                    }
                }
            } elseif (isset($args[1]) && $args[1]=="friendrequests"){
                $str .= userFriendRequests($com->attr("friendRequest"));
            } elseif (isset($args[1]) && $args[1]=="list"){
                try {
                    $str .= userList($com->userList());
                } catch(ComException $e){
                    Error::redirect($e->getErrno());
                }
            } elseif (isset($args[1]) && $args[1]=="avatar"){
                if (isset($args[2]) && $args[2]=="save" && isset($_POST["save"], $_FILES["avatar"])){
                    if ($_FILES["avatar"]["error"]>0){
                        Error::redirect(COM_NOUPLOAD);
                        return;
                    }
                    $avatar = $_FILES["avatar"];
                    
                    try {
                        $com->addAvatar($avatar["type"], $avatar["size"], $avatar["tmp_name"]);
                        System::redirect("rendszeruzenet/".COM_USER_AVATARUPLOAD_SUCCESS);
                    } catch(ComException $e) {
                        Error::redirect($e->getErrno());
                    }
                   
                } else {
                    $str .= addAvatarForm();
                }
            }
            break;
        case "reg":
            if (isset($args[1]) && $args[1]=="activate"){
                if (!isset($args[2], $args[3])){
                    Error::redirect(COM_REGACTIVATIONUSEFULLLLINK);
                } else {
                    if ($com->activateNewUser($args[2], $args[3])){
                        System::redirect("rendszeruzenet/".COM_REG_ACTIVATION_SUCCESS);
                    } else {
                        Error::redirect(COM_REGACTIVATIONFALIED);
                    }
                }
            } else {
                $str .= regform();
            }
            break;
        case "elfelejtettjelszo":
            $str .= lostPassForm();
            break;
        case "ujjelszo":
        {
            if (isset($args[1], $args[2]) && !empty($args[1]) && !empty($args[2])){
                try {
                    $key = $com->genPass($args[1], $args[2]);
                    if (!email($args[1], "Új jelszó", "Új jelszó: ".$key)){
                                
                        Error::fatal_error(ERRAJAX_NOEMAILSENT,__FILE__,__LINE__,"Falied: send mail!",true);
                        Error::redirect(ERRAJAX_NOMAILSENT);
                    } else {
                        $str .= "Új jelszó elküldve a megadott e-mail címre!";
                    }
                }catch(COMException $e){
                    Error::redirect($e->getErrno());
                }
            } else {
                Error::redirect(COM_INVALIDINPUT);
            }
            break;
        }
        case "forum":
            $str .= "forum<br>";
            break;
        default:
            if ($com->isUser()){
                try {
                    $str .= UserWall($com->userWall(COM_USERWALL_GETPOSTS));
                } catch(ComException $e){
                    if ($e->getErrno()==COM_NORES){
                        Error::redirect(COM_NORES);
                        return;
                    }
                    $str .= module_systemMSG(COM_USERWALL_NODATA);
                    $str .= UserWall(false);
                }
            } else {
                System::redirect("community/reg");
            }
            break;
    }
    
    return $str;
}

?>