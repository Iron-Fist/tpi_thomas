<?php

/**
 * Création d'un concours
 * @staticvar var $query
 * @param string $intitule
 * @param string $lieu
 * @param int $nb_places
 * @param date $date_concours
 * @param date $date_limite_inscription
 */
function creer_concours($intitule, $lieu, $nb_places, $date_concours, $date_limite_inscription) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("INSERT INTO t_concours(intitule, lieu, nb_places, date_concours, date_limite_inscription) 
    VALUES (:intitule, :lieu, :nb_places, :date_concours, :date_limite_inscription)");
    }

    $query->execute([
        "intitule" => $intitule,
        "lieu" => $lieu,
        "nb_places" => $nb_places,
        "date_concours" => $date_concours,
        "date_limite_inscription" => $date_limite_inscription
    ]);
}

/**
 * Modification d'un concours
 * @param int $id_concours
 * @param string $intitule
 * @param string $lieu
 * @param int $nb_places
 * @param date $date_concours
 * @param date $date_limite_inscription
 */
function modifier_concours($id_concours, $intitule, $lieu, $nb_places, $date_concours, $date_limite_inscription) {
    $query = connectDB()->prepare("UPDATE `t_concours`
                                   SET `intitule`=:intitule, `lieu`=:lieu, `nb_places`=:nb_places, `date_concours`=:date_concours, `date_limite_inscription`=:date_limite_inscription
                                   WHERE `id_concours`=:id_concours");
    $data = array(
        "id_concours" => $id_concours,
        "intitule" => $intitule,
        "lieu" => $lieu,
        "nb_places" => $nb_places,
        "date_concours" => $date_concours,
        "date_limite_inscription" => $date_limite_inscription
    );



    $query->execute($data);
}

function supprimer_concours($id_concours){
    $query = connectDB()->prepare("DELETE FROM t_concours WHERE id_concours = ?");
    $query->execute([$id_concours]);
    $data = $query->fetch(PDO::FETCH_ASSOC);
    return $data;
}

/**
 * Création d'un tableau qui nous servira pour créer un concours
 * @return array
 */
function nouveau_concours_temporaire() {
    return array(
        "id_concours" => -1,
        "intitule" => "",
        "lieu" => "",
        "nb_places" => "",
        "date_concours" => "",
        "date_limite_inscription" => ""
    );
}

/**
 * Séléction d'un concours (déjà crée) à partir de son id
 * @param int $id_concours
 * @return array $data
 */
function ancien_concours_temporaire($id_concours) {
    $query = connectDB()->prepare("SELECT * FROM `t_concours` WHERE id_concours = ?");
    $query->execute([$id_concours]);
    $data = $query->fetch(PDO::FETCH_ASSOC);
    return $data;
}

/**
 * Vérifie si l'intitule est déjà utilisé par un autre concours
 * @staticvar var $query
 * @param string $intitule
 * @return boolean
 */
function concours_existe($intitule) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT * FROM `t_concours` WHERE intitule = ?");
    }
    $query->execute([$intitule]);

    $data = $query->fetch(PDO::FETCH_ASSOC);

    if ($data === false)
        return false;
    else
        return true;
}

/**
 * Vérifie si la date est valide et si la date limite d'inscription n'est pas déjà dépassé
 * @param date $date_concours
 * @param date $date_limite_inscription
 * @return boolean
 */
function date_concours_valide($date_concours, $date_limite_inscription) {
    if (preg_match("#^([0-9]{4})-([0-9]{2})-([0-9]{2})$#", $date_concours, $matches)) {

        if (checkdate($matches[2], $matches[3], $matches[1])) {
            if ($date_limite_inscription > date('Y-m-d')) {
                return true;
            }
        }
    }
    return false;
}

function creation_concours_valide($concours, $date_limite_inscription) {
    $message_erreur = "";

    if ($concours['intitule'] != "") {
        if (concours_existe($concours['intitule']) === false) {
            if ($concours['lieu'] != "") {
                if ($concours['nb_places'] > 0 && $concours['nb_places'] != "") {
                    if (date_concours_valide($concours['date_concours'], $date_limite_inscription)) {
                        creer_concours($concours['intitule'], $concours['lieu'], $concours['nb_places'], $concours['date_concours'], $date_limite_inscription);
                        header('Location: administration.php');
                    } else {
                        $message_erreur = "La <b>Date</b> n'est pas valide.";
                    }
                } else {
                    $message_erreur = "Le champ <b>Nombre de places</b> n'est pas composé d'un nombre suffisament élevé.";
                }
            } else {
                $message_erreur = "Le champ <b>Lieu</b> est vide.";
            }
        } else {
            $message_erreur = "L'<b>Intitule</b> est déjà utilisé.";
        }
    } else {
        $message_erreur = "Le champ <b>Intitule</b> est vide.";
    }

    return $message_erreur;
}

function modification_concours_valide($concours, $date_limite_inscription) {
    $message_erreur = "";

    if ($concours['intitule'] != "") {
        if ($concours['lieu'] != "") {
            if ($concours['nb_places'] > 0 && $concours['nb_places'] != "") {
                if (date_concours_valide($concours['date_concours'], $date_limite_inscription)) {
                    modifier_concours($concours['id_concours'], $concours['intitule'], $concours['lieu'], $concours['nb_places'], $concours['date_concours'], $date_limite_inscription);
                    header('Location: administration.php');
                } else {
                    $message_erreur = "La <b>Date</b> n'est pas valide.";
                }
            } else {
                $message_erreur = "Le champ <b>Nombre de places</b> n'est pas composé d'un nombre suffisament élevé.";
            }
        } else {
            $message_erreur = "Le champ <b>Lieu</b> est vide.";
        }
    } else {
        $message_erreur = "Le champ <b>Intitule</b> est vide.";
    }

    return $message_erreur;
}

function tableau_futur_concours($date_jour) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT * FROM `t_concours` WHERE `date_limite_inscription` > ?");
    }
    $query->execute([$date_jour]);

    if ($query->rowCount() == 0) {
        echo '<tr>';
        echo '<td colspan="5" class="text-center">Il n\'y a aucun futurs concours.</td>';
        echo '</tr>';
    } else {
        while (($data = $query->fetch(PDO::FETCH_ASSOC)) !== false) {
            echo '<tr>';
            echo '<td>' . $data['intitule'] . '</td>';
            echo '<td>' . $data['lieu'] . '</td>';
            echo '<td>' . $data['nb_places'] . '</td>';
            echo '<td>' . date_format(date_create($data['date_concours']), "l d F") . '</td>';
            echo '<td><a href="creer-modifier-concours.php?id_concours_modification=' . $data["id_concours"] . '"><span class="glyphicon glyphicon-wrench"></span></a>' . " " . '<a href="suppression-validation.php?id_concours_suppression=' . $data["id_concours"] . '"><span class="glyphicon glyphicon-trash"></span></a></td>';
            echo '</tr>';
        }
    }
}

function tableau_concours_avec_resultats() {
    
}

function tableau_concours_attente_resultats() {
    
}
