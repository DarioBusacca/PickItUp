<?php
  //overflow:scroll;
    $dbconn = pg_connect("host=localhost port=5432 dbname=PickItUp
                user=postgres password=postgres") 
                or die('Could not connect: ' . pg_last_error());
    $username = $_GET['username'];
    $coord =$_GET['coord'];
    $coord = str_replace('(', '', $coord);
    $coord = str_replace(')','',$coord);
    $arr=explode(',', $coord);
    $lat=$arr[0];
    $lng = $arr[1];
    $radius = $_GET['radius'];
    $luogo = $lat . ':'.$lng . ':'. $radius;
    $query = "insert into challenges (creator,luogo,nPartecipanti) values ($1,$2,1)";
    $result = pg_query_params($dbconn, $query, array($username,$luogo));
       
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PickItUp | Crea Challenge</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Square+Peg&family=Tapestry&display=swap" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="./style.css">
</head>
<body>
  <div class="create_challenge">
  <font size="30px">PickItUp</font>
  <form flex="1" action="" class="form-create_challenge" method="POST"
  name="myForm">
    <input type="text"   name="description" placeholder="Add a description">
    <br>          
    <input type="submit" value="CREATE CHALLENGE" name="create-button">
  </form>
</div>

</body>
</html>
<?php
if ((isset($_POST['create-button']))) {
  
   $descr =$_POST['description']; 
   $query = "select challenge_id from challenges where luogo=$1";
   $result = pg_query_params($dbconn,$query,array($luogo));
   $line=pg_fetch_array($result,null,PGSQL_ASSOC);
   $challenge_id=$line['challenge_id'];
   $query = "insert into challenge_room (challenge_id,creator,description) values ($1,$2,$3)";
   $result=pg_query_params($dbconn,$query,array($challenge_id,$username,$descr));
   $query = "select room_id from challenge_room where challenge_id=$1";
   $result=pg_query_params($dbconn,$query,array($challenge_id));
   $line = pg_fetch_array($result,null,PGSQL_ASSOC);
   $room_id=$line['room_id'];
   $query = "insert into room_member(username,room_id) values ($1,$2)";
   $result = pg_query_params($dbconn,$query,array($username,$room_id));
   if($result){
        header('location: ../Challenge/index.php?username=' . $username );
      } 
 }
?>