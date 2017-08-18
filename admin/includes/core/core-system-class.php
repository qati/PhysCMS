<?php

/**
 * @author Attila
 * @copyright 2012
 * @package adminCore
 * @subpackage System
 */
 

/**
 * adminSystem class
 * @package adminCore
 * @subpackage System
 */
class adminSystem extends System
{
    /**
     * Constructor.
     */
    public function __construct(MySQL &$mysql, $xmlLogFile, $ipBANN, $fireWallON=true)
    {
        parent::__construct($mysql, $xmlLogFile, $ipBANN, $fireWallON);
    }
}

?>