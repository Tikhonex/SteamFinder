<!doctype html>
<html>
  <head>
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.form.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <title>SteamIDFinder</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/bootstrap-responsive.css">
    <link rel="stylesheet" href="css/style.css">
    <meta name="author" content="Tikhonex" />
    <meta name="description" content="steamfinder.tk" />
    <meta name="keywords" content="steam id checker, steam id finder, scammer database, trusted database, tikhonex" />

  </head>
  <body>
    <div class="container">
      <div class="row main-features"></div>
      <div class="hero-unit hidden-phone">
        <ul class="nav nav-tabs"></ul>
        <h1>SteamIDFinder</h1>
        <form action="site/action" method="post" id="formid">
            <input type="text" id="steamid" name="s" title="Например:&#013;Tikhonex&#013;76561197990231526&#013;STEAM_0:0:14982899&#013;steamcommunity.com/id/tikhonex&#013;steamcommunity.com/profiles/76561197990231526" size="60" class="search-query input-block-level" placeholder="Введите SteamID / SteamCommunityID / Имя профиля / URL профиля">
			<input type="submit" style="position: absolute;;left: -99999px;" value="" />
        </form>
		<script>
		$('#formid').submit(function() {
		var id = $("#steamid").val();
		$.ajax({
		type: "GET",
		data: "s="+id,
		url: 'steam.php',
		beforeSend:function()
		{
		$(".content").html("<center><img src='ajax-loader.gif'></br></br>Секундочку</center>");
		},
		success: function(html) {
		$(".content").html(html);
		}
		});
		return false;
		});
		</script>
      </div>
    </div>
	<div class="profile">
	    <div class="content">
            
	    </div>
	</div>
  </body>
</html>