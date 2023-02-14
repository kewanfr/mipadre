<h2 class="text-left"><?= $title ?></h2>
<?= $this->Session->flash(); ?>

<a href="<?= Router::url('admin/clients') ?>" class="btn btn-info btn-sm  mb-3">Retour</a>

<form method="POST">
  <div class="form-group row">
    <div class="col-md-1">
      <?= $this->Form->input("id", "ID", array("readonly" => true)) ?>
    </div>
    <div class="col">
      <?= $this->Form->input("name", "Nom", array("required" => true)) ?>
    </div>
  </div>
  <div class="form-group">
    <?= $this->Form->input("mail", "Adresse email") ?>

  </div>
  <div class="form-group row">
    <div class="col-md-7">
      <?= $this->Form->input("adresse", "Adresse") ?>
    </div>
    <div class="col">
      <?= $this->Form->input("telephone", "Téléphone") ?>
    </div>
  </div>

  <div class="form-group row">
    <div class="col-md-2">
      <?= $this->Form->input("nb_bouteilles", "Nombre de bouteilles", array("type" => "number", "defaultVal" => 0)) ?>
    </div>
  </div>
  <?php if ($mode == "edit") : ?>
    <a class="btn btn-warning mt-4  mb-3" target="_blank" href="<?= Router::url('admin/clients/generateqr/' . $id) ?>"> Générer un QR Code de connexion </a>
  <?php endif; ?>
  <button type="submit" class="btn btn-primary mt-4 mb-3"><?= $mode == "edit" ? "Enregistrer les modifications" : "Créer le client" ?></button>

</form>

<?php if ($mode == "edit") : ?>
  <button type="button" class="btn btn-danger mb-5" data-toggle="modal" data-target="#deleteModal">Supprimer</button>
  <div class="modal fade" id=deleteModal tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id=deleteModalLabel> Supprimer ce client </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"> &times; </span>
          </button>
        </div>
        <div class="modal-body">Voulez vous vraiment supprimer ce client ?</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"> Annuler </button>
          <form action="<?= Router::url('admin/clients/delete/' . $id) ?>" method="POST">
            <button type="submit" class="btn btn-danger btn-block">Supprimer</button>
          </form>
        </div>
      </div>
    </div>
  </div>

<?php endif; ?>