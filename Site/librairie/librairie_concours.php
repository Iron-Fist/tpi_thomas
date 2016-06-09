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

function supprimer_concours($id_concours) {
    $query = connectDB()->prepare("DELETE FROM t_concours WHERE id_concours = ?");
    $query->execute([$id_concours]);
    $data = $query->fetch(PDO::FETCH_ASSOC);
    return $data;
}

function desinscrire_membres_inscrits_concours_supprimer($id_concours) {
    $query = connectDB()->prepare("DELETE FROM t_inscrits WHERE id_concours = ?");
    $query->execute([$id_concours]);
    $data = $query->fetch(PDO::FETCH_ASSOC);
    return $data;
}

function inscription_concours($id_concours, $id_membre) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("INSERT INTO t_inscrits(id_concours, id_membre) 
    VALUES (:id_concours, :id_membre)");
    }

    $query->execute([
        "id_concours" => $id_concours,
        "id_membre" => $id_membre
    ]);
}

function desinscription_concours($id_concours, $id_membre) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("DELETE FROM `t_inscrits` WHERE id_concours = ? AND id_membre = ?");
    }

    $query->execute([$id_concours, $id_membre]);
}

/**
 * Création d'un tableau qui nous servira pour créer un concours
 * @return array
 */
function charger_nouveau_concours() {
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
function charger_donnees_concours($id_concours) {
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

function est_inscrit($id_concours, $id_membre) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT * FROM `t_inscrits` WHERE id_concours = ? AND id_membre = ?");
    }
    $query->execute([$id_concours, $id_membre]);

    if ($query->rowCount() > 0)
        return true;
    else
        return false;
}

function nb_inscrits($id_concours) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT COUNT(id_membre) AS nb_inscrits FROM `t_inscrits` WHERE id_concours = ?");
    }
    $query->execute([$id_concours]);

    return $query->fetch(PDO::FETCH_ASSOC)['nb_inscrits'];
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
        $query = connectDB()->prepare("SELECT * FROM `t_concours` WHERE `date_concours` > ?");
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
            echo '<td>' . ($data['nb_places'] - nb_inscrits($data['id_concours'])) . " / " . $data['nb_places'] . '</td>';
            echo '<td>' . date_format(date_create($data['date_concours']), "l d F") . '</td>';
            echo '<td><a href="creer-modifier-concours.php?id_concours_modification=' . $data["id_concours"] . '"><span class="glyphicon glyphicon-wrench"></span></a>'
            . " " . '<a href="suppression-validation-inscription.php?id_concours_suppression=' . $data["id_concours"] . '"><span class="glyphicon glyphicon-trash"></span></a></td>';
            echo '</tr>';
        }
    }
}

function tableau_futur_concours_inscription($date_jour, $id_membre) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT * FROM `t_concours` WHERE `date_concours` >= ?");
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
            echo '<td>' . ($data['nb_places'] - nb_inscrits($data['id_concours'])) . " / " . $data['nb_places'] . '</td>';
            echo '<td>' . date_format(date_create($data['date_concours']), "l d F") . '</td>';
            echo '<td>' . date_format(date_create($data['date_limite_inscription']), "l d F") . '</td>';
            if ($id_membre != -1) {
                if ($data['date_limite_inscription'] >= $date_jour) {
                    if (est_inscrit($data['id_concours'], $id_membre)) {
                        echo '<td><a href="suppression-validation-inscription.php?id_concours_desinscription=' . $data["id_concours"] . '">Désinscription</a></td>';
                    } else if(($data['nb_places'] - nb_inscrits($data['id_concours'])) > 0) {
                        echo '<td><a href="suppression-validation-inscription.php?id_concours_inscription=' . $data["id_concours"] . '">Inscription</a></td>';
                    }
                    else{
                        echo '<td>Plus de places</td>';
                    }
                } else {
                    echo '<td>Inscription fermée</td>';
                }
            }
            echo '</tr>';
        }
    }
}

function tableau_futur_concours_inscrits($id_membre, $date_jour) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT c.id_concours, c.intitule, c.lieu, c.nb_places, c.date_concours, c.date_limite_inscription FROM t_concours as c, t_inscrits as i WHERE c.id_concours = i.id_concours AND i.id_membre = ? AND c.date_concours >= ?");
    }

    $query->execute([$id_membre, $date_jour]);

    if ($query->rowCount() == 0) {
        echo '<tr>';
        echo '<td colspan="6" class="text-center">Vous n\'êtes inscrit à aucun concours.</td>';
        echo '</tr>';
    } else {
        while (($data = $query->fetch(PDO::FETCH_ASSOC)) !== false) {

            echo '<tr>';
            echo '<td>' . $data['intitule'] . '</td>';
            echo '<td>' . $data['lieu'] . '</td>';
            echo '<td>' . ($data['nb_places'] - nb_inscrits($data['id_concours'])) . " / " . $data['nb_places'] . '</td>';
            echo '<td>' . date_format(date_create($data['date_concours']), "l d F") . '</td>';
            echo '<td><a href="suppression-validation-inscription.php?id_concours_desinscription=' . $data["id_concours"] . '">Désinscription</a></td>';
            echo '</tr>';
        }
    }
}

