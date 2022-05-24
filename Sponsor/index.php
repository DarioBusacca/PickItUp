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
        <script type="text/javascript" src="../slideshow.js"></script>
        <title> PickItUp | Sponsors </title>
        <link rel="stylesheet" type="text/css" href="../style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Square+Peg&family=Tapestry&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.5/css/unicons.css">

    </head>

    <body>
    <!--------BANNER--------->
    <div class="banner">
    	<a class="logo" href = "../index.php?username=<?php echo $username?>" style="text-decoration: none">PICKITUP</a>
		<form class="searchbar" name="searchbar" method="POST" action="search.php">
			<input type="text" name="search" placeholder="Search">
			<i style="color:white;"class="uil uil-search"></i>
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
                    $logo = $media .'/logo.jpg';

                echo '<div class = "post">';
                    $images = glob($media."/ad/*.jpg");

                    echo '<div class = "post_banner">';
                    echo '<img src = "'.$logo.'" class = "post-profile_picture">';
                    echo '<div class="post_banner-username">'.$azienda.'</div>';
                    echo '</div>';
                
                //SLIDESHOW CONTAINER
                echo '<div class = "slideshow-container" >';


                foreach($images as $image){
                    
                        echo '<div class = "mySlides_'.$azienda.' fade">';
                        echo '<img src ="'.$image.'" style = "width:100%">';
                        echo '</div>';
                }
                if(count($images) > 1){
                    echo '<a id ="prev '.$azienda.'" >&#10094;</a>';
                    echo '<a id ="next '.$azienda.'" >&#10095;</a>';
                }
                echo '</div>'; //END SLIDESHOW

                echo '<div style = "text-align: center">';
                
                $script = '<script>
                document.getElementById("prev '.$azienda.'"). onclick= function() {
                    plusSlides(-1,"mySlides_'.$azienda.'");
                }
                </script>';
                echo $script;
                $script='<script>
                document.getElementById("next '.$azienda.'"). onclick= function() {
                    plusSlides(+1,"mySlides_'.$azienda.'");
                }
                </script>';
                echo $script;
                echo '</div>';

                echo '<script>';
                $script='initSlideShow("mySlides_'.$azienda.'");';
                echo $script;
                echo '</script>';
                
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