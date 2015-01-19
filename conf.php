<?php
  $mysqli = new mysqli("{HOST}", "{USER}", "{PASSWORD}", "{DATABASE}");
  if ($mysqli->connect_errno) {
    echo '
    <div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title">DB Error</h3>
			</div>
			<div class="panel-body">
				Error connecting to database :(
			</div>
		</div>';
  }
  $apikey = "{APIKEY}"; //http://steamcommunity.com/dev/apikey
  $debugOn = false;
  $cacheTime = 15000;
?>