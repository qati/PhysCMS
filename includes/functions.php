<?php

/**
 * FUNCTIONS FILE.
 * @author Attila
 * @copyright 2011
 */


/**
 * Get header theme function.
 */
function get_header()
{
    global $info, $com;
    
    require_once THEME."header.php";
}

/**
 * Get footer theme function.
 */
function get_footer()
{
    global $info;
    
    require_once THEME."footer.php";
}

/**
 * Get sidebar theme function.
 */
function get_sidebar()
{
    global $info, $com;
    
    require_once THEME."sidebar.php";
}

/**
 * Print menu.
 */
function print_menu()
{
    global $info, $com;
    $str    = stripslashes($info->menu);
    $menu   = unserialize(preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $str));
    $engine = getEngine();
    
    echo "<ul id='pagemenu'>";
    foreach($menu as $item){
        if ($item["engine"]==$engine){
            if (!defined("WCM_ADMIN") || strpos($_SERVER["REQUEST_URI"], "/admin/index.php")===false){
                echo "<li class='current_page_item'><a href='{$item['url']}'>{$item['name']}</a></li>";
            } else {
                echo "<li class='page_item'><a href='{$item['url']}'>{$item['name']}</a></li>";
            }
        } else {
            echo "<li class='page_item'><a href='{$item['url']}'>{$item['name']}</a></li>";
        }
    }
    if (defined("WCM_ADMIN") && strpos($_SERVER["REQUEST_URI"], "/admin/index.php")!==false){
        echo "<li class='current_page_item'><a href='".$info->siteurl."admin/index.php'>ADMIN</a><li>";
    } elseif ($com->getUserLevel()>4){
        echo "<li class=''page_item'><a href='".$info->siteurl."admin/index.php'>ADMIN</a></li>";
    }

    echo "</ul>";
    return;
}

/**
 * Print newsCats
 */
function print_newsCats()
{
    global $cm;
    try {
        $db   = $cm->getAllCat();
        $args = makeRequest(getEngine());
        
        echo "<ul id='nav'>";
        
        if (!isset($args[0]) || empty($args[0])){
            echo "<li class='current-cat'><a href='".URL."'>Kezdőlap</a></li>"; 
        } else {
            echo "<li><a href='".URL."'>Kezdőlap</a></li>"; 
        }
        
        if (is_array(end($db))){
            foreach($db as $item){
                if (isset($args[0]) && $args[0]==$item["url"]){
                    echo "<li class='current-cat'><a href='".URL."home/{$item["url"]}/'>{$item["title"]}</a></li>"; 
                } else {
                    echo "<li><a href='".URL."home/{$item["url"]}/'>{$item["title"]}</a></li>";
                }
            }    
        } elseif (is_array($db)) {
            if (isset($args[0]) && $args[0]==$db["url"]){
                    echo "<li class='current-cat'><a href='".URL."home/{$db["url"]}/'>{$db["title"]}</a></li>"; 
            } else {
                    echo "<li><a href='".URL."home/{$db["url"]}/'>{$db["title"]}</a></li>";
            }
        }
        echo "</ul>";
    } catch(CMException $e){
        Error::redirect($e->getErrno());
    }
    return;
}

/**
 * Print print_postCats
 */
function print_postCats()
{
    global $cm;
    
    try{
        $db = $cm->getAllCat(2);
        $args = makeRequest(getEngine());
        
        echo "<ul id='nav'>";
        
        if (!isset($args[1]) || empty($args[1])){
            echo "<li class='current-cat'><a href='".URL."'>Kezdőlap</a></li>"; 
        } else {
            echo "<li><a href='".URL."'>Kezdőlap</a></li>"; 
        }
        
        if (is_array(end($db))){
            foreach($db as $item){
                if (isset($args[1]) && $args[1]==$item["url"]){
                    echo "<li class='current-cat'><a href='".URL."cikkek/{$item["url"]}/'>{$item["title"]}</a></li>"; 
                } else {
                    echo "<li><a href='".URL."cikkek/{$item["url"]}/'>{$item["title"]}</a></li>";
                }
            }    
        } elseif (is_array($db)) {
            if (isset($args[1]) && $args[1]==$db["url"]){
                    echo "<li class='current-cat'><a href='".URL."cikkek/{$db["url"]}/'>{$db["title"]}</a></li>"; 
            } else {
                    echo "<li><a href='".URL."cikkek/{$db["url"]}/'>{$db["title"]}</a></li>";
            }
        }
        echo "</ul>";
    } catch(CMException $e){
        Error::redirect($e->getErrno());
    }
    return;
}

