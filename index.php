<?php
session_start();
$_SESSION=[];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <link rel="icon" href="logo.jpg" type="image/jpg">

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Autonomiczny System Zarządzania Księgowoscią</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <nav><img src="dose.png" alt="dose" class="logo" /></nav>
    <div class="login_box">
      <form action="index.php" method="post">
        <h2>Autonomiczny System Zarządzania Księgowoscią</h2>
        <br />
        <input type="text" placeholder="login" name='login' class="input_psw" />
        <br />
        <input type="password" placeholder="haslo" name='password' class="input_psw" />
        <br />
        <input type="submit" class="submit" value="Zaloguj" />
      </form>
      <?php
        $polaczenie = new PDO('mysql:host=localhost;dbname=ksiegowosc','root','');
      
        if(isset($_POST['login'])&&!empty($_POST['login'])&&isset($_POST['password'])&&!empty($_POST['password'])){
          $login = $_POST['login'];
          $haslo = $_POST['password'];
          $logowanie = $polaczenie->prepare("SELECT id FROM `konta` where login ='$login' AND haslo = '$haslo'");
          
         $logowanie ->execute();
         if($konto = $logowanie->fetch()){
          var_dump($konto[0]);
          $_SESSION['id_konta'] = $konto[0];
          header("Location: logowanie.php");
         }
          else
          echo "Podany login lub hasło jest niepoprawne";
 
          // echo 'podano login i haslo: '.$login.' '.$haslo;
        }else if(isset($_POST['login'])&&empty($_POST['login'])&&isset($_POST['password'])&&empty($_POST['password']))
        {
          echo 'prosze podać login oraz hasło';
        }
        
      
      ?>
    </div>
    
    <script src="script.js"></script>
  </body>
</html>
