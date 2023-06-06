<h2 class="text-left"><?= $title ?></h2>

<?= $this->Session->flash(); ?>
<a href="<?= Router::url('admin/users/list') ?>" class="btn btn-info btn-sm  mb-3">Retour</a>

<form method="POST">
  <div class="form-group row">
    <div class="col-md-5">
      <?= $this->Form->input("firstname", "Prénom", array("required" => false)) ?>
    </div>
    <div class="col">
      <?= $this->Form->input("login", "Nom d'utilisateur", array("required" => true)) ?>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-md-5">
      <?= $this->Form->input("email", "Adresse email", array("type" => "email")) ?>
    </div>
    <div class="col-md-5">
    <?= $this->Form->input("role", "Role", array("type" => "select", "defaultVal" => "user", "options" => array(
      "user" => "Utilisateur",
      "admin" => "Administrateur"
    ))) ?>
    </div>
  </div>
  <div class="form-group row">
    <div class="col-md-5">
      <?= $this->Form->input("password", "Nouveau Mot de passe", array("type" => "password")) ?>
    </div>
    </div>
  <button type="submit" class="btn btn-primary mt-4 mb-3"><?= $mode == "edit" ? "Enregistrer les modifications" : "Créer l'utilisateur" ?></button>
</form>

<?php if ($mode == "edit") : ?>
  <button type="button" class="btn btn-danger mb-5" data-toggle="modal" data-target="#deleteModal">Supprimer</button>
  <div class="modal fade" id=deleteModal tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id=deleteModalLabel> Supprimer cet utilisateur </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"> &times; </span>
          </button>
        </div>
        <div class="modal-body">Voulez vous vraiment supprimer cet utilisateur ?</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"> Annuler </button>
          <form action="<?= Router::url('admin/users/delete/' . $id) ?>" method="POST">
            <button type="submit" class="btn btn-danger btn-block">Supprimer</button>
          </form>
        </div>
      </div>
    </div>
  </div>

<?php endif; ?>