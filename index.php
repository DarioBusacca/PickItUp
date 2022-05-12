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
            (select username, picture, points as punti
			from user_profile as u           
			limit 9)

 			UNION

			(select username, picture, points as punti
            from user_profile as u1
            where u1.username=$1)
			order by points desc;";
			
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
             echo ('<div class="titolo-sezione">TIMELINE</div><br>');
			 $q1 = "select post_id, profile_id, written_text, media_location
			 		from post
					order by times";
             $q2 = "select challenge_id, luogo, npartecipanti
			 		from challenges
					order by times";
			
					$result1 = pg_query($dbconn, $q1);
			$result2 = pg_query($dbconn, $q2);
			$rand = rand(0, 100);
			$i = 0;
			while($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
				if($i == $rand){
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
					}
				} else {
					$profile = $line['profile_id'];
					$text = $line['written_text'];
					$media = $line['media_location'];
					$post_id = $line['post_id'];

					echo '<div class = "post">';
					$arr = explode("/", $media);
					$profile_src = $arr[0].'/'.$arr[1];
					$profile_src = scandir($profile_src);
					for($i = 0; $i < count($profile_src); $i++){
						if(!is_numeric($profile_src)){
							$profile_pic_src = $profile_src[$i];
						}
					}
					
					$profile_pic_src = $arr[0].'/'.arr[1].'/'.$profile_pic_src;
					$media_array = scandir($media);
					$src = $media.'/'.$media_array[2];
					echo '<div class = "post_banner">';
					echo '<font size = "45>' . $profile . '&nbsp;&nbsp;</font>';
					echo '<img src = "' . $profile_pic_src . '" id = "profile_picture">';
					echo '</div>';
					
					echo '<div class = "post_text">';
					echo "<br>$text";
					echo '</div>';

					echo '<div class = "post_media"' . $post_id . '">';
					echo '<img src ="' . $src . 'class = "post_img">';
					echo '<div class = "scroll_image >' . $post_id . '" > > </div>';

					echo '<script type="text/javascript">
							document.getElementsByClass("scroll_image > '.$post_id.'"). onclick = function () {
							const media_array=<?php echo json_encode($media_array); ?>;
							const div=document.getElementsByClass("post_media '.$post_id .'");
							for(let i=0;i < media_array.length;i++){
								if(div.query_selector(".post_image").src == media_array[i]){
									div.innerHTML = "<div class ="scroll_image < '.$post_id.'" > < </div>";
								}
							}
						};
						</script>';
					echo '<script type = "text/javascript">
							document.getElementsByClass("scroll_image <' . $post_id . '").onclick=functio(){

							};
						</script>';
					echo '</div>';
					echo '</div>';
					$rand = rand(0, 100);
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
</body>
</html>