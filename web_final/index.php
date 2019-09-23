<?php
// ------------------- CONTROLLER -------------------
session_start(); // Start einer neuen, oder Weiterführung einer bestehenden Session
// Alle Site-relevanten Werte (base-url, DB-Einstellungen) sind in config.php zentral gespeichert.
require_once('system/config.php');
// Alle DB-Abfragen sind in data.php zusammengefasst.
require_once('system/data.php');
// Die Verwaltung der Session wiederholt sich auf allen Seiten (ausser login und register)
//   und kann daher in einer zentalen Datei zusammengefasst werden.
require_once('templates/session_handler.php');

/* ************ Daten mit GET übertragen und in PHP auslesen ************************
/*
/* Wir können in HTML Daten mit Hilfe der sog. GET-Methode übertragen.
/* Dabei werden die Daten an die URL der Seite angehängt.
/* Der Datenteil der URL beginnt mit einem Fragezeichen (?).
/* Darauf folgt ein frei definierbarer Variablenname, ein Gleichheitszeichen (=)
/*   und der Wert, den die Variable haben soll.
/*   Bsp1: ..../index.php?type=1
/* Bei mehr als ein Variablen-Werte-Paar werden diese durch ein Ampersand (&) getrennt.
/*   Bsp2: ..../index.php?type=1&id=5&search=mmp
/* Die Reihenfolge der Variablen-Werte-Paare spielt keine Rolle.
/*
/* In PHP sind die Werte im Array $_GET gespeichert.
/* Die einzelnen Werte können wir durch $_GET['Variablenname'] auslesen.
/*   Für Bsp2 wären das: $_GET['type'], $_GET['id'], $_GET['search']
/*
/* Mit Hilfe der PHP-Funktion empty() können wir herausfinden, ob eine Variable existiert,
/*   bzw. ob sie einen Inhalt hat.
/* Wen $_GET['type'] nicht existiert, oder keinen Inhalt hat,
/*   gibt empty($_GET['type']) den Wert true zurück.
/* So können wir feststellen, dass nicht ein bestimmter Typ (Angebot oder Anfrage),
/*   sondern alle Inserate abgefragt werden sollen.
*/
if(empty($_GET['type'])){
  // Alle Items (Inserate) werden in die Variable $item_list geladen.
  // Die Funktion get_all_items() haben wir in die Datei data.php ausgelagert.
  //   Sie liefert ein einfaches Array allen Items zurück.
  //   Dabei ist jedes Produkt ein eigenes, indiziertes Array.
  $item_list = get_all_items();
}else{
  // Nur die Items (Inserate) mit type=1 (Angebote), oder type=2 (Anfragen)
  //   werden in die Variable $item_list geladen.
  // Die Funktion get_item_by_type() haben wir in die Datei data.php ausgelagert.
  //   Sie benötigt einen Parameter, den type, nach dem die Items gefiltert werden sollen.
  //   Sie liefert ein einfaches Array allen passenden Items zurück.
  //   Dabei ist jedes Produkt ein eigenes, indiziertes Array.
  $item_list = get_items_by_type($_GET['type']);
}
// ---------------------- VIEW ----------------------
?>
<!DOCTYPE html>
<html>
<head>

<?php
  $page_head_title = "MMP Ricardo - Home"; // Inhalt des <title>-Elements
  require_once('templates/page_head.php'); // Inhalt des <head>-Elements aus externer PHP-Datei
?>

</head>
<body class="py-5">
<!-- Navigation -->
<?php require_once('templates/menu.php'); // Navigation aus externer PHP-Datei ?>

<!-- Inhalt -->
  <div class="container">
    <section class="inserate">
      <div class="card-columns">

