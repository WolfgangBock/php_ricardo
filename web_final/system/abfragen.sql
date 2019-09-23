
-- ------------------- --
-- SELECT - Statements --
-- ------------------- --
SELECT * FROM users;
/* 
	Alle Angaben aller Datensätze der Tabelle users.
*/

SELECT * FROM users WHERE role = 'user';
/* 
	Alle Angaben der Datensätze aus users, in deren Spalte role Wert 'user' steht.
*/

SELECT email FROM users WHERE role = 'user';
/* 
	Nur die Werte aus der Spalte email der Datensätze aus users, in deren Spalte role Wert 'user' steht.
*/

SELECT email, firstname, lastname, role FROM users WHERE role = 'user';
/* 
	Die Werte aus den Spalten email, firstname, lastname und role der Datensätze aus users, in deren Spalte role Wert 'user' steht.
*/

SELECT * FROM items, users;
/* 
	Alle Einträge aller Datensätze der Tabellen users und items.
	Jeder Datensatz einer Tabelle wird mit jedem Datensatz der anderen Tabelle kombiniert.
*/

SELECT items.title, users.email FROM items, users;
/* 
	Alle Einträge von items.title und users.email.
	Jeder Datensatz einer Tabelle wird mit jedem Datensatz der anderen Tabelle kombiniert.
*/

SELECT items.title, users.email FROM items, users 
	WHERE items.user_id = users.id; 
/* 
	Einträge aus items.title und users.email, 
		bei denen items.user_id und users.id übereinstimmen.
*/

SELECT i.title, u.email FROM items i, users u 
	WHERE i.user_id = u.id;
/* 
	Einträge aus items.title und users.email, 
		bei denen items.user_id und users.id übereinstimmen.

	Für die Tabellennamen können Platzhalter vergeben werden. Die Benennung der Platzhalter ist im Rahmen der Internetkonventionen frei. 
	Häufig werden die Anfangsbuchstaben der Tabellennamen verwendet.
*/

SELECT i.title, u.email FROM items i, users u 
	WHERE i.user_id = u.id 
	AND u.role = 'user';
/* 
	Einträge aus items.title und users.email, 
		bei denen items.user_id und users.id übereinstimmen
		UND users.role gleich 'user' ist.
*/

SELECT * FROM items LEFT JOIN users 
	ON items.user_id = users.id;
/* 
	Alle Datensätze aus der linken Tabelle (items) werden mit den Datensätze aus der rechten Tabelle (users),
	entsprechend der Bedingung nach dem ON kombiniert.
*/

SELECT * FROM items RIGHT JOIN users 
	ON items.user_id = users.id;
/* 
	Alle Datensätze aus der rechten Tabelle (users) werden mit den Datensätze aus der linken Tabelle (items),
		entsprechend der Bedingung nach dem ON kombiniert.

	Gibt es aus einem Datensatz der rechten Tabelle keinen Treffer in der linken Tabelle, werden stattdessen NULL-Werte angegeben.
*/

SELECT * FROM items WHERE id IN 
	(SELECT item_id FROM item_has_category WHERE category_id = 'Technik');
/* 
	Alle Angaben aller Datensätze aus items, deren id in einer Wertliste vorkommt, die aus der Abfrage 
		(aller item_id aus item_has_category, deren category_id = 'Technik' sind), stammt.
*/

SELECT item_id FROM item_has_category 
	WHERE category_id = 'Computer' 
	OR category_id = 'Technik';
/* 
	Die item_id aller Datensätze aus item_has_category
		deren category_id gleich 'Computer' 
		ODER gleich 'Technik' ist.
*/

SELECT item_id FROM item_has_category 
	WHERE category_id = 'Computer' 
	OR category_id = 'Technik'
	GROUP BY item_id;
/* 
	Die item_id aller Datensätze aus item_has_category
		deren category_id gleich 'Computer'
		ODER gleich 'Technik' ist.
		Alle gleichen item_id werden GRUPPIERT.
*/

