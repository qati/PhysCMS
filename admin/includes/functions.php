<?php

/**
 * ADMIN FUNCTIONS FILE.
 * @author Attila
 * @copyright 2012
 */


/**
 * Get error.
 */
function get_error($errno)
{
    $str  = "<blockquote class='errormsg'>";
    $str .= get_msg($errno, false);
    $str .= "</blockquote>";
    
    return $str;
}

/**
 * Get header.
 */
function get_admin_header()
{
    global $info;
    
    require_once ATHEME."header.php";
}

/**
 * Get footer.
 */
function get_admin_footer()
{
    global $info;
    
    require_once ATHEME."footer.php";
}

/**
 * Get admin menu.
 */
function get_admin_menu()
{
    global $info;
    
    require_once ATHEME."menu.php";
}


/**
 * Print settings
 * @param array $array
 * @return string
 */
function print_settings($array)
{
    $str  = "<div>";
        $str .="<h3>Új hozzáadása</h3>";
    $str .= "</div>";
    $str .= "<div>";
        $str .= "<h3>Szerkeszt</h3>";
        $str .= "<select name='edit_settings'>";
            foreach($array as $item){
                $str .= "<option value='".$item['id']."'>".$item['option_name']."</option>";
            }
        $str .= "</select>";
        $str .= "Unserialize <input type='checkbox' value='1' name='speci' />";
        $str .= "Base64 <input type='checkbox' value='2' name='speci' />";
        $str .= "<input type='button' value='Szerkeszt' onclick='' />";
    $str .= "</div>";
    
    return $str;
}

/**
 * Print log
 * @param array $array
 * @return string
 */
function print_log($array)
{
    $str = "";
    
    foreach($array as $item){
        $str .= print_r(unserialize($item), true);
    }
    
    return $str;
}

/**
 * Get new cat form function.
 */
function newCatForm($error="")
{
    $str  = "<h1 class='title'>Új cikk/hír kategória létrehozása</h1>";
    
    if (!empty($error)){
        $str .= "<br />$error<br />";
    }
    
    $str .= "<table cellpadding='0' cellspacing='0' class='tbl'>";
    $str .= "<form action='index.php' method='POST'>";
            
        $str .= "<tr>";
            $str .= "<td class='first'>Kategória neve:</td>";
            $str .= "<td><input type='text' name='title' maxlength='255' /></td>";
        $str .= "</tr>";
            
        $str .= "<tr>";
            $str .= "<td class='first'>Kategória link:</td>";
            $str .= "<td><input type='text' name='url' maxlength='255' /></td>";
        $str .= "</tr>";
            
        $str .= "<tr>";
            $str .= "<td class='first'>Láthatóság:</td>";
            $str .= "<td>";
                $str .= "<select name='visibility'>";
                    $str .= "<option value='0'>Nem látható</option>";
                    $str .= "<option value='1'>Mindenki számára látható</option>";
                    $str .= "<option value='2'>Csak a felhasználók számára látható</option>";
                $str .= "</select>";
            $str .= "</td>";
        $str .= "</tr>";
            
        $str .= "<tr>";
            $str .= "<td class='first'>Típus:</td>";
            $str .= "<td>";
                $str .= "<select name='type'>";
                    $str .= "<option value='0'>Hír kategória</option>";
                    $str .= "<option value='3'>Cikk kategória</option>";
                $str .= "</select>";
            $str .= "</td>";
        $str .= "</tr>";
            
        $str .= "<tr>";
            $str .= "<td colspan='2' class='sbm'>";
                $str .= "<input type='hidden' name='engine' value='cm' />";
                $str .= "<input type='hidden' name='module' value='newcat' />";
                $str .= "<input type='submit' name='add' value='Hozzáadás' />";
            $str .= "</td>";
        $str .= "</tr>";
            
    $str .= "</form>";
    $str .= "</table>";
    
    return $str;
}

/**
 * Get new subcat form function.
 */
