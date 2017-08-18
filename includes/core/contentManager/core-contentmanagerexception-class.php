<?php

/**
 * @author Attila
 * @copyright 2011
 * @package core
 * @subpackage contentManager
 */


/**
 * Include contentManager exception constans.
 */
require_once CORE."contentManager/core-contentmanagerexception-consts.php";

/**
 * Include contentManager exception messages.
 */
require_once CORE."contentManager/core-contentmanagerexception-msgs.php";


/**
 * CMException class.
 * @package core
 * @subpackage contentManager
 */
class CMException extends Exception
{
    /**
     * XML file.
     * @access private
     * @var resource
     * @static
     */
    public static $xml;
    /**
     * XML file name.
     * @access private
     * @var string
     * @static
     */
    public static $xmlFile;
    /**
     * Error mode.
     * @access private
     * @var string
     */
    public $errno;
    
    /**
     * Constructor
     */
    public function __construct($errno=NULL, $msg="")
    {
        parent::__construct($msg);
        $this->errno = $errno;
    }
    
    /**
     * Public static function: OpenXML file.
     * @access public
     * @param string $xmlFile
     * @return boolean
     * @static
     */
    public static function openXML($xmlFile)
    {
        if (!empty(self::$xml)) return false;
        if (!is_string($xmlFile)) return false;
        
        self::$xmlFile = $xmlFile;
        self::$xml     = new DOMDocument;
        
        return self::$xml->load(self::$xmlFile);
    }
    
    /**
     * Public static function: SaveXML file.
     * @access public
     * @return boolean
     * @static
     */
    public static function saveXML()
    {
        return (self::$xml->save(self::$xmlFile)) ? true : false;
    }
    
    /**
     * Public static function: fatal_error.
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
        
        $parent=self::$xml->getElementsByTagName("fatal")->item(0);
        $parent->appendChild(self::$xml->createElement("item",$dat));
        
        if ($noRedirect===true){
            die(get_msg($errno));
            return;
        }
        
        Error::redirect($errno);
        
        return;
    }
    
    /**
     * log errors
     * @return boolean
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
     * getErrno
     * @return string
     */
    public function getErrno()
    {
        return $this->errno;
    }
    
    /**
     * Public static function get log.
     * @access public
     * @static
     * @return array
     */
    public static function get_log()
    {
        $array = array("fatal"=>array(), "log"=>array());
        $fatal = self::$xml->getElementsByTagName("fatal")->item(0);
        $log   = self::$xml->getElementsByTagName("log")->item(0);
        
        foreach($fatal->childNodes as $item){
            $array["fatal"][] = stripslashes(xmlspecialchars_decode($item->nodeValue));
        }
        
        foreach($log->childNodes as $item){
            $array["log"][] = stripslashes(xmlspecialchars_decode($item->nodeValue));
        }
        
        return $array;
    }
}

?>