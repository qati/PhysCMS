<?php

/**
 * THEME FILE.
 * @author Attila
 * @copyright 2011
 */
 
 
 /**
 * Security checks.
 */
if (!defined("WCM")){
    header("Content-Type: text/html; charset=UTF-8");
    die("Hozzáférés megtagadva!");
}

if (!isset($content)){
    header("Content-Type: text/html; charset=UTF-8");
    die("Hozzáférés megtagadva!");
}

?>
<?php get_header(); ?>
<div class="span-24" id="contentwrap"> 
    <div class="span-16"> 
        <div id="content"><?php echo $content; ?></div>
    </div>
    <div class="span-8 last">
        <div class="sidebar"><?php get_sidebar(); ?></div>
    </div>
</div>
<?php get_footer(); ?>				
