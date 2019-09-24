 <?php
$db = new PDO('', '', '');

$sql = "SELECT * FROM users";
foreach ($db->query($sql) as $row) {
   echo $row['email']."<br />";
   echo $row['firstname']."<br />";
   echo $row['lastname']."<br /><br />";
}
?>
