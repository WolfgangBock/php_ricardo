<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">
<title>MMP Ricardo - Home</title>

<!-- Bootstrap Verlinkung -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>
<body class="py-5">
<!-- Navigation -->
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
        <a class="nav-link" href="subsite/newproduct.php">Inserieren</a>
        <a class="nav-link" href="login.php">Anmelden</a>
        <a class="nav-link" href="register.php">Registrieren</a>
      </div>
    </div>
  </div>
</nav>

<!-- Inhalt -->
  <div class="container">
    <section class="inserate">
      <div class="card-columns">

        <!-- Ausgabe jedes item als Card -->
        <article class="card">
          <div class="card-header text-muted">
            <small><!-- inserttime --></small>
          </div>


          <div class="card-body">
            <h3 class="card-title"><!-- title --></h3>
            <h5 class="card-subtitle mb-2 text-muted"><!-- type --></h5>
            <p class="card-text">
                <span class="badge badge-secondary"> <!-- category --></span>
            </p>
            <!-- Link auf details.php -- mit angehängten Daten
              Damit details.php weiss, welches Item angezeigt werden soll, schicken wir in der URL die ID mit.
              Der Syntax:   URL?var1=wert1&var2=wert2
              URL:        die Adresse des Datei, welche die Daten verarbeiten soll (hier: details.php)
              ?:          Zeigt an, dass an die URL Daten angehängt sind.
              var1=wert1: var1 ist der Bezeichner von wert1.
                          So können wir in details.php den Wert mit $_GET['var1'] ermitteln.
              &:          Zeigt an, dass weitere Daten folgen.

              In unserem Beispiel können wir also in details.php über $_GET['item'] ermitteln,
                wie die ID des anzuzeigenden Items lautet und nachfolgend das Item aus der DB laden.
            -->
            <a href="#" class="btn btn-sm btn-info btn-block">Details</a>

          </div>
        </article>


          </div>
        </article>
      </div>
    </section>
  </div>


</body>
</html>
