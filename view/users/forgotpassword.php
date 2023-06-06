<div class="page-header">
  <h1>Mot de passe oublié</h1>
  <?php echo $this->Session->flash(); ?>
  <form action="<?= Router::url("users/forgotpassword"); ?>" id="form" method="post">
    <div class="form-group row">
      <div class="col">
        <?= $this->Form->input("login", "Adresse mail ou nom d'utilisateur", array("invalidFeedback" => "", "required" => true)); ?>
      </div>
    </div>
    <div class="form-group">
      <a href="<?= Router::url('users/login'); ?>">Se connecter</a>
    </div>
    <button type="submit" class="btn btn-primary">Réinitialiser mon mot de passe</button>
  </form>
</div>