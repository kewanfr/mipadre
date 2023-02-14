<?php $pageName = "login" ?>

<div class="page-header">
  <h1>Connexion</h1>
  <?= $this->Session->flash(); ?>
  <form action="<?= Router::url("users/login"); ?>" method="post">
    <div class="form-group">
      <?= $this->Form->input("login", "Identifiant"); ?>
    </div>
    <div class="form-group">
      <?= $this->Form->input("password", "Mot de passe", array("type" => "password")); ?>
    </div>
    <button type="submit" class="btn btn-primary">Se connecter</button>
</form>
</div>