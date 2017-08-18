<?php

/**
 * @author Attila
 * @copyright 2011
 * @package core
 * @subpackage system
 * @version 1.0
 */


/**
 * Include error core file.
 */
require_once CORE."core-error-class.php";

/**
 * Include firewall core file.
 */
require_once CORE."core-firewall-class.php";

/**
 * System class.
 * @package core
 * @subpackage system
 */
class System
{
    /**
     * Reference to database object.
     * @var MySQL
     * @access protected
     */
    protected $db = NULL;
    /**
     * FireWall object.
     */
    protected $fireWall = NULL;
    /**
     * Script start time.
     * @var integer
     * @access protected
     */
    protected $sTime = NULL;
    
    /**
     * Constructor.
     */
    public function __construct(MySQL &$mysql, $xmlLogFile, $ipBANN, $fireWallON=true)
    {
        $this->db           = $mysql;
        $this->fireWall     = $fireWallON ? new FireWall($ipBANN) : NULL;
        $this->sTime        = microtime(true);
        $this->smtpResponse = false;
        $this->smtp         = false;
        
        /** Open error log file. **/
        if (!Error::openXML($xmlLogFile)){
            die(get_msg(ERROR_OPENXMLLOG));
        }
        
        /** FireWall **/
        if ($fireWallON){
            $this->fireWall->unsetREGISTERGLOBALS();
            $this->fireWall->protect('IPBANN');
            $this->fireWall->protect('SUPERGLOBALS', "COOKIE");
            $this->fireWall->protect('SUPERGLOBALS', "POST");
            $this->fireWall->protect('SUPERGLOBALS', "GET");
            $this->fireWall->protect('URL');
            $this->fireWall->protect('POSTfromSERVER');
            $this->fireWall->protect('SANTY');
            $this->fireWall->protect('BOTS');
            $this->fireWall->protect('REQUESTMETHOD');
            $this->fireWall->protect('DOS');
            $this->fireWall->protect('SQL');
            $this->fireWall->protect('CLICKATACK');
            $this->fireWall->protect('XSSATACK');
        }
        
        /** Start output buffering **/
        if (!ini_get('output_buffering')){
            if (!ob_start("ob_gzhandler")){
                if (!ob_start()){
                    Error::fatal_error(ERROR_OBSTART, __FILE__, __LINE__, "Falied to start output buffering", true);
                    return;
                }
            }
        }
        
        /** Start session **/
        if (!session_start()){
            Error::fatal_error(ERROR_SESSIONSTART, __FILE__, __LINE__, "Falied to start session", true);
        }
        
        return;
    }
    
    /**
     * Destructor
     */
    public function __destruct()
    {
        unset($this->db);
        unset($this->fireWall);
        unset($this->sTime);
        
        /** Send datas and turn off output buffering **/
        if (!ini_get('output_buffering')){
            ob_flush();
            ob_end_clean();
        }
        
        /** Save error log file **/
        if (!Error::saveXML()){
           die(get_msg(ERROR_SAVEXMLLOG));
        }
        
        return;
    }
    
    /**
     * Gentime
     * @access public
     * @return integer script's runtime
     */
    public function gentime()
    {
        return round(microtime(true)-$this->sTime, 4);
    }
    
    /**
     * Redirect
     * @access public
     * @return none
     * @static
     */
    public static function redirect($page)
    {
        header("Location: ".URL.$page);
    }
}

?>