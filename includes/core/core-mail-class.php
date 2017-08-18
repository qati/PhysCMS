<?php

/**
 * @author Attila
 * @copyright 2011
 * @package core
 * @version 1.0
 */


/**
 * Mail class.
 * @package core
 */
class Mail
{
    /**
     * Private variable newLine.
     * @var string
     * @access private
     */
    private $newLine = NULL;
    /**
     * Private variable server.
     * @var string
     * @access private
     */
    private $server = NULL;
    /**
     * Private variable port.
     * @var string
     * @access private
     */
    private $port = NULL;
    /**
     * Private variable user.
     * @var string
     * @access private
     */
    private $user = NULL;
    /**
     * Private variable pass.
     * @var string
     * @access private
     */
    private $pass = NULL;
    /**
     * Private variable hello.
     * @var string
     * @access private
     */
    private $hello = NULL;
    /**
     * Private variable smtp.
     * @var resource
     * @access private
     */
    private $smtp = NULL;
    /**
     * Private variable resp.
     * @var array
     * @access private
     */
    private $resp = NULL;
    /**
     * Private variable use auth
     * @var boolean
     * @access private
     */
    private $useAUTH = NULL;
    
    /**
     * Private function connect.
     * @access private
     * @return mixed
     */
    private function connect()
    {
        if (!$this->server || !$this->port || $this->smtp){
            return false;
        }
        
        $this->smtp = @fsockopen($this->server, $this->port, $errno, $errstr);
        if (!$this->smtp){
            $err = array("msg"=>"Falied to connect to ".$this->Server, "errno"=>$errno, "errstr"=>$errstr);
            $this->resp["error"] = $err;
            return false;
        }
        
        $this->resp["welcome"] = @fgets($this->smtp);
        if (empty($this->resp["welcome"])){
            $err = array("msg"=>"No welcome", "errno"=>$errno, "errstr"=>$errstr);
            $this->resp["error"] = $err;
            return false;
        }

        return true;
    }
    
    /**
     * Private function close
     * @access private
     * @return boolean
     */
    private function close()
    {
        if ($this->smtp){
            return @fclose($this->smtp);
        }
        return false;
    }
    
    /**
     * Private function server puts
     * @access private
     * @param string $msg
     * @return int
     */
    private function server_puts($msg)
    {
        return @fputs($this->smtp, $msg.$this->newLine);
    }
    
    /**
     * Private function server gets
     * @access private
     * @return string
     */
    private function server_gets()
    {
        return @fgets($this->smtp);
    }
    
    /**
     * Private function server_hello
     * @access private
     * @return boolean
     */
    private function server_hello()
    {
        if (!$this->hello || !$this->smtp){
            return false;
        }
        
        if (!$this->server_puts("HELO ".$this->hello)){
            return false;
        }
        $this->resp["helo"] = $this->server_gets();
       
        if (empty($this->resp["helo"])){
            return false;
        }
        return true;
    }
    
    /**
     * Private function server_auth
     * @access private
     * @return boolean
     */
    private function server_auth()
    {
        if (!$this->useAUTH || !$this->user || !$this->pass || !$this->smtp){
            return false;
        }
        
        if (!$this->server_puts("AUTH LOGIN")){
            return false;
        }
        $this->resp["auth"] = $this->server_gets();
        
        if (!$this->server_puts($this->user)){
            return false;
        }
        $this->resp["user"] = $this->server_gets();
        
        if (!$this->server_puts($this->pass)){
            return false;
        }
        $this->resp["pass"] = $this->server_gets();
        
        if (!is_string($this->resp["auth"]) || !is_string($this->resp["user"]) || !is_string($this->resp["pass"])){
            return false;
        }  
        
        return true;
    }
    
    /**
     * Private function server quit
     * @access private
     * @return boolean
     */
    private function server_quit()
    {
        if (!$this->smtp){
            return false;
        }
        
        if (!$this->server_puts("QUIT")){
            return false;
        }
        $this->resp["quit"] = $this->server_gets();
        
        if (!is_string($this->resp["quit"])){
            return false;
        }
        
        return substr($this->resp["quit"], 0, 3)=="221" ? true : false;   
    }
    
    /**
     * Constructor
     */
    public function __construct(&$info)
    {
        $this->newLine = "\r\n";       
        $this->server  = $info->smtp_server;
        $this->port    = $info->smtp_port;
        $this->user    = $info->smpt_user;
        $this->pass    = $info->smpt_pass;
        $this->useAUTH = (!empty($this->user) && !empty($this->pass)) ? true : false;
        $this->smtp    = NULL;
        $this->resp    = array();
        $this->hello   = $info->siteurl;
    }
    
    /**
     * Destructor
     */
    public function __destruct()
    {
        unset($this->newLine);
        unset($this->useAUTH);
        unset($this->server);
        unset($this->port);
        unset($this->user);
        unset($this->pass);
        unset($this->smtp);
        unset($this->resp);
        unset($this->hello);
    }
    
    /**
     * Public function: send
     * @access public
     * @param string $to
     * @param string $from
     * @param string $subject
     * @param string $msg
     * @return boolean
     */
    public function send($to, $from, $subject, $msg, $time)
    {
        $headers  = "MIME-Version: 1.0".$this->newLine;
        $headers .= "Content-type: text/html; charset=utf-8".$this->newLine;
        $headers .= "To: <$to>".$this->newLine;
        $headers .= "From: <$from>".$this->newLine;
        $headers .= "Reply-To: ".$from.$this->newLine;
        $headers .= "Date: ".date("D, d M Y H:s:i", time()+$time)." +0100".$this->newLine;
        
        if (!$this->connect()){
            return false;
        }

        if (!$this->server_hello()){
            return false;
        }
        
        if (!$this->server_auth()){
            return false;
        }
        
        if (!$this->server_puts("MAIL FROM: <$from>")){
            return false;
        }
        $this->resp["from"] = $this->server_gets();
        
        if (!$this->server_puts("RCPT TO: <$to>")){
            return false;
        }
        $this->resp["to"] = $this->server_gets();
       
        if (!$this->server_puts("DATA")){
            return false;
        }
        $this->resp["data"] = $this->server_gets();
        
        $data  = "To: $to".$this->newLine;
        $data .= "From: $from".$this->newLine;
        $data .= "Subject: $subject".$this->newLine;
        $data .= $headers.$this->newLine.$this->newLine;
        $data .= "<html><body>".$msg."</body></html>".$this->newLine;
        $data .= ".";

        if (!$this->server_puts($data)){
            return false;
        }
        $this->resp["send"] = $this->server_gets();
        
        if (!$this->server_quit()){
            return false;
        }
        
        if (!$this->close()){
            return false;
        }
        
        return true;
    }
    
    /**
     * Public function getResp
     * @access public
     * @param boolean $getAsString default true
     * @return string or array
     */
    public function getResp($getAsString=true)
    {
        if (!$getAsString){
            return $this->resp;
        }
        return serialize($this->resp);
    }
}


?>