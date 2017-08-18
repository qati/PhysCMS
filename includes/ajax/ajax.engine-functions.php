<?php

/**
 * AJAX Engine functions file.
 * @author Attila
 * @copyright 2011
 * @package ajax
 * @subpackage engine
 */



//================================================Content manager================================================\\

/**
 * module_contentmanager
 * @global $ajax
 * @global $cm
 * @global $com
 */
function module_contentmanager()
{
    global $ajax, $cm, $com;
    
    if ($ajax->getModule()=="post"){
        if ($ajax->request["action"]="showPost" && isset($ajax->request["id"])){
            try {
                $db = $cm->getPost($ajax->request["id"]);
                
                if (isset($ajax->request["incat"])){
                    $url = "/".$db["url"];
                } else {
                    $url = "/";
                    if (!isset($ajax->request["isArticle"])){
                        $url .= "home/";
                    }
                    $url .= $cm->getCatURL($cm->getCatID($db["subcat"], true))."/".$db["url"];
                }
                
                $content = printFullNews($db);
                
                $ajax->response  = $url."__SELECTOR__".$db['title']."__SELECTOR__".$db['keywords'];
                $ajax->response .= "__SELECTOR__".$db['summary']."__SELECTOR__".$content;
                
            } catch(CMException $e){
                $ajax->response = get_msg($e->getErrno());
            }
            
            
        } else {
            $ajax->response = get_msg(ERRAJAX_INVALIDREQUEST);
        }
    } elseif ($ajax->getModule()=="comment"){
        if ($ajax->request["action"]=="get" && isset($ajax->request["page"], $ajax->request["id"])){
            try {
                $ajax->response = printComments($ajax->request["id"], $com->isUser(), $ajax->request["page"], true);
            }catch(CMException $e){
                $ajax->response = get_msg($e->getErrno());
            }
        } elseif ($ajax->request["action"]=="add" && isset($ajax->request["msg"], $ajax->request["id"])){
            if (empty($ajax->request["msg"]) || strlen($ajax->request["msg"])<5){
                $ajax->response = "A hozzászólásnak legalább 5 karakter hosszúnak kell lennie!";
            } else {
                try {
                    $cm->addComment($ajax->request["id"], $ajax->request["msg"]);
                    $ajax->response = "";
                }catch(CMException $e){
                    $ajax->response = get_msg($e->getErrno());
                }
            }
        } else {
            $ajax->response = get_msg(ERRAJAX_INVALIDREQUEST);
        }
    } else {
        $ajax->response = get_msg(ERRAJAX_INVALIDREQUEST);
    }
}


//==================================================Community==================================================\\

/**
 * module_community.
 * @global $ajax
 * @global $community
 */
