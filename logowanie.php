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
$pracownik = $polaczenie->query("SELECT pracownik.imie, pracownik.Nazwisko FROM `pracownik`, konta where konta.id = $id_konta  and konta.id_pracownika = pracownik.id");
$pracownik_dane = $pracownik->fetch();


if(isset($_POST['ciastko'])&&!empty($_POST['ciastko'])){
  setcookie('ciastko', $_POST['ciastko']);
}else{
  setcookie('ciastko', 'xd');
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <link rel="icon" href="logo.jpg" type="image/jpg">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Zalogowano</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <nav>
      <img src="dose.png" alt="dose" class="logo" />
      <div>
        <form action='logowanie.php' id='myForm' method='post'> 
      <input type='text' class='firma_editor'id='firma_input' placeholder='wpisz nazwę firmy'>
        <select name='firma' class='input_design' id='firma_select' >
          <?php
            $wybierz_dane = $polaczenie->query("SELECT * FROM `firmy` order by nazwa_firmy");
            echo "<option value=''>wybierz firmę</option>";
            while($dane = $wybierz_dane->fetch())
              {
                
                if($_POST['firma']==$dane[0])
                echo "<option value='$dane[0]' selected class='firma_option'>$dane[1]</option>";
                else
                echo "<option value='$dane[0]'  class='firma_option'>$dane[1]</option>";

              }
          ?>
        </select> 
        <input type='submit' class='input_design input_green'value='Wyświetl raport' name='edytor'>
        <input type="hidden" name='swiezosc' value='nowy'>

        </form>
      </div>
      <div class="nav" data-src='dane'>Sztywne dane</div>
      <div class="nav" data-src='stawki'>Pracownicy</div>
      <div class="nav" data-src='pojazdy'>Pojazdy</div>
      <div class="nav" data-src='raport'>Generuj raport</div>
      <a href='dodajfirme.php' class='nav_design'><div >Dodaj firmę</div></a>
      <a href='edytujfirme.php' class='nav_design'><div >Edytuj firmę</div></a>

      <form action='logowanie.php' method='POST'>
        <input type="submit" value="Wyloguj" name='wyloguj' class='logout'>
      </form>
    </nav>
    
<main>





<div class='dane'>
  <form  class='dane_formularz'action="logowanie.php" method='post'>
    <div class='uloz'>    
      <input type="hidden" name="ciastko" value='dane'>
      <input type='submit' name='swiezosc' value='nowy'>
      <input type='submit' name='swiezosc' value='stary'>
        <?php
 if(!empty($_POST['firma'])&&isset($_POST['firma'])&&!empty($_POST['swiezosc'])&&isset($_POST['swiezosc'])){
 $firma_id=$_POST['firma'];
$swiezosc = $_POST['swiezosc'];
 $dane_pojazdow = $polaczenie->query("SELECT miesiac FROM `pojazdy` where firma_id = $firma_id ORDER BY `pojazdy`.`miesiac` DESC limit 1");
 if($dane_pojazdow_fetch=$dane_pojazdow->fetch()){
  $ostatni_miesiac_pojazdy = $dane_pojazdow_fetch[0];
 }else{
  $ostatni_miesiac_pojazdy='brak';
 }
 $dane_pracownikow = $polaczenie->query("SELECT miesiac FROM `aktywni_kierowcy` where id_firmy = $firma_id ORDER BY `miesiac` DESC limit 1");
 if($dane_pracownikow_fetch=$dane_pracownikow->fetch()){
  $ostatni_miesiac_pracownicy = $dane_pracownikow_fetch[0];
 }else{
  $ostatni_miesiac_pracownicy='brak';
 }
 $input = $swiezosc == 'nowy'?"<input type='submit' name='edytor' value='Edytuj' class='input_design input_green'>":'';
  echo "

    $input
    <input type='button' value='drukuj' data-druk='.druk' class='drukowanie input_design input_green'>
    Ostatni raport dla pojazdów: $ostatni_miesiac_pojazdy <br>
    Ostatni raport dla pracowników: $ostatni_miesiac_pracownicy

    ";
  }
        ?>
    </div>
            
    <div class='druk drukowanie_wszystko'>
    <div class='dane_uloz'>
      <?php
      

      if(isset($_POST['firma'])&&!empty($_POST['firma'])||isset($_POST['firma2'])&&!empty($_POST['firma2'])){
      $firma_id=$_POST['firma'];
     
      echo "
      <div class='uloz_table'>
    <input name='firma' type='hidden' value='$firma_id'>";
    if(isset($_POST['swiezosc'])&&!empty($_POST['swiezosc'])){
$swiezosc = $_POST['swiezosc']=='nowy'?1:2;
  $wybierz_opiekun_gps = $polaczenie->query("SELECT * FROM opiekun_gps_kontakt where id_firmy = '$firma_id' and swiezosc = '$swiezosc'");
  $nazwy_tabel = $polaczenie->query("SELECT * FROM nazwy_tabel where id_firmy = '$firma_id' and swiezosc = '$swiezosc'");
$nazwy_tabel_fetch=$nazwy_tabel->fetch();
$tabela1 = $nazwy_tabel_fetch ?$nazwy_tabel_fetch[1] : '';
echo "
<h3> <input name='tabela1' class='main_input' value='$tabela1'></h3>
";

      if($wybierz_opiekun_gps_fetch = $wybierz_opiekun_gps ->fetch()){


  echo"
    
      <table>
      <tr>
        <th>Nazwa GPS</th>
        <th>MAIL</th>
        <th>LOGIN</th>
        <th>HASŁO</th>
        <th>LINK</th>
      </tr>
      <tr>
        <td>
          <input type='text' value='$wybierz_opiekun_gps_fetch[6]' name='Nazwa_GPS[]'>
        </td>
        <td>
          <input type='text' value='$wybierz_opiekun_gps_fetch[1]' name='MAIL[]'>
        </td>
        <td>
          <input type='text' value='$wybierz_opiekun_gps_fetch[2]' name='LOGIN[]'>
        </td>
        <td>
          <input type='text' value='$wybierz_opiekun_gps_fetch[3]' name='HASLO[]' value=''>
        </td>
        <td>
          <input type='text' value='$wybierz_opiekun_gps_fetch[4]' name='LINK[]'>
        </td>
      </tr>

  ";
  if( $wybierz_opiekun_gps_fetch = $wybierz_opiekun_gps ->fetch()){
     echo "
  <tr>
        <td>
          <input type='text' value='$wybierz_opiekun_gps_fetch[6]' name='Nazwa_GPS[]'>
        </td>
        <td>
          <input type='text' value='$wybierz_opiekun_gps_fetch[1]' name='MAIL[]'>
        </td>
        <td>
          <input type='text' value='$wybierz_opiekun_gps_fetch[2]' name='LOGIN[]'>
        </td>
        <td>
          <input type='text' value='$wybierz_opiekun_gps_fetch[3]' name='HASLO[]' value=''>
        </td>
        <td>
          <input type='text' value='$wybierz_opiekun_gps_fetch[4]' name='LINK[]'>
        </td>
      </tr>
      ";
  }else{
    echo "
    <tr>
    <td>
      <input type='text'  name='Nazwa_GPS[]'>
    </td>
    <td>
      <input type='text' name='MAIL[]'>
    </td>
    <td>
      <input type='text' name='LOGIN[]'>
    </td>
    <td>
      <input type='text' name='HASLO[]' value=''>
    </td>
    <td>
      <input type='text' name='LINK[]'>
    </td>
  </tr>
  ";
  }
 

 if($wybierz_opiekun_gps_fetch = $wybierz_opiekun_gps ->fetch()){
 
  echo
  "
  <tr>
  <td>
    <input type='text' value='$wybierz_opiekun_gps_fetch[6]' name='Nazwa_GPS[]'>
  </td>
  <td>
    <input type='text' value='$wybierz_opiekun_gps_fetch[1]' name='MAIL[]'>
  </td>
  <td>
    <input type='text' value='$wybierz_opiekun_gps_fetch[2]' name='LOGIN[]'>
  </td>
  <td>
    <input type='text' value='$wybierz_opiekun_gps_fetch[3]' name='HASLO[]' value=''>
  </td>
  <td>
    <input type='text' value='$wybierz_opiekun_gps_fetch[4]' name='LINK[]'>
  </td>
</tr>
</table></div>
 
     
      ";
    }else{
      echo "
      <tr>
      <td>
        <input type='text'  name='Nazwa_GPS[]'>
      </td>
      <td>
        <input type='text' name='MAIL[]'>
      </td>
      <td>
        <input type='text' name='LOGIN[]'>
      </td>
      <td>
        <input type='text' name='HASLO[]' value=''>
      </td>
      <td>
        <input type='text' name='LINK[]'>
      </td>
    </tr>
      </table></div>
    <div class='uloz_table'>";
      
    }

  }else{
    echo "
    <h3> opiekun kontakt gps</h3>
      <table>
        <tr>
          <th>Nazwa GPS</th>
          <th>MAIL</th>
          <th>LOGIN</th>
          <th>HASŁO</th>
          <th>LINK</th>
        </tr>
        <tr>
          <td>
            <input type='text'  name='Nazwa_GPS[]'>
          </td>
          <td>
            <input type='text' name='MAIL[]'>
          </td>
          <td>
            <input type='text' name='LOGIN[]'>
          </td>
          <td>
            <input type='text' name='HASLO[]' value=''>
          </td>
          <td>
            <input type='text' name='LINK[]'>
          </td>
        </tr>
        <tr>
        <td>
          <input type='text'  name='Nazwa_GPS[]'>
        </td>
        <td>
          <input type='text' name='MAIL[]'>
        </td>
        <td>
          <input type='text' name='LOGIN[]'>
        </td>
        <td>
          <input type='text' name='HASLO[]' value=''>
        </td>
        <td>
          <input type='text' name='LINK[]'>
        </td>
      </tr>
      <tr>
      <td>
        <input type='text'  name='Nazwa_GPS[]'>
      </td>
      <td>
        <input type='text' name='MAIL[]'>
      </td>
      <td>
        <input type='text' name='LOGIN[]'>
      </td>
      <td>
        <input type='text' name='HASLO[]' value=''>
      </td>
      <td>
        <input type='text' name='LINK[]'>
      </td>
    </tr>
      </table></div>
    <div class='uloz_table'>";
  }

  $dane_cyfrowe_tarczki = $polaczenie->query("SELECT * FROM dane_cyfrowe_tarczki where id_firmy = '$firma_id' and swiezosc = '$swiezosc'");

 
$tabela1 = $nazwy_tabel_fetch ?$nazwy_tabel_fetch[2] : '';
echo "
<h3>    <div class='uloz_table'>
<h3><input name='tabela2' class='main_input' value='$tabela1'></h3> </h3>
";

  if($dane_cyfrowe_tarczki_fetch = $dane_cyfrowe_tarczki ->fetch()){
    echo "

        <table>
          <tr>
          <th>Okres rozliczeniowy</th>
          <th>Ewidencja rozliczana</th>
          <th>Wysłany</th>
          <th>ŚW. I NIEDZIELE</th>
          <th>Godz. nocne</th>
          <th>Pora nocna</th>
          </tr>
          <tr>
            <td>
              <input type='text' value='$dane_cyfrowe_tarczki_fetch[1]' name='okres_rozliczeniowy'>
            </td>
            <td>
              <input type='text' value='$dane_cyfrowe_tarczki_fetch[2]' name='ewidencja_rozliczana'>
            </td>
            <td>
              <input type='text' value='$dane_cyfrowe_tarczki_fetch[3]' name='ewidencja_wysylana'>
            </td>
            <td>
              <input type='text' value='$dane_cyfrowe_tarczki_fetch[4]' name='SWIETA_I_NIEDZIELE'>
            </td>
            <td>
              <input type='text' value='$dane_cyfrowe_tarczki_fetch[5]' name='GODZINY_NOCNE'>
            </td>
            <td>
              <input type='text' value='$dane_cyfrowe_tarczki_fetch[6]' name='PORA_NOCNA'>
            </td>
          </tr>
        </table>
      </div>";
  }else{
    echo "
   
        <table>
          <tr>
            <th>OKRES ROZLICZENIOWY</th>
            <th>EWIDENCJA ROZLICZANA</th>
            <th>Wysłany</th>
            <th>ŚW. I NIEDZIELE</th>
            <th>Godz. nocne</th>
            <th>Pora nocna</th>
          </tr>
          <tr>
            <td>
              <input type='text' name='okres_rozliczeniowy'>
            </td>
            <td>
              <input type='text' name='ewidencja_rozliczana'>
            </td>
            <td>
              <input type='text' name='ewidencja_wysylana'>
            </td>
            <td>
              <input type='text' name='SWIETA_I_NIEDZIELE'>
            </td>
            <td>
              <input type='text' name='GODZINY_NOCNE'>
            </td>
            <td>
              <input type='text' name='PORA_NOCNA'>
            </td>
          </tr>    
        </table>
    </div>";
  }

  $dane = $polaczenie->query("SELECT * FROM `transport_lokalny_krajowy` where id_firmy ='$firma_id' and swiezosc = '$swiezosc'");
  $tabela1 = $nazwy_tabel_fetch ?$nazwy_tabel_fetch[3] : '';
  echo "
  <div class='uloz_table'> <h3>   
  <h3><input name='tabela2' class='main_input' value='$tabela1'></h3> </h3>
  ";
  if($dane_fetch=$dane->fetch()){
    echo "

        <table>
          <tr>
            <td>Pakiet mobilności?</td>
            <td>Delegacje</td>
            <td>Płace</td>
            <td>Zadawanie pobierania gps</td>
            <td>Dane tacho na gps</td>
            <td>Urlopy</td>
            <td>IMI</td>
          </tr>
          <tr>
            <td><input type='text' name='pakiet_mobilnosci' value='$dane_fetch[1]'></td>
            <td><input type='text' name='rozliczamy_diety' value='$dane_fetch[2]'></td>
            <td><input type='text' name='rozliczamy_place' value='$dane_fetch[3]'></td>
            <td><input type='text' name='zadajemy_z_gps' value='$dane_fetch[4]'></td>
            <td><input type='text' name='pobieramy_z_tacho' value='$dane_fetch[5]'></td>
            <td><input type='text' name='czy_urlopy' value='$dane_fetch[6]'></td>
            <td><input type='text' name='IMI' value='$dane_fetch[8]'></td>
          </tr>
        </table>
      </div>
    </div>";

  }else{
    echo "

        <table>
          <tr>
          <td>Pakiet mobilności?</td>
          <td>Delegacje</td>
          <td>Płace</td>
          <td>Zadawanie pobierania gps</td>
          <td>Dane tacho na gps</td>
          <td>Urlopy</td>
          <td>IMI</td>
          </tr>
          <tr>
            <td><input type='text' name='pakiet_mobilnosci' value=''></td>
            <td><input type='text' name='rozliczamy_diety' value=''></td>
            <td><input type='text' name='rozliczamy_place' value=''></td>
            <td><input type='text' name='zadajemy_z_gps' value=''></td>
            <td><input type='text' name='pobieramy_z_tacho' value=''></td>
            <td><input type='text' name='czy_urlopy' value=''></td>
            <td><input type='text' name='IMI' value=''></td>

          </tr>
        </table>
      </div>
    </div>
   ";
  }
  $kontakty = $polaczenie->query("SELECT * FROM kontakty where id_firmy = '$firma_id'  and swiezosc = '$swiezosc'");
  
  if($kontakty_fetch=$kontakty->fetch()){
    echo "
    <label for='kto_odczyty'>Kto wysyła odczyty?
      <textarea name='kto_odczyty' id='kto_odczyty'>$kontakty_fetch[1]</textarea>
    </label>
    <label for='kto_tarczki'>Kto wysyła tarczki?
      <textarea name='kto_tarczki' id='kto_tarczki'>$kontakty_fetch[2]</textarea>
    </label>
    <label for='do_kogo_ewidencje'>Do kogo wysyłać ewidencje czasu pracy?
      <textarea name='do_kogo_ewidencje' id='do_kogo_ewidencje'>$kontakty_fetch[3]</textarea>
    </label>
    <label for='do_kogo_raporty'>Do kogo wysyłać raporty itd.?
      <textarea name='do_kogo_raporty' id='do_kogo_raporty'>$kontakty_fetch[4]</textarea>
    </label>
    <label for='do_kogo_delegacje'>Do kogo wysyłać delegacje?
      <textarea name='do_kogo_delegacje' id='do_kogo_delegacje'>$kontakty_fetch[5]</textarea>
    </label>
    <label for='z_kim_kontakt'>Z kim się kontaktować?
      <textarea name='z_kim_kontakt' id='z_kim_kontakt'>$kontakty_fetch[6]</textarea>
    </label>";
  }else{
    echo "
    <label for='kto_odczyty'>Kto wysyła odczyty?
      <textarea cols='100' name='kto_odczyty' id='kto_odczyty'></textarea>
    </label>
    <label for='kto_tarczki'>Kto wysyła tarczki?
      <textarea cols='100' name='kto_tarczki' id='kto_tarczki'></textarea>
    </label>
    <label for='do_kogo_ewidencje'>Do kogo wysyłać ewidencje czasu pracy? / Wirtualne diety
      <textarea cols='100' name='do_kogo_ewidencje' id='do_kogo_ewidencje'></textarea>
    </label>
    <label for='do_kogo_raporty'>Do kogo wysyłać raporty itd.?
      <textarea cols='100'name='do_kogo_raporty' id='do_kogo_raporty'></textarea>
    </label>
    <label for='do_kogo_delegacje'>Do kogo wysyłać delegacje?
      <textarea cols='100' name='do_kogo_delegacje' id='do_kogo_delegacje'></textarea>
    </label>
    <label for='z_kim_kontakt'>Z kim się kontaktować?
      <textarea cols='100' name='z_kim_kontakt' id='z_kim_kontakt'></textarea>
    </label>";
  }

  $dane = $polaczenie->query("SELECT * FROM `reszta_danych` WHERE id_firmy='$firma_id' and swiezosc = '$swiezosc'");

  if($dane_fetch=$dane->fetch()){
  echo 
  "<label for='indywidualne_zadania'>indywidualne zadania:
    <textarea name='indywidualne_zadania' id='indywidualne_zadania' cols='100'>$dane_fetch[1]</textarea>
  </label>
  <label for='zasady_rozliczen'>zasady rozliczen:
     <textarea name='zasady_rozliczen' id='zasady_rozliczen' cols='100'>$dane_fetch[2]</textarea>
  </label>
  <label for='Uwagi'>Uwagi:
     <textarea name='Uwagi' id='Uwagi' cols='100'>$dane_fetch[3]</textarea>
  </label>";
  }else{
    echo 
    "<label for='indywidualne_zadania'>indywidualne zadania:
      <textarea name='indywidualne_zadania' id='indywidualne_zadania' cols='100'></textarea>
    </label>
    <label for='zasady_rozliczen'>zasady rozliczen:
      <textarea name='zasady_rozliczen' id='zasady_rozliczen' cols='100'></textarea>
    </label>
    <label for='Uwagi'>Uwagi:
      <textarea name='Uwagi' id='Uwagi' cols='100'></textarea>
    </label>";
    
  }
  $dane = $polaczenie->query("SELECT * from kontakty_rel  where id_firmy='$firma_id' and swiezosc = '$swiezosc'");
echo "<input type='hidden' value='$firma_id' name='firma2'>
<input type='hidden' value='$firma_id' name='firma'>


<table class='kontakty_table'>

  <tr>
    <th>IMIE i NAZWISKO</th>
    <th>STANOWISKO</th>
    <th>MAIL</th>
    <th>TELEFON</th>
    <th>USUŃ KONTAKT</th>
  </tr>";

while($dane_fetch = $dane->fetch()){
  echo "<tr class='closest'>
          <td><input name='imie_nazwisko[]' value='$dane_fetch[1]'></td>
          <td><input name='stanowisko_kontakt[]'value='$dane_fetch[3]'></td>
          <td><input name='mail_kontakt[]' value='$dane_fetch[4]'></td>
          <td><input name='tel_kontakt[]' value='$dane_fetch[5]'></td>
          <td><input type='button' value='X' class='kontakt_deleter  '></td>
        </tr>"     ;
}
  echo "</table> <input type='button' value='Nowy kontakt' class='nowy_kontakt input_design'";

    }}
if(isset($_POST['edytor']))
if(isset($_POST['firma2'])&&!empty($_POST['firma2'])&& $_POST['edytor']=='Edytuj'){

  $firma_id = $_POST['firma2'];


  $dane = $polaczenie->query("DELETE from kontakty_rel where id_firmy='$firma_id' and swiezosc = 2");
  $dane = $polaczenie->query("UPDATE  kontakty_rel set swiezosc = 2 where id_firmy='$firma_id' and swiezosc = 1");
  
  $sql = "INSERT INTO kontakty_rel VALUES (null, :imieNazwisko, :firma_id, :stanowiskoKontakt, :mailKontakt, :telKontakt, 1)";
        

  $stmt = $polaczenie->prepare($sql);
  if($_POST['imie_nazwisko'])
  foreach ($_POST['imie_nazwisko'] as $key => $imieNazwisko) {
    $stanowiskoKontakt = $_POST['stanowisko_kontakt'][$key];
    $mailKontakt = $_POST['mail_kontakt'][$key];
    $telKontakt = $_POST['tel_kontakt'][$key];
   
    
    $stmt->bindValue(':imieNazwisko', $imieNazwisko);
    $stmt->bindValue(':firma_id', $firma_id);
    $stmt->bindValue(':stanowiskoKontakt', $stanowiskoKontakt);
    $stmt->bindValue(':mailKontakt', $mailKontakt);
    $stmt->bindValue(':telKontakt', $telKontakt);

    $stmt->execute();

  }
  $dane = $polaczenie->query("DELETE from opiekun_gps_kontakt where id_firmy='$firma_id' and swiezosc = 2");
  $dane = $polaczenie->query("UPDATE  opiekun_gps_kontakt set swiezosc = 2 where id_firmy='$firma_id' and swiezosc = 1");

  $nazwa_gps = $_POST['Nazwa_GPS'];
  $mail = $_POST['MAIL'];
  $login = $_POST['LOGIN'];
  $haslo = $_POST['HASLO'];
  $link = $_POST['LINK'];
  foreach($nazwa_gps as $key => $value){
    $dane = $polaczenie->prepare("INSERT INTO opiekun_gps_kontakt VALUES(null, :mail, :login, :haslo, :link, :firma_id, :nazwa_gps, 1)");
  
    $dane->bindValue(':mail', $mail[$key]);
    $dane->bindValue(':login', $login[$key]);
    $dane->bindValue(':haslo', $haslo[$key]);
    $dane->bindValue(':link', $link[$key]);
    $dane->bindValue(':nazwa_gps', $nazwa_gps[$key]);
    $dane->bindValue(':firma_id', $firma_id); 
    
    $dane->execute();
  }





  $dane = $polaczenie->query("DELETE from dane_cyfrowe_tarczki where id_firmy='$firma_id' and swiezosc = 2");
  $dane = $polaczenie->query("UPDATE  dane_cyfrowe_tarczki set swiezosc = 2 where id_firmy='$firma_id' and swiezosc = 1");
  $okres_rozliczeniowy = $_POST['okres_rozliczeniowy'];
  $ewidencja_rozliczana = $_POST['ewidencja_rozliczana'];
  $ewidencja_wysylana = $_POST['ewidencja_wysylana'];
  $SWIETA_I_NIEDZIELE = $_POST['SWIETA_I_NIEDZIELE'];
  $GODZINY_NOCNE = $_POST['GODZINY_NOCNE'];
  $PORA_NOCNA = $_POST['PORA_NOCNA'];
  
  $dane = $polaczenie->prepare("INSERT INTO dane_cyfrowe_tarczki VALUES (null,:okres_rozliczeniowy, :ewidencja_rozliczana, :ewidencja_wysylana, :SWIETA_I_NIEDZIELE, :GODZINY_NOCNE, :PORA_NOCNA,:firma_id, 1)");
  
  $dane->bindValue(':okres_rozliczeniowy', $okres_rozliczeniowy);
  $dane->bindValue(':ewidencja_rozliczana', $ewidencja_rozliczana);
  $dane->bindValue(':ewidencja_wysylana', $ewidencja_wysylana);
  $dane->bindValue(':SWIETA_I_NIEDZIELE', $SWIETA_I_NIEDZIELE);
  $dane->bindValue(':GODZINY_NOCNE', $GODZINY_NOCNE);
  $dane->bindValue(':PORA_NOCNA', $PORA_NOCNA);
  $dane->bindValue(':firma_id', $firma_id); 
  
  $dane->execute();




$dane = $polaczenie->query("DELETE from transport_lokalny_krajowy where id_firmy='$firma_id' and swiezosc = 2");
$dane = $polaczenie->query("UPDATE  transport_lokalny_krajowy set swiezosc = 2 where id_firmy='$firma_id' and swiezosc = 1");

$pakiet_mobilnosci = $_POST['pakiet_mobilnosci'];
$rozliczamy_diety = $_POST['rozliczamy_diety'];
$rozliczamy_place = $_POST['rozliczamy_place'];
$zadajemy_z_gps = $_POST['zadajemy_z_gps'];
$pobieramy_z_tacho = $_POST['pobieramy_z_tacho'];
$czy_urlopy = $_POST['czy_urlopy'];
$imi = $_POST['IMI'];
$dane = $polaczenie->prepare("INSERT INTO transport_lokalny_krajowy VALUES (null,:pakiet_mobilnosci, :rozliczamy_diety, :rozliczamy_place, :zadajemy_z_gps, :pobieramy_z_tacho, :czy_urlopy,:firma_id,:IMI, 1)");

$dane->bindValue(':pakiet_mobilnosci', $pakiet_mobilnosci);
$dane->bindValue(':rozliczamy_diety', $rozliczamy_diety);
$dane->bindValue(':rozliczamy_place', $rozliczamy_place);
$dane->bindValue(':zadajemy_z_gps', $zadajemy_z_gps);
$dane->bindValue(':pobieramy_z_tacho', $pobieramy_z_tacho);
$dane->bindValue(':czy_urlopy', $czy_urlopy);
$dane->bindValue(':firma_id', $firma_id); 
$dane->bindValue(':IMI', $imi); 


$dane->execute();




$dane = $polaczenie->query("DELETE from kontakty where id_firmy='$firma_id'and swiezosc = 2");
$dane = $polaczenie->query("UPDATE  kontakty set swiezosc = 2 where id_firmy='$firma_id' and swiezosc = 1");

$kto_odczyty = $_POST['kto_odczyty'];
$kto_tarczki = $_POST['kto_tarczki'];
$do_kogo_ewidencje = $_POST['do_kogo_ewidencje'];
$do_kogo_raporty = $_POST['do_kogo_raporty'];
$do_kogo_delegacje = $_POST['do_kogo_delegacje'];
$z_kim_kontakt = $_POST['z_kim_kontakt'];


$dane = $polaczenie->prepare("INSERT INTO kontakty VALUES (null,:kto_odczyty, :kto_tarczki, :do_kogo_ewidencje, :do_kogo_raporty, :do_kogo_delegacje, :z_kim_kontakt,:firma_id, 1)");

$dane->bindValue(':kto_odczyty', $kto_odczyty);
$dane->bindValue(':kto_tarczki', $kto_tarczki);
$dane->bindValue(':do_kogo_ewidencje', $do_kogo_ewidencje);
$dane->bindValue(':do_kogo_raporty', $do_kogo_raporty);
$dane->bindValue(':do_kogo_delegacje', $do_kogo_delegacje);
$dane->bindValue(':z_kim_kontakt', $z_kim_kontakt);
$dane->bindValue(':firma_id', $firma_id);

$dane->execute();





$dane = $polaczenie->query("DELETE from reszta_danych where id_firmy='$firma_id' and swiezosc = 2");
$dane = $polaczenie->query("UPDATE  reszta_danych set swiezosc = 2 where id_firmy='$firma_id' and swiezosc = 1");

$indywidualne_zadania = $_POST['indywidualne_zadania'];
$zasady_rozliczen = $_POST['zasady_rozliczen'];
$Uwagi = $_POST['Uwagi'];

$dane = $polaczenie->prepare("INSERT INTO reszta_danych VALUES (null,:indywidualne_zadania, :zasady_rozliczen, :Uwagi,:firma_id, 1)");

$dane->bindValue(':indywidualne_zadania', $indywidualne_zadania);
$dane->bindValue(':zasady_rozliczen', $zasady_rozliczen);
$dane->bindValue(':Uwagi', $Uwagi);
$dane->bindValue(':firma_id', $firma_id);

$dane->execute();
}

          ?>
          
</div>
</div>
  </form> 
  </div>
</div>










<div class="stawki">
  <form action="logowanie.php" method='post' class='cos_tu_nie_gra'>
    <div class='uloz'>
  
     

    <select id="monthSelect2" name='czas' class="input_design">
      <option value="">Wybierz miesiąc</option>
    </select>
    <select id="yearSelect2"  name='czas2' class="input_design">
      <option value="">Wybierz rok</option>
    </select>
    <input type="submit" value='Wyświetl raport' name='edytor' class="input_design input_green">
    <input type="hidden" name='swiezosc' value='nowy'>

    <input type="hidden" name="ciastko" value='stawki'>
    
      <?php
      if(isset($_POST['firma'])&&!empty($_POST['firma'])){
        $firma = $_POST['firma'];
        echo "<input name='firma' type='hidden' value='$firma'>";
      }
        if(isset($_POST['czas'])&&!empty($_POST['czas'])&&isset($_POST['czas2'])&&!empty($_POST['czas2'])&&isset($_POST['firma'])&&!empty($_POST['firma'])){
          $firma = $_POST['firma'];
          $time = rework_time($_POST['czas'], $_POST['czas2'] );
          echo "
          <input type='hidden' value='$time' name='time'>
          <input type='hidden' value='$firma' name='firma'>
          
          ";
          

          $wybierz_dane = $polaczenie->query("SELECT count(*) FROM aktywni_kierowcy where miesiac ='$time' AND id_firmy = '$firma'");
          $wybierz_dane_fetch = $wybierz_dane->fetch();
          $dane_pracownikow = $polaczenie->query("SELECT miesiac FROM `aktywni_kierowcy` where id_firmy = $firma_id ORDER BY `miesiac` DESC limit 1");
          $dane_pracownikow_fetch=$dane_pracownikow->fetch();
          $ostatni_miesiac_pracownicy = $dane_pracownikow_fetch[0];
          echo '<input type="submit" value="edytuj raport" class="input_design raport_editing ';
          echo $ostatni_miesiac_pracownicy!=$time?' zablokowany" disabled ':"";
          echo '  name="stawki_submit" value="edytuj raport">';
          echo $ostatni_miesiac_pracownicy!=$time?"<input type='button' class='odblokuj input_design' value='odblokuj'>":"";
        
          echo"
          <input type='button' value='drukuj' data-druk='.stawki_druk' class='drukowanie input_design input_green'></div>";

          if($wybierz_dane_fetch[0]){
            echo "
              <table class='stawki_druk drukowanie_wszystko'>
              <tr>
                <th>Aktywni kierowcy</th>
                <th>Karta kierowcy</th>
                <th>Jaka umowa</th>
                <th>Etat</th>
                <th>Od Kiedy</th>
                <th>Do kiedy</th>
                <th>Zasadnicza</th>
                <th>Dyżur</th>
                <th>Premia</th>
                <th>Nadgodziny</th>
                <th>Nocne</th>
                <th>Uwagi</th>
                <th>Odczyt</th>

              </tr>
      
              ";

            $wybierz_dane2 = $polaczenie->query("SELECT * FROM aktywni_kierowcy where miesiac ='$time' AND id_firmy = '$firma'");
              while($wybierz_dane2_fetch = $wybierz_dane2->fetch()){
                $input = $wybierz_dane2_fetch[15]==1?"<input type='checkbox' checked disabled>":"<input type='checkbox' disabled>";
                echo "
                <tr>
                  <td>$wybierz_dane2_fetch[1]</td>
                  <td class='date-cell'>$wybierz_dane2_fetch[14]</td>
                  <td>$wybierz_dane2_fetch[2]</td>
                  <td>$wybierz_dane2_fetch[3]</td>
                  <td>$wybierz_dane2_fetch[4]</td>
                  <td>$wybierz_dane2_fetch[5]</td>
                  <td>$wybierz_dane2_fetch[6]</td>
                  <td>$wybierz_dane2_fetch[7]</td>
                  <td>$wybierz_dane2_fetch[8]</td>
                  <td>$wybierz_dane2_fetch[9]</td>
                  <td>$wybierz_dane2_fetch[10]</td>
                  <td>$wybierz_dane2_fetch[11]</td>
<td>$input</td>
                 
                </tr>
                
                
                ";
              }
              echo "</table>";
            }

            }else{
              echo "</div>";
            }
            if(isset($_POST['stawki_submit'])&&!empty($_POST['stawki_submit'])&&$_POST['stawki_submit']=='edytuj raport'){
              $time = $_POST['time'];
              $firma = $_POST['firma'];
              echo "

              <input type='submit' value='edytuj raport' name='submit' class='input_design'>
              <input type='submit' value='utwórz raport na aktualny miesiąc' name='submit' class='input_design'>
              <input type='hidden' value='$time' name='time'>
              <input type='hidden' value='$firma' name='firma'>
             
        <div class='wszystko_overflow'>
<div class='podkresl'>
                <input disabled class='wykluczenie' value='Aktywni kierowcy'>
                <input disabled class='wykluczenie' value='Karta kierowcy'>
                <input disabled class='wykluczenie' value='Jaka umowa'>
                <input disabled class='wykluczenie' value='Etat'>
                <input disabled class='wykluczenie' value='Od Kiedy'>
                <input disabled class='wykluczenie' value='Do kiedy'>
                <input disabled class='wykluczenie' value='Zasadnicza'>
                <input disabled class='wykluczenie' value='Dyżur'>
                <input disabled class='wykluczenie' value='Premia'>
                <input disabled class='wykluczenie' value='Nadgodziny'>
                <input disabled class='wykluczenie' value='Nocne'>
                <input disabled class='wykluczenie' value='Uwagi'>
 
      </div>
              ";

            $wybierz_dane2 = $polaczenie->query("SELECT * FROM aktywni_kierowcy where miesiac ='$time' AND id_firmy = '$firma'");
            while ($wybierz_dane2_fetch = $wybierz_dane2->fetch()) {
              $input = $wybierz_dane2_fetch[15] == 1 ? 'checked' : '';
              $hiddenInputDisabled = $wybierz_dane2_fetch[15] == 1 ? 'disabled' : '';
          
              echo "
                  <div class='podkresl'>
                  <input type='button' class='clonning' value='+'>
                  <input name='kierowcy[]' value='$wybierz_dane2_fetch[1]'>
                  <input name='karta_kierowcy[]' value='$wybierz_dane2_fetch[14]'>
                  <input name='umowa[]' value='$wybierz_dane2_fetch[2]'>
                  <input name='etat[]' value='$wybierz_dane2_fetch[3]'>
                  <input name='uwagi[]' value='$wybierz_dane2_fetch[11]'>
                  <input name='odkiedy[]' value='$wybierz_dane2_fetch[4]'>
                  <input name='dokiedy[]' value='$wybierz_dane2_fetch[5]'>
                  <input name='zasadnicza[]' value='$wybierz_dane2_fetch[6]'>
                  <input name='dyzur[]' value='$wybierz_dane2_fetch[7]'>
                  <input name='premia[]' value='$wybierz_dane2_fetch[8]'>
                  <input name='nadgodziny[]' value='$wybierz_dane2_fetch[9]'>
                  <input name='nocne[]' value='$wybierz_dane2_fetch[10]'>
                  <input name='odczyt[]' type='checkbox' value='true' $input onchange='changeCheckboxValue(this)'>
                  <input type='hidden' name='odczyt[]' value='false' $hiddenInputDisabled>
                  <input type='button' value='usuń' class='line_deleting input_design'>
                  </div>
              ";
          
        
             
           
          
              

               
              }
              echo "<br><input type='button' class='new_line input_design lol' value='dodaj nowa linijke'></div>";
            }
            if(isset($_POST['submit'])&&!empty($_POST['submit'])){
              

              $time = $_POST['time'];
              $firma = $_POST['firma'];
              echo "<input type='hidden' value='$firma' name='firma'>";

              $kierowcy = $_POST['kierowcy'];
              $karta_kierowcy = $_POST['karta_kierowcy'];
              $odczyt = $_POST['odczyt'];
              $umowa = $_POST['umowa'];
              $etat = $_POST['etat'];
              $odkiedy = $_POST['odkiedy'];
              $dokiedy = $_POST['dokiedy'];
              $zasadnicza = $_POST['zasadnicza'];
              $dyzur = $_POST['dyzur'];
              $premia = $_POST['premia'];
              $nadgodziny = $_POST['nadgodziny'];
              $nocne = $_POST['nocne'];
              $uwagi = $_POST['uwagi'];
              if($_POST['submit']=='edytuj raport'){
                $wybierz_dane2 = $polaczenie->query("DELETE FROM aktywni_kierowcy where miesiac ='$time' AND id_firmy = '$firma'");
                foreach($kierowcy as $key => $value){
                  $odczyt_blokada = $odczyt[$key]=='true'?1:0;
                $wybierz_dane2 = $polaczenie->query("INSERT INTO aktywni_kierowcy 
                VALUES(null,'$value','$umowa[$key]','$etat[$key]','$odkiedy[$key]','$dokiedy[$key]','$zasadnicza[$key]','$dyzur[$key]','$premia[$key]',
                '$nadgodziny[$key]','$nocne[$key]','$uwagi[$key]','$time','$firma','$karta_kierowcy[$key]','$odczyt_blokada')");
                 }
              }else if($_POST['submit']=='utwórz raport na aktualny miesiąc'){

                $wybierz_dane2 = $polaczenie->query("SELECT miesiac FROM aktywni_kierowcy where id_firmy = '$firma' ORDER BY miesiac desc limit 1");
                $wybierz_dane2_fetch = $wybierz_dane2->fetch();
                $data = $wybierz_dane2_fetch[0];
                list($year,$month,$day)=explode('-',$data);
                $month++;
                if($month>12){
                  $month=1;
                  $year++;
                }
                $aktualnaData=rework_time($month, $year);
                echo $aktualnaData;
                  foreach($kierowcy as $key => $value){
                    $wybierz_dane2 = $polaczenie->query("INSERT INTO aktywni_kierowcy 
                    VALUES(null,'$value','$umowa[$key]','$etat[$key]','$odkiedy[$key]','$dokiedy[$key]','$zasadnicza[$key]','$dyzur[$key]','$premia[$key]',
                    '$nadgodziny[$key]','$nocne[$key]','$uwagi[$key]','$aktualnaData','$firma')");
                     
                }
              }

            }


          ?>
         
          </form>
</div>


















      <div class='pojazdy'>
       
        <form action="logowanie.php" method="post">
         
        <div class='pojazdy_menu'> 
  
          <!-- <input type="month" name='czas' class="input_design" />
         -->
         <div class='uloz'>
         <form action="logowanie.php" method='post'>
          <div class='design_box'>
          <input type="text" name='samochod' class='wyszukiwarka' placeholder = 'wyszukaj po rejestracji'>
          <input type="submit" class='input_design input_green'value="wyszukaj samochód">
          </div>
        </form>
  
         <select id="monthSelect" name='czas' class="input_design">

    <option value="">Wybierz miesiąc</option>
  </select>
  <select id="yearSelect"  name='czas2' class="input_design">
    <option value="">Wybierz rok</option>
  </select>
          <input type="submit" value='Wyświetl raport' name='edytor' class="input_design input_green">
          <input type="hidden" name="ciastko" value='pojazdy'>
          <input type="hidden" name='swiezosc' value='nowy'>
       
            <?php



              function rework_time($time,$time2){
                $time = intval($time);
    
                $time=$time<10?'0'.$time:$time;

                $time = implode('-',[$time2,$time,'01']);
                return $time;
              }
              if(isset($_POST['firma'])&&!empty($_POST['firma'])){
                $firma = $_POST['firma'];
                echo "<input name='firma' type='hidden' value='$firma'>";
              }
              if(isset($_POST['samochod'])&&!empty($_POST['samochod'])){
                $samochod = $_POST['samochod'];
            
                $wybierz_dane = $polaczenie->query("SELECT * FROM `pojazdy` where aktywne_pojazdy='$samochod'");
                $wszystkie_samochody = $wybierz_dane->fetchAll();
                if(!empty($wszystkie_samochody)){
                  echo "</div>
                          <table>";
                  foreach($wszystkie_samochody as $key =>$value){
                    $input = $value[7]==0?'<input type="checkbox" disabled >':"<input type='checkbox' disabled  checked>";
             

                    echo "  <tr>
                              <td>$value[0]</td>
                              <td>$value[1]</td>
                              <td>$value[2]</td>
                              <td>$value[3]</td>
                              <td>$value[4]</td>
                              <td>$value[5]</td>
                              <td>$input</td>

                         

                            </tr>";
                  }
                  echo "</table>";

                }

              }
              
              
              if(isset($_POST['czas'])&&!empty($_POST['czas'])&&isset($_POST['czas2'])&&!empty($_POST['czas2'])&&isset($_POST['firma'])&&!empty($_POST['firma'])){
                $czas = rework_time($_POST['czas'],$_POST['czas2']);
                $id_firmy=$_POST['firma'];
                $wybierz_dane = $polaczenie->query("SELECT * FROM `pojazdy` where miesiac = '$czas' AND firma_id='$id_firmy'");
                $dane_pojazdow = $polaczenie->query("SELECT miesiac FROM `pojazdy` where firma_id = $firma_id ORDER BY `pojazdy`.`miesiac` DESC limit 1");
                $dane_pojazdow_fetch=$dane_pojazdow->fetch();
                 $ostatni_miesiac_pojazdy = $dane_pojazdow_fetch[0];
                 
                
                echo '
                <input type="button" data-druk=".tabela" class="drukowanie input_design" value="drukowanie"> 
                Tylko braki: 
                <input type="checkbox" class="braki_only"> 
                <form action="logowanie.php" method="POST">
                  <input type="submit" class="input_design raport_editing';
                  echo $ostatni_miesiac_pojazdy!=$czas?' zablokowany" disabled ':"";
                  echo '  name="edytor" value="edytuj raport">';
                  echo $ostatni_miesiac_pojazdy!=$czas?"<input type='button' class='odblokuj input_design' value='odblokuj'>":"";
                  echo '
                  <input type="hidden" name="id_firmy" value="'.$id_firmy.'">
                  <input type="hidden" name="firma" value="'.$id_firmy.'">

                  <input type="hidden" name="ciastko" value="pojazdy"><input type="hidden" name="form" value="'.$czas.'">
                  </div> 
                </form>
              <table class="tabela drukowanie_wszystko">';
              $i=1;
                while($dane = $wybierz_dane ->fetch()){
                  $input = $dane[7]==0?'<input type="checkbox" disabled >':"<input type='checkbox' disabled  checked>";
                echo '
                <tr class="linia">
                  <td>'.$i++.'</td>
                  <td>'.$dane[1].'</td>
                  '."
                 <td class='pokdreslnik'> 
$input
                 </td>
                  
                  ".
                 
                  
                  '
                  <td class="tarch">'.$dane[2].'</td>
                  <td>'.$dane[3].'</td>
                  <td class="uwagi">'.$dane[4].'</td>
                </tr>';
              }
              echo '
              </table>';
              echo "<input type='hidden' value='$id_firmy' name='firma'></form>";
              
           
    

              }else if(isset($_POST['czas'])&&!$_POST['czas']&&!isset($_POST['raporcik'])&&!isset($_POST['form'])&&!isset($_POST['samochod'])){
                echo 'Wybierz poprawną date   </form>';
              }
              // else if(!isset($_POST['firma'])||empty($_POST['firma'])){
              //   echo 'Wybierz firmę';

              // }
            
     
                
            if(isset($_POST['form'])&&!empty($_POST['form']&&$_POST['edytor']=='edytuj raport')){
             $czas = $_POST['form'];
             $id_firmy=$_POST['id_firmy'];
              $wybierz_dane = $polaczenie->query("SELECT * FROM `pojazdy` where miesiac = '$czas' AND firma_id='$id_firmy'");
              echo "
              <input type='submit' class='input_design raport_editing' name='edytor'value='edytuj raport'>
              <input type='submit' class='input_design raport_editing'  name='edytor' value='utwórz raport na aktualny miesiąc'>
              <input type='hidden' name='id_firmy' value='$id_firmy'>
              <input type='hidden' name='ciastko' value='pojazdy'>
              <input type='hidden' name='form2' value='".$czas."'></div>";
              echo 
              '<div class="pojazdy_edit">
              <br>'.$czas."<br>
             
              <input disabled class='wykluczenie' value='Aktywne pojazdy'>
              <input disabled class='wykluczenie' value='Tachograf'>
              <input disabled class='wykluczenie' value='Uwagi'>
              <input disabled  class='wykluczenie' value='Braki w KM'>
              <input type='hidden' name='raporcik'>";
              $i=1;
              while($dane = $wybierz_dane->fetch()) {
                $checked = $dane[7] == 0 ? '' : 'checked';
                echo '
                  
                  <div class="podkresl"><input type="button" class="clonning" value="+">.
                  <input name="pojazdy[]" value="'.$dane[1].'"/>
                  <input type="checkbox" class="blokada_check" name="blokada[]" value="true" '.$checked.' onchange="changeCheckboxValue(this)">
                  <input type="hidden" name="blokada[]" value="false" '.($checked ? 'disabled' : '').'>
                  <input name="tachograf[]" value="'.$dane[2].'"/>
                  <input name="uwagi[]" value="'.$dane[3].'"/>
                  <input name="braki[]" value="'.$dane[4].'"/>
                 
                  <input type="button" value="usuń" class="input_design line_deleting">
                 
                </div>
                <br>';
              }
            
              echo "<input type='button' class='new_line input_design' value='dodaj nowa linijke'>";
            }
            if(isset($_POST['form2'])&&!empty($_POST['form2'])){
              $aktualnaData = date('Y-m-d');
              list($year,$month,$day)=explode('-',$aktualnaData);
              $aktualnaData = rework_time($month,$year);
              if(isset($_POST['edytor'])&&!empty($_POST['edytor']))
                $data = $_POST['edytor']=='edytuj raport'?$_POST['form2']:$aktualnaData;
                $id_firmy=$_POST['id_firmy'];
              $wybierz_dane = $polaczenie->query("SELECT COUNT(miesiac) FROM pojazdy where miesiac = '$data' AND firma_id='$id_firmy'");
              $dane = $wybierz_dane ->fetch();

              if($dane[0]){
                  if(isset($_POST['edytor'])&&!empty($_POST['edytor'])){
                    if($_POST['edytor']=='edytuj raport'){

                       $usun_dane=$polaczenie->query("delete from pojazdy where miesiac = '$data' AND firma_id ='$id_firmy'");

                       if(isset($_POST['pojazdy'])&&!empty($_POST['pojazdy'])){
                       
                        foreach($_POST['pojazdy'] as $key => $value){

                          $tachograf = $_POST['tachograf'][$key];
                          $uwagi = $_POST['uwagi'][$key];
                          $braki = $_POST['braki'][$key];
                  
                          $blokady = $_POST['blokada'][$key]=='true'?1:0;

                          $wybierz_dane = $polaczenie->prepare("INSERT INTO pojazdy VALUES(null, :value, :tachograf, :uwagi, :braki, :data, :id_firmy,:blokady)");

                          $wybierz_dane->bindValue(':value', $value);
                          $wybierz_dane->bindValue(':blokady', $blokady);
                          $wybierz_dane->bindValue(':tachograf', $tachograf);
                          $wybierz_dane->bindValue(':uwagi', $uwagi);
                          $wybierz_dane->bindValue(':braki', $braki);
                          $wybierz_dane->bindValue(':data', $data);
                          $wybierz_dane->bindValue(':id_firmy', $id_firmy);
                          
                          $wybierz_dane->execute();
                          
                        }
                      }                      
                      echo 'pomyślnie edytowano raport';
                    }                
                  }

              }
              
              if($_POST['edytor']=='utwórz raport na aktualny miesiąc'){
                echo "xd";
                $wybierz_dane2 = $polaczenie->query("SELECT miesiac FROM pojazdy where firma_id = '$id_firmy' ORDER BY miesiac desc limit 1");
                $wybierz_dane2_fetch = $wybierz_dane2->fetch();
                echo "$wybierz_dane2_fetch[0] <br>" ;
                $data = $wybierz_dane2_fetch[0];
                list($year,$month,$day)=explode('-',$data);
                $month++;
                echo $month."<br>";
                if($month>12){
                  $month=1;
                  $year++;
                }
                $data=rework_time($month, $year);
                echo $data;
                foreach($_POST['pojazdy'] as $key => $value){
                  $tachograf = $_POST['tachograf'][$key];
                  $uwagi = $_POST['uwagi'][$key];
                  $braki = $_POST['braki'][$key];
                  $blokady = $_POST['blokada'][$key]=='true'?1:0;

                  // echo $value. " ". $tachograf." ".$uwagi." ".$braki[$key].'<br> ';
                 $wybierz_dane = $polaczenie->query("INSERT INTO pojazdy VALUES(null,'$value','$tachograf','$uwagi','$braki','$data','$id_firmy','$blokady')");                  
                }
                echo 'pomyslnie utworzono nowy raport na bierzacy miesiac';
              }
              
              if(!$dane[0]&&$_POST['edytor']=='edytuj raport'){                
                foreach($_POST['pojazdy'] as $key => $value){
                  $tachograf = $_POST['tachograf'][$key];
                  $uwagi = $_POST['uwagi'][$key];
                  $braki = $_POST['braki'][$key];
                  // echo $value. " ". $tachograf." ".$uwagi." ".$braki[$key].'<br> ';
                 $wybierz_dane = $polaczenie->query("INSERT INTO pojazdy VALUES(null,'$value','$tachograf','$uwagi','$braki','$data','$id_firmy')");
                  
                }
                echo 'pomyślnie edytowano raport';
              }
            }
            ?>
          </div>
          </div>
          </div>
        <div class="raport">
          <div class='uloz'>
            <form action='logowanie.php' method='POST'>
         
          <select id="monthSelect3" name='czas' class="input_design">
            <option value="">Wybierz miesiąc</option>
          </select>
          <select id="yearSelect3"  name='czas2' class="input_design">
            <option value="">Wybierz rok</option>
          </select>
            <input type="submit" value='Wyświetl raport' name='edytor' class="input_design input_green">
          <input type="hidden" name='swiezosc' value='nowy'>

            <input type="hidden" name="ciastko" value='raport'>
          </select>
          <form>
          
          <?php
                if(isset($_POST['firma'])&&!empty($_POST['firma'])){
                  $firma = $_POST['firma'];
                  echo "<input name='firma' type='hidden' value='$firma'>";
                }
            if(isset($_POST['firma'])&&!empty($_POST['firma'])&&isset($_POST['czas'])&&!empty($_POST['czas'])&&isset($_POST['czas2'])&&!empty($_POST['czas2'])&&isset($_POST['edytor'])&&$_POST['edytor']=='Wyświetl raport')
            {
              $miesiac = $_POST['czas'];
              $rok = $_POST['czas2'];
              $id_firmy = $_POST['firma'];
              $czas = rework_time($miesiac,$rok);
              $pracownik = $polaczenie->query("SELECT pracownik.imie, pracownik.Nazwisko FROM `pracownik`, konta where konta.id = $id_konta  and konta.id_pracownika = pracownik.id");
              $pracownik_dane = $pracownik->fetch();
              $wybierz_dane = $polaczenie->query("SELECT `aktywne_pojazdy`,`braki w kilometrach`  FROM `pojazdy` where `braki w kilometrach` !='' AND miesiac = '$czas' AND firma_id = $id_firmy");
              echo " <input type='button' value='drukuj' data-druk='.generuj_raport_druk' class='drukowanie input_design input_green'>
              <input type='button' value='drukuj wszystko' data-druk='drukuj_wszystko' class='drukowanie input_design input_green'></div>
              <textarea  class='generuj_raport_druk drukowanie_wszystko'>"."Witam,\n";
              while($wybierz_dane_fetch=$wybierz_dane->fetch()){
                echo $wybierz_dane_fetch[0].": ".$wybierz_dane_fetch[1].".\n";
              }
              echo "Pozdrawiam, $pracownik_dane[0] $pracownik_dane[1].</textarea >
         "
          ;
          
            }else{
              echo "</div>";
            }
          ?>
        
        </div>
      </main>
    <script src="script.js" defer></script>
  </body>
</html>
