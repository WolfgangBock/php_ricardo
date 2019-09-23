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

// Die Validierung der Eingaben erfolgt in Weiten Teilen analog zu newproduct.php
// Um es einfacher zu machen, verzichten wir auf die Möglichkeit das Bild zu aktualisieren.
if(isset($_POST['item_submit'])){ // Wenn die Seite über das Formular aufgerufen wurde

  // Wir speichern die item_id, um mit ihr
  //      NACH DER AKTUALISIERUNG
  //die Daten zum Inserat aus der DB abzurufen.
  $item_id = $_POST['item_id'];

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
    $price = floatval($_POST['price']);
  }else{
    $price = floatval(0);
  }

  // Daten in die Datenbank schreiben ******************************************************

  if($inserat_submit_valid){

    // Mit der update_item()-Funktion aus system/data.php aktualisieren wir alle Angaben zum Item (nicht zu den Kategorien).
    // Die update_item()-Funktion gibt true oder false zurück.
    $has_updated = update_item( $title, $description, $price, $type, $item_id);
    var_dump($has_updated);
    if($has_updated){ // Wenn das Item erfolgreich aktualisiert wurde ...
      // ... löschen wir zuerst alle zum Item gehörenden Kategorien
      //     mit der delete_category_for_item() aus system/data.php ...
      delete_category_for_item($item_id);
      // ... und speichern die Item-Kategorie-Kombinationen in einer foreach()-Schleife in der Datenbank
      foreach ($categories as $category) {
        update_item_category($item_id, $category);
      }
    }
    if($has_updated){
      $msg = "Das Inserat wurde erfolgreich gespeichert.</br>";
    }else{
      $msg .= "Es gibt ein Problem mit der Datenbankverbindung.</br>";
    }
  }

}else{ // Wenn die Seite NICHT über das Formular aufgerufen wurde
  // Wir prüfen, ob beim Aufruf der Seite der URL eine Variable 'id' mitgegeben wurde.
  if(isset($_GET['id'])){
    // Wir speichern die item_id, um mit ihr die Daten zum Inserat aus der DB abzurufen.
    $item_id = $_GET['id'];
  }

  if(isset($item_id)){
    // Wir den item-Datensatz aus der DB ab und speichern ihn in der Variablen $item.
    $item = get_item_by_id($item_id);

    // Wir speichern alle aktualisierbaren Werte in eigenen Variablen ab,
    //  um sie im View an den entsprechenden Stellen ins Formular einzufügen,
    //   bzw. die richtigen radio-buttons (Inserat-Typ) und checkboxen (Kategorien) zu aktivieren.
    $type = $item['type'];
    $categories = get_categories_by_id($item['id']);
    $title = $item['title'];
    $description = $item['description'];
    $price = $item['price'];
  }
}
// ---------------------- VIEW ----------------------
?>
<!DOCTYPE html>
<html>
<head>
  <?php
  $page_head_title = "MMP Ricardo – Inserat bearbeiten";
  include_once('../templates/page_head.php');
  ?>
</head>
<body class="py-5">

  <!-- Inhalt mit Navigation -->
  <div class="container">

<?php include_once('../templates/menu.php') ?>

    <section class="content">
      <h1>Inserieren</h1>
      <p>Hier kannst du dein Inserat bearbeiten.</p>

      <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
        <input type="hidden" name="item_id" value="<?php echo $item_id ?>">
        <h4>Typ</h4>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="itemtype" id="angebot" value="1" <?php if($type == 1) echo "checked"; // Bedingung zum schreiben des checked-Attributs ?>>
          <label class="form-check-label" for="angebot">
            Angebot
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="itemtype" id="anfrage" value="2" <?php if($type == 2) echo "checked"; // Bedingung zum schreiben des checked-Attributs ?>>
          <label class="form-check-label" for="anfrage">
            Anfrage
          </label>
        </div>

        <h4>Kategorie</h4>
<?php
	// Kategorien werden ausgelesen - pro Kategorie wird ein "option" Element für das Auswahlfeldes erstellt
  $cat_nr = 0;
  foreach ($all_categories as $cat) {
    $cat_id = "cat".$cat_nr;
?>
        <div class="form-check">
          <input  class="form-check-input"
                  type="checkbox"
                  name="category[]"
                  id="<?php echo $cat_id ?>"
                  value="<?php echo $cat; ?>"
                  <?php if(in_array($cat, $categories)) echo "checked"; // Bedingung zum schreiben des checked-Attributs ?>>
          <label class="form-check-label" for="<?php echo $cat_id ?>">
            <?php echo $cat; ?>
          </label>
        </div>
<?php
    $cat_nr ++;
  }
?>




        <div class="form-group">
          <label for="item_title">Titel: </label>
          <input type="text" name="title" class="form-control" id="item_title" value="<?php echo $title; // schreiben des aktuellen Wertes ins value-Attribut ?>">
        </div>

        <div class="form-group">
          <label for="item_description">Beschreibung: </label>
          <textarea type="text" name="description" class="form-control" id="item_description"><?php echo $description; // schreiben des aktuellen Wertes ins value-Attribut ?></textarea>
        </div>

        <div class="form-group">
          <label for="item_price">Preis: </label>
          <input type="number" name="price" class="form-control" id="item_price" step=".01" value="<?php echo $price; // schreiben des aktuellen Wertes ins value-Attribut ?>">
        </div>

        <button type="submit" name="item_submit" class="btn btn-danger" value="inserieren">Text aktualisieren</button>
        <a href="delete.php?item=<?php echo $item['id']; ?>"  <button type="button" class="btn btn-warning">Inserat löschen</button></a>
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
  </body>
  </html>
