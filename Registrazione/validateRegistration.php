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
    <head>
        <link rel="stylesheet" type="text/css" href="reg-style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Square+Peg&family=Tapestry&display=swap" rel="stylesheet">
    </head>
    <body>
        <?php
       
            if ($dbconn) {
                $username = $_POST['username'];
                $q1="select * from user_profile where username= $1";
                $result=pg_query_params($dbconn, $q1, array($username));
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
                    $email = $_POST['email'];
                    $gender = $_POST['sesso'];

                    $q2 = "insert into user_profile(username, nome, cognome, email, password, gender, birthday) values ($1,$2,$3,$4,$5,$6,$7);";
                    $data = pg_query_params($dbconn, $q2,
                        array($username, $nome, $cognome, $email, $password, $gender, $birthday));
                    if ($data) {
                        echo "<h1>Registration completed<br/></h1>"
                        echo '<div class="signup">
                        <font size="30px" >PickItUp</font>
                        <input type="submit" value="UPLOAD PROFILE PICTURE" id="upload" >
                        <script type="text/javascript">
                        document. getElementById("upload"). onclick = function () {
                        location. href = "profile_pic.html?username=$username";
                        };
                        </script>
                        <input type="submit" value="SKIP" id="skip" >
                        <script type="text/javascript">
                        document. getElementById("skip"). onclick = function () {
                        location. href = "../index.php?username=$username";
                        };
                        </script>
                        </div>'
                    }
                }
            }
        ?> 
    </body>
</html>