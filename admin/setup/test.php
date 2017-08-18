<?php

$m[]=array("engine"=>"home", "name"=>"Főoldal", "url"=>"http://phys.tk/");
$m[]=array("engine"=>"content", "name"=>"Cikkek", "url"=>"http://phys.tk/cikkek/");
$m[]=array("engine"=>"simulation", "name"=>"Szimulációk", "url"=>"http://phys.tk/szimulaciok/");
$m[]=array("engine"=>"community","name"=>"Közösség", "url"=>"http://phys.tk/community/");
echo addslashes(serialize($m));

echo "<br><br>";

$sm["community"][]=array("useronly"=>1, "page"=>"", "name"=>"Üzenő Fal", "url"=>"http://phys.tk/community/");

$sm["community"][]=array("useronly"=>0, "page"=>"forum", "name"=>"Fórum", "url"=>"http://phys.tk/community/forum/");

$sm["community"][]=array("useronly"=>0, "page"=>"userlist", "name"=>"Felhasználók listálya", "url"=>"http://phys.tk/community/user/list/");



echo addslashes(serialize($sm));

echo "<br><br>";

$options = array("hideEmail"=>0, "sex"=>1, "activation"=>0, "wallNumOfMaxPosts"=>10,
                        "friends"=>0, "friendRequest"=>0, "publicWall"=>1);
						
echo htmlentities(serialize($options), ENT_QUOTES, "UTF-8");

echo "<br><br>";

$userLevel = array(0=>"Bannolt", 1=>"Regisztrált", 2=>"Tag", 3=>"Tag", 4=>"Tag", 5=>"Tag", 6=>"Tag", 7=>"Tag", 8=>"Tag", 9=>"Admin");

echo addslashes(serialize($userLevel));

echo "<br /><br /><br /><pre>";



/*echo strtotime("2011-08-03 20:29:00"), "<br />";
echo strtotime("2011-08-03 20:30:20"), "<br>";
class XX{
public static function cmpByDate($a, $b)
{
	return strtotime($a['post_date'])-strtotime($b['post_date']);
}}

$xd[]=array("post_date"=>"2011-08-03 20:29:00", "id"=>1, "msg1");
$xd[]=array("post_date"=>"2011-08-03 10:29:00", "id"=>2, "msg2");
$xd[]=array("post_date"=>"2011-08-01 10:29:00", "id"=>3, "msg3");
$xd[]=array("post_date"=>"1993-08-03 20:29:00", "id"=>1, "msg1");
$xd[]=array("post_date"=>"2011-08-03 10:29:04", "id"=>4, "msg4");

echo "3, 2, 4, 1";

echo "<pre>";
usort($xd, "XX::cmpByDate");
print_r($xd);

$x=unserialize('a:4:{s:9:"hideEmail";s:1:"0";s:3:"sex";s:1:"1";s:10:"activation";s:32:"FCf0E22qYNKV2V43TUe9I2IlSx5m5nTW";s:17:"wallNumOfMaxPosts";s:2:"10";}');
unset($x["activation"]);
$x["friends"]=array(12,1,2);
echo htmlentities(serialize($x));
$x[]['s']='1';
$x[]['x']='2';
$y[]['s']='3';
$y[]['s']='4';
print_r(array_merge($x,$y));

try {
	try {
		throw new Exception;
	} catch(Exception $e){
		echo "kozepe";
		throw new Exception;
	}
} catch(Exception $e){
echo "vege";
}


/**
 * is_2darray
 * @param array $array
 * @return boolean
 *//*
function is_2darray($array)
{
    foreach($array as $item){
        if (is_array($item)){
            return true;
        } else {
            return false;
        }
    }
    return false;
}

$xd[0]['xd']="asda";
$xd[0]['yd']="asda";

$xd[1]['xd']="asda";
$xd[1]['yd']="asda";

$yd['xd']="asda";
$yd['yd']="asda";

echo "<br>MD:".is_2darray($yd)."<br>";

echo is_array("sddf");



a:4:{s:9:&quot;hideEmail&quot;;s:1:&quot;0&quot;;s:3:&quot;sex&quot;;s:1:&quot;1&quot;;s:10:&quot;activation&quot;;s:32:&quot;FCf0E22qYNKV2V43TUe9I2IlSx5m5nTW&quot;;s:17:&quot;wallNumOfMaxPosts&quot;;s:2:&quot;10&quot;;}
*/
/*
class XX
{
	public $x=1, $y=2;
	
}
class YY
{
	private $xx;
	public function __construct()
	{
	$this->xx=new XX;
	}
	public function __get($name)
	{
		return $this->xx->$name;
	}
}

$yy = new YY;
echo $yy->y;*/
/*
$userLevel = array(0=>"Bannolt", 1=>"Regisztrált", 2=>"Tag", 3=>"Tag", 4=>"Tag", 5=>"Tag", 6=>"Tag", 7=>"Tag", 8=>"Tag", 9=>"Admin");

echo addslashes(serialize($userLevel));*/
/*

$op = unserialize(html_entity_decode('a:7:{s:9:&quot;hideEmail&quot;;i:0;s:3:&quot;sex&quot;;i:1;s:10:&quot;activation&quot;;i:0;s:17:&quot;wallNumOfMaxPosts&quot;;i:10;s:7:&quot;friends&quot;;N;s:13:&quot;friendRequest&quot;;a:2:{i:0;s:1:&quot;3&quot;;i:1;s:1:&quot;1&quot;;}s:10:&quot;publicWall&quot;;i:1;}', ENT_QUOTES, "UTF-8"));

echo "<pre>";
print_r($op);

$id ="1";
var_dump($id);
if (preg_match("/^([0-9])+$/", $id)){
	echo "ok";
}*/
?>