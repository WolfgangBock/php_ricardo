<?php if(isset($user_id)){ // Der Footer wird nur angezeigt, wenn der User eingeloggt ist. ?>
  <footer class="fixed-bottom bg-info text-white text-center">
      <p>Eingeloggt als <?php echo  $user['firstname'] . " " . $user['lastname']; // Ausgabe des vollen Usernames?></p>
  </footer>
<?php } ?>
