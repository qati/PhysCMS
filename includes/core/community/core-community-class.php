<?php

/**
 * @author Attila
 * @copyright 2011
 * @version 1.0
 * @package core
 * @subpackage community
 */


/**
 * Include core community exception class.
 */
require_once CORE."community/core-communityexception-class.php";

/**
 * Include core community constans.
 */
require_once CORE."community/core-community-consts.php";

/**
 * Include core userinfo class.
 */
require_once CORE."community/core-userinfo-class.php";

/**
 * Include core userwall class.
 */
require_once CORE."community/core-userwall-class.php";

/**
 * Community class.
 * @package core
 * @subpackage community
 */
class Community
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
     * UserInfo object.
     * @access protected
     */
    protected $userInfo = NULL;
    /**
     * UserWall object.
     * @access protected;
     */
    protected $userWall = NULL;
    /**
     * Community main table.
     * @access protected
     */
    protected $tb = "";
    /**
     * is user
     * @access protected
     */
    protected $isUser = false;
    /**
     * user id
     * @access protected
     */
    protected $id = false;
    
    /**
     * Constructor.
     */
    public function __construct(MySQL &$db, Info &$info, $xmlErrLog, $table, $userWallTable)
    {
        /** Open community error log file **/
        if (!ComException::openXML($xmlErrLog)){
            ComException::fatal_error(CFERR_FOXML, __FILE__, __LINE__, "Falied to open xml log file.", true);
            return;
        }
        $this->db       = $db;
        $this->info     = $info;
        $this->userInfo = NULL;
        $this->userWall = NULL;
        $this->tb       = $table;
        $this->isUser   = false;
        $this->id       = false;
        
        if (isset($_SESSION["user"])){
            $this->isUser   = true;
            $this->id       = $_SESSION["user"];
            $this->userInfo = new UserInfo($db, $this->tb, $this->id, $this);
            $this->userWall = new UserWall($db, $this->userInfo, $info, $userWallTable, $this->id);
        } else {
            $id = 0;
            $this->userInfo = new UserInfo($db, $this->tb, $id, $this);
            $this->userWall = new UserWall($db, $this->userInfo, $info, $userWallTable, $id);
        }
    }
    
    /**
     * Destructor
     */
    public function __destruct()
    {
        if (!ComException::saveXML()){
            ComException::fatal_error(CFERR_FSXML, __FILE__, __LINE__, "Falied to save xml log file.", true);
            return;
        }
        
        unset($this->db);
        unset($this->info);
        unset($this->userInfo);
        unset($this->userWall);
        unset($this->tb);
        unset($this->isUser);
        unset($this->id);
    }
    
    /**
     * Public function: get_defaultOptions
     * @access public
     * @return array
     */
    public function get_defaultOptions()
    {
        return unserialize(html_entity_decode($this->info->defaultUserOptions, ENT_QUOTES, "UTF-8"));
    }
    
    /**
     * login
     * @access public
     * @param string $nick
     * @param string $pass
     * @return boolean
     */
    public function login($nick, $pass)
    {
        if ($this->isUser || isset($_SESSION["user"])){
            return false;
        }
        
        $this->db->real_escape_string($nick);
        $pass = md5($pass);
        
        try {
            $res = $this->db->get($this->tb, "id, userLevel, nick", "WHERE nick='$nick' AND pass='$pass'");
            if ($res["userLevel"]<2){
                if ($res["userLevel"]==1){
                    throw new ComException(COM_NOACTIVATE);
                } else {
                    throw new ComException(COM_BANNED);
                }
                throw new ComException(COM_NOLOGIN);
            }
            $_SESSION["user"] = $res["id"];
            $_SESSION["nick"] = $res["nick"];
            $this->id         = $res["id"];
            $this->isUser     = true;
            $this->db->update($this->tb, "lastVisit='".getTime()."'", "id='".$this->id."'");
        } catch(MySQLException $e) {
            if ($e->getMode()==NO_RES){
                $e->log();
                throw new ComException(COM_NORES);
            } elseif ($e->getMode()==FALIED_UPDATE) {
                throw new ComException(COM_NOUPDATE);
            } else {
                throw new ComException(COM_NOLOGIN);
            }
        }
        return true;
    }
    
    /**
     * logout
     * @access public
     * @return none
     */
    public function logout()
    {
        $this->isUser = false;
        $this->id     = null;
        unset($_SESSION["user"]);
        unset($_SESSION["nick"]);
        return;
    }
    
    /**
     * getUserID
     * @access public
     * @return integer
     */
    public function getUserID()
    {
        return $this->id;
    }
    
    /**
     * getUserName
     * @access public
     * @param integer $id default false
     * @return string
     */
    public function getUserName($id=false)
    {
        if ($id===false){
            $id = $this->id;
        }
        
        try {
            $res = $this->db->get($this->tb, "nick", "WHERE id='$id'");
            return $res["nick"];
            
        } catch(MySQLException $e) {
            if ($e->getMode()==NO_RES){
                $e->log();
                throw new ComException(COM_NORES);
            } else {
                throw new ComException(COM_NODATA);
            }
        }
        return;
    }
    
    /**
     * isUser
     * @access public
     * @return boolean
     */
    public function isUser()
    {
        return $this->isUser;
    }
    
    /**
     * getUserLevel
     * @access public
     * @return int
     */
    public function getUserLevel()
    {
        try {
            $res = $this->db->get($this->tb, "userLevel", "WHERE id='{$this->id}'");
            return $res["userLevel"];
        } catch(MySQLException $e) {
            if ($e->getMode()==NO_RES){
                $e->log();
            }
            return false;
        }
        return false;
    }
    
    /**
     * check
     * @access public
     * @param integer $check
     * @param string $str
     * @return boolean
     */
    public function check($check, $str)
    {    
        if ($check==CHECK_NICK){
            if (strlen($str)<4 || strlen($str)>20){
                return false;
            }
            
            try {
                $this->db->real_escape_string($str);
                $this->db->get($this->tb, "*", "WHERE nick='$str'");
                return false;
            } catch(MySQLException $e){
                if ($e->getMode()==NO_RES){
                    $e->log();
                    throw new ComException(COM_NORES);
                }
                return true;
            }
            return false;
            
        } elseif ($check==CHECK_EMAIL){
            
            if (!preg_match("/^[0-9a-z\._-]+@([0-9a-z-]+\.)+[a-z]{2,4}$/i", $str)){
                return false;
            }
            
            try {
                $this->db->real_escape_string($str);
                $this->db->get($this->tb, "*", "WHERE email='$str'");
                return false;
            } catch(MySQLException $e){
                if ($e->getMode()==NO_RES){
                    $e->log();
                    throw new ComException(COM_NORES);
                }
                return true;
            }
            return false;
            
        } elseif ($check==CHECK_ALL){
            //nick
            if (!$this->check(CHECK_NICK, $str[0])){
                return false;
            }
            //name
            if (strlen($str[1])<6 || strlen($str[1])>32){
                return false;
            }
            //pass
            if (strlen($str[2])<6 || strlen($str[2])>32){
                return false;
            }
            
            //email
            if (!$this->check(CHECK_EMAIL, $str[3])){
               return false;
            }
            //hideEmail
            if (!preg_match("/^(0|1){1}$/i", $str[4])){
                return false;
            }
            //sex
            if (!preg_match("/^(1|2){1}$/i", $str[5])){
                return false;
            }
            //bdate
            if (!preg_match("/^[0-9]{4}-([0-9]{2})|([0-9]{1})-([0-9]{2})|([0-9]{1})$/i", $str[6])){
                return false;
            }
            
            return true;
            
        } else {
            return false;
        }
        return false;
    }
    
    /**
     * new user
     * @access public
     * @param string $nick
     * @param string $pass
     * @param string $email
     * @param string $name
     * @param string $bdate
     * @param boolean $hideEmail
     * @return none
     */
    public function newUser($nick, $name, $pass, $email, $hideEmail, $sex, $bdate)
    {
        settype($nick, "string");
        settype($name, "string");
        settype($pass, "string");
        settype($email, "string");
        settype($hideEmail, "string");
        settype($sex, "string");
        settype($bdate, "string");
        
        $datasToCheck = array($nick, $name, $pass, $email, $hideEmail, $sex, $bdate);
        if (!$this->check(CHECK_ALL, $datasToCheck)){
            throw new ComException(COM_INVALIDINPUT);
        }
        
        $this->db->real_escape_string($nick);
        $this->db->real_escape_string($name);
        $this->db->real_escape_string($email);
        $this->db->real_escape_string($hideEmail);
        $this->db->real_escape_string($sex);
        $this->db->real_escape_string($bdate);
        $pass = md5($pass);
        
        $key = "";
        for($i=0;$i<32;$i++){
            $part  = rand(1, 3);
            $first = ($part==1) ? 48 : (($part==2) ? 65 : 97);
            $last  = ($part==1) ? 57 : (($part==2) ? 90 : 122);
            
            $key .= chr(rand($first, $last));
        }
        
        try {
            $options               = $this->get_defaultOptions();
            $options["hideEmail"]  = $hideEmail;
            $options["sex"]        = $sex;
            $options["activation"] = $key;
            $options               = htmlentities(serialize($options), ENT_QUOTES, "UTF-8");
            
            $vals = "'$nick', '$name', '$pass', '$email', '$bdate', '".getTime()."', '".$options."'";
            
            $this->db->insert($this->tb, "nick, name, pass, email, bdate, regdate, options", $vals);
        } catch(MySQLException $e){
            $e->log();
            throw new ComException(COM_NOINSERT);
        }
        
        return array("email"=>$email, "key"=>$key);
    }
    
    /**
     * activate new user
     * @access public
     * @param string $email
     * @param string $code
     * @return boolean
     */
    public function activateNewUser($email, $code)
    {
        if (!preg_match("/^[0-9a-z\._-]+@([0-9a-z-]+\.)+[a-z]{2,4}$/i", $email) || strlen($code)!=32){
            return false;
        }
        
        $this->db->real_escape_string($email);
        
        try {
            
            $res     = $this->db->get($this->tb, "options", "WHERE email='$email'");
            $options = unserialize(html_entity_decode($res["options"], ENT_QUOTES, "UTF-8"));
            
            if ($options["activation"]==$code){
                unset($options["activation"]);
                $options = htmlentities(serialize($options), ENT_QUOTES, "UTF-8");
                $this->db->real_escape_string($options);
                
                $res = $this->db->update($this->tb, "userLevel='2', options='$options'", "email='$email'");
                
                return true;
                
            } else {
                return false;
            }
        } catch(MySQLException $e){
            if ($e->getMode()==NO_RES || $e->getMode()==FALIED_UPDATE){
                $e->log();
            }
            return false;
        }
        
        return false;;
    }
    
    /**
     * Public function get user option.
     * @access public
     * @parama string $name
     * @param boolean $info default false
     * @parama integer $userID default false
     * @return string
     */
    public function attr($name, $info=false, $userID=false)
    {
        if ($info==true){
            return $this->info->$name;
        }
        if ($userID!==false && (is_integer($userID) || preg_match("/^[0-9]+$/i", $userID))){
            $op = $this->userInfo->options($userID);
            return $op[$name];
        }
        return $this->userInfo->$name;
    }
    
    
    /**
     * Public function update or add user option.
     * @access public
     * @param string $name
     * @param string $value
     * @return none
     */
    public function set_attr($name, $value)
    {
        $this->userInfo->$name = $value;
        return;
    }
    
    
    /**
     * Public function userWall
     * @access public
     * @param integer $mode
     * @param mixed $params
     * @return mixed
     */
    public function userWall($mode, $params=false)
    {
        if (!$this->isUser){
            return false;
        }
        
        if ($mode==COM_USERWALL_GETPOSTS){
            return $this->userWall->get_wall($params && $params>1 ? $params : 1);
        } elseif ($mode==COM_USERWALL_ADDPOST){
            return $this->userWall->addPostToMyWall($params);
        } elseif ($mode==COM_USERWALL_COUNTPOSTS){
            return $this->userWall->countPosts();
        }
        
        return false;
    }
    
    /**
     * Public function addAvatar
     * @access public
     * @param string $type
     * @param int $size
     * @param string $tmp_name
     * @return none
     */
    public function addAvatar($type, $size, $tmp_name)
    {
        if (!($type=="image/png" || $type=="image/gif" || $type=="image/jpg" || $type=="image/jpeg")){
            throw new ComException(COM_INVALIDINPUT);
        }
        if ($size>$this->info->avatarMaxSize){
            throw new ComException(COM_INVALIDINPUT);
        }
        
        if (avatar_type($this->getUserName())){
            $avatar = $this->getUserName().".".avatar_type($this->getUserName());
            if (!unlink(AVATARSPATH.$avatar)){
                throw new ComException(COM_FALIEDTODELET);
            }
        }
        
        $avatar = $this->getUserName().".".str_replace("image/", "", $type);
        
        if (!move_uploaded_file($tmp_name, AVATARSPATH.$avatar)){
            throw new ComException(COM_FALIEDTOSAVE);
        }
        
        $prop = @getimagesize(AVATARSPATH.$avatar);
        if ($prop[0]!=$prop[1] || $prop[0]<100){
            if (!unlink(AVATARSPATH.$avatar)){
                throw new ComException(COM_FALIEDTODELET);
            }
            throw new ComException(COM_INVALIDINPUT);
        }
        
        return; 
    }
    
    /**
     * Public function: userProfile
     * @access public
     * @param string $id default false
     * @return array
     */
    public function userProfile($id=false)
    {
        if (!$id && $this->isUser){
            $id = $this->id;
        } elseif (!$id && !$this->isUser){
            return false;
        }
        $this->db->real_escape_string($id);
        try {
            $select = "id, nick, name, email, bdate, regDate, lastVisit, userLevel";
            $res = $this->db->get($this->tb, $select, "WHERE id='$id' LIMIT 1");
            
            $op = $this->userInfo->options($id);
            if ($op["publicWall"]==1){
                try {
                    $res["wall"] = $this->userWall->get_wall(1, $id);
                } catch(ComException $e) {
                    if ($e->getErrno()==COM_NORES){
                        $e->log();
                        throw new ComException(COM_NORES);
                    }
                    $res["wall"] = "";
                }
            }
            
            $res["sex"]          = $op["sex"];
            $res["numOfFriends"] = is_array($op["friends"]) ? count($op["friends"]) : 0;
            
            return $res;
        } catch(MySQLException $e){
            if ($e->getMode()==NO_RES){
                $e->log();
                throw new ComException(COM_NORES);
            }
            throw new ComException(COM_NODATA);
        }
    }
    
    /**
     * Public function: isFriend
     * @access public
     * @param int $id
     * @return boolean
     */
    public function isFriend($id)
    {
        if (!preg_match("/^[0-9]+$/", $id) || !is_array($this->userInfo->friends)){
            return false;
        }
        if (in_array($id, $this->userInfo->friends)){
            return true;
        }
        return false;
    }
    
    /**
     * Public function: isMarkedAsFriend
     * @access public
     * @param int $id
     * @return boolean
     */
    public function isMarkedAsFriend($id)
    {
        try {
            $op = $this->userInfo->options($id);
            if (is_array($op["friendRequest"]) && in_array_m($this->id, $op["friendRequest"])){
                return true;
            }
            return false;
        } catch(ComException $e){
            throw $e;
        }
    }
    
    /**
     * Public function: isIMarkedAsFriend
     * @access public
     * @param int $id
     * @return boolean
     */
    public function isIMarkedAsFriend($id)
    {
        if (!preg_match("/^[0-9]+$/", $id) || !is_array($this->userInfo->friendRequest)){
            return false;            
        }
        if (in_array_m($id, $this->userInfo->friendRequest)){
            return true;
        }
        return false;
    }
    
    /**
     * Public function: addFriend
     * @access public
     * @param int $id
     * @return none
     */
    public function addFriend($id)
    {
        if (!preg_match("/^[0-9]+$/", $id) || $this->isFriend($id) || $this->id==$id){
            throw new ComException(COM_INVALIDPARAM);
        }

        try {
    
            $options = $this->userInfo->options($id);
            
            if (!is_array($options["friendRequest"])){
                $options["friendRequest"] = array(array("id"=>$this->id,"date"=>getTime()));
            } elseif (!in_array($this->id, $options["friendRequest"])){
                array_push($options["friendRequest"], array("id"=>$this->id,"date"=>getTime()));
            }
            
            $this->userInfo->updateOptions($id, $options);
                
            return true;
            
        } catch(ComException $e) {
            throw $e;
        } 
        return;
    }
    
    /**
     * Public function: confirmFriend
     * @access public
     * @param int $id
     * @return
     */
    public function confirmFriend($id)
    {
        if (!preg_match("/^[0-9]+$/", $id) || $this->isFriend($id) || $this->id==$id){
           throw new ComException(COM_INVALIDPARAM);
        }
        
        try {
            
        
            $friends = $this->userInfo->friends;
            if (is_array($friends)){
                array_push($friends, $id);
            } else {
                $friends = array($id);
            }
            $this->userInfo->friends = $friends;
        
            $fr = $this->userInfo->friendRequest;
            foreach($fr as $key=>$val){
                if ($val["id"]==$id){
                    unset($fr[$key]);
                }
            }
            $this->userInfo->friendRequest = $fr;
        
            $options = $this->userInfo->options($id);
    
            if (is_array($options["friends"])){
                array_push($options["friends"], $this->id);
            } else {
                $options["friends"] = array($this->id);
            }
            $this->userInfo->updateOptions($id, $options);
        
        } catch(ComException $e){
            throw $e;
        }
    }
    
    /**
     * Public function: userList
     * @access public
     * @param int $page default 1
     * @return array
     */
    public function userList($page=1)
    {
        $max   = $this->info->userListMax;
        $start = ($page-1)*$max;
        
        try {
            return $this->db->get($this->tb, "id, nick, regDate, lastVisit", "ORDER BY id ASC LIMIT $start, $max");
        } catch(MySQLException $e){
            if ($e->getMode()==NO_RES){
                $e->log();
                throw new ComException(COM_NORES);
            }
            throw new ComException(COM_NODATA);
        }
    }
    
    /**
     * Public function: countUsers
     * @access public
     * @return int
     */
    public function countUsers()
    {
        try {
            $res = $this->db->get($this->tb, "COUNT(*) AS num");
            return isset($res["num"]) ? $res["num"] : false;
        } catch(MySQLException $e){
            if ($e->getMode()==NO_RES){
                $e->log();
            }
        }
        return false;
    }
    
    /**
     * Public function: newPass
     * @access public
     * @param string $email
     * @return string
     */
    public function newPass($email)
    {
        if (!preg_match("/^[0-9a-z\._-]+@([0-9a-z-]+\.)+[a-z]{2,4}$/i", $email)){
            throw new ComException(COM_INVALIDINPUT);
        }
        
        try {
            $this->db->real_escape_string($email);
            $db = $this->db->get($this->tb, "*", "WHERE email='$email'");
            return md5(md5($db["id"].$db["nick"].$db["name"].$db["email"].$db["bdate"].$db["regDate"]).$db["pass"]);
        }catch(MySQLException $e){
            if ($e->getMode()==NO_RES){
                $e->log();
                throw new ComException(COM_NORES);
            }
            throw new ComException(COM_INVALIDINPUT);
        }
    }
    
    /**
     * Public function: genPass()
     * @access public
     * @param string $email
     * @param string $code
     * @return string
     */
    public function genPass($email, $code)
    {
        if (!preg_match("/^[0-9a-z\._-]+@([0-9a-z-]+\.)+[a-z]{2,4}$/i", $email)){
            throw new ComException(COM_INVALIDINPUT);
        } 
        
        try {
            $this->db->real_escape_string($email);
            $db = $this->db->get($this->tb, "*", "WHERE email='$email'");
            
            $cc = md5(md5($db["id"].$db["nick"].$db["name"].$db["email"].$db["bdate"].$db["regDate"]).$db["pass"]);
            if ($code == $cc){
                $key = "";
                for($i=0;$i<8;$i++){
                    $part  = rand(1, 3);
                    $first = ($part==1) ? 48 : (($part==2) ? 65 : 97);
                    $last  = ($part==1) ? 57 : (($part==2) ? 90 : 122);
            
                    $key .= chr(rand($first, $last));
                }
                $this->db->update($this->tb, "pass='".md5($key)."'", "email='$email'");
                return $key;
            }
            throw new ComException(COM_INVALIDINPUT);
        }catch(MySQLException $e){
            if ($e->getMode()==NO_RES){
                $e->log();
                throw new ComException(COM_NORES);
            }           
            throw new ComException(COM_INVALIDINPUT); 
        }
    }
    
}

?>