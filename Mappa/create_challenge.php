<?php
  //overflow:scroll;
    $dbconn = pg_connect("host=localhost port=5432 dbname=PickItUp
                user=postgres password=postgres") 
                or die('Could not connect: ' . pg_last_error());
    $username = $_GET['username'];
    $coord =$_GET['coord'];
    $radius = $_GET['radius'];
    $luogo = $coord . ':'. $radius;
    $query = "insert into challenges (creator,luogo,nPartecipanti) values ($1,$2,1)";
    $result = pg_query_params($dbconn, $query, array($username,$luogo));
    if($result){
      header('location: ../Challenges/index.php?username=' . $username );
    }   
?>
