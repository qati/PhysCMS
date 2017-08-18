<?php

/**
 * @author Attila
 * @copyright 2011
 * @package core
 * @subpackage system
 * @version 1.0
 */


/**
 * Include firewall msgs.
 */
require_once CORE."core-firewall-msgs.php";

/**
 * Firewall class.
 * @package core
 * @subpackage system
 */
class FireWall
{
    /**
     * IPBANN file.
     * @var string
     * @access private
     */
    private $ipbann = "";
    /**
     * Private function get_env
     * @access private
     * @param string $st_var
     * @return string
     */
    private function get_env($st_var)
    {
        if (isset($_SERVER[$st_var])){
            return strip_tags($_SERVER[$st_var]);
        } elseif (isset($_ENV[$st_var])){
            return strip_tags($_ENV[$st_var]);
        } elseif (getenv($st_var)){
            return strip_tags(getenv($st_var));
        } elseif (function_exists("apache_getenv") && apache_getenv($st_var)){
            return strip_tags(apache_getenv($st_var));
        }
        return "";
    }
    
    /**
     * Private function get_refer
     * @access private
     * @return string
     */
    private function get_refer()
    {
        if ($this->get_env("HTTP_REFERER")){
            return $this->get_env("HTTP_REFERER");
        }
        return "";
    }
    
    /**
     * Private function get_ip
     * @access private
     * @return string
     */
    private function get_ip()
    {
        if ($this->get_env("REMOTE_ADDR")){
            return $this->get_env("REMOTE_ADDR");
        }
        return "";
    }
    
    /**
     * Private function get_userAgent
     * @access private
     * @return string
     */
    private function get_userAgent()
    {
        if ($this->get_env("HTTP_USER_AGENT")){
            return $this->get_env("HTTP_USER_AGENT");
        }
        return "";
    }
    
    /**
     * Private function get_queryString
     * @access private
     * @return string
     */
    private function get_queryString()
    {
        if ($this->get_env("QUERY_STRING")){
            return str_replace('%09', '%20', $this->get_env("QUERY_STRING")); 
        }
        return "";
    }
    
    /**
     * Private function get_requestMethod
     * @access private
     * @return string
     */
    private function get_requestMethod()
    {
        if ($this->get_env("REQUEST_METHOD")){
            return $this->get_env("REQUEST_METHOD");
        }
        return "";
    }
    
    /**
     * Private function get_hostByADDR
     * @access private
     * @return string
     */
    private function get_hostByADDR()
    {
        return strip_tags(@gethostbyaddr($this->get_ip()));
    }
    
    /**
     * Private function protection_SUPERGLOBALS
     * @access private
     * @return none
     */
    private function protection_SUPERGLOBALS($sg)
    {
  		$ct_rules = Array('applet', 'base', 'bgsound', 'blink', 'embed', 'expression', 'frame', 'javascript', 'layer', 'link', 'meta', 'object', 'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload', 'script', 'style', 'title', 'vbscript', 'xml');
        if ($sg=="COOKIE"){
            foreach($_COOKIE as $key=>$value){
                $check = str_replace($ct_rules, '*', $value);
                if ($value!=$check){
                    Error::hack_log(__FILE__, __LINE__, "Cookie protect");
                    unset($_COOKIE[$key]);
                    die(FIREWALL);
                }
            }
        } elseif ($sg=="POST"){
            foreach($_POST as $key=>$value){
                $check = str_replace($ct_rules, '*', $value);
                if( $value != $check ) {
                    Error::hack_log(__FILE__, __LINE__, 'POST protect');
                    unset($_POST[$key]);
                    die(FIREWALL);
                }
            }
        } elseif ($sg=="GET"){
            foreach($_GET as $key=>$value){
                $check = str_replace($ct_rules, '*', $value);
                if($value!=$check) {
                    Error::hack_log(__FILE__, __LINE__, 'GET protect');
                    unset($_GET[$key]);
                    die(FIREWALL);
                }
            }
        }
        return;
    }
    
