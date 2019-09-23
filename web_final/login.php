<?php
// ------------------- CONTROLLER -------------------
session_start(); // Start einer neuen, oder Weiterführung einer bestehenden Session
// Alle Site-relevanten Werte (base-url, DB-Einstellungen) sind in config.php zentral gespeichert.
require_once('system/config.php');
// Alle DB-Abfragen sind in data.php zusammengefasst.
require_once('system/data.php');
// Die Verwaltung der Session erledigen wir hier nicht über eine externe Datei, sondern direkt im Controller

// Wir prüfen, ob es eine Session-Variable $_SESSION['userid'] gibt.
if(isset($_SESSION['userid'])) {
  // Falls es eine solche Variable gibt, zerstören wir sie...
  unset($_SESSION['userid']);
  // ... und beenden gleich darauf die Session.
  // Ergebnis: Der User ist ausgeloggt.
  session_destroy();
}
// Die in templates/menu.php benötigten Variablen müssen wir im Controller setzen.
// (Diese Variablen werden sonst in templates/session_handler.php vergeben.)
$logged_in = false;
$log_in_out_text = "Anmelden";

// Wir prüfen, ob der User das Login-Formular abgeschickt hat.
// Nur dann versuchen wir den User mit den eingegebenen Daten einzuloggen.
// Ansonsten wird lediglich das leere Formular angezeigt.
//
// Im Array $_POST werden alle Daten übertragen, die von einem Formular mit der Methode POST übermittelt wurden.
// Der Bezeichner (hier: 'login_submit') entspricht dabei dem name-Attribut des jeweiligen Formularelements.
// In unserem Beispiel prüfen wir, ob es in dem abgeschickten Formular ein Element mit dem
//   name-Attribut 'login_submit' gibt.
//   <input ... name="login_submit" ...>
// Welchen Wert das <input>-Element hat, prüfen wir nicht.
// In unserem Beispiel handelt es sich um Anmelden-Schaltfläche.
if(isset($_POST['login_submit'])){

  // Wir führen eine ganz einfache Überpüfung der eingegebenen Daten durch.
  // Dabei prüfen wir nur, OB etwas eingegeben wurde
  // Zuerst setzen wir aber eine sog. Schalter-Variable ($login_submit_valid) auf den Wert true.
  // Dabei gehen wir erstmal davon aus, dass der User alles richtig eingibt.
  // Erst wenn er einen Fehler macht, also einen Wert nicht eingibt,
  //   setzen wir die Schaltervariable auf false.
  // Der eigentliche Login-Vorgang wird nur durchgeführt, wenn $login_submit_valid true ist.
  $login_submit_valid = true;

  // Sollte der User einen Fehler machen, sollten wir ihm auch mitteilen, was er falsch gemacht hat.
  // Dazu erzeugen wir zuerst die Variable $msg mit einer leeren Zeichenkette als Inhalt.
  // Sollte der User einen Fehler machen, hängen wir die entsprechende Fehlermeldung
  //   an die bestehende (jetzt noch leere) Zeichenkette an.
  // Wenn $msg nicht leer ist, geben wir den Inhalt im View aus.
  $msg = "";

  // Jetzt prüfen wir, ob der User einen Wert in
  //   <input ... name="email" ...> eingegeben hat.
  if(!empty($_POST['email'])){
    // Wenn das der Fall ist, speichern wir den Inhalt in der Variablen $email.
    $email = $_POST['email'];
  }else{
    // Andernfalls hängen wir eine Fehlermeldung an die Variable $msg an.
    // Das Anhängen erfolgt durch den Punkt (.) vor dem Gleichheitszeichen (=).
    // Der Punkt ist der sog. Verkettungsopperator.
    $msg .= "Bitte gib deine E-Mailadresse  ein.<br>";
    // Ohne $email kann der User nicht eingeloggt werden. Daher setzen wir die
    //    Schalter-Variable ($login_submit_valid) auf den Wert false.
    $login_submit_valid = false;
  }

  // Für <input ... name="password" ...> gehen wir analog der email-Eingabe vor.
  if(!empty($_POST['password'])){
    $password = $_POST['password'];
  }else{
    $msg .= "Bitte gib dein Passwort ein.<br>";
    $login_submit_valid = false;
  }

  // Wenn $login_submit_valid noch immer true ist, können wir versuchen den User einzuloggen.
  if($login_submit_valid){

    // Die eingegebenen Daten des Users werden durch unsere login()-Funktion
    //   aus system/data.php mit der Datenbank abgeglichen.
    // Die Funktion liefert entweder die vollständigen Userdaten als assoziatives Array,
    //   oder den Wert false zurück.
    // Die Bezeichner des Assoziativen Arrays sind die Namen der Datenbankspalten der Tabelle user.
    // Wir speichern das Ergebnis in $result.
    $result = login($email , $password);   // Siehe system/data.php

    // Eine if()-Bedingung prüft nur, ob Bdingung in den Klammern false, bzw. 0 ist.
    // Alle anderen Werte werden als true angesehen.
    // Wenn also Userdaten in $result gespeichert sind, ist die Bedingung erfüllt.
    if($result){
      // Wir speichern den Inhalt von $result in der Variablen $user.
      // Dieser Schritt ist technisch überflüssig. Er dient lediglich dem
      //   besseren Verständnis und der besseren Lesbarkeit.
      $user = $result;

      // In einer Session können Daten kurzzeitig gespeichert werden, um sie bei
      //   nachfolgenden Seitenaufrufen wieder abzurufen.
      // $_SESSION ist dabei ein assoziatives Array, deren Bezeichner wir selbst vergeben können.
      // Wir speichern die $user['id'] aus der DB ni der Session unter dem Bezeichner 'userid'.
      // Auf nachfolgenden Seiten können wir diesen Wert mit $_SESSION['userid'] wieder abrufen.
      // Ist dieser Wert vorhanden, ist der User eingeloggt, anderenfalls wird er als
      //   anonymer User behandelt und kann keine Inserate aufgeben, bearbeiten oder löschen.
      //
      // Siehe: https://www.php-einfach.de/php-tutorial/php-sessions/
      $_SESSION['userid'] = $user['id'];

      // Hiermit leiten wir den User auf index.php weiter.
      header('Location: index.php');
      // Danach quittieren wir die weitere Abarbeitung des PHP Programmes mit exit;,
      //   weil sonst noch weitere Programmteile abgearbeitet werden würden.
      exit;

    }else{
      $msg = "Die Benutzerdaten sind nicht in unserer Datenbank vorhanden.";
    }
  }
}
// ---------------------- VIEW ----------------------
?>
<!DOCTYPE html>
<html>
<head>
<?php
  $page_head_title = "MMP Ricardo - Login";
  include_once('templates/page_head.php');
