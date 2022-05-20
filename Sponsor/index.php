<?php
  //overflow:scroll;
    $dbconn = pg_connect("host=localhost port=5432 dbname=PickItUp
                user=postgres password=postgres") 
                or die('Could not connect: ' . pg_last_error());
    $username = $_GET['username'];            
    $query = "select picture from user_profile where username = $1";
    $result = pg_query_params($dbconn,$query,array($username));
    $line=pg_fetch_array($result,null,PGSQL_ASSOC);
    $pic= $line['picture'];
    
?>
<!DOCTYPE html>
<html>
    <head>
        <title> PickItUp | Sponsors </title>
        <link rel="stylesheet" type="text/css" href="../style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Square+Peg&family=Tapestry&display=swap" rel="stylesheet">

    </head>

    <body>
        <div class="banner">
    	<a class="logo" href = "../index.php?username=<?php echo $username?>" style="text-decoration: none">PICKITUP</a>
        <script type="text/javascript">
        document.getElementById("logo"). onclick = function () {
            var url_string = window.location.href;
            var url = new URL(url_string);
            var username = url.searchParams.get("username"); 
            location.href = "../index.php?username="+username;
        };
        </script>
        <form class="searchbar" name="searchbar" method="POST" action="search.php">
        <input type="text" name="search" placeholder="Search">
        <input type="submit" class="search-btn" value="SEARCH">
        </form>
        <a  class = "nav-link" href="../Challenge/index.php?username=<?php echo $username ?>">CHALLENGES</a>
        <a  class = "nav-link" href="../Mappa/index.php?username=<?php echo $username ?>">MAP</a>
        <a  class = "nav-link" href="../Sponsor/index.php?username=<?php echo $username ?>">SPONSORS</a>
        <img  class = "profile_picture" src=<?php echo $pic; ?>>
        <button id="settings-btn" class="nav-button">SETTINGS</button>
        <script type="text/javascript">
        document.getElementById("settings-btn"). onclick = function () {
            var url_string = window.location.href;
            var url = new URL(url_string);
            var username = url.searchParams.get("username"); 
            location.href = "../Settings/index.php?username="+username;
        };
        </script>
        <button id="logout-btn" class="nav-button" >LOG OUT</button>
        <script type="text/javascript">
        document. getElementById("logout-btn"). onclick = function () {
        location. href = "../Login/login.html";
        };
        </script>
        </div>

    </body>
</html>