    /**
     * Private function protection_URL
     * @access private
     * @return none
     */
    private function protection_URL()
    {
        $ct_rules = array('absolute_path', 'ad_click', 'alert(', 'alert%20', ' and ', 'basepath', 'bash_history', '.bash_history', 'cgi-', 'chmod(', 'chmod%20', '%20chmod', 'chmod=', 'chown%20', 'chgrp%20', 'chown(', '/chown', 'chgrp(', 'chr(', 'chr=', 'chr%20', '%20chr', 'chunked', 'cookie=', 'cmd', 'cmd=', '%20cmd', 'cmd%20', '.conf', 'configdir', 'config.php', 'cp%20', '%20cp', 'cp(', 'diff%20', 'dat?', 'db_mysql.inc', 'document.location', 'document.cookie', 'drop%20', 'echr(', '%20echr', 'echr%20', 'echr=', '}else{', '.eml', 'esystem(', 'esystem%20', '.exe',  'exploit', 'file\://', 'fopen', 'fwrite', '~ftp', 'ftp:', 'ftp.exe', 'getenv', '%20getenv', 'getenv%20', 'getenv(', 'grep%20', '_global', 'global_', 'global[', 'http:', '_globals', 'globals_', 'globals[', 'grep(', 'g\+\+', 'halt%20', '.history', '?hl=', '.htpasswd', 'http_', 'http-equiv', 'http/1.', 'http_php', 'http_user_agent', 'http_host', '&icq', 'if{', 'if%20{', 'img src', 'img%20src', '.inc.php', '.inc', 'insert%20into', 'ISO-8859-1', 'ISO-', 'javascript\://', '.jsp', '.js', 'kill%20', 'kill(', 'killall', '%20like', 'like%20', 'locate%20', 'locate(', 'lsof%20', 'mdir%20', '%20mdir', 'mdir(', 'mcd%20', 'motd%20', 'mrd%20', 'rm%20', '%20mcd', '%20mrd', 'mcd(', 'mrd(', 'mcd=', 'mod_gzip_status', 'modules/', 'mrd=', 'mv%20', 'nc.exe', 'new_password', 'nigga(', '%20nigga', 'nigga%20', '~nobody', 'org.apache', '+outfile+', '%20outfile%20', '*/outfile/*',' outfile ','outfile', 'password=', 'passwd%20', '%20passwd', 'passwd(', 'phpadmin', 'perl%20', '/perl', 'phpbb_root_path','*/phpbb_root_path/*','p0hh', 'ping%20', '.pl', 'powerdown%20', 'rm(', '%20rm', 'rmdir%20', 'mv(', 'rmdir(', 'phpinfo()', '<?php', 'reboot%20', '/robot.txt' , '~root', 'root_path', 'rush=', '%20and%20', '%20xorg%20', '%20rush', 'rush%20', 'secure_site, ok', 'select%20', 'select from', 'select%20from', '_server', 'server_', 'server[', 'server-info', 'server-status', 'servlet', 'sql=', '<script', '<script>', '</script','script>','/script', 'switch{','switch%20{', '.system', 'system(', 'telnet%20', 'traceroute%20', '.txt', 'union%20', '%20union', 'union(', 'union=', 'vi(', 'vi%20', 'wget', 'wget%20', '%20wget', 'wget(', 'window.open', 'wwwacl', ' xor ', 'xp_enumdsn', 'xp_availablemedia', 'xp_filelist', 'xp_cmdshell', '$_request', '$_get', '$request', '$get',  '&aim', '/etc/password','/etc/shadow', '/etc/groups', '/etc/gshadow', '/bin/ps', 'uname\x20-a', '/usr/bin/id', '/bin/echo', '/bin/kill', '/bin/', '/chgrp', '/usr/bin', 'bin/python', 'bin/tclsh', 'bin/nasm', '/usr/x11r6/bin/xterm', '/bin/mail', '/etc/passwd', '/home/ftp', '/home/www', '/servlet/con', '?>', '.txt');
        
        $check = str_replace($ct_rules, '*', $this->get_queryString());
        
        if($this->get_queryString()!=$check ) {
			Error::hack_log(__FILE__, __LINE__, 'URL protect');
			die(FIREWALL_PROTECTION_URL);
		}
        return;
    }
    
    /**
     * Private function protection_POSTfromSERVER
     * @access private
     * @return none
     */
    private function protection_POSTfromSERVER()
    {
        if ($this->get_requestMethod()=='POST'){
            if (isset($_SERVER['HTTP_REFERER'])){
                if (!stripos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'], 0 )){
					Error::hack_log(__FILE__, __LINE__, 'Posting another server');
					die(FIREWALL_PROTECTION_OTHER_SERVER);
				}
            }
        }
        return;
    }
    
