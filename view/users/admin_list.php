<?php $pageName = "users" ?>
<script type="text/javascript" src="<?= Router::webroot("vendor/js/jquery.dataTables.min.js"); ?>"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
<h2 class="text-center">Liste des Utilisateurs</h2>

<?= $this->Session->flash(); ?>
<a type="button" class="btn btn-info mb-4" href="<?= Router::url("admin/users/edit/") ?>">Ajouter un utilisateur</a>
  <div class="modal fade" id="resetModal" tabindex="1" role="dialog" aria-labelledby="resetModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id=resetModalLabel> Supprimer cet utilisateur </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"> &times; </span>
          </button>
        </div>
        <div class="modal-body">Voulez-vous remettre à 0 le nombre de bouteilles ?</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal"> Annuler </button>
          <form action="" method="POST">
            <button type="submit" class="btn btn-warning btn-block">Mettre à 0</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  

  
<table id="users-table" class="table table-striped table-bordered table-hover display">
  <thead>
    <tr>
      <th scope="col" name="id">ID</th>
      <th scope="col" name="firstname">Prénom</th>
      <th scope="col" name="login">Nom d'utilisateur</th>
      <th scope="col" name="email">Adresse E-mail</th>
      <th scope="col" name="role">Role</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $key => $user) { ?>
      <tr>
        <td class="bold"><?= $user->id ?></td>
        <td class="bold"><?= $user->firstname ?></td>
        <td class="bold"><?= $user->login ?></td>
        <td class="bold"><?= $user->email ?></td>
        <td class="bold"><?= $user->role ?></td>
      </tr>

    <?php } ?>

  </tbody>
</table>
<br>


<script>
  $(document).ready(function() {
    $("#users-table").DataTable({
      language: {
        "url": "//cdn.datatables.net/plug-ins/1.10.22/i18n/French.json"
      },
      pageLength: 50,
      columns: [
        {
          name: "id",
          orderable: false,
        },
        {
          name: "firstname",
          orderable: true,
        },
        {
          name: "login",
          orderable: true,
        },
        {
          name: "email",
          orderable: true,
        },
        {
          name: "role",
          orderable: true,
        },
      ],
      order: [
        [1, "desc"]
      ]
    });
  });
</script>