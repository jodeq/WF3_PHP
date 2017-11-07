
--Pokemon : id, numero, nom, experience, vie, defense, attaque, id_pokedex
--Pokedex : id, nom_proprietaire

create table pokedex (
    id INTEGER NOT NULL AUTO_INCREMENT,
    nom_proprietaire VARCHAR(50) NOT NULL UNIQUE,
    PRIMARY KEY (id)
);

create table pokemon (
    id INTEGER NOT NULL AUTO_INCREMENT,
    numero INTEGER NOT NULL,
    nom VARCHAR(50) NOT NULL,
    experience INTEGER NOT NULL DEFAULT 0,
    vie INTEGER NOT NULL,
    defense INTEGER NOT NULL,
    attaque INTEGER NOT NULL,
    id_pokedex INTEGER,
    PRIMARY KEY (id),
    FOREIGN KEY (id_pokedex) REFERENCES pokedex(id)ON DELETE SET NULL
);