    /**
     * Private function protection_SANTY
     * @access private
     * @return none
     */
    private function protection_SANTY()
    {
        $ct_rules = array('rush','highlight=%','perl','chr(','pillar','visualcoder','sess_');
        
		$check = str_replace($ct_rules, '*', strtolower($_SERVER["REQUEST_URI"]));
		if (strtolower($_SERVER["REQUEST_URI"])!=$check ) {
			Error::hack_log(__FILE__, __LINE__, 'Santy');
            die(FIREWALL_PROTECTION_SANTY);
		}
        return;
    }
    
    /**
     * Private function protection_BOTS
     * @access private
     * @return none
     */
    private function protection_BOTS()
    {
        $ct_rules = array( '@nonymouse', 'addresses.com', 'ideography.co.uk', 'adsarobot', 'ah-ha', 'aktuelles', 'alexibot', 'almaden', 'amzn_assoc', 'anarchie', 'art-online', 'aspseek', 'assort', 'asterias', 'attach', 'atomz', 'atspider', 'autoemailspider', 'backweb', 'backdoorbot', 'bandit', 'batchftp', 'bdfetch', 'big.brother', 'black.hole', 'blackwidow', 'blowfish', 'bmclient', 'boston project', 'botalot', 'bravobrian', 'buddy', 'bullseye', 'bumblebee ', 'builtbottough', 'bunnyslippers', 'capture', 'cegbfeieh', 'cherrypicker', 'cheesebot', 'chinaclaw', 'cicc', 'civa', 'clipping', 'collage', 'collector', 'copyrightcheck', 'cosmos', 'crescent', 'custo', 'cyberalert', 'deweb', 'diagem', 'digger', 'digimarc', 'diibot', 'directupdate', 'disco', 'dittospyder', 'download accelerator', 'download demon', 'download wonder', 'downloader', 'drip', 'dsurf', 'dts agent', 'dts.agent', 'easydl', 'ecatch', 'echo extense', 'efp@gmx.net', 'eirgrabber', 'elitesys', 'emailsiphon', 'emailwolf', 'envidiosos', 'erocrawler', 'esirover', 'express webpictures', 'extrac', 'eyenetie', 'fastlwspider', 'favorg', 'favorites sweeper', 'fezhead', 'filehound', 'filepack.superbr.org', 'flashget', 'flickbot', 'fluffy', 'frontpage', 'foobot', 'galaxyBot', 'generic', 'getbot ', 'getleft', 'getright', 'getsmart', 'geturl', 'getweb', 'gigabaz', 'girafabot', 'go-ahead-got-it', 'go!zilla', 'gornker', 'grabber', 'grabnet', 'grafula', 'green research', 'harvest', 'havindex', 'hhjhj@yahoo', 'hloader', 'hmview', 'homepagesearch', 'htmlparser', 'hulud', 'http agent', 'httpconnect', 'httpdown', 'http generic', 'httplib', 'httrack', 'humanlinks', 'ia_archiver', 'iaea', 'ibm_planetwide', 'image stripper', 'image sucker', 'imagefetch', 'incywincy', 'indy', 'infonavirobot', 'informant', 'interget', 'internet explore', 'infospiders',  'internet ninja', 'internetlinkagent', 'interneteseer.com', 'ipiumbot', 'iria', 'irvine', 'jbh', 'jeeves', 'jennybot', 'jetcar', 'joc web spider', 'jpeg hunt', 'justview', 'kapere', 'kdd explorer', 'kenjin.spider', 'keyword.density', 'kwebget', 'lachesis', 'larbin',  'laurion(dot)com', 'leechftp', 'lexibot', 'lftp', 'libweb', 'links aromatized', 'linkscan', 'link*sleuth', 'linkwalker', 'libwww', 'lightningdownload', 'likse', 'lwp','mac finder', 'mag-net', 'magnet', 'marcopolo', 'mass', 'mata.hari', 'mcspider', 'memoweb', 'microsoft url control', 'microsoft.url', 'midown', 'miixpc', 'minibot', 'mirror', 'missigua', 'mister.pix', 'mmmtocrawl', 'moget', 'mozilla/2', 'mozilla/3.mozilla/2.01', 'mozilla.*newt', 'multithreaddb', 'munky', 'msproxy', 'nationaldirectory', 'naverrobot', 'navroad', 'nearsite', 'netants', 'netcarta', 'netcraft', 'netfactual', 'netmechanic', 'netprospector', 'netresearchserver', 'netspider', 'net vampire', 'newt', 'netzip', 'nicerspro', 'npbot', 'octopus', 'offline.explorer', 'offline explorer', 'offline navigator', 'opaL', 'openfind', 'opentextsitecrawler', 'orangebot', 'packrat', 'papa foto', 'pagegrabber', 'pavuk', 'pbwf', 'pcbrowser', 'personapilot', 'pingalink', 'pockey', 'program shareware', 'propowerbot/2.14', 'prowebwalker', 'proxy', 'psbot', 'psurf', 'puf', 'pushsite', 'pump', 'qrva', 'quepasacreep', 'queryn.metasearch', 'realdownload', 'reaper', 'recorder', 'reget', 'replacer', 'repomonkey', 'rma', 'robozilla', 'rover', 'rpt-httpclient', 'rsync', 'rush=', 'searchexpress', 'searchhippo', 'searchterms.it', 'second street research', 'seeker', 'shai', 'sitecheck', 'sitemapper', 'sitesnagger', 'slysearch', 'smartdownload', 'snagger', 'spacebison', 'spankbot', 'spanner', 'spegla', 'spiderbot', 'spiderengine', 'sqworm', 'ssearcher100', 'star downloader', 'stripper', 'sucker', 'superbot', 'surfwalker', 'superhttp', 'surfbot', 'surveybot', 'suzuran', 'sweeper', 'szukacz/1.4', 'tarspider', 'takeout', 'teleport', 'telesoft', 'templeton', 'the.intraformant', 'thenomad', 'tighttwatbot', 'titan', 'tocrawl/urldispatcher','toolpak', 'traffixer', 'true_robot', 'turingos', 'turnitinbot', 'tv33_mercator', 'uiowacrawler', 'urldispatcherlll', 'url_spider_pro', 'urly.warning ', 'utilmind', 'vacuum', 'vagabondo', 'vayala', 'vci', 'visualcoders', 'visibilitygap', 'vobsub', 'voideye', 'vspider', 'w3mir', 'webauto', 'webbandit', 'web.by.mail', 'webcapture', 'webcatcher', 'webclipping', 'webcollage', 'webcopier', 'webcopy', 'webcraft@bea', 'web data extractor', 'webdav', 'webdevil', 'webdownloader', 'webdup', 'webenhancer', 'webfetch', 'webgo', 'webhook', 'web.image.collector', 'web image collector', 'webinator', 'webleacher', 'webmasters', 'webmasterworldforumbot', 'webminer', 'webmirror', 'webmole', 'webreaper', 'websauger', 'websaver', 'website.quester', 'website quester', 'websnake', 'websucker', 'web sucker', 'webster', 'webreaper', 'webstripper', 'webvac', 'webwalk', 'webweasel', 'webzip', 'wget', 'widow', 'wisebot', 'whizbang', 'whostalking', 'wonder', 'wumpus', 'wweb', 'www-collector-e', 'wwwoffle', 'wysigot', 'xaldon', 'xenu', 'xget', 'x-tractor', 'zeus' );
		$check = str_replace($ct_rules, '*', strtolower($this->get_userAgent()));
		if( strtolower($this->get_userAgent())!=$check){
            Error::hack_log(__FILE__, __LINE__, 'Bots attack');
			die(FIREWALL_PROTECTION_BOTS);
		}
        return;
    }
    
