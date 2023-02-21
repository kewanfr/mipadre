<?php $pageName = "login" ?>

<h1>Profil</h1>
<?= $this->Session->flash(); ?>

<div class="row mt-3">
  <div class="col-lg-3">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Mon compte</h5>
      </div>
      <div class="list-group list-group-flush" role="tablist">
        <a class="list-group-item list-group-item-action" data-toggle="list" href="#informations" role="tab">Mes informations</a>
        <a class="list-group-item list-group-item-action" data-toggle="list" href="#login" role="tab">Nom d'utilisateur</a>
        <a class="list-group-item list-group-item-action" data-toggle="list" href="#email" role="tab">Adresse email</a>
        <a class="list-group-item list-group-item-action" data-toggle="list" href="#password" role="tab">Mot de passe</a>
      </div>
    </div>
  </div>


  <div class="col-lg-9">
    <div class="tab-content">

      <div class="tab-pane" id="informations" role="tabpanel">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title mb-0">Mes informations</h5>
          </div>
          <div class="card-body">
            <p>Nom d'utilisateur : <strong><?= $user->login; ?></strong></p>
            <p>Email : <strong><?= $user->email; ?></strong></p>
            <p>Permission : <strong><?= $user->role; ?></strong></p>
          </div>
        </div>
      </div>

      <div class="tab-pane" id="login" role="tabpanel">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title mb-0">Modifier le nom d'utilisateur</h5>
          </div>
          <div class="card-body">
            <form method="post" action="<?= Router::url("users/update/login") ?>">
              <div class="form-group">
                <label for="login">Nouveau nom d'utilisateur :</label>
                <input type="text" class="form-control" id="login" name="login" value="<?= $user->login ?>">
              </div>
              <button type="submit" class="btn btn-primary">Modifier</button>
            </form>
          </div>
        </div>
      </div>

      <div class="tab-pane" id="email" role="tabpanel">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title mb-0">Modifier l'email</h5>
          </div>
          <div class="card-body">
            <form method="post" action="<?= Router::url("users/update/email") ?>">
              <div class="form-group">
                <label for="email">Nouvel email :</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= $user->email ?>" required>
              </div>
              <button type="submit" class="btn btn-primary">Modifier</button>
            </form>
          </div>
        </div>
      </div>

      <div class="tab-pane" id="password" role="tabpanel">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title mb-0">Modifier le mot de passe</h5>
          </div>
          <div class="card-body">
            <form method="post" action="<?= Router::url("users/update/password") ?>">

              <div class="form-group">
                <label for="old_password">Ancien mot de passe :</label>
                <div class="input-group">
                  <input type="password" name="old_password" id="old_password" class="form-control" required autocomplete="on">
                  <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="show-old_password">
                      <i class="fa fa-eye-slash"></i>
                    </button>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="new_password">Nouveau mot de passe :</label>
                <div class="input-group">
                  <input type="password" name="new_password" id="new_password" class="form-control" required autocomplete="on">
                  <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="show-new_password">
                      <i class="fa fa-eye-slash"></i>
                    </button>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="confirm_new_password">Confirmer le nouveau mot de passe :</label>
                <div class="input-group">
                  <input type="password" name="confirm_new_password" id="confirm_new_password" class="form-control" required autocomplete="on">
                  <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="show-confirm_new_password">
                      <i class="fa fa-eye-slash"></i>
                    </button>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary">Modifier</button>
            </form>
          </div>
        </div>
      </div>


    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#show-old_password, #show-new_password, #show-confirm_new_password').click(function() {
      var id = $(this).attr('id').split('-')[1];
      $('#' + id).attr('type', $('#' + id).attr('type') == 'password' ? 'text' : 'password');
      $(this).find('i').toggleClass('fa-eye-slash fa-eye');
    });
  });

  var hash = window.location.hash;

  if (hash == "" && hash != "#login" && hash != "#email" && hash != "#password") {
    hash = "#informations";
  }

  $(".nav-link").removeClass("active");
  $("a[href='" + hash + "']").addClass("active");
  $(hash).addClass("active");

  $(".list-group-item").on("click", function() {
    window.location.hash = $(this).attr("href");
  });
</script>