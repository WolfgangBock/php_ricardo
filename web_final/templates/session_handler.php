
<?php

if(isset($_SESSION['userid'])){ // Prüfung, ob die Session-Variable "userid" besteht.
  // Die get_user_by_id()-Funktion aus system/data.php holt die Daten für eine
  //   bestimmte user_id aus der DB.
  $user = get_user_by_id($_SESSION['userid']);
  $user_id = $user['id'];
  $logged_in = true;           // $logged_in wird in templates/menu.php verwendet.
  $log_in_out_text = "Logout"; // $log_in_out_text wird in templates/menu.php verwendet.

}else{
  $logged_in = false;
  $log_in_out_text = "Anmelden";
}
?>
