<div class="page-header">
  <h1>Compte en attente de validation</h1>
  <?php echo $this->Session->flash(); ?>

  <!-- Cette page s'affiche après que l'utilisateur se soit inscrit et n'ai accès à rien sans contacter un administrateur -->
  <!-- Votre compte est en attente de validation par un administrateur. Vous ne pouvez pas accéder au site pour le moment.
  Vous recevrez un mail lorsque votre compte sera validé.
  Si vous n'avez pas reçu de mail, veuillez contacter un administrateur. -->

  <!-- Affiche de manière jolie -->
  <br>
  <div class="alert alert-info" role="alert">
    <h4 class="alert-heading">Votre compte est en attente de validation par un administrateur.</h4>
    <p>Vous ne pouvez pas accéder au site pour le moment. Vous recevrez un mail lorsque votre compte sera validé.</p>
    <hr>
    <p class="mb-0">Si vous n'avez pas reçu de mail, veuillez contacter un administrateur.</p>
  </div>

</div>
