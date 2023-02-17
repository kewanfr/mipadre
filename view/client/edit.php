<h2 class="text-center">Modification</h2>
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

<form action="<?= Router::url("client/edit") ?>" method="post">
  <div class="group">
    <label for="nb_bouteilles">Modification</label>
    <div class="input-number-container">
      <button type="button" class="input-number-button input-number-decrement">-</button>
      <input type="number" class="input-number" name="nb_bouteilles" value="<?= $client->nb_bouteilles ?>" min="0" step="1">
      <button type="button" class="input-number-button input-number-increment">+</button>
    </div>
    <button type="submit" class="btn btn-success">Valider</button>
  </div>
</form>

<p  id="last-editbox">Vous avez modifé vos consignes <?= RelativeDatetime($client->updated) ?></p>
 
<br>
<script>
  $(".input-number-increment").click(function() {
    var input = $(this).parent().find(".input-number");
    var currentVal = parseInt(input.val());
    if (!isNaN(currentVal)) {
      input.val(currentVal + 1);
    } else {
      input.val(0);
    }
  });

  $(".input-number-decrement").click(function() {
    var input = $(this).parent().find(".input-number");
    var currentVal = parseInt(input.val());
    if (!isNaN(currentVal) && currentVal > 0) {
      input.val(currentVal - 1);
    } else {
      input.val(0);
    }
  });
</script>