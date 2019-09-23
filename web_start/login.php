<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>MMP Ricardo - Login</title>

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
      <h1>Anmelden</h1>
      <p>Bitte melde dich an, um neue Inserate zu erstellen oder deine bestehenden Inserate zu löschen.</p>

      <!-- FORM
        Das <form>-Element umschliesst alle Formularfelder, deren Wert zum auswwrtenden Skript geschickt werden sollen.
        Im action-Attribut des <form>-Elements steht die Adresse Skripts, das den Formularinhalt auswertet,
          in unserem Fall ist das login.php, also eben diese Seite, in dem sich auch das Formular befindet.
        Das method-Attribut enthät die Methode, mit der der Inhalt der Formularfelder verschickt wird.
          POST: Werte werden unsichtbar, !!!!! ABER NICHT VERSCHLÜSSELT !!!!! verschickt.
          GET:  Wetre werden an die URL angehängt.

        Siehe: https://wiki.selfhtml.org/wiki/HTML/Formulare/form
        Siehe: https://www.w3schools.com/html/html_forms.asp
      -->
      <form action="/login.php" method="post">
        <div class="form-group">
          <!-- LABEL
            Siehe: https://wiki.selfhtml.org/wiki/HTML/Formulare/label
            Siehe: https://www.w3schools.com/tags/tag_label.asp
          -->
          <label for="id_email">E-Mail: </label>
          <!-- INPUT
            Siehe: https://wiki.selfhtml.org/wiki/HTML/Formulare/input
            Siehe: https://www.w3schools.com/tags/tag_input.asp
            type-Attribut
            Siehe: https://www.w3schools.com/tags/att_input_type.asp
            Siehe: https://www.w3schools.com/html/html_form_input_types.asp
          -->
          <input type="email" name="email" class="form-control" id="id_email">
        </div>
        <div class="form-group">
          <label for="id_password">Passwort: </label>
          <input type="password" name="password" class="form-control" id="id_password">
        </div>
        <button type="submit" name="login_submit" class="btn btn-primary" value="einloggen">Anmelden</button>
      </form>

      <!-- optionale Nachricht -->


    </section>
  </div>
</body>
</html>
