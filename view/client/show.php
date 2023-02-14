<h2 class="text-left"><?= $title ?></h2>
<?= $this->Session->flash(); ?>

<form method="POST">
  <div class="form-group row">
    <div class="col">
      <p>Il y a <strong><?= $client->nb_bouteilles ?></strong> bouteilles dans votre stock</o>
    </div>
  </div>
</form>