<?php

require_once('./librairie/errors.php');

/**
 * Verifie si les données saisie correspondent à celle 
 * d'un des utilisateurs de la base de donnée
 * @param type $pseudo
 * @param type $pwd
 * @return array $data
 */
function connexion($num_licence, $mdp) {
    $query = connectDB()->prepare("SELECT * FROM `t_membres` WHERE num_licence = ? AND mdp = ?");
    $query->execute([$num_licence, sha1($mdp)]);
    $data = $query->fetch(PDO::FETCH_ASSOC);
    return $data;
}

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

function charger_donnees_membre($id_membre) {
    $query = connectDB()->prepare("SELECT * FROM `t_membres` WHERE id_membre = ?");
    $query->execute([$id_membre]);
    $data = $query->fetch(PDO::FETCH_ASSOC);
    return $data;
}

function date_naissance_valide($date_naissance) {
    if (preg_match("#^([0-9]{4})-([0-9]{2})-([0-9]{2})$#", $date_naissance, $matches)) {
        if(checkdate($matches[2], $matches[3], $matches[1])){
            if($date_naissance < date('Y-m-d')){
                return true;
            }
        }
    }
    return false;
}

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
            echo '<td><a href="suppression-validation-inscription.php?id_membre_valide=' . $data["id_membre"] . '"><span class="glyphicon glyphicon-ok"></span></a></td>';
            echo '</tr>';
        }
    }
}

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
        if(est_inscrit_concours($data['id_membre']))
            echo '</td>';
        else
        echo " " . '<a href="suppression-validation-inscription.php?id_membre_suppression=' . $data["id_membre"] . '"><span class="glyphicon glyphicon-trash"></span></a></td>';
        echo '</tr>';
    }
}

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

function supprimer_membre($id_membre) {
    $query = connectDB()->prepare("DELETE FROM t_membres WHERE id_membre = ?");
    $query->execute([$id_membre]);
    $data = $query->fetch(PDO::FETCH_ASSOC);
    return $data;
}

function num_licence_valide($num_licence) {
    return preg_match("([0-9]{5})", $date_concours, $matches);
}

function creation_membre_valide($membre, $mdp, $mdp_verif) {
    $message_erreur = "";

    if ($membre['num_licence'] != "") {
        if (preg_match("/^([0-9]{5})$/", $membre['num_licence'], $matches)) {
            if (num_licence_existe($membre['num_licence']) === false) {
                if ($membre['nom'] != "") {
                    if ($membre['prenom'] != "") {
                        if (date_naissance_valide($membre['date_naissance'])) {
                            if ($mdp != "" && $mdp_verif != "") {
                                if ($mdp == $mdp_verif) {
                                    creer_membre($membre['num_licence'], $membre['nom'], $membre['prenom'], $membre['date_naissance'], $mdp);
                                    header('Location: connexion.php');
                                } else {
                                    $message_erreur = "Vos deux mots de passe ne sont pas identiques";
                                }
                            } else {
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
                $message_erreur = "Votre <b>Numéro de licence</b> est déjà utilisé.";
            }
        } else {
            $message_erreur = "Votre <b>Numéro de licence</b> ne contient pas exactement 5 chiffres.";
        }
    } else {
        $message_erreur = "Le champ <b>Numéro de licence</b> est vide.";
    }

    return $message_erreur;
}

function modification_membre_valide($membre, $mdp, $mdp_verif) {
    $message_erreur = "";

    if ($membre['num_licence'] != "") {
        if (preg_match("/^([0-9]{5})$/", $membre['num_licence'], $matches)) {
            if ($membre['nom'] != "") {
                if ($membre['prenom'] != "") {
                    if (date_naissance_valide($membre['date_naissance'])) {
                        if ($mdp != "" && $mdp_verif != "") {
                            if ($mdp == $mdp_verif) {
                                modifier_membre($membre['id_membre'], $membre['num_licence'], $membre['nom'], $membre['prenom'], $membre['date_naissance'], $mdp);
                                header('Location: administration-membres.php');
                                exit();
                            } else {
                                $erreur = true;
                                $message_erreur = "Vos deux mots de passe ne sont pas identiques";
                            }
                        } else {
                            modifier_membre_sans_mdp($membre['id_membre'], $membre['num_licence'], $membre['nom'], $membre['prenom'], $membre['date_naissance']);
                            header('Location: administration-membres.php');
                            exit();
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