function newSubcatForm($cats, $error="")
{
    $str  = "<h1 class='title'>Új cikk/hír alkategória létrehozása</h1>";
    
    if (!empty($error)){
        $str .= "<br />$error<br />";
    }
    
    $str .= "<table cellpadding='0' cellspacing='0' class='tbl'>";
    $str .= "<form action='index.php' method='POST'>";
        
        $str .= "<tr>";
            $str .= "<td class='first'>Kategória:</td>";
            $str .= "<td><select name='cat'>";
                foreach($cats as $cat){
                    $str .= "<option value='".$cat['id']."'>".$cat['title']."</option>";
                }
            $str .= "</select></td>";
        $str .= "</tr>";
        
        $str .= "<tr>";
            $str .= "<td class='first'>Alkategória neve:</td>";
            $str .= "<td><input type='text' name='title' maxlength='255' /></td>";
        $str .= "</tr>";
            
        $str .= "<tr>";
            $str .= "<td class='first'>Alkategória link:</td>";
            $str .= "<td><input type='text' name='url' maxlength='255' /></td>";
        $str .= "</tr>";
            
        $str .= "<tr>";
            $str .= "<td class='first'>Láthatóság:</td>";
            $str .= "<td>";
                $str .= "<select name='visibility'>";
                    $str .= "<option value='0'>Nem látható</option>";
                    $str .= "<option value='1'>Mindenki számára látható</option>";
                    $str .= "<option value='2'>Csak a felhasználók számára látható</option>";
                $str .= "</select>";
            $str .= "</td>";
        $str .= "</tr>";
            
        $str .= "<tr>";
            $str .= "<td class='first'>Típus:</td>";
            $str .= "<td>";
                $str .= "<select name='type'>";
                    $str .= "<option value='1'>Hír alkategória - felhasználók írhatnak bele</option>";
                    $str .= "<option value='2'>Hír alkategória - felhasználók nem írhatnak bele</option>";
                    $str .= "<option value='4'>Cikk alkategória - felhasználók írhatnak bele</option>";
                    $str .= "<option value='5'>Cikk alkategória - felhasználók nem írhatnak bele</option>";
                $str .= "</select>";
            $str .= "</td>";
        $str .= "</tr>";
            
        $str .= "<tr>";
            $str .= "<td colspan='2' class='sbm'>";
                $str .= "<input type='hidden' name='engine' value='cm' />";
                $str .= "<input type='hidden' name='module' value='newsubcat' />";
                $str .= "<input type='submit' name='add' value='Hozzáadás' />";
            $str .= "</td>";
        $str .= "</tr>";
            
    $str .= "</form>";
    $str .= "</table>";
    
    return $str;
}

/**
 * Get show cats to edit.
 */
function allCatToEditForm($cats, $error="")
{
    $str  = "<h1 class='title'>Cikk/hír kategória szerkesztése</h1>";
    
    if (!empty($error)){
        $str .= "<br />$error<br />";
    } 
    
    $str .= "<table cellpadding='0' cellspacing='0' class='tbl'>";
    $str .= "<form action='index.php' method='POST'>";
        
        $str .= "<tr>";
            $str .= "<td class='first'>Kategória:</td>";
            $str .= "<td><select name='cat'>";
                foreach($cats as $cat){
                    $str .= "<option value='".$cat['id']."'>".$cat['title']."</option>";
                }
            $str .= "</select></td>";
        $str .= "</tr>";
        
        $str .= "<tr>";
            $str .= "<td class='first'>Művelet</td>";
            $str .= "<td><select name='muvelet'>";
                $str .= "<option value='0'>Szerkeszt</option>";
                $str .= "<option value='1'>Törül</option>";
            $str .= "</select></td>";
        $str .= "</tr>";
        
        $str .= "<tr>";
            $str .= "<td colspan='2' class='sbm'>";
                $str .= "<input type='hidden' name='engine' value='cm' />";
                $str .= "<input type='hidden' name='module' value='editcat' />";
                $str .= "<input type='submit' name='show' value='Szerkeszt' />";
            $str .= "</td>";
        $str .= "</tr>";
            
    $str .= "</form>";
    $str .= "</table>";
    return $str;
}

/**
 * Get edit cat form.
 */
