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
    	<a class="logo" href = "../index.php?username=<?php echo $username?>" style="text-decoration: none">PICKITUP</a>
		<form class="searchbar" name="searchbar" method="POST" action="search.php">
			<input type="text" name="search" placeholder="Search">
			<input type="submit" class="search-btn" value="SEARCH">
		</form>
		<a  class = "nav-link" href="Challenge/index.php?username=<?php echo $username?>">CHALLENGES</a>
		<a  class = "nav-link" href="Mappa/index.php?username=<?php echo $username?>">MAP</a>
		<a  class = "nav-link" href="Sponsor/index.php?username=<?php echo $username?>">SPONSORS</a>
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
            echo ('<div class="titolo-sezione">LEADERBOARD</div><br>');
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
            	echo '<img   src ="'.$pic_src . '" id="profile_picture">';
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
             
			 $q1 = "select post_id, profile_id, written_text, picture, media_location
			 		from post join user_profile on profile_id = username
					order by post.times";
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
					$profile_pic = $line['picture'];
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
					$prof_pic = substr($profile_pic, 3);
					$profile_pic_src = $arr[0].'/'.$arr[1].'/'.$profile_pic_src;
					$media=$media . $post_id;
					$media_array = scandir($media);
					
					echo '<div class = "post_banner">';
					echo '<img src = "'.$prof_pic.'" id = "post-profile_picture">';
					echo '<div class="post_banner-username">'.$profile.'</div>';
					echo '</div>';
					echo '<img src = "' . $profile_pic_src . '" class = "post-image" >';
					
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
            $query = "select * from premi";


            echo '</div>';
    //FINE PREMI E OFFERTE
        ?> 
       </div>
    </main>
</body>
</html>