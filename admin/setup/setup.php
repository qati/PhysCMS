<?php

/**
 * @author Attila
 * @copyright 2011
 */
 
header("Content-Type: text/html; charset=UTF-8");

 
 
define("URL", "http://phys.tk/");




function mainMenu($url)
{
    $m[]=array("engine"=>"home", "name"=>"Főoldal", "url"=>$url, "level"=>0);
    $m[]=array("engine"=>"content", "name"=>"Cikkek", "url"=>$url."cikkek/", "level"=>0);
    $m[]=array("engine"=>"simulation", "name"=>"Szimulációk", "url"=>$url."szimulaciok/", "level"=>0);
    $m[]=array("engine"=>"community","name"=>"Közösség", "url"=>$url."community/", "level"=>0);
    return addslashes(serialize($m));
}

function subMenu($url)
{
    $menu["community"][]=array("useronly"=>1, "page"=>"", "name"=>"Üzenő Fal", "url"=>$url."community/");
    $menu["community"][]=array("useronly"=>0, "page"=>"forum", "name"=>"Fórum", "url"=>$url."community/forum/");
    $menu["community"][]=array("useronly"=>0, "page"=>"userlist", "name"=>"Felhasználók listálya", 
                                "url"=>$url."community/user/list/");

    return addslashes(serialize($menu));
}

function defaultUserSettings()
{
    $options = array("hideEmail"=>0, "sex"=>1, "activation"=>0, "wallNumOfMaxPosts"=>10,
                        "friends"=>0, "friendRequest"=>0, "publicWall"=>0);
    
    return htmlentities(serialize($options), ENT_QUOTES, "UTF-8");
}

echo "<pre>";
echo "Főmenű: ".mainMenu(URL)."<br /><br /><br />";
echo "Almen: ".subMenu(URL)."<br /><br /><br />";
echo "Alap felhasználói beálítások: ".defaultUserSettings()."<br /><br /><br />";

?>