/**
 * Print submenu.
 */
function print_submenu()
{
    global $info, $com;
    if (!isset($_GET["engine"]) || $_GET["engine"]=="home"){
        return print_newsCats();   
    } elseif (isset($_GET["engine"]) && $_GET["engine"]=="content"){
        return print_postCats();
    }
    $str        = stripslashes($info->submenu);
    $submenutmp = unserialize(preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $str));
    if (!isset($submenutmp[getEngine()])){
        return false;
    }
     
    $args    = makeRequest(getEngine());   
    $submenu = $submenutmp[getEngine()];

    if (!isset($args[0]) || empty($args[0])){
        $args[0] = $submenu[0]["page"];
    }
    
    echo "<ul id='nav'>";
    foreach($submenu as $item){
        if ($item["useronly"] && !$com->isUser()){
            continue;
        }
        if (isset($args[0]) && $args[0]==$item["page"]){
            echo "<li class='current-cat'><a href='{$item['url']}'>{$item['name']}</a></li>";
            $first = false;
        } else {
            echo "<li><a href='{$item['url']}'>{$item['name']}</a></li>"; 
        }
    }
    if (getEngine()=="community" && !$com->isUser()){
        if (isset($args[0]) && $args[0]=="reg"){
            echo "<li class='current-cat'><a href='".$info->siteurl."community/reg'>Regisztráció</a></li>";
        } else {
            echo "<li><a href='".$info->siteurl."community/reg'>Regisztráció</a></li>";
        }
    }
    echo "</ul>";
    return;
}

/**
 * Regform;.
 */
function regform()
{
    global $com;
    if ($com->isUser()){
        return false;
    }
    
    $str  = "<h1 class='title'>Regisztráció</h1>";
    $str .= "<div id='error'>";
        $str .= "<h3>Hiba!</h3>";
        $str .= "<ul>";
            $str .= "<li id='nv_nick'>";
                $str .= "Rossz vagy foglalt nick! A nicknek minimum 4 hosszúnak kell lennie.";
            $str .= "</li>";
            $str .= "<li id='nv_name'>";
                $str .= "Nem megfelelő név! A név minimum 6 karakter hosszú lehet.";
            $str .= "</li>";
            $str .= "<li id='nv_pass'>";
                $str .= "Nem megfelelő jelszó vagy a két jelszó nem egyezik! Legalább 6 karakter kell.";
            $str .= "</li>";
            $str .= "<li id='nv_email'>";
                $str .= "Nem megfelelő e-mail cím vagy már regisztráltak ezzel az e-mail címmel.";
            $str .= "</li>";
            $str .= "<li id='nv_bdate'>";
                $str .= "Add meg a születési dátumodat.";
            $str .= "</li>";
            $str .= "<li id='nv_captcha'>";
                $str .= "Helyelen ellenőrző kód!";
            $str .= "</li>";
        $str .= "</ul>";
    $str .= "</div>";
    $str .="<table class='tbl' cellpadding='0' cellspacing='0'>";
    $str .="<form method='POST' action='javascript: com.user.reg.save();'>";
    
    $str .="<tr>";
        $str .= "<td class='first'>Felhasználónév:</td>";
        $str .= "<td><input type='text' name='reg_nick' id='reg_nick' maxlength='20' /></td>";
    $str .="</tr>";
    
    $str .="<tr>";
        $str .= "<td class='first'>Név:</td>";
        $str .= "<td><input type='text' name='name' id='name' maxlength='32' /></td>";
    $str .="</tr>";
    
    $str .="<tr>";
        $str .= "<td class='first'>Jelszó:</td>";
        $str .= "<td><input type='password' name='pass1' id='pass1' maxlength='32' /></td>";
    $str .="</tr>";
    
    $str .="<tr>";
        $str .= "<td class='first'>Jelszó újra:</td>";
        $str .= "<td><input type='password' name='pass2' id='pass2' maxlength='32' /></td>";
    $str .="</tr>";
    
    $str .="<tr>";
        $str .= "<td class='first'>E-mail:</td>";
        $str .= "<td><input type='text' name='mail' id='mail' /></td>";
    $str .="</tr>";
    
    $str .="<tr>";
        $str .= "<td class='first'>E-mail elrejtése:</td>";
        $str .= "<td>";
            $str .= "<div class='last'>Igen <input type='checkbox' name='hidemail_yes' id='hidemail_yes' /></div>";
            $str .= "<div class='last'>Nem ";
                $str .= "<input type='checkbox' name='hidemail_no' id='hidemail_no' checked='checked' />";
            $str .= "</div>";
        $str .= "</td>";
    $str .="</tr>";
    
    $str .="<tr>";
        $str .= "<td class='first'>Nemed:</td>";
        $str .= "<td><select name='sex' id='sex'>";
            $str .= "<option value='1'>Fiú</option>";
            $str .= "<option value='2'>Lány</option>";
        $str .= "</select></td>";
    $str .="</tr>";
    
    $str .="<tr>";
        $str .= "<td class='first'>Születési dátum:</td>";
        $str .= "<td>";
            $str .= "<select name='bdate_year' id='bdate_year'>";
                $str .= "<option value='0' selected='selected'>00</option>";
                for($i=2005;$i>1900;$i--){
                    $str .= "<option value='$i'>$i</option>";
                }
            $str .= "</select>";
            $str .= "<select name='bdate_month' id='bdate_month'>";
                $str .= "<option value='0' selected='selected'>00</option>";
                for($i=1;$i<=12;$i++){
                    $str .= "<option value='$i'>$i</option>";
                }
            $str .= "</select>";
            $str .= "<select name='bdate_day' id='bdate_day'>";
                $str .= "<option value='0' selected='selected'>00</option>";
                for($i=1;$i<=31;$i++){
                    $str .= "<option value='$i'>$i</option>";
                }
            $str .= "</select>";
        $str .= "</td>";
    $str .="</tr>";
    
    $str .="<tr>";
        $str .= "<td class='first'>Ellenőrző kód:</td>";
        $str .= "<td><div id='recaptcha'>";
            $str .= "<script type='text/javascript'>";
                $str .= "Recaptcha.create(reCaptchaPublicKey, 'recaptcha', reCaptchaOptions);";
                $str .= "$(document).scrollTop(0);";
            $str .= "</script>";
        $str .= "</div></td>";
    $str .="</tr>";
    
    $str .= "<tr>";
        $str .= "<td colspan='2' class='sbm'>";
            $str .= "<input type='submit' value='Regisztráció' />";
        $str .= "</td>";
    $str .= "</tr>";

    $str .= "</form>";
    $str .="</table>";
    return $str;
}