function module_community()
{
    global $ajax, $com, $info;
    
    if ($ajax->getModule()=="user"){
        if ($ajax->request["action"]=="addFriend" && isset($ajax->request["id"])){
            try {
                $com->addFriend($ajax->request["id"]);
                $ajax->response = Ajax::get_msg(AJAX_COM_USER_ADDFRIEND_SUCCESS);
            } catch(ComException $e){
                $ajax->response = Ajax::get_msg(AJAX_COM_USER_ADDFRIEND_FALIED);
            }
        } elseif ($ajax->request["action"]=="confirmFriend" && isset($ajax->request["id"])){ 
            try {
                $com->confirmFriend($ajax->request["id"]);
                $ajax->response = userFriendRequests($com->attr('friendRequest'), true);
            } catch(ComException $e){
                $ajax->response = get_msg($e->getErrno());
            }
        } elseif ($ajax->request["action"]=="newpass" && isset($ajax->request["rcf"], $ajax->request["rrf"], $ajax->request["email"])){
            $rcf = $ajax->request["rcf"];
            $rrf = $ajax->request["rrf"];
            $resp = recaptcha_check_answer(RECAPTCHA_PRIVATEKEY, $_SERVER["REMOTE_ADDR"], $rcf, $rrf);
            
            if ($resp->is_valid) {
                try{
                    $code  = $com->newPass($ajax->request["email"]);
                    $link  = "<a href='";
                    $link .= $info->siteurl."community/ujjelszo/".$ajax->request['email']."/".$code;
                    $link .= "'>";
                    $link .= $info->siteurl."community/ujjelszo/".$ajax->request['email']."/".$code;
                    $link .= "</a>";
                    
                    if (!email($ajax->request["email"], "Új jelszó", str_replace("[LINK]", $link, $info->newPass))){
                                
                        Error::fatal_error(ERRAJAX_NOEMAILSENT,__FILE__,__LINE__,"Falied: send mail!",true);
                        $ajax->response = lostPassForm(get_msg(ERRAJAX_NOEMAILSENT));
                    } else {
                        $ajax->response = "Megerősítő e-mail elküldve!";
                    }
                }catch(COMException $e){
                    if ($e->getErrno()==COM_NORES){
                        $ajax->response = lostPassForm(get_msg($e->getErrno()));
                        return;
                    }
                    $ajax->response = lostPassForm("Nincs ilyen e-mail cím!");
                }
            } else {
                $ajax->response = lostPassForm("Rossz elenőrző kód!");
            }
        } else {
            $ajax->response =get_msg(ERRAJAX_INVALIDREQUEST);
        }
    } elseif ($ajax->getModule()=="userwall"){
        if ($ajax->request["action"]=="add" && isset($ajax->request["post"]) && $com->isUser()){
            try {
                $com->userWall(COM_USERWALL_ADDPOST, $ajax->request["post"]);
                
                $ajax->response = userWall($com->userWall(COM_USERWALL_GETPOSTS));
                
            } catch(ComException $e) {
                $ajax->response = get_msg($e->getErrno());
            }
            return;
        } elseif ($ajax->request["action"]=="changePage" && isset($ajax->request["page"]) && $com->isUser()){
            try {
                $page = $ajax->request["page"];
                $ajax->response = userWall($com->userWall(COM_USERWALL_GETPOSTS, $page), $page);
            } catch(ComException $e){
                $ajax->response = get_msg($e->getErrno());
            }
        } else {
            $ajax->response =get_msg(ERRAJAX_INVALIDREQUEST);
        }
    } elseif ($ajax->getModule()=="reg"){
        if ($ajax->request["action"]=="check"){
            if (isset($ajax->request["nick"])){
                try {
                    if ($com->check(CHECK_NICK, $ajax->request["nick"])){
                        $ajax->response = "1";
                    } else {
                        $ajax->response = "0";
                    }
                } catch(ComException $e) {
                    $ajax->response = Ajax::get_msg(AJAX_COM_REG_CHECK_NICK);
                }
            } elseif (isset($ajax->request["email"])){
                try {
                    if ($com->check(CHECK_EMAIL, $ajax->request["email"])){
                        $ajax->response = "1";
                    } else {
                        $ajax->response = "0";
                    }
                } catch(ComException $e){
                    $ajax->response = Ajax::get_msg(AJAX_COM_REG_CHECK_EMAIL);
                }
            } else {
                $ajax->response =get_msg(ERRAJAX_INVALIDREQUEST);
            }
        } elseif ($ajax->request["action"]=="saveReg"){
            $rcf = $ajax->request["rcf"];
            $rrf = $ajax->request["rrf"];
            $resp = recaptcha_check_answer(RECAPTCHA_PRIVATEKEY, $_SERVER["REMOTE_ADDR"], $rcf, $rrf);
            
            if ($resp->is_valid) {
                
                if (isset($_SESSION["newUserDatas"]) && !empty($_SESSION["newUserDatas"])){
                    $ajax->request = $_SESSION["newUserDatas"];
                    unset($_SESSION["newUserDatas"]);
                }
                    
                try {
                           
                    $res = $com->newUser($ajax->request["nick"], $ajax->request["name"], $ajax->request["pass"],
                                        $ajax->request["email"], $ajax->request["hideEmail"], $ajax->request["sex"],
                                        $ajax->request["bdate"]);
                            
                    $link  = "<a href='";       
                    $link .= $info->siteurl."community/reg/activate/".$res['email']."/".$res['key'];
                    $link .= "'>";
                    $link .= $info->siteurl."community/reg/activate/".$res['email']."/".$res['key'];
                    $link .= "</a>";
                            
                    if (!email($res["email"], Ajax::get_msg(AJAX_COM_REG_SR_EMAILSUBJECT),
                        str_replace("[LINK]", $link, $info->activationEmail))){
                                
                        Error::fatal_error(ERRAJAX_NOEMAILSENT,__FILE__,__LINE__,"Falied: send mail!",true);
                        $ajax->response = get_msg(ERRAJAX_NOEMAILSENT);
                    } else {
                        $ajax->response = Ajax::get_msg(AJAX_COM_REG_SR_SUCCESS);
                    }
                            
                } catch(ComException $e) {
                    if ($e->getErrno()==COM_NOINSERT){
                        $e->log();
                        $ajax->response = get_msg(ERRAJAX_NOSAVE);
                    } else {
                        $ajax->response = get_msg(ERRAJAX_INVALIDINPUT);
                    }
                }
                
            } else {
                if (!isset($_SESSION["newUserDatas"]) || empty($_SESSION["newUserDatas"])){
                    unset($ajax->request["rcf"]);
                    unset($ajax->request["rrf"]);
                    unset($ajax->request["action"]);
                    $_SESSION["newUserDatas"] = $ajax->request;
                }
                $ajax->response = newReCaptcha();
            }
        } else {
            $ajax->response = get_msg(ERRAJAX_INVALIDREQUEST);
        }   
    }
    
    return;
}

?>