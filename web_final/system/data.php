<?php

// Datenbank Verbindung aufbauen
// Siehe: https://www.php-einfach.de/mysql-tutorial/crashkurs-pdo/
// Siehe: https://www.php-einfach.de/mysql-tutorial/verbindung-aufbauen/
// Siehe: https://phpdelusions.net/pdo#dsn

function get_db_connection(){

  /* Die in config.php festgelegten Variablen gelten innerhalb einer Funktion standardmässig NICHT.
    Um sie innerhalb einer Funktion zugänglich zu machen, müssen sie mit dem Schlüsselwort global innerhalb der Funktion gekennzeichnet werden.
    Siehe: https://www.php.net/manual/de/language.variables.scope.php
  */
  global $db_host, $db_name, $db_user, $db_pass, $db_charset;

  $dsn = "mysql:host=$db_host;dbname=$db_name;charset=$db_charset"; // siehe https://en.wikipedia.org/wiki/Data_source_name
  $options = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false
  ];

  // Einfache Version der DB-Verbindung
  //$db = new PDO($dsn, $user, $pass, $options);

  // Ausführliche Version der DB-Verbindung
  try {
       $db = new PDO($dsn, $db_user, $db_pass, $options);
  } catch (\PDOException $e) {
       throw new \PDOException($e->getMessage(), (int)$e->getCode());
  }

  // Wir geben die in der Variablen $db gespeicherte Datenbankverbindung
  //   als Ergebnis der Funktion zurück.
  return $db;
}



/************************ GRUNDLEGENDE BEFEHLE ************************/





// Einloggen
// Der Funktion benötigt zwei Parameter, die E-Mail-Adresse und das Passwort des Users.
function login($email, $password){
  // Wir stellen über die Funktion get_db_connection() (siehe oben) eine DB-Verbindung her
  //   und speichern sie in der Variablen $db.
  //   ($db ist ein Objekt der PDO-Klasse, welche von PHP zur verfügung gestellt wird.)
  $db = get_db_connection();

  // Wir formulieren die SQL-Abfrage und speichern sie in der Variablen $sql.

  //       !!!!! ACHTUNG !!!!!
  // !!!!! Die direkte Formulierung als SQL-Statement ist aus Sicherheitsgründen nicht zu  empfehlen.
  // !!!!! PDO stellt sog. perpared Statements zur Verfügung.
  // !!!!! Sie verhindern wirkungsvoll Hackerangriffe SQL-Injections.
  // !!!!! Siehe: https://www.php-einfach.de/mysql-tutorial/php-prepared-statements/
  // !!!!! Siehe: https://phpdelusions.net/pdo#prepared
  $sql = "SELECT * FROM users WHERE email='$email' AND password='$password';";

  // Mit der query()-Methode schicken wir die SQL-Abfrage an die DB und
  //   speichern das Ergebnis in der Variablen $result.
  $result = $db->query($sql);

  // Die Methode rowCount() liefert die Anzahl der Ergebnisse zurück.
  // Für einen erfolgreichen Login muss genau ein Ergebnis zurückgegeben werden.
  if($result->rowCount() == 1){    // Wenn es genau ein Ergebnis gibt ...
    // ... wandeln wir mit der fetch()-Methode das Ergebnis in ein assoziatives Array um
    //   und speichern es in der Variablen $row.
    // Die Bezeichner des Assoziativen Arrays sind die Namen der Datenbankspalten der Tabelle user.
    $row = $result->fetch();

    // Den Wert aus $row geben wir als Ergebnis der Funktion zurück.
    return $row;
  }else{          // Wenn es mehr oder weniger als genau ein Ergebnis gibt ...
    // ... geben wir als Ergebnis der Fuktion den Wert false zurück.
    return false;
  }
}



/************************ INSERT BEFEHLE ************************/