SELECT item_id FROM item_has_category 
	WHERE category_id = 'Computer' 
	AND category_id = 'Technik';
/* 
	Die item_id aller Datensätze aus item_has_category
		deren category_id gleich 'Computer'
		UND gleich 'Technik' ist.

	Leeres Ergebnis, da die category_id jedes Datensatzes nur einen Wert haben kann.
*/

SELECT item_id FROM item_has_category 
	WHERE category_id = 'Computer' 
	AND item_id IN 
		(SELECT item_id FROM item_has_category WHERE category_id = 'Technik');
/* 
	Die item_id aller Datensätze aus item_has_category
		deren category_id gleich 'Computer'
		UND deren item_id in der Wertliste vorkommt, die aus der Abfrage 
			(aller item_id aus item_has_category, deren category_id = 'Technik' sind), stammt.
*/


-- ------------------- --
-- INSERT - Statements --
-- ------------------- --
INSERT INTO roles (role_name_id)
  VALUES ('chef');
/* 
	In die Tabelle roles wird ein Datensatz eingefügt.
		In die Spalte role_name_id wird der Wert 'chef' eingetragen.
*/

INSERT INTO roles (role_name_id)
  VALUES ('redaktor/in'), ('moderator/in');
/* 
	In die Tabelle roles werden zwei Datensätze eingefügt.
		In die Spalte role_name_id werden die WERTE 'redaktor/in' und 'moderator/in' eingetragen.
*/
  
INSERT INTO users (id, email, password, firstname, lastname, regtime, role, img) 
	VALUES (NULL, 'isabella.krakehla@gmail.com', '44444', 'Isabella', 'Krakehla', CURRENT_TIMESTAMP, 'user', NULL);
/* 
	In die Tabelle users wird ein Datensatz eingefügt.
	In die Spalten id, email, password, firstname, lastname, regtime, role, img 
		werden die WERTE NULL, 'isabella.krakehla@gmail.com', '44444', 'Isabella', 'Krakehla', CURRENT_TIMESTAMP, 'user', NULL eingetragen.
	
	Die Reihenfolge der Werte muss der Reihenfolge der Spaltenliste entsprechen.
	Den NULL-Wert für id ersetzt das DBMS automatisch durch den nächsten AUTO_INCREMENT-Wert.
*/
	
INSERT INTO users (email, password, firstname, lastname, role) 
	VALUES ( 'paul.wunderlich', '55555', 'Paul', 'Wunderlich', 'user');
/* 
	In die Tabelle users wird ein Datensatz eingefügt.
	In die Spalten email, password, firstname, lastname, regtime, role
		werden die WERTE 'paul.wunderlich', '55555', 'Paul', 'Wunderlich', 'user' eingetragen.

	Die Reihenfolge der Werte muss der Reihenfolge der Spaltenliste entsprechen.
	Den fehlenden für id und img ersetzt das DBMS durch den nächsten Standard-Werte,
		das ist für id der AUTO_INCREMENT-Wert, für regtime der CURRENT_TIMESTAMP und für img der Wert NULL.
*/


-- ------------------- --
-- UPDATE - Statements --
-- ------------------- --
UPDATE users 
	SET email = 'paul.wunderlich@gmail.com' 
	WHERE id = 6;
/* 
	AKTUALISIERT in der Tabelle users 
		den Wert der Spalte email auf 'paul.wunderlich@gmail.com' 
		für alle Datensätze (1) bei denen id den Wert 6 hat.
*/


-- ------------------- --
-- DELETE - Statements --
-- ------------------- --
DELETE FROM users WHERE users.id = 5;
/* 
	Löscht alle Datensätze aus users, deren id gleich 5 ist.
*/

DELETE FROM users WHERE users.id > 5;
/* 
	Löscht alle Datensätze aus users, deren id grösser 5 ist.
*/