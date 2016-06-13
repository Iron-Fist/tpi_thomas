<?php

require_once('./librairie/errors.php');

/**
 * Verifie si les données saisie correspondent à celle 
 * d'un des utilisateurs de la base de donnée
 * @param int $num_licence
 * @param string $mdp
 * @return array $data
 */
function connexion($num_licence, $mdp) {
    $query = connectDB()->prepare("SELECT * FROM `t_membres` WHERE num_licence = ? AND mdp = ?");
    $query->execute([$num_licence, sha1($mdp)]);
    $data = $query->fetch(PDO::FETCH_ASSOC);
    return $data;
}

/**
 * Vérifie si il y a déjà un num_licence = $num_licence dans la t_membres
 * @staticvar var $query
 * @param int $num_licence
 * @return boolean
 */
function num_licence_existe($num_licence) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT * FROM `t_membres` WHERE num_licence = ?");
    }
    $query->execute([$num_licence]);

    $data = $query->fetch(PDO::FETCH_ASSOC);

    if ($data === false)
        return false;
    else
        return true;
}

/**
 * Ajout d'un nouvel enregistrement dans la table t_membres
 * @staticvar type $query
 * @param int $num_licence
 * @param string $nom
 * @param string $prenom
 * @param date $date_naissance
 * @param string $mdp
 */
function creer_membre($num_licence, $nom, $prenom, $date_naissance, $mdp) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("INSERT INTO t_membres(num_licence, nom, prenom, date_naissance, mdp) 
    VALUES (:num_licence, :nom, :prenom, :date_naissance, :mdp)");
    }

    $query->execute([
        "num_licence" => $num_licence,
        "nom" => $nom,
        "prenom" => $prenom,
        "date_naissance" => $date_naissance,
        "mdp" => sha1($mdp)
    ]);
}

/**
 * Modification d'un enregistrement de la table t_membres
 * @param int $id_membre
 * @param int $num_licence
 * @param string $nom
 * @param string $prenom
 * @param date $date_naissance
 * @param string $mdp
 */