function editCatForm($db, $error="")
{
    $str  = "<h1 class='title'>Cikk/hír kategória szerkesztése</h1>";
    
    if (!empty($error)){
        $str .= "<br />$error<br />";
    }
    
    $str .= "<table cellpadding='0' cellspacing='0' class='tbl'>";
    $str .= "<form action='index.php' method='POST'>";
            
        $str .= "<tr>";
            $str .= "<td class='first'>Kategória neve:</td>";
            $str .= "<td><input type='text' name='title' maxlength='255' value='".$db['title']."' /></td>";
        $str .= "</tr>";
            
        $str .= "<tr>";
            $str .= "<td class='first'>Kategória link:</td>";
            $str .= "<td><input type='text' name='url' maxlength='255' value='".$db['url']."' /></td>";
        $str .= "</tr>";
            
        $str .= "<tr>";
            $str .= "<td class='first'>Láthatóság:</td>";
            $str .= "<td>";
                $str .= "<select name='visibility'>";
                    if ($db['visibility']=="0"){
                        $str .= "<option value='0' selected='selected'>Nem látható</option>";
                    } else {
                        $str .= "<option value='0'>Nem látható</option>";
                    }
                    if ($db['visibility']=="1"){
                        $str .= "<option value='1' selected='selected'>Mindenki számára látható</option>";
                    } else {
                        $str .= "<option value='1'>Mindenki számára látható</option>";
                    }
                    if ($db['visibility']=="2"){
                        $str .= "<option value='2' selected='selected'>Csak a felhasználók számára látható</option>";
                    } else {
                        $str .= "<option value='2'>Csak a felhasználók számára látható</option>";
                    }
                $str .= "</select>";
            $str .= "</td>";
        $str .= "</tr>";
            
        $str .= "<tr>";
            $str .= "<td class='first'>Típus:</td>";
            $str .= "<td>";
                $str .= "<select name='type'>";
                    if ($db["type"]=="0"){
                        $str .= "<option value='0' selected='selected'>Hír kategória</option>";
                    } else {
                        $str .= "<option value='0'>Hír kategória</option>";
                    }
                    if ($db["type"]=="3"){
                        $str .= "<option value='3' selected='selected'>Cikk kategória</option>";
                    } else {
                        $str .= "<option value='3'>Cikk kategória</option>";
                    }
                $str .= "</select>";
            $str .= "</td>";
        $str .= "</tr>";
            
        $str .= "<tr>";
            $str .= "<td colspan='2' class='sbm'>";
                $str .= "<input type='hidden' name='engine' value='cm' />";
                $str .= "<input type='hidden' name='module' value='editcat' />";
                $str .= "<input type='hidden' name='id' value='".$db['id']."' />";
                $str .= "<input type='submit' name='edit' value='Módosit' />";
            $str .= "</td>";
        $str .= "</tr>";
            
    $str .= "</form>";
    $str .= "</table>";
    
    return $str;   
}

/**
 * Get show subcats to edit.
 */
function allSubCatToEditForm($subcats, $error="")
{
    $str  = "<h1 class='title'>Cikk/hír alkategória szerkesztése</h1>";
    
    if (!empty($error)){
        $str .= "<br />$error<br />";
    }
    
    $str .= "<table cellpadding='0' cellspacing='0' class='tbl'>";
    $str .= "<form action='index.php' method='POST'>";
        
        $str .= "<tr>";
            $str .= "<td class='first'>Kategória:</td>";
            $str .= "<td><select name='subcat'>";
                foreach($subcats as $subcat){
                    $str .= "<option value='".$subcat['id']."'>".$subcat['title']."</option>";
                }
            $str .= "</select></td>";
        $str .= "</tr>";
        
        $str .= "<tr>";
            $str .= "<td class='first'>Művelet</td>";
            $str .= "<td><select name='muvelet'>";
                $str .= "<option value='0'>Szerkeszt</option>";
                $str .= "<option value='1'>Törül</option>";
            $str .= "</select></td>";
        $str .= "</tr>";
        
        $str .= "<tr>";
            $str .= "<td colspan='2' class='sbm'>";
                $str .= "<input type='hidden' name='engine' value='cm' />";
                $str .= "<input type='hidden' name='module' value='editsubcat' />";
                $str .= "<input type='submit' name='show' value='Szerkeszt' />";
            $str .= "</td>";
        $str .= "</tr>";
            
    $str .= "</form>";
    $str .= "</table>";
    return $str;
}

