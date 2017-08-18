<?php

/**
 * @author Attila
 * @copyright 2011
 * @package core
 * @version 1.0
 */


/**
 * Info class.
 * @package core
 */
class Info
{
    /**
     * Reference to database object.
     * @var MySQL
     * @access private
     */
    private $db = NULL;
    /**
     * Reference to system object.
     * @var System
     * @access private
     */
    private $sys = NULL;
    /**
     * Table.
     * @var string
     * @access private
     */
    private $tb = "";
    /**
     * Options.
     * @var string
     * @access private
     */
    private $options = array();
    
    /**
     * Function initOptions.
     * @access private
     * @return none
     */
    private function initOptions()
    {
        try {
            $res = $this->db->get($this->tb, "option_name, option_value", "WHERE autoload='1'");
            foreach($res as $item){
                $this->options[$item["option_name"]]=$item["option_value"];
            }
        } catch (MySQLException $e){
            if ($e->getMode()==NO_RES){
                $e->log();
                die(get_msg(ERRIN_NORES));
            }
            die(get_msg(ERRIN_NODATA));
        }
        return;
    }
    
    /**
     * Function checkOptionName.
     * @access private
     * @return none
     */
    private function checkOptionName($name)
    {
        try {
            $this->db->get($this->tb, "*", "WHERE option_name='$name'");
            return true;
        } catch (MySQLException $e) {
            if ($e->getMode()==NO_RES){
                $e->log();
               Error::redirect(ERRIN_NORES);
            }
            return false;
        }
    }
    
    
    /**
     * Constructor.
     */
    public function __construct(MySQL &$mysql, System &$sys, $table)
    {
        $this->db  = $mysql;
        $this->sys = $sys;
        $this->tb  = $table;
        
        $this->initOptions();
        
        return;
    }
    
    /**
     * Destructor
     */
    public function __destruct()
    {
        unset($this->db);
        unset($this->sys);
        unset($this->tb);
        unset($this->options);
        return;
    }
    
    /**
     * Overloading __get function.
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->options)){
            return $this->options[$name];
        } elseif ($this->checkOptionName($name)){
            try {
                $res = $this->db->get($this->tb, "option_value", "WHERE option_name='$name'");
                return isset($res["option_value"]) ? $res["option_value"] : false;
            } catch (MySQLException $e){
                if ($e->getMode()==NO_RES){
                    $e->log();
                    Error::redirect(ERIN_NORES);
                    return;
                }
            }
        }
        $err = new Error(ERRIN_OGET, "Trying to get undefined attr.");
        $err->log();
        Error::redirect(ERRIN_OGET);
        return;
    }
    
    /**
     * Overloading __set function.
     */
    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->options)){
            if ($name=="title"){
                $this->options[$name] .= " | ".$value;
            } elseif ($name=="keywords"){
                $this->options[$name] .= ", ".$value;
            } else {
                $this->options[$name]=$value;
            }
            return;
        }
        $err = new Error(ERRIN_OSET, "Trying to set undefined attr.");
        $err->log();
        Error::redirect(ERRIN_OSET);
        return;
    }
    
    /**
     * Public function: option. Get a serialized option.
     * @access public
     * @param string $name
     * @return array
     */
    public function option($name)
    {
        return unserialize(stripslashes($this->$name));
    }
    
    /**
     * Get php script runtime.
     * @return string
     */
    public function getGenTime()
    {
        return $this->sys->gentime();
    }
    
    /**
     * Get ajax authentification key.
     * @return string
     */
    public function ajaxAuthKey()
    {
        return Ajax::getAuthKey();
    }

}

?>