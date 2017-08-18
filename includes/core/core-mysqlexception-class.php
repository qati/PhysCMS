<?php

/**
 * @author Attila
 * @copyright 2011
 * @package core
 * @subpackage mysql
 */
 
 
 /**
  * Include the exception mode file.
  */
 require_once CORE."core-mysqlexception-consts.php";

/**
 * MySQL Exception Class
 * @package core
 * @subpackage mysql
 */
class MySQLException extends Exception
{
    /**
     * dbh private static variable
     * @access private
     * @var resource
     * @static
     */
    private static $dbh;
    /**
     * xml private static variable
     * @access private
     * @var resource
     * @static
     */
    private static $xml;
    /**
     * xmlfile private static variable
     * @access private
     * @var string
     * @static
     */
    private static $xmlfile;
    /**
     * mode private variable
     */
    private $mode;
    
    /**
     * constructor
     */
    public function __construct($mode=fasle)
    {
        parent::__construct();
        $this->code    = self::$dbh->errno;
        $this->message = self::$dbh->error;
        $this->mode=$mode;
    }
    
    /**
     * static function: set up dbh
     * @static
     * @param reference to a resource $dbh db header
     * @return none
     */
    public static function setDBH(mysqli &$dbh)
    {
        self::$dbh=$dbh;
        return;
    }
    
    /**
     * static function: destroy dbh
     * @static
     */
    public static function destroyDBH()
    {
        self::$dbh=NULL;
        return;
    }
    
    /**
     * open the xml file
     * @param string $xml path to xml log file
     * @return boolean
     * @static
     */
    public static function openXML($xml)
    {
        if (!is_string($xml)){
            return false;
        }
        
        self::$xmlfile=$xml;
        self::$xml=new DOMDocument;
        
        return self::$xml->load(self::$xmlfile);
    }
    
    /**
     * save datas and close the xml file
     * @return boolean
     * @static
     */
    public static function saveXML()
    {
        return (self::$xml->save(self::$xmlfile)) ? true : false;
    }
    
    /**
     * fatal error
     * @return none
     * @static
     */
     public static function fatal_error($errno, $file, $line, $msg, $connect_error=false, $xml_error=false)
     {
        if ($connect_error){
            die(MSG_FALIEDDBCON);
        }
        
        if ($xml_error){
            die(MSG_FALIEDXML);
        }
        
        $fe["FILE"] = $file;
        $fe["LINE"] = $line;
        $fe["MSG"]  = $msg;
        $fe["DES"]  = array("errno"=>self::$dbh->errno, "error"=>self::$dbh->error);
        $fe["DATE"] = getTime();
        $fe["IP"]   = $_SERVER["REMOTE_ADDR"];
        
        $dat=xmlspecialchars(addslashes(serialize($fe)));
        
        $parent=self::$xml->getElementsByTagName("fatal")->item(0);
        $parent->appendChild(self::$xml->createElement("item",$dat));
        
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
     * get exception mode
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
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