<?php

/**
 * @author Attila
 * @copyright 2011
 * @package core
 * @subpackage mysql
 * @version 1.0
 */
 

 /**
  * Include the MySQLException class.
  */
 require_once CORE."core-mysqlexception-class.php";
 

/**
 * MySQL class
 * @package core
 * @subpackage mysql
 */
class MySQL
{
    /**
     * dbh private variable
     * @access private
     * @var resource
     */
    private $dbh;
    
    /**
     * constructor
     */
    public function __construct($host, $user, $pass, $db, $xmlLogFile)
    {
        /** Open database error log file. **/
        if (!MySQLException::openXML($xmlLogFile)){
            MySQLException::fatal_error(FATAL_NOLOG,__FILE__, __LINE__, "Unable to open xmlLog file.", false, true);
            die(MSG_FALIEDXML);
            return;
        }
        
        /** Make database connection. **/
        $this->dbh = new mysqli($host, $user, $pass, $db);
        MySQLException::setDBH($this->dbh);
        if ($this->dbh->connect_error){
            MySQLException::fatal_error(FATAL_NOCON, __FILE__, __LINE__, "Unable to connect to database.", true);
            die(MSG_FALIEDDBCON);
            return;
        }
        
        /** Set up database conection. **/
        $sqls[] = "SET NAMES 'utf8';";
        $sqls[] = "SET CHARACTER SET 'utf8';";
        $sqls[] = "SET COLLATION_CONNECTION='utf8_unicode_ci';";
        $sqls[] = "SET character_set_results ='utf8';";
        $sqls[] = "SET character_set_server='utf8';";
        $sqls[] = "SET character_set_client='utf8';";
        foreach($sqls as $sql){
            $this->dbh->query($sql);
            if ($this->dbh->error){
                MySQLException::fatal_error(FATAL_NOSETS, ___FILE__, __LINE__, "Unable to configure the db con.");
                die(MSG_FALIEDDBCON);
                return;
            }
        }
        return;
    }
    
    /**
     * destruct
     */
    public function __destruct()
    {
        /** Destroy database resource. **/
        MySQLException::destroyDBH();
        
        /** Save database error file. **/
        if (!MySQLException::saveXML()){
            MySQLException::fatal_error(FATAL_NOXMLSAVE, __FILE__, __LINE__, "Unable to save errors.", false, true);
            die(MSG_FALIEDDBCON);
            return;
        }
        
        /** Kill database connection. **/
        if ($this->dbh){
            $this->dbh->close();
        }
    }
    
    /**
     * real_escape_string
     * @param string $str string to escape
     * @access public 
     * @return none
     */
    public function real_escape_string(&$str)
    {
        $str = $this->dbh->real_escape_string($str);
        return;
    }
    
    /**
     * Get datas from database.
     * @parm string $table table
     * @parm string $cols columns
     * @parm string $condition condition
     * @access public
     * @return mixed
     */
    public function get($table, $cols="*", $condition="")
    {
        if (!$res=$this->dbh->query("SELECT $cols FROM `$table` $condition")){
            throw new MySQLException(NO_RES);
        }
        if ($res->num_rows<=0){
            throw new MySQLException(NO_DATA);
        }
        if ($res->num_rows==1){
            return $res->fetch_assoc();
        }
        for($db=array();$row=$res->fetch_assoc();){
            $db[]=$row;
        }
        return $db;
    }
    
    /**
     * Insert datas to database.
     * @param string $table table
     * @param string $cols columns
     * @param string $vals values
     * @access public
     * @return boolean
     */
    public function insert($table, $cols, $vals)
    {
        if (!$this->dbh->query("INSERT INTO `$table` ".(($cols=='*')?"":"($cols) ")."VALUES ($vals)")){
            throw new MySQLException(FALIED_INSERT);
        }
        return true;
    }
    
    /**
     * Update a row.
     * @param string $table table
     * @param string $set set
     * @param string $where where
     * @access public
     * @return boolean
     */
    public function update($table, $set, $where)
    {
        if (!$this->dbh->query("UPDATE `$table` SET $set WHERE $where")){
            throw new MySQLException(FALIED_UPDATE);
        }
        return true;
    }
    
    /**
     * Delete a row
     * @param string $table table
     * @param string $where where
     * @access public
     * @return boolean
     */
    public function delete($table, $where)
     {
        if (!$this->dbh->query("DELETE FROM `$table` WHERE $where")){
            throw new MySQLException(FALIED_DELETE);
        }
        return true;
     }
     
     /**
      * Execute a sql code.
      * @param string $query query
      * @return MySQLStmt
      * @access public
      */
    public function execute($query)
    {
        if (!$res=$this->dbh->query($query)){
            throw new MySQLException(NO_RES);
        }
        if ($res->num_rows<=0){
            throw new MySQLException(NO_DATA);
        }
        if ($res->num_rows==1){
            return $res->fetch_assoc();
        }
        for($db=array();$row=$res->fetch_assoc();){
            $db[]=$row;
        }
        return $db;
    }
}

?>