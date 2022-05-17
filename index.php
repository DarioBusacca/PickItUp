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
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>PickItUp | HomePage</title>

    <link rel="stylesheet" href="./style.css"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Square+Peg&family=Tapestry&display=swap" rel="stylesheet">
</head>

<body>
<!--BANNER-->
	
	<div class="banner">
		<span class="logo">PICKITUP</span>
		<form class="searchbar" name="searchbar" method="POST" action="search.php">
			<input type="search" name="search" placeholder="Search">
			<i id="search_icon" class="uil uil-search" style="margin-right: 200px;"></i>
			
		</form>
		<a  class = "nav-link" href="Challenge/index.php?username=<?php echo $username?>">CHALLENGES</a>
		<a  class = "nav-link" href="Mappa/index.php?username=<?php echo $username?>">MAP</a>
		<a  class = "nav-link" href="Sponsor/index.php?username=<?php echo $username?>" id="last-link">SPONSORS</a>
		<img  class = "profile_picture" src=<?php echo $userpic_src; ?>>
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


	<main>
		<div class="container">
	<!--LEADERBOARD-->
		<?php
        	
            echo ('<div  class="leaderboard" >' );
            echo ('<div class="titolo-sezione">LEADERBOARD |&nbsp|&nbsp|&nbsp|&nbspPOINTS</div><br>');
            $query="
            (select username, picture, points as punti
			from user_profile as u           
			limit 9)

 			UNION

			(select username, picture, points as punti
            from user_profile as u1
            where u1.username=$1)
			order by punti desc;";
			
            $result=pg_query_params($dbconn,$query,array($username));
            $pos = 1;
            while($line=pg_fetch_array($result, null, PGSQL_ASSOC)){
            	if($pos == 10)
            		break;
            	$profile_id=$line['username'];
            	$points = $line['punti'];
            	$pic= $line['picture'];
            	$pic_src=substr($pic, 3);
            	echo '<div class="leaderboard-element">';
            	echo '<div  class= "position">'. $pos. '°</div>';
            	echo '<div  class= "user">'. $profile_id . '</div>';
            	echo '<img   src ="'.$pic_src . '" class="profile_picture">';
            	echo '<div class = "points">' . $points .'</div>';
          		echo ' </div><br>';
            	$pos += 1;
            }
            
            if($pos == 10){
            	$points=$line['punti'];
            	$query_pos="select count(*) as pos
            	from user_profile 
            	where username != $1 and points > $2";
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
             
			 $q1 = "select post_id, profile_id, written_text, media_location
			 		from post
					order by times";
             $q2 = "select challenge_id, luogo, npartecipanti
			 		from challenges
					order by times";
			
					$result1 = pg_query($dbconn, $q1);
			$result2 = pg_query($dbconn, $q2);
			$rand = rand(0, 10);
			$i = 0;
			while($line = pg_fetch_array($result1, null, PGSQL_ASSOC)) {
				if($i == 10){
					$i=0;
				}
				else if($i == $rand){
					$line = pg_fetch_array($result2, null, PGSQL_ASSOC);
					if($line){
						$id = $line['challenge_id'];
						$info = $line['luogo'];
						$nPart = $line['npartecipanti'];
						echo '<div class = "post">';
						echo '<form method = "GET" action = "Challenge/partecipa_challenge.php?username=' . $username . '$id=' . $id . '">';
						echo '<input type = "submit" class = "partecipa" name = "partecipa-btn" value = "PARTECIPA" />';
						echo '</form>';
						echo '</div>';
						$i -= $rand;
					}else
							continue;
				} else {
					$profile = $line['profile_id'];
					$text = $line['written_text'];
					$media = $line['media_location'];
					$post_id = $line['post_id'];

					echo '<div class = "post">';
					$arr = explode("/", $media);
					$profile_src = $arr[0].'/'.$arr[1];
					$profile_src = scandir($profile_src);
					for($j = 0; $j < count($profile_src); $j++){
						if(!is_numeric($profile_src)){
							$profile_pic_src = $profile_src[$j];
						}
					}
					
					$profile_pic_src = $arr[0].'/'.$arr[1].'/'.$profile_pic_src;
					$media=$media . $post_id;
					$media_array = scandir($media);
					
					echo '<div class = "post_banner">';
					echo '<font size = "45">' . $profile . '&nbsp;&nbsp;</font>';
					echo '<img src = "' . $profile_pic_src . '" class = "profile_picture">';
					echo '</div>';
					
					echo '<div class = "post_text">';
					echo "<br>$text";
					echo '</div>';

					echo '<div class = "post_media">';
					echo '<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
  						<div class="carousel-inner">';
  					for ($j=2; $j < count($media_array) ; $j++) {
  						$src = $media. '/' .$media_array[$j];
  						if($j == 2){
  							echo '<div   id ="post_img"class="carousel-item active">
     						 <img id="post_pic" class="d-block w-100" src="'. $src.'" >
    								</div>';
  						}else{
  							echo '<div  id="post_img" class="carousel-item ">
     						 <img    class="d-block w-100" src="'.$src.'" >
    								</div>';
  						}
  						
  					}
	  				echo'</div>
	  					<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
	   					 <span class="carousel-control-prev-icon" aria-hidden="true"></span>
	   						 <span class="sr-only">Previous</span>
	 					 </a>
	 						 <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
	   						 <span class="carousel-control-next-icon" aria-hidden="true"></span>
	    				<span class="sr-only">Next</span>
	  				</a>
					</div>';
					
					echo '</div>';
					echo '<div class="post_lower_banner"';
					echo '<a href="./like.php" style="cursor:pointer;"> <i  id="like-btn"class="uil uil-thumbs-up"></i></a>';
					echo '</div>';
					echo '</div>';
					$rand = rand(0, 10);
					$i += 1;
				}
			}
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
    </main>
</body>
</html>