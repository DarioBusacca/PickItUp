<?php
$dbconn = pg_connect("host=localhost port=5432 dbname=PickItUp
                user=postgres password=postgres") 
                or die('Could not connect: ' . pg_last_error());
    $username = $_GET['username'];            
    $query = "select picture from user_profile where username = $1";
    $result = pg_query_params($dbconn,$query,array($username));
    $line=pg_fetch_array($result,null,PGSQL_ASSOC);
    $pic= $line['picture'];
?>
<!DOCTYPE html>
<html>
  <head>
  <link rel="stylesheet" type="text/css" href="../style.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Square+Peg&family=Tapestry&display=swap" rel="stylesheet">

  <meta charset="utf-8">

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PickItUp | Challenges</title>
  <link rel="stylesheet" type="text/css" href="./challenge-style.css">
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
</head>
<body>
  <!--------BANNER--------->
  <div class="banner">
    <a class="logo" href = "../index.php?username=<?php echo $username?>" style="text-decoration: none">PICKITUP</a>
    <form class="searchbar" name="searchbar" method="POST" action="search.php">
      <input type="search" name="search" placeholder="Search">
      <i class="uil uil-search" style="margin-right: 200px;"></i>
      
    </form>
    <a  class = "nav-link" href="../Challenge/index.php?username=<?php echo $username; ?>">CHALLENGES</a>
    <a  class = "nav-link" href="../Mappa/index.php?username=<?php echo $username ;?>">MAP</a>
    <a  class = "nav-link" href="../Sponsor/index.php?username=<?php echo $username; ?>">SPONSORS</a>
    <img  class = "profile_picture" src=<?php echo $pic; ?>>
    <button id="settings-btn" class="nav-button">SETTINGS</button>
    <script type="text/javascript">
      document.getElementById("settings-btn"). onclick = function () {
        var url_string = window.location.href;
        var url = new URL(url_string);
        var username = url.searchParams.get("username"); 
        location.href = "../Settings/index.php?username="+username;
      };
    </script>
    <button id="logout-btn" class="nav-button" >LOG OUT</button>
      <script type="text/javascript">
      document. getElementById("logout-btn"). onclick = function () {
      location. href = "../Login/login.html";
      };
      </script>
  </div>
  <!--------FINE BANNER--------->

  <!--------MAIN--------->
  <main>
    <div id="challenges">
      <!--------YOUR CHALLENGES--------->
      <div id="your_challenges">
        YOUR CHALLENGES
      <?php
        $query = "select c.description,p.challenge_id,c.nPartecipanti as quanti
        from challenges c join partecipa p on c.challenge_id=p.challenge_id
        where p.profile_id = $1";
        $result = pg_query_params($dbconn,$query,array($username));
        while($line = pg_fetch_array($result,null,PGSQL_ASSOC)){
          $challenge_id=$line['challenge_id'];
          $nPart=$line['quanti'];
          $descr = $line['description'];
          echo '<div id="challenge_active_'.$challenge_id.'">'.$challenge_id;
          $query = "select distinct m.times,m.msg,u.picture,m.username
          from user_profile u left join (
          messages m join partecipa p on (m.username=p.profile_id))
          on u.username = m.username
          where m.challenge_id = $1
          order by m.times ";
          $r=pg_query_params($dbconn,$query,array($challenge_id));
          //CHAT BANNER 
          $chat = "";
          $chat = 'var chat="<div id=\"chat_banner\"></div>';//DA MODIFICARE
          //CHAT
          while($l=pg_fetch_array($r,null,PGSQL_ASSOC)){
            $msg=$l['msg'];
            $user=$l['username'];
            $picture=$l['picture'];
            if($user == $username){
              $chat.= '<div class=\"user_msg\">'.
             $msg. '<img id = \"post-profile_picture\" src=\"'.$picture.'\"></div>';
            }
            else{
              $chat.= '<div class=\"msg\">'.
               $msg. '<img id = \"post-profile_picture\" src=\"'.$picture.'\"></div>';
            }


          }
          //INPUT FOR MSG
          $chat .= '<div id=\"send_message\"><form action=\"send_message.php?username='.$username.'&id='.$challenge_id.'\" method =\"post\" name=\"msg_form\">"+
          "<input type=\"text\" name=\"input_msg\" class=\"input_msg\">"+
          "<input type=\"submit\"  id=\"send-btn\" name=\"send-btn\" value=\"SEND\">"+
          "</form></div>";';
          $chat_HTML="";
          if($chat){
          $chat_HTML='<script type="text/javascript">
          document. getElementById("challenge_active_'.$challenge_id.'"). onclick = function () {
          
          var div = document.querySelector("#chat");
          '.$chat.'
          div.innerHTML=chat;
          
          };
          </script>';
          echo $chat_HTML;
        }


          echo '</div>';
        }
      ?>
      </div>
      <!--------OTHER_CHALLENGES-------->
      <div id="other_challenges">
        OTHER CHALLENGES
        <?php
         $query="select p.challenge_id,c.nPartecipanti as quanti
        from challenges c join partecipa p on p.challenge_id=c.challenge_id
        where not exists
        (select profile_id
        from partecipa p1
        where p1.challenge_id=c.challenge_id and profile_id=$1 )";
        $result = pg_query_params($dbconn,$query,array($username));
        while($line = pg_fetch_array($result,null,PGSQL_ASSOC)){
          $challenge_id=$line['challenge_id'];
          $nPart=$line['quanti'];
        }
        ?>
      </div>
    </div>
    <!--------CHAT-------->
    <div id="chat">
      <?php
        if(isset($_GET['id'])){
          $id=$_GET['id'];
          $query = "select distinct m.times,m.msg,u.picture,m.username
          from user_profile u left join (
          messages m join partecipa p on (m.username=p.profile_id))
          on u.username = m.username
          where m.challenge_id = $1
          order by m.times ";
          $r=pg_query_params($dbconn,$query,array($id));
          //CHAT BANNER 
          $chat = 'var chat="<div id=\"chat_banner\"></div>';//DA MODIFICARE
          //CHAT
          while($l=pg_fetch_array($r,null,PGSQL_ASSOC)){
            $msg=$l['msg'];
            $user=$l['username'];
            $picture=$l['picture'];
            if($user == $username){
              $chat.= '<div class=\"user_msg\">'.
             $msg. '<img class=\"profile_picture\" src=\"'.$picture.'\"></div>';
            }
            else{
              $chat.= '<div class=\"msg\">'.
               $msg. '<img class=\"profile_picture\" src=\"'.$picture.'\"></div>';
            }


          }
          //INPUT FOR MSG
          $chat .= '<div id=\"send_message\"><form action=\"send_message.php?username='.$username.'&id='.$challenge_id.'\" method =\"post\" name=\"msg_form\">"+
          "<input type=\"text\" name=\"input_msg\" class=\"input_msg\">"+
          "<input type=\"submit\"  id=\"send-btn\" name=\"send-btn\" value=\"SEND\">"+
          "</form></div>";';
          $chat_HTML="";
          if($chat){
            $chat_HTML='<script type="text/javascript">
            var div = document.querySelector("#chat");
            '.$chat.'
            div.innerHTML=chat;
            
            
            </script>';  
            echo $chat_HTML;
          }        
        }
      ?>

    </div>
  </main>


  <!--------FINE MAIN--------->

</body>
</html>