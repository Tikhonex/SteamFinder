<?php
include ("conf.php");
?>
<?php 
error_reporting (0);
if(isset($_GET['s']))
{
    $okay = $_GET['s'];
    
    $steamid1 = '/^STEAM_0:([0|1]):([\d]+)$/';	//STEAM_0:0:14982899
    $steamid2 = '/^([\d]+)$/'; //76561197990231526
    $steamid3 = '/^[^-_\d]{1}[-a-zA-Z_\d]+$/'; 	//Tikhonex
    $steamid4 = '~^(http[s]?://)?(www\.)?steamcommunity.com/profiles/([^-_]{1}[\d(/)?]+)$~'; //steamcommunity.com/profiles/76561197990231526
    $steamid5 = '~^(http[s]?://)?(www\.)?steamcommunity.com/id/([^-_]{1}[-a-zA-Z_\d(/)?]+)$~'; //steamcommunity.com/id/tikhonex

    if (preg_match($steamid1, $okay, $matches))  //Если данные из Input вида "STEAM_0:0:14982899"
    {
	$valid1 = $matches[1];
	$valid2 = $matches[2];
	$realokay = ($valid2*2) + 76561197960265728 + $valid1; //Формула расчета steamID64 из STEAM_0:X:XXXXXXXX

        $urljson = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=BC29D18B925718D6A8A17BCB9B925517&steamids=$realokay");
        $data = (array) json_decode($urljson)->response->players[0];
        $profileurl = $data['profileurl'];							//Находим profileurl (customurl)
    }

    if (preg_match($steamid2, $okay)) 
    {
        $urljson = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=BC29D18B925718D6A8A17BCB9B925517&steamids=$okay");
        $data = (array) json_decode($urljson)->response->players[0];
        $profileurl = $data['profileurl'];							//Находим profileurl (customurl)
    }

    if (preg_match($steamid4, $profileurl, $matchespro))		//Если profileurl вида "steamcommunity.com/profiles/76561197990231526", находим "76561198036370701" из ссылки
    { 
        if(substr($matchespro[3], -1) == '/')						//Если на конце знак "/"
        {
            $myurl = substr($matchespro[3], 0, -1);					//Убираем его
        }
	else {$myurl = $matchespro[3];}
        
	$slf = "http://steamcommunity.com/profiles/$myurl/?xml=1";
	$url =  simplexml_load_file($slf);
	$link = "http://steamcommunity.com/profiles/$myurl";
    }

    if (preg_match($steamid5, $profileurl, $matchesid))			//Если profileurl вида "steamcommunity.com/id/tikhonex", находим "tikhonex" из ссылки
    { 
        if(substr($matchesid[3], -1) == '/') 						//Если на конце знак "/"
	{						
            $myurl = substr($matchesid[3], 0, -1);					//Убираем его
	}
	else {$myurl = $matchesid[3];}
        $slf = "http://steamcommunity.com/id/$myurl/?xml=1";
	$url =  simplexml_load_file($slf);
	$link = "http://steamcommunity.com/id/$myurl";
    }

    if (preg_match($steamid3, $okay)) 							//Если Input вида "Tikhonex"
    {
        $slf = "http://steamcommunity.com/id/$okay/?xml=1";
	$url =  simplexml_load_file($slf);
	$link = "http://steamcommunity.com/id/$okay";
    } 
    if (preg_match($steamid4, $okay)) 
    {
        if (preg_match($steamid4, $okay, $matchespro))		//Если Input вида "steamcommunity.com/profiles/76561197990231526", находим "76561197990231526" из ссылки
        { 
            if(substr($matchespro[3], -1) == '/')						//Если на конце знак "/"
            {					
                $myurl = substr($matchespro[3], 0, -1);					//Убираем его
            }
            else {$myurl = $matchespro[3];}

            $urljson = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=BC29D18B925718D6A8A17BCB9B925517&steamids=$myurl");
            $data = (array) json_decode($urljson)->response->players[0];
            $profileurl = $data['profileurl'];							//Проверяем, есть ли customurl

            if (preg_match($steamid4, $profileurl, $matchesprox))		//Если profileurl вида "steamcommunity.com/profiles/76561197990231526", находим "76561197990231526" из ссылки
            { 
                if(substr($matchesprox[3], -1) == '/')						//Если на конце знак "/"
                {					
                    $myurlx = substr($matchesprox[3], 0, -1);					//Убираем его
                }
                else {$myurlx = $matchesprox[3];}
                $slf = "http://steamcommunity.com/profiles/$myurlx/?xml=1";
                $url =  simplexml_load_file($slf);
                $link = "http://steamcommunity.com/profiles/$myurlx";
            }

            if (preg_match($steamid5, $profileurl, $matchesprox))		//Если profileurl вида "steamcommunity.com/profiles/76561197990231526", находим "76561197990231526" из ссылки
            { 
                if(substr($matchesprox[3], -1) == '/')						//Если на конце знак "/"
                {					
                    $myurlx = substr($matchesprox[3], 0, -1);					//Убираем его
                }
                else {$myurlx = $matchesprox[3];}
                $slf = "http://steamcommunity.com/id/$myurlx/?xml=1";
                $url =  simplexml_load_file($slf);
                $link = "http://steamcommunity.com/id/$myurlx";
            }
        }
	
    }

    if (preg_match($steamid5, $okay, $matchesid))			//Если profileurl вида "steamcommunity.com/id/tikhonex", находим "tikhonex" из ссылки
    { 
        if(substr($matchesid[3], -1) == '/') 						//Если на конце знак "/"
	{						
            $myurl = substr($matchesid[3], 0, -1);					//Убираем его
	}
	else {$myurl = $matchesid[3];}
        $slf = "http://steamcommunity.com/id/$myurl/?xml=1";
	$url =  simplexml_load_file($slf);
	$link = "http://steamcommunity.com/id/$myurl";
    }

    $sid64 = $url->steamID64;
    if (($sid64 - 76561197960265728 - 1)-(($sid64 - 76561197960265728 - 1)/2) - floor (($sid64 - 76561197960265728 - 1)/2) == 0)
        {$ass = 1;}
    else
        {$ass = 0;}	
    $sid = $sid64 - 76561197960265728;

    $myfriend = simplexml_load_file($link . "/friends/?xml=1");
}
?>

