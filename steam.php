<?php
	include ("conf.php"); //load config file
	if ($debugOn) {
		$start = microtime(true); // Время выполнения скрипта
	}
	
	/* START READ CACHE */
	$url = $_GET['s'];
	$encode = md5($url);
	$modif=time()-@filemtime ("cache/$encode");
	if ($modif < $cacheTime) { 
		include ("cache/$encode");
		exit();
	}
	ob_start ();
	/* END READ CACHE */
	
	$isVACBan = '';
	$isTradeBan = '';
	$isLimitedAccount = '';
	
	//error_reporting (0);
	if(isset($_GET['s'])) {
		$okay = $_GET['s'];
		$steamid1 = '/^STEAM_0:([0|1]):([\d]+)$/';	//STEAM_0:0:14982899
		$steamid2 = '/^([\d]{17})$/'; //76561197990231526
		$steamid3 = '/^[^-_\d]{1}[-a-zA-Z_\d]+$/'; 	//Tikhonex
		$steamid4 = '~^(http[s]?://)?(www\.)?steamcommunity.com/profiles/([^-_]{1}[\d(/)?]+)$~'; //steamcommunity.com/profiles/76561197990231526
		$steamid5 = '~^(http[s]?://)?(www\.)?steamcommunity.com/id/([^-_]{1}[-a-zA-Z_\d(/)?]+)$~'; //steamcommunity.com/id/tikhonex
		$steamid6 = '/^([\d]{1,13})$/'; //29965798

		if (preg_match($steamid1, $okay, $matches))  //Если данные из Input вида "STEAM_0:0:14982899"
		{
			if (debugOn) echo 'steamid1';
			$valid1 = $matches[1];
			$valid2 = $matches[2];
			$realokay = ($valid2 * 2) + 76561197960265728 + $valid1; //Формула расчета steamID64 из STEAM_0:X:XXXXXXXX

			$urljson = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$apikey&steamids=$realokay");
			$data = (array) json_decode($urljson)->response->players[0];
			$profileurl = $data['profileurl'];							//Находим profileurl (customurl)*/
		
			$slf = "http://steamcommunity.com/profiles/$realokay/?xml=1";
			$url =  simplexml_load_file($slf);
			$link = "http://steamcommunity.com/profiles/$realokay";
		}
	
		if (preg_match($steamid6, $okay))  //Если данные из Input вида "29965798"
		{
			$myurl = $okay + 76561197960265728; //Формула расчета steamID64 из steamID32
			$slf = "http://steamcommunity.com/profiles/$myurl/?xml=1";
			$url =  simplexml_load_file($slf);
			$link = "http://steamcommunity.com/profiles/$myurl";
		}

		if (preg_match($steamid2, $okay)) 
		{
			$urljson = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$apikey&steamids=$okay");
			$data = (array) json_decode($urljson)->response->players[0];
			$profileurl = $data['profileurl'];							//Находим profileurl (customurl)
			$personaname = $data['personaname'];
			$avatarfull = $data['avatarfull'];	
			
			$slf = "http://steamcommunity.com/profiles/$realokay/?xml=1";
			$url =  simplexml_load_file($slf);
			$link = "http://steamcommunity.com/profiles/$realokay";
		}

		if (preg_match($steamid4, $profileurl, $matchespro))		//Если profileurl вида "steamcommunity.com/profiles/76561197990231526", находим "76561198036370701" из ссылки
		{
			if(substr($matchespro[3], -1) == '/') {						//Если на конце знак "/"
				$myurl = substr($matchespro[3], 0, -1);					//Убираем его
			} else {
				$myurl = $matchespro[3];
			}
			$slf = "http://steamcommunity.com/profiles/$myurl/?xml=1";
			$url =  simplexml_load_file($slf);
			$link = "http://steamcommunity.com/profiles/$myurl";
		}

		if (preg_match($steamid5, $profileurl, $matchesid))			//Если profileurl вида "steamcommunity.com/id/tikhonex", находим "tikhonex" из ссылки
		{ 
			if(substr($matchesid[3], -1) == '/') { 						//Если на конце знак "/"
				$myurl = substr($matchesid[3], 0, -1);					//Убираем его
			}
			else { $myurl = $matchesid[3]; }
			$slf = "http://steamcommunity.com/id/$myurl/?xml=1";
			$url =  simplexml_load_file($slf);
			$link = "http://steamcommunity.com/profiles/$url->steamID64";
		}

		if (preg_match($steamid3, $okay)) 							//Если Input вида "Tikhonex"
		{
			$slf = "http://steamcommunity.com/id/$okay/?xml=1";
			$url =  simplexml_load_file($slf);
			$link = "http://steamcommunity.com/profiles/$url->steamID64";
		} 
		
		if (preg_match($steamid4, $okay)) 
		{
			if (preg_match($steamid4, $okay, $matchespro))		//Если Input вида "steamcommunity.com/profiles/76561197990231526", находим "76561197990231526" из ссылки
			{ 
				if(substr($matchespro[3], -1) == '/') {						//Если на конце знак "/"
					$myurl = substr($matchespro[3], 0, -1);					//Убираем его
				}
				else {$myurl = $matchespro[3];}
				$urljson = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$apikey&steamids=$myurl");
				$data = (array) json_decode($urljson)->response->players[0];
				$profileurl = $data['profileurl'];							//Проверяем, есть ли customurl

				if (preg_match($steamid4, $profileurl, $matchesprox))		//Если profileurl вида "steamcommunity.com/profiles/76561197990231526", находим "76561197990231526" из ссылки
				{
					if(substr($matchesprox[3], -1) == '/') {						//Если на конце знак "/"
						$myurlx = substr($matchesprox[3], 0, -1);					//Убираем его
					}
					else {$myurlx = $matchesprox[3];}
					$slf = "http://steamcommunity.com/profiles/$myurlx/?xml=1";
					$url =  simplexml_load_file($slf);
					$link = "http://steamcommunity.com/profiles/$myurlx";
				}

				if (preg_match($steamid5, $profileurl, $matchesprox))		//Если profileurl вида "steamcommunity.com/profiles/76561197990231526", находим "76561197990231526" из ссылки
				{ 
					if(substr($matchesprox[3], -1) == '/') {						//Если на конце знак "/"		
						$myurlx = substr($matchesprox[3], 0, -1);					//Убираем его
					}
					else { $myurlx = $matchesprox[3]; }
					$slf = "http://steamcommunity.com/id/$myurlx/?xml=1";
					$url =  simplexml_load_file($slf);
					$link = "http://steamcommunity.com/profiles/$url->steamID64";
				}
			}
	
		}

		if (preg_match($steamid5, $okay, $matchesid))			//Если profileurl вида "steamcommunity.com/id/tikhonex", находим "tikhonex" из ссылки
		{ 
			if(substr($matchesid[3], -1) == '/') { 						//Если на конце знак "/"	
				$myurl = substr($matchesid[3], 0, -1);					//Убираем его
			}
			else {$myurl = $matchesid[3];}
			$slf = "http://steamcommunity.com/id/$myurl/?xml=1";
			$url =  simplexml_load_file($slf);
			$link = "http://steamcommunity.com/id/$myurl";
		}

		$sid64 = $url->steamID64;
		if (($sid64 - 76561197960265728 - 1)-(($sid64 - 76561197960265728 - 1)/2) - floor (($sid64 - 76561197960265728 - 1)/2) == 0)
			$ass = 1;
		else
			$ass = 0;
		$sid = $sid64 - 76561197960265728;

		//$myfriend = simplexml_load_file($link . "/friends/?xml=1");
	}
	
	if(isset($_GET['dev'])) {
		echo base64_decode("0KDQsNC30YDQsNCx0L7RgtGH0LjQutC+0Lwg0Y/QstC70Y/QtdGC0YHRjyBBbGV4YW5kZXIgKFRpa2hvbmV4KSBTaHRlZmFuOiBodHRwOi8vc3RlYW1jb21tdW5pdHkuY29tL3Byb2ZpbGVzLzc2NTYxMTk3OTkwMjMxNTI2");
	}
	
	/* OUTPUT */
  
	$title = ""; $class = "default";
	$formgroup = "form-group";
	
	if ($result = $mysqli->query("SELECT * FROM `steamidfinder` WHERE `steamid64` = ".$url->steamID64."")) {
		if ($obj = $result->fetch_object()) {
			// таблица не пуста
			if ($obj->status == null) {
				$title = ""; $class = "";
			} elseif ($obj->status == 1) {
				$class = "success";
				$title = $obj->message;
			} elseif ($obj->status == 2) {
				$class = "info";
				if(empty($obj->message))
					$title = "White List";
				else
					$title = $obj->message;
			} elseif ($obj->status == 3) {
				$class = "primary";
				$title = "Blacklisted";
				if(!empty($obj->message))
					echo '
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title">Warning: User Reported for Fraud</h3>
						</div>
						<div class="panel-body">
							<b>Proof:</b> <a target="_blank" href="' . $obj->message . '">link</a>
						</div>
					</div>';
					//$title = $obj->message;
			}
		} else {
			// таблица пуста
		}
	}
	
	foreach (($url->groups->group) as $group){
		if ($group->groupID64 == "103582791429521614"){ // SUFMods
			$class = "success";
			$title = "Steam Moderator";
		}
	}
	
	foreach (($url->groups->group) as $group){
		if ($group->groupID64 == "103582791429521412"){ // Valve
			$class = "success";
			$title = "Valve employee";
			$formgroup = "form-group has-success";
		}
	}
	
	if($url->vacBanned == 0) $vacBanned_Result = 'default'; else $vacBanned_Result = 'primary';
	if($url->tradeBanState != 'None') $tradeBan_Result = 'primary'; else $tradeBan_Result = 'default';
	if($url->isLimitedAccount == 0) $isLimitedAccount_Result = 'default'; else $isLimitedAccount_Result = 'primary';
	if($url->steamID == '') $personaname_Result = $personaname; else $personaname_Result = $url->steamID;
	//if($url->location == '') $location_Result = '-'; else { $location_Result = preg_replace("/^(.+?,){2}/u", '' ,$url->location); }; //оставляем только страну
	//if($url->realname == '') $realname_Result = '-'; else $realname_Result = $url->realname;
	//if($url->memberSince == '') $memberSince_Result = '<i>private</i>'; else $memberSince_Result = $url->memberSince;
	if($avatarfull != "")
		$avatarFull_Result = $avatarfull;
	elseif($url->avatarFull != "")
		$avatarFull_Result = $url->avatarFull;
	else $avatarFull_Result = "./assets/img/none_avatar.jpg";

