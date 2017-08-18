<?php

/**
 * @author Attila
 * @copyright 2011
 * @package core
 * @subpackage ajax
 */


/**
 * Ajax class.
 * @package core
 */
class Ajax
{
    /**
     * Requested engine.
     * @var string
     * @access private
     */
    private $engine = NULL;
    /**
     * Requested module.
     * @var string
     * @access private
     */
    private $module = NULL;
    /**
     * Request.
     * @var array
     * @access public
     */
    public $request = NULL;
     /**
     * Response.
     * @var string
     * @access public
     */
     public $response = NULL;
    
    /**
     * Authentification.
     * @access private
     * @return boolean
     */
    private function auth()
    {    
        $auth = isset($_POST["auth"]) ? $_POST["auth"] : (isset($_GET["auth"]) ? $_GET["auth"] : NULL);
        
        if ($auth==NULL || strlen($auth)!=32){
            return false;
        }
        
        return true;
    }
    
    /**
     * Construct.
     */
    public function __construct()
    {
        if (!$this->auth()){
            throw new Error(ERRAJAX_NOAUTH, "Authentification falied!");
        }
        
        $engine = isset($_POST["engine"]) ? $_POST["engine"] : (isset($_GET["engine"]) ? $_GET["engine"] : false);
        $module = isset($_POST["module"]) ? $_POST["module"] : (isset($_GET["module"]) ? $_GET["module"] : false);
        
        if (!$engine || !$module){
            throw new Error(ERRAJAX_INVALIDREQUEST, "Request isn't valid!");
        }
        
        $this->engine = $engine;
        $this->module = $module;
        
        if (isset($_POST["auth"])){
            unset($_POST["auth"]);
            unset($_POST["engine"]);
            unset($_POST["module"]);
            
            $this->request = $_POST;  
        } elseif(isset($_GET["auth"])){
            unset($_GET["auth"]);
            unset($_GET["engine"]);
            unset($_GET["module"]);
            
            $this->request = $_GET;
        } else {
            throw new Error(ERRAJAX_INVALIDREQUEST, "Request isn't valid!");
        }
        
        $this->response = "";
        return;
    }
    
    /**
     * Destructor.
     */
    public function __destruct()
    {
        echo $this->response;
    }
    
    /**
     * Public static function getAuthKey.
     * @access public
     * @static
     * @return string
     */
    public static function getAuthKey()
    {
        $key = "";
        for($i=0;$i<32;$i++){
            $part  = rand(1, 3);
            $first = ($part==1) ? 48 : (($part==2) ? 65 : 97);
            $last  = ($part==1) ? 57 : (($part==2) ? 90 : 122);
            
            $key .= chr(rand($first, $last));
        }
        
        return $key;
    }
    
    /**
     * Public function get engine.
     * @access public
     * @return string
     */
    public function getEngine()
    {
        return $this->engine;
    }
    
    /**
     * Public function get module.
     */
    public function getModule()
    {
        return $this->module;
    }
    
    /**
     * Public function get_msg.
     * @access public
     * @param string $code
     * @return string
     * @static
     */
    public static function get_msg($code)
    {
        if (!preg_match("/^([a-zA-Z]{3})|([a-zA-Z]{4})[0-9]+$/i", $code) || !defined("ajaxmsg".$code)){
            $code = ERRAJAX_INVALIDPARAM;
        }    
        return eval("return ajaxmsg".$code.";");
    }
}

?>