<?php
$sql = mysql_query ("SELECT * FROM `steamidfinder` WHERE `steamid64` = ".$url->steamID64."");
    $f = mysql_fetch_array($sql);
	if($f[status] == NULL){
		if(empty($f[message])){
			$title = "";
			$class = "";}}
	elseif($f[status] == 1){
		$class = "label label-success";
		$title = $f[message];}
	elseif($f[status] == 2){
		if(empty($f[message]))
			$title = "White List";
		else
			$title = $f[message];
		$class = "label";}
	elseif($f[status] == 3){
		if(empty($f[message]))
			$title = "Blacklisted";
		else
			$title = $f[message];
		$class = "label label-important";}
		
if((!empty($url->steamID64)) AND (!empty($okay))){
if (!empty($url->steamID64)){$show = "STEAM_0:" . $ass . ":" . floor($sid/2);}
echo '<div class="profile">
<div class="left">
<div class="avatar"><img style="border: 1px solid rgb(179,179,179)" src="'.$url->avatarFull.'" height="150" width="150"><br /><br /><center><a href="'.$link.'" target="_blank">Steam Profile</a></center></div>
<div class="details">
<div class="heading">
<div style="float:left;"><strong>'.$url->steamID.'</strong> <span class="'.$class.'">'.$title.'</span></div>

<div class="clear_both"></div>
</div>
<div class="row">
<div class="row_left">Last Online</div>
<div class="row_right">'.$url->onlineState.'</div>
<div class="clear_both"></div>	
</div>			
<div class="divider"></div>							
<div class="row">
<div class="row_left">Profile Privacy</div>
<div class="row_right">'.$url->privacyState.'</div>
<div class="clear_both"></div>	
</div>
<div class="divider"></div>
<div class="row">
<div class="row_left">Registered</div>
<div class="row_right">'.$url->memberSince.'</div>
<div class="clear_both"></div>	
</div>
<div class="divider"></div>
<div class="row">
<div class="row_left">steamID</div>
<div class="row_right">'.$show.'</div>
<div class="clear_both"></div>	
</div>
<div class="divider"></div>
<div class="row">
<div class="row_left">steamID64</div>
<div class="row_right">'.$url->steamID64.'</div>
<div class="clear_both"></div>	
</div>						
</div>
<div class="clear_both"></div>
</div>
<div class="right">
<div class="heading">More</div>
<div class="row">
<div class="row_left">Location</div>
<div class="row_right">'.$url->location.'</div>
<div class="clear_both"></div>	
</div>
<div class="divider"></div>
<div class="row">
<div class="row_left">Realname</div>
<div class="row_right">'.$url->realname.'</div>
<div class="clear_both"></div>	
</div>
<div class="divider"></div>
<div class="row">
<div class="row_left">Trade Ban Status</div>
<div class="row_right">'.$url->tradeBanState.'</div>
<div class="clear_both"></div>	
</div>
<div class="divider"></div>
<div class="row">
<div class="row_left">VAC</div>
<div class="row_right">'.$url->vacBanned.'</div>
<div class="clear_both"></div>	
</div>
<div class="divider"></div>
<div class="row">
<div class="row_left">Limited Account</div>
<div class="row_right">'.$url->isLimitedAccount.'</div>
<div class="clear_both"></div>	
</div>						
</div>
<div class="clear_both"></div>					
</div>';
}

if ((empty($url->steamID64)) AND (!empty($okay)) AND ((preg_match($steamid1, $okay)) OR  (preg_match($steamid2, $okay)) OR (preg_match($steamid3, $okay)) OR (preg_match($steamid4, $okay)) OR (preg_match($steamid5, $okay))) )
{
    echo "<div class=\"alert alert-error\">Ошибка запроса &laquo;$okay&raquo;! Пожалуйста, повторите свой запрос.</div>"; 
}

if ((!empty($okay)) AND (!preg_match($steamid1, $okay)) AND  (!preg_match($steamid2, $okay)) AND (!preg_match($steamid3, $okay)) AND (!preg_match($steamid4, $okay)) AND (!preg_match($steamid5, $okay)) )
{
    echo "<div class=\"alert alert-error\">Ошибка. Проверьте правильность введенных данных.</div>";
}
?>