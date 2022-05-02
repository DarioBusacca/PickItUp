
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>PickItUp | HomePage</title>
	<link rel="stylesheet" href="../css/bootstrap.min.css"/>
    <link rel="stylesheet" href="style.css"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Square+Peg&family=Tapestry&display=swap" rel="stylesheet">
    <script src="../js/bootstrap.min.js"></script>

</head>
<style type="text/css">
	.banner{
		background-color:#35682d;
		width: 100%;
		height: 75px;
		display: flex;
		flex-direction: row;
		font-family:Square peg;
		font-size: 25px;
		color: white;
		font-weight: bolder;
		
	}
	.logo {
		padding-left: 16px;
		padding-right: 50px;
		padding-top: 10px;
		padding-bottom: 10px;
		margin-right: 20px;
		border-color: black;
	}
	.searchbar {
		margin-right: 230px;
		border-style: solid;
		border: none;
		margin-top: 10px;




	}
	.nav-link {
		margin-right: 20px;
		width: auto;
		height: auto;
		color: white;
		background-color: #35682d;
		transition: background-color 0.3s ,color 1s;
	}
	.nav-link:hover {
		background-color: white;
		color: #35682d;
		border-radius:  4px;
	}
	.nav-button {
		width: auto;
		height: auto;
		padding-top: 10px;
		padding-bottom: 10px;
		padding-left: 16px;
		padding-right: 15px;
		color:white;
		background-color: green;
		border-radius: 20px;
		margin-right: 10px;
		margin-left: 30px;
		margin-top: auto;
		margin-bottom: auto;
		border-color: green;
		transition: opacity 0.6s, background-color 0.6s,color 0.6s;
	}
	.nav-button:hover {
		background-color: white;
		color: green;
	}
	.nav-button:active {
		opacity: 0.7;
		background-color: white;
		color: green;
	}
</style>
<body>
	<div class="banner">
		<span class="logo">PICKITUP</span>
		<form class="searchbar" name="searchbar" method="POST" action="">
			<input type="text" name="search" placeholder="Search">
		</form>
		<a  class = "nav-link" href="Sfide/index.php">CHALLENGES</a>
		<a  class = "nav-link" href="Mappa/index.php">MAP</a>
		<a  class = "nav-link" href="Sponsor/index.php">SPONSORS</a>
		<img  id = "profile_picture" >
		<button id="settings-btn" class="nav-button">SETTINGS</button>
		<script type="text/javascript">
			document.getElementById("settings-btn"). onclick = function () {
				location.href = "Settings/index.php";
			};
		</script>
		<button id="logout-btn" class="nav-button" >LOG OUT</button>
			<script type="text/javascript">
			document. getElementById("logout-btn"). onclick = function () {
			location. href = "Login/login.html";
			};
			</script>
			<?php
        
            echo ('<div  class="leaderboard">' );
            $query="select profile_id,picture,nPunti
            from points as p join User_profile as u on p.profile_id=u.username
            order by nPunti desc
            limit 9
            union 
            select profile_id,picture,nPunti
            from points as p join User_profile as u on p.profile_id=u.username
            where profile_id=$1;
            ";
            $result=pg_query($dbconn,$query,array($username));
            $pos = 1;
            while($line=pg_fetch_array($result, null, PGSQL_ASSOC)){
            	if($pos == 10)
            		break;




            	$pos += 1;
            }
            
            

           
           
            if($pos == 10){
            	$user_points=$line['nPunti'];
            	$query_pos="select count(*) as pos
            	from points 
            	where profile_id != $1 and nPunti > $2";
            	$result_pos = pg_query_params($dbconn,$query_pos,array($username,$user_points));
            	$line=pg_fetch_array($result_pos,null,PGSQL_ASSOC);
            	$user_pos=$line['pos'];
            }
            

        ?> 
		


		


	</div>

</body>
</html>