/**
 * Print recaptcha.
 */
function newReCaptcha()
{
    $str = "<h1 class='title'>Regisztráció</h1>";
    $str .= "<div id='error' class='show'><h3>Hiba!</h3><ul>";
        $str .= "<li class='showli'>Helyelen ellenőrző kód!</li>";
    $str .= "</ul></div>";
    
    $str .="<table class='tbl' cellpadding='0' cellspacing='0'>";
    $str .="<tr>";
        $str .= "<td class='first'>Ellenőrző kód:</td>";
        $str .= "<td><div id='recaptcha'>";
            $str .= "<script type='text/javascript'>";
                $str .= "Recaptcha.create(reCaptchaPublicKey, 'recaptcha', reCaptchaOptions);";
            $str .= "</script>";
        $str .= "</div></td>";
    $str .="</tr>";
    
    $str .= "<tr>";
        $str .= "<td colspan='2' class='sbm'>";
            $str .= "<input type='button' value='Regisztráció' onclick='com.user.reg.newCaptcha();' />";
        $str .= "</td>";
    $str .= "</tr>";

    $str .="</table>";

    return $str;
}

/**
 * Print LoginMenu
 */
function print_loginMenu()
{
    global $info;
    echo "<h2>Bejelentkezés</h2>";
    echo "<form method='POST' action='".$info->siteurl."community/login'>";
        echo "<input type='text' value='Felhasználónév' name='nick' id='nick' maxlength='20' />";
        echo "<input type='text' value='Jelszó' name='pass' id='pass' maxlength='32' />";
        echo "<input type='submit' value='Bejelentkezés' id='login' />";
    echo "</form>";
    echo "<ul>";
        echo "<li><a href='".$info->siteurl."community/reg'>Regisztráció</a></li>";
        echo "<li><a href='".$info->siteurl."community/elfelejtettjelszo'>Elfelejtett jelszó</a></li>";
    echo "</ul>";
    return;
}