// Inserieren
/* Ein neues Inserat wir in der Datenbank gespeichert */
// save_item() läuft nahezu analog zur save_user()-Funktion ab
function save_item($user_id, $title, $description, $price, $type, $img_name){
  $db = get_db_connection();
  $sql = "INSERT INTO items (user_id ,title, description, price, type, img) VALUES (?,?,?,?,?,?);";
  $stmt = $db->prepare($sql);
  $stmt->execute(array($user_id, $title, $description, floatval($price), $type, $img_name));
  return $db->lastInsertId(); // Die lastInsertId()-Methode gibt die ID des zuletzt eingefügten Datensatzes zurück.
}

// Speichert die item_id-category-Kombination in der Tabelle item_has_category ab.
function save_item_category($item_id, $category){
  $db = get_db_connection();
  $sql = "INSERT INTO item_has_category (item_id ,category_id) VALUES (?,?);";
  $stmt = $db->prepare($sql);
  $stmt->execute(array($item_id, $category));
  return true;
}

/************************ SELECT BEFEHLE ************************/

// User Daten auslesen
/* Die Daten des eingeloggten Benutzers werden via User_Id ausgelesen */
// Die Funktion verläuft in einer etwas verkürzten Version analog zur login()-Funktion
function get_user_by_id($id){
  $db = get_db_connection();
  $sql = "SELECT * FROM users WHERE id = $id;";
  $result = $db->query($sql);
  return $result->fetch();
}

// Überprüfung, ob die E-Mail-Adresse in der Tabelle users vorhanden ist.
function does_email_exist($email){
  $db = get_db_connection(); // DB-Verbindung herstellen (s. login())
  // SQL-Statement erstellen
  // Die SQL-Funktion count() gibt nur die Anzahl der Ergebnisse zurück.
  // Wenn es keinen User mit der E-Mail-Adresse gibt, liefert die DB den Wert 0 zurück.
  // In PHP wird in einer if()-Bedingung der Wert 0 mit false gleichgesetzt.
  $sql = "SELECT count(*) FROM users where email = '$email';";
  // SQL-Statement an die DB schicken
  $result = $db->query($sql);
  return $result;
}

// Inserate von Suchen und Bieten auslesen
function get_all_items(){
  $db = get_db_connection();
  $sql = "SELECT * FROM items";
  $result = $db->query($sql);
  return $result->fetchAll();
}


// Kategorie des Inserates via item_id aus der Tabelle "item_has_category" auslesen
function get_categories_by_id($id){
  $db = get_db_connection();
  $sql = "SELECT category_id FROM item_has_category WHERE item_id = $id;";
  $result = $db->query($sql);
  return $result->fetchAll(PDO::FETCH_COLUMN);
}

// Die get_item_by_id()-Funktion holt alle Daten eines Items zu einer
//   bestimmten item_id aus der DB-Tabelle 'items' und gibt den Datensatz als
//   assoziatives Array zurück.
// Die Bezeichner (keys) des assoziativen Arrays sind die Namen der Tabellenspalten.
function get_item_by_id($id){
  $db = get_db_connection();
  $sql = "SELECT * FROM items WHERE id = $id;";
  $result = $db->query($sql);
  return $result->fetch();
}

// Registrieren
/* Neue Benutzerdaten werden in die User Datenbank gespeichert */
function save_user($email, $password, $firstname, $lastname){
  $db = get_db_connection();  // DB-Verbindung herstellen (s. login())
  // Das PHP-Datenbank-Interface PDO stellt sog. prpared statements zur Verfügung.
  // siehe: https://www.php-einfach.de/mysql-tutorial/crashkurs-pdo/
  // Dabei stehen im SQL-Statement Fragezeichen als Platzhalter für die zu übertragenden Werte.
  $sql = "INSERT INTO users (email, password, firstname, lastname) VALUES (?, ?, ?, ?);";
  // Im folgenden Schritt wird das Statement mit $db->prepare($sql) vorbereitet und in einer Variablen gespeichert.
  $stmt = $db->prepare($sql);
  // Mit der execute()-Methode wird die Abfrage ausgeführt.
  // Dabei müssen die einzusetzenden Werte als Array übermittelt werden.
  // Innerhalb des Arrays müssen die Werte die richtige Reihenfolge haben.
  // Da es sich bei dem Statement um einen INSERT-Befehl handelt,
  //   wird als Ergebnis true fur eine erfolgreiche Speicherung
  //   und false für eine misslungene Speicherung zurückgegeben
  return $stmt->execute(array($email, $password, $firstname, $lastname));
}

