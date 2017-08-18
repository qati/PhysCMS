<?php

/**
 * ADMIN ENGINE FUNCTIONS.
 * @author Attila
 * @copyright 2012
 * @package adminEngine
 */


/**
 * ADMIN HOME.
 * @return string
 */
function module_adminHome()
{
    //kiirja az uj kommenteket
   return "kezdo admin lap";
}

/**
 * ADMIN CONTENT MANAGER
 * @global $cm
 * @param array $args
 * @return string
 */
function module_cmAdmin($args)
{
    global $cm;
    $str = "";
    
    switch(isset($args["module"]) ? $args["module"] : null)
    {
        default:
        {
            $str .= "tartalom";
            break;
        }
        case "newcat":
        {
            if (isset($args["add"])){
                try {
                    $cm->newCat($args["title"], $args["url"], $args["visibility"], $args["type"]);
                    $str .= "Kategória sikeresen hozzáadva!";
                }catch(CMException $e){
                    $str .= newCatForm(get_error($e->getErrno()));
                }
            } else {
                $str .= newCatForm();
            }
            break;   
        }
        case "editcat":
        {
            $cats = array();
            try{
                $cats = $cm->getAllCat(3);
            }catch(CMException $e){
                Error::redirect($e->getErrno());
            }
            
            if (isset($args["edit"])){
                try{
                    $cm->updateCat($args["id"],$args["title"], $args["url"], $args["visibility"], $args["type"]);
                    $str .= "Kategória módosítva!";
                }catch(CMException $e){
                    $str .= get_error($e->getErrno());
                    $str .= "Kategória módosítása sikertelen!";
                }
            } elseif (isset($args["show"])){
                if ($args["muvelet"]=="0"){
                    try {
                        $db = $cm->adminGet("cat", $args["cat"]);
                        $str .= editCatForm($db);
                    }catch(CMException $e){
                        $str .= allCatToEditForm($cats, get_error($e->getErrno()));
                    }
                } elseif ($args["muvelet"]=="1"){
                    try{
                        $cm->delete("cat", $args["cat"]);
                        $str .= "Kategória sikeresen törülve!";
                    }catch(CMException $e){
                        $str .= get_error($e->getErrno());
                    }
                } else {
                    $str .= allCatToEditForm($cats, get_error(CM_INVALIDINPUT));
                }
            } else {
                $str .= allCatToEditForm($cats);
            }
            
            break;
        }
        case "newsubcat":
        {
            $cats = array();
            try{
                $cats = $cm->getAllCat(3);
            }catch(CMException $e){
                Error::redirect($e->getErrno());
            }
            if (isset($args["add"])){
                try {
                    $cm->newSubCat($args["cat"], $args["title"], $args["url"], $args["visibility"], $args["type"]);
                    $str .= "Alkategória sikeresen hozzáadva!";
                }catch(CMException $e){
                    $str .= newSubcatForm($cats, get_error($e->getErrno()));
                }
            } else{
                $str .= newSubcatForm($cats);
            }
            break;
        }
        case "editsubcat":
        {
            $cats = array();
            $subcats = array();
            try{
                $cats = $cm->getAllCat(3);
                $subcats = $cm->getAllSubCat(false, true);
            }catch(CMException $e){
                Error::redirect($e->getErrno());
            }
            
            if (isset($args["edit"])){
                try{
                    $cm->updateSubCat($args["id"],$args["cat"],$args["title"],$args["url"],$args["visibility"],$args["type"]);
                    $str .= "Alkategória módosítva!";
                }catch(CMException $e){
                    $str .= get_error($e->getErrno());
                    $str .= "Alkategória módosítása sikertelen!";
                }
            } elseif (isset($args["show"])){
                if ($args["muvelet"]=="0"){
                    try {
                        $db   = $cm->adminGet("cat", $args["subcat"]);
                        $str .= editSubCatForm($cats, $db);
                    }catch(CMException $e){
                        $str .= allSubCatToEditForm($cats, get_error($e->getErrno()));
                    }
                } elseif ($args["muvelet"]=="1"){
                    try{
                        $cm->delete("cat", $args["subcat"]);
                        $str .= "Alkategória sikeresen törülve!";
                    }catch(CMException $e){
                        $str .= get_error($e->getErrno());
                    }
                } else {
                    $str .= allSubCatToEditForm($cats, get_error(CM_INVALIDINPUT));
                }
            } else {
                $str .= allSubCatToEditForm($subcats);
            }
            
            break;
        }
        case "newpost":
        {
            $subcats = array();
            try{
                $subcats = $cm->getAllSubCat(false, true);
            }catch(CMException $e){
                Error::redirect($e->getErrno());
            }
            
            if (isset($args["add"])){
                $id = -1;
                try{
                    $cm->newPost($args["subcat"], $args["title"], $args["url"], $args["keywords"], $args["summary"], $args["content"], $args["type"]);
                    $id = $cm->getPostID($args["url"]);
                    $str .= "Cikk/hír hozzáadva!";
                }catch(CMException $e){
                    $str .= get_error($e->getErrno());
                }
                if ($_FILES["img"]["error"] > 0){
                    $str .= newPostForm($subcats, $_FILES["img"]["error"]);
                } else {
                    move_uploaded_file($_FILES["img"]["tmp_name"], NEWSIMGPATH."summary/".$id.".".str_replace("image/", "", $_FILES["img"]["type"]));
                }
            } else {
                $str .= newPostForm($subcats);
            }
            break;
        }
        case "editpost":
        {
            if (isset($args["edit"])){
                try{
                    $cm->editPost($args["id"], $args["subcat"], $args["title"], $args["url"], $args["keywords"], $args["summary"], $args["content"], $args["type"]);
                    $str .= "Cikk/hír módosítva!";
                }catch(CMException $e){
                    $str .= get_error($e->getErrno());
                }
            }elseif (isset($args["select"])){
                if ($args["select"]=="Törül"){
                    try{
                        $cm->delete("post", $args["post"]);
                        $str .= "Cikk sikeresen törülve!";
                    }catch(CMException $e){
                        Error::redirect(($e->getErrno()));
                    }
                } else {
                    try{
                        $db = $cm->getPost($args["post"]);
                        $subcats = $cm->getAllSubCat(false, true);
                        $str .= editpostForm($db, $subcats);
                    }catch(CMException $e){
                        Error::redirect(($e->getErrno()));
                    }
                }
            } else {
                try {
                    $db = $cm->getShortPost(1, false, false, true);
                    $str .= selectPostForm($db);
                }catch(CMException $e){
                    Error::redirect($e->GetErrno());
                }
                break;
            }
        }
    }
    
    
    return $str;
}

