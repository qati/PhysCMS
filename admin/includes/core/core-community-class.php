<?php

/**
 * @author Attila
 * @copyright 2012
 * @package adminCore
 * @subpackage community
 */


/**
 * adminCommunity class
 * @package adminCore
 * @package community
 */
class adminCommunity extends Community
{
    /**
     * Constructor.
     */
    public function __construct(MySQL &$db, Info &$info, $logfile, $usersTable, $userWallTable)
    {
        parent::__construct($db, $info, $logfile, $usersTable, $userWallTable);
        if ($this->getUserLevel()<5){
            die("Access dennied");
        }
    }
}

?>