?>
</head>
<body class="py-5">
  <!-- Inhalt mit Navigation -->
  <div class="container">

<?php include_once('templates/menu.php') ?>

    <section class="content">
      <h1>Anmelden</h1>
      <p>Bitte melde dich an, um neue Inserate zu erstellen oder deine bestehenden Inserate zu löschen.</p>

      <!-- FORM
        Das <form>-Element umschliesst alle Formularfelder, deren Wert zum auswwrtenden Skript geschickt werden sollen.
        Im action-Attribut des <form>-Elements steht die Adresse Skripts, das den Formularinhalt auswertet,
          in unserem Fall ist das login.php, also eben diese Seite, in dem sich auch das Formular befindet.
        Das method-Attribut enthät die Methode, mit der der Inhalt der Formularfelder verschickt wird.
          POST: Werte werden unsichtbar, !!!!! ABER NICHT VERSCHLÜSSELT !!!!! verschickt.
          GET:  Wetre werden an die URL angehängt.

        Siehe: https://wiki.selfhtml.org/wiki/HTML/Formulare/form
        Siehe: https://www.w3schools.com/html/html_forms.asp
      -->
      <form action="<?php echo $_SERVER['PHP_SELF']; // $_SERVER['PHP_SELF'] gibt die Adresse der aktuellen Datei aus ?>" method="post">
        <div class="form-group">
          <!-- LABEL
            Siehe: https://wiki.selfhtml.org/wiki/HTML/Formulare/label
            Siehe: https://www.w3schools.com/tags/tag_label.asp
          -->
          <label for="id_email">E-Mail: </label>
          <!-- INPUT
            Siehe: https://wiki.selfhtml.org/wiki/HTML/Formulare/input
            Siehe: https://www.w3schools.com/tags/tag_input.asp
            type-Attribut
            Siehe: https://www.w3schools.com/tags/att_input_type.asp
            Siehe: https://www.w3schools.com/html/html_form_input_types.asp
          -->
          <input type="email" name="email" class="form-control" id="id_email">
        </div>
        <div class="form-group">
          <label for="id_password">Passwort: </label>
          <input type="password" name="password" class="form-control" id="id_password">
        </div>
        <button type="submit" name="login_submit" class="btn btn-primary" value="einloggen">Anmelden</button>
      </form>

      <!-- optionale Nachricht -->
<?php if(!empty($msg)){ ?>
      <div class="alert alert-info msg" role="alert">
        <p><?php echo $msg ?></p>
      </div>
<?php } ?>


    </section>
  </div>
</body>
</html>
