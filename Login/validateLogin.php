<?php
if (!(isset($_POST['login-button']))) {
    header("Location: /");
}
else {
    $dbconn = pg_connect("host=localhost port=5432 dbname=PickItUp
                user=postgres password=S.apienza2022") 
                or die('Could not connect: ' . pg_last_error());
}
?>
<!DOCTYPE html>
<html>
    <head></head>
    <body>
        <?php
            if ($dbconn) {
                $username = $_POST['username'];
                $q1 = "select * from user_profile where username= $1";
                $result = pg_query_params($dbconn, $q1, array($username));
                if (!($line=pg_fetch_array($result, null, PGSQL_ASSOC))) {
                    echo "<h1>Sorry, you are not a registered user</h1>
                        <a href=../Registrazione/register.html> 
                            Click here to register
                        </a>";
                }
                else {
                    $password = md5($_POST['password']);
                    $q2 = "select * from user_profile where username = $1 and password = $2";
                    $result = pg_query_params($dbconn, $q2, array($username,$password));
                    if (!($line=pg_fetch_array($result, null, PGSQL_ASSOC))) {
                        echo "<h1> The password is erroneous</h1>
                            <a href=login.html> Click here to login </a>";
                    }
                    else {
                        header('location: ../index.php?username=' . $username );
                    }
                }
            }
        ?> 
    </body>
</html>