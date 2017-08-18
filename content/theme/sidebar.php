<?php

/**
 * @author Attila
 * @copyright 2011
 */


/**
 * Security check.
 */
if (!defined("WCM")){
    header("Content-Type: text/html; charset=UTF-8");
    die("Hozzáférés megtagadva!");
}

?>
<?php if ($com->isUser()): ?>
<!-- felhasznaloi menu -->
<ul>
    <li>
        <h2><?php echo $com->getUserName(); ?></h2>
        <ul>
            <li><a href="<?php echo $info->siteurl; ?>community/user/profile">Profil</a></li>
            <li><a href="<?php echo $info->siteurl; ?>community/user/friendrequests">Barátkérelmek</a></li>
            <li><a href="<?php echo $info->siteurl; ?>community/user/avatar">Avatar</a></li>
            <li><a href="<?php echo $info->siteurl; ?>community/logout">Kijelentkezés</a></li>
        </ul>
    </li>
    
<?php if ($com->getUserLevel()>4 && defined("WCM_ADMIN") && strpos($_SERVER["REQUEST_URI"], "/admin/index.php")!==false): ?>
    <li>
        <h2>Általános</h2>
        <ul>
            <li><a href="<?php echo $info->siteurl; ?>admin/index.php?engine=settings">Beálítások</a></li>
            <li><a href="<?php echo $info->siteurl; ?>admin/index.php?engine=error">Hibák</a></li>
        </ul>
    </li>
    
    <li>
        <h2>Tartalom</h2>
        <ul>
            <li><a href="<?php echo $info->siteurl; ?>admin/index.php?engine=cm&module=newcat">Új kategória</a></li>
            <li><a href="<?php echo $info->siteurl; ?>admin/index.php?engine=cm&module=editcat">Kategória szerkesztése</a></li>
            <li><a href="<?php echo $info->siteurl; ?>admin/index.php?engine=cm&module=newsubcat">Új alkategória</a></li>
            <li><a href="<?php echo $info->siteurl; ?>admin/index.php?engine=cm&module=editsubcat">Alkategória szerkesztése</a></li>
            <li><a href="<?php echo $info->siteurl; ?>admin/index.php?engine=cm&module=newpost">Új cikk</a></li>
            <li><a href="<?php echo $info->siteurl; ?>admin/index.php?engine=cm&module=editpost">Cikk szerkesztése</a></li>
        </ul>
    </li>
</ul>
<?php endif; ?>
<?php else: ?>
<!-- normal menu -->
<ul>
    <li><?php print_loginMenu(); ?></li>
    <li>
        <h2>Menu1</h2>
        <ul>
            <li><a href="#">Link</a></li>
            <li><a href="#">Link</a></li>
            <li><a href="#">Link</a></li>
            <li><a href="#">Link</a></li>
        </ul>
    </li>
    <li>
        <h2>Menu2</h2>
        <ul>
            <li><a href="#">Link</a></li>
            <li><a href="#">Link</a></li>
            <li><a href="#">Link</a></li>
            <li><a href="#">Link</a></li>
            <li><a href="#">Link</a></li>
            <li><a href="#">Link</a></li>
            <li><a href="#">Link</a></li>
            <li><a href="#">Link</a></li>
        </ul>
    </li>
</ul>
<?php endif; ?>