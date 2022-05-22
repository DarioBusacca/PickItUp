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
	<script type="text/javascript" src="slideshow.js"></script>


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
					order by c.times";
			
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
						$profile = $line['creator'];
						$profile_pic = $line['picture'];
						$profile_pic = substr($profile_pic, 3);
						$desc = $line['description'];
						$id = $line['challenge_id'];
						$info = $line['luogo'];
						$nPart = $line['npartecipanti'];

						echo '<div class = "post">';
							echo '<div class = post_banner>';
								echo '<img src = "'.$profile_pic.'" class = "post-profile_picture">';
								echo '<div class="post_banner-username">'.$profile.'</div>';
							echo '</div>';

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
					
					$prof_pic = substr($profile_pic, 3);
					
					$images = glob($media."/*.jpg");

					echo '<div class = "post_banner">';
					echo '<img src = "'.$prof_pic.'" class = "post-profile_picture">';
					echo '<div class="post_banner-username">'.$profile.'</div>';
					echo '</div>';
				
				//SLIDESHOW CONTAINER
					echo '<div class = "slideshow-container">';
					
					foreach($images as $image){
						echo '<div class = "mySlides fade">';
						echo '<img src ="'.$image.'" style = "width:100%">';
						echo '</div>';
					}

					echo '<a class ="prev" onclick = "plusSlides(-1)">&#10094;</a>';
					echo '<a class ="next" onclick = "plusSlides(+1)">&#10095;</a>';

					echo '</div>'; //END SLIDESHOW

					//dots/circles
					echo '<div style = "text-align: center">';
					for ($j = 1; $j <= count($images); $j++){
						print_r($j);
						echo '<span class = "dot" onclick = "currentSlide('.$j.')"></span>';
					}
					echo '</div>';
					
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
            echo ('<div class="titolo-sezione">AWARDS&nbsp;&&nbsp;OFFERS</div><br>');
            $query = "select  prezzo, nome, premio, logo, quantita
						from premi join aziende on codice = azienda_id
						order by premio_id";
			
			$result1 = pgquery($dbconn, $query);
			while($line = pg_fetch_array($result1, null, PGSQL_ASSOC)){

			//NON FUNZIONA QUESTA PARTE 
				$prezzo = $line['prezzo'];
				$azienda = $line['nome'];
				$premio = $line['premio'];
				$quantita = $line['quantita'];
				$logo = $line['logo'];
				$logo_img = substr($logo, 3);

				echo '<div class = "awards_item">
						<img class = "post-profile_picture" src ="' .$logo_img.'">
						<div class = "post_text">'.$premio.'</div>
						<div class = "award_price">'.$prezzo.'</div>';
						

			}
            echo '</div>';
    //FINE PREMI E OFFERTE
        ?> 
       </div>
    </main>
</body>
</html>