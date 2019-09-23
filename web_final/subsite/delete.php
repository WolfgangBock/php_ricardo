<?php
// ------------------- CONTROLLER -------------------
// Sessionverwaltung und laden der Konfiguration sowie aller DB-Funktionen.
// genauere Beschreibung siehe index.php
session_start();
require_once('../system/config.php');
require_once('../system/data.php');
require_once('../templates/session_handler.php');

// Initialisierung der Variablen $msg
$msg = "";

// Initialisierung der Variablen $item_deleted
// Beim erstmaligen Laden der Seite wird das zu löschende Item angezeigt werden,
//   bevor der User die Löschung endgültig bestätigt.
// Wurde das Item gelöscht, wird nur noch eine entsprechende Nachricht angezeigt.
// (Das Item selbst kann nicht mehr angezeigt werden, da es aus der DB gelöscht wurde.)
// Wir setzen zuerst $item_deleted = false.
// Erst wenn wir von der Lösch-Funktion aus data.php (delete_item()) die Bestätigung
//   über die Löschung erhalten haben, setzen wir $item_deleted = true.
$item_deleted = false;

// Wir prüfen, ob beim Aufruf der Seite der URL eine Variable 'item' mitgegeben wurde.
if(isset($_GET['item'])){
  // Wenn es die Variable 'item' gibt, speichern wir deren Wert in der PHP-Variablen $item_id
  $item_id = $_GET['item'];
}
// Andernfalls überprüfen wir, ob eine 'item'-Variable per POST mitgeschickt wurde.
// Das ist der Fall, wenn der User im Formular der Seite auf "Inserat endgültig löschen" klickt.
else if(isset($_POST['item']))
{
  //$item_id = $_POST['item'];
  // Die delete_item()-Funktion aus system/data.php lösch alle Daten,
  //   die mit dem Item in Zusammenhang stehen.
  $item_deleted = delete_item(intval($_POST['item']));
}

// Wenn das Item (Inserat) noch nicht gelöscht wurde, laden wir den entsprechenden
//   Datensatz aus der Datenbank.
// Die Vorgehensweise ist dabei identisch zu details.php.
if(!$item_deleted){
  $item = get_item_by_id($item_id);
  $item_user = get_user_by_id($item['user_id']);

  if($item['type'] == 1){
    $type = "Angebot";
  }else{
    $type = "Anfrage";
  }

  $categories = get_categories_by_id($item['id']);
  $categoy_text = '';
  foreach ($categories as $category) {
    $categoy_text .= ' <span class="badge badge-primary"> '.$category .'</span> ';
  }

  $date = new DateTime($item['insert_time']);
} else { // Andernfalls, wenn das Item (Inserat) also gelöscht wurde ...
  // ... erstellen wir eine Nachricht, die wir im View ausgeben.
  $alert_type = "alert-success";
  $msg = "Das Inserat wurde erfolgreich gelöscht.";
}
// ---------------------- VIEW ----------------------
?>
<!DOCTYPE html>
<html>
<head>
<?php
$page_head_title = "MMP Ricardo – Inserat löschen";
include_once('../templates/page_head.php');
?>
</head>
<body class="py-5">
  <!-- Inhalt mit Navigation -->
  <div class="container">

<?php
include_once('../templates/menu.php');

// Nur wenn das Item noch NICHT gelöscht wurde, wird der Datensatz in HTML ausgegeben.
// Ohne diese Bedingung gäbe es eine Fehlermeldung.
if(!$item_deleted){
?>

    <section class="content">
        <h2><?php echo $item['title']; ?></h2>
        <p><?php echo nl2br($item['description']); ?></p>
<?php if(!empty($item['price'])) { ?>
	      <p class="preis"><strong>Preis:</strong> <?php echo $item['price']; ?> CHF</p>
<?php } else { ?>
	      <p class="preis"><strong>Preis:</strong> auf Anfrage</p>
<?php }?>
        <p><strong>inseriert von:</strong> <?php echo $item_user['firstname']." ".$item_user['lastname']; ?></p>
        <p class="tag">
            <span><strong>Inserattyp:</strong> <?php echo $type; ?></span> |
            <span><strong>Kategorie:</strong> <?php echo $categoy_text; ?> </span> |
            <span><strong>Datum:</strong> <?php echo $date->format('d.m.Y'); ?></span>
        </p>
<?php if(!empty($item['img'])) { ?>
	      <img src="../item_img/<?php echo $item['img']; ?>" class="img-thumbnail" alt="<?php echo $item['title']; ?>" width="200">
<?php }?>
    </section>

<?php
  // Der Code für den Löschen-Button innerhalb der if()-Bedingung wird nur ausgegeben,
  //   wenn es die Variable $user_id gibt ...
  //   ... und $user_id mit $item['user_id'] (ID des Users, der das item erstellt hat) übereinstimmt.
  // Diese Sicherheitsstufe sollte nicht nötig sein, da ja schon vorher der Link auf
  //   delete.php nur ebendiesen Bedingungen angezeigt wird.
  // Wir verhindern so jedoch, dass User die zufällig, oder durch Ausprobieren iuaf diesen Link stossen
  //   das Item (Inserat) löschen können.
  if(isset($user_id) && $user_id == $item['user_id']) {
?>
    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
      <!--
        Da wir das Formular aus Sicherheitsgründen mit der POST-Methode übertragen,
          müssen wir die item_id mit dem Formular übertragen und können es nicht einfach an die URL anhängen.
        Ein <input type="hidden" ...> wird im Browser nicht angezeigt,
          der Wert (value-Attribut) wird jedoch übertragen.
        Der Bezeichner ist der Inhalt des name-Attributs.
        Wir könne bei der Auswertung in PHP den Wert also mit $_POST['item'] auslesen.
      -->
      <input type="hidden" name="item" value="<?php echo $item_id; ?>">
	    <button type="submit" class="btn btn-danger btn-lg">Inserat endgültig löschen</button>
    </form>
<?php
  }
}
if(!empty($msg)){
  include('../templates/msg.php');
}
?>
  </div>

<?php include_once('../templates/footer.php') ?>
</body>
</html>