<?php
/* ************ foreach()-Schleife ************************
/*
/* Die foreach()-Schleife wird für jedes Element eines Arrays ($item_list) einmal durchlaufen.
/* Dabei wird der Inhalt des gerade aktuellen Array-Elements in der Variablen,
/*   die nach dem Schlüsselwort 'as' ($item) gespeichert.
/* In unserem Beispiel durchlaufen wir die $item_list und können
/*   bei jedem Schleifendurchlauf den Inhalt der Variablen $item (auch ein Array) auslesen.
/*
/* Bei einem assoziativen Array hat jeder Wert einen Bezeichner (engl: key).
/* In unserm Beispiel stammen die Bezeichner ebenso wie die Werte aus unserer Datenbank.
/* In der Abfrage haben wir Werte aus der Tabelle items ausgelesen.
/* Die Bezeichner sind die Spaltennamen der Tabelle
/*   ('id', 'user_id', 'title', 'description', 'price', 'insert_time', 'type', 'img')
/* Wollen wir also die Beschreibung des aktuellen Produkts auslesen,
/*   können wir das mit $item['description'] tun.
*/
foreach ($item_list as $item) {

/* Für jedes Produkt müssen wir zuerst noch Daten aus der Datenbank für den User lesbar machen.

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

  /* ---------- DB-Wert insert_time (timestamp) in ein Date-Objekt transformieren  ----------
  /* Die insert_time (Tabelle items) ist im Timestamp-Format gespeichert (Bsp: 2019-07-11 10:35:54).
  /* new DateTime() kreiert ein Date-Objekt.
  /* Später passen wir das Format an und geben das Datum aus.
  */
  $date = new DateTime($item['insert_time']); //

  /* ---------- Kategorie(en) für das aktuelle Inserat (Item) aus der Datenbank auslesen  ----------
  /* Da jedes Produkt mehr als eine Kategorie haben kann,
  /*    sind diese in einer separaten Tabelle (item_has_category) gespeichert.
  /* get_categories_by_id() holt die Kategorien für ein item mit einer bestimmten ID aus der DB
  /*   und gibt ein Array (imdiziert) mit diesen Kategorien zurück.
  /* Später durchlaufen wir das Array $categories mit einer foreach()-Schleife
  /*   und geben den Inhalt aus.
  */
  $categories = get_categories_by_id($item['id']);

?>
        <!-- Ausgabe jedes item als Card -->
        <article class="card">
          <div class="card-header text-muted">
            <small><?php echo $date->format('d.m.Y'); // https://www.php.net/manual/de/datetime.format.php ?></small>
          </div>

<?php   if(!empty($item['img'])){ ?>
          <img
            src="item_img/<?php echo $item['img']; // Pfad zum Produtbild ?>"
            class="card-img-top" alt="<?php echo $item['title']; // Title als Inhalt des alt-Attributs ?>">
<?php   } ?>

          <div class="card-body">
            <h3 class="card-title"><?php echo $item['title']; ?></h3>
            <h5 class="card-subtitle mb-2 text-muted"><?php echo $type; // Ausgabe des Titels ?></h5>
            <p class="card-text">
<?php   foreach ($categories as $category) { ?>
                <span class="badge badge-secondary"> <?php echo $category; // Ausgabe jeder Kategorie als Bootstrap-Badge ?></span>
<?php   } ?>
            </p>
            <!-- Link auf details.php -- mit angehängten Daten
              Damit details.php weiss, welches Item angezeigt werden soll, schicken wir in der URL die ID mit.
              Der Syntax:   URL?var1=wert1&var2=wert2
              URL:        die Adresse des Datei, welche die Daten verarbeiten soll (hier: details.php)
              ?:          Zeigt an, dass an die URL Daten angehängt sind.
              var1=wert1: var1 ist der Bezeichner von wert1.
                          So können wir in details.php den Wert mit $_GET['var1'] ermitteln.
              &:          Zeigt an, dass weitere Daten folgen.

              In unserem Beispiel können wir also in details.php über $_GET['item'] ermitteln,
                wie die ID des anzuzeigenden Items lautet und nachfolgend das Item aus der DB laden.
            -->
            <a href="<?php echo $base_url; // $base_url in config.php definiert ?>subsite/details.php?item=<?php echo $item['id']; // Für jedes $item wird die eigene id angehängt ?>" class="btn btn-sm btn-info btn-block">Details</a>

<?php
  // Der Code für den Bearbeiten- und den Löschen-Button innerhalb der if()-Bedingung wird nur ausgegeben,
  //   wenn es die Variable $user_id gibt ...
  //   ... und $user_id mit $item['user_id'] (ID des Users, der das item erstellt hat) übereinstimmt.
  if(isset($user_id) && $user_id == $item['user_id']) {
?>
            <!-- Button-Link zur Bearbeiten-Seite des Inhalts -->
            <!-- Link auf edit.php -- mit angehängten Daten
              Erklärung: s. "Link auf details.php"
              SICHERHEITSHINWEIS:
                Sollen Daten bearbeitet oder gelöscht werden ist es ratsam NICHT mit GET zu arbeiten.
                Abgesehen von der äusserst simplen Möglichkeit eines Angriffs kann ein socher Link
                aus Versehen gespeichert oder/und mehrfach abgeschickt werden.
                Besser wäre es z. B. die Daten per POST oder in einer Session-Variable zu verschicken.
                Der Aufwand ist jedoch viel höher. In diesem reinen Übungsprojekt verzichten wir darauf.
            -->
            <a href="<?php echo $base_url ?>subsite/edit.php?id=<?php echo $item['id']; ?>"  <button type="button" class="btn btn-sm btn-warning btn-block">Text bearbeiten</button></a>
            <!-- Button-Link zur Löschen-Seite des Inhalts -->
            <!-- Link auf delete.php -- mit angehängten Daten
              Erklärung: s. "Link auf details.php"
              SICHERHEITSHINWEIS: s. "Link auf edit.php"
            -->
            <a href="<?php echo $base_url ?>subsite/delete.php?item=<?php echo $item['id']; ?>"  <button type="button" class="btn btn-sm btn-warning btn-block">Inserat löschen</button></a>
<?php   }?>

          </div>
        </article>
<?php } ?>
      </div>
    </section>
  </div>

<?php include_once('templates/footer.php') ?>

</body>
</html>
