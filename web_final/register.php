<?php
// ------------------- CONTROLLER -------------------
// Die Initialisierung der Sessiou und das Laden der benötigten Dateien läuft
//   analog zu login.php ab.
session_start();
require_once('system/config.php');
require_once('system/data.php');

if(isset($_SESSION['userid'])) {
  unset($_SESSION['userid']);
  session_destroy();
}

$logged_in = false;
$log_in_out_text = "Anmelden";

// Die Prüfun, ob die Seite direkt, oder durch Klick auf die Registrieren-Schaltfläche
//   geladen wurde, läuft analog zu login.php ab.
// Die Validierung der einzelnen Eingaben läuft mit einer Ausnahme analog zu login.php ab.
if(isset($_POST['register_submit'])){

  $msg = "";
  $register_submit_valid = true;

  if(!empty($_POST['email'])){
    $email = $_POST['email'];
  }else{
    $msg .= "Bitte gib deine E-Mailadresse  ein.<br>";
    $register_submit_valid = false;
  }

  if(!empty($_POST['password'])){
    $password = $_POST['password'];
  }else{
    $msg .= "Bitte gib dein Passwort ein.<br>";
    $register_submit_valid = false;
  }

  // Wir überpüfen, ob der User etwas in
  //   <input ... name="password_confirm" ...>
  //   eingegeben hat und ob dieser Wert mit $password übereinstimmt.
  if(empty($_POST['password_confirm']) || $password != $_POST['password_confirm']){
    $msg .= "Passwort und Passwortbestätigung stimmen nicht überein.<br>";
    $register_submit_valid = false;
  }

  if(!empty($_POST['firstname'])){
    $firstname = $_POST['firstname'];
  }else{
    $msg .= "Bitte gib deinen Vornamen ein.<br>";
    $register_submit_valid = false;
  }

  if(!empty($_POST['lastname'])){
    $lastname = $_POST['lastname'];
  }else{
    $msg .= "Bitte gib deinen Nachnamen ein.<br>";
    $register_submit_valid = false;
  }
  // Daten in die Datenbank schreiben ******************************************************

  if($register_submit_valid){
    // Zuerst müssen wir überprüfen, od der User schon existiert.
    // Indiz dafür ist die E-Mail-Adresse.
    // Jede E-Mail-Adresse darf in der DB nur einmal vorkommen.
    // Die Funktion does_email_exist() steht in system/data.php.
    // Wir erwarten als Ergebnis den Wert 0, also keine Einträge.
    if(does_email_exist($email)){
      // Wenn does_email_exist() etwas anderes als 0 zurückgibt, existiert die E-mail-Adresse berits.
      // Der User kann sich nicht erneut registrieren.
      // Wir erweitern $msg um die entsprechende Fehlermeldung.
      $msg = "Diese E-Mail-Adresse ist bereits vergeben.</br>";
    }else{
      // Wenn does_email_exist() den Wert 0 zurückgibt, schreiben wir einen neuen User in die DB.
      // Die save_user()-Funktion befindet sich ebenfalls in system/data.php.
      // Wenn der User erfolgreich gespeichert wurde gibt sie true zurück, anderenfalls false.
      $result = save_user($email, $password, $firstname, $lastname);

      // Meldung für den User zusammenstellen
      if($result){  // Wenn der User erfolgreich gespeichert wurde ...
        unset($_POST);  // Daten aus der $_POST-Variablen löschen, damit sie nicht wersehentlich nochmal geladen werden.
        $alert_type = "alert-success"; // Farbe-class (grün) für die Boostrap-Alert-Box bestimmen
        $msg = "Du hast dich erfolgreich registriert.</br>"; // Nachricht schreiben
      }else{       // andernfalls ...
        $alert_type = "alert-danger"; // Farbe-class (rot) für die Boostrap-Alert-Box bestimmen
        $msg .= "Es gibt ein Problem mit der Datenbankverbindung.</br>"; // Nachricht schreiben
      }
    }
  }else{
     // Farbe-class (orange) für die Boostrap-Alert-Box (Anzeige der Validierungsfehler) bestimmen
    $alert_type = "alert-warning";
  }

}

// ---------------------- VIEW ----------------------
?>

<!DOCTYPE html>
<html>
<head>
<?php
  $page_head_title = "MMP Ricardo - Registrierung";
  include_once('templates/page_head.php');
?>
</head>
<body class="py-5">
  <!-- Inhalt mit Navigation -->
  <div class="container">

    <?php include_once('templates/menu.php') ?>

    <section class="content">
      <h1>Registrieren</h1>
      <p>Bitte registriere dich, um neue Inserate zu erstellen oder deine bestehenden Inserate zu löschen.</p>

      <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
        <div class="form-group">
          <label for="id_email">Email: </label>
          <input type="email" name="email" class="form-control" id="id_email" value="<?php if(isset($email)) echo $email; // Wenn es einen $email-Wert gibt, wird er angezeigt ?>">
        </div>
        <!-- Passwort und Passwortbestätigung müssen jedesmal neu eingegeben werden -->
        <div class="form-group">
          <label for="id_password">Passwort: </label>
          <input type="password" name="password" class="form-control" id="id_password">
        </div>
        <div class="form-group">
          <label for="id_password_confirm">Passwortbestätigung: </label>
          <input type="password" name="password_confirm" class="form-control" id="id_password_confirm">
        </div>
        <div class="form-group">
          <label for="id_firstname">Vorname: </label>
          <input type="text" name="firstname" class="form-control" id="id_firstname" value="<?php if(isset($firstname)) echo $firstname; // Wenn es einen $firstname-Wert gibt, wird er angezeigt ?>">
        </div>
        <div class="form-group">
          <label for="id_lastname">Nachname: </label>
          <input type="text" name="lastname" class="form-control" id="id_lastname" value="<?php if(isset($lastname)) echo $lastname; // Wenn es einen $lastname-Wert gibt, wird er angezeigt ?>">
        </div>
        <button type="submit" name="register_submit" class="btn btn-primary" value="einloggen">Registrieren</button>
      </form>


      <!-- optionale Nachricht (mit angepasster CSS) -->
<?php
  if(!empty($msg)){
    include('templates/msg.php');
  }
?>




    </section>
  </div>
</body>
</html>
