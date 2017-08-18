<?php

/**
 * @author Attila
 * @copyright 2012
 * @package adminCore
 * @subpackage contentManager
 */


/**
 * adminContentManager class
 * @package adminCore
 * @subpackage contentManager
 */
class adminContentManager extends ContentManager
{
    /**
     * Constructor.
     */
    public function __construct(MySQL &$db, Info &$info, Community &$com, $cmTB, $xmlLogFile)
    {
        parent::__construct($db, $info, $com, $cmTB, $xmlLogFile);
    }
    
    /**
     * Public function: newCat
     * @access public
     * @param
     */
    public function newCat($title, $url, $visibility, $type)
    {
        if (strlen($title)<4){
            throw new CMException(CM_ADMIN_INVALID_TITLE);
        }
        if (strlen($url)<4 || !preg_match("/^[0-9a-zA-Z\-_\.]+$/", $url)){
            throw new CMException(CM_ADMIN_INVALID_URL);
        }
        if(!preg_match("/^(0|1|2){1}$/", $visibility)){
            throw new CMException(CM_ADMIN_INVALID_VISIBILITY);
        }
        if (!preg_match("/^(0|3){1}$/", $type)){
            throw new CMException(CM_ADMIN_INVALID_TYPE);
        }
        $title = htmlentities($title, ENT_QUOTES, "UTF-8");
        $url = htmlentities($url, ENT_QUOTES, "UTF-8");
        $this->db->real_escape_string($title);
        $this->db->real_escape_string($url);
        
        try{
            $datas = "'".$url."', '".$title."', '".$visibility."', '".$type."', '".getTime()."'";
            $this->db->insert($this->cTB["cat"], "url, title, visibility, type, created", $datas);
            return;
        }catch(MySQLException $e){
            $e->log();
            throw new CMException(CM_NOINSERT);
        }
    }
    
    /**
     * Public function: newSubCat
     * @access public
     * @param
     */
    public function newSubCat($cat, $title, $url, $visibility, $type)
    {
        if (!preg_match("/^[0-9]+$/", $cat)){
            throw new CMException(CM_ADMIN_INVALID_CAT);
        }
        if (strlen($title)<4){
            throw new CMException(CM_ADMIN_INVALID_TITLE);
        }
        if (strlen($url)<4 || !preg_match("/^[0-9a-zA-Z\-_\.]+$/", $url)){
            throw new CMException(CM_ADMIN_INVALID_URL);
        }
        if(!preg_match("/^(0|1|2){1}$/", $visibility)){
            throw new CMException(CM_ADMIN_INVALID_VISIBILITY);
        }
        if (!preg_match("/^(1|2|4|5){1}$/", $type)){
            throw new CMException(CM_ADMIN_INVALID_TYPE);
        }
        $title = htmlentities($title, ENT_QUOTES, "UTF-8");
        $url = htmlentities($url, ENT_QUOTES, "UTF-8");
        $this->db->real_escape_string($title);
        $this->db->real_escape_string($url);
        
        try{
            $datas = "'".$cat."', '".$url."', '".$title."', '".$visibility."', '".$type."', '".getTime()."'";
            $this->db->insert($this->cTB["cat"], "cat, url, title, visibility, type, created", $datas);
            return;
        }catch(MySQLException $e){
            $e->log();
            throw new CMException(CM_NOINSERT);
        }
    }
    
    /**
     * Public function adminGet
     * @access public
     * @param int $what
     * @param int $id
     * @return array
     */
    public function adminGet($what, $id)
    {
        if (!preg_match("/[0-9]+/", $id)){
            throw new CMException(CM_INVALIDPARAM);
        }
        if ($what=="cat"){
            try{
                return $this->db->get($this->cTB["cat"], "*", "WHERE id='$id'");
            } catch(MySQLException $e){
                if ($e->getMode()==NO_RES){
                    $e->log();
                    throw new CMException(CM_NORES);
                }
                throw new CMException(CM_NODATA);
            }
        } elseif ($what=="post"){
             try{
                return $this->db->get($this->cTB["post"], "*", "WHERE id='$id'");
            } catch(MySQLException $e){
                if ($e->getMode()==NO_RES){
                    $e->log();
                    throw new CMException(CM_NORES);
                }
                throw new CMException(CM_NODATA);
            }
        }
    }
    
    /**
     * Public function: updateCat
     * @access public
     * @return none
     */
    public function updateCat($id, $title, $url, $visibility, $type)
    {
        if (strlen($title)<4){
            throw new CMException(CM_ADMIN_INVALID_TITLE);
        }
        if (strlen($url)<4 || !preg_match("/^[0-9a-zA-Z\-_\.]+$/", $url)){
            throw new CMException(CM_ADMIN_INVALID_URL);
        }
        if(!preg_match("/^(0|1|2){1}$/", $visibility)){
            throw new CMException(CM_ADMIN_INVALID_VISIBILITY);
        }
        if (!preg_match("/^(0|3){1}$/", $type)){
            throw new CMException(CM_ADMIN_INVALID_TYPE);
        }
        if(!preg_match("/^[0-9]+$/", $id)){
            throw new CMException(CM_INVALIDPARAM);
        }
        $title = htmlentities($title, ENT_QUOTES, "UTF-8");
        $url = htmlentities($url, ENT_QUOTES, "UTF-8");
        $this->db->real_escape_string($title);
        $this->db->real_escape_string($url);
        
        try{
            $set  = "url='".$url."', title='".$title."', visibility='".$visibility."', ";
            $set .= "type='".$type."', created='".getTime()."'";
            
            $this->db->update($this->cTB["cat"], $set, "id='$id'");
        }catch(MySQLException $e){
            $e->log();
            throw new CMException(CM_NORES);
        }
        return;
    }
    
