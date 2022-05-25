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
    if(isset($_GET['err'])){
		$err = $_GET['err'];
		$script='<script>
		alert("'.$err.'");
		window.location.href = "./index.php?username='.$username.'";
		</script>';
		echo $script;
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>PickItUp | HomePage</title>

    <link rel="stylesheet" href="style.css"/>
	<script type="text/javascript" src="slideshow.js"></script>
	<link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.5/css/unicons.css">
	<link rel="stylesheet" type="text/css" href="./Challenge/challenge-style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Square+Peg&family=Tapestry&display=swap" rel="stylesheet">
</head>

<body>
<!--BANNER-->
	<div class="banner">
    	<a class="logo" href = "../index.php?username=<?php echo $username?>" style="text-decoration: none">PICKITUP</a>
		<form class="searchbar" name="searchbar" method="POST" action="search.php">
			<input type="text" class="search" name="search" placeholder="Search">
			<i style="color:white;"class="uil uil-search"></i>
			
		</form>
		<div class = "button_container">
			<a  class = "nav-link" href="Challenge/index.php?username=<?php echo $username?>">CHALLENGES</a>
			<a  class = "nav-link" href="Mappa/index.php?username=<?php echo $username?>">MAP</a>
			<a  class = "nav-link" href="Sponsor/index.php?username=<?php echo $username?>">SPONSORS</a>
		</div>
		
		<!--Menu impostazioni-->
		<div id = "hormenu">
			<ul>
				<li>
					<a href = "#"> Settings </a>
					<ul>
						<li> <a href = "./Login/login.html"> Log Out </a></li> 
						<li> <a href = "#"> MyAccount </li> </a>
					</ul>
				</li>
			</ul>
		</div>
		<img style = "float: right" alt = "" class = "profile_picture" src=<?php echo $userpic_src; ?>>

			
	</div>
	<!--FINE BANNER-->

	
	<div class = "site-action" style="display:flex;">
	<!--LEADERBOARD-->
		<?php
        	
            echo ('<div  class="leaderboard" >' );
            echo ('<div class="titolo-sezione">LEADERBOARD</div>');
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
          		echo ' </div>';
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
            	echo '<img  flex = "2" src ="'.$userpic_src . '" class="profile_picture">';
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
            
			$q2 = "select challenge_id, luogo, npartecipanti, creator, picture, description
			 		from challenges c join user_profile on creator = username
					where challenge_id not in
			 		(select challenge_id
			 		from partecipa
			 		where profile_id = $1)
					order by c.times";
			
			$result1 = pg_query($dbconn, $q1);
			$result2 = pg_query_params($dbconn, $q2,array($username));
			$rand = rand(0, 10);
			$i = 0;
			while($line = pg_fetch_array($result1, null, PGSQL_ASSOC)) {
				if($i == 10){
					$i=0;
				}
				else if($i == $rand){
					$line = pg_fetch_array($result2, null, PGSQL_ASSOC);
					if($line){
						$profile = $line['creator'];
						$profile_pic = $line['picture'];
						$profile_pic = substr($profile_pic, 3);
						$descr = $line['description'];
						$id = $line['challenge_id'];
						$luogo = $line['luogo'];
						$nPart = $line['npartecipanti'];

						echo '<div class = "post">';
							echo '<div class = post_banner>';
								echo '<img src = "'.$profile_pic.'" class = "post-profile_picture">';
								echo '<div class="post_banner-username">'.$profile.'</div>';
							echo '</div>';

							echo '<div class="challenge">';
          echo '<div class="challenge_text">'.$descr.'</div>';
          echo '<div class="challenge_buttons">';
          echo '<a  style="text-decoration:none;" class = "challenge-button" href="./Challenge/partecipa_challenge.php?username='.$username.'&id='.$id.'&l='.$luogo.'" >PARTECIPA </a>';
          
          echo '<a  style="text-decoration:none;" class = "challenge-button" href="./Mappa/index.php?username='.$username.'&id='.$id.'&l='.$luogo.'" >CHALLENGE LOCATION </a>';
          echo '</div>';
          echo '</div>';
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
					
					$prof_pic = substr($profile_pic, 3);
					
					$images = glob($media."/*.jpg");

					echo '<div class = "post_banner">';
					echo '<img src = "'.$prof_pic.'" class = "post-profile_picture">';
					echo '<div class="post_banner-username">'.$profile.'</div>';
					echo '</div>';
				
				//SLIDESHOW CONTAINER
				echo '<div class = "slideshow-container" >';


				foreach($images as $image){
					
						echo '<div class = "mySlides_'.$post_id.' fade">';
						echo '<img src ="'.$image.'" style = "width:100%">';
						echo '</div>';
				}
				if(count($images) > 1){
					echo '<a id ="prev '.$post_id.'" >&#10094;</a>';
					echo '<a id ="next '.$post_id.'" >&#10095;</a>';
				}
				echo '</div>'; //END SLIDESHOW

				echo '<div style = "text-align: center">';
				
				$script = '<script>
				document.getElementById("prev '.$post_id.'"). onclick= function() {
					plusSlides(-1,"mySlides_'.$post_id.'");
				}
				</script>';
				echo $script;
				$script='<script>
				document.getElementById("next '.$post_id.'"). onclick= function() {
					plusSlides(+1,"mySlides_'.$post_id.'");
				}
				</script>';
				echo $script;
				echo '</div>';

				echo '<script>';
				$script='initSlideShow("mySlides_'.$post_id.'");';
				echo $script;
				echo '</script>';
				
				echo '<div class = "post_text">';
				echo "<br>$text";
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
		echo ('<div class="titolo-sezione">AWARDS&nbsp;&&nbsp;OFFERS</div>');
		$query = "select  premio_id,prezzo, nome, premio,p.media_location as media1,a.media_location as media2
					from premi p join aziende a on codice = azienda_id
					where quantita > 0 and premio_id not in
					(select premio
					from premio_acquisito
					where profile_id=$1)
					order by premio_id";
		
		$result1 = pg_query_params($dbconn, $query,array($username));
		while($line = pg_fetch_array($result1, null, PGSQL_ASSOC)){

			$premio_id=$line['premio_id'];
			$prezzo = $line['prezzo'];
			$azienda = $line['nome'];
			$premio = $line['premio'];
		
			$media_premio = $line['media1'];
			$media_az = $line['media2'];
			$logo=substr($media_az, 3) .'/logo.jpg';
			$premio_src=glob(substr($media_premio,3).'/*.jpg');
			$premio_src=$premio_src[0];

			echo '<div class = "award_item">
					<img style="border-style:solid;border-color:green;" class = "profile_picture" src ="'.$logo.'">
					<img style="position:relative;width:100%;margin-top:0;" src="'.$premio_src.'">
					<div class = "post_text">'.$premio.'</div>
					<a style="text-decoration:none" href="./acquista_premio.php?username='.$username.'&id='.$premio_id.'&p='.$prezzo.'"class = "awards_price">'.$prezzo.'<i  class="uil uil-coins"></i> </a>
				</div>';

				
			
			
		}
		echo '</div>';
		//FINE PREMI E OFFERTE
        ?> 
       </div>
    </main>
</body>
</html>