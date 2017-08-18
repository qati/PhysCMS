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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="hu-HU">
<head>  
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="auth" id="auth" content="<?php echo $info->ajaxAuthKey(); ?>" />
    <meta http-equiv='Content-Language' content='hun' />
    <meta http-equiv='Content-Encoding' content='gzip' />
    <meta http-equiv='Content-Style-Type' content='text/css' />
    <meta http-equiv='Content-Script-Type' content='text/javascript' />
    <meta name='robots' content='index, follow' />
    <meta name='copyright' content='Attila' />
    <meta name="generator" content="WCM 1.0" /> 
    <title><?php echo $info->title; ?></title>
    <meta name="keywords" content="<?php echo $info->keywords; ?>" />
    <meta name="description" content="<?php echo $info->description; ?>" />
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $info->siteurl; ?>script.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $info->siteurl; ?>content/theme/style.css" />
    <script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-30892611-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body>
<div id="wrapper"><div id="container" class="container">
    <div class="span-24"> 
        <div class="span-21">
            <div id="pagemenucontainer">
                <?php print_menu(); ?>
            </div>
        </div>
    </div>
    <div id="header" class="span-24">
        <div class="span-12">
            <a href="<?php echo $info->siteurl; ?>"><img src="<?php echo $info->siteurl; ?>content/theme/images/logo.png" title="<?php echo $info->title; ?>" class="logoimg" /></a>
        </div>
    </div>
    <div class="span-24">
        <div id="navcontainer">
            <?php
                if ($com->getUserLevel()<5 || !defined("WCM_ADMIN") || strpos($_SERVER["REQUEST_URI"], "/admin/index.php")===false){
                    print_submenu(); 
                }
            ?>
        </div>
    </div>