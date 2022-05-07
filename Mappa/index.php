<?php
  //overflow:scroll;
    $dbconn = pg_connect("host=localhost port=5432 dbname=PickItUp
                user=postgres password=S.apienza2022") 
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
    <title>PickItUp | Mappa</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

    <!--<link rel="stylesheet" type="text/css" href="./style.css" />-->
    <!--<script type="module" src="./index.js"></script>-->
    <link rel="stylesheet" type="text/css" href="../style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Square+Peg&family=Tapestry&display=swap" rel="stylesheet">
  </head>
  <script type="text/javascript">
  function initMap() {
    const myLatlng = { lat: 41.9028, lng: 12.4964 };
    const map = new google.maps.Map(document.getElementById("map"), {
      zoom: 11,
      center: myLatlng,
      mapTypeId: 'terrain'
    });
    //const other_challenges=array();
    map.setClickableIcons(false);
    var radius=300;
    const challenge_zone = new google.maps.Circle({
        strokeColor: "red",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "darkred",
        fillOpacity: 0.35,
        map:map,
        radius: radius,
        editable : true,
      }); 
    var url_string = window.location.href;
    var url = new URL(url_string);
    var username = url.searchParams.get("username"); 
    
    const infowindow = new google.maps.InfoWindow();
    const marker = new google.maps.Marker({
          map:map,
        });
    map.addListener('click', function(e) {
        challenge_zone.setMap(null);
        marker.setMap(null);
        infowindow.setMap(null);
        var lat = e.latLng.lat();
        var lng = e.latLng.lng();
        var p=new google.maps.LatLng(lat, lng);
        
        const contentString ='<div id="create_challenge">' + '<a style="color:white" href="create_challenge.php?username='+username +
        '&coord='+p+'&radius='+radius+ '">'+
        "Create Challenge here"+'</a>'+
        "</div>";
        infowindow.setContent(contentString);
        challenge_zone.setEditable(true);
        challenge_zone.setMap(map);
        challenge_zone.setCenter(p); 
        challenge_zone.addListener('click',() =>  {
          challenge_zone.setEditable(false);
          marker.setMap(map);
          marker.setPosition(p);
          infowindow.open({
            anchor: marker,
            map:map,
            shouldFocus: false,
          }); 
        });
        challenge_zone.addListener('radius_changed',() =>{
            radius=challenge_zone.getRadius();
        });
    });
  
   
    infowindow.addListener('closeclick', ()=>{
          // Handle focus manually.
    });
    challenge_zone.addListener('dblclick',() =>{
          challenge_zone.setMap(null);
          infowindow.setMap(null);
          marker.setMap(null);
      });
  }
   
  window.initMap = initMap;
</script>
  <style>
  #map {
    height: 100%;
  }
  html, body {
    height: 100%;
    margin: 0;
    padding: 0;
  }
  #create_challenge {
    padding-top: 10px;
    padding-bottom: 10px;
    padding-left: 16px;
    padding-right: 16px;
    background-color: green;
    color: white;
    width: 200px;
    height: 50px;
    cursor: pointer;
  }
</style>
  <body>
    <div class="banner">
    <div class="logo" id="logo">PICKITUP</div>
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
    <a  class = "nav-link" href="../Sfide/index.php?username=<?php echo $username ?>">CHALLENGES</a>
    <a  class = "nav-link" href="index.php?username=<?php echo $username ?>">MAP</a>
    <a  class = "nav-link" href="../Sponsor/index.php?username=<?php echo $username ?>">SPONSORS</a>
    <img  id = "profile_picture" src=<?php echo $pic; ?>>
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


    <div class="titolo-sezione">Select the area to PickItUp!</div>
   


    <div id="map"></div>

    <script async
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCplC2ePOmxiywnXvngIy6S2w3yALf93OE&callback=initMap">
    </script>
</body>
</html>