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
    $query = "select luogo from challenges";
    $result = pg_query($dbconn,$query);
    $other_challenges=array();
    while($line =pg_fetch_array($result,null,PGSQL_ASSOC)){
      array_push($other_challenges, $line['luogo']);
    }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>PickItUp | Mappa</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Square+Peg&family=Tapestry&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.5/css/unicons.css">
  </head>
  <script type="text/javascript">
  var url_string = window.location.href;
  var url = new URL(url_string);
if (url.searchParams.has("l") == true)  
{

  function initMap() {
      const id=url.searchParams.get('id');
      document.querySelector(".titolo-sezione").textContent = "CHALLENGE N°"+id;
      const luogo=url.searchParams.get('l');
      const arr = luogo.split(':');
      let lat =arr[0] ;
      let lng = arr[1];
      let rad =parseInt(arr[2]);
      const myLatlng = new google.maps.LatLng(lat,lng);
      const map = new google.maps.Map(document.getElementById("map"), {
        zoom: 16,
        center: myLatlng,
        mapTypeId: 'terrain',
      });
      const challenge_zone = new google.maps.Circle({
          strokeColor: "red",
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: "blue",
          fillOpacity: 0.35,
          map:map,
          radius: rad,
          center: myLatlng,
          editable : false,
          draggable : false,
        });
      var username = url.searchParams.get("username"); 
        challenge_zone.addListener('click', () => {
           const contentString ='<div id="join_challenge">' + '<a style="color:white" href="../Challenge/partecipa_challenge.php?username='+username +
          '&id='+id+ '">'+
          "Join Challenge here"+'</a>'+
          "</div>";
        
          const infowindow = new google.maps.InfoWindow({
            content: contentString
          });
          const marker= new google.maps.Marker({
            position:myLatlng,
            map:map,
          });
          infowindow.open({
            anchor: marker,
            position:myLatlng,
            map:map,
            shouldFocus: false,
          });
         
          infowindow.addListener('closeclick', ()=>{
            // Handle focus manually.
          });
          
        });
    
      
    }

}else{
  function initMap() {
     document.querySelector(".titolo-sezione").textContent = "SELECT AREA TO PICKIPUP!";
    const myLatlng = { lat: 41.9028, lng: 12.4964 };
    const map = new google.maps.Map(document.getElementById("map"), {
      zoom: 11,
      center: myLatlng,
      mapTypeId: 'terrain'
    });

    const other_challenges = <?php echo json_encode($other_challenges);?>;

    for(let i=0;i < other_challenges.length;i++){
      var arr=other_challenges[i].split(':');
      var lat=arr[0];
      var lng=arr[1];
      var rad=parseInt(arr[2]);
      const c=new google.maps.Circle({
        strokeColor: "red",
        strokeOpacity: 1,
        strokeWeight: 2,
        fillColor: "blue",
        fillOpacity: 0.35,
        editable : false,
        draggable:false
      });
      
      c.setMap(map);
      c.setCenter(new google.maps.LatLng(lat,lng));
      c.setRadius(rad);
      
    }

    map.setClickableIcons(false);

    var radius=200;

    const challenge_zone = new google.maps.Circle({
        strokeColor: "red",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "darkred",
        fillOpacity: 0.35,
        map:map,
        radius: radius,
        editable : true,
        draggable:false,
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
        
        
        challenge_zone.setEditable(true);
        challenge_zone.setMap(map);
        challenge_zone.setCenter(p); 
        challenge_zone.addListener('click',() =>  {
          challenge_zone.setEditable(false);
          const contentString ='<div id="create_challenge">' + '<a style="color:white" href="create_challenge.php?username='+username +
        '&coord='+p+'&radius='+radius+ '">'+
        "Create Challenge here"+'</a>'+
        "</div>";
        infowindow.setContent(contentString);
          
          marker.setMap(map);
          marker.setPosition(p);
          infowindow.open({
            anchor: marker,
            map:map,
            shouldFocus: false,
          }); 
        });
        
    });
    challenge_zone.addListener('radius_changed',() =>{
            radius=challenge_zone.getRadius();
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
  #create_challenge,#join_challenge {
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
    	<a class="logo" href = "../index.php?username=<?php echo $username?>" style="text-decoration: none">PICKITUP</a>
		<form class="searchbar" name="searchbar" method="POST" action="search.php">
			<input type="text" name="search" placeholder="Search">
			<i style="color:white;"class="uil uil-search"></i>
		</form>
    
      <a  class = "nav-link" href="../Challenge/index.php?username=<?php echo $username ?>">CHALLENGES</a>
      <a  class = "nav-link" href="index.php?username=<?php echo $username ?>">MAP</a>
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


    <div class="titolo-sezione"></div>
   


    <div id="map"></div>

    <script async
    src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap">
    </script>
</body>
</html>
