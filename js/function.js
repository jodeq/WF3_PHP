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
