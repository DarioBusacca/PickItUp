<?php

    $dbconn = pg_connect("host=localhost port=5432 dbname=PickItUp
                user=postgres password=postgres") 
                or die('Could not connect: ' . pg_last_error());
    $username = $_GET['username']; 
    $premio_id = $_GET['id'];
    $prezzo = $_GET['p'];
    $query="select points from user_profile where username=$1";
    $result = pg_query_params($dbconn,$query,array($username));
    $line=pg_fetch_array($result,null,PGSQL_ASSOC);
    if($line['points'] >= $prezzo ){          
        $query = "insert into premio_acquisito(profile_id,premio, prezzo)
        values($1,$2, $3)";
        $result = pg_query_params($dbconn,$query,array($username,$premio_id, $prezzo));
        if($result){
            header('Location:./index.php?username='.$username);
        }
    }else{
            header('Location:./index.php?username='.$username.'&err=not_enough_coins');
    }
    
?>