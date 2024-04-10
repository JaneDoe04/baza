<?php
session_start();

if(isset($_POST['wyloguj'])){
$_SESSION=[];
session_destroy();
header("Location: index.php");
}

if(isset($_SESSION['id_konta'])&&!empty($_SESSION['id_konta'])){
  $id_konta = $_SESSION['id_konta'];
}else{
  header("Location: index.php");

}
$polaczenie = new PDO('mysql:host=localhost;dbname=ksiegowosc','root','');
    if(isset($_POST['imie'])&&!empty($_POST['imie'])&&isset($_POST['nazwisko'])&&!empty($_POST['nazwisko'])&&isset($_POST['login'])&&!empty($_POST['login'])&&isset($_POST['haslo'])&&!empty($_POST['haslo'])){
        $imie=$_POST['imie'];
        $nazwisko = $_POST['nazwisko'];
        $login= $_POST['login'];
        $haslo = $_POST['haslo'];
        $stanowisko = $_POST['stanowisko'];
        $stmt=$polaczenie->prepare("INSERT into pracownik values(null,:imie,:nazwisko,:stanowisko)");
        $stmt->bindValue(':imie', $imie);
        $stmt->bindValue(':nazwisko', $nazwisko);
        $stmt->bindValue(':stanowisko', $stanowisko);
        $stmt->execute();
        $stmt2=$polaczenie->prepare("INSERT INTO konta values((SELECT id from pracownik ORDER BY id desc limit 1),:login,:haslo,(SELECT id from pracownik ORDER BY id desc limit 1))");
        $stmt2->bindValue(':login', $login);
        $stmt2->bindValue(':haslo', $haslo);
        $stmt2->execute();

    }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <link rel="icon" href="logo.jpg" type="image/jpg">

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tworzenie konta</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <nav>
      <a href='logowanie.php' ><img src="dose.png" alt="dose" class="logo" /></a>
    </nav>
    <div class="login_box">
      <form action="tworzeniekonta.php" method="post" class='wyglad'>
        <h2>Autonomiczny System Zarządzania Księgowoscią</h2>
        <br>
        <label for="imie"  class='label'>Imię: <input type="text" id="imie" name="imie" required></label>
   

    <label for="nazwisko"  class='label'>Nazwisko:<input type="text" id="nazwisko" name="nazwisko" required></label>
    

    <label for="login" class='label'>Login:   <input type="text" id="login" name="login" required></label>
 

    <label for="haslo"  class='label'>Hasło:<input type="password" id="haslo" name="haslo" required></label>
    

    <label for="stanowisko"  class='label'>Stanowisko:<input type="text" id="stanowisko" name="stanowisko"></label>
    

    

 
        <input type="submit" class="submit" value="Utwórz konto" />
      </form>


    </div>
    
    <script src="script.js"></script>
  </body>
</html>
