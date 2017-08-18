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
<div class="span-24">
    <div id="footer"><?php
        echo $info->footer.$info->getGenTime();
    ?></div>
    <div id="footer2"></div>
</div>
</div></div>
</body>
</html>