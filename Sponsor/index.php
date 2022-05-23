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
    <!--------BANNER--------->
    <div class="banner">
    	<a class="logo" href = "../index.php?username=<?php echo $username?>" style="text-decoration: none">PICKITUP</a>
		<form class="searchbar" name="searchbar" method="POST" action="search.php">
			<input type="text" name="search" placeholder="Search">
			<input type="submit" class="search-btn" value="SEARCH">
		</form>
        
        <a  class = "nav-link" href="../Challenge/index.php?username=<?php echo $username ?>">CHALLENGES</a>
        <a  class = "nav-link" href="../Mappa/index.php?username=<?php echo $username ?>">MAP</a>
        <a  class = "nav-link" href="../Sponsor/index.php?username=<?php echo $username ?>">SPONSORS</a>
        
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
		<img style = "float: right" alt = "" class = "profile_picture" src=<?php echo $pic; ?>>
        </div>
    
    <!--SPONSOR TIMELINE-->
    <div class="sponsor_timeline">
    <?php

    echo '<div class="timeline" >';
             
            $q1 ="select nome,sito,description,media_location from aziende" ;
            
            $result = pg_query($dbconn, $q1);
            while($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
                
                    $azienda = $line['nome'];
                    $sito_href = $line['sito'];
                    $descr = $line['description'];
                    $media = $line['media_location'];
                    $logo = $media .'/logo.jpeg';

                echo '<div class = "post">';
                    
                    
                    $images = glob($media."/ad/*.jpg");

                    echo '<div class = "post_banner">';
                    echo '<img src = "'.$logo.'" class = "profile_picture">';
                    echo '<div class="post_banner-username">'.$azienda.'</div>';
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
                    echo "<br>$descr";
                    echo '</div>';
                
                echo '</div>';
                    
            }
        echo '</div>';
        ?>
    </div>


    <!----FINE TIMELINE--->

    </body>
</html>