/**
 * UserWallPost
 * @param array $item
 * @return string
 */
function userWallPost($item)
{
    global $com;
    
    $userName = $item["user_id"];
    $addName  = $item["add_id"];
    
    try {
        $userName = $com->getUserName($item["user_id"]);
    } catch(ComException $e){
        if ($e->getErrno()==COM_NORES){
            Error::redirect(COM_NORES);
        }
    }
    
    try {
        $addName  = $com->getUserName($item["add_id"]);
    } catch(ComException $e){
        if ($e->getErrno()==COM_NORES){
            Error::redirect(COM_NORES);
        }
    }
    
    $str = "<div class='userwall_post'>";
        $str .= "<div class='img'><a href='".URL."community/user/profile/".$item['user_id']."'>";
            if (avatar_type($userName)){
                $str .= "<img src='".AVATARS.$userName.".".avatar_type($userName)."' />";
            } else {
                $str .= "<img src='".AVATARS."default.png' />";
            }
        $str .= "</a></div>";
        $str .= "<div class='post'>";
            $str .= "<a href='".URL."community/user/profile/".$item['add_id']."'>".$addName."</a>";
            $str .= "<br />".$item["post"];
            $str .= "<br /><div class='date'>Dátum: ".$item["post_date"]."</div>";
        $str .= "</div>";
    $str .= "</div>";
    $str .= "<hr />";
    
    return $str;
}

/**
 * UserWall.
 * @param array $db
 * @param int $page default 1
 * @return string
 */
function userWall($db, $page=1)
{
    global $com;
    
    $str  = "<div id='userwall_newpost'>";
        $str .= "<form action='javascript: com.userWall.addPost();'>";
            $str .= "<div id='wall_msg'><textarea id='userwall_post' rows='2' cols='60'></textarea></div>";
            $str .= "<div id='wall_submit'><input type='submit' value='Hozzáad' /></div>";
        $str .= "</form>";
    $str .= "</div>";
    
    if ($db===false){
        return $str;
    }
    
    $str .= "<div id='userwall_posts'>";
    
    if (is_array(end($db))){
        foreach($db as $item){
            $str .= userWallPost($item);
        }
    } else {
        $str .= userWallPost($db);
    }
    
    $str .= "</div>";
    
    try {
        $nr = $com->userWall(COM_USERWALL_COUNTPOSTS);
    } catch(ComException $e){
        Error::redirect($e->getErrno());
    }
    $pages = ceil($nr/$com->attr("wallNumOfMaxPosts"));
    
    $str .= "<div class='center'>";
        for($i=1;$i<=$pages;$i++){
            if ($i==$page){
                $str .= " <a class='userwall_pages' id='userwall_currentpage'>[$i]</a>";
            } else {
                $str .= " <a class='userwall_pages' onclick='com.userWall.changePage($i);'>$i</a>";
            }
        }
    $str .= "</div>";
    
    return $str;
}


/**
 * PrintNews
 * @param array $news
 * @param boolean $isInCat default false
 * @global $info
 * @global $com
 * @return string
 */
function printNews($news, $isInCat=false)
{
    global $com, $info;
    
    $author   = $news["user_id"];
    $url      = $info->siteurl;
    $img_type = img_type(NEWSIMGPATH."summary/".$news['id']);
    $img      = NEWSIMG."summary/".$news['id'].".".$img_type;
    try {
        $author = $com->getUserName($author);
    } catch (ComException $e){
        $author = "Lekérdezés sikertelen!";
    }
    
    $str  = "<div class='news'>";
    
    $str .= "<img width='260' height='195' src='".$img."' class='alignleft post_thumbnail post-image' />";
    
    $str .= "<h2 class='title'>";
    if ($isInCat){
        $str .= "<a href='#' onclick='cm.showPost(\"{$news['id']}\", 1);'>{$news['title']}</a>";
    } else {
        $str .= "<a href='#' onclick='cm.showPost(\"{$news['id']}\");'>{$news['title']}</a>";
    }
    $str .= "</h2>";
    
    $str .= "<div class='postdate'>";
        $str .= "<img src='".$url."content/theme/images/date.png' />".$news["created"];
        $str .= "&nbsp;<img src='".$url."content/theme/images/user.png' />".$author;
        $str .= "&nbsp;<img src='".$url."content/theme/images/numcomments.png' />".$news["comments"];
        $str .= "&nbsp;<img src='".$url."content/theme/images/folder.png' />".$news["opened"];
    $str .= "</div>";
    
    $str .= "<div class='entry'>";
        $str .= $news["summary"];
    
    if ($isInCat){
        $str .= " <a href='#' onclick='cm.showPost(\"{$news['id']}\", 1);' class='more-link'>";
    } else {
        $str .= " <a href='#' onclick='cm.showPost(\"{$news['id']}\");' class='more-link'>";   
    }
    
            $str .= "<strong>Tovább &raquo;</strong>";
        $str .= "</a>";
    $str .= "</div>";
    
    $str .= "</div>";
    
    $str .= "<hr />";
    
    return $str;
}

