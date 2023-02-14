<div class="page-header">
  <h1>Inscription</h1>
    <?php echo $this->Session->flash(); ?>
  <form action="<?= Router::url("users/register/hvVtRzMsy"); ?>" method="post">
    <div class="form-group row">
      <div class="col">

        <?= $this->Form->input("login", "Nom d'utilisateur"); ?>
      </div>
      <div class="col">
        <?= $this->Form->input("email", "Adresse mail"); ?>
      </div>
    </div>
    <div class="form-group">
    </div>
    <div class="form-group">
      <?= $this->Form->input("password", "Mot de passe", array("type" => "password")); ?>
    </div>
    <button type="submit" class="btn btn-primary">S'inscrire</button>
</form>
</div>