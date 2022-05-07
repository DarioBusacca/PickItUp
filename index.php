<?php

    $dbconn = pg_connect("host=localhost port=5432 dbname=PickItUp
                user=postgres password=postgres") 
                or die('Could not connect: ' . pg_last_error());
    $username = $_GET['username'];            
    $query = "select picture from user_profile where username = $1";
    $result = pg_query_params($dbconn,$query,array($username));
    $line=pg_fetch_array($result,null,PGSQL_ASSOC);
    $pic= $line['picture'];
    $userpic_src=substr($pic, 3);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>PickItUp | HomePage</title>
    <link rel="stylesheet" href="style.css"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Square+Peg&family=Tapestry&display=swap" rel="stylesheet">
</head>

<body>
<!--BANNER-->
	<div class="banner">
		<span class="logo">PICKITUP</span>
		<form class="searchbar" name="searchbar" method="POST" action="search.php">
			<input type="text" name="search" placeholder="Search">
			<input type="submit" class="search-btn" value="SEARCH">
		</form>
		<a  class = "nav-link" href="Sfide/index.php">CHALLENGES</a>
		<a  class = "nav-link" href="Mappa/index.php?username=<?php echo $username?>">MAP</a>
		<a  class = "nav-link" href="Sponsor/index.php">SPONSORS</a>
		<img  id = "profile_picture" src=<?php echo $userpic_src; ?>>
		<button id="settings-btn" class="nav-button">SETTINGS</button>
		<script type="text/javascript">
			document.getElementById("settings-btn"). onclick = function () {
				var url_string = window.location.href;
				var url = new URL(url_string);
				var username = url.searchParams.get("username"); 
				location.href = "Settings/index.php?username="+username;
			};
		</script>
		<button id="logout-btn" class="nav-button" >LOG OUT</button>
			<script type="text/javascript">
			document. getElementById("logout-btn"). onclick = function () {
			location. href = "Login/login.html";
			};
			</script>
	</div>
	<!--FINE BANNER-->


	<div class = "site-action" style="display:flex;">
	<!--LEADERBOARD-->
			<?php
        	
            echo ('<div  class="leaderboard" >' );
            echo ('<div class="titolo-sezione">LEADERBOARD |&nbsp|&nbsp|&nbsp|&nbspPOINTS</div><br>');
            $query="
            (select p.profile_id,u.picture,p.nPunti as punti
            from points as p join User_profile as u on p.profile_id=u.username
            
			limit 9)
 			UNION
			( select p1.profile_id,u1.picture,p1.nPunti as punti
            from points as p1 join User_profile as u1 on p1.profile_id=u1.username
            where p1.profile_id=$1)
			order by punti desc;
            ";
            $result=pg_query_params($dbconn,$query,array($username));
            $pos = 1;
            while($line=pg_fetch_array($result, null, PGSQL_ASSOC)){
            	if($pos == 10)
            		break;
            	$profile_id=$line['profile_id'];
            	$points = $line['punti'];
            	$pic= $line['picture'];
            	$pic_src=substr($pic, 3);
            	echo '<div class="leaderboard-element">';
            	echo '<div  class= "position">'. $pos. '°</div>';
            	echo '<div  class= "user">'. $profile_id . '</div>';
            	echo '<img   src ="'.$pic_src . '" id="profile_picture">';
            	echo '<div class = "points">' . $points .'</div>';
          		echo ' </div><br>';
            	$pos += 1;
            }
            
            if($pos == 10){
            	$points=$line['nPunti'];
            	$query_pos="select count(*) as pos
            	from points 
            	where profile_id != $1 and nPunti > $2";
            	$result_pos = pg_query_params($dbconn,$query_pos,array($username,$points));
            	$line=pg_fetch_array($result_pos,null,PGSQL_ASSOC);
            	$user_pos=$line['pos'];
            	echo '<div style="display:flex; class="leaderboard-element">';
            	echo '<div flex = "1" class= "position">'. $user_pos. '</div>';
            	echo '<div flex = "1" class= "user">'. $username . '</div>';
            	echo '<img  flex = "2" src ="'.$userpic_src . '" id="profile_picture">';
            	echo '<div flex="0.5" >' . $points .'</div>';
            	echo '</div>';
            	
            }
            echo '</div>';

    //FINE LEADERBOARD


    //TIMELINE
            echo '<div class="timeline" >';
             echo ('<div class="titolo-sezione">TIMELINE</div><br>');
             $q1="select * from posts";
             $q2="select * from aziende";
            echo '</div>';
    //FINE TIMELINE



    //PREMI E OFFERTE
            echo '<div class="awards">';
            echo ('<div class="titolo-sezione">AWARDS&nbsp;&&nbsp;OFFERS</div><br>');
            $query = "select * from awards";


            echo '</div>';
    //FINE PREMI E OFFERTE
        ?> 
	</div>
</body>
</html>