/**
 * printComments
 * @param array $comment
 * @global $com
 * @return string
 */
function printComment($comment)
{
    global $com;
    
    $str      = "<div class='comment'>";
    $userName = $comment["user_id"];
    
    try {
        $userName = $com->getUserName($comment["user_id"]);
    } catch(ComException $e){
        if ($e->getErrno()==COM_NORES){
            Error::redirect(COM_NORES);
        }
    }
    
    $str .= "<div class='img'><a href='".URL."community/user/profile/".$comment['user_id']."'>";
        if (avatar_type($userName)){
            $str .= "<img src='".AVATARS.$userName.".".avatar_type($userName)."' />";
        } else {
            $str .= "<img src='".AVATARS."default.png' />";
        }
    $str .= "</a></div>";
    $str .= "<div class='post'>";
        $str .= "<a href='".URL."community/user/profile/".$item['add_id']."'>".$userName."</a>";
        $str .= "<br />".$comment['msg'];
        $str .= "<br /><div class='date'>Dátum: ".$comment['created']."</div>";
    $str .= "</div>";
    
        
    $str .= "</div>";
    $str .= "<hr />";
    
    return $str;
}

/**
 * PrintComments
 * @global $cm
 * @global $info
 * @param int $id
 * @param boolean $canAddComment
 * @param int $page default 1
 * @param boolean $isAjax default false
 * @return string
 */
function printComments($id, $canAddComment, $page = 1, $isAjax=false)
{
    global $cm, $info;
    
    $str      = "";
    $comments = "";
    
    try {
        $comments = $cm->getComments($id, $page);
    } catch(CMException $e){
        if ($isAjax){
            throw new CMException($e->getErrno());
        } else {
            Error::redirect($e->getErrno());
        }
    }
    if (!$isAjax){
        if ($canAddComment){
            $str .= "<div id='addComment'>";
                $str .= "<form action='javascript: cm.addComment();'>";
                    $str .= "<input type='hidden' id='ac_id' value='".$id."' />";
                    $str .= "<div id='c_msg'><textarea id='ac_msg' rows='2' cols='60'></textarea></div>";
                    $str .= "<div id='c_submit'><input type='submit' value='Hozzászól' /></div>";
                $str .= "</form>";
            $str .= "</div>";
        } else {
            $str .= "<div id='addComment'>";
                $str .= "<input type='hidden' id='ac_id' value='".$id."' />";
                $str .= "<b>Hozzászólás írásához be kell jelentkezned!</b>";
            $str .= "</div>";
        }
    }
        
    $str .="<div id='comments'>";
        if (is_array($comments) && is_array(end($comments))){
            foreach($comments as $item){
                $str .= printComment($item);
            }
        } elseif (is_array($comments)){
            $str .= printComment($comments);
        } else {
            $str .= "Még nem érkezett hozzászólás!";   
        }
        
        $nr = 0;
        
        try {
            $nr = $cm->countComments($id);
        } catch(CMException $e){
            Error::redirect($e->getErrno());
        }
        
        $pages = ceil($nr/$info->commentsPerPage);
    
        $str .= "<div class='center'>";
            for($i=1;$i<=$pages;$i++){
                if ($i==$page){
                    $str .= " <a class='comments_pages' id='comments_currentpage'>[$i]</a>";
                } else {
                    $str .= " <a class='comments_pages' onclick='cm.getComments($i)'>$i</a>";
                }
        }
        $str .= "</div>";
        
    $str .= "</div>";
    
    return $str;
}

/**
 * PrintFullNews
 * @param array $news
 * @global $info
 * @global $com
 * @return string
 */
