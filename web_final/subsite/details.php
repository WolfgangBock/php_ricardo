<?php
// ------------------- CONTROLLER -------------------
// Sessionverwaltung und laden der Konfiguration sowie aller DB-Funktionen.
// genauere Beschreibung siehe index.php
session_start();
require_once('../system/config.php');
require_once('../system/data.php');
require_once('../templates/session_handler.php');


// Wir prüfen, ob beim Aufruf der Seite der URL eine Variable 'item' mitgegeben wurde.
if(isset($_GET['item'])){

  // Die get_item_by_id()-Funktion aus system/data.php holt alle Daten eines Items zu einer
  //   bestimmten item_id aus der DB-Tabelle 'items'.
  // Wir speichern den Datensatz in der php-Variablen $item.
  $item = get_item_by_id($_GET['item']);

  // In $item ist nur die ID des Users gespeichert, der das Inserat aufgegeben hat ($item['user_id']).
  // Wir holen mit get_user_by_id()-Funktion aus system/data.php den gesamten Datensatz
  //   zu dieser ID aus der Tabelle 'users';
  $item_user = get_user_by_id($item['user_id']); //

  /* ---------- DB-Wert type (integer) in eine lesbare Zeichenkette "übersetzen" ----------
  /* In der Datenbank-Tabelle items steht in der Spalte type für ein Angebot eine 1, für eine Anfrage eine 2.
  /* Um es für den User lesbar zu machen, müssen wir es zurückübersetzen.
  /* Den Inhalt von $type zeigen wir später an.
  */
  if($item['type'] == 1){
    $type = "Angebot";
  }else{
    $type = "Anfrage";
  }

  /* ---------- Kategorie(en) für das aktuelle Inserat (Item) aus der Datenbank auslesen  ----------
  /* Da jedes Produkt mehr als eine Kategorie haben kann,
  /*    sind diese in einer separaten Tabelle (item_has_category) gespeichert.
  /* get_categories_by_id() holt die Kategorien für ein item mit einer bestimmten ID aus der DB
  /*   und gibt ein Array (imdiziert) mit diesen Kategorien zurück.
  */
  $categories = get_categories_by_id($item['id']); // Name der Kategorie auslesen
  $categoy_text = '';
  /* Wir durchlaufen das Array $categories mit einer foreach()-Schleife
  /*   und speichern den Inhalt als HTML in der Variable $categoy_text.
  */
  foreach ($categories as $category) {
    $categoy_text .= ' <span class="badge badge-pill badge-primary"> '.$category .'</span> ';
  }

  /* ---------- DB-Wert insert_time (timestamp) in ein Date-Objekt transformieren  ----------
  /* Die insert_time (Tabelle items) ist im Timestamp-Format gespeichert (Bsp: 2019-07-11 10:35:54).
  /* new DateTime() kreiert ein Date-Objekt.
  /* Später passen wir das Format an und geben das Datum aus.
  */
  $date = new DateTime($item['insert_time']);
}
// ---------------------- VIEW ----------------------
?>
<!DOCTYPE html>
<html>
<head>
<?php
$page_head_title = "MMP Ricardo - Details";
include_once('../templates/page_head.php');
?>
</head>
<body class="py-5">
  <!-- Inhalt mit Navigation -->
  <div class="container">

<?php include_once('../templates/menu.php') ?>


    <section class="content">
        <h2><?php echo $item['title']; // Ausgabe des Titels ?></h2>
        <!-- Ausgabe der Beschreibung
          In der DB kann ein mehrzeiliger Text mit Textumbrüchen gespeichert werden.
          Umbrüche werden in der DB anders gespeichert (newline –> nl) als in HTML benötigt (<br>).
          Die php-Funktion nl2br() wandelt die DB-Textümbrüche zu HTML-Textumbrüche um. (sehr praktisch)
        -->
        <p><?php echo nl2br($item['description']); ?>
        </p>
<?php if(!empty($item['price'])) { // Wenn ein Preis angegeben wurde, wird er angezeigt ?>
	      <p class="preis"><strong>Preis:</strong> <?php echo $item['price']; ?> CHF</p>
<?php } else { ?>
	      <p class="preis"><strong>Preis:</strong> auf Anfrage</p>
<?php }?>
        <p><strong>inseriert von:</strong> <?php echo $item_user['firstname']." ".$item_user['lastname']; ?></p>
        <p class="tag">
            <span><strong>Inserattyp:</strong> <?php echo $type; ?></span> <br>
            <span><strong>Kategorie:</strong> <?php echo $categoy_text; // Ausgabe des $categoy_text aus dem Controller?> </span> <br>
            <span><strong>Datum:</strong> <?php echo $date->format('d.m.Y'); // https://www.php.net/manual/de/datetime.format.php ?></span>
        </p>
<?php if(!empty($item['img'])) { // Wenn ein Bild gibt, wird es angezeigt ?>
	      <img src="../item_img/<?php echo $item['img']; ?>" class="img-thumbnail" alt="<?php echo $item['title']; ?>" width="200">
<?php }?>
    </section>
<?php
  // Der Code für den Bearbeiten- und den Löschen-Button innerhalb der if()-Bedingung wird nur ausgegeben,
  //   wenn es die Variable $user_id gibt ...
  //   ... und $user_id mit $item['user_id'] (ID des Users, der das item erstellt hat) übereinstimmt.

  // Beschreibung der Buttons siehe index.php
  if(isset($user_id) && $user_id == $item['user_id']) {
?>
	      <a href="edit.php?id=<?php echo $item['id']; ?>"  <button type="button" class="btn btn-warning">Text bearbeiten</button></a>
        <a href="delete.php?item=<?php echo $item['id']; ?>"  <button type="button" class="btn btn-warning">Inserat löschen</button></a>
<?php }?>
  </div>

<?php include_once('../templates/footer.php') ?>
</body>
</html>