    /**
     * Private function protection_REQUESTMETHOD
     * @access private
     * @return none
     */
    private function protection_REQUESTMETHOD()
    {
        $reqMethod = strtolower($this->get_requestMethod());
        if ($reqMethod!='get' && $reqMethod!='head' && $reqMethod!='post' && $reqMethod!='put'){
			Error::hack_log(__FILE__, __LINE__, 'Invalid request');
			die(FIREWALL_PROTECTION_REQUEST);
		}
        return;
    }
    
    /**
     * Private function protection_DOS
     * @access private
     * @return none
     */
    private function protection_DOS()
    {
        if (!$this->get_userAgent() || $this->get_userAgent()=='-'){
			Error::hack_log(__FILE__, __LINE__, 'Dos attack');
			die(FIREWALL_PROTECTION_DOS);
		}
        return;
    }
    
    /**
     * Private function protection_SQL
     * @access private
     * @return none
     */
    private function protection_SQL()
    {
        $stop     = 0;
        
        //check URL datas
        $query    = $this->get_queryString();
		$ct_rules = array( '*/from/*', '*/insert/*', '+into+', '%20into%20', '*/into/*', ' into ', 'into', '*/limit/*', 'not123exists*', '*/radminsuper/*', '*/select/*', '+select+', '%20select%20', ' select ',  '+union+', '%20union%20', '*/union/*', ' union ', '*/update/*', '*/where/*' );
		
        if($query!=str_replace($ct_rules, '*', $query)){
            $stop++;
        }		
        if (preg_match('#\w?\s?union\s\w*?\s?(select|all|distinct|insert|update|drop|delete)#is', $query)){
            $stop++;
        }
		if (preg_match('/([OdWo5NIbpuU4V2iJT0n]{5}) /', rawurldecode( $query ))){
            $stop++;
        }
		if (strstr(rawurldecode($query), '*')){
            $stop++;
        }
        
        //check POST datas
        $query = "";
        foreach($_POST as $key=>$value){
            $query .= $key.$value;
        }

        if($query!=str_replace($ct_rules, '*', $query)){
            $stop++;
        }		
        if (preg_match('#\w?\s?union\s\w*?\s?(select|all|distinct|insert|update|drop|delete)#is', $query)){
            $stop++;
        }
		if (preg_match('/([OdWo5NIbpuU4V2iJT0n]{5}) /', rawurldecode( $query ))){
            $stop++;
        }
		if (strstr(rawurldecode($query), '*')){
		  $stop++;
        }
        
		if (!empty($stop)){
			Error::hack_log(__FILE__, __LINE__, 'Union attack');
			die(FIREWALL_PROTECTION_SQL);
		}
        return;
    }
    
