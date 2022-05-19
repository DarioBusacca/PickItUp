<?php
	if (!(isset($_POST['partecipa-btn']))) {
    header("Location: /");
}
else {
    $dbconn = pg_connect("host=localhost port=5432 dbname=PickItUp
                user=postgres password=postgres") 
                or die('Could not connect: ' . pg_last_error());
}
$username = $_GET['profile_id'];
$challenge_id = $_GET['id'];

$query = "insert into partecipa (profile_id,challenge_id) values ($1,$2)";
$result = pg_query_params($dbconn,$query,array($username,$challenge_id));

if($result){
	header('location: ./index.php?username=' . $username );
}

?>