/**
 * Get edit subcat form.
 */
function editSubCatForm($cats, $db, $error="")
{
    $str  = "<h1 class='title'>Cikk/hír alkategória szerkesztése</h1>";
    
    if (!empty($error)){
        $str .= "<br />$error<br />";
    }
    
    $str .= "<table cellpadding='0' cellspacing='0' class='tbl'>";
    $str .= "<form action='index.php' method='POST'>";
        
        $str .= "<tr>";
            $str .= "<td class='first'>Kategória:</td>";
            $str .= "<td><select name='cat'>";
                foreach($cats as $cat){
                    if ($db["cat"]==$cat["id"]){
                        $str .= "<option value='".$cat['id']."' selected='selected'>".$cat['title']."</option>";
                    } else {
                        $str .= "<option value='".$cat['id']."'>".$cat['title']."</option>";
                    }
                }
            $str .= "</select></td>";
        $str .= "</tr>";
        
        $str .= "<tr>";
            $str .= "<td class='first'>Alkategória neve:</td>";
            $str .= "<td><input type='text' name='title' maxlength='255' value='".$db['title']."' /></td>";
        $str .= "</tr>";
            
        $str .= "<tr>";
            $str .= "<td class='first'>Alkategória link:</td>";
            $str .= "<td><input type='text' name='url' maxlength='255' value='".$db['url']."' /></td>";
        $str .= "</tr>";
            
        $str .= "<tr>";
            $str .= "<td class='first'>Láthatóság:</td>";
            $str .= "<td>";
                $str .= "<select name='visibility'>";
                    if ($db['visibility']=="0"){
                        $str .= "<option value='0' selected='selected'>Nem látható</option>";
                    } else {
                        $str .= "<option value='0'>Nem látható</option>";
                    }
                    if ($db['visibility']=="1"){
                        $str .= "<option value='1' selected='selected'>Mindenki számára látható</option>";
                    } else {
                        $str .= "<option value='1'>Mindenki számára látható</option>";
                    }
                    if ($db['visibility']=="2"){
                        $str .= "<option value='2' selected='selected'>Csak a felhasználók számára látható</option>";
                    } else {
                        $str .= "<option value='2'>Csak a felhasználók számára látható</option>";
                    }
                $str .= "</select>";
            $str .= "</td>";
        $str .= "</tr>";
            
        $str .= "<tr>";
            $str .= "<td class='first'>Típus:</td>";
            $str .= "<td>";
                $str .= "<select name='type'>";
                    if ($db["type"]=="1"){
                        $str .= "<option value='1' selected='selected'>Hír alkategória - felhasználók írhatnak bele</option>";
                    } else {
                        $str .= "<option value='1'>Hír alkategória - felhasználók írhatnak bele</option>";
                    }
                    if ($db["type"]=="2"){
                        $str .= "<option value='2' selected='selected'>Hír alkategória - felhasználók nem írhatnak bele</option>";
                    } else {
                        $str .= "<option value='2'>Hír alkategória - felhasználók nem írhatnak bele</option>";
                    }
                    if ($db["type"]=="4"){
                        $str .= "<option value='4' selected='selected'>Cikk alkategória - felhasználók írhatnak bele</option>";
                    } else {
                        $str .= "<option value='4'>Cikk alkategória - felhasználók írhatnak bele</option>";
                    }
                    if ($db["type"]=="5"){
                        $str .= "<option value='5' selected='selected'>Cikk alkategória - felhasználók nem írhatnak bele</option>";
                    } else {
                        $str .= "<option value='5'>Cikk alkategória - felhasználók nem írhatnak bele</option>";
                    }
                $str .= "</select>";
            $str .= "</td>";
        $str .= "</tr>";
            
        $str .= "<tr>";
            $str .= "<td colspan='2' class='sbm'>";
                $str .= "<input type='hidden' name='engine' value='cm' />";
                $str .= "<input type='hidden' name='module' value='editsubcat' />";
                $str .= "<input type='hidden' name='id' value='".$db['id']."' />";
                $str .= "<input type='submit' name='edit' value='Módosít' />";
            $str .= "</td>";
        $str .= "</tr>";
            
    $str .= "</form>";
    $str .= "</table>";
    
    return $str;
}