function tableau_concours_passe_inscrits($id_membre, $date_jour) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT c.id_concours, c.intitule, c.lieu, c.nb_places, c.date_concours, c.date_limite_inscription FROM t_concours as c, t_inscrits as i WHERE c.id_concours = i.id_concours AND i.id_membre = ? AND c.date_concours <= ?");
    }

    $query->execute([$id_membre, $date_jour]);

    if ($query->rowCount() == 0) {
        echo '<tr>';
        echo '<td colspan="6" class="text-center">Vous n\'avez participez à aucun concours.</td>';
        echo '</tr>';
    } else {
        while (($data = $query->fetch(PDO::FETCH_ASSOC)) !== false) {

            echo '<tr>';
            echo '<td>' . $data['intitule'] . '</td>';
            echo '<td>' . $data['lieu'] . '</td>';
            echo '<td>' . date_format(date_create($data['date_concours']), "l d F") . '</td>';
            if (resultat_remis($data['id_concours']))
                echo '<td><a href="rediger-consulter-resultats.php?id_concours_consulte=' . $data["id_concours"] . '">Consulter</a></td>';
            else
                echo '<td>Pas encore remis</td>';
            echo '</tr>';
        }
    }
}

function tableau_remise_resultats_concours($date_jour) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT DISTINCT intitule, lieu, date_concours, c.id_concours FROM t_concours c, t_inscrits i WHERE c.id_concours = i.id_concours AND date_concours < ? AND score = -1");
    }
    $query->execute([$date_jour]);

    if ($query->rowCount() == 0) {
        echo '<tr>';
        echo '<td colspan="5" class="text-center">Il n\'y a aucun concours en attente de résultat.</td>';
        echo '</tr>';
    } else {
        while (($data = $query->fetch(PDO::FETCH_ASSOC)) !== false) {
            echo '<tr>';
            echo '<td>' . $data['intitule'] . '</td>';
            echo '<td>' . $data['lieu'] . '</td>';
            echo '<td>' . date_format(date_create($data['date_concours']), "l d F") . '</td>';
            echo '<td><a href="rediger-consulter-resultats.php?id_concours_resultats=' . $data["id_concours"] . '">Remettre</a></td>';
            echo '</tr>';
        }
    }
}

function tableau_modifier_resultats_concours($date_jour) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT DISTINCT intitule, lieu, date_concours, c.id_concours FROM t_concours c, t_inscrits i WHERE c.id_concours = i.id_concours AND date_concours < ? AND score <> -1");
    }
    $query->execute([$date_jour]);

    if ($query->rowCount() == 0) {
        echo '<tr>';
        echo '<td colspan="5" class="text-center">Il n\'y a aucun concours en attente de résultat.</td>';
        echo '</tr>';
    } else {
        while (($data = $query->fetch(PDO::FETCH_ASSOC)) !== false) {
            echo '<tr>';
            echo '<td>' . $data['intitule'] . '</td>';
            echo '<td>' . $data['lieu'] . '</td>';
            echo '<td>' . date_format(date_create($data['date_concours']), "l d F") . '</td>';
            echo '<td><a href="rediger-consulter-resultats.php?id_concours_resultats=' . $data["id_concours"] . '">Modifier</a></td>';
            echo '</tr>';
        }
    }
}

function liste_participant($id_concours) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT i.id_membre, score, num_licence, nom, prenom FROM t_inscrits i, t_membres m WHERE i.id_membre = m.id_membre AND id_concours = ?");
    }

    $query->execute([$id_concours]);

    while (($data = $query->fetch(PDO::FETCH_ASSOC)) !== false) {
        echo '<tr>';
        echo '<td>' . $data['num_licence'] . '</td>';
        echo '<td>' . $data['nom'] . '</td>';
        echo '<td>' . $data['prenom'] . '</td>';
        if ($data['score'] != -1)
            echo '<td><input type="number" name="' . $data['id_membre'] . '_score" value="' . $data['score'] . '" min="0" max="600"></td>';
        else
            echo '<td><input type="number" name="' . $data['id_membre'] . '_score" min="0" max="600"></td>';
        echo '</tr>';
    }
}

function consulter_concours($id_concours) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT i.id_membre, score, num_licence, nom, prenom FROM t_inscrits i, t_membres m WHERE i.id_membre = m.id_membre AND id_concours = ?");
    }

    $query->execute([$id_concours]);

    while (($data = $query->fetch(PDO::FETCH_ASSOC)) !== false) {
        echo '<tr>';
        echo '<td>' . $data['num_licence'] . '</td>';
        echo '<td>' . $data['nom'] . '</td>';
        echo '<td>' . $data['prenom'] . '</td>';
        echo '<td>' . $data['score'] . '</td>';
        echo '</tr>';
    }
}

function mise_a_jour_score($id_membre, $score, $id_concours) {
    $query = connectDB()->prepare("UPDATE t_inscrits SET score=:score WHERE id_membre=:id_membre AND id_concours=:id_concours");

    $data = array(
        "score" => $score,
        "id_membre" => $id_membre,
        "id_concours" => $id_concours
    );

    $query->execute($data);
}

function resultat_remis($id_concours) {
    static $query = null;

    if ($query == null) {
        $query = connectDB()->prepare("SELECT * FROM `t_inscrits` WHERE id_concours = ? AND score <> -1");
    }
    $query->execute([$id_concours]);

    $data = $query->fetch(PDO::FETCH_ASSOC);

    if ($data === false)
        return false;
    else
        return true;
}
