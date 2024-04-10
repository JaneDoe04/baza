<?php
session_start();
if(isset($_SESSION['id_konta'])&&!empty($_SESSION['id_konta'])){
    $id_konta = $_SESSION['id_konta'];
  }else{
    header("Location: index.php");
  
  }
$polaczenie = new PDO('mysql:host=localhost;dbname=ksiegowosc','root','');

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
    <nav>
      <a href='logowanie.php'><img src="dose.png" alt="dose" class="logo" /></a>
    </nav>
    <div class="login_box">
      <form action="dodajfirme.php" method="post">
        <h2>Autonomiczny System Zarządzania Księgowoscią</h2>
        <br />
        <input type="text" placeholder="Nazwa Firm" name='nazwa_firmy' class="input_psw" />
        <br />
        <input type="submit" class="submit" value="Dodaj nową firmę" />
        <?php
        if(isset($_POST['nazwa_firmy'])&&!empty($_POST['nazwa_firmy'])){
    $polaczenie = new PDO('mysql:host=localhost;dbname=ksiegowosc','root','');
    $nazwa_firmy = $_POST['nazwa_firmy'];
    $dane = $polaczenie->prepare("INSERT INTO firmy values(null,:nazwa_firmy)");
    $dane->bindValue(':nazwa_firmy', $nazwa_firmy);
    $dane->execute();
        }
        ?>
      </form>
    </div>
    <div class="login_box">
      <form action="dodajfirme.php" method="post">
        <?php
       $edytujfirme = $polaczenie->query("SELECT id, nazwa_firmy from firmy");
       if(!isset($_POST['edycja'])&&empty($_POST['edycja'])){
        echo "<select name='edycja'>";
        while($fetch = $edytujfirme->fetch()){
         echo "<option value='$fetch[0]'>$fetch[1]</option>'";
        }
        echo "</select><input type='submit' class='submit' value='Wybierz firmę' />";
       }
       
       if(isset($_POST['edycja'])&&!empty($_POST['edycja'])&&empty($_POST['blokada'])){

        $id= $_POST['edycja'];
        $fetchowanie = $polaczenie->query("SELECT id, nazwa_firmy from firmy where id=$id");
        $fetchowaniex=$fetchowanie->fetch();
        echo "<input value='$fetchowaniex[1]' type='text' name='edytuj_firme'><br><input type='submit' value='edytuj' name='edytownik'>
        <input type='hidden' value='$fetchowaniex[0]' name='id'>
        <input type='submit' value='usuń' name='edytownik'><br>";
        echo "<input type='hidden' name='blokada' value='1'>";

       }

       if(isset($_POST['edytownik'])&&!empty($_POST['edytownik'])){
        if($_POST['edytownik']=='usuń'){
            $id = $_POST['id'];
            $usunfirme=$polaczenie->query("DELETE from firmy where id=$id");
        
        }else
        if($_POST['edytownik']=='edytuj'){
            $edytujfirme_input = $_POST['edytuj_firme'];
            $id = $_POST['id'];
            $updatefirmy = $polaczenie->query("UPDATE firmy set nazwa_firmy = '$edytujfirme_input' where id = $id ");
           

        }
       }
        ?>
        

      </form>
    </div>
    
    <script src="script.js"></script>
  </body>
</html>