    /**
     * Private function protection_CLICKATACK
     * @access private
     * @return none
     */
    private function protection_CLICKATACK()
    {
        $ct_rules = array( '/*', 'c2nyaxb0', '/*' );
		if($this->get_queryString()!= str_replace($ct_rules, '*', $this->get_queryString())){
			Error::hack_log(__FILE__, __LINE__, 'Click attack');
			die(FIREWALL_PROTECTION_CLICK);
		}
        return;
    }
    
    /**
     * Private function protection_XSSATACK
     * @access private
     * @return none
     */
    private function protection_XSSATACK()
    {
        $ct_rules = array( 'http\:\/\/', 'https\:\/\/', 'cmd=', '&cmd', 'exec', 'concat', './', '../',  'http:', 'h%20ttp:', 'ht%20tp:', 'htt%20p:', 'http%20:', 'https:', 'h%20ttps:', 'ht%20tps:', 'htt%20ps:', 'http%20s:', 'https%20:', 'ftp:', 'f%20tp:', 'ft%20p:', 'ftp%20:', 'ftps:', 'f%20tps:', 'ft%20ps:', 'ftp%20s:', 'ftps%20:', '.php?url=' );
		if($this->get_queryString()!=str_replace($ct_rules, '*', $this->get_queryString())){
			Error::hack_log(__FILE__, __LINE__, 'XSS attack');
			die(FIREWALL_PROTECTION_XSS);
		}
        return;
    }
    
    /**
     * Private function protection_IPBANN.
     * @access private
     * @return none
     */
    private function protection_IPBANN()
    {
        $ips = @file_get_contents($this->ipbann);
        if ($ips!=str_replace($this->get_ip(), "*", $ips)){
            die(FIREWALL_IPBANN);
        }
        return;
    }
    
    /**
     * Constructor
     */
    public function __construct($ipBANN)
    {
        $this->ipbann = $ipBANN;
    }
    
    /**
     * Public function protect.
     * @access public
     * @param string $protection
     * @return none
     */
    public function protect($protection, $arg=false)
    {
        $protection = "protection_".$protection;
        if ($arg){
            $this->$protection($arg);
        } else {
            $this->$protection();
        }
        return;
    }
    
    /**
     * Public function unsetGlobals.
     * @access public
     * @return none
     */
    public function unsetREGISTERGLOBALS()
    {
        if (ini_get('register_globals')){
            
            ini_set("register_globals", "0");

            $allow = array('_ENV' => 1, '_GET' => 1, '_POST' => 1, '_COOKIE' => 1, '_FILES' => 1, '_SERVER' => 1, 
                            '_REQUEST' => 1,'_SESSION'=>1, 'GLOBALS' => 1, 'db'=>1, 'sys'=>1, 'info'=>1, 'com'=>1, 
                            'ajax'=>1, 'cm'=>1, 'content'=>1);
            foreach($GLOBALS as $key=>$value){
                if (!isset($allow[$key]) && isset($GLOBALS[$key])){
                    unset($GLOBALS[$key]);
                }
            }
        }
        if (ini_get("display_errors")){
          //  ini_set("display_errors", "0");
        }
        return;
    }
}

?>