
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>MMP Ricardo - Registrierung</title>

<!-- Bootstrap Verlinkung -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="py-5">
  <!-- Inhalt mit Navigation -->
  <div class="container">

    <!-- einfaches Bootstrap-Menü -->
<nav class="navbar navbar-expand-lg navbar-dark bg-info mb-3 fixed-top">
  <div class="container">
    <a class="navbar-brand" href="/index.php">MMP Ricardo</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mmp_ricardo_navbar" aria-controls="mmp_ricardo_navbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mmp_ricardo_navbar">
      <div class="navbar-nav">
        <a class="nav-link" href="index.php">Home</a>
        <a class="nav-link" href="index.php?type=1">Angebote</a>
        <a class="nav-link" href="index.php?type=2">Anfragen</a>
        <a class="nav-link" href="login.php">Anmelden</a>
        <a class="nav-link" href="register.php">Registrieren</a>
      </div>
    </div>
  </div>
</nav>

    <section class="content">
      <h1>Registrieren</h1>
      <p>Bitte registriere dich, um neue Inserate zu erstellen oder deine bestehenden Inserate zu löschen.</p>

      <form action="/register.php" method="post">
        <div class="form-group">
          <label for="id_email">Email: </label>
          <input type="email" name="email" class="form-control" id="id_email" value="">
        </div>
        <!-- Passwort und Passwortbestätigung müssen jedesmal neu eingegeben werden -->
        <div class="form-group">
          <label for="id_password">Passwort: </label>
          <input type="password" name="password" class="form-control" id="id_password">
        </div>
        <div class="form-group">
          <label for="id_password_confirm">Passwortbestätigung: </label>
          <input type="password" name="password_confirm" class="form-control" id="id_password_confirm">
        </div>
        <div class="form-group">
          <label for="id_firstname">Vorname: </label>
          <input type="text" name="firstname" class="form-control" id="id_firstname" value="">
        </div>
        <div class="form-group">
          <label for="id_lastname">Nachname: </label>
          <input type="text" name="lastname" class="form-control" id="id_lastname" value="">
        </div>
        <button type="submit" name="register_submit" class="btn btn-primary" value="einloggen">Registrieren</button>
      </form>


      <!-- optionale Nachricht (mit angepasster CSS) -->




    </section>
  </div>
</body>
</html>
