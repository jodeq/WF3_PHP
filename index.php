<!DOCTYPE HTML>
<html>
<head>
  <!-- Insérer le css ici -->
  <link rel="stylesheet" type="text/css" href="style.css">
  <!-- Jquery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>

  <?php
    require("pokemon.php");
  ?>

  <form>
    <fieldset>
      <legend>Pokemon 1 :
        <select id="pokemon1" name="pokemon1" <?php echo isset($form_error['pokemon1']) ? 'class="error"' : ''; ?>>
          <?php
            foreach($pokemons as $pokemon => $stats) {
              echo '<option value="' . $pokemon . '">' . $pokemon . '</option>';
            }
          ?>
        </select>
      </legend>
      <div>Points de vie : <input type="test" name="pv_pokemon1" value="<?php echo $pikachu['pv']; ?>" <?php echo isset($form_error['pv_pokemon1']) ? 'class="error"' : ''; ?> /></div>
      <div>Points de défense : <input type="test" name="defense_pokemon1" value="<?php echo $pikachu['defense']; ?>" <?php echo isset($form_error['defense_pokemon1']) ? 'class="error"' : ''; ?> /></div>
      <div>Points d'attaque : <input type="test" name="attaque_pokemon1" value="<?php echo $pikachu['attaque']; ?>" <?php echo isset($form_error['attaque_pokemon1']) ? 'class="error"' : ''; ?> /></div>
    </fieldset>
    <fieldset>
      <legend>Pokemon 2 :
        <select id="pokemon2" name="pokemon2" <?php echo isset($form_error['pokemon2']) ? 'class="error"' : ''; ?>>
          <?php
            foreach($pokemons as $pokemon => $stats) {
              echo '<option value="' . $pokemon . '" ' . ($pokemon == 'Bulbizarre' ? 'selected' : '') . '>' . $pokemon . '</option>';
            }
          ?>
        </select>
      </legend>
      <div>Points de vie : <input type="test" name="pv_pokemon2" value="<?php echo $bulbizarre['pv']; ?>" <?php echo isset($form_error['pv_pokemon2']) ? 'class="error"' : ''; ?> /></div>
      <div>Points de défense : <input type="test" name="defense_pokemon2" value="<?php echo $bulbizarre['defense']; ?>" <?php echo isset($form_error['defense_pokemon2']) ? 'class="error"' : ''; ?> /></div>
      <div>Points d'attaque : <input type="test" name="attaque_pokemon2" value="<?php echo $bulbizarre['attaque']; ?>" <?php echo isset($form_error['attaque_pokemon2']) ? 'class="error"' : ''; ?> /></div>
    </fieldset>
    <button type="submit">Combattez !</button>
  </form>

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

    function changePokemon(event) {
      selPokemon = $(this).val();
      // Récupération de la valeur name de l'élément courant
      select = $(this).attr('name');
      $("[name='pv_" + select + "']").val(pokemons[selPokemon]["pv"]);
      $("[name='defense_" + select + "']").val(pokemons[selPokemon]["defense"]);
      $("[name='attaque_" + select + "']").val(pokemons[selPokemon]["attaque"]);
    }

    $(document).ready(function() {
      // Tous les select avec un attribut name qui commence par 'pokemon'
      $("select[name^='pokemon']").on("change", changePokemon);
    });

  </script>

</body>
</html>
