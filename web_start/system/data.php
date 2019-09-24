<?php

// Datenbank Verbindung aufbauen
// Siehe: https://www.php-einfach.de/mysql-tutorial/crashkurs-pdo/
// Siehe: https://www.php-einfach.de/mysql-tutorial/verbindung-aufbauen/
// Siehe: https://phpdelusions.net/pdo#dsn

/*
function get_db_connection(){

  // Die in config.php festgelegten Variablen gelten innerhalb einer Funktion standardmässig NICHT.
  //Um sie innerhalb einer Funktion zugänglich zu machen, müssen sie mit dem Schlüsselwort global innerhalb der Funktion gekennzeichnet werden.
  //Siehe: https://www.php.net/manual/de/language.variables.scope.php

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
*/



/************************ GRUNDLEGENDE BEFEHLE ************************/


// Einloggen
// Der Funktion benötigt zwei Parameter, die E-Mail-Adresse und das Passwort des Users.
function login($email, $password){

}

/************************ SELECT BEFEHLE ************************/


// Inserate von Suchen und Bieten auslesen
function get_all_items(){

}

// Kategorie des Inserates via item_id aus der Tabelle "item_has_category" auslesen
function get_categories_by_id($id){

}

// Inserate nur von Suchen ODER Bieten auslesen (Je nach ID in der URL Adresse)
function get_items_by_type($type){

}

// Die get_item_by_id()-Funktion holt alle Daten eines Items zu einer
//   bestimmten item_id aus der DB-Tabelle 'items' und gibt den Datensatz als
//   assoziatives Array zurück.
// Die Bezeichner (keys) des assoziativen Arrays sind die Namen der Tabellenspalten.
function get_item_by_id($id){

}

// User Daten auslesen
/* Die Daten des eingeloggten Benutzers werden via User_Id ausgelesen */
// Die Funktion verläuft in einer etwas verkürzten Version analog zur login()-Funktion
function get_user_by_id($id){

}

// Überprüfung, ob die E-Mail-Adresse in der Tabelle users vorhanden ist.
function does_email_exist($email){

}

// Alle Kategorien auslesen
function get_all_categories(){

}



/************************ INSERT BEFEHLE ************************/



// Registrieren
/* Neue Benutzerdaten werden in die User Datenbank gespeichert */
function save_user($email, $password, $firstname, $lastname){

}

// Inserieren
/* Ein neues Inserat wir in der Datenbank gespeichert */
// save_item() läuft nahezu analog zur save_user()-Funktion ab
function save_item($user_id, $title, $description, $price, $type, $img_name){

}

// Speichert die item_id-category-Kombination in der Tabelle item_has_category ab.
function save_item_category($item_id, $category){

}



/************************ UPDATE BEFEHLE ************************/
/* Ein  Inserat wird aktualisiert */
function update_item( $title, $description, $price, $type, $item_id){

}



/************************ DELETE BEFEHLE ************************/

// Die delete_category_for_item() läuft technisch analog zur save_user()-Funktion ab.
function delete_category_for_item($id){

}


// Inserat löschen
function delete_item($id){

}