function modifier_membre($id_membre, $num_licence, $nom, $prenom, $date_naissance, $mdp) {
    $query = connectDB()->prepare("UPDATE t_membres
                                   SET num_licence=:num_licence, nom=:nom, prenom=:prenom, date_naissance=:date_naissance, mdp=:mdp
                                   WHERE id_membre=:id_membre");
    $data = array(
        "id_membre" => $id_membre,
        "num_licence" => $num_licence,
        "nom" => $nom,
        "prenom" => $prenom,
        "date_naissance" => $date_naissance,
        "mdp" => sha1($mdp)
    );

    $query->execute($data);
}

/**
 * Modification d'un enregistrement de la table t_membres excepté le champ mdp
 * @param int $id_membre
 * @param int $num_licence
 * @param string $nom
 * @param string $prenom
 * @param date $date_naissance
 */
function modifier_membre_sans_mdp($id_membre, $num_licence, $nom, $prenom, $date_naissance) {
    $query = connectDB()->prepare("UPDATE t_membres
                                   SET num_licence=:num_licence, nom=:nom, prenom=:prenom, date_naissance=:date_naissance
                                   WHERE id_membre=:id_membre");
    $data = array(
        "id_membre" => $id_membre,
        "num_licence" => $num_licence,
        "nom" => $nom,
        "prenom" => $prenom,
        "date_naissance" => $date_naissance,
    );

    $query->execute($data);
}

/**
 * Création d'un tableau prêt à accueillir des données
 * @return array
 */
function charger_nouveau_membre() {
    return array(
        "id_membre" => -1,
        "num_licence" => "",
        "mdp" => "",
        "nom" => "",
        "prenom" => "",
        "date_naissance" => ""
    );
}

/**
 * Chargement d'un enregistrement de la table t_membres ayant comme id_membre : $id_membre
 * @param int $id_membre
 * @return array $data
 */
function charger_donnees_membre($id_membre) {
    $query = connectDB()->prepare("SELECT * FROM `t_membres` WHERE id_membre = ?");
    $query->execute([$id_membre]);
    $data = $query->fetch(PDO::FETCH_ASSOC);
    return $data;
}

/**
 * Verifie que $date_naissance remplie les critère du preg_match
 * et qu'elle plus petite que la date d'aujourd'hui
 * @param date $date_naissance
 * @return boolean
 */
function date_naissance_valide($date_naissance) {
    if (preg_match("#^([0-9]{4})-([0-9]{2})-([0-9]{2})$#", $date_naissance, $matches)) {
        if (checkdate($matches[2], $matches[3], $matches[1])) {
            if ($date_naissance < date('Y-m-d')) {
                return true;
            }
        }
    }
    return false;
}

/**
 * Création d'un tableau de tout les enregistrements de la table t_membres
 * qui on comme champ est_valide = 0
 * Avec comme possiblité la validation ou le rejet d'un nouveau compte membre
 * @staticvar var $query
 */
function tableau_membre_non_valide() {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT * FROM `t_membres` WHERE `est_valide` = 0");
    }
    $query->execute();

    if ($query->rowCount() == 0) {
        echo '<tr>';
        echo '<td colspan="5" class="text-center">Il n\'y a aucun membre en attente de validation.</td>';
        echo '</tr>';
    } else {
        while (($data = $query->fetch(PDO::FETCH_ASSOC)) !== false) {
            echo '<tr>';
            echo '<td>' . $data['num_licence'] . '</td>';
            echo '<td>' . $data['nom'] . '</td>';
            echo '<td>' . $data['prenom'] . '</td>';
            echo '<td>' . date_format(date_create($data['date_naissance']), "d/m/Y") . '</td>';
            echo '<td><a href="#" data-toggle="modal" data-target="#' . $data['num_licence'] . '"><span class="glyphicon glyphicon-ok"></span></a> '
                    . '<a href="#" data-toggle="modal" data-target="#' . $data['id_membre'] . '"><span class="glyphicon glyphicon-remove"></span></a></td>';
            echo '</tr>';
            
            creer_modale($data['num_licence'], 'Validation d\'un membre', 'Voulez-vous vraiment accepter ' . $data['prenom'] . " ". $data['nom'] . " ?",
                '<a type="button" class="btn btn-primary" href="suppression-validation-inscription.php?id_membre_valide=' . $data["id_membre"] . '">Oui</a>');
            
            creer_modale($data['id_membre'], 'Refus de validation d\'un membre', 'Voulez-vous vraiment refuser ' . $data['prenom'] . " ". $data['nom'] . " ?",
                '<a type="button" class="btn btn-primary" href="suppression-validation-inscription.php?id_membre_suppression=' . $data["id_membre"] . '">Oui</a>');
        }
    }
}

/**
 * Création d'un tableau avec tout les enregistrement qui ont leur champ
 * est_valide de la table t_membres qui est = 1
 * @staticvar var $query
 */
function tableau_membre_valide() {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT * FROM `t_membres` WHERE `est_valide` = 1");
    }
    $query->execute();

    while (($data = $query->fetch(PDO::FETCH_ASSOC)) !== false) {
        echo '<tr>';
        echo '<td>' . $data['num_licence'] . '</td>';
        echo '<td>' . $data['nom'] . '</td>';
        echo '<td>' . $data['prenom'] . '</td>';
        echo '<td>' . date_format(date_create($data['date_naissance']), "d/m/Y") . '</td>';
        echo '<td><a href="creer-modifier-membres.php?id_membre_modification=' . $data["id_membre"] . '"><span class="glyphicon glyphicon-wrench"></span></a>';
        if (est_inscrit_concours($data['id_membre']))
            echo '</td>';
        else
            echo " " . '<a href="#" data-toggle="modal" data-target="#' . $data['id_membre'] . '"><span class="glyphicon glyphicon-trash"></span></a></td>';
        echo '</tr>';
        
        creer_modale($data['id_membre'], 'Suppression d\'un compte membre', 'Voulez-vous vraiment supprimer ' . $data['prenom'] . " ". $data['nom'] . " ?",
                '<a type="button" class="btn btn-primary" href="suppression-validation-inscription.php?id_membre_suppression=' . $data["id_membre"] . '">Oui</a>');
    }
}

