
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>MMP Ricardo - Details</title>

<!-- Bootstrap Verlinkung -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="py-5">
  <!-- Inhalt mit Navigation -->
  <div class="container">

<!-- einfaches Bootstrap-Menü -->
<nav class="navbar navbar-expand-lg navbar-dark bg-info mb-3 fixed-top">
  <div class="container">
    <a class="navbar-brand" href="../index.php">MMP Ricardo</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mmp_ricardo_navbar" aria-controls="mmp_ricardo_navbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mmp_ricardo_navbar">
      <div class="navbar-nav">
        <a class="nav-link" href="../index.php">Home</a>
        <a class="nav-link" href="../index.php?type=1">Angebote</a>
        <a class="nav-link" href="../index.php?type=2">Anfragen</a>
        <a class="nav-link" href="newproduct.php">Inserieren</a>
        <a class="nav-link" href="../login.php">Anmelden</a>
        <a class="nav-link" href="../register.php">Registrieren</a>
      </div>
    </div>
  </div>
</nav>


    <section class="content">

        <h2><!-- title --></h2>
        <!-- Ausgabe der Beschreibung
          In der DB kann ein mehrzeiliger Text mit Textumbrüchen gespeichert werden.
          Umbrüche werden in der DB anders gespeichert (newline –> nl) als in HTML benötigt (<br>).
          Die php-Funktion nl2br() wandelt die DB-Textümbrüche zu HTML-Textumbrüche um. (sehr praktisch)
        -->

        <p><!-- description --></p>
	      <p class="preis"><strong>Preis:</strong> <!-- price --></p>
        <p><strong>inseriert von:</strong> <!-- firstname lastname --></p>
        <p class="tag">
            <span><strong>Inserattyp:</strong> <!-- type --></span> <br>
            <span><strong>Kategorie:</strong>  <span class="badge badge-pill badge-primary"> <!-- category --></span>  </span> <br>
            <span><strong>Datum:</strong> <!-- inserttime --></span>
        </p>
    </section>
  </div>

</body>
</html>
