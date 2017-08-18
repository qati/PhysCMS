<?php

/**
 * @author Attila
 * @copyright 2011
 * @package core
 * @subpackage community
 */


/**
 * UserWall class.
 * @package core
 * @subpackage sommunity
 */
class UserWall
{
    /**
     * Reference to db object.
     * @access private
     */
    private $db = NULL;
    /**
     * Reference to userInfo object.
     * @access private
     */
    private $userInfo = NULL;
    /**
     * Reference to info object.
     * @access private
     */
    private $info = NULL;
    /**
     * UserWall table.
     * @access private
     */
    private $tb = "";
    /**
     * User id.
     * @access private
     */
    private $userID = NULL;
    
    /**
     * Constructor.
     */
    public function __construct(MySQL &$db, UserInfo &$userInfo, Info &$info, $table, $userID)
    {
        $this->db       = $db;
        $this->userInfo = $userInfo;
        $this->info     = $info;
        $this->tb       = $table;
        $this->userID   = $userID;
    }
    
    /**
     * Destructor
     */
    public function __destruct()
    {
        unset($this->db);
        unset($this->userInfo);
        unset($this->info);
        unset($this->tb);
    }
    
    /**
     * public function:: compare by date
     * @access public
     * @param array $a
     * @param array $b
     * @return int
     * @static
     */
    public static function cmpByDate($a, $b)
    {
        return strtotime($a['post_date'])-strtotime($b['post_date']);
    }
    
    /**
     * public function: get wall content
     * @access public
     * @param int $page
     * @param int $id
     * @return string
     */
    public function get_wall($page=1, $id=false)
    {    
        $posts     = false;
        $max       = $this->userInfo->wallNumOfMaxPosts;
        $page      = ($page-1)*$max;
        $condition = "WHERE user_id='".(($id!=false) ? $id : $this->userID)."'";
        $friends   = $this->userInfo->options(($id!=false) ? $id : $this->userID);
        $friends   = $friends["friends"];
       
        if (is_array($friends)){
            foreach($friends as $friend){
                $condition .= " OR user_id='".$friend."'";
            }
        }

        //megcsinalni
        /**
         * ha egy felhasznalonak a fala nem publikus, de a baratjae igen akkor a baratja profiljaba ne jelenjen
         * meg az o posztjai
         */
       
        try {
            return $this->db->get($this->tb, "*", $condition." ORDER BY post_date DESC LIMIT $page, $max");
        } catch(MySQLException $e){
            if ($e->getMode()==NO_RES){
                $e->log();
                throw new ComException(COM_NORES);
            }
            throw new ComException(COM_NODATA);
        }
        
        return false;
    }
    
    /**
     * Public function: countPosts
     * @access public
     * @return int
     */
    public function countPosts()
    {
        $friends   = $this->userInfo->friends;
        $condition = "WHERE user_id='".$this->userID."'";
        
        if (is_array($friends)){
            foreach($friends as $friend){
                $condition .= " OR user_id='".$friend."'";
            }
        }
        
        try {
            $res = $this->db->get($this->tb, "COUNT(*) AS num", $condition);
            return $res["num"];
        } catch(MySQLException $e){
            if ($e->getMode()==NO_RES){
                $e->log();
                throw new ComException(COM_NORES);
            }
            throw new ComException(COM_NODATA);
        }
        
        return false;
    }
    
    /**
     * Public function addPostToMyWall
     * @access public
     * @param string $post
     * @return boolean
     */
    public function addPostToMyWall($post)
    {
        $post = htmlentities($post, ENT_QUOTES, "UTF-8");
        $this->db->real_escape_string($post);
        $vals ="'".$this->userID."', '".$this->userID."', '".$post."', '".getTime()."'";
        
        try {
            $this->db->insert($this->tb, "user_id, add_id, post, post_date", $vals);
            
        } catch(MySQLException $e){
            if ($e->getMode()==NO_RES){
                $e->log();
                throw new ComException(COM_NORES);
            }
            throw new ComException(COM_NOINSERT);
        }
    }
}

?>