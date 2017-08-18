<?php

/**
 * @author Attila
 * @copyright 2011
 * @package core
 * @subpackage community
 */


/**
 * UserInfo class.
 * @package core
 * @subpackage community
 */
class UserInfo
{
    /**
     * Reference to database object
     * @access private
     */
    private $db = NULL;
    /**
     * Reference to community object
     * @access private
     */
    private $com = NULL;
    /**
     * Reference to users table name.
     * @access private
     */
    private $tb = "";
    /**
     * Reference to user id.
     * @access private
     */
    private $userID = false;
    /**
     * User options.
     * @access private
     */
    private $options = array();
    
    /**
     * Private function check_options
     * @access private
     * @param reference to  
     * @return boolean
     */
    private function check_options(&$arr=NULL)
    {
        $defaults = $this->com->get_defaultOptions();
        unset($defaults["activation"]);
        if (is_array($arr)){
            foreach($defaults as $key=>$val){
                if (!isset($arr[$key])){
                    return false;
                }
            }
        } else {
            foreach($defaults as $key=>$val){
                if (!isset($this->options[$key])){
                    return false;
                }
            }
        }
        return true;    
    }
    
    /**
     * Constructor.
     */
    public function __construct(MySQL &$db, &$tb, &$userID, &$com)
    {
         $this->db     = $db;
         $this->com    = $com;
         $this->tb     = $tb;
         $this->userID = $userID;
         
        if ($userID==0){
            $this->options = $com->get_defaultOptions();
            return;
        }
        try {
            $res = $db->get($tb, "options", "WHERE id='$userID'");
            if (!isset($res["options"])){
                throw new MySQLException(NO_DATA);
            }
            $this->options = unserialize(html_entity_decode($res["options"], ENT_QUOTES, "UTF-8"));
            if (!is_array($this->options) || !$this->check_options()){
                die(get_msg(COM_ERROR));
            }
        } catch(MySQLException $e){
            if ($e->getMode()==NO_RES){
                $e->log();
                die(get_msg(COM_NORES));
            } elseif ($e->getMode()==NO_DATA){
                die(get_msg(COM_NODATA));
            }
            die(get_msg(COM_DBERROR));
        }
        return;
    }
    
    /**
     * Destructor.
     */
    public function __destruct()
    {
        unset($this->db);
        unset($this->tb);
        unset($this->options);
        unset($this->userID);
    }
    
    /**
     * Overloading __get function.
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->options)){
            return $this->options[$name];   
        }
        $err = new ComException(null, "Trying to get undefined user option! Undefined user option: $name");
        $err->log();
        return false;
    }
    
    /**
     * Overloading __set function.
     */
    public function __set($name, $value)
    {
        $this->options[$name] = $value;
        $op = htmlentities(serialize($this->options), ENT_QUOTES, "UTF-8");
        $this->db->real_escape_string($op);
        
        try {
            $this->db->update($this->tb, "options='$op'", "id='".$this->userID."'");
        } catch(MySQLException $e){
            if ($e->getMode()==NO_RES){
                $e->log();
                throw new ComException(COM_NORES);
            }
            throw new ComException(COM_NOUPDATE);
        }
        return;
    }
    
    /**
     * Public function: options
     * @access public
     * @param int $id
     * @return array
     */
    public function options($id)
    {
        if (!preg_match("/^[0-9]+$/", $id)){
             throw new ComException(COM_INVALIDPARAM);
        }
        try {
            $res = $this->db->get($this->tb, "options", "WHERE id='$id' LIMIT 1");
            
            $op = unserialize(html_entity_decode($res["options"], ENT_QUOTES, "UTF-8"));
            if (!is_array($op) || !$this->check_options($op)){
                throw new ComException(COM_ERROR);
            }
           
            return $op;
        } catch(MySQLException $e){
            if ($e->getMode()==NO_RES){
                $e->log();
                throw new ComException(COM_NORES);
            }
            throw new ComException(COM_NODATA);
        }
    }
    
    /**
     * Public function: updateOptions
     * @access public
     * @param int $id
     * @param array $options
     * @return none
     */
    public function updateOptions($id, $options)
    {
        if (!preg_match("/^[0-9]+$/", $id) || !is_array($options) || !$this->check_options($options)){
            throw new ComException(COM_INVALIDPARAM);
        }
        try {
            
            $op = htmlentities(serialize($options), ENT_QUOTES, "UTF-8");
            $this->db->update($this->tb, "options='$op'", "id='$id'");
            
        } catch(MySQLException $e){
            if ($e->getMode()==NO_RES){
                $e->log();
                throw new ComException(COM_NORES);
            }
            throw new ComException(COM_NOUPDATE);
        }
        return;
    }
}

?>