function printFullNews($news)
{
    global $com, $info;
    
    $author   = $news["user_id"];
    $url      = $info->siteurl;
    $str      = "";
    $img_type = img_type(NEWSIMGPATH."summary/".$news['id']);
    $img      = NEWSIMG."summary/".$news['id'].".".$img_type;
    try {
        $author = $com->getUserName($author);
    } catch (ComException $e){
        $author = "Lekérdezés sikertelen!";
    }
   
    $str .= "<img width='260' height='195' src='".$img."' class='alignleft post_thumbnail post-image' />";
    
    $str .= "<h2 class='title'>{$news['title']}</h2>";
    
    $str .= "<div class='postdate'>";
        $str .= "<img src='".$url."content/theme/images/date.png' />".$news["created"];
        $str .= "&nbsp;<img src='".$url."content/theme/images/user.png' />".$author;
        $str .= "&nbsp;<img src='".$url."content/theme/images/numcomments.png' />".$news["comments"];
        $str .= "&nbsp;<img src='".$url."content/theme/images/folder.png' />".$news["opened"];
    $str .= "</div>";

    $str .= "<div class='entry'>";
        $str .= $news["summary"];
    $str .= "</div>";
    
    $str .= "<div class='post'>";
        $str .= $news["content"];
    $str .= "</div>";
    
    if ($news["type"]=="1" || $news["type"]=="3"){
        $str .= printComments($news["id"], $com->isUser());
    }
    
    return $str;
}


/**
 * Get avatar form.
 * @return string
 */
function addAvatarForm()
{
    global $com;
    $str = "";
    $haveAvatar = avatar_type($com->getUserName()) ? true : false;
    $str .= "<h2 class='title'>Avatar hozzáadása/lecserélése</h2>";

    if ($haveAvatar){
        $str .= "<div class='dbox left'>";
        $datas = @getimagesize(AVATARS.$com->getUserName().".".avatar_type($com->getUserName()));
        $str .= "<div><img src='".AVATARS.$com->getUserName().".".avatar_type($com->getUserName())."' />";
        $str .= "<br /><b>Típus:</b> ".$datas["mime"];
        $str .= "<br /><b>Méret:</b> ".$datas[0]."x".$datas[1]."</div>";
        $str .= "</div>";
    } else {
        $str .= "<div class='dbox left'>";
        $str .= "<b>Nincs avatar!</b>";
        $str .= "</div>";
    }
    
    $str .= "<div class='left padding20'>";
        $str .= "<ul>";
            $str .= "<li>Az avatar formátuma lehet: png, gif, jpg</li>";
            $str .= "<li>Az avatar maximális mérete lehet: 100 KB</li>";
            $str .= "<li>Az avatar minimálisan 100x100-as kell legyen!</li>";
            $str .= "<li>Az avatar szélesége és magasága egyforma kell legyen!(pl. 150x150)</li>";
        $str .= "</ul>";
    $str .= "<form action='".URL."community/user/avatar/save' method='POST' enctype='multipart/form-data'>";
        $str .= "<input type='file' name='avatar' /><br />";
        $str .= "<div class='center padding10'><input type='submit' value='Mentés' name='save' /></div>";
    $str .= "</form>";
    $str .= "</div>";
    
    return $str;
}


/**
 * userProfile
 * @param array $db
 * @global $com
 * @global $info
 * @return string
 */