    /**
     * Public function: delete
     * @access public
     * @param string $what
     * @param string $id
     * @return none
     */
    function delete($what, $id)
    {
        if (!preg_match("/^[0-9]+$/", $id)){
            throw new CMException(CM_INVALIDPARAM);
        }
        if ($what=="cat"){
            try{
                $this->db->delete($this->cTB["cat"], "id='$id'");
            }catch(MySQLException $e){
                $e->log();
                throw new CMException(CM_NORES);
            }
        }elseif ($what=="post"){
            try{
                $this->db->delete($this->cTB["post"], "id='$id'");
            }catch(MySQLException $e){
                $e->log();
                throw new CMException(CM_NORES);
            }
        }
        return;
    }
    
    /**
     * Public function: updateSubCat
     * @access public
     * @return none
     */
    function updateSubCat($id, $cat, $title, $url, $visibility, $type)
    {
        if (!preg_match("/^[0-9]+$/", $cat)){
            throw new CMException(CM_ADMIN_INVALID_CAT);
        }
        if (strlen($title)<4){
            throw new CMException(CM_ADMIN_INVALID_TITLE);
        }
        if (strlen($url)<4 || !preg_match("/^[0-9a-zA-Z\-_\.]+$/", $url)){
            throw new CMException(CM_ADMIN_INVALID_URL);
        }
        if(!preg_match("/^(0|1|2){1}$/", $visibility)){
            throw new CMException(CM_ADMIN_INVALID_VISIBILITY);
        }
        if (!preg_match("/^(1|2|4|5){1}$/", $type)){
            throw new CMException(CM_ADMIN_INVALID_TYPE);
        }
        if(!preg_match("/^[0-9]+$/", $id)){
            throw new CMException(CM_INVALIDPARAM);
        }
        $title = htmlentities($title, ENT_QUOTES, "UTF-8");
        $url = htmlentities($url, ENT_QUOTES, "UTF-8");
        $this->db->real_escape_string($title);
        $this->db->real_escape_string($url);
        
        try{
            $set  = "cat='".$cat."', url='".$url."', title='".$title."', visibility='".$visibility."', ";
            $set .= "type='".$type."', created='".getTime()."'";
            $this->db->update($this->cTB["cat"], $set, "id='$id'");
            return;
        }catch(MySQLException $e){
            $e->log();
            throw new CMException(CM_NOINSERT);
        }
    }
    
    /**
     * Public function: newPost
     * @access public
     * @return none
     */
    function newPost($subcat, $title, $url, $keywords, $summary, $content, $type)
    {
        if (!preg_match("/^[0-9]+$/", $subcat)){
            throw new CMException(CM_ADMIN_INVALID_SUBCAT);
        }
        if (strlen($title)<4){
            throw new CMException(CM_ADMIN_INVALID_TITLE);
        }
        if (strlen($url)<4 || !preg_match("/^[0-9a-zA-Z\-_\.]+$/", $url)){
            throw new CMException(CM_ADMIN_INVALID_URL);
        }
        if (strlen($keywords)<5){
            throw new CMException(CM_ADMIN_INVALID_KEYWORDS);
        }
        if (strlen($summary)<50){
            throw new CMException(CM_ADMIN_INVALID_SUMMARY);
        }
        if (strlen($content)<100){
            throw new CMException(CM_ADMIN_INVALID_CONTENT);
        }
        if (!preg_match("/^(1|2|4|5){1}$/", $type)){
            throw new CMException(CM_ADMIN_INVALID_TYPE);
        }
        
        try{
            $cols  = "subcat, url, title, keywords, summary, content, user_id, type, created";
            $vals  = "'$subcat', '$url', '$title', '$keywords', '$summary', '$content', ";
            $vals .= "'".$this->com->getUserID()."', '$type', '".getTime()."'";
            $this->db->insert($this->cTB["post"], $cols, $vals);
        }catch(MySQLException $e){
            $e->log();
            throw new CMException(CM_NORES);
        }
        
    }
    
    /**
     * Public function: editPost
     * @access public
     * @return none
     */
    public function editPost($id, $subcat, $title, $url, $keywords, $summary, $content, $type)
    {
        if(!preg_match("/^[0-9]+$/", $id)){
            throw new CMException(CM_INVALIDPARAM);
        }
        if (!preg_match("/^[0-9]+$/", $subcat)){
            throw new CMException(CM_ADMIN_INVALID_SUBCAT);
        }
        if (strlen($title)<4){
            throw new CMException(CM_ADMIN_INVALID_TITLE);
        }
        if (strlen($url)<4 || !preg_match("/^[0-9a-zA-Z\-_\.]+$/", $url)){
            throw new CMException(CM_ADMIN_INVALID_URL);
        }
        if (strlen($keywords)<5){
            throw new CMException(CM_ADMIN_INVALID_KEYWORDS);
        }
        if (strlen($summary)<50){
            throw new CMException(CM_ADMIN_INVALID_SUMMARY);
        }
        if (strlen($content)<100){
            throw new CMException(CM_ADMIN_INVALID_CONTENT);
        }
        if (!preg_match("/^(1|2|4|5){1}$/", $type)){
            throw new CMException(CM_ADMIN_INVALID_TYPE);
        }
        
        try{
            $set  = "subcat='$subcat', url='$url', title='$title', keywords='$keywords', summary='$summary', ";
            $set .= "content='$content', user_id='".$this->com->getUserID()."', type='$type'";
            $this->db->update($this->cTB["post"], $set, "id='$id'");
        }catch(MySQLException $e){
            $e->log();
            throw new CMException(CM_NORES);
        }
        
    }
}

?>