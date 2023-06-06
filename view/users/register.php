<div class="page-header">
  <h1>Inscription</h1>
  <?php echo $this->Session->flash(); ?>
  <form action="<?= Router::url("users/register"); ?>" id="form" method="post">
    <div class="form-group row">
      <div class="col">

        <?= $this->Form->input("firstname", "Prénom", array("invalidFeedback" => "", "required" => "true")); ?>
      </div>
      <div class="col">
        <?= $this->Form->input("login", "Nom d'utilisateur", array("invalidFeedback" => "", "required" => "true")); ?>
      </div>
    </div>
    <div class="form-group row">
      <div class="col">
        <?= $this->Form->input("email", "Adresse mail", array("invalidFeedback" => "", "required" => "true", "type" => "email")); ?>
      </div>
    </div>
    <div class="form-group row">
      <div class="col">
        <?= $this->Form->input("password", "Mot de passe", array("type" => "passwordWithBtn", "invalidFeedback" => "", "required" => "true")); ?>
      </div>
      <div class="col">
        <?= $this->Form->input("passwordConfirm", "Confirmation", array("type" => "passwordWithBtn","invalidFeedback" => "", "required" => "true")); ?>
      </div>
    </div>
    <div class="form-group">
    </div>
    <div class="form-group">
      Déjà un compte ? <a href="<?= Router::url('users/login'); ?>">Se connecter</a>
    </div>
    <button type="submit" class="btn btn-primary">S'inscrire</button>
  </form>

</div>

<script>

  
  $(document).ready(function() {
    $('#show-password, #show-passwordConfirm').click(function() {
      var id = $(this).attr('id').split('-')[1];
      $('#' + id).attr('type', $('#' + id).attr('type') == 'password' ? 'text' : 'password');
      $(this).find('i').toggleClass('fa-eye-slash fa-eye');
    });
  });

  function isValidEmail(email) {
    var emailRegex = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/.test(email);
  }

  var verificators = {
    "login": function(val) {
      return val == '' ? "Veillez remplir ce champ." : false;
    },
    "email": function(val) {
      return (val == '' || !isValidEmail(val)) ? "L'adresse email n'est pas valide" : false;
    },
    "password": function(val) {
      var passwordRegex = /^^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,30}$/;
      return !passwordRegex.test(val) ? 'Le mot de passe doit comporter au moins 5 caractères, une majuscule et un chiffre.' : false;
    },
    "passwordConfirm": function(val) {
      return val == '' || val !== $('#password').val() ? "Les mots de passe ne correspondent pas." : false;
    }
  }
  
  function verif(){
    Object.keys(verificators).forEach(function(key) {
      var verificator = verificators[key];
      var error = verificator($('#' + key).val());
      if (error) {
        errors += 1;
        $('#' + key).addClass('is-invalid');
        $('#invalid-' + key).html(error);
      } else {
        $('#' + key).removeClass('is-invalid');
        $('#' + key).addClass('is-valid');
      }
    });
    console.log(errors);
    if (errors <= 0) {
      $('#form').unbind('submit').submit();
    }
  }

  let phpErrors = <?php echo json_encode($errors ?? null) ?>;
  var errors = 0;
  $(document).ready(function() {
    if(phpErrors) verif();
    if(errors > 0) $('input').on("input", verif);
    $('#form').submit(function(event) {
      event.preventDefault();
      verif();
    });
  });

</script>