/**
 * New post form
 */
function newPostForm($subcats, $error="")
{
    $str  = "<h1 class='title'>Új cikk/hír</h1>";
    
    if (!empty($error)){
        $str .= "<br />$error<br />";
    }
    
    $str .= "<table cellpadding='0' cellspacing='0' class='tbl'>";
    $str .= "<form action='index.php' method='POST' enctype='multipart/form-data'>";
        
        $str .= "<tr>";
            $str .= "<td class='first'>Alkategória:</td>";
            $str .= "<td><select name='subcat'>";
                foreach($subcats as $subcat){
                    $str .= "<option value='".$subcat['id']."'>".$subcat['title']."</option>";
                }
            $str .= "</select></td>";
        $str .= "</tr>";
        
        $str .= "<tr>";
            $str .= "<td class='first'>Cím:</td>";
            $str .= "<td><input type='text' name='title' maxlength='255' /></td>";
        $str .= "</tr>";
            
        $str .= "<tr>";
            $str .= "<td class='first'>Link:</td>";
            $str .= "<td><input type='text' name='url' maxlength='255' /></td>";
        $str .= "</tr>";
        
        $str .= "<tr>";
            $str .= "<td class='first'>Kulcsszavak:</td>";
            $str .= "<td><input type='text' name='keywords' maxlength='255' /></td>";
        $str .= "</tr>";
        
        $str .= "<tr>";
            $str .= "<td class='first'>Főkép:</td>";
            $str .= "<td><input type='file' name='img' id='img' /></td>";
        $str .= "</tr>";
        
        $str .= "<tr>";
            $str .= "<td class='first'>Bevezető:</td>";
            $str .= "<td><textarea name='summary' rows='30' cols='50'></textarea></td>";
        $str .= "</tr>";
        
        $str .= "<tr>";
            $str .= "<td class='first'>Tartalom:</td>";
            $str .= "<td><textarea name='content' rows='100' cols='80' class='txtarea480'></textarea></td>";
        $str .= "</tr>";
        
        $str .= "<tr>";
            $str .= "<td class='first'>Típus</td>";
            $str .= "<td><select name='type'>";
                $str .= "<option value='0'>Rejtett</option>";
                $str .= "<option value='1'>Lehet kommentelni</option>";
                $str .= "<option value='2'>Nem lehet kommentelni</option>";
                $str .= "<option value='3'>Fontos - lehet kommentelni</option>";
                $str .= "<option value='4'>Fontos - nem lehet kommentelni</option>";
            $str .= "</select></td>";
        $str .= "</tr>";
        
            
        $str .= "<tr>";
            $str .= "<td colspan='2' class='sbm'>";
                $str .= "<input type='hidden' name='engine' value='cm' />";
                $str .= "<input type='hidden' name='module' value='newpost' />";
                $str .= "<input type='submit' name='add' value='Hozzáadás' />";
            $str .= "</td>";
        $str .= "</tr>";
            
    $str .= "</form>";
    $str .= "</table>";
    
    return $str;
}

/**
 * Edit post form.
 */
function selectPostForm($db, $errno="")
{
    $str  = "<h1 class='title'>Cikk/hír szerkesztése</h1>";
    
    if (!empty($error)){
        $str .= "<br />$error<br />";
    }
    
    $str .= "<table cellpadding='0' cellspacing='0' class='tbl'>";
    $str .= "<form action='index.php' method='POST'>";
    
    $str .= "<tr>";
        $str .= "<td class='first'>Cikk/Hír kiválasztása</td>";
        $str .= "<td><select name='post'>";
            if (is_array(end($db))){
                foreach($db as $item){
                    $str .= "<option value='".$item['id']."'>".$item['title']."</option>";
                }
            } else {
                $str .= "<option value='".$db['id']."'>".$db['title']."</option>";
            }
        $str .= "</select></td>";
    $str .= "</tr>";
    
    $str .= "<tr>";
        $str .= "<td colspan='2' class='sbm'>";
            $str .= "<input type='hidden' name='engine' value='cm' />";
            $str .= "<input type='hidden' name='module' value='editpost' />";
            $str .= "<input type='submit' name='select' value='Szerkesztés' />";
            $str .= "<input type='submit' name='select' value='Törül' />";
        $str .= "</td>";
    $str .= "</tr>";
    
    $str .= "</form>";
    $str .= "</table>";
    
    return $str;    
}

