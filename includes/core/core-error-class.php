<?php

/**
 * @author Attila
 * @copyright 2011
 * @package core
 * @subpackage system
 * @version 1.0
 */


/**
 * Include core error constans.
 */
require_once CORE."core-error-consts.php";

/**
 * Include core error messages.
 */
require_once CORE."core-error-msgs.php";

/**
 * Error class.
 * @package core
 * @subpackage system
 */
class Error extends Exception
{
    /**
     * Error log filename.
     * @var string
     * @access private
     * @static
     */
    private static $xmlfile=false;
    /**
     * Error log xml file.
     * @var resource
     * @access private
     * @static
     */
    private static $xml=false;
    /**
     * Errno.
     * @var string
     * @access private
     */
    private $errno = NULL;
    
    /**
     * Constructor.
     */
    public function __construct($errno=false, $msg="")
    {
        parent::__construct($msg);
        $this->errno = $errno;
    }
    
    /**
     * Public function open xml log file.
     * @access public
     * @return boolean
     * @static
     */
    public static function openXML($xmlFile)
    {
        if (!empty(self::$xml)) return false;
        if (!is_string($xmlFile)) return false;
        
        self::$xmlfile = $xmlFile;
        self::$xml     = new DOMDocument;
        
        return self::$xml->load(self::$xmlfile);
    }
    
    /**
     * Public function save xml log file.
     * @access public
     * @return boolean
     * @static
     */
    public static function saveXML()
    {
        return (self::$xml->save(self::$xmlfile)) ? true : false;
    }
    
    /**
     * Public function log error.
     * @access public
     * @return none
     */
    public function log()
    {
        $fe["FILE"] = $this->getFile();
        $fe["LINE"] = $this->getLine();
        $fe["CODE"] = $this->getCode();
        $fe["MSG"]  = $this->getMessage();
        $fe["TRACE"] = $this->getTraceAsString();
        $fe["DATE"] = getTime();
        $fe["IP"]   = $_SERVER["REMOTE_ADDR"];    
        $dat=xmlspecialchars(addslashes(serialize($fe)));
       
        $parent=self::$xml->getElementsByTagName("log")->item(0);
        $parent->appendChild(self::$xml->createElement("item", $dat));
        return;
    }
    
    /**
     * Public static function redirect.
     * @access public
     * @return none
     * @static
     */
    public static function redirect($errno)
    {
        header("Location: ".URL."hiba/$errno");
    }
    
    /**
     * Public static function fatal error.
     * @access public
     * @param string $errno
     * @param string $file
     * @param string $line
     * @param string $msg
     * @param boolean $noRedirect if $noRedirect===true: die(error_message) else redirect(error_page)
     * @return none
     * @static
     */
    public static function fatal_error($errno, $file, $line, $msg, $noRedirect=false)
    {
        $fe["FILE"] = $file;
        $fe["LINE"] = $line;
        $fe["MSG"]  = $msg;
        $fe["DATE"] = getTime();
        $fe["IP"]   = $_SERVER["REMOTE_ADDR"];
        $dat=xmlspecialchars(addslashes(serialize($fe)));
        
        $parent = self::$xml->getElementsByTagName("fatal")->item(0);
        $parent->appendChild(self::$xml->createElement("item", $dat));
        
        if ($noRedirect===true){
            die(get_msg($errno));
            return;
        }
        
        self::redirect($errno);
        
        return;
    }
    
    /**
     * Public function get errno.
     * @accss pubic
     * @return string
     */
    public function getErrno()
    {
        return $this->errno;
    }
    
    /**
     * Public static function hack log.
     * @access public
     * @static
     * @return none
     */
    public static function hack_log($file, $line, $msg)
    {
        $fe["FILE"]    = $file;
        $fe["LINE"]    = $line;
        $fe["MSG"]     = $msg;
        $fe["DATE"]    = getTime();
        $fe["IP"]      = $_SERVER["REMOTE_ADDR"];
        $fe["DNS"]     = gethostbyaddr($fe["IP"]);
        $fe["GET"]     = $_GET;
        $fe["POST"]    = $_POST;
        $fe["SESSION"] = $_SESSION;
        $fe["COOKIE"]  = $_COOKIE;
        $fe["REQUEST"] = $_REQUEST;
        $fe["SERVER"]  = $_SERVER;
        
        $dat=xmlspecialchars(addslashes(serialize($fe)));
        
        $parent = self::$xml->getElementsByTagName("hack")->item(0);
        $parent->appendChild(self::$xml->createElement("item", $dat));
        
        return;
    }
    
    /**
     * Public static function get log.
     * @access public
     * @static
     * @return array
     */
    public static function get_log()
    {
        $array = array("fatal"=>array(), "log"=>array(), "hack"=>array());
        $fatal = self::$xml->getElementsByTagName("fatal")->item(0);
        $log   = self::$xml->getElementsByTagName("log")->item(0);
        $hack  = self::$xml->getElementsByTagName("hack")->item(0);
        
        foreach($fatal->childNodes as $item){
            $array["fatal"][] = stripslashes(xmlspecialchars_decode($item->nodeValue));
        }
        
        foreach($log->childNodes as $item){
            $array["log"][] = stripslashes(xmlspecialchars_decode($item->nodeValue));
        }
        
        foreach($hack->childNodes as $item){
            $array["hack"][] = stripslashes(xmlspecialchars_decode($item->nodeValue));
        }
        
        return $array;
    }
}

?>