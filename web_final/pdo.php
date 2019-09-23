 <?php
$db = new PDO('mysql:host=localhost;dbname=539197_24_1', '539197_24_1', 'iWKuZB3wYCvj');

$sql = "SELECT * FROM users";
foreach ($db->query($sql) as $row) {
   echo $row['email']."<br />";
   echo $row['firstname']."<br />";
   echo $row['lastname']."<br /><br />";
}
?>