/**
 * verifie si il y a déjà un id_membre = $id_membre dans la table t_inscrits
 * @staticvar var $query
 * @param int $id_membre
 * @return boolean
 */
function est_inscrit_concours($id_membre) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT * FROM `t_inscrits` WHERE id_membre = ?");
    }
    $query->execute([$id_membre]);

    $data = $query->fetch(PDO::FETCH_ASSOC);

    if ($data === false)
        return false;
    else
        return true;
}

/**
 * Modification du champ est_valide de la table t_membres
 * @param int $id_membre
 */
function validation_membre($id_membre) {
    $query = connectDB()->prepare("UPDATE t_membres
                                       SET est_valide=:est_valide
                                     WHERE id_membre=:id_membre");
    $data = array(
        "est_valide" => 1,
        "id_membre" => $id_membre
    );

    $query->execute($data);
}

/**
 * Suppression d'un enregistrement présent dans la table
 * t_membres qui à comme id_membre : $id-membre
 * @param int $id_membre
 */
function supprimer_membre($id_membre) {
    $query = connectDB()->prepare("DELETE FROM t_membres WHERE id_membre = ?");
    $query->execute([$id_membre]);
}

/**
 * Vérifie si $num_licence a bien 5 chiffres compris entre 0-9
 * @param int $num_licence
 * @return boolean
 */
function num_licence_valide($num_licence) {
    return preg_match("([0-9]{5})", $date_concours, $matches);
}

/**
 * Vérification des données saisie dans le formulaire de la page
 * creer-modifier-membres.php
 * @param array $membre
 * @param string $mdp
 * @param string $mdp_verif
 * @param boolean $cree_nouveau_membre
 * @return string
 */
function validation_creation_modification_membre($membre, $mdp, $mdp_verif, $cree_nouveau_membre) {
    $message_erreur = "";

    if ($membre['num_licence'] != "") {
        if (preg_match("/^([0-9]{5})$/", $membre['num_licence'], $matches)) {
            if ($cree_nouveau_membre) {
                if (num_licence_existe($membre['num_licence'])) {
                    return $message_erreur = "Votre <b>Numéro de licence</b> est déjà utilisé.";
                    exit();
                }
            }

            if ($membre['nom'] != "") {
                if ($membre['prenom'] != "") {
                    if (date_naissance_valide($membre['date_naissance'])) {
                        if ($mdp != "" && $mdp_verif != "") {
                            if ($mdp == $mdp_verif) {
                                if ($cree_nouveau_membre) {
                                    creer_membre($membre['num_licence'], $membre['nom'], $membre['prenom'], $membre['date_naissance'], $mdp);
                                    header('Location: connexion.php');
                                } else {
                                    modifier_membre($membre['id_membre'], $membre['num_licence'], $membre['nom'], $membre['prenom'], $membre['date_naissance'], $mdp);
                                    header('Location: administration-membres.php');
                                }
                            } else {
                                $message_erreur = "Vos deux mots de passe ne sont pas identiques";
                            }
                        } else {
                            if (!$cree_nouveau_membre) {
                                modifier_membre_sans_mdp($membre['id_membre'], $membre['num_licence'], $membre['nom'], $membre['prenom'], $membre['date_naissance']);
                                header('Location: administration-membres.php');
                            }
                            $message_erreur = "Les deux champ de mot de passe sont vides.";
                        }
                    } else {
                        $message_erreur = "Le champ <b>Date de naissance</b> est invalide.";
                    }
                } else {
                    $message_erreur = "Le champ <b>Prénom</b> est vide.";
                }
            } else {
                $message_erreur = "Le champ <b>Nom</b> est vide.";
            }
        } else {
            $message_erreur = "Votre <b>Numéro de licence</b> ne contient pas exactement 5 chiffres.";
        }
    } else {
        $message_erreur = "Le champ <b>Numéro de licence</b> est vide.";
    }

    return $message_erreur;
}
