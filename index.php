<!DOCTYPE HTML>
<html>
<head>
  <!-- Insérer le css ici -->
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <!-- Jquery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
  <img src="img/arene.jpg"/>

  <?php
    require("inc/pokemon.php");
  ?>

  <form method="post">
    <fieldset>
      <legend>Pokemon 1 :
        <select name="pokemon1" <?php echo isset($form_error['pokemon1']) ? 'class="error"' : ''; ?>>
          <option value="">- Aucun -</option>
          <?php
            foreach($pokemons as $pokemon => $stats) {
              echo '<option value="' . $pokemon . '" ' . (isset($nom_pokemon1) && $pokemon == $nom_pokemon1 ? 'selected' : '') . '>' . $pokemon . '</option>';
            }
          ?>
        </select>
      </legend>
      <div>Points de vie : <input type="test" name="pv_pokemon1" value="<?php echo isset($pokemon1) ? $pokemon1['pv'] : ''; ?>" <?php echo isset($form_error['pv_pokemon1']) ? 'class="error"' : ''; ?> /></div>
      <div>Points de défense : <input type="test" name="defense_pokemon1" value="<?php echo isset($pokemon1) ? $pokemon1['defense'] : ''; ?>" <?php echo isset($form_error['defense_pokemon1']) ? 'class="error"' : ''; ?> /></div>
      <div>Points d'attaque : <input type="test" name="attaque_pokemon1" value="<?php echo isset($pokemon1) ? $pokemon1['attaque'] : ''; ?>" <?php echo isset($form_error['attaque_pokemon1']) ? 'class="error"' : ''; ?> /></div>
    </fieldset>
    <fieldset>
      <legend>Pokemon 2 :
        <select id="pokemon2" name="pokemon2" <?php echo isset($form_error['pokemon2']) ? 'class="error"' : ''; ?>>
          <option value="">- Aucun -</option>
          <?php
            foreach($pokemons as $pokemon => $stats) {
              echo '<option value="' . $pokemon . '" ' . (isset($nom_pokemon2) && $pokemon == $nom_pokemon2 ? 'selected' : '') . '>' . $pokemon . '</option>';
            }
          ?>
        </select>
      </legend>
      <div>Points de vie : <input type="test" name="pv_pokemon2" value="<?php echo isset($pokemon2) ? $pokemon2['pv'] : ''; ?>" <?php echo isset($form_error['pv_pokemon2']) ? 'class="error"' : ''; ?> /></div>
      <div>Points de défense : <input type="test" name="defense_pokemon2" value="<?php echo isset($pokemon2) ? $pokemon2['defense'] : ''; ?>" <?php echo isset($form_error['defense_pokemon2']) ? 'class="error"' : ''; ?> /></div>
      <div>Points d'attaque : <input type="test" name="attaque_pokemon2" value="<?php echo isset($pokemon2) ? $pokemon2['attaque'] : ''; ?>" <?php echo isset($form_error['attaque_pokemon2']) ? 'class="error"' : ''; ?> /></div>
    </fieldset>
    <button type="submit">Combattez !</button>
  </form>

  <script type="text/javascript" src="js/function.js"></script>
  <script type="text/javascript">
    var pokemons = [];
    <?php
      foreach($pokemons as $pokemon => $stats) {
        echo 'pokemons["' . $pokemon . '"] = [];' . "\n";
        foreach ($stats as $cle => $valeur) {
          echo 'pokemons["' . $pokemon . '"]["' . $cle . '"] = ' . $valeur . "\n";
        }
      }
    ?>

    $(document).ready(function() {
      // Tous les select avec un attribut name qui commence par 'pokemon'
      $("select[name^='pokemon']").on("change", changePokemon);
    });

  </script>

</body>
</html>