function userProfile($db)
{
    if (!is_array($db)){
        return false;
    }
    
    global $com, $info;
    $str = "";
    $str .= "<div class='dbox padding20 profileinfo clear' />";
        $str .= "<div class='left'><table cellpadding='0' cellspacing='0' id='profile_tb'>";
            
            $str .="<tr>";
                $str .= "<td class='first'>Felhasználónév:</td>";
                $str .= "<td class='second'>".$db['nick']."</td>";
            $str .="</tr>";
            
            $str .="<tr>";
                $str .= "<td class='first'>Név:</td>";
                $str .= "<td class='second'>".$db['name']."</td>";
            $str .="</tr>";
            
            $str .="<tr>";
                $str .= "<td class='first'>E-mail:</td>";
                if ($com->attr('hideEmail')=="1"){
                    $str .= "<td class='second'>Rejtett</td>";
                } else {
                    $str .= "<td class='second'>".$db['email']."</td>";
                }
            $str .="</tr>";
            
            $str .="<tr>";
                $str .= "<td class='first'>Születési dátum:</td>";
                $str .= "<td class='second'>".$db['bdate']."</td>";
            $str .="</tr>";
            
            $str .="<tr>";
                $str .= "<td class='first'>Nem:</td>";
                $str .= "<td class='second'>".($db['sex']=='1' ? 'fiú' : 'lány')."</td>";
            $str .="</tr>";
            
            $str .="<tr>";
                $str .= "<td class='first'>Regisztrált:</td>";
                $str .= "<td class='second'>".$db['regDate']."</td>";
            $str .="</tr>";
            
            $str .="<tr>";
                $str .= "<td class='first'>Utoljára itt:</td>";
                $str .= "<td class='second'>".$db['lastVisit']."</td>";
            $str .="</tr>";
            
            $str .="<tr>";
                $level = $info->option('userLevel');
                $str  .= "<td class='first'>Felhasználói szint:</td>";
                $str  .= "<td class='second'>".$level[$db['userLevel']]."</td>";
            $str .="</tr>";
        
            $str .="<tr>";
                $str .= "<td class='first'>Ismerősök száma:</td>";
                $str .= "<td class='second'>";
                    $str .= $db['numOfFriends'];
                $str .= "</td>";
            $str .="</tr>";
            
        $str .= "</table></div>";
        $str .= "<div>";
            if (($type=avatar_type($db['nick']))!=false){
                $str .= "<img width='100' height='100' src='".AVATARS.$db['nick'].".".$type."' />";
            } else {
                $str .= "<img width='100' height='100' src='".AVATARS."default.png' />";
            }
            try{
            if ($db["id"]!=$com->getUserID() && $com->isUser() && !$com->isFriend($db["id"])){
                if (!$com->isMarkedAsFriend($db["id"]) && !$com->isIMarkedAsFriend($db["id"])){
                    $str .= "<br /><a href='javascript: com.user.addFriend({$db['id']});'>Ismerősnek jelölöm</a>";
                } elseif ($com->isMarkedAsFriend($db["id"])){
                    $str .= "<br />Ismerősnek jelöltem!";
                } elseif ($com->isIMarkedAsFriend($db["id"])){
                    $str .= "<br />Ismerősnek jelölt!";
                }
            }
            }catch(ComException $e){
                Error::redirect($e->getErrno());
            }
        $str .= "</div>";
    $str .= "</div>";
    
    $str .= "<div id='userwall_posts'>";
        $str .= "<h2 class='title'>Bejegyzések</h2>";
        if ($db["wall"] == "") {
            $str .= "<br /><br />A felhasználó üzenőfala nem publikus!";
        } elseif (is_array(end($db["wall"]))){
            foreach($db["wall"] as $item){
                $str .= userWallPost($item);
            }
        } else {
            $str .= userWallPost($db["wall"]);
        }
    $str .= "</div>";
    
    return $str;
}

/**
 * userFriendRequests
 * @param array $db
 * @param array $isAJAX default false
 * @global $com
 * @return string
 */
function userFriendRequests($db, $isAJAX=false)
{
    global $com;
    if (is_array($db) && is_array(end($db))){
        $str = "<table class='tbl friendRequests'>";
            $str .= "<tr>";
                $str .= "<th>Ismerősnek jelölt</th>";
                $str .= "<th>Ekkor</th>";
                $str .= "<th>Elfogadás</th>";
            $str .= "</tr>";
            foreach($db as $item){
                $link = URL."community/user/profile/".$item['id'];
                $str .= "<tr>";
                    $user = "";
                    try{
                        $user = $com->getUserName($item['id']);
                    }catch(ComException $e){
                        if ($isAJAX){
                            return get_msg($e->getErrno());
                        }
                        Error::redirect($e->getErrno());
                    }
                    $str .= "<td><a href='$link'>".$user."</a></td>";
                    $str .= "<td>".$item['date']."</td>";
                    $str .= "<td>";
                        $str .= "<a href='#' onclick='com.user.confirmFriend({$item['id']});'>Megerősítés</a>";
                    $str .= "</td>";
                $str .= "</tr>";
            }
        $str .= "</table>";
        
        return $str;
    } else {
        if ($isAJAX){
            return Ajax::get_msg(AJAX_COM_USER_NOFRIENDREQUEST);
        } else {
            System::redirect("rendszeruzenet/".COM_USER_NOFRIENDREQUEST);
        }
    }
}