if((!empty($url->steamID64)) AND (!empty($okay))){
if (!empty($url->steamID64)){$show = "STEAM_0:" . $ass . ":" . floor($sid/2);}
echo '<script>document.title = \'SteamFinder — ' . $personaname_Result . ' | ' . $url->steamID64 . ' | ' . ($url->steamID64-76561197960265728) . ' | ' . $show . '\';</script>';
echo '
<div class="row clearfix"> <!-- INFORMATION -->
	<div class="col-md-8 column">
		<h2>'.$personaname_Result.'</h2>
		<span class="label label-'.$class.'">'.$title.'</span> <span class="label label-'.$tradeBan_Result.'">Trade</span> <span class="label label-'.$vacBanned_Result.'">VAC</span> <span class="label label-'.$isLimitedAccount_Result.'">Community</span>
	</div>
	<div class="col-md-4 column">
		<a target="_blank" href="'.$link.'"><img src="'.$avatarFull_Result.'" width="140" height="140" class="img-thumbnail" /></a>
	</div>
</div><br />
			
<div class="row clearfix"> 
	<div class="col-md-12 column">
		<div class="'.$formgroup.'">
			<div class="input-group">
				<span class="input-group-addon">steamID</span>
				<input type="text" class="form-control" onclick="this.select();" value="'.$show.'">
				<span class="input-group-btn">
				</span>
			</div>
		</div>
	</div>
	<div class="col-md-12 column">
		<div class="'.$formgroup.'">
			<div class="input-group">
				<span class="input-group-addon">steamID32</span>
				<input type="text" class="form-control" onclick="this.select();" value="'.($url->steamID64-76561197960265728).'">
				<span class="input-group-btn">
				</span>
			</div>
		</div>
	</div>
	<div class="col-md-12 column">
		<div class="'.$formgroup.'">
			<div class="input-group">
				<span class="input-group-addon">steamID64</span>
				<input type="text" class="form-control" onclick="this.select();" value="'.$url->steamID64.'">
				<span class="input-group-btn">
				</span>
			</div>
		</div>
	</div>
</div> <!-- INFORMATION -->';
}
	/* START SAVE CACHE */
	$cache = ob_get_contents();
	ob_end_clean ();
	echo $cache;
	$fp = @fopen ("cache/$encode", "w");
	@fwrite ($fp, $cache);
	@fclose ($fp);
	/* END SAVE CACHE */
  
	if ($debugOn) {
		// Время выполнения скрипта
		$time = microtime(true) - $start;
		printf('<p style="text-align: right;"><small><i>found a within %.4F sec.</i></small></p>', $time);
	}

if ((empty($url->steamID64)) AND (!empty($okay)) AND ((preg_match($steamid1, $okay)) OR  (preg_match($steamid2, $okay)) OR (preg_match($steamid3, $okay)) OR (preg_match($steamid4, $okay)) OR (preg_match($steamid5, $okay)) OR (preg_match($steamid6, $okay))))
{
	echo '<div class="alert alert-dismissable alert-danger">
			<button type="button" class="close" data-dismiss="alert">×</button>
			<strong>Oh snap!</strong> <a href="#" class="alert-link">Change a few things up</a> and try submitting again.
		</div>';
}

if ((!empty($okay)) AND (!preg_match($steamid1, $okay)) AND  (!preg_match($steamid2, $okay)) AND (!preg_match($steamid3, $okay)) AND (!preg_match($steamid4, $okay)) AND (!preg_match($steamid5, $okay)) AND (!preg_match($steamid6, $okay)))
{
	echo '<div class="alert alert-dismissable alert-danger">
			<button type="button" class="close" data-dismiss="alert">×</button>
			<strong>Make sure</strong> that you have correctly written request and try submitting again.
		</div>';
}
?>