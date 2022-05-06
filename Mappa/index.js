function initMap() {
    const myLatlng = { lat: 41.9028, lng: 12.4964 };
    const map = new google.maps.Map(document.getElementById("map"), {
      zoom: 11,
      center: myLatlng,
      mapTypeId: 'terrain'
    });
    map.addListener('click', function(e) {
      const challenge_zone = new google.maps.Circle({
        strokeColor: "red",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "darkred",
        fillOpacity: 0.35,
        map:map,
        radius: 500,
        editable : true,
        draggable : true
      });
        var lat = e.latLng.lat();
        var lng = e.latLng.lng();
        var p=new google.maps.LatLng(lat, lng);
        challenge_zone.setCenter(p);
  
        challenge_zone.addListener('dblclick',() =>{
          challenge_zone.setMap(null);
        });
      
      
        
  
      challenge_zone.addListener('click', () => {
         const contentString ='<div id="create_challenge">' +
        "Create Challenge here"+
        "</div>";
        const infowindow = new google.maps.InfoWindow({
        content: contentString
        });
        const marker= new google.maps.Marker({
          position:p,
          map:map,
        });
        infowindow.open({
          anchor: marker,
          position:p,
          map:map,
          shouldFocus: false,
        });
       
        infowindow.addListener('closeclick', ()=>{
          // Handle focus manually.
        });
        const create=document.getElementById("create_challenge");
          google.maps.event.addDomListener(create, "click", () => {
            var url_string = window.location.href;
            var url = new URL(url_string);
            var username = url.searchParams.get("username"); 
            location.href = "create_challenge.php?username="+username+"&coord="+p;
          });
      
      });
      
    });
  
    
  }
   
  window.initMap = initMap;