/**
 * Edit post form.
 */
function editpostForm($db, $subcats, $error="")
{
    $str  = "<h1 class='title'>Cikk/hír szerkesztése</h1>";
    
    if (!empty($error)){
        $str .= "<br />$error<br />";
    }
    
    $str .= "<table cellpadding='0' cellspacing='0' class='tbl'>";
    $str .= "<form action='index.php' method='POST'>";
        
        $str .= "<tr>";
            $str .= "<td class='first'>Alkategória:</td>";
            $str .= "<td><select name='subcat'>";
                foreach($subcats as $subcat){
                    if ($subcat["id"]==$db["subcat"]){
                        $str .= "<option value='".$subcat['id']."' selected='selected'>".$subcat['title']."</option>";
                    } else {
                        $str .= "<option value='".$subcat['id']."'>".$subcat['title']."</option>";
                    }
                }
            $str .= "</select></td>";
        $str .= "</tr>";
        
        $str .= "<tr>";
            $str .= "<td class='first'>Cím:</td>";
            $str .= "<td><input type='text' name='title' maxlength='255' value='".$db['title']."' /></td>";
        $str .= "</tr>";
            
        $str .= "<tr>";
            $str .= "<td class='first'>Link:</td>";
            $str .= "<td><input type='text' name='url' maxlength='255' value='".$db['url']."' /></td>";
        $str .= "</tr>";
        
        $str .= "<tr>";
            $str .= "<td class='first'>Kulcsszavak:</td>";
            $str .= "<td><input type='text' name='keywords' maxlength='255' value='".$db['keywords']."' /></td>";
        $str .= "</tr>";
        
        $str .= "<tr>";
            $str .= "<td class='first'>Bevezető:</td>";
            $str .= "<td><textarea name='summary' rows='30' cols='50'>".$db['summary']."</textarea></td>";
        $str .= "</tr>";
        
        $str .= "<tr>";
            $str .= "<td class='first'>Tartalom:</td>";
            $str .= "<td><textarea name='content' rows='100' cols='80' class='txtarea480'>".$db['content']."</textarea></td>";
        $str .= "</tr>";
        
        $str .= "<tr>";
            $str .= "<td class='first'>Típus</td>";
            $str .= "<td><select name='type'>";
                if ($db["type"]=="0"){
                    $str .= "<option value='0' selected='selected'>Rejtett</option>";
                } else {
                    $str .= "<option value='0'>Rejtett</option>";
                }
                if ($db["type"]=="1"){
                    $str .= "<option value='1' selected='selected'>Lehet kommentelni</option>";
                } else {
                    $str .= "<option value='1'>Lehet kommentelni</option>";
                }
                if ($db["type"]=="2"){
                    $str .= "<option value='2' selected='selected'>Nem lehet kommentelni</option>";
                } else {
                    $str .= "<option value='2'>Nem lehet kommentelni</option>";
                }
                if ($db["type"]=="3"){
                    $str .= "<option value='3' selected='selected'>Fontos - lehet kommentelni</option>";
                } else {
                    $str .= "<option value='3'>Fontos - lehet kommentelni</option>";
                }
                if ($db["type"]=="4"){
                    $str .= "<option value='4' selected='selected'>Fontos - nem lehet kommentelni</option>";
                } else {
                    $str .= "<option value='4'>Fontos - nem lehet kommentelni</option>"; 
                }
            $str .= "</select></td>";
        $str .= "</tr>";
        
            
        $str .= "<tr>";
            $str .= "<td colspan='2' class='sbm'>";
                $str .= "<input type='hidden' name='id' value='".$db['id']."' />";
                $str .= "<input type='hidden' name='engine' value='cm' />";
                $str .= "<input type='hidden' name='module' value='editpost' />";
                $str .= "<input type='submit' name='edit' value='Módosít' />";
            $str .= "</td>";
        $str .= "</tr>";
            
    $str .= "</form>";
    $str .= "</table>";
    
    return $str;
}

?>