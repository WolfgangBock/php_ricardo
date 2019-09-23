<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
<title>MMP Ricardo – neues Inserat</title>

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
      <h1>Inserieren</h1>
      <p>Hier kannst du dein Inserat verfassen.</p>

      <!-- !!!!!!!!
        Um mit einem Formular eine Datei hochladen zu können, müssen wir ein weiteres Attribut zum <form>-Element hinzufügen.
        Das enctype-Attribut muss den Wert "multipart/form-data" haben.
      !!!!!!! -->
      <form action="/subsite/newproduct.php" method="post" enctype="multipart/form-data">
        <h4>Typ</h4>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="itemtype" id="angebot" value="1">
          <label class="form-check-label" for="angebot">
            Angebot
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="itemtype" id="anfrage" value="2">
          <label class="form-check-label" for="anfrage">
            Anfrage
          </label>
        </div>

        <h4>Kategorie</h4>
        <div class="form-check">
          <input  class="form-check-input"
                  type="checkbox" name="category[]"
                  id="cat0"
                  value="Computer" >
          <label class="form-check-label" for="cat0"> <!-- category --></label>
        </div>

        <div class="form-group">
          <label for="item_title">Titel: </label>
          <input type="text" name="title" class="form-control" id="item_title">
        </div>

        <div class="form-group">
          <label for="item_description">Beschreibung: </label>
          <textarea type="text" name="description" class="form-control" id="item_description"></textarea>
        </div>

        <div class="form-group">
          <label for="item_price">Preis: </label>
          <input type="number" name="price" class="form-control" id="item_price" step=".01">
        </div>

        <div class="form-group">
          <div class="custom-file">
            <input type="file" class="custom-file-input" id="prod_img" name="item_img">
            <label class="custom-file-label" for="prod_img">Durchsuchen...</label>
          </div>
        </div>

        <button type="submit" name="item_submit" class="btn btn-success" value="inserieren">Inserieren</button>
      </form>

        <!-- optionale Nachricht -->

      </section>
    </div>

      <footer class="fixed-bottom bg-info text-white text-center">
        <p>Eingeloggt als <!-- firstname lastname --></p>
  </footer>

    <!--
      Für die Darstellung des Dateiuploads benötigt Bootstrap einige JavaScript-Komponenten.
      Diese haben auf den eigentlichen Upload-Vorgang KEINEN Einfluss.
    -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
    <script>
      $(document).ready(function () {
        bsCustomFileInput.init()
      })
    </script>
  </body>
  </html>
