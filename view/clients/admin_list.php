<?php $pageName = "clients" ?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
<h2 class="text-center">Liste des Clients</h2>
<a type="button" class="btn btn-info mb-4" href="<?= Router::url("admin/clients/edit/") ?>">Ajouter un client</a>

<table id="cavistes-table" class="table table-striped table-bordered table-hover display">
  <thead>
    <tr>
      <th scope="col" name="name">Nom</th>
      <th scope="col" name="bouteilles" style="width: 1%">Bouteilles</th>
      <th scope="col" name="adresse">Adresse</th>
      <th scope="col" name="actions" style="max-width: 9vw">Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($clients as $key => $client) { ?>
      <?php
        if($client->nb_bouteilles == 0){
          $badgeColor = "success";
        }else if($client->nb_bouteilles < 3){
          $badgeColor = "warning";
        }else{
          $badgeColor = "danger";
        }
        ?>
      <!-- onclick="window.location.href = <= '\'' . Router::url('admin/clients/edit/' . $client->id) . '\'' ?>;" -->
      <tr >
        <td class="bold"><?= $client->name ?></td>
        <td><span class="badge badge-<?= $badgeColor ?>"><?= $client->nb_bouteilles ?></span></td>
        <td><?= $client->adresse ?></td>
        <td>
          <a type="button" class="btn btn-info btn-sm" href="<?= Router::url("admin/clients/edit/" . $client->id) ?>">Editer</a>
          <a type="button" class="btn btn-warning btn-sm" href="<?= Router::url("admin/clients/generateqr/" . $client->id) ?>">QR Code</a>
        </td>
      </tr>

    <?php } ?>

  </tbody>
</table>
<br>

<script>
  $(document).ready(function() {
    $("#cavistes-table").DataTable({
      language: {
        "url": "//cdn.datatables.net/plug-ins/1.10.22/i18n/French.json"
      },
      pageLength: 25,
      columns: [
        {
          name: "name",
          orderable: true,
        },
        {
          name: "bouteilles",
          orderable: true,
        },
        {
          name: "adresse",
          orderable: false,
        },
        {
          name: "actions",
          orderable: false,
        },
        
      ],
      order: [[ 1, "desc" ]]
    });
  });
</script>