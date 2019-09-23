<?php
// ------------------- CONTROLLER -------------------
// Sessionverwaltung und laden der Konfiguration sowie aller DB-Funktionen.
// genauere Beschreibung siehe index.php
session_start();
require_once('../system/config.php');
require_once('../system/data.php');
require_once('../templates/session_handler.php');

// Wir benötigen alle Kategorien, damit der User aus der Liste alle
//   zum Inserat passenden Kategorien auswählen kann.
// Die get_all_categories()-Funktion aus system/data.php liefert alle Kategorien als Array zurück.
// Wir speichern das Array in $all_categories.
$all_categories = get_all_categories();

$msg = ""; // Initialisierung von $msg
$inserat_submit_valid = true; // Initialisierung der Schaltervariable

// Die Validierung der Eingaben erfolgt in Weiten Teilen analog zu register.php
// Nachfolgend sind nur die Unterschiede kommentiert!
if(isset($_POST['item_submit'])){ // Wenn die Seite über das Formular aufgerufen wurde

  if(!empty($_SESSION['userid'])){
    $user_id = $_SESSION['userid'];
  }else{
    $inserat_submit_valid = false;
  }

  if(!empty($_POST['itemtype'])){
    $type = $_POST['itemtype'];
  }else{
    $msg .= "Bitte wähle einen Inserattyp aus<br>";
    $inserat_submit_valid = false;
  }

  if(!empty($_POST['category'])){
    // Der Inhalt von $_POST['category'] ist ein numerisches Array,
    //   dessen Inhalt wir in $categories speichern.
    $categories = $_POST['category'];
  }else{
    $msg .= "Bitte wähle mindestens eine Kategorie aus.<br>";
    $inserat_submit_valid = false;
  }

  if(!empty($_POST['title'])){
    $title = $_POST['title'];
  }else{
    $msg .= "Bitte gib einen Titel ein.<br>";
    $inserat_submit_valid = false;
  }

  if(!empty($_POST['description'])){
    $description = $_POST['description'];
  }else{
    $msg .= "Bitte gib eine Beschreibung ein.<br>";
    $inserat_submit_valid = false;
  }

  if(!empty($_POST['price'])){
    // Der Preis-Wert hat type="number".
    // Nachkommawerte müssen dort mit einem Komma eingegeben werden.
    // Formularwerte werden standardmässig (so auch hier) als String (Zeichenkette) übertragen.
    // Da wir in der DB für die Wert price als Typ decimal(10,2) definiert haben, würde die
    //   Übertragung des String-Werts zu einer Fehlermeldung führen.
    // Die PHP-Funktion floatval() wandelt diesen String in einn float-Wert (Flisskommazahl) um.

    $price = floatval($_POST['price']);
  }else{
    $price = floatval(0);
  }

  // Bildupload Vorbereitung
  // Das input-Feld für den Upload hat als name-Attribut name="item_img".
  // Alle Angaben zum Bild werden in $_FILES['item_img'] übertragen,
  //   wobei 'item_img' der Wert des name-Attributs des <input>-Elements ist,
  //   das dei Datei überträgt.

  // $_FILES['item_img']['name']       Dateiname der Uploaddatei.
  // $_FILES['item_img']['size']       Dateigrösse der Uploaddatei Bytes
  // $_FILES['item_img']['tmp_name']   aktueller Dateiname der Uploaddatei.
  //   Beim Hochladen wird die Datei zuerst temporär in einem eigenen Verzeichnis zwischengespeichert.
  //   Wir wissen zwar nicht, wo die Datei genau gespeichert ist (eine Servereinstellung),
  //     wir können jedoch über $_FILES['item_img']['tmp_name'] den aktuellen Dateinamen auslesen und nutzen.

  if(!empty($_FILES['item_img']['name']) && $inserat_submit_valid){
    $upload_folder = '../item_img/'; //Das Upload-

    // Die pathinfo()-Funktion liefert Informationen über einen Dateipfad
    // pathinfo($_FILES['item_img']['name'], PATHINFO_FILENAME) gibt den reinen Dateinamen,
    //   ohne den Pfad oder die Extension zurück.
    $filename = pathinfo($_FILES['item_img']['name'], PATHINFO_FILENAME);

    // pathinfo($_FILES['item_img']['name'], PATHINFO_EXTENSION) gibt nur die Extension zurück.
    // Wir ermitteln die Dateinamenerweiterung (extension) mit pathinfo($_FILES['item_img']['name'], PATHINFO_EXTENSION),
    // Siehe: https://www.php.net/manual/de/function.pathinfo.php
    // Die strtolower()-Funktion wandelt eine Zeichenkette in Kleinbuchstaben um.
    // So können wir die vorhandene extension mit einer Liste erlaubter extensions vergleichen
    $extension = strtolower(pathinfo($_FILES['item_img']['name'], PATHINFO_EXTENSION));
    // In $allowed_extensions speichern wir die Liste erlaubter extensions ...
    $allowed_extensions = array('png', 'jpg', 'jpeg', 'gif');
    // ... und vergleichen sie mit der in_array()-Funktion
    //   den Wert von $extension mit dem Inhalt mit jedem Ellement aus $allowed_extensions.
    // Wenn $extension NICHT (!) in $allowed_extensions enthalten ist ...
    if(!in_array($extension, $allowed_extensions)) {
      // ... erzeugen wir eine Fehlermeldung ...
      $msg .= "Ungültige Dateiendung";
      // ... und setzen die Schaltervariable $inserat_submit_valid auf false
      $inserat_submit_valid = false;
    }

    // Eine Extension kann jeder schreiben (fälschen).
    // Es ist kein sicherer Schutz vor ausführbaren Dateien.
    // Wenn der Server die Funktion freigeschaltet hat, können wir den Bildtyp
    //   mit der exif_imagetype()-Funktion zuverlässiger prüfen.
    // Dabei liesst php die ersten Bytes der Bilddatei und prüft die dort gespeicherte Signatur.
    // Siehe: https://www.php.net/manual/de/function.exif-imagetype.php

    // Zuerst speichern wir wieder die erlaubten Types in einem Array ...
    $allowed_types = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
    // ... und ermitteln dann exif_imagetype() den Type der Datei.
    $detected_type = exif_imagetype($_FILES['item_img']['tmp_name']);
    // Die exif_imagetype()-Funktion benötigt den Dateinamen.


    // Der Vergleich des Image_Types verläuft analog zum Vergleich der Extension.
    if(!in_array($detected_type, $allowed_types)) {
      $msg .= "Nur der Upload von Bilddateien ist gestattet";
      $inserat_submit_valid = false;
    }

    // Beschränkung der Dateigrösse
    // In $_FILES['item_img']['size'] ist die Dateigrösse der Uploaddatei Bytes gespeichert.
    // 1 Kilobyte (KB) = 1024 Bytes
    // 500 KB = 500 * 1024 Bytes
    $max_size = 500*1024; //500 KB
    if($_FILES['item_img']['size'] > $max_size) {
      $msg .= "Bitte keine Dateien größer 500kb hochladen";
      $inserat_submit_valid = false;
    }

    $temporary_name = $_FILES['item_img']['tmp_name'];
    $extension = pathinfo($_FILES['item_img']['name'], PATHINFO_EXTENSION);

    //Pfad zum Upload
    $full_img_name_path = $upload_folder.$filename.'.'.$extension;
    // Neuer Dateiname für die Speicherung (ohne Pfad)
    $full_img_name = $filename.'.'.$extension;
    //Neuer Dateiname falls die Datei bereits existiert
    if(file_exists($full_img_name_path)) { //Falls Datei existiert, hänge eine Zahl an den Dateinamen
      $id = 1;
      // Die do-while()-Schleife wird sicher einmal durchlaufen.
      // Nach jedem Durchlauf findet eine erneute Prüfung statt.
      do {
        // Zusammensetzen des neuen Upload-Pfades mit Zahl
        $full_img_name_path = $upload_folder.$filename.'_'.$id.'.'.$extension;
         // Zusammensetzen des neuen Dateinamens mit Zahl
        $full_img_name = $filename.'_'.$id.'.'.$extension;
        $id++; // Hochsetzen des Zählers
      } while(file_exists($full_img_name_path)); // erneute Prüfung
    }

  }
  /* Wir haben geprüft,
    - ob in alle Felder etwas eingegeben wurde
    - ob die Uploaddatei die richtige Dateiendung hat
    - ob die Uploaddatei eine Bilddatei ist
    - ob die Uploaddatei die Grössenbeschränkung nicht überschreitet
    Wir haben einen einmaligen Bildnamen erzeugt.
  */
  // Daten in die Datenbank schreiben ******************************************************

  if($inserat_submit_valid){

    if(!empty($full_img_name_path)){ // Wenn es einen Upload-Pfad gibt ...
      // ... bewege die Datei vom temporären Speicherplatz ...
      // ... mit dem festgelegten Namen zum endgültigen Speicherplatz
      move_uploaded_file($temporary_name, $full_img_name_path);
    }else{
      $full_img_name = null;
    }

    // Mit der save_item()-Funktion aus system/data.php speichern wir alle Angaben zum Item ab (nicht zu den Kategorien).
    // Die save_item()-Funktion gibt die ID des zuletzt neuen Datensatzes zurück.
    $item_id = save_item($user_id, $title, $description, $price, $type, $full_img_name);
    if($item_id){ // Wenn das Item gespeichert wurde ...
      // ... durchlaufen wir das Array $categories, in dem alle ausgewählten Kategorien gespeichert sind ...
      foreach ($categories as $category) {
        // ... und speichern die Item-Kategorie-Kombination in der Datenbank
        //     mit der save_item_category()-Funktion aus system/data.php ab.
        save_item_category($item_id, $category);
      }
    }
    if($item_id){
      // Erfolsnachricht
      $msg = "Das Inserat wurde erfolgreich gespeichert.</br>";
    }else{
      // Fehlermeldung
      $msg .= "Es gibt ein Problem mit der Datenbankverbindung.</br>";
    }
  }

}
// ---------------------- VIEW ----------------------
?>

<!DOCTYPE html>
<html>
<head>
  <?php
  $page_head_title = "MMP Ricardo – neues Inserat";
  include_once('../templates/page_head.php');
  ?>
</head>
<body class="py-5">
  <!-- Inhalt mit Navigation -->
  <div class="container">

<?php include_once('../templates/menu.php') ?>

    <section class="content">
      <h1>Inserieren</h1>
      <p>Hier kannst du dein Inserat verfassen.</p>

      <!-- !!!!!!!!
        Um mit einem Formular eine Datei hochladen zu können, müssen wir ein weiteres Attribut zum <form>-Element hinzufügen.
        Das enctype-Attribut muss den Wert "multipart/form-data" haben.
      !!!!!!! -->
      <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">
        <h4>Typ</h4>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="itemtype" id="angebot" value="1">
          <label class="form-check-label" for="angebot">
            Angebot
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="itemtype" id="anfrage" value="2">
          <label class="form-check-label" for="anfrage">
            Anfrage
          </label>
        </div>

        <h4>Kategorie</h4>
<?php
// ---------- Kategorien setzen
// Im Controller haben wir alle vorhandenen Kategorien aus der DB in das Array $all_categories geschrieben.
// Für jedes Element dieses Arrays, also für jede Kategorie durchlaufen wir in einer foreach()-Schleife das Array einmal ...
// ... und erzeugen für jede Kategorie ein Kontrollkästchen (<input type="checkbox" ...>) mit dem datzgehörenden <label>-Element.
// Bei sechs Kategorien durchlaufen wir das Array also sechs mal.
// !!!! Besonderheit beim name-Attribut der Kategorie-Kontrollkästchen in diesem Formular. !!!!
// !!!! Bei allen Kategorie-Kontrollkästchen in diesem Formular ist name="category[]"
// !!!! Durch die eckigen Klammern [] hinter am Ende des name-Werts werden die values aller
//      ausgewählten Kontrollkästchen als numerisches Array mit im Formular übertragen.
//      So können innerhalb eines Formulares zusammengehörende Werte gebündelt übertragen werden.
//      Zusammengehörende Werte wie die Kategorien sind so wesentlich leichter auszulesen.
//      Gleichzeitig müssen wir uns keine Gedanken über die individuelle Namensgebung für jedes Kontollkästchen machen.
// Bei einem <label>-Element bezieht sich das for-Attribut direkt auf das id-Attribut im zugehörigen <input>-Element.
// Beide Attribute müssen den selben Wert haben.
// Diesen Wert erzeugen wir ebenfalls bei jedem Schleifendurchlauf.
// Dazu hängen wir an eine Zeichenkette lediglich eine Zählervariable (integer) an, deren Wert wir bei jedem Schleifendurchlauf um 1 erhöhen.
$i= 0; // Initialisierung der Zählervariablen
foreach ($all_categories as $categorie) {
  $cat_id = "cat".$i; // Anhängen der Zählervariablen $i an die Zeichenkette "cat", gespeichert in der Variablen $cat_id
?>
        <div class="form-check">
          <input  class="form-check-input"
                  type="checkbox" name="category[]"
                  id="<?php echo $cat_id ?>"
                  value="<?php echo $categorie; ?>" >
          <label class="form-check-label" for="<?php echo $cat_id ?>">
            <?php echo $categorie; ?>
          </label>
        </div>
<?php
  $i ++;
}
?>
        <div class="form-group">
          <label for="item_title">Titel: </label>
          <input type="text" name="title" class="form-control" id="item_title">
        </div>

        <div class="form-group">
          <label for="item_description">Beschreibung: </label>
          <textarea type="text" name="description" class="form-control" id="item_description"></textarea>
        </div>

        <div class="form-group">
          <label for="item_price">Preis: </label>
          <input type="number" name="price" class="form-control" id="item_price" step=".01">
        </div>

        <div class="form-group">
          <div class="custom-file">
            <input type="file" class="custom-file-input" id="prod_img" name="item_img">
            <label class="custom-file-label" for="prod_img">Durchsuchen...</label>
          </div>
        </div>

        <button type="submit" name="item_submit" class="btn btn-success" value="inserieren">Inserieren</button>
      </form>

        <!-- optionale Nachricht -->
<?php
if(!empty($msg)){
  include('../templates/msg.php');
}
?>

      </section>
    </div>

    <?php include_once('../templates/footer.php') ?>

    <!--
      Für die Darstellung des Dateiuploads benötigt Bootstrap einige JavaScript-Komponenten.
      Diese haben auf den eigentlichen Upload-Vorgang KEINEN Einfluss.
    -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
    <script>
      $(document).ready(function () {
        bsCustomFileInput.init()
      })
    </script>
  </body>
  </html>