/**
 * ADMIN SETTINGS MODULE
 * @return string
 */
function module_settings()
{
    global $db;
    $str = "";

    $str .= print_settings($db->get(TBOptions));
    
    return $str;
}

/**
 * ADMIN ERROR MODULE
 * @return string
 */
function module_error()
{
    $str = "";
    
    $err = Error::get_log();
    $str .= "<div>";
        $str .= "<h2>Rendszer</h2>";
            $str .= "<h3>Végzetes hibák</h3>";
            $str .= "<pre>";
                $str .= print_log($err["fatal"]);
            $str .= "</pre>";
            $str .= "<h3>Hibák</h3>";
            $str .= "<pre>";
                $str .= print_log($err["log"]);
            $str .= "</pre>";
             $str .= "<h3>Hack</h3>";
            $str .= "<pre>";
                $str .= print_log($err["hack"]);
            $str .= "</pre>";
    $str .="</div><br /><br /><br />";
    
    $err = MySQLException::get_log();
    $str .= "<div>";
        $str .= "<h2>Adatbázis kezelés</h2>";
            $str .= "<h3>Végzetes hibák</h3>";
            $str .= "<pre>";
                $str .= print_log($err["fatal"]);
            $str .= "</pre>";
            $str .= "<h3>Hibák</h3>";
            $str .= "<pre>";
                $str .= print_log($err["log"]);
            $str .= "</pre>";
    $str .="</div><br /><br /><br />";
    
    $err = ComException::get_log();
    $str .= "<div>";
        $str .= "<h2>Közösségi rendszer</h2>";
            $str .= "<h3>Végzetes hibák</h3>";
            $str .= "<pre>";
                $str .= print_log($err["fatal"]);
            $str .= "</pre>";
            $str .= "<h3>Hibák</h3>";
            $str .= "<pre>";
                $str .= print_log($err["log"]);
            $str .= "</pre>";
    $str .="</div><br /><br /><br />";
    
    $err = CMException::get_log();
    $str .= "<div>";
        $str .= "<h2>Tartalomkezelő rendszer</h2>";
            $str .= "<h3>Végzetes hibák</h3>";
            $str .= "<pre>";
                $str .= print_log($err["fatal"]);
            $str .= "</pre>";
            $str .= "<h3>Hibák</h3>";
            $str .= "<pre>";
                $str .= print_log($err["log"]);
            $str .= "</pre>";
    $str .="</div><br /><br /><br />";
   
    return $str;
}

?>