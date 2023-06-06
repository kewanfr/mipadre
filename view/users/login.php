<?php $pageName = "login" ?>

<div class="page-header">
  <h1>Connexion</h1>
  <?= $this->Session->flash(); ?>
  <form action="<?= Router::url("users/login"); ?>" method="post">
    <div class="form-group">
      <?= $this->Form->input("login", "Identifiant (email ou nom d'utilisateur)", array("required" => true)); ?>
    </div>
    <div class="form-group">
      <?= $this->Form->input("password", "Mot de passe", array("type" => "passwordWithBtn", "required" => true)); ?>
    </div>
    <div class="form-group">
      Pas encore de compte ? <a href="<?= Router::url('users/register'); ?>">S'inscrire</a> <br>
      Mot de passe oublié ? <a href="<?= Router::url('users/forgotpassword'); ?>">Réinitialiser</a>
    </div>
    <button type="submit" class="btn btn-primary">Se connecter</button>
  </form>
</div>

<script>
  $(document).ready(function() {
    $('#show-password').click(function() {
      $('#password').attr('type', $('#password').attr('type') == 'password' ? 'text' : 'password');
      $(this).find('i').toggleClass('fa-eye fa-eye-slash');
    });
  });
</script>