<?= $this->Session->flash(); ?>
<img src="<?= Router::webroot("img/illustration.jpeg"); ?>" class="img-illustration" alt="illustration mipadre">
<div class="text-center">
  <div class="clientName">
    <h2><?= $client->name ?></h2>
  </div>
</div>
<div class="text-center">
  <p>Vous avez <strong><?= $client->nb_bouteilles ?></strong> <?= $client->nb_bouteilles > 1 ? "bouteilles revenues" : "bouteille revenue" ?></o>
</div>

<div class="text-center">
  <a href="<?= Router::url("client/edit") ?>" class="btn btn-primary">
    Modification
  </a>
</div>
 
