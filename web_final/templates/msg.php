<?php
  if(empty($alert_type)){        // Wenn es $alert_type nicht gibt ...
    $alert_type = "alert-info";
  }
?>
<div class="alert <?php echo $alert_type; // alert-Farb-Klasse setzen ?>" role="alert">
  <p><?php echo $msg; // $msg (Nachricht) ausgeben ?></p>
</div>
