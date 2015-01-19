<?
	require ('steamauth/steamauth.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>SteamFinder</title>
    <meta name="viewport"#outputinfo="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="favicon.ico" />
    <link rel="stylesheet" href="./assets/css/bootstrap.css" media="screen">
    <link rel="stylesheet" href="./assets/css/bootswatch.min.css">
    <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/bootswatch.js"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="./assets/js/html5shiv.js"></script>
      <script src="./assets/js/respond.min.js"></script>
    <![endif]-->
    <meta name="author" content="Tikhonex" />
    <meta name="description" content="steamfinder.tk" />
    <meta name="keywords" content="steam id checker, steam id finder, scammer database, trusted database, tikhonex" />
    <script>
      function findSID(id) {
        window.history.pushState("", "", "/sid:" + id);
        $.ajax({
          type: "GET",
          data: "s=" + id,
          url: 'steam.php',
          beforeSend:function() {
            $("#outputinfo").html("<center><img src='./assets/img/load.gif'></br></br>Wait...</center>");
          },success: function(html) {
            $("#outputinfo").html(html);
          }
        });
      }
    </script>
  </head>
  <body>
  <div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a href="../" class="navbar-brand">SteamFinder</a>
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
          <ul class="nav navbar-nav">
            <li>
              <a href="/">Main</a>
            </li>
            <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#" id="download">About<span class="caret"></span></a>
              <ul class="dropdown-menu" aria-labelledby="download">
                <li><a href="#">API (soon)</a></li>
                <li class="divider"></li>
                <li><a target="_blank" href="http://steamcommunity.com/profiles/76561197990231526">Author</a></li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
          <?php
            if(!isset($_SESSION['steamid'])) {
              steamlogin(); //login button
            } else {
              include ('steamauth/userInfo.php');
              //Protected content
              echo "<li><p style=\"margin-top: 5%;\">". $steamprofile['personaname'] . " <a title=\"Click to search\" href=//". $_SERVER["SERVER_NAME"] . "/sid:" . $steamprofile['steamid'] . "><img title=\"Click for more information\" src=".$steamprofile['avatar']." class=\"img-circle\"></a></p></li>";
              logoutbutton();
            }
          ?>
        </ul>
        </div>
      </div>
    </div>
  <div class="container">
  <div style="margin-top:5%;" class="row clearfix">
    <div class="col-md-3 column">
      <!-- Left Block -->
    </div>
    <div class="col-md-5 column"> <!-- MAIN DIV -->
      <div class="form-group">
        <label class="control-label">Find any user Steam, example: <a href="./sid:tikhonex">tikhonex</a></label>
        <div>
          <form method="post" name="formid" id="formid">
            <input type="text" width="100%" class="form-control" id="steamid" name="s" title="Example:&#013;Tikhonex&#013;29965798&#013;76561197990231526&#013;STEAM_0:0:14982899&#013;steamcommunity.com/id/tikhonex&#013;steamcommunity.com/profiles/76561197990231526" value="<?php if(isset($_GET['s'])) echo $_GET['s']; ?>">
            <input type="submit" id="invbtn" style="position: absolute;;left: -99999px;" value="" />
          </form>
          <script>
            $('#formid').submit(function() {
              var id = $("#steamid").val().replace(/(?:https?\:)?\/\//i, '');
              $.ajax({
                type: "GET",
                data: "s="+id,
                url: 'steam.php',
                beforeSend:function() {
                  $("#outputinfo").html("<center><img src='./assets/img/load.gif'></br></br>Wait...</center>");
                },success: function(html) {
                  $("#outputinfo").html(html);
                  if (id.split('/')[2] != undefined) window.history.pushState("", "", "/sid:"+id.split('/')[2]);
                  else window.history.pushState("", "", "/sid:"+id);
                }
              });
              return false;
            });
            $(document).ready(function() {
              var id = '<?php echo $_GET['s']; ?>';
              if (id != null) {
                $.ajax({
                  type: "GET",
                  data: "s="+id,
                  url: 'steam.php',
                  beforeSend:function() {
                    $("#outputinfo").html("<center><img src='./assets/img/load.gif'></br></br>Wait...</center>");
                  },success: function(html) {
                    $("#outputinfo").html(html);
                  }
                });
              }
              return false;
            });
          </script>
        </div>
      </div>
      <div id="outputinfo"> <!-- OUTPUT INFO -->
      </div>
    </div>
    <div class="col-md-3 column">
      <!-- Right Block -->
    </div>
  </div>
  </div>
  </body>
</html>