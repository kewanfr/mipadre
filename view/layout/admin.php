<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <link rel="stylesheet" href="<?= Router::webroot("vendor/css/bootstrap.min.css"); ?>">
  <link rel="stylesheet" href="<?= Router::webroot("vendor/fontawesome/css/all.min.css"); ?>">

  <link rel="stylesheet" href="<?= Router::webroot("css/style.css") ?>">

  <title><?= SecureConf::$pageTitlePrefix ?>Administration | <?php echo isset($title_for_layout) ? $title_for_layout : Conf::$siteName; ?></title>

</head>
<?php
$pageName = "home";
?>

<body class="d-flex flex-column h-90">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="<?= Router::url("") ?>">
      <img src="<?= Router::webroot("img/logo.png") ?>" class="nav-logo" alt="<?= Conf::$siteName ?>">
      <span class="nav-brand">Administration</span>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item <?= $pageName == 'clients' ? 'active' : '' ?>">
          <a class="nav-link" href="<?= Router::url("admin/clients/") ?>">Liste des Clients</a>
        </li>
      </ul>
      <ul class="navbar-nav mr-right">
        <?php if ($this->Session->isLogged()) : ?>
          <li class="nav-item">
            <a class="nav-link">Connecté en tant que <?= $this->Session->user("login") ?></a>
          </li>

          <li class="nav-item">
            <a class="nav-link" href="<?= Router::url("users/logout") ?>">Déconnexion</a>
          </li>
        <?php else : ?>
          <li class="nav-item <?= $pageName == 'login' ? 'active' : '' ?>">
            <a class="nav-link" href="<?= Router::url("users/login") ?>">Connexion</a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </nav>

    <script src="<?= Router::webroot("vendor/js/jquery-3.6.0.min.js"); ?>"></script>
    <script src="<?= Router::webroot("vendor/js/popper.min.js"); ?>"></script>
    <script src="<?= Router::webroot("vendor/js/bootstrap.min.js"); ?>"></script>

    <div class="container py-4 h-90">
      <?php echo $content_for_layout; ?>
    </div>
  <?php require "elements/footer.php" ?>
</body>

</html>