/**
 * userList
 * @param array $db
 * @param $page default 1
 * @global $com
 * @return string
 */
function userList($db, $page=1)
{
    global $com;
    
    $str = "<table class='tbl userList'>";
        
        $str .= "<tr>";
            $str .= "<th>ID</th>";
            $str .= "<th>Nick</th>";
            $str .= "<th>Privát üzenet</th>";
            $str .= "<th>Regisztrált</th>";
            $str .= "<th>Utoljára itt</th>";
        $str .= "</tr>";
        
        if (is_array(end($db))){
            foreach($db as $item){
            $str .= "<tr>";
                $str .= "<td>";
                    $str .= $item["id"];
                $str .= "</td>";
                $str .= "<td>";
                    $str .= "<a href='".$com->attr('siteurl', true)."community/user/profile/".$item['id']."'>";
                        $str .= $item["nick"];
                    $str .= "</a>";
                $str .= "</td>";
                $str .= "<td>";
                    $str .= "<a href='#' onclick=''>Privát üzenet</a>";
                $str .= "</td>";
                
                $str .= "<td>";
                    $str .= $item["regDate"];
                $str .= "</td>";
                
                $str .= "<td>";
                    $str .= $item["lastVisit"];
                $str .= "</td>";
            $str .= "</tr>";
            }
        } else {
        $str .= "<tr>";
            $str .= "<td>";
                $str .= "<a href='".$com->attr('siteurl', true)."/community/user/profile/".$db['id']."'>";
                    $str .= $db["id"];
                $str .= "</a>";
            $str .= "</td>";
            $str .= "<td>";
                $str .= $db["nick"];
            $str .= "</td>";
            $str .= "<td>";
                $str .= "<a href='#' onclick='$.com(\"PM\", {$db['id']});'>Privát üzenet</a>";
            $str .= "</td>";
            $str .= "<td>";
                $str .= $db["regDate"];
            $str .= "</td>";
            $str .= "<td>";
                $str .= $db["lastVisit"];
            $str .= "</td>";
        $str .= "</tr>";
        }
        
    $str .= "</table>";
    
    $str .= "<div class='center'>";
    
    $numOfUsers = $com->countUsers();
    $pages = ceil($numOfUsers/$com->attr('userListMax', true));
    for($i=1;$i<=$pages;$i++){
        if ($i==$page){
            $str .= "&nbsp;<a href='#'>[$i]</a>&nbsp;";
        } else {
            $str .= "&nbsp;<a href='#' onclick='$.com(\"chPageUserList\", $i);'>$i</a>&nbsp;";
        }
    }
    
    $str .= "</div>";
    
    return $str;
}

/**
 * lostPassForm
 * @global $com
 * @global string $error
 * @return string
 */
function lostPassForm($error="")
{
    global $com;
    
    if ($com->isUser()){
        return "";
    }
    
    $str  = "<h1 class='title'>Elfelejtett jelszó</h1>";
    if (strlen($error)>3){
        $str .= "<div id='error' style='display: block;'>";
            $str .= "<h3>Hiba!</h3>";
            $str .= "<ul style='display: block;'>";
                $str .= "<li style='display: block;'>";
                    $str .= $error;
                $str .= "</li>";
            $str .= "</ul>";
        $str .= "</div>";
    }
    $str .="<table class='tbl' cellpadding='0' cellspacing='0'>";
    $str .="<form method='POST' action='javascript: com.user.newpass();'>";

        $str .="<tr>";
            $str .= "<td class='first'>E-mail:</td>";
            $str .= "<td><input type='text' name='mail' id='mail' /></td>";
        $str .="</tr>";
    
        $str .="<tr>";
            $str .= "<td class='first'>Ellenőrző kód:</td>";
                $str .= "<td><div id='recaptcha'>";
                $str .= "<script type='text/javascript'>";
                    $str .= "Recaptcha.create(reCaptchaPublicKey, 'recaptcha', reCaptchaOptions);";
                    $str .= "$(document).scrollTop(0);";
                $str .= "</script>";
            $str .= "</div></td>";
        $str .="</tr>";
    
        $str .= "<tr>";
            $str .= "<td colspan='2' class='sbm'>";
                $str .= "<input type='submit' value='Új jelszó' />";
            $str .= "</td>";
        $str .= "</tr>";

    $str .= "</form>";
    $str .="</table>";
    
    return $str;
}

?>