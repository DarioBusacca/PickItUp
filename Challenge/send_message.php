<?php
if(isset($_POST['send-btn'])){
    $dbconn = pg_connect("host=localhost port=5432 dbname=PickItUp
                user=postgres password=postgres") 
                or die('Could not connect: ' . pg_last_error());
    $username=$_GET['username'];
    $challenge_id=$_GET['id'];
    $msg_to_send=$_POST['input_msg'];
    $query ="insert into messages(username,challenge_id,msg) values($1,$2,$3);";
    $result=pg_query_params($dbconn,$query,array($username,$challenge_id,$msg_to_send));
    if($result)
    header('./index.php?username='.$username);
}
?>