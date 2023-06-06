<?php $pageName = "users" ?>
<script type="text/javascript" src="<?= Router::webroot("vendor/js/jquery.dataTables.min.js"); ?>"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
<h2 class="text-center">Liste des Utilisateurs</h2>

<?= $this->Session->flash(); ?>
<a type="button" class="btn btn-info mb-4" href="<?= Router::url("admin/users/edit/") ?>">Créer un utilisateur</a>
  
<table id="users-table" class="table table-striped table-bordered table-hover display">
  <thead>
    <tr>
      <th scope="col" name="id">ID</th>
      <th scope="col" name="firstname">Prénom</th>
      <th scope="col" name="lastname">Nom</th>
      <th scope="col" name="login">Pseudo</th>
      <th scope="col" name="email">Adresse E-mail</th>
      <th scope="col" name="role">Statut</th>
      <th scope="col" name="actions"></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $key => $user) { ?>
      <tr>
        <td class="bold"><?= $user->id ?></td>
        <td class="bold"><?= $user->firstname ?></td>
        <td class="bold"><?= $user->lastname ?></td>
        <td class="bold"><?= $user->login ?></td>
        <td class="bold"><?= $user->email ?></td>
        <!-- Si pas admin, compte désactivé avec pastille -->
        <td class="bold"><?= $user->role != "user" ? $user->role : "<span class='badge badge-danger'>Désactivé</span>" ?></td>
        
        <td>
          <div class="nowrap">
            <a type="button" class="btn btn-info btn-sm" href="<?= Router::url("admin/users/edit/" . $user->id) ?>">Editer</a>
            <?php if ($user->role == "user") { ?>
              <a type="button" class="btn btn-success btn-sm" href="<?= Router::url("admin/users/activate/" . $user->id) ?>">Activer</a>
            <?php } ?>
          </div>

        </td>
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
          name: "lastname",
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
        {
          name: "actions",
          orderable: false,
        },
      ],
      order: [
        [0, "asc"]
      ]
    });
  });
</script>