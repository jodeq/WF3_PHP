
function formSubmit(id) {
  form = $("#" + id);
  if (form.length == 0) {
    console.log("Formulaire " + id + " inconnu");
    return;
  }
  for (i = 1; i < arguments.length; i = i + 2) {
    if (arguments[i + 1] != null)
      $('#' + arguments[i]).val(arguments[i + 1]);
  }
  form.submit();
}


function changePokemon(event) {
  selPokemon = $(this).val();
  // Récupération de la valeur name de l'élément courant
  select = $(this).attr('name');
  // Si aucun pokemon n'est sélectionné
  if (selPokemon == '') {
    $("[name='pv_" + select + "']").val(null);
    $("[name='defense_" + select + "']").val(null);
    $("[name='attaque_" + select + "']").val(null);
  } else {
    $("[name='pv_" + select + "']").val(pokemons[selPokemon]["pv"]);
    $("[name='defense_" + select + "']").val(pokemons[selPokemon]["defense"]);
    $("[name='attaque_" + select + "']").val(pokemons[selPokemon]["attaque"]);
  }
}