// Inserate nur von Suchen ODER Bieten auslesen (Je nach ID in der URL Adresse)
function get_items_by_type($type){
  $db = get_db_connection();
  $sql = "SELECT * FROM items WHERE type = $type";
  $result = $db->query($sql);
  return $result->fetchAll();
}

// Alle Kategorien auslesen
function get_all_categories(){
  $db = get_db_connection();
  $sql = "SELECT * FROM categories";
  $result = $db->query($sql);
  // durch die PDO-Option PDO::FETCH_COLUMN wird als Resultat ein numerisches Array aller Einträge zurückgegeben.
  return $result->fetchAll(PDO::FETCH_COLUMN);
}


/************************ UPDATE BEFEHLE ************************/
/* Ein  Inserat wird aktualisiert */
function update_item( $title, $description, $price, $type, $item_id){
  $db = get_db_connection();
  $sql = "UPDATE items SET title = ? , description = ?, price = ?, type =? WHERE id = ?;";
  $stmt = $db->prepare($sql);
  return $stmt->execute(array($title, $description, $price, $type, $item_id));
}
function update_item_category($item_id, $category){
  $db = get_db_connection();
  $sql = "INSERT INTO item_has_category (item_id ,category_id) VALUES (?,?);";
  $stmt = $db->prepare($sql);
  return $stmt->execute(array($item_id, $category));;
}

/************************ DELETE BEFEHLE ************************/

// Die delete_category_for_item() läuft technisch analog zur save_user()-Funktion ab.
function delete_category_for_item($id){
  $db = get_db_connection();
  $sql = "DELETE FROM item_has_category WHERE item_id = ?;";
  $stmt = $db->prepare($sql);
  // Die $stmt->execute()_Methode gibt bei DELETE-Statements true/false zurück,
  //   jenachdem, ob Daten gelöscht wurden, oder nicht.
  return $stmt->execute(array($id));
}


// Inserat löschen
function delete_item($id){
  // Bevor wir das Item selbst löschen können,
  //   müssen wir alle Item-Kategorie-Beziehungen aus der Tabelle item_has_category löschen.
  // Dazu haben wir eine eigene delete_category_for_item()-Funktion. (s. o.)
  delete_category_for_item($id);

  $db = get_db_connection();

  // Zuerst müssen wir aufräumen.
  // Ist zu dem Inserat ein Bild gespeichert, müssen wir es vom Server löschen.
  // Dafür rufen wir den Datensatz des Items ab.
  $sql = "SELECT * FROM items WHERE id = $id;";
  $result = $db->query($sql);
  $item = $result->fetch();
  // Wenn im Datensatz die Spalte 'img' nicht leer war...
  if(!empty($item['img'])) {
    // ... löschen wir mit der PHP-eigenen unlink()-Funktion das die Bilddatei vom Server.
    // siehe: https://www.php.net/manual/de/function.unlink.php
    unlink('../item_img/'.$item['img']);
  };

  // Zuletzt stellen wir das Statement zum löschen des Items als prepared-statement zusammen.
  $sql = "DELETE FROM items WHERE id = ?;";
  $stmt = $db->prepare($sql);
  $stmt->execute(array($id));
  // Die Anzahl der betroffenen (gelöschten) Datensätze geben wir als Rückgabewert aus.
  return $stmt->rowCount();
}
