<!-- einfaches Bootstrap-Menü -->
<nav class="navbar navbar-expand-lg navbar-dark bg-info mb-3 fixed-top">
  <div class="container">
    <a class="navbar-brand" href="/index.php">MMP Ricardo</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mmp_ricardo_navbar" aria-controls="mmp_ricardo_navbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mmp_ricardo_navbar">
      <div class="navbar-nav">
        <a class="nav-link" href="<?php echo $base_url; // $base_url kommt aus system/config.php ?>index.php">Home</a>
        <a class="nav-link" href="<?php echo $base_url ?>index.php?type=1">Angebote</a>
        <a class="nav-link" href="<?php echo $base_url ?>index.php?type=2">Anfragen</a>
<?php if($logged_in){ // Der folgende Menüunkt wird nur angezeigt, wenn der User eingeloggt ist. ?>
        <a class="nav-link" href="<?php echo $base_url ?>subsite/newproduct.php">Inserieren</a>
<?php } ?>
        <a class="nav-link" href="<?php echo $base_url ?>login.php"><?php echo $log_in_out_text; // $log_in_out_text kommt aus templates/session_handler.php?></a>
<?php if(!$logged_in){ // Der folgende Menüunkt wird nur angezeigt, wenn der User NICHT eingeloggt ist.?>
        <a class="nav-link" href="<?php echo $base_url ?>register.php">Registrieren</a>
<?php } ?>
      </div>
    </div>
  </div>
</nav>
