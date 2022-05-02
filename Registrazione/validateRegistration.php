<?php
if (!(isset($_POST['reg-button']))) {
    header("Location: /");
}
else {
    $dbconn = pg_connect("host=localhost port=5432 dbname=PickItUp
                user=postgres password=postgres") 
                or die('Could not connect: ' . pg_last_error());
}
?>
<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <?php
       
            if ($dbconn) {
                $email = $_POST['email'];
                $q1="select * from user_profile where email= $1";
                $result=pg_query_params($dbconn, $q1, array($email));
                if ($line=pg_fetch_array($result, null, PGSQL_ASSOC)) {
                    echo "<h1> Sorry, you are already a registered user</h1>
                        ";
                    echo "<a href=../Login/login.html> Click here to login </a>";
                }
                else {
                    $nome = $_POST['nome'];
                    $cognome = $_POST['cognome'];
                    $password = md5($_POST['password']);
                    
                    $birthday = $_POST['birthday'];
                    $username = $_POST['username'];
                    $gender = $_POST['sesso'];

                    $q2 = "insert into user_profile(username, nome, cognome, email, password, gender, birthday) values ($1,$2,$3,$4,$5,$6,$7);";
                    $data = pg_query_params($dbconn, $q2,
                        array($username, $nome, $cognome, $email, $password, $gender, $birthday));
                    if ($data) {
                        echo "<h1> Registration is completed. 
                            Start using the website <br/></h1>";
                        echo "<a href=../index.php?username=$username> Premi qui </a>
                            per inziare ad utilizzare il sito web";
                    }
                }
            }
        ?> 
    </body>
</html>