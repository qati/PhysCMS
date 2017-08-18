<?php

/**
 * @author Attila
 * @copyright 2011
 * @package core
 * @subpackage contentManager
 */


/**
 * Include contentManager exception class.
 */
require_once CORE."contentManager/core-contentmanagerexception-class.php";

/**
 * Include contentManager constans.
 */
require_once CORE."contentManager/core-contentmanager-consts.php";


/**
 * ContentManager class.
 * @package core
 * @subpackage contentManager
 */
class ContentManager
{
    /**
     * Reference to db object.
     * @access protected
     */
    protected $db = NULL;
    /**
     * Reference to info object.
     * @access protected
     */
    protected $info = NULL;
    /**
     * Reference to community object.
     * @access protected
     */
    protected $com = NULL;
    /**
     * ContentManager: News tables.
     * @access protected
     */
    protected $cTB = array("cat"=>"", "post"=>"", "comment"=>"");
    
    /**
     * Constructor.
     */
    public function __construct(MySQL &$db, Info &$info, Community &$com, $cmTBs, $xmlLogFile)
    {
        if (!CMException::openXML($xmlLogFile)){
            CMException::fatal_error(CMFERR_OPENXML, __FILE__, __LINE__, "Falied to open xml log file.", true);
            return;
        }
        
        $this->db             = $db;
        $this->info           = $info;
        $this->com            = $com;
        $this->cTB["cat"]     = $cmTBs[0];
        $this->cTB["post"]    = $cmTBs[1];
        $this->cTB["comment"] = $cmTBs[2];
    }
    
    /**
     * Destructor.
     */
    public function __destruct()
    {
        if (!CMException::saveXML()){
            CMException::fatal_error(CMFERR_SAVEXML, __FILE__, __LINE__, "Falied to save xml log file.", true);
            return;
        }
        
        unset($this->db);
        unset($this->info);
        unset($this->cTB);
    }
    
    /**
     * Public function: getCatID
     * @access public
     * @param string $url
     * @param boolean $subcat default false
     * @param boolean $isArticle default false
     * @return int
     */
    public function getCatID($url, $subcat=false, $isArticle=false)
    {
        $this->db->real_escape_string($url);
        try {
            $cond = "";
            if ($isArticle){
                $cond .= "AND (type='3' OR type='4' OR type='5')";
            }
            if ($subcat==true){
                $res = $this->db->get($this->cTB["cat"], "cat", "WHERE id='$url' $cond LIMIT 1");
                return isset($res["cat"]) ? $res["cat"] : false;   
            }
            $res = $this->db->get($this->cTB["cat"], "id", "WHERE url='$url' $cond LIMIT 1");
            return isset($res["id"]) ? $res["id"] : false;
        } catch(MySQLException $e){
            if ($e->getMode()==NO_RES){
                $e->log();
            }
            return false;
        }
        return false;
    }
    
    /**
     * Public function: getCatURL
     * @access public
     * @param int $id
     * @return string
     */
    public function getCatURL($id)
    {
        $this->db->real_escape_string($id);
        try {
            $res = $this->db->get($this->cTB["cat"], "url", "WHERE id='$id' LIMIT 1");
            return isset($res["url"]) ? $res["url"] : false;
        } catch(MySQLException $e){
            if ($e->getMode()==NO_RES){
                $e->log();
            }
            return false;
        }
        return false;
    }
    
    /**
     * Public function: getAllCat
     * @access public
     * @param int $type (1-news; 2-posts;3-admin)
     * @return array
     */
    public function getAllCat($type=1)
    {
        try {
            $condition  = "";
            if ($type==1){
                $condition .= "WHERE type='0' AND cat IS NULL AND visibility='1' ";
                $condition .= ($this->com->isUser()) ? "OR visibility='2' " : "";
                $condition .= "ORDER BY id ASC";
            } elseif ($type==2){
                $condition .= "WHERE (type='3') AND (cat IS NULL) AND (visibility='1'";
                $condition .= ($this->com->isUser()) ? " OR visibility='2') " : ")";
                $condition .= "ORDER BY id ASC";
            } elseif ($type==3){
                $condition .= "WHERE (type='0' or type='3') AND cat IS NULL ORDER BY id ASC";
            } else {
                throw new CMException(CM_INVALIDPARAM);
            }
            return $this->db->get($this->cTB["cat"], "*", $condition);
        } catch(MySQLException $e) {
            if ($e->getMode()==NO_RES){
                $e->log();
                throw new CMException(CM_NORES);
            }
            throw new CMException(CM_NODATA);
        }
    }
    
    /**
     * Public function: getAllSubCat
     * @access public
     * @param int $cat
     * @param boolean $admin
     * @return array
     */
    public function getAllSubCat($cat=false, $admin=false)
    {
        $this->db->real_escape_string($cat);
        try {
            $condition  = "WHERE type!='0' AND type!='3' AND cat".(($cat==false) ? " IS NOT NULL" : "='$cat'");
            $condition .= " AND visibility='1'".($this->com->isUser() ? " OR visibility='2'" : "");
            $condition .= ($admin===true) ? " OR visibility='0'" : "";
            $condition .= " ORDER by id ASC";
            return $this->db->get($this->cTB["cat"], "*", $condition);
        } catch(MySQLException $e) {
            if ($e->getMode()==NO_RES){
                $e->log();
                throw new CMException(CM_NORES);
            }
            throw new CMException(CM_NODATA);
        }
    }
    
    /**
     * Public function: getPostID
     * @access public
     * @param string $url
     * @return string
     */
    public function getPostID($url)
    {
        $this->db->real_escape_string($url);
        try {
            $res = $this->db->get($this->cTB["post"], "id", "WHERE url='$url' LIMIT 1");
            return isset($res["id"]) ? $res["id"] : false;
        } catch(MySQLException $e){
            if ($e->getMode()==NO_RES){
                $e->log();
            }
            return false;
        }
        return false;
    }
    
    /**
     * Public function: getShortPost
     * @access public
     * @param int $page
     * @param int $cat default false
     * @param int $isNews default true
     * @param int $admin default false
     * @return array
     */
    public function getShortPost($page=1, $cat=false, $isNews=true, $admin=false)
    {
        $this->db->real_escape_string($page);
        $this->db->real_escape_string($cat);
        try {
            $max    = $this->info->postPerPage;
            $page   = ($page-1)*$max;
            
            if ($cat==false){
                $tb1 = $this->cTB['post'];
                $tb2 = $this->cTB['cat'];
                
                $select  = $tb1.".id as id, ".$tb1.".title as title, ".$tb1.".summary as summary, ";
                $select .= $tb1.".user_id as user_id, ".$tb1.".opened as opened, ".$tb1.".created as created, ";
                $select .= $tb1.".comments as comments";
                
                $sql = "SELECT $select FROM $tb1, $tb2 WHERE ".$tb1.".subcat=".$tb2.".id ";
                if ($admin===true){
                    $sql .= "ORDER by ".$tb1.".created DESC";
                } else {
                    $sql .= "AND ";
                    if ($isNews){
                        $sql .= "(".$tb2.".type='1' OR ".$tb2.".type='2')";
                    } else {
                        $sql .= "(".$tb2.".type='4' OR ".$tb2.".type='5')";
                    }
                    $sql .= " ORDER by ".$tb1.".created DESC LIMIT $page, $max";
                }
                return $this->db->execute($sql);
                 
            }
        
            $subcats   = $this->db->get($this->cTB["cat"], "id", "WHERE type!=0 AND type!=3 AND cat='$cat'");
            $condition = "WHERE type!=0 AND";
            
            if (is_array(end($subcats))){
                $first = true;
                foreach($subcats as $subcat){
                    if ($first){
                        $condition .= " subcat='{$subcat['id']}'";
                        $first = false;
                    } else {
                        $condition .= " OR subcat='{$subcat['id']}'";
                    }
                }
            } else {
                $condition .= " subcat='{$subcats['id']}'";
            }
            $condition .= " ORDER by created DESC LIMIT $page, $max";
       
            return $this->db->get($this->cTB["post"], "*", $condition);
            
        } catch(MySQLException $e) {
            if ($e->getMode()==NO_RES){
                $e->log();
                throw new CMException(CM_NORES);
            }
            throw new CMException(CM_NODATA);
        }
    }
    
    /**
     * Public function: getPost
     * @access public
     * @param int $cond
     * @param boolean $url
     * @return array
     */
    public function getPost($cond, $url=false)
    {
        $this->db->real_escape_string($cond);
        $condition = "";
        if ($url){
            $condition .= "url='$cond'";
        } else {
            $condition .= "id='$cond'";
        }
        try {
            $res = $this->db->get($this->cTB["post"], "*", "WHERE $condition LIMIT 1");
            $this->db->update($this->cTB["post"], "opened='".($res['opened']+1)."'", "$condition");
            return $res;
        } catch(MySQLException $e) {
            if ($e->getMode()==NO_RES){
                $e->log();
                throw new CMException(CM_NORES);
            }
            throw new CMException(CM_NODATA);
        }
    }
    
    /**
     * Public function: getComments
     * @access public
     * @param int $page
     * @return array
     */
    public function getComments($id, $page=1)
    {
        if (!is_string($page) && !preg_match("/[0-9]+/i", $page)){
            throw new CMException(CM_INVALIDPARAM);
        }
        
        try {
            $start = ($page-1)*$this->info->commentsPerPage;
            $end   = $this->info->commentsPerPage;
            $res   = $this->db->get($this->cTB["comment"], "*", "WHERE post_id='$id' ORDER BY created DESC LIMIT $start, $end");
            
            return $res;
            
        }catch(MySQLException $e){
            if ($e->getMode()==NO_RES){
                $e->log();
                throw new CMException(CM_NORES);
            }
            return false;
        }
    }
    
    /**
     * Public function: countComments
     * @access public
     * @param string $id
     * @return int
     */
    public function countComments($id)
    {
        if (!preg_match("/^[0-9]+$/", $id)){
            throw new CMException(CM_INVALIDPARAM);
        }
        
        try{
            $db = $this->db->get($this->cTB["comment"], "COUNT(*) AS num", "WHERE post_id='$id'");
            return $db["num"];
        }catch(MySQLException $e){
            if ($e->getMode()==NO_RES){
                $e->log();
                throw new CMException(CM_NORES);
            }
            return false;
        }
    }
    
    /**
     * Public function: addComment
     * @access public
     * @param string $id
     * @param string $msg
     * @return boolean
     */
    public function addComment($id, $msg)
    {
        if (!$this->com->isUser() || !is_string($msg) || !preg_match("/^[0-9]+$/", $id)){
            throw new CMException(CM_INVALIDPARAM);
        }
        
        try {
            $msg = htmlentities($msg, ENT_QUOTES, "UTF-8");
            $this->db->real_escape_string($msg);
            
            $vals = "'{$id}', '".$this->com->getUserID()."', '{$msg}', '".getTime()."'";
            
            $this->db->insert($this->cTB["comment"],"post_id, user_id, msg, created", $vals);
            
            $db = $this->db->get($this->cTB["post"], "comments", "WHERE id='$id'");
            
            $this->db->update($this->cTB["post"],"comments='".(++$db["comments"])."'", "id='$id'");
        }catch(MySQLException $e){
            throw new CMException(CM_NOINSERT